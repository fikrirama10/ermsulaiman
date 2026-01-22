<?php

namespace App\Services\Bpjs;

use App\Models\LogBpjs;
use Illuminate\Support\Facades\Log;

class VclaimLoggingService
{
    /**
     * Log BPJS API Request
     *
     * @param string $url
     * @param string $method
     * @param mixed $response
     * @param string $code
     * @param string $timeRequest (duration/timestamp)
     * @param string $name (Action name)
     * @param mixed $dataPayload
     */
    public static function log($url, $method, $response, $code, $timeRequest, $name = 'BPJS Connect', $dataPayload = null)
    {
        try {
            // Encode payload/response if array/object
            $responseData = is_string($response) ? $response : json_encode($response);
            $payloadData = is_string($dataPayload) ? $dataPayload : json_encode($dataPayload);

            LogBpjs::create([
                'url' => $url,
                'methhod' => $method, // Legacy typo preserved
                'time_request' => $timeRequest,
                'response' => $responseData,
                'message' => $code == '200' ? 'Success' : 'Error', // Simple logic, can be improved
                'code' => (string)$code,
                'name' => $name,
                'created_at' => now(),
                'data' => $payloadData
            ]);
        } catch (\Exception $e) {
            // Fallback logging to file if DB fails, to prevent app crash
            Log::error('Failed to write to BPJS Log Table: ' . $e->getMessage());
        }
    }
}
