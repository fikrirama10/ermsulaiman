<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien\Pasien;
use App\Models\Dokter;
use Illuminate\Support\Facades\Route;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $results = [];

        if (!$keyword) {
            return response()->json([]);
        }

        // 1. Search Patients
        $pasiens = Pasien::where('nama_pasien', 'like', "%{$keyword}%")
            ->orWhere('no_rm', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        foreach ($pasiens as $pasien) {
            $results[] = [
                'category' => 'Pasien',
                'title' => $pasien->nama_pasien,
                'subtitle' => 'RM: ' . $pasien->no_rm,
                'icon' => 'bi-person',
                'url' => route('pasien.rekammedis_detail', $pasien->id),
            ];
        }

        // 2. Search Doctors
        $dokters = Dokter::where('nama_dokter', 'like', "%{$keyword}%")
            ->where('status', 1)
            ->limit(3)
            ->get();

        foreach ($dokters as $dokter) {
            $results[] = [
                'category' => 'Dokter',
                'title' => $dokter->nama_dokter,
                'subtitle' => $dokter->poli->poli ?? 'Dokter Spesialis',
                'icon' => 'bi-heart-pulse',
                'url' => route('dokter.index', ['search' => $dokter->nama_dokter]), // Assuming dokter index has search param
            ];
        }

        // 3. Search Menus (Static Definition)
        $menus = [
            ['title' => 'Dashboard', 'url' => url('dashboard'), 'keywords' => ['home', 'dashboard', 'utama']],
            ['title' => 'Pendaftaran Pasien', 'url' => route('pendaftaran.create'), 'keywords' => ['daftar', 'registrasi', 'pasien baru']],
            ['title' => 'Kunjungan Hari Ini', 'url' => route('pendaftaran.index'), 'keywords' => ['kunjungan', 'rawat jalan', 'ugd']],
            ['title' => 'Data Dokter', 'url' => route('dokter.index'), 'keywords' => ['dokter', 'jadwal', 'spesialis']],
            ['title' => 'Laporan', 'url' => url('/laporan'), 'keywords' => ['laporan', 'rekap', 'statistik']],
        ];

        foreach ($menus as $menu) {
            if (stripos($menu['title'], $keyword) !== false || 
                collect($menu['keywords'])->contains(fn($k) => stripos($k, $keyword) !== false)) {
                $results[] = [
                    'category' => 'Menu',
                    'title' => $menu['title'],
                    'subtitle' => 'Navigasi Menu',
                    'icon' => 'bi-grid',
                    'url' => $menu['url'],
                ];
            }
        }

        return response()->json($results);
    }
}
