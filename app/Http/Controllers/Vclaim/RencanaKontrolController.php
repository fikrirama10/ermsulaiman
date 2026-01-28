<?php

namespace App\Http\Controllers\Vclaim;

use Carbon\Carbon;
use App\Models\Poli;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\Vclaim\VclaimRencanaKontrolHelper;

class RencanaKontrolController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // For the "source" table (List Kunjungan)
            if ($request->has('source')) {
                return $this->dataKunjungan($request);
            }

            // For the "history" table (List Surat Kontrol)
            $date = $request->date ?? date('Y-m-d');
            $data = VclaimRencanaKontrolHelper::listSuratKontrol($date, $date, $request->filter ?? 2); // 2 = Rencana Kontrol

            if (isset($data['response']['list'])) {
                return DataTables::of(collect($data['response']['list']))
                    ->addIndexColumn()
                    ->make(true);
            }
            return DataTables::of(collect([]))->make(true);
        }

        $polis = Poli::get();
        return view('vclaim.rencana_kontrol.index', compact('polis'));
    }

    private function dataKunjungan(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');

        $query = DB::table('rawat')
            ->join('pasien', 'pasien.no_rm', '=', 'rawat.no_rm')
            ->join('poli', 'poli.id', '=', 'rawat.idpoli')
            ->leftJoin('dokter', 'dokter.id', '=', 'rawat.iddokter')
            ->leftJoin('rawat_bayar', 'rawat_bayar.id', '=', 'rawat.idbayar')
            ->select([
                'rawat.id',
                'rawat.no_rm',
                'rawat.tglmasuk',
                'rawat.no_sep',
                'pasien.nama_pasien',
                'pasien.no_bpjs',
                'poli.poli as nama_poli',
                'dokter.nama_dokter',
                'rawat_bayar.bayar',
            ])
            ->whereDate('rawat.tglmasuk', '>=', $startDate)
            ->whereDate('rawat.tglmasuk', '<=', $endDate)
            ->where('rawat.status', '<>', 0)
            ->where('rawat.idjenisrawat', 1)
            ->whereNotNull('rawat.no_sep') // Only those with SEP
            ->where('rawat.no_sep', '<>', '')
            ->orderBy('rawat.tglmasuk', 'desc');

        if ($request->idpoli) {
            $query->where('rawat.idpoli', $request->idpoli);
        }

        return DataTables::of($query)
            ->editColumn('tglmasuk', function ($row) {
                return Carbon::parse($row->tglmasuk)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" onclick="openRencanaKontrol(\'' . $row->no_sep . '\', \'' . ($row->no_bpjs ?? '') . '\')" title="Buat Rencana Kontrol">
                            <i class="ki-outline ki-calendar-add fs-2"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function modalCreate(Request $request)
    {
        $no_sep = $request->no_sep;
        $no_kartu = $request->no_kartu;

        $sep = null;
        if ($no_sep) {
            $sep = VclaimRencanaKontrolHelper::getDatabysep($no_sep);
        }

        $polis = Poli::get();

        return view('vclaim.rencana_kontrol.modal_create', compact('sep', 'polis', 'no_sep', 'no_kartu'));
    }

    public function checkSep(Request $request)
    {
        $no_sep = $request->no_sep;
        $response = VclaimRencanaKontrolHelper::getDatabysep($no_sep);
        return response()->json($response);
    }

    public function checkSchedule(Request $request)
    {
        $jnsKontrol = $request->jns_kontrol;
        $kdPoli = $request->kd_poli;
        $tgl = $request->tgl;

        $response = VclaimRencanaKontrolHelper::JadwalPraktekDokter($jnsKontrol, $kdPoli, $tgl);
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $dokter = Dokter::find($request->dokter_id);

        $post_data = [
            "request" => [
                "noSEP" => $request->no_sep,
                "kodeDokter" => $request->kode_dokter,
                "poliKontrol" => $request->kode_poli,
                "tglRencanaKontrol" => $request->tgl_kontrol,
                "user" => auth()->user()->name ?? 'System'
            ]
        ];

        if ($request->jns_kontrol == '1') {
            $response = VclaimRencanaKontrolHelper::getInsertSpri($post_data);
        } else {
            $response = VclaimRencanaKontrolHelper::getInsert($post_data);
        }

        return response()->json($response);
    }
}
