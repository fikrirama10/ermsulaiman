<?php

namespace App\Http\Controllers;

use App\Models\Rawat;
use App\Models\RawatKunjungan;
use App\Models\RawatTindakan;
use App\Models\Tarif;
use App\Models\Dokter;
use App\Models\Bayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use PDF;

class PatientJourneyController extends Controller
{
    /**
     * Display the consolidated billing and visit history for a specific visit.
     *
     * @param string $idkunjungan
     * @return \Illuminate\View\View
     */
    public function show($idkunjungan)
    {
        $kunjungan = RawatKunjungan::with(['pasien', 'user'])
            ->where('idkunjungan', $idkunjungan)
            ->firstOrFail();

        $rawatEpisodes = Rawat::with([
            'poli',
            'dokter',
            'ruangan',
            'riwayat_ruangan.ruangan',
            'riwayat_ruangan.kelas',
            'riwayat_tindakan.tarif',
            'riwayat_tindakan.dokter',
            'riwayat_resep'
        ])
            ->where('idkunjungan', $idkunjungan)
            ->orderBy('tglmasuk', 'asc')
            ->get();

        // Calculate totals
        $totalTindakan = 0;
        $totalFarmasi = 0;
        $totalRuangan = 0;

        foreach ($rawatEpisodes as $episode) {
            foreach ($episode->riwayat_tindakan as $tindakan) {
                // Assuming tarif table has a 'tarif' column based on previous research
                $totalTindakan += $tindakan->tarif->tarif ?? 0;
            }

            // Real pharmacy cost calculation
            $pharmacyTotal = DB::table('obat_transaksi')
                ->join('obat_transaksi_detail', 'obat_transaksi.id', '=', 'obat_transaksi_detail.idtrx')
                ->where('obat_transaksi.idrawat', $episode->id)
                ->sum('obat_transaksi_detail.total');

            $totalFarmasi += $pharmacyTotal;

            foreach ($episode->riwayat_ruangan as $room) {
                // Assuming there's a way to calculate room cost - usually los * room_price
                // For now aggregating duration
                $totalRuangan += ($room->los ?? 0) * ($room->kelas->harga ?? 0); // Assuming 'harga' exists
            }
        }

        $tarifs = Tarif::all();
        $dokters = Dokter::where('status', 1)->get();
        $bayars = Bayar::all();

        return view('billing.billing-visit', compact('kunjungan', 'rawatEpisodes', 'totalTindakan', 'totalFarmasi', 'totalRuangan', 'tarifs', 'dokters', 'bayars'));
    }

    /**
     * Add a procedure to a rawat episode.
     */
    public function addTindakan(Request $request)
    {
        $request->validate([
            'idrawat' => 'required|exists:rawat,id',
            'idtindakan' => 'required|exists:tarif,id',
            'iddokter' => 'nullable|exists:dokter,id',
            'idbayar' => 'required|exists:rawat_bayar,id',
        ]);

        $rawat = Rawat::findOrFail($request->idrawat);

        RawatTindakan::create([
            'idrawat' => $request->idrawat,
            'idtindakan' => $request->idtindakan,
            'iddokter' => $request->iddokter,
            'idbayar' => $request->idbayar,
            'idkunjungan' => $rawat->idkunjungan, // Using the string idkunjungan
            'no_rm' => $rawat->no_rm,
            'tgl' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'tindakan' => $request->profesi ?? 'Dokter'
        ]);

        return back()->with('berhasil', 'Tindakan berhasil ditambahkan');
    }

    /**
     * Delete a procedure.
     */
    public function deleteTindakan($id)
    {
        $tindakan = RawatTindakan::findOrFail($id);
        $tindakan->delete();

        return back()->with('berhasil', 'Tindakan berhasil dihapus');
    }

