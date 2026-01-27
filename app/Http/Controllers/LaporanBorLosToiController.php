<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawatRuangan;
use App\Models\RuanganBed;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanBorLosToiController extends Controller
{
    public function index()
    {
        return view('laporan.bor-los-toi');
    }

    public function data(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if (!$start || !$end) {
            return response()->json(['data' => []]);
        }

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;

        // Get all inpatient rooms
        $ruangans = Ruangan::where('idjenis', 1)->where('status', 1)->get(); // Assuming idjenis 2 is Rawat Inap rooms or we filter by actual usage

        $reportData = [];

        foreach ($ruangans as $ruangan) {
            // 1. TT (Tempat Tidur) / Bed Count
            $bedCount = RuanganBed::where('idruangan', $ruangan->id)->count();

            if ($bedCount == 0) continue; // Skip rooms with no beds

            // 2. HP (Hari Perawatan)
            // Logic: Sum of days overlapping with the period for each patient in this room
            // rawat_ruangan tracks movement.
            // Condition:
            // - Entered before End Date
            // - Left after Start Date (or hasn't left yet)

            $hp = 0;

            $patients = RawatRuangan::where('idruangan', $ruangan->id)
                ->where('tgl_masuk', '<=', $end . ' 23:59:59')
                ->where(function ($q) use ($start) {
                    $q->where('tgl_keluar', '>=', $start . ' 00:00:00')
                        ->orWhereNull('tgl_keluar');
                })
                ->get();

            foreach ($patients as $p) {
                $masuk = Carbon::parse($p->tgl_masuk);
                $keluar = $p->tgl_keluar ? Carbon::parse($p->tgl_keluar) : Carbon::now();

                // Clamp dates to period
                $calcStart = $masuk->greaterThan($startDate) ? $masuk : $startDate;
                $calcEnd = $keluar->lessThan($endDate) ? $keluar : $endDate;

                if ($calcEnd->greaterThanOrEqualTo($calcStart)) {
                    // diffInDays returns absolute difference. 
                    // If start and end are same day, it returns 0, but it counts as 1 day of care?
                    // Standard HP calculation: 
                    // If patient in and out same day = 1 day.
                    // Else, difference in dates. 
                    // BUT, we are calculating "Census Days".
                    // Census = Headcount at midnight.
                    // For the purpose of BOR (Bed Occupancy Rate), we usually aggregate "Bed Days".

                    // Simple approach: Calendar days overlapping.
                    $days = $calcStart->diffInDays($calcEnd);

                    // Edge case: Admission and Discharge same day counts as 1 day for billing, 
                    // but for Census it might be 0 or 1 depending on policy. 
                    // Let's assume 1 day if dates are equal, otherwise just the difference?
                    // If exact timestamps, diffInDays(float) might be better?
                    // Let's stick to standard practice: 
                    // Total Hari Perawatan = Sum of (DateOut - DateIn) for the period.

                    if ($days == 0) $days = 1;

                    $hp += $days;
                }
            }

            // 3. Pasien Keluar (Discharges/Deaths/Transfers Out)
            // Count records where tgl_keluar is in range.
            $pasienKeluar = RawatRuangan::where('idruangan', $ruangan->id)
                ->whereBetween('tgl_keluar', [$start . ' 00:00:00', $end . ' 23:59:59'])
                ->count();

            // To exclude transfers (if needed), check 'status' or next room logic. 
            // For per-room stats, transfer out IS an exit. So keep it.

            // 4. Pasien Mati (Optional, if we have status)
            // $pasienMati = ...

            // Calculations
            $bor = 0;
            $alos = 0;
            $toi = 0;
            $bto = 0;

            if (($bedCount * $daysInPeriod) > 0) {
                $bor = ($hp / ($bedCount * $daysInPeriod)) * 100;
            }

            if ($pasienKeluar > 0) {
                // ALOS = Total Length of Stay of Discharged Patients / Discharges
                // We need to fetch LOS of discharged patients specifically.
                $dischargedPatients = RawatRuangan::where('idruangan', $ruangan->id)
                    ->whereBetween('tgl_keluar', [$start . ' 00:00:00', $end . ' 23:59:59'])
                    ->get();

                $totalLos = 0;
                foreach ($dischargedPatients as $dp) {
                    $masuk = Carbon::parse($dp->tgl_masuk);
                    $keluar = Carbon::parse($dp->tgl_keluar);
                    $los = $masuk->diffInDays($keluar);
                    if ($los == 0) $los = 1;
                    $totalLos += $los;
                }
                $alos = $totalLos / $pasienKeluar;

                $toi = (($bedCount * $daysInPeriod) - $hp) / $pasienKeluar;
                $bto = $pasienKeluar / $bedCount;
            }

            $reportData[] = [
                'ruangan' => $ruangan->nama_ruangan,
                'bed_count' => $bedCount,
                'hp' => $hp,
                'pasien_keluar' => $pasienKeluar,
                'bor' => round($bor, 2),
                'alos' => round($alos, 2),
                'toi' => round($toi, 2),
                'bto' => round($bto, 2),
            ];
        }

        return response()->json(['data' => $reportData]);
    }
}
