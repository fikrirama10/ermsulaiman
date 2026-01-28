<?php

namespace App\Helpers\Vclaim;

use LZCompressor\LZString;
use App\Helpers\MakeRequestHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VclaimRujukanHelper
{
    public static function get_rujukan_norujukan($noRujukan)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-norujukan', 'get', '/Rujukan/' . $noRujukan);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_rujukan_nokartu($noKartu)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-nokartu', 'get', 'RujukanPeserta/' . $noKartu);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_rujukan_nokartu_multiple($noKartu)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-nokartu-multiple', 'get', '/Rujukan/List/Peserta/' . $noKartu);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_rujukan_norujukan_rs($noRujukan)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-norujukan-rs', 'get', '/Rujukan/RS/' . $noRujukan);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_rujukan_nokartu_rs($noKartu)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-nokartu-rs', 'get', 'Rujukan/RS/Peserta/' . $noKartu);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_rujukan_nokartu_multiple_rs($noKartu)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-rujukan-nokartu-multiple-rs', 'get', '/Rujukan/RS/List/Peserta/' . $noKartu);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    public static function get_jumlah_rujukan($noRujukan, $faskes)
    {
        try {
            $response = MakeRequestHelper::makeRequest('get-jumlah-rujukan', 'get', '/Rujukan/JumlahSEP/' . $faskes . '/' . $noRujukan);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    //Rujukan Keluar RS

    public static function list_rujukan_luar_rs($start, $end)
    {
        try {
            $response = MakeRequestHelper::makeRequest('list-rujukan-keluar-rs', 'get', '/Rujukan/Keluar/List/tglMulai/' . $start . '/tglAkhir/' . $end);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function insertRujukan($data)
    {
        try {
            // Endpoint Rujukan V2.0 : POST /Rujukan/2.0
            $response = MakeRequestHelper::makeRequest('insert-rujukan', 'post', '/Rujukan/2.0', $data);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function getListSpesialistik($ppk, $tgl)
    {
        try {
            // Endpoint: /Rujukan/ListSpesialistik/PPKRujukan/{ppk}/TglRujukan/{tgl}
            $response = MakeRequestHelper::makeRequest('list-spesialistik-rujukan', 'get', '/Rujukan/ListSpesialistik/PPKRujukan/' . $ppk . '/TglRujukan/' . $tgl);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function getListSarana($ppk)
    {
        try {
            // Endpoint: /Rujukan/ListSarana/PPKRujukan/{ppk}
            $response = MakeRequestHelper::makeRequest('list-sarana-rujukan', 'get', '/Rujukan/ListSarana/PPKRujukan/' . $ppk);
            return $response;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