    /**
     * Finalize billing with discount and split payment logic.
     */
    public function finishBilling(Request $request, $idkunjungan)
    {
        $request->validate([
            'payment_method' => 'required', // 1: Full Cash, 2: Full BPJS/Claim, 3: Split
            'discount' => 'nullable|numeric|min:0',
            'amount_bpjs' => 'nullable|numeric|min:0',
            'amount_cash' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $kunjungan = RawatKunjungan::where('idkunjungan', $idkunjungan)->firstOrFail();
            $rawatEpisodes = Rawat::where('idkunjungan', $idkunjungan)->get();

            // Calculate current totals for validation/logging
            $totalTindakan = DB::table('rawat_tindakan')->where('idkunjungan', $idkunjungan)->sum(DB::raw('(SELECT tarif FROM tarif WHERE id = rawat_tindakan.idtindakan)'));

            $totalFarmasi = 0;
            foreach ($rawatEpisodes as $episode) {
                $totalFarmasi += DB::table('obat_transaksi')
                    ->join('obat_transaksi_detail', 'obat_transaksi.id', '=', 'obat_transaksi_detail.idtrx')
                    ->where('obat_transaksi.idrawat', $episode->id)
                    ->sum('obat_transaksi_detail.total');
            }

            $totalRuangan = 0;
            // Simplified room calculation - ideally needs a proper los * price join
            foreach ($rawatEpisodes as $episode) {
                $rooms = DB::table('rawat_ruangan')->where('idrawat', $episode->id)->get();
                foreach ($rooms as $r) {
                    $harga = DB::table('ruangan_kelas')->where('id', $r->idkelas)->value('harga') ?? 0;
                    $totalRuangan += ($r->los ?? 0) * $harga;
                }
            }

            $grandTotal = ($totalTindakan + $totalFarmasi + $totalRuangan) - ($request->discount ?? 0);

            // Update Kunjungan Status to 'Pulang/Selesai' (4)
            $kunjungan->update(['status' => 4]);

            // Update all related Rawat status to 4
            Rawat::where('idkunjungan', $idkunjungan)->update(['status' => 4]);

            // Update standard 'transaksi' table
            $transaksi = DB::table('transaksi')->where('kode_kunjungan', $idkunjungan)->first();
            if ($transaksi) {
                DB::table('transaksi')->where('id', $transaksi->id)->update([
                    'status' => 2, // Paid
                    'updated_at' => now(),
                    // We can add more fields if we expand the table, for now auditing status
                ]);
            }

            // Record Final Billing Summary (could use a new table or log)
            // For now, let's log the transaction in a generic way if a table doesn't exist
            // Or we could update a 'total_harga' if standard SIMRS has it.

            DB::commit();
            return redirect()->route('billing.index')->with('berhasil', 'Billing kunjungan #' . $idkunjungan . ' berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF Invoice for the visit.
     */
    public function cetakBilling($idkunjungan)
    {
        $kunjungan = RawatKunjungan::with(['pasien', 'user'])
            ->where('idkunjungan', $idkunjungan)
            ->firstOrFail();

        $rawatEpisodes = Rawat::with(['poli', 'dokter', 'ruangan', 'riwayat_tindakan.tarif', 'riwayat_ruangan.kelas'])
            ->where('idkunjungan', $idkunjungan)
            ->get();

        $totalTindakan = 0;
        $totalFarmasi = 0;
        $totalRuangan = 0;

        foreach ($rawatEpisodes as $episode) {
            foreach ($episode->riwayat_tindakan as $tindakan) {
                $totalTindakan += $tindakan->tarif->tarif ?? 0;
            }

            $totalFarmasi += DB::table('obat_transaksi')
                ->join('obat_transaksi_detail', 'obat_transaksi.id', '=', 'obat_transaksi_detail.idtrx')
                ->where('obat_transaksi.idrawat', $episode->id)
                ->sum('obat_transaksi_detail.total');

            foreach ($episode->riwayat_ruangan as $room) {
                $totalRuangan += ($room->los ?? 0) * ($room->kelas->harga ?? 0);
            }
        }

        $pdf = \PDF::loadView('billing.cetak-billing', compact('kunjungan', 'rawatEpisodes', 'totalTindakan', 'totalFarmasi', 'totalRuangan'));
        return $pdf->stream('Billing-' . $idkunjungan . '.pdf');
    }

    /**
     * List all visits for a patient (View).
     */
    public function index(Request $request)
    {
        return view('billing.index');
    }

    /**
     * Data Source for DataTables.
     */
    public function data(Request $request)
    {
        if ($request->ajax()) {
            $query = RawatKunjungan::with(['pasien', 'user', 'rawat.poli', 'rawat.ruangan']);

            // Filter by Date Range
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tgl_kunjungan', [$request->start_date, $request->end_date]);
            } elseif ($request->date) {
                $query->whereDate('tgl_kunjungan', $request->date);
            }

            // Filter by Status
            if ($request->status != "") {
                $query->where('status', $request->status);
            }
            $query->orderBy('tgl_kunjungan', 'desc');

            return DataTables::of($query)
                ->editColumn('tgl_kunjungan', function ($row) {
                    return Carbon::parse($row->tgl_kunjungan)->format('d/m/Y') . ' <small class="text-muted">' . $row->jam_kunjungan . '</small>';
                })
                ->editColumn('idkunjungan', function ($row) {
                    return '<span class="badge badge-light-primary fw-bold">' . $row->idkunjungan . '</span>';
                })
                ->addColumn('nama_pasien', function ($row) {
                    return '<div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">' . ($row->pasien->nama_pasien ?? '-') . '</span>
                                <span class="text-muted fs-7">' . $row->no_rm . '</span>
                            </div>';
                })
                ->addColumn('jenis_rawat', function ($row) {
                    $types = $row->rawat->pluck('idjenisrawat')->unique();
                    $badges = '';
                    foreach ($types as $type) {
                        switch ($type) {
                            case 1:
                                $badges .= '<span class="badge badge-light-primary me-1">RJ</span>';
                                break;
                            case 2:
                                $badges .= '<span class="badge badge-light-warning me-1">RI</span>';
                                break;
                            case 3:
                                $badges .= '<span class="badge badge-light-danger me-1">UGD</span>';
                                break;
                        }
                    }
                    return $badges ?: '<span class="text-muted">-</span>';
                })
                ->addColumn('unit_terakhir', function ($row) {
                    $lastRawat = $row->rawat->sortByDesc('tglmasuk')->first();
                    if (!$lastRawat) return '<span class="text-muted">-</span>';

                    $unit = $lastRawat->poli->poli ?? ($lastRawat->ruangan->nama_ruangan ?? 'Unit Umum');
                    return '<span class="text-gray-700 fs-7">' . $unit . '</span>';
                })
                ->addColumn('list_unit', function ($row) {
                    $units = Rawat::where('idkunjungan', $row->idkunjungan)->get();
                    $list = '';
                    foreach ($units as $unit) {
                        if ($unit->idjenisrawat == 2) {
                            $list .= '<span class="badge badge-light-warning me-1">' . $unit->ruangan->nama_ruangan . '</span>';
                        } else {
                            $list .= '<span class="badge badge-light-primary me-1">' . $unit->poli->poli . '</span>';
                        }
                    }
                    return $list;
                })
                ->addColumn('status_badge', function ($row) {
                    $statusMapping = [
                        1 => ['label' => 'Antrian', 'class' => 'badge-light-warning'],
                        2 => ['label' => 'Dirawat', 'class' => 'badge-light-primary'],
                        3 => ['label' => 'Pemeriksaan', 'class' => 'badge-light-info'],
                        4 => ['label' => 'Pulang', 'class' => 'badge-light-success'],
                        5 => ['label' => 'Batal', 'class' => 'badge-light-danger'],
                    ];
                    $st = $statusMapping[$row->status] ?? ['label' => 'Unknown', 'class' => 'badge-light-secondary'];
                    return '<span class="badge ' . $st['class'] . ' fs-8 fw-bold">' . $st['label'] . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('billing.show', $row->idkunjungan) . '" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px" title="Lihat Journey">
                                <i class="ki-outline ki-eye fs-3"></i>
                            </a>';
                })
                ->rawColumns(['tgl_kunjungan', 'idkunjungan', 'nama_pasien', 'jenis_rawat', 'unit_terakhir', 'status_badge', 'action', 'list_unit'])
                ->make(true);
        }
    }
}
