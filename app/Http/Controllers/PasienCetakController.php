<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien\Pasien;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class PasienCetakController extends Controller
{
    /**
     * Cetak Formulir Rekam Medis
     */
    public function cetakRM($id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Ambil data detail rekap medis terakhir jika ada
        $last_visit = DB::table('demo_detail_rekap_medis')
            ->select('demo_detail_rekap_medis.*', 'rawat.tglmasuk', 'dokter.nama_dokter', 'poli.poli')
            ->join('rawat', 'rawat.id', '=', 'demo_detail_rekap_medis.idrawat')
            ->join('dokter', 'rawat.iddokter', '=', 'dokter.id')
            ->join('poli', 'rawat.idpoli', '=', 'poli.id')
            ->where('no_rm', $pasien->no_rm)
            ->orderBy('id', 'desc')
            ->first();

        return view('cetak.rm', compact('pasien', 'last_visit'));
    }

    /**
     * Cetak Label Pasien
     */
    public function cetakLabel(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Parameter kustomisasi bisa ditambahkan di sini via Request
        $copies = $request->get('copies', 1);
        $size = $request->get('size', 'standard'); // small, standard, large
        
        return view('cetak.label-pasien', compact('pasien', 'copies', 'size'));
    }

    /**
     * Cetak Label Map
     */
    public function cetakMap($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('cetak.label-map', compact('pasien'));
    }

    /**
     * Cetak Gelang Pasien
     */
    public function cetakGelang($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('cetak.gelang', compact('pasien'));
    }
}
