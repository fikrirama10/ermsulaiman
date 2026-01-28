<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Poli;
use App\Models\Dokter;

class MonitoringPendaftaranController extends Controller
{
    public function index(Request $request)
    {
        // Default range: Today to 1 month ahead
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d', strtotime('+1 month'));

        // If Ajax request for stats, return JSON
        if ($request->ajax() && $request->has('get_stats')) {
            return response()->json($this->getStats($startDate, $endDate));
        }

        $stats = $this->getStats($startDate, $endDate);
        $polis = Poli::get();
        $dokters = Dokter::where('status', 1)->get();
        $bayars = DB::table('rawat_bayar')->get();

        return view('monitoring.pendaftaran.index', compact('stats', 'polis', 'dokters', 'bayars', 'startDate', 'endDate'));
    }

    private function getStats($startDate, $endDate)
    {
        return [
            'total' => DB::table('rawat')
                ->whereDate('tglmasuk', '>=', $startDate)
                ->whereDate('tglmasuk', '<=', $endDate)
                ->where('status', '<>', 0)->count(),
            'online' => DB::table('rawat')
                ->whereDate('tglmasuk', '>=', $startDate)
                ->whereDate('tglmasuk', '<=', $endDate)
                ->where('antrian_online', 1)
                ->where('status', '<>', 0)->count(),
            'bpjs' => DB::table('rawat')->join('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
                ->whereDate('tglmasuk', '>=', $startDate)
                ->whereDate('tglmasuk', '<=', $endDate)
                ->where('status', '<>', 0)
                ->where('rawat_bayar.bayar', 'like', '%BPJS%')->count(),
            'umum' => DB::table('rawat')->join('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
                ->whereDate('tglmasuk', '>=', $startDate)
                ->whereDate('tglmasuk', '<=', $endDate)
                ->where('status', '<>', 0)
                ->where('rawat_bayar.bayar', 'not like', '%BPJS%')->count(),
        ];
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->start_date ?? date('Y-m-d');
            $endDate = $request->end_date ?? date('Y-m-d', strtotime('+1 month'));

            $query = DB::table('rawat')
                ->join('pasien', 'pasien.no_rm', '=', 'rawat.no_rm')
                ->join('poli', 'poli.id', '=', 'rawat.idpoli')
                ->leftJoin('dokter', 'dokter.id', '=', 'rawat.iddokter')
                ->leftJoin('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
                ->select([
                    'rawat.id',
                    'rawat.no_rm',
                    'rawat.tglmasuk',
                    'rawat.status',
                    'rawat.antrian_online',
                    'rawat.no_antrian',
                    'pasien.nama_pasien',
                    'poli.poli as nama_poli',
                    'dokter.nama_dokter',
                    'rawat_bayar.bayar',
                ])
                ->whereDate('rawat.tglmasuk', '>=', $startDate)
                ->whereDate('rawat.tglmasuk', '<=', $endDate)
                ->where('rawat.idjenisrawat', 1)
                ->where('rawat.status', '<>', 0);

            // Filters
            if ($request->filter == 'online') {
                $query->where('rawat.antrian_online', 1);
            }
            if ($request->idpoli) {
                $query->where('rawat.idpoli', $request->idpoli);
            }
            if ($request->iddokter) {
                $query->where('rawat.iddokter', $request->iddokter);
            }
            if ($request->idbayar) {
                $query->where('rawat.idbayar', $request->idbayar);
            }
            if ($request->status) {
                $query->where('rawat.status', $request->status);
            }

            return DataTables::of($query)
                ->editColumn('tglmasuk', function ($row) {
                    return Carbon::parse($row->tglmasuk)->format('d/m/Y H:i');
                })
                ->addColumn('online_status', function ($row) {
                    return $row->antrian_online == 1
                        ? '<span class="badge badge-light-success">Online</span>'
                        : '<span class="badge badge-light-secondary">Onsite</span>';
                })
                ->addColumn('status_badge', function ($row) {
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
                    return '<button class="btn btn-sm btn-light btn-active-light-primary" onclick="openDetail(' . $row->id . ')">
                                <i class="ki-outline ki-eye fs-2"></i> Detail
                            </button>';
                })
                ->rawColumns(['online_status', 'status_badge', 'action'])
                ->make(true);
        }
    }

    public function show($id)
    {
        $rawat = DB::table('rawat')
            ->join('pasien', 'pasien.no_rm', '=', 'rawat.no_rm')
            ->join('poli', 'poli.id', '=', 'rawat.idpoli')
            ->leftJoin('dokter', 'dokter.id', '=', 'rawat.iddokter')
            ->leftJoin('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
            ->select([
                'rawat.*',
                'pasien.nama_pasien',
                'pasien.tgllahir',
                'poli.poli as nama_poli',
                'dokter.nama_dokter',
                'rawat_bayar.bayar as nama_bayar',
            ])
            ->where('rawat.id', $id)
            ->first();

        if (!$rawat) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json($rawat);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string',
        ]);

        $id = $request->id;
        $action = $request->action;

        try {
            if ($action == 'edit_date') {
                $request->validate(['tglmasuk' => 'required|date']);
                DB::table('rawat')->where('id', $id)->update(['tglmasuk' => $request->tglmasuk]);
                return response()->json(['success' => true, 'message' => 'Tanggal berhasil diubah']);
            } elseif ($action == 'cancel') {
                DB::table('rawat')->where('id', $id)->update(['status' => 0]);
                return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil dibatalkan']);
            } elseif ($action == 'checkin') {
                DB::table('rawat')->where('id', $id)->update(['status' => 2]); // 2 = Diproses
                return response()->json(['success' => true, 'message' => 'Pasien berhasil check-in']);
            }

            return response()->json(['error' => 'Invalid action'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
