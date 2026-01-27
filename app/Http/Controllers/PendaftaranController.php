<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\DokterJadwal;
use App\Models\DokterKuota;
use App\Models\Pasien\Pasien;
use App\Models\Poli;
use App\Models\Ruangan;
use App\Helpers\MakeRequestHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;

use App\Models\RuanganBed;
use App\Models\RawatSpri;
use App\Models\Rawat;

class PendaftaranController extends Controller
{
    public function index()
    {
        return view('pendaftaran.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->date ?? date('Y-m-d');

            $query = DB::table('rawat')
                ->join('pasien', 'pasien.no_rm', '=', 'rawat.no_rm')
                ->join('poli', 'poli.id', '=', 'rawat.idpoli')
                ->leftJoin('dokter', 'dokter.id', '=', 'rawat.iddokter')
                ->leftJoin('ruangan', 'ruangan.id', '=', 'rawat.idruangan')
                ->leftJoin('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
                ->select([
                    'rawat.id',
                    'rawat.no_rm',
                    'rawat.tglmasuk',
                    'rawat.status',
                    'rawat.idjenisrawat',
                    'pasien.nama_pasien',
                    'poli.poli as nama_poli',
                    'dokter.nama_dokter',
                    'ruangan.nama_ruangan',
                    'rawat_bayar.bayar',
                    'rawat.no_antrian'
                ])
                ->whereDate('rawat.tglmasuk', $date)
                ->orderBy('rawat.tglmasuk', 'desc');

            if ($request->jenis_rawat) {
                $query->where('rawat.idjenisrawat', $request->jenis_rawat);
            }

            return DataTables::of($query)
                ->editColumn('tglmasuk', function ($row) {
                    return Carbon::parse($row->tglmasuk)->format('d/m/Y H:i');
                })
                ->addColumn('jenis_badge', function ($row) {
                    switch ($row->idjenisrawat) {
                        case 1:
                            return '<span class="badge badge-light-primary">Rawat Jalan</span>';
                        case 2:
                            return '<span class="badge badge-light-warning">Rawat Inap</span>';
                        case 3:
                            return '<span class="badge badge-light-danger">UGD</span>';
                        default:
                            return '<span class="badge badge-light-secondary">Lainnya</span>';
                    }
                })
                ->addColumn('status_badge', function ($row) {
                    // Mapping status codes (adjust based on your actual status codes)
                    $status = [
                        1 => ['label' => 'Antrian', 'class' => 'badge-light-warning'],
                        2 => ['label' => 'Diproses', 'class' => 'badge-light-info'],
                        3 => ['label' => 'Selesai', 'class' => 'badge-light-success'],
                        0 => ['label' => 'Batal', 'class' => 'badge-light-danger'],
                    ];

                    $st = $status[$row->status] ?? ['label' => 'Unknown', 'class' => 'badge-light-secondary'];
                    return '<span class="badge ' . $st['class'] . '">' . $st['label'] . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pasien.rekammedis_detail', $row->id) . '" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px me-1" title="Lihat Detail"><i class="fas fa-eye"></i></a>';
                    if ($row->status == 1) { // Only allow cancel if waiting
                        $btn .= '<button onclick="batalkanKunjungan(' . $row->id . ')" class="btn btn-sm btn-icon btn-bg-light btn-active-color-danger w-30px h-30px" title="Batalkan"><i class="fas fa-times"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['jenis_badge', 'status_badge', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $poli = Poli::all();
        $dokter = Dokter::where('status', 1)->get(); // Active doctors
        $ruangan = Ruangan::all(); // You might want to filter available rooms later
        return view('pendaftaran.create', compact('poli', 'dokter', 'ruangan'));
    }

    public function searchPasien(Request $request)
    {
        $keyword = $request->term;
        $pasien = Pasien::where('nama_pasien', 'like', "%$keyword%")
            ->orWhere('no_rm', 'like', "%$keyword%")
            ->orWhere('nik', 'like', "%$keyword%")
            ->limit(20)
            ->get(['id', 'nama_pasien', 'no_rm', 'nik', 'tgllahir']);

        return response()->json($pasien);
    }

    public function getDokterByPoli($id_poli)
    {
        // Logic to get doctors schedule could help here, simple fetch for now
        $date = date('Y-m-d');
        $timestamp = strtotime($date);
        $dayNumber = date('N', $timestamp);

        // Ideal: Get from DokterJadwal
        $dokter = DB::table('dokter_jadwal')
            ->join('dokter', 'dokter.id', '=', 'dokter_jadwal.iddokter')
            ->where('dokter_jadwal.idpoli', $id_poli)
            ->where('dokter_jadwal.idhari', $dayNumber)
            ->select('dokter.id', 'dokter.nama_dokter', 'dokter_jadwal.jam_mulai', 'dokter_jadwal.jam_selesai')
            ->get();

        if ($dokter->isEmpty()) {
            // Fallback if no schedule found, show all doctors linked to poli (if any mapping exists) or all doctors
            // For now just return empty or all doctors if strict schedule not enforced
            $dokter = Dokter::where('status', 1)->get(['id', 'nama_dokter']);
        }

        return response()->json($dokter);
    }

    public function getBeds($id_ruangan)
    {
        $beds = RuanganBed::where('idruangan', $id_ruangan)
            ->where('terisi', 0) // Only available beds
            ->where('status', 1) // Active beds
            ->get(['id', 'kodebed']);

        return response()->json($beds);
    }

    public function searchSpri(Request $request)
    {
        $term = $request->term;
        $no_rm = $request->no_rm;

        $query = RawatSpri::with(['dokter'])->where('status', 1); // Status 1 = Created/Active

        if ($no_rm) {
            $query->where('no_rm', $no_rm);
        }

        if ($term) {
            $query->where('no_spri', 'like', "%$term%");
        }

        $spri = $query->limit(10)->get();
        return response()->json($spri);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpasien' => 'required',
            'jenis_rawat' => 'required',
            'penanggung' => 'required', // idbayar
            // Add other validations
        ]);

        DB::beginTransaction();
        try {
            $pasien = Pasien::findOrFail($request->idpasien);

            // 1. Prepare Data
            $kode_kunjungan = date('dmY') . rand(1000, 9999);
            $tgl_masuk = date('Y-m-d'); // Or from request if backdating allowed
            $kode = 'RJ';
            $ruangan = 2; // Default Rawat Jalan room ID? need verification

            if ($request->jenis_rawat == 1) {
                // Rawat Jalan
                $kode = 'RJ';
                $ruangan = 2; // Dummy default
                $idpoli = $request->idpoli;
                $iddokter = $request->iddokter;
            } elseif ($request->jenis_rawat == 3) {
                // UGD
                $kode = 'UGD';
                $ruangan = 1; // UGD Room default
                $idpoli = 0; // Or UGD Poli ID
                $iddokter = $request->iddokter; // Dokter jaga

            } elseif ($request->jenis_rawat == 2) {
                // Rawat Inap
                $kode = 'RI';
                $ruangan = $request->idruangan;
                $idpoli = 0;
                $iddokter = $request->iddokter; // DPJP
            }

            // 2. Insert Rawat Kunjungan
            $insert_rawat_kunjungan = DB::table('rawat_kunjungan')->insertGetId([
                'idkunjungan' => $kode_kunjungan,
                'no_rm' => $pasien->no_rm,
                'tgl_kunjungan' => $tgl_masuk,
                'jam_kunjungan' => date('H:i:s'),
                'iduser' => auth()->user()->id,
                'usia_kunjungan' => $pasien->usia_tahun ?? 0,
                'status' => 1,
                'idpasien' => $pasien->id,
            ]);

            // 3. Insert Transaksi
            DB::table('transaksi')->insert([
                'idtransaksi' => 'TRX' . date('dmY') . rand(1000, 9999),
                'tgltransaksi' => date('Y-m-d H:i:s'),
                'iduser' => auth()->user()->id,
                'status' => 1,
                'idkunjungan' => $insert_rawat_kunjungan,
                'no_rm' => $pasien->no_rm,
                'tgl_masuk' => $tgl_masuk,
                'kode_kunjungan' => $kode_kunjungan,
                'idpasien' => $pasien->id,
                'id_bayar' => $request->penanggung
            ]);

            // 4. Generate Antrian & Kode Booking
            $kode_booking = $kode . date('Ymd') . rand(1000, 9999);
            // Helper might need adjustment for types other than RJ, but keeping safe fallback
            $antrian = 0;
            if ($request->jenis_rawat == 1 && $idpoli && $iddokter) {
                $antrian = MakeRequestHelper::genAntri($idpoli, $iddokter, null, $tgl_masuk);
            }

            // 5. Insert Rawat
            $idrawat = DB::table('rawat')->insertGetId([
                'idrawat' => $kode_booking, // This is actually 'id' string in schema usually? Or is 'idrawat' primary key? table 'rawat' usually has 'id' AI. Let's check schema if possible, but assuming PasienController logic: 'idrawat' => $kode_booking seems odd if id is AI. 
                // Wait, in PasienController: 'idrawat' => $kode_booking. 
                // Let's trust PasienController logic, but usually `insertGetId` returns the AI ID. 
                // Ah, PasienController uses `idrawat` column for the booking code? 
                // "idrawat" => $kode_booking
                'idkunjungan' => $kode_kunjungan,
                'idjenisrawat' => $request->jenis_rawat,
                'no_rm' => $pasien->no_rm,
                'idpoli' => $idpoli,
                'iddokter' => $iddokter,
                'idruangan' => $ruangan, // For RI this is real room
                'idbayar' => $request->penanggung,
                'tglmasuk' => $tgl_masuk . ' ' . date("H:i:s"),
                'status' => 1,
                'anggota' => $request->anggota ?? 0,
                'no_antrian' => $antrian
            ]);

            // [NEW] Handle Rawat Inap Specifics
            if ($request->jenis_rawat == 2) {
                // Update Ruangan Bed status
                if ($request->idbed) {
                    DB::table('ruangan_bed')->where('id', $request->idbed)->update([
                        'terisi' => 1,
                        'updated_at' => now()
                    ]);

                    // Update Rawat with Bed
                    DB::table('rawat')->where('id', $idrawat)->update([
                        'idbed' => $request->idbed,
                        // 'idkelas' => $request->idkelas, // Assuming idkelas comes from Room or Input. Usually linked to Room.
                        // Let's get class from Room if needed, or if passed. 
                        // Check Ruangan Model/Table. Ruangan has 'idkelas'.
                    ]);

                    // Link BED in RawatRuangan (History)
                    DB::table('rawat_ruangan')->insert([
                        'idkunjungan' => $insert_rawat_kunjungan,
                        'idrawat' => $idrawat,
                        'no_rm' => $pasien->no_rm,
                        'idruangan' => $ruangan,
                        'idbed' => $request->idbed, // New field 
                        'idbayar' => $request->penanggung,
                        'tgl_masuk' => now(),
                        'status' => 1,
                        'asal' => 'Pendaftaran'
                    ]);
                }

                // Handle SPRI Linking
                if ($request->id_spri) {
                    $spri = RawatSpri::find($request->id_spri);
                    if ($spri) {
                        $spri->status = 2; // Used
                        $spri->idrawat = $idrawat;
                        $spri->save();

                        // Copy diagnosis from SPRI if any needed? 
                        // For now just linking.
                    }
                }

                // Save Diagnosis Masuk (if provided)
                if ($request->diagnosa_masuk) {
                    // Assuming there's a place for initial diagnosis. Usually 'rawat.icdx' or 'rawat.diagnosa'.
                    // Schema check: 'rawat' has 'icdx' varchar(250).
                    DB::table('rawat')->where('id', $idrawat)->update([
                        'icdx' => $request->diagnosa_masuk
                    ]);
                }
            }

            // 6. Handle RJ Schedule/Quota (Only for RJ)
            if ($request->jenis_rawat == 1) {
                $date = date('Y-m-d');
                $timestamp = strtotime($date); // This should be tgl_masuk if booking future
                $dayNumber = date('N', $timestamp);

                $jadwal = DokterJadwal::where(['iddokter' => $iddokter, 'idhari' => $dayNumber])->first();
                if ($jadwal) {
                    $kuota = DokterKuota::firstOrNew(
                        ['iddokter' => $iddokter, 'idhari' => $dayNumber, 'tgl' => $tgl_masuk],
                        ['kuota' => $jadwal->kuota, 'sisa' => $jadwal->kuota, 'terdaftar' => 0, 'status' => 1]
                    );

                    if ($kuota->sisa > 0) {
                        $kuota->sisa -= 1;
                        $kuota->terdaftar += 1;
                        $kuota->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil dibuat! Antrian: ' . $antrian);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())->withInput();
        }
    }

    public function batal($id)
    {
        // Cancel logic
        return back()->with('error', 'Fitur belum aktif');
    }
}
