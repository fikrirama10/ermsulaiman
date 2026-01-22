<?php

namespace App\Services\Satusehat;

use App\Models\LogSatusehat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SatusehatLoggingService
{
    /**
     * Log SatuSehat API Request
     *
     * @param string $url
     * @param string $method
     * @param mixed $response
     * @param string $code
     * @param string $time (Execution time)
     * @param string $name (Endpoint name)
     * @param mixed $data (Request payload)
     */
    public static function log($url, $method, $response, $code, $time, $name = 'SatuSehat', $data = null)
    {
        try {
            // Encode payload/response if array/object
            $responseData = is_string($response) ? $response : json_encode($response);
            $payloadData = is_string($data) ? $data : json_encode($data);
            $userId = Auth::check() ? Auth::user()->id : 0;

            LogSatusehat::create([
                'url' => $url,
                'response' => $responseData,
                'time' => $time,
                'created_at' => now(),
                'user' => $userId,
                'name' => $name,
                'method' => $method,
                'data' => $payloadData,
                'code' => (string)$code
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write to SatuSehat Log Table: ' . $e->getMessage());
        }
    }
}
