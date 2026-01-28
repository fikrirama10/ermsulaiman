<?php

namespace App\Http\Controllers\Vclaim;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\Vclaim\VclaimRujukanHelper;
use App\Helpers\Vclaim\VclaimReferensiHelper;
use App\Models\Poli;
use App\Models\Dokter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RujukanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Tab 1: List Kunjungan (Source for creating new Rujukan)
            if ($request->has('source')) {
                return $this->dataKunjungan($request);
            }

            // Tab 2: History Rujukan (From VClaim)
            $start_date = $request->start_date ?? date('Y-m-d');
            $end_date = $request->end_date ?? date('Y-m-d');

            // Call Helper to get list from BPJS
            $data = VclaimRujukanHelper::list_rujukan_luar_rs($start_date, $end_date);

            if (isset($data['response']['list'])) {
                return DataTables::of(collect($data['response']['list']))
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('vclaim.rujukan.print', ['noRujukan' => $row['noRujukan']]) . '" target="_blank" class="btn btn-sm btn-icon btn-bg-light btn-active-color-info" title="Print">
                                    <i class="ki-outline ki-printer fs-2"></i>
                                </a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return DataTables::of(collect([]))->make(true);
        }

        $polis = Poli::get();
        return view('vclaim.rujukan.index', compact('polis'));
    }

    private function dataKunjungan(Request $request)
    {
        // Similar to RencanaKontrolController but maybe filter differently if needed
        // For now using same logic: Patients with SEP who are currently active or recently visited
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');

        $query = DB::table('rawat')
            ->join('pasien', 'pasien.no_rm', '=', 'rawat.no_rm')
            ->join('poli', 'poli.id', '=', 'rawat.idpoli')
            ->leftJoin('dokter', 'dokter.id', '=', 'rawat.iddokter')
            ->select([
                'rawat.id',
                'rawat.no_rm',
                'rawat.tglmasuk',
                'rawat.no_sep',
                'pasien.nama_pasien',
                'pasien.no_bpjs',
                'poli.poli as nama_poli',
                'dokter.nama_dokter',
            ])
            ->whereDate('rawat.tglmasuk', '>=', $startDate)
            ->whereDate('rawat.tglmasuk', '<=', $endDate)
            ->where('rawat.status', '<>', 0)
            ->where('rawat.idjenisrawat', 1) // Rawat Jalan
            ->whereNotNull('rawat.no_sep')
            ->where('rawat.no_sep', '<>', '')
            ->orderBy('rawat.tglmasuk', 'desc');

        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('pasien.nama_pasien', 'like', "%$keyword%")
                    ->orWhere('rawat.no_rm', 'like', "%$keyword%")
                    ->orWhere('rawat.no_sep', 'like', "%$keyword%");
            });
        }

        return DataTables::of($query)
            ->editColumn('tglmasuk', function ($row) {
                return Carbon::parse($row->tglmasuk)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" onclick="openModalRujukan(\'' . $row->no_sep . '\', \'' . ($row->no_bpjs ?? '') . '\')" title="Buat Rujukan">
                            <i class="ki-outline ki-exit-right fs-2"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function modalCreate(Request $request)
    {
        $no_sep = $request->no_sep;
        $no_kartu = $request->no_kartu;

        // Possibly fetch SEP details to pre-fill info
        // $sep = VclaimRencanaKontrolHelper::getDatabysep($no_sep);

        return view('vclaim.rujukan.modal_create', compact('no_sep', 'no_kartu'));
    }

    public function cariRS(Request $request)
    {
        // Wrapper for Faskes Search (Rujukan)
        // param: nama (keyword), jenis (2=RS)
        $keyword = $request->search;
        // 2 = Rujukan Faskes Tingkat Lanjut (RS)
        $response = VclaimReferensiHelper::getFaskes($keyword, 2);

        $results = [];
        if (isset($response['response']['faskes'])) {
            foreach ($response['response']['faskes'] as $item) {
                $results[] = [
                    'id' => $item['kode'],
                    'text' => $item['nama'] // Display name only
                ];
            }
        }
        return response()->json(['results' => $results]);
    }

    public function cariPoli(Request $request)
    {
        $ppk = $request->ppk_rujukan;
        $tgl = $request->tgl_rujukan;

        // Use ListSpesialistik
        $response = VclaimRujukanHelper::getListSpesialistik($ppk, $tgl);

        $results = [];
        if (isset($response['response']['list'])) {
            foreach ($response['response']['list'] as $item) {
                $results[] = [
                    'id' => $item['kodeSpesialis'],
                    'text' => $item['namaSpesialis']
                ];
            }
        }
        return response()->json($results);
    }

    public function cariDiagnosa(Request $request)
    {
        $keyword = $request->keyword;
        $response = VclaimReferensiHelper::getDiagnosa($keyword);

        $results = [];
        if (isset($response['response']['diagnosa'])) {
            foreach ($response['response']['diagnosa'] as $item) {
                $results[] = [
                    'id' => $item['kode'],
                    'text' => $item['nama']
                ];
            }
        }
        return response()->json(['results' => $results]); // Select2 expects 'results' key or we map it in JS
    }

    public function store(Request $request)
    {
        /*
           Data format expected by insertRujukan:
           {
              "request": {
                 "t_rujukan": {
                    "noSep": "...",
                    "tglRujukan": "...",
                    "tglRencanaKunjungan": "...",
                    "ppkDirujuk": "...",
                    "jnsPelayanan": "2", // 1=Inap, 2=Jalan
                    "catatan": "...",
                    "diagRujukan": "...",
                    "tipeRujukan": "...", // 0=Penuh, 1=Partial, 2=Rujuk Balik
                    "poliRujukan": "...",
                    "user": "..."
                 }
              }
           }
        */

        $data = [
            "request" => [
                "t_rujukan" => [
                    "noSep" => $request->no_sep,
                    "tglRujukan" => $request->tgl_rujukan,
                    "tglRencanaKunjungan" => $request->tgl_rencana,
                    "ppkDirujuk" => $request->ppk_dirujuk,
                    "jnsPelayanan" => $request->jns_pelayanan, // Should be 2 for Rawat Jalan Rujukan Keluar usually
                    "catatan" => $request->catatan,
                    "diagRujukan" => $request->diag_rujukan,
                    "tipeRujukan" => $request->tipe_rujukan,
                    "poliRujukan" => $request->poli_rujukan,
                    "user" => auth()->user()->name ?? 'System'
                ]
            ]
        ];

        $response = VclaimRujukanHelper::insertRujukan($data);
        return response()->json($response);
    }

    public function print($noRujukan)
    {
        // Try to get detail first
        $rujukan = VclaimRujukanHelper::get_rujukan_norujukan($noRujukan);

        if (!isset($rujukan['response'])) {
            return redirect()->back()->with('error', 'Data rujukan tidak ditemukan atau error dari BPJS.');
        }

        // We might need local patient data too if we want to show nice things
        // For now just pass the VM object
        return view('vclaim.rujukan.print', compact('rujukan'));
    }
}
