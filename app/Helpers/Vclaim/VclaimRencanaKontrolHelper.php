<?php

namespace App\Helpers\Vclaim;

use LZCompressor\LZString;
use App\Helpers\MakeRequestHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Helpers\Vclaim\VclaimAuthHelper;
use GuzzleHttp\Client;

class VclaimRencanaKontrolHelper
{
    public static function getInsert($post_data = null)
    {
        try {
            $response = MakeRequestHelper::makeRequest('post-insest-rencana-kontrol', 'post', '/RencanaKontrol/insert', $post_data);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function getUpdate($post_data)
    {

        try {
            $response = MakeRequestHelper::makeRequest('put-update-rencana-kontrol', 'put', '/RencanaKontrol/Update', $post_data);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function getInsertSpri($post_data)
    {
        try {
            return MakeRequestHelper::makeRequest('post-insest-spri', 'post', '/RencanaKontrol/InsertSPRI', $post_data);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function getDatabynomor($bulan, $tahun, $nokartu, $filter)
    {
        try {
            return MakeRequestHelper::makeRequest('get-surat-by-nomor', 'get', '/RencanaKontrol/ListRencanaKontrol/Bulan/' . $bulan . '/Tahun/' . $tahun . '/Nokartu/' . $nokartu . '/filter/' . $filter);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function getDatabysep($nosep)
    {
        try {
            return MakeRequestHelper::makeRequest('get-surat-by-sep', 'get', '/RencanaKontrol/nosep/' . $nosep);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function getDatabynosurat($nosurat)
    {
        try {
            return MakeRequestHelper::makeRequest('get-surat-by-nosurat', 'get', '/RencanaKontrol/noSuratKontrol/' . $nosurat);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function listSuratKontrol($start, $end, $filter)
    {
        try {
            return MakeRequestHelper::makeRequest('list-surat-nosurat', 'get', '/RencanaKontrol/ListRencanaKontrol/tglAwal/' . $start . '/tglAkhir/' . $end . '/filter/' . $filter);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function JadwalPraktekDokter($jnskontrol, $kdpoli, $tglrencanakontrol)
    {
        try {
            return MakeRequestHelper::makeRequest('jadwal-praktek-dokter', 'get', '/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/' . $jnskontrol . '/KdPoli/' . $kdpoli . '/TglRencanaKontrol/' . $tglrencanakontrol);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
