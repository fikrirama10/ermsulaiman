<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\LogBpjs;
use App\Models\LogSatusehat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntegrationDashboardController extends Controller
{
    public function index()
    {
        // BPJS Stats (Today)
        $bpjsTotal = LogBpjs::whereDate('created_at', now())->count();
        $bpjsSuccess = LogBpjs::whereDate('created_at', now())->where('code', '200')->count();
        $bpjsError = $bpjsTotal - $bpjsSuccess;
        $bpjsSuccessRate = $bpjsTotal > 0 ? round(($bpjsSuccess / $bpjsTotal) * 100, 2) : 100;

        // SatuSehat Stats (Today)
        $ssTotal = LogSatusehat::whereDate('created_at', now())->count();
        $ssSuccess = LogSatusehat::whereDate('created_at', now())->where('code', '200')->count();
        $ssError = $ssTotal - $ssSuccess;
        $ssSuccessRate = $ssTotal > 0 ? round(($ssSuccess / $ssTotal) * 100, 2) : 100;

        // Recent Logs
        $bpjsLogs = LogBpjs::orderBy('created_at', 'desc')->take(10)->get();
        $ssLogs = LogSatusehat::orderBy('created_at', 'desc')->take(10)->get();

        return view('monitoring.integration-dashboard', compact(
            'bpjsTotal', 'bpjsSuccess', 'bpjsError', 'bpjsSuccessRate',
            'ssTotal', 'ssSuccess', 'ssError', 'ssSuccessRate',
            'bpjsLogs', 'ssLogs'
        ));
    }
}
