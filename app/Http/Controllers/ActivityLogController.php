<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogExport;

class ActivityLogController extends Controller
{
    /**
     * Display dashboard monitoring
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with(['subject', 'causer']);

        // Filter by log name (kategori)
        if ($request->filled('log_name')) {
            $query->ofLogName($request->log_name);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->ofEvent($request->event);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        } elseif ($request->filled('periode')) {
            switch ($request->periode) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by patient (no_rm)
        if ($request->filled('no_rm')) {
            $query->byPatient($request->no_rm);
        }

        // Filter by rawat
        if ($request->filled('idrawat')) {
            $query->byRawat($request->idrawat);
        }

        // Filter by user role
        if ($request->filled('user_role')) {
            $query->where('user_role', $request->user_role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('no_rm', 'like', "%{$search}%")
                  ->orWhere('dokter', 'like', "%{$search}%")
                  ->orWhere('poli', 'like', "%{$search}%");
            });
        }

        // Get statistics
        $stats = $this->getStatistics($request);

        // Get activities
        $activities = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique users for filter
        $users = DB::table('activity_logs')
                   ->select('user_id', 'user_name')
                   ->distinct()
                   ->whereNotNull('user_id')
                   ->orderBy('user_name')
                   ->get();

        return view('monitoring.index', compact('activities', 'stats', 'users'));
    }

    /**
     * Show activity detail
     */
    public function show($id)
    {
        $activity = ActivityLog::with(['subject', 'causer'])->findOrFail($id);

        return view('monitoring.show', compact('activity'));
    }

    /**
     * Get statistics
     */
    private function getStatistics(Request $request)
    {
        $query = ActivityLog::query();

        // Apply same filters as main query
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        } elseif ($request->filled('periode')) {
            switch ($request->periode) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        } else {
            $query->today();
        }

        return [
            'total' => $query->count(),
            'kunjungan' => (clone $query)->ofLogName('kunjungan')->count(),
            'rekam_medis' => (clone $query)->ofLogName('rekam_medis')->count(),
            'tindak_lanjut' => (clone $query)->ofLogName('tindak_lanjut')->count(),
            'by_event' => (clone $query)->select('event', DB::raw('count(*) as total'))
                                         ->groupBy('event')
                                         ->pluck('total', 'event')
                                         ->toArray(),
            'by_user' => (clone $query)->select('user_name', DB::raw('count(*) as total'))
                                        ->groupBy('user_name')
                                        ->orderByDesc('total')
                                        ->limit(5)
                                        ->get(),
            'by_hour' => (clone $query)->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
                                        ->groupBy('hour')
                                        ->orderBy('hour')
                                        ->pluck('total', 'hour')
                                        ->toArray(),
        ];
    }

    /**
     * Get activity by patient
     */
    public function byPatient($noRm)
    {
        $activities = ActivityLog::byPatient($noRm)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(50);

        return view('monitoring.by-patient', compact('activities', 'noRm'));
    }

    /**
     * Get activity by rawat
     */
    public function byRawat($idrawat)
    {
        $activities = ActivityLog::byRawat($idrawat)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(50);

        return view('monitoring.by-rawat', compact('activities', 'idrawat'));
    }

    /**
     * Export to Excel
     */
    public function export(Request $request)
    {
        $query = ActivityLog::query();

        // Apply all filters
        if ($request->filled('log_name')) {
            $query->ofLogName($request->log_name);
        }

        if ($request->filled('event')) {
            $query->ofEvent($request->event);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new ActivityLogExport($activities), 'activity-log-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Delete old logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 90); // Default 90 days

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->back()->with('berhasil', "Berhasil menghapus {$deleted} log lama (lebih dari {$days} hari)");
    }

    /**
     * API: Get chart data
     */
    public function chartData(Request $request)
    {
        $type = $request->input('type', 'daily'); // daily, weekly, monthly
        $days = $request->input('days', 7);

        $data = ActivityLog::where('created_at', '>=', now()->subDays($days))
                           ->select(
                               DB::raw('DATE(created_at) as date'),
                               'event',
                               DB::raw('count(*) as total')
                           )
                           ->groupBy('date', 'event')
                           ->orderBy('date')
                           ->get()
                           ->groupBy('date');

        return response()->json($data);
    }
}

