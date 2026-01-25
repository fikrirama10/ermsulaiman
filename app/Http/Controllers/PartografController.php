<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Partograf\FetalMonitoring;
use App\Models\Partograf\LaborMedication;
use App\Models\Partograf\LaborProgress;
use App\Models\Partograf\LaborRecord;
use App\Models\Partograf\MaternalUrine;
use App\Models\Partograf\MaternalVital;
use App\Models\Partograf\PartographLine;
use App\Models\Partograf\UterineContraction;
use App\Models\Pasien\Pasien;
use App\Models\Rawat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PartografController extends Controller
{
    /**
     * Display a listing of labor records
     */
    public function index(Request $request)
    {
        $query = LaborRecord::with(['visit.pasien', 'midwife', 'doctor'])
            ->orderBy('admission_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('admission_date', $request->date);
        }

        // Search by patient name or RM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('visit.pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%");
            });
        }

        $laborRecords = $query->paginate(20);

        return view('partograf.index', compact('laborRecords'));
    }

    /**
     * Show the form for creating a new labor record
     */
    public function create(Request $request)
    {
        // Get visits with melahirkan = 1 that don't have labor record yet
        $visits = Rawat::melahirkan()
            ->with('pasien')
            ->whereDoesntHave('laborRecord')
            ->where('status', 1)
            ->orderBy('tglmasuk', 'desc')
            ->get();

        // Get midwives and doctors
        $midwives = User::where('idpriv', '12')->orWhere('idpriv', '11')->get();
        $doctors = Dokter::where('idpoli', 4)->get();

        // If visit_id is provided, pre-load visit data
        $selectedVisit = null;
        if ($request->filled('visit_id')) {
            $selectedVisit = Rawat::with('pasien')->find($request->visit_id);
        }

        return view('partograf.create', compact('visits', 'midwives', 'doctors', 'selectedVisit'));
    }

    /**
     * Store a newly created labor record
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'visit_id' => 'required|exists:rawat,id',
            'patient_no_rm' => 'required',
            'gravida' => 'required|integer|min:1',
            'para' => 'required|integer|min:0',
            'abortus' => 'required|integer|min:0',
            'gestational_age' => 'required|integer|min:20|max:45',
            'labor_start_time' => 'required|date',
            'membrane_rupture_time' => 'nullable|date',
            'midwife_id' => 'required|exists:user,id',
            'doctor_id' => 'nullable|exists:dokter,id',
            'notes' => 'nullable|string',
            'initial_risk_assessment' => 'nullable|array',
            'initial_assessment_notes' => 'nullable|string|max:1000',
        ]);

        // return $validated;

        try {
            DB::beginTransaction();

            // Get visit to retrieve patient_id
            $visit = Rawat::findOrFail($validated['visit_id']);
            $pasien = Pasien::where('no_rm', $validated['patient_no_rm'])->first();

            // Process risk assessment checkboxes
            $riskAssessment = [];
            if (isset($validated['initial_risk_assessment']) && is_array($validated['initial_risk_assessment'])) {
                $riskKeys = array_keys(config('partograf.risk_factors'));
                foreach ($riskKeys as $key) {
                    $riskAssessment[$key] = in_array($key, $validated['initial_risk_assessment']);
                }
            }

            // Create labor record
            $laborRecord = LaborRecord::create([
                'visit_id' => $validated['visit_id'],
                'patient_id' => $pasien->id,
                'patient_no_rm' => $validated['patient_no_rm'],
                'admission_date' => now(),
                'gravida' => $validated['gravida'],
                'para' => $validated['para'],
                'abortus' => $validated['abortus'],
                'gestational_age' => $validated['gestational_age'],
                'labor_start_time' => $validated['labor_start_time'],
                'membrane_rupture_time' => $validated['membrane_rupture_time'] ?? null,
                'midwife_id' => $validated['midwife_id'],
                'doctor_id' => $validated['doctor_id'] ?? null,
                'status' => 'ongoing',
                'notes' => $validated['notes'] ?? null,
                'initial_risk_assessment' => !empty($riskAssessment) ? $riskAssessment : null,
                'initial_assessment_notes' => $validated['initial_assessment_notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Create partograph lines (alert line starts when cervix reaches 3cm)
            // For now, we'll set it to labor start time
            $alertLineStart = Carbon::parse($validated['labor_start_time']);

            PartographLine::create([
                'labor_record_id' => $laborRecord->id,
                'alert_line_start_time' => $alertLineStart,
                'action_line_start_time' => $alertLineStart->copy()->addHours(4),
            ]);

            DB::commit();

            return redirect()
                ->route('partograf.show', $laborRecord->id)
                ->with('success', 'Partograf berhasil dibuat. Silakan mulai observasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified labor record (monitoring view)
     */
    public function show($id)
    {
        $laborRecord = LaborRecord::with([
            'visit.pasien',
            'midwife',
            'doctor',
            'progresses.recorder',
            'contractions.recorder',
            'fetalMonitorings.recorder',
            'maternalVitals.recorder',
            'maternalUrines.recorder',
            'medications.administrator',
            'partographLine'
        ])->findOrFail($id);

        return view('partograf.show', compact('laborRecord'));
    }

    /**
     * Show the form for editing labor record
     */
    public function edit($id)
    {
        $laborRecord = LaborRecord::with('visit.pasien')->findOrFail($id);
        $midwives = User::where('idpriv', '12')->orWhere('idpriv', '11')->get();
        $doctors = Dokter::where('idpoli', 4)->get();

        return view('partograf.edit', compact('laborRecord', 'midwives', 'doctors'));
    }

    /**
     * Update the specified labor record
     */
    public function update(Request $request, $id)
    {
        $laborRecord = LaborRecord::findOrFail($id);

        $validated = $request->validate([
            'gravida' => 'required|integer|min:1',
            'para' => 'required|integer|min:0',
            'abortus' => 'required|integer|min:0',
            'gestational_age' => 'required|integer|min:20|max:45',
            'labor_start_time' => 'required|date',
            'labor_end_time' => 'nullable|date',
            'membrane_rupture_time' => 'nullable|date',
            'delivery_method' => 'nullable|in:normal,vacuum,forceps,cesarean',
            'baby_condition' => 'nullable|string',
            'complications' => 'nullable|string',
            'midwife_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:dokter,id',
            'status' => 'required|in:ongoing,completed,referred',
            'notes' => 'nullable|string',
        ]);

        try {
            $laborRecord->update(array_merge($validated, [
                'updated_by' => Auth::id(),
            ]));

            return redirect()
                ->route('partograf.show', $id)
                ->with('success', 'Data partograf berhasil diupdate.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate partograf: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified labor record
     */
    public function destroy($id)
    {
        try {
            $laborRecord = LaborRecord::findOrFail($id);
            $laborRecord->delete();

            return redirect()
                ->route('partograf.index')
                ->with('success', 'Partograf berhasil dihapus.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus partograf: ' . $e->getMessage());
        }
    }

    /**
     * Store labor progress observation
     */
    public function storeProgress(Request $request, $id)
    {
        $validated = $request->validate([
            'observation_time' => 'required|date',
            'cervical_dilatation' => 'required|numeric|min:0|max:10',
            'fetal_head_descent' => 'required|in:5/5,4/5,3/5,2/5,1/5,0/5',
            'molding' => 'nullable|in:0,1,2,3',
            'position' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);

        try {
            LaborProgress::create(array_merge($validated, [
                'labor_record_id' => $id,
                'recorded_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Observasi kemajuan persalinan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan observasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store uterine contraction observation
     */
    public function storeContraction(Request $request, $id)
    {
        $validated = $request->validate([
            'observation_time' => 'required|date',
            'contractions_per_10min' => 'required|integer|min:0|max:10',
            'duration_seconds' => 'nullable|integer|min:0|max:120',
            'intensity' => 'required|in:weak,moderate,strong',
            'notes' => 'nullable|string',
        ]);

        try {
            UterineContraction::create(array_merge($validated, [
                'labor_record_id' => $id,
                'recorded_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Observasi kontraksi ber hasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan observasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store fetal monitoring observation
     */
    public function storeFetalMonitoring(Request $request, $id)
    {
        $validated = $request->validate([
            'observation_time' => 'required|date',
            'fetal_heart_rate' => 'required|integer|min:60|max:220',
            'amniotic_fluid_color' => 'required|in:intact,clear,meconium,blood',
            'notes' => 'nullable|string',
        ]);

        try {
            FetalMonitoring::create(array_merge($validated, [
                'labor_record_id' => $id,
                'recorded_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Observasi kondisi janin berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan observasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store maternal vitals observation
     */
    public function storeMaternalVitals(Request $request, $id)
    {
        $validated = $request->validate([
            'observation_time' => 'required|date',
            'blood_pressure_systolic' => 'nullable|integer|min:50|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:30|max:150',
            'pulse_rate' => 'nullable|integer|min:40|max:200',
            'temperature' => 'nullable|numeric|min:35|max:42',
            'respiration_rate' => 'nullable|integer|min:10|max:60',
            'notes' => 'nullable|string',
        ]);

        try {
            MaternalVital::create(array_merge($validated, [
                'labor_record_id' => $id,
                'recorded_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Observasi vital signs berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan observasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store maternal urine observation
     */
    public function storeUrine(Request $request, $id)
    {
        $validated = $request->validate([
            'observation_time' => 'required|date',
            'volume_ml' => 'nullable|integer|min:0',
            'protein' => 'nullable|in:negative,trace,1+,2+,3+,4+',
            'acetone' => 'nullable|in:negative,trace,1+,2+,3+,4+',
            'notes' => 'nullable|string',
        ]);

        try {
            MaternalUrine::create(array_merge($validated, [
                'labor_record_id' => $id,
                'recorded_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Observasi urine berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan observasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store medication/fluid administration
     */
    public function storeMedication(Request $request, $id)
    {
        $validated = $request->validate([
            'administration_time' => 'required|date',
            'medication_type' => 'required|in:drug,iv_fluid,oxytocin',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'route' => 'required|in:oral,iv,im,sc',
            'notes' => 'nullable|string',
        ]);

        try {
            LaborMedication::create(array_merge($validated, [
                'labor_record_id' => $id,
                'administered_by' => Auth::id(),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Pemberian obat/cairan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chart data for partograph visualization
     */
    public function getChartData($id)
    {
        $laborRecord = LaborRecord::with([
            'progresses',
            'contractions',
            'fetalMonitorings',
            'maternalVitals',
            'partographLine'
        ])->findOrFail($id);

        // Prepare cervical dilatation data
        $cervicalData = $laborRecord->progresses->map(function ($progress) use ($laborRecord) {
            return [
                'time' => $progress->observation_time->diffInHours($laborRecord->labor_start_time),
                'dilatation' => $progress->cervical_dilatation,
                'timestamp' => $progress->observation_time->format('Y-m-d H:i'),
            ];
        });

        // Prepare fetal descent data
        $descentData = $laborRecord->progresses->map(function ($progress) use ($laborRecord) {
            return [
                'time' => $progress->observation_time->diffInHours($laborRecord->labor_start_time),
                'descent' => $progress->getDescentNumeric(),
                'label' => $progress->fetal_head_descent,
            ];
        });

        // Prepare fetal heart rate data
        $fhrData = $laborRecord->fetalMonitorings->map(function ($monitoring) use ($laborRecord) {
            return [
                'time' => $monitoring->observation_time->diffInHours($laborRecord->labor_start_time),
                'rate' => $monitoring->fetal_heart_rate,
                'timestamp' => $monitoring->observation_time->format('Y-m-d H:i'),
            ];
        });

        // Prepare alert and action lines
        $alertLine = [];
        $actionLine = [];

        if ($laborRecord->partographLine) {
            for ($hour = 0; $hour <= 12; $hour++) {
                $alertLine[] = [
                    'time' => $hour,
                    'dilatation' => min(3 + $hour, 10),
                ];

                $actionLine[] = [
                    'time' => $hour + 4,
                    'dilatation' => min(3 + $hour, 10),
                ];
            }
        }

        // Prepare maternal vitals data
        $vitalData = $laborRecord->maternalVitals->map(function ($vital) use ($laborRecord) {
            return [
                'time' => $vital->observation_time->diffInHours($laborRecord->labor_start_time),
                'pulse' => $vital->pulse_rate,
                'bloodPressure' => $vital->blood_pressure_systolic,
                'temperature' => $vital->temperature,
                'timestamp' => $vital->observation_time->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'cervicalData' => $cervicalData,
            'descentData' => $descentData,
            'fhrData' => $fhrData,
            'vitalData' => $vitalData,
            'alertLine' => $alertLine,
            'actionLine' => $actionLine,
            'laborStartTime' => $laborRecord->labor_start_time->format('Y-m-d H:i'),
        ]);
    }

    /**
     * Check for alerts and abnormal values
     */
    public function checkAlerts($id)
    {
        $laborRecord = LaborRecord::with([
            'progresses',
            'fetalMonitorings',
            'maternalVitals'
        ])->findOrFail($id);

        $alerts = [];

        // Check if crossing alert line
        if ($laborRecord->is_alert) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Pembukaan serviks melewati garis waspada!',
                'priority' => 'high',
            ];
        }

        // Check if crossing action line
        if ($laborRecord->is_action) {
            $alerts[] = [
                'type' => 'danger',
                'message' => 'Pembukaan serviks melewati garis bertindak! Segera konsultasi dokter.',
                'priority' => 'critical',
            ];
        }

        // Check fetal heart rate
        $latestFhr = $laborRecord->fetalMonitorings()->latest('observation_time')->first();
        if ($latestFhr && !$latestFhr->isFetalHeartRateNormal()) {
            $status = $latestFhr->getFetalHeartRateStatus();
            $alerts[] = [
                'type' => 'danger',
                'message' => "DJJ abnormal: {$latestFhr->fetal_heart_rate} bpm ({$status})",
                'priority' => 'critical',
            ];
        }

        // Check vital signs
        $latestVitals = $laborRecord->maternalVitals()->latest('observation_time')->first();
        if ($latestVitals) {
            if ($latestVitals->isHypertensive()) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Tekanan darah tinggi: {$latestVitals->blood_pressure}",
                    'priority' => 'high',
                ];
            }

            if ($latestVitals->isFebrile()) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Suhu tubuh tinggi: {$latestVitals->temperature}°C",
                    'priority' => 'medium',
                ];
            }
        }

        return response()->json([
            'alerts' => $alerts,
            'count' => count($alerts),
        ]);
    }

    /**
     * Export partograph to PDF
     */
    public function exportPDF($id)
    {
        $laborRecord = LaborRecord::with([
            'visit.pasien',
            'midwife',
            'doctor',
            'progresses',
            'contractions',
            'fetalMonitorings',
            'maternalVitals',
            'maternalUrines',
            'medications',
            'partographLine'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('partograf.pdf', compact('laborRecord'))
            ->setPaper('legal', 'portrait');

        $filename = 'Partograf_' . $laborRecord->patient_no_rm . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get chart data for visualization
     */
    public function chartData($id)
    {
        $laborRecord = LaborRecord::with(['partographLine'])->findOrFail($id);

        $progressData = LaborProgress::where('labor_record_id', $id)
            ->orderBy('observation_time', 'asc')
            ->get();

        $cervicalData = $progressData->map(function ($item) use ($laborRecord) {
            $hours = Carbon::parse($laborRecord->labor_start_time)
                ->diffInHours(Carbon::parse($item->observation_time));

            return [
                'time' => round($hours, 1),
                'dilatation' => $item->cervical_dilatation,
                'descent' => $item->fetal_head_descent,
                'timestamp' => Carbon::parse($item->observation_time)->format('d/m H:i')
            ];
        });

        // Get alert and action lines from PartographLine model or calculate them
        $alertLine = [];
        $actionLine = [];

        // Simple calculation for alert line (1cm/hour starting from 4cm)
        // This should clear be replaced by actual logic if PartographLine model has it
        for ($i = 0; $i <= 10; $i++) {
            $alertLine[] = ['time' => $i, 'dilatation' => min(10, 4 + $i)];
            $actionLine[] = ['time' => $i + 4, 'dilatation' => min(10, 4 + $i)]; // Action line is 4 hours to the right
        }

        return response()->json([
            'cervicalData' => $cervicalData,
            'alertLine' => $alertLine,
            'actionLine' => $actionLine,
            'progressData' => $progressData,
            'contractionData' => UterineContraction::where('labor_record_id', $id)->orderBy('observation_time', 'asc')->get(),
            'fetalData' => FetalMonitoring::where('labor_record_id', $id)->orderBy('observation_time', 'asc')->get(),
            'vitalData' => MaternalVital::where('labor_record_id', $id)->orderBy('observation_time', 'asc')->get(),
            'urineData' => MaternalUrine::where('labor_record_id', $id)->orderBy('observation_time', 'asc')->get(),
            'medicationData' => LaborMedication::where('labor_record_id', $id)->orderBy('administration_time', 'asc')->get(),
        ]);
    }



    /**
     * DataTable for Progress
     */
    public function dtProgress($id)
    {
        $query = LaborProgress::where('labor_record_id', $id)->orderBy('observation_time', 'desc');
        return DataTables::of($query)
            ->editColumn('observation_time', function ($row) {
                return Carbon::parse($row->observation_time)->format('d/m/Y H:i');
            })
            ->editColumn('cervical_dilatation', function ($row) {
                return '<span class="badge badge-light-primary">' . $row->cervical_dilatation . ' cm</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editProgress(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteProgress(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['cervical_dilatation', 'action'])
            ->make(true);
    }

    /**
     * DataTable for Contraction
     */
    public function dtContraction($id)
    {
        $query = UterineContraction::where('labor_record_id', $id)->orderBy('observation_time', 'desc');
        return DataTables::of($query)
            ->editColumn('observation_time', function ($row) {
                return Carbon::parse($row->observation_time)->format('d/m/Y H:i');
            })
            ->editColumn('frequency', function ($row) {
                return '<span class="badge badge-light-warning">' . $row->contractions_per_10min . '/10 menit</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editContraction(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteContraction(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['frequency', 'action'])
            ->make(true);
    }

    /**
     * DataTable for Fetal
     */
    public function dtFetal($id)
    {
        $query = FetalMonitoring::where('labor_record_id', $id)->orderBy('observation_time', 'desc');
        return DataTables::of($query)
            ->editColumn('observation_time', function ($row) {
                return Carbon::parse($row->observation_time)->format('d/m/Y H:i');
            })
            ->editColumn('fetal_heart_rate', function ($row) {
                $class = ($row->fetal_heart_rate >= 120 && $row->fetal_heart_rate <= 160) ? 'badge-light-success' : 'badge-light-danger';
                return '<span class="badge ' . $class . '">' . $row->fetal_heart_rate . ' bpm</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editFetal(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteFetal(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['fetal_heart_rate', 'action'])
            ->make(true);
    }

    /**
     * DataTable for Vitals
     */
    public function dtVitals($id)
    {
        $query = MaternalVital::where('labor_record_id', $id)->orderBy('observation_time', 'desc');
        return DataTables::of($query)
            ->editColumn('observation_time', function ($row) {
                return Carbon::parse($row->observation_time)->format('d/m/Y H:i');
            })
            ->addColumn('blood_pressure', function ($row) {
                return ($row->blood_pressure_systolic ?? '-') . '/' . ($row->blood_pressure_diastolic ?? '-');
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editVitals(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteVitals(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * DataTable for Urine
     */
    public function dtUrine($id)
    {
        $query = MaternalUrine::where('labor_record_id', $id)->orderBy('observation_time', 'desc');
        return DataTables::of($query)
            ->editColumn('observation_time', function ($row) {
                return Carbon::parse($row->observation_time)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editUrine(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteUrine(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * DataTable for Medication
     */
    public function dtMedication($id)
    {
        $query = LaborMedication::where('labor_record_id', $id)->orderBy('administration_time', 'desc');
        return DataTables::of($query)
            ->editColumn('administration_time', function ($row) {
                return Carbon::parse($row->administration_time)->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="editMedication(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteMedication(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroyProgress($laborRecordId, $id)
    {
        LaborProgress::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function destroyContraction($laborRecordId, $id)
    {
        UterineContraction::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function destroyFetal($laborRecordId, $id)
    {
        FetalMonitoring::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function destroyVitals($laborRecordId, $id)
    {
        MaternalVital::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function destroyUrine($laborRecordId, $id)
    {
        MaternalUrine::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function destroyMedication($laborRecordId, $id)
    {
        LaborMedication::where('labor_record_id', $laborRecordId)->findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
