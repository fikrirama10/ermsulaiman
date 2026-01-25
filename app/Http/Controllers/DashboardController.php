<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rawat;
use App\Models\Pasien\Pasien;
use App\Models\Ruangan\Ruangan;
use App\Models\Ruangan\RuanganBed;
use App\Models\Ruangan\RuanganKelas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Get executive statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Total Pasien Terdaftar
            $totalPasien = Pasien::count();

            // Kunjungan Hari Ini
            $kunjunganHariIni = Rawat::whereDate('tglmasuk', $today)->count();

            // Rawat Inap Aktif (belum keluar)
            $rawatInapAktif = Rawat::where('idjenisrawat', 2)
                ->whereNull('tglpulang')
                ->where('status',2)
                ->whereYear('tglmasuk', $today->year)
                ->count();

            // Pasien Baru Bulan Ini
            $pasienBaruBulanIni = Pasien::whereBetween('tgldaftar', [$startOfMonth, $endOfMonth])
                ->count();

            // Total Kunjungan Bulan Ini
            $kunjunganBulanIni = Rawat::whereBetween('tglmasuk', [$startOfMonth, $endOfMonth])
                ->count();

            // Pasien Rawat Jalan Hari Ini
            $rawatJalanHariIni = Rawat::whereDate('tglmasuk', $today)
                ->where('idjenisrawat', '!=', 1)
                ->count();

            return response()->json([
                'total_pasien' => $totalPasien,
                'kunjungan_hari_ini' => $kunjunganHariIni,
                'rawat_inap_aktif' => $rawatInapAktif,
                'pasien_baru_bulan_ini' => $pasienBaruBulanIni,
                'kunjungan_bulan_ini' => $kunjunganBulanIni,
                'rawat_jalan_hari_ini' => $rawatJalanHariIni,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly visits data (last 12 months)
     */
    public function getKunjunganBulanan(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');

            $data = Rawat::select(
                    DB::raw('MONTH(tglmasuk) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('tglmasuk', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Fill all months with 0
            $result = [];
            $labels = [];
            for ($i = 1; $i <= 12; $i++) {
                $found = $data->where('month', $i)->first();
                $result[] = $found ? $found->total : 0;
                $labels[] = Carbon::create()->month($i)->format('M');
            }

            return response()->json([
                'labels' => $labels,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load monthly visits',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get visits by poli (top 10)
     */
    public function getKunjunganPerPoli(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');

            $data = Rawat::select('poli.poli as poli', DB::raw('COUNT(*) as total'))
                ->leftJoin('poli', 'rawat.idpoli', '=', 'poli.id')
                ->whereYear('tglmasuk', $year)
                ->whereNotNull('poli.poli')
                ->groupBy('poli.poli')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'labels' => $data->pluck('poli')->toArray(),
                'data' => $data->pluck('total')->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load poli visits',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gender demographics
     */
    public function getDemografiGender(Request $request)
    {
        try {
            $data = Pasien::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
                ->groupBy('jenis_kelamin')
                ->get();

            $labels = [];
            $values = [];
            foreach ($data as $item) {
                $labels[] = $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                $values[] = $item->total;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $values
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load gender demographics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get age demographics
     */
    public function getDemografiUsia(Request $request)
    {
        try {
            $data = Pasien::select(DB::raw('
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
            ->orderByRaw('FIELD(kategori_usia, "Balita (0-5)", "Anak (6-12)", "Remaja (13-18)", "Dewasa (19-60)", "Lansia (>60)", "Unknown")')
            ->get();

            return response()->json([
                'labels' => $data->pluck('kategori_usia')->toArray(),
                'data' => $data->pluck('total')->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load age demographics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment method distribution
     */
    public function getCaraBayar(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');

            $data = Rawat::select('rawat_bayar.bayar as cara_bayar', DB::raw('COUNT(*) as total'))
                ->leftJoin('rawat_bayar', 'rawat.idbayar', '=', 'rawat_bayar.id')
                ->whereNotNull('rawat_bayar.bayar')
                ->whereYear('tglmasuk', $year)
                ->groupBy('rawat_bayar.bayar')
                ->orderBy('total', 'desc')
                ->get();

            return response()->json([
                'labels' => $data->pluck('cara_bayar')->toArray(),
                'data' => $data->pluck('total')->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load payment methods',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top 10 diagnoses this month
     */
    public function getTopDiagnosa(Request $request)
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $data = Rawat::select('icdx', DB::raw('COUNT(*) as total'))
                ->whereNotNull('icdx')
                ->where('icdx', '!=', '')
                ->whereBetween('tglmasuk', [$startOfMonth, $endOfMonth])
                ->groupBy('icdx')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load top diagnoses',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bed availability
     */
    public function getBedAvailability(Request $request)
    {
        try {
            $ruangan = Ruangan::with('kelas')
                ->withCount('bed', 'bed_kosong')
                ->where('jenis', 2)
                ->where('status', 1)
                ->orderBy('idkelas', 'asc')
                ->get();

            $data = $ruangan->map(function($val) {
                $total = $val->bed_count;
                $kosong = $val->bed_kosong_count;
                $terisi = $total - $kosong;
                $percentage = $total > 0 ? round(($kosong / $total) * 100) : 0;

                return [
                    'nama_ruangan' => $val->nama_ruangan,
                    'kelas' => $val->kelas->kelas ?? '-',
                    'total' => $total,
                    'kosong' => $kosong,
                    'terisi' => $terisi,
                    'percentage' => $percentage,
                    'status' => $kosong > 0 ? 'Tersedia' : 'Penuh'
                ];
            });

            return response()->json([
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load bed availability',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
