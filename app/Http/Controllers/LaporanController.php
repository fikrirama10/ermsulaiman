<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rawat;
use App\Models\Pasien\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use App\Exports\KunjunganExport;
use App\Exports\RawatInapExport;
use App\Exports\DemografiExport;
use App\Exports\DiagnosaExport;
use App\Exports\BpjsExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    // Chart Data APIs
    public function dataChartKunjunganBulanan(Request $request)
    {
        $year = $request->year ?? date('Y');

        $data = Rawat::select(DB::raw('MONTH(tglmasuk) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('tglmasuk', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fill all months with 0
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $found = $data->where('month', $i)->first();
            $result[] = $found ? $found->total : 0;
        }

        return response()->json([
            'data' => $result
        ]);
    }

    public function dataChartKunjunganPoli(Request $request)
    {
        $data = Rawat::select('poli.namapoli as poli', DB::raw('COUNT(*) as total'))
            ->leftJoin('poli', 'rawat.idpoli', '=', 'poli.id')
            ->whereYear('tglmasuk', date('Y'))
            ->groupBy('poli.namapoli')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function dataChartDemografiGender(Request $request)
    {
        $data = Pasien::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function dataChartDemografiUsia(Request $request)
    {
        $query = Pasien::select(DB::raw('
            CASE
                WHEN usia_tahun <= 5 THEN "Balita (0-5)"
                WHEN usia_tahun BETWEEN 6 AND 12 THEN "Anak (6-12)"
                WHEN usia_tahun BETWEEN 13 AND 18 THEN "Remaja (13-18)"
                WHEN usia_tahun BETWEEN 19 AND 60 THEN "Dewasa (19-60)"
                WHEN usia_tahun > 60 THEN "Lansia (>60)"
                ELSE "Unknown"
            END as kategori_usia
        '), DB::raw('COUNT(*) as total'))
        ->groupBy('kategori_usia')
        ->orderBy('kategori_usia')
        ->get();

        return response()->json([
            'data' => $query
        ]);
    }

    public function dataChartCaraBayar(Request $request)
    {
        $data = Rawat::select('bayar.namabayar as cara_bayar', DB::raw('COUNT(*) as total'))
            ->leftJoin('bayar', 'rawat.idbayar', '=', 'bayar.id')
            ->whereNotNull('bayar.namabayar')
            ->whereYear('tglmasuk', date('Y'))
            ->groupBy('bayar.namabayar')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // Laporan Kunjungan Pasien
    public function kunjungan()
    {
        $poli = Poli::all();
        $dokter = Dokter::all();
        return view('laporan.kunjungan', compact('poli', 'dokter'));
    }

    public function dataKunjungan(Request $request)
    {
        $query = Rawat::with(['pasien', 'poli', 'dokter', 'bayar'])
            ->select('rawat.*');

        // Filter berdasarkan tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$request->tanggal_mulai . ' 00:00:00', $request->tanggal_selesai . ' 23:59:59']);
        }

        // Filter berdasarkan poli
        if ($request->idpoli) {
            $query->where('idpoli', $request->idpoli);
        }

        // Filter berdasarkan dokter
        if ($request->iddokter) {
            $query->where('iddokter', $request->iddokter);
        }

        // Filter berdasarkan jenis rawat
        if ($request->idjenisrawat) {
            $query->where('idjenisrawat', $request->idjenisrawat);
        }

        // Filter berdasarkan cara datang
        if ($request->cara_datang) {
            $query->where('cara_datang', $request->cara_datang);
        }

        $data = $query->orderBy('tglmasuk', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function exportKunjungan(Request $request)
    {
        return Excel::download(
            new KunjunganExport(
                $request->tanggal_mulai,
                $request->tanggal_selesai,
                $request->idpoli,
                $request->iddokter,
                $request->idjenisrawat,
                $request->cara_datang
            ),
            'laporan-kunjungan-' . date('YmdHis') . '.xlsx'
        );
    }

    // Laporan Rawat Inap
    public function rawatInap()
    {
        $ruangan = Ruangan::all();
        $dokter = Dokter::all();
        return view('laporan.rawat-inap', compact('ruangan', 'dokter'));
    }

    public function dataRawatInap(Request $request)
    {
        $query = Rawat::with(['pasien', 'ruangan', 'dokter', 'bayar'])
            ->where('idjenisrawat', 1) // Rawat Inap
            ->select('rawat.*');

        // Filter berdasarkan tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$request->tanggal_mulai . ' 00:00:00', $request->tanggal_selesai . ' 23:59:59']);
        }

        // Filter berdasarkan ruangan
        if ($request->idruangan) {
            $query->where('idruangan', $request->idruangan);
        }

        // Filter berdasarkan dokter
        if ($request->iddokter) {
            $query->where('iddokter', $request->iddokter);
        }

        // Filter berdasarkan cara keluar
        if ($request->cara_keluar) {
            $query->where('cara_keluar', $request->cara_keluar);
        }

        $data = $query->orderBy('tglmasuk', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function exportRawatInap(Request $request)
    {
        return Excel::download(
            new RawatInapExport(
                $request->tanggal_mulai,
                $request->tanggal_selesai,
                $request->idruangan,
                $request->iddokter,
                $request->cara_keluar
            ),
            'laporan-rawat-inap-' . date('YmdHis') . '.xlsx'
        );
    }

    // Laporan Demografi Pasien
    public function demografi()
    {
        $agama = \App\Models\Pasien\Agama::all();
        return view('laporan.demografi', compact('agama'));
    }

    public function dataDemografi(Request $request)
    {
        $query = Pasien::with(['agama']);

        // Filter berdasarkan tanggal daftar
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tgldaftar', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan usia
        if ($request->usia_dari && $request->usia_sampai) {
            $query->whereBetween('usia_tahun', [$request->usia_dari, $request->usia_sampai]);
        }

        // Filter berdasarkan kategori usia
        if ($request->kategori_usia) {
            $kategoriUsia = $request->kategori_usia;
            $query->where(function($q) use ($kategoriUsia) {
                if ($kategoriUsia == 'balita') {
                    $q->where('usia_tahun', '<=', 5);
                } elseif ($kategoriUsia == 'anak') {
                    $q->whereBetween('usia_tahun', [6, 12]);
                } elseif ($kategoriUsia == 'remaja') {
                    $q->whereBetween('usia_tahun', [13, 18]);
                } elseif ($kategoriUsia == 'dewasa') {
                    $q->whereBetween('usia_tahun', [19, 60]);
                } elseif ($kategoriUsia == 'lansia') {
                    $q->where('usia_tahun', '>', 60);
                }
            });
        }

        // Filter berdasarkan agama
        if ($request->idagama) {
            $query->where('idagama', $request->idagama);
        }

        // Filter berdasarkan kelurahan
        if ($request->idkelurahan) {
            $query->where('idkelurahan', $request->idkelurahan);
        }

        $data = $query->orderBy('tgldaftar', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function exportDemografi(Request $request)
    {
        return Excel::download(
            new DemografiExport(
                $request->tanggal_mulai,
                $request->tanggal_selesai,
                $request->jenis_kelamin,
                $request->usia_dari,
                $request->usia_sampai,
                $request->kategori_usia,
                $request->idagama,
                $request->idkelurahan
            ),
            'laporan-demografi-' . date('YmdHis') . '.xlsx'
        );
    }

    // Laporan 10 Diagnosa Terbanyak (ICD-X)
    public function diagnosa()
    {
        return view('laporan.diagnosa');
    }

    public function dataDiagnosa(Request $request)
    {
        $query = Rawat::select('icdx', DB::raw('COUNT(*) as total'))
            ->whereNotNull('icdx')
            ->where('icdx', '!=', '');

        // Filter berdasarkan tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$request->tanggal_mulai . ' 00:00:00', $request->tanggal_selesai . ' 23:59:59']);
        }

        $data = $query->groupBy('icdx')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function exportDiagnosa(Request $request)
    {
        return Excel::download(
            new DiagnosaExport(
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ),
            'laporan-diagnosa-' . date('YmdHis') . '.xlsx'
        );
    }

    // Laporan BPJS
    public function bpjs()
    {
        return view('laporan.bpjs');
    }

    public function dataBpjs(Request $request)
    {
        $query = Rawat::with(['pasien', 'poli', 'dokter'])
            ->whereNotNull('no_sep')
            ->where('no_sep', '!=', '')
            ->select('rawat.*');

        // Filter berdasarkan tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tglmasuk', [$request->tanggal_mulai . ' 00:00:00', $request->tanggal_selesai . ' 23:59:59']);
        }

        $data = $query->orderBy('tglmasuk', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function exportBpjs(Request $request)
    {
        return Excel::download(
            new BpjsExport(
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ),
            'laporan-bpjs-' . date('YmdHis') . '.xlsx'
        );
    }
}
