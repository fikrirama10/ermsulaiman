@extends('layouts.index')

@section('css')
<style>
    .partograf-header {
        background: #0064c8ff;
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .observation-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .observation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
        position: relative;
        height: 500px;
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .alert-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .timeline-item {
        border-left: 2px solid #e4e6ef;
        padding-left: 1.5rem;
        padding-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item::before {
        content: "";
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #009EF7;
    }

    .timeline-item:last-child {
        border-left: 0;
    }
</style>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    <i class="fas fa-chart-line text-primary me-2"></i> Monitoring Partograf
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('partograf.index') }}" class="text-muted text-hover-primary">Partograf</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Monitoring</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('partograf.pdf', $laborRecord->id) }}" class="btn btn-sm btn-light-primary" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('partograf.edit', $laborRecord->id) }}" class="btn btn-sm btn-light">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Patient Header-->
            <div class="partograf-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="text-white mb-2">{{ $laborRecord->visit->pasien->nama_pasien ?? '-' }}</h2>
                        <div class="d-flex gap-4 text-white-50">
                            <span><i class="fas fa-id-card me-2"></i>RM: {{ $laborRecord->patient_no_rm }}</span>
                            <span><i class="fas fa-venus me-2"></i>G{{ $laborRecord->gravida }}P{{ $laborRecord->para }}A{{ $laborRecord->abortus }}</span>
                            <span><i class="fas fa-calendar me-2"></i>{{ $laborRecord->gestational_age }} minggu</span>
                            <span><i class="fas fa-clock me-2"></i>{{ $laborRecord->labor_duration }} jam</span>
                        </div>
                        <div class="d-flex gap-4 text-white-50 mt-2">
                            <span><i class="fas fa-hourglass-start me-2"></i>Mulai: {{ $laborRecord->labor_start_time->format('d/m/Y H:i') }}</span>
                            @if($laborRecord->membrane_rupture_time)
                            <span><i class="fas fa-tint me-2"></i>Pecah Ketuban: {{ $laborRecord->membrane_rupture_time->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($laborRecord->status == 'ongoing')
                        <span class="badge badge-light-success fs-5 px-4 py-3">
                            <i class="fas fa-circle me-2" style="font-size: 8px;"></i> Persalinan Berlangsung
                        </span>
                        @elseif($laborRecord->status == 'completed')
                        <span class="badge badge-light-info fs-5 px-4 py-3">
                            <i class="fas fa-check-circle me-2"></i> Persalinan Selesai
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Patient Header-->

            <!--begin::Risk Assessment-->
            @if($laborRecord->initial_risk_assessment && count($laborRecord->getActiveRiskFactors()) > 0)
            <div class="card card-flush mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Penilaian Awal Risiko
                    </h3>
                    <div class="card-toolbar">
                        <span class="badge badge-{{ $laborRecord->risk_color }} fs-6 px-4 py-2">
                            <i class="fas fa-circle me-2" style="font-size: 8px;"></i>
                            {{ $laborRecord->risk_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($laborRecord->getActiveRiskFactors() as $key)
                                @php
                                $riskFactors = config('partograf.risk_factors');
                                $label = $riskFactors[$key] ?? $key;
                                @endphp
                                <span class="badge badge-light-danger px-3 py-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $label }}
                                </span>
                                @endforeach
                            </div>
                            @if($laborRecord->initial_assessment_notes)
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4">
                                <i class="fas fa-sticky-note fs-2tx text-warning me-4"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-gray-900 mb-2">Catatan:</div>
                                    <div class="text-gray-700">{{ $laborRecord->initial_assessment_notes }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="text-center bg-light rounded p-4">
                                <div class="fs-7 text-muted mb-2">Faktor Risiko</div>
                                <div class="fs-2x fw-bold text-{{ $laborRecord->risk_color }}">
                                    {{ $laborRecord->risk_factor_count }}
                                </div>
                                <div class="fs-8 text-muted">faktor teridentifikasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!--end::Risk Assessment-->

            <!--begin::Alerts-->
            <div id="alertsContainer"></div>
            <!--end::Alerts-->

            <div class="row g-5 g-xl-8">
                <!--begin::Quick Actions-->
                <div class="col-xl-3">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h3 class="card-title">Input Observasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column gap-3">
                                <button class="btn btn-light-primary w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#progressModal">
                                    <i class="fas fa-heartbeat me-2"></i> Kemajuan Persalinan
                                    <small class="d-block text-muted mt-1">Setiap 4 jam</small>
                                </button>
                                <button class="btn btn-light-warning w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#contractionModal">
                                    <i class="fas fa-wave-square me-2"></i> Kontraksi Uterus
                                    <small class="d-block text-muted mt-1">Setiap 30 menit</small>
                                </button>
                                <button class="btn btn-light-info w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#fetalModal">
                                    <i class="fas fa-baby me-2"></i> Monitoring Janin
                                    <small class="d-block text-muted mt-1">Setiap 30 menit</small>
                                </button>
                                <button class="btn btn-light-success w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#vitalsModal">
                                    <i class="fas fa-stethoscope me-2"></i> Vital Signs Ibu
                                    <small class="d-block text-muted mt-1">Setiap 4 jam</small>
                                </button>
                                <button class="btn btn-light-danger w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#urineModal">
                                    <i class="fas fa-flask me-2"></i> Urine Output
                                    <small class="d-block text-muted mt-1">Setiap BAK</small>
                                </button>
                                <button class="btn btn-light-dark w-100 text-start observation-card"
                                    data-bs-toggle="modal" data-bs-target="#medicationModal">
                                    <i class="fas fa-pills me-2"></i> Obat & Cairan
                                    <small class="d-block text-muted mt-1">Sesuai kebutuhan</small>
                                </button>
                            </div>

                            <div class="separator my-5"></div>

                            <div class="text-center">
                                <h4 class="mb-3">Timer Observasi</h4>
                                <div id="nextObservationTimer" class="fs-2 fw-bold text-primary">
                                    --:--:--
                                </div>
                                <small class="text-muted">Waktu observasi berik utnya</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Quick Actions-->

                <!--begin::Charts-->
                <div class="col-xl-9">
                    <!--begin::Fetal Heart Rate Chart-->
                    <div class="card card-flush mb-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-heartbeat text-danger me-2"></i>
                                Detak Jantung Janin (DJJ)
                            </h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light-info">Normal: 120-160 bpm</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="fhrChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--end::Fetal Heart Rate Chart-->

                    <!--begin::Cervical Dilation Chart-->
                    <div class="card card-flush mb-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                Pembukaan Serviks
                            </h3>
                            <div class="card-toolbar">
                                <button class="btn btn-sm btn-light" onclick="refreshChart()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="partografChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--end::Cervical Dilation Chart-->

                    <!--begin::Maternal Vitals Chart-->
                    <div class="card card-flush mb-5">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-stethoscope text-success me-2"></i>
                                Tanda Vital Ibu
                            </h3>
                            <div class="card-toolbar">
                                <div class="d-flex gap-2">
                                    <span class="badge badge-light-danger">Nadi</span>
                                    <span class="badge badge-light-warning">TD Systolic</span>
                                    <span class="badge badge-light-success">Suhu</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="vitalsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--end::Maternal Vitals Chart-->

                    <!--begin::Latest Observations-->
                    <div class="card card-flush">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Observasi Detail</h3>
                        </div>
                        <div class="card-body">
                            <!--begin::Tabs-->
                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_progress">
                                        <i class="fas fa-heartbeat me-2"></i> Kemajuan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_contraction">
                                        <i class="fas fa-wave-square me-2"></i> Kontraksi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_fetal">
                                        <i class="fas fa-baby me-2"></i> Janin
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_vitals">
                                        <i class="fas fa-stethoscope me-2"></i> Vital Signs
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_urine">
                                        <i class="fas fa-flask me-2"></i> Urine
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_medication">
                                        <i class="fas fa-pills me-2"></i> Obat
                                    </a>
                                </li>
                            </ul>
                            <!--end::Tabs-->

                            <!--begin::Tab content-->
                            <div class="tab-content" id="observationTabs">
                                <!--begin::Progress Tab-->
                                <div class="tab-pane fade show active" id="tab_progress">
                                    <div class="table-responsive">
                                        <table id="tableProgress" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-100px">Pembukaan</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Progress Tab-->

                                <!--begin::Contraction Tab-->
                                <div class="tab-pane fade" id="tab_contraction">
                                    <div class="table-responsive">
                                        <table id="tableContraction" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-100px">Frekuensi</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Contraction Tab-->

                                <!--begin::Fetal Tab-->
                                <div class="tab-pane fade" id="tab_fetal">
                                    <div class="table-responsive">
                                        <table id="tableFetal" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-100px">DJJ (bpm)</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Fetal Tab-->

                                <!--begin::Vitals Tab-->
                                <div class="tab-pane fade" id="tab_vitals">
                                    <div class="table-responsive">
                                        <table id="tableVitals" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-100px">TD (mmHg)</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Vitals Tab-->

                                <!--begin::Urine Tab-->
                                <div class="tab-pane fade" id="tab_urine">
                                    <div class="table-responsive">
                                        <table id="tableUrine" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-100px">Volume (ml)</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Urine Tab-->

                                <!--begin::Medication Tab-->
                                <div class="tab-pane fade" id="tab_medication">
                                    <div class="table-responsive">
                                        <table id="tableMedication" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Waktu</th>
                                                    <th class="min-w-150px">Nama Obat/Cairan</th>
                                                    <th class="min-w-100px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Medication Tab-->
                            </div>
                            <!--end::Tab content-->
                        </div>
                    </div>
                    <!--end::Latest Observations-->
                </div>
                <!--end::Chart-->
            </div>

        </div>
    </div>
    <!--end::Content-->
</div>

<!-- Include Modals -->
@include('partograf.modals.progress')
@include('partograf.modals.contraction')
@include('partograf.modals.fetal')
@include('partograf.modals.vitals')
@include('partograf.modals.urine')
@include('partograf.modals.medication')

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const laborRecordId = "{{  $laborRecord->id }}"
    let partografChart = null;

    $(document).ready(function() {
        // Set current time as default
        $('#progressModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formProgress').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/progress`,
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#progressModal').modal('hide');
                    toastr.success(response.message);
                    $('#formProgress')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

        $('#contractionModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formContraction').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/contraction`,
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#contractionModal').modal('hide');
                    toastr.success(response.message);
                    $('#formContraction')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

        $('#fetalModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formFetal').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/fetal`, // TODO: Create this route
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#fetalModal').modal('hide');
                    toastr.success(response.message);
                    $('#formFetal')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

        $('#vitalsModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formVitals').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/vitals`, // TODO: Create this route
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#vitalsModal').modal('hide');
                    toastr.success(response.message);
                    $('#formVitals')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

        $('#urineModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formUrine').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/urine`, // TODO: Create this route
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#urineModal').modal('hide');
                    toastr.success(response.message);
                    $('#formUrine')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

        $('#medicationModal').on('show.bs.modal', function() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('input[name="observation_time"]', this).val(now.toISOString().slice(0, 16));
        });

        $('#formMedication').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/partograf/${laborRecordId}/medication`, // TODO: Create this route
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#medicationModal').modal('hide');
                    toastr.success(response.message);
                    $('#formMedication')[0].reset();
                    loadChartData();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data');
                }
            });
        });

    });

    $(document).ready(function() {
        // Load initial chart
        loadChartData();

        // Check for alerts
        checkAlerts();

        // Auto-refresh every 30 seconds
        setInterval(function() {
            loadChartData();
            checkAlerts();
        }, 30000);

        // Start observation timer
        startObservationTimer();
    });

    function loadChartData() {
        $.ajax({
            url: `/partograf/${laborRecordId}/chart-data`,
            method: 'GET',
            success: function(data) {
                renderChart(data);
                updateLatestObservations(data);
            },
            error: function(xhr) {
                console.error('Error loading chart data:', xhr);
            }
        });
    }

    function renderChart(data) {
        const ctx = document.getElementById('partografChart').getContext('2d');

        // Destroy existing chart
        if (partografChart) {
            partografChart.destroy();
        }

        partografChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                        label: 'Pembukaan Serviks',
                        data: data.cervicalData.map(d => ({
                            x: d.time,
                            y: d.dilatation
                        })),
                        borderColor: '#009EF7',
                        backgroundColor: 'rgba(0, 158, 247, 0.1)',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        borderWidth: 3,
                        tension: 0.4
                    },
                    {
                        label: 'Garis Waspada',
                        data: data.alertLine.map(d => ({
                            x: d.time,
                            y: d.dilatation
                        })),
                        borderColor: '#FFC700',
                        borderDash: [10, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Garis Bertindak',
                        data: data.actionLine.map(d => ({
                            x: d.time,
                            y: d.dilatation
                        })),
                        borderColor: '#F1416C',
                        borderDash: [10, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'linear',
                        title: {
                            display: true,
                            text: 'Waktu (Jam)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        min: 0,
                        max: 12,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Pembukaan Serviks (cm)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' cm';
                            }
                        }
                    }
                }
            }
        });
    }

    function checkAlerts() {
        $.ajax({
            url: `/partograf/${laborRecordId}/check-alerts`,
            method: 'GET',
            success: function(data) {
                const container = $('#alertsContainer');
                container.empty();

                if (data.alerts && data.alerts.length > 0) {
                    data.alerts.forEach(alert => {
                        const alertClass = alert.type === 'danger' ? 'alert-danger' : 'alert-warning';
                        const icon = alert.type === 'danger' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';

                        container.append(`
                        <div class="alert ${alertClass} alert-dismissible fade show mb-3" role="alert">
                            <i class="fas ${icon} me-2 alert-badge"></i>
                            <strong>${alert.priority === 'critical' ? 'KRITIS!' : 'PERHATIAN!'}</strong> ${alert.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    });
                }
            }
        });
    }

    function updateLatestObservations(data) {
        let observationsHTML = '';

        // Show latest cervical dilatation
        if (data.cervicalData && data.cervicalData.length > 0) {
            const latest = data.cervicalData[data.cervicalData.length - 1];
            observationsHTML += `
                <div class="timeline-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold fs-6">
                                <i class="fas fa-heartbeat text-primary me-2"></i>
                                Kemajuan Persalinan
                            </div>
                            <div class="text-muted fs-7 mt-1">${latest.timestamp}</div>
                        </div>
                        <span class="badge badge-light-primary fs-4">${latest.dilatation} cm</span>
                    </div>
                    ${latest.descent ? `<div class="text-muted fs-7 mt-2">Penurunan: ${latest.descent}</div>` : ''}
                </div>
            `;
        }

        // Show latest contraction
        if (data.contractionData && data.contractionData.length > 0) {
            const latest = data.contractionData[data.contractionData.length - 1];
            observationsHTML += `
                <div class="timeline-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold fs-6">
                                <i class="fas fa-wave-square text-warning me-2"></i>
                                Kontraksi Uterus
                            </div>
                            <div class="text-muted fs-7 mt-1">${latest.timestamp}</div>
                        </div>
                        <span class="badge badge-light-warning">${latest.frequency}/10 menit</span>
                    </div>
                    ${latest.intensity ? `<div class="text-muted fs-7 mt-2">Intensitas: ${latest.intensity}</div>` : ''}
                </div>
            `;
        }

        // Show latest fetal monitoring
        if (data.fetalData && data.fetalData.length > 0) {
            const latest = data.fetalData[data.fetalData.length - 1];
            const heartRateClass = (latest.heartRate >= 120 && latest.heartRate <= 160) ?
                'badge-light-success' : 'badge-light-danger';
            observationsHTML += `
                <div class="timeline-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold fs-6">
                                <i class="fas fa-baby text-info me-2"></i>
                                Denyut Jantung Janin
                            </div>
                            <div class="text-muted fs-7 mt-1">${latest.timestamp}</div>
                        </div>
                        <span class="badge ${heartRateClass} fs-5">${latest.heartRate} bpm</span>
                    </div>
                    ${latest.fluidColor ? `<div class="text-muted fs-7 mt-2">Air ketuban: ${latest.fluidColor}</div>` : ''}
                </div>
            `;
        }

        // Show latest vital signs
        if (data.vitalData && data.vitalData.length > 0) {
            const latest = data.vitalData[data.vitalData.length - 1];
            observationsHTML += `
                <div class="timeline-item">
                    <div class="fw-bold fs-6">
                        <i class="fas fa-stethoscope text-success me-2"></i>
                        Vital Signs Ibu
                    </div>
                    <div class="text-muted fs-7 mt-1">${latest.timestamp}</div>
                    <div class="row g-2 mt-2">
                        ${latest.bloodPressure ? `<div class="col-6"><small class="text-muted">TD:</small> <span class="fw-bold">${latest.bloodPressure}</span></div>` : ''}
                        ${latest.pulse ? `<div class="col-6"><small class="text-muted">Nadi:</small> <span class="fw-bold">${latest.pulse} x/mnt</span></div>` : ''}
                        ${latest.temperature ? `<div class="col-6"><small class="text-muted">Suhu:</small> <span class="fw-bold">${latest.temperature}°C</span></div>` : ''}
                        ${latest.respiration ? `<div class="col-6"><small class="text-muted">RR:</small> <span class="fw-bold">${latest.respiration} x/mnt</span></div>` : ''}
                    </div>
                </div>
            `;
        }

        // If no observations yet
        if (observationsHTML === '') {
            observationsHTML = `
                <div class="text-center text-muted py-10">
                    <i class="fas fa-clipboard-list fs-3x mb-3"></i>
                    <p>Belum ada observasi</p>
                    <small>Mulai input observasi dengan klik tombol di samping</small>
                </div>
            `;
        }

        $('#latestObservations').html(observationsHTML);

        // Also populate the tables
        // populateAllTables(data);
    }

    function populateAllTables_OLD(data) {
        // Populate Progress Table
        if (data.progressData && data.progressData.length > 0) {
            let html = '';
            data.progressData.slice().reverse().forEach(item => {
                html += `
                    <tr>
                        <td><small class="text-muted">${item.observation_time}</small></td>
                        <td><span class="badge badge-light-primary">${item.cervical_dilatation} cm</span></td>
                        <td>${item.fetal_head_descent || '-'}</td>
                        <td>${item.molding || '-'}</td>
                        <td>${item.position || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('progress', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#progressTableBody').html(html);
        }

        // Populate Contraction Table
        if (data.contractionData && data.contractionData.length > 0) {
            let html = '';
            data.contractionData.slice().reverse().forEach(item => {
                html += `
                    <tr>
                        <td><small class="text-muted">${item.observation_time}</small></td>
                        <td><span class="badge badge-light-warning">${item.contractions_per_10min}/10 menit</span></td>
                        <td>${item.duration_seconds || '-'} detik</td>
                        <td>${item.intensity || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('contraction', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#contractionTableBody').html(html);
        }

        // Populate Fetal Table
        if (data.fetalData && data.fetalData.length > 0) {
            let html = '';
            data.fetalData.slice().reverse().forEach(item => {
                const heartRateClass = (item.fetal_heart_rate >= 120 && item.fetal_heart_rate <= 160) ?
                    'badge-light-success' : 'badge-light-danger';
                html += `
                    <tr>
                        <td><small class="text-muted">${item.observation_time}</small></td>
                        <td><span class="badge ${heartRateClass}">${item.fetal_heart_rate} bpm</span></td>
                        <td>${item.amniotic_fluid_color || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('fetal', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#fetalTableBody').html(html);
        }

        // Populate Vitals Table
        if (data.vitalData && data.vitalData.length > 0) {
            let html = '';
            data.vitalData.slice().reverse().forEach(item => {
                const bp = `${item.blood_pressure_systolic || '-'}/${item.blood_pressure_diastolic || '-'}`;
                html += `
                    <tr>
                        <td><small class="text-muted">${item.observation_time}</small></td>
                        <td>${bp}</td>
                        <td>${item.pulse_rate || '-'}</td>
                        <td>${item.temperature || '-'}</td>
                        <td>${item.respiration_rate || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('vitals', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#vitalsTableBody').html(html);
        }

        // Populate Urine Table
        if (data.urineData && data.urineData.length > 0) {
            let html = '';
            data.urineData.slice().reverse().forEach(item => {
                html += `
                    <tr>
                        <td><small class="text-muted">${item.observation_time}</small></td>
                        <td>${item.volume_ml || '-'} ml</td>
                        <td>${item.protein || '-'}</td>
                        <td>${item.acetone || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('urine', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#urineTableBody').html(html);
        }

        // Populate Medication Table
        if (data.medicationData && data.medicationData.length > 0) {
            let html = '';
            data.medicationData.slice().reverse().forEach(item => {
                html += `
                    <tr>
                        <td><small class="text-muted">${item.administration_time}</small></td>
                        <td><span class="badge badge-secondary">${item.medication_type}</span></td>
                        <td>${item.medication_name || '-'}</td>
                        <td>${item.dosage || '-'}</td>
                        <td>${item.route || '-'}</td>
                        <td><small>${item.notes || '-'}</small></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteObservation('medication', ${item.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#medicationTableBody').html(html);
        }
    }

    // Delete observation function
    function deleteObservation_OLD(type, id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

        $.ajax({
            url: `/partograf/${laborRecordId}/${type}/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('Data berhasil dihapus');
                loadChartData();
            },
            error: function(xhr) {
                toastr.error('Gagal menghapus data');
            }
        });
    }

    function startObservationTimer() {
        // Simple countdown timer for next observation
        let nextObservation = new Date();
        nextObservation.setMinutes(nextObservation.getMinutes() + 30);

        setInterval(function() {
            const now = new Date();
            const diff = nextObservation - now;

            if (diff > 0) {
                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                $('#nextObservationTimer').text(
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0')
                );
            }
        }, 1000);
    }

    function refreshChart() {
        loadChartData();
        toastr.success('Chart berhasil direfresh');
    }

    // DataTables Initialization
    $(document).ready(function() {
        // Shared options for all tables
        const dtOptions = {
            processing: true,
            serverSide: true,
            order: [
                [0, 'desc']
            ],
            language: {
                "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing": "Sedang memproses...",
                "sLengthMenu": "Tampilkan _MENU_ entri",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir"
                }
            }
        };

        // Progress Table
        $('#tableProgress').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/progress`,
            columns: [{
                    data: 'observation_time',
                    name: 'observation_time'
                },
                {
                    data: 'cervical_dilatation',
                    name: 'cervical_dilatation'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#tableContraction').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/contraction`,
            columns: [{
                    data: 'observation_time',
                    name: 'observation_time'
                },
                {
                    data: 'frequency',
                    name: 'frequency'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#tableFetal').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/fetal`,
            columns: [{
                    data: 'observation_time',
                    name: 'observation_time'
                },
                {
                    data: 'fetal_heart_rate',
                    name: 'fetal_heart_rate'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#tableVitals').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/vitals`,
            columns: [{
                    data: 'observation_time',
                    name: 'observation_time'
                },
                {
                    data: 'blood_pressure',
                    name: 'blood_pressure'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#tableUrine').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/urine`,
            columns: [{
                    data: 'observation_time',
                    name: 'observation_time'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#tableMedication').DataTable({
            ...dtOptions,
            ajax: `/partograf/${laborRecordId}/dt/medication`,
            columns: [{
                    data: 'administration_time',
                    name: 'administration_time'
                },
                {
                    data: 'medication_name',
                    name: 'medication_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });

    // Wrappers for delete actions
    function deleteProgress(id) {
        deleteObservation('progress', id, '#tableProgress');
    }

    function deleteContraction(id) {
        deleteObservation('contraction', id, '#tableContraction');
    }

    function deleteFetal(id) {
        deleteObservation('fetal', id, '#tableFetal');
    }

    function deleteVitals(id) {
        deleteObservation('vitals', id, '#tableVitals');
    }

    function deleteUrine(id) {
        deleteObservation('urine', id, '#tableUrine');
    }

    function deleteMedication(id) {
        deleteObservation('medication', id, '#tableMedication');
    }

    // Edit placeholders
    function editProgress(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    function editContraction(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    function editFetal(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    function editVitals(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    function editUrine(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    function editMedication(id) {
        toastr.info('Fitur edit akan segera hadir');
    }

    // New delete observation function
    function deleteObservation(type, id, tableId) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

        $.ajax({
            url: `/partograf/${laborRecordId}/${type}/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success('Data berhasil dihapus');
                $(tableId).DataTable().ajax.reload(null, false);
                loadChartData();
            },
            error: function(xhr) {
                toastr.error('Gagal menghapus data');
            }
        });
    }

    // Chart instances
    let fhrChart = null;
    let cervicalChart = null;
    let vitalsChart = null;

    // Initialize Charts
    function initializeCharts() {
        // FHR Chart
        const fhrCtx = document.getElementById('fhrChart');
        if (fhrCtx) {
            fhrChart = new Chart(fhrCtx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Detak Jantung Janin',
                        data: [],
                        borderColor: '#F1416C',
                        backgroundColor: 'rgba(241, 65, 108, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 100,
                            max: 180,
                            ticks: {
                                stepSize: 10,
                                callback: function(value) {
                                    return value + ' bpm';
                                }
                            },
                            grid: {
                                color: function(context) {
                                    // Highlight normal range (120-160)
                                    if (context.tick.value >= 120 && context.tick.value <= 160) {
                                        return 'rgba(80, 205, 137, 0.3)';
                                    }
                                    return 'rgba(0, 0, 0, 0.1)';
                                }
                            }
                        },
                        x: {
                            type: 'linear',
                            title: {
                                display: true,
                                text: 'Jam'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' jam';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'DJJ: ' + context.parsed.y + ' bpm';
                                }
                            }
                        },
                        annotation: {
                            annotations: {
                                normalRangeBox: {
                                    type: 'box',
                                    yMin: 120,
                                    yMax: 160,
                                    backgroundColor: 'rgba(80, 205, 137, 0.05)',
                                    borderColor: 'rgba(80, 205, 137, 0.3)',
                                    borderWidth: 1,
                                    label: {
                                        display: true,
                                        content: 'Normal Range',
                                        position: 'start'
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }

        // Cervical Dilation Chart (existing partografChart)
        const cervicalCtx = document.getElementById('partografChart');
        if (cervicalCtx) {
            cervicalChart = new Chart(cervicalCtx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Pembukaan Serviks',
                        data: [],
                        borderColor: '#009EF7',
                        backgroundColor: 'rgba(0, 158, 247, 0.1)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }, {
                        label: 'Garis Waspada',
                        data: [],
                        borderColor: '#FFC700',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }, {
                        label: 'Garis Bertindak',
                        data: [],
                        borderColor: '#F1416C',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 10,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + ' cm';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Pembukaan (cm)'
                            }
                        },
                        x: {
                            type: 'linear',
                            title: {
                                display: true,
                                text: 'Jam'
                            },
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value + ' jam';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Maternal Vitals Chart
        const vitalsCtx = document.getElementById('vitalsChart');
        if (vitalsCtx) {
            vitalsChart = new Chart(vitalsCtx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Nadi',
                        data: [],
                        borderColor: '#F1416C',
                        backgroundColor: 'rgba(241, 65, 108, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y-pulse'
                    }, {
                        label: 'TD Systolic',
                        data: [],
                        borderColor: '#FFC700',
                        backgroundColor: 'rgba(255, 199, 0, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y-bp'
                    }, {
                        label: 'Suhu',
                        data: [],
                        borderColor: '#50CD89',
                        backgroundColor: 'rgba(80, 205, 137, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y-temp'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        'y-pulse': {
                            type: 'linear',
                            position: 'left',
                            min: 60,
                            max: 120,
                            ticks: {
                                stepSize: 10,
                                callback: function(value) {
                                    return value + ' bpm';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Nadi (bpm)',
                                color: '#F1416C'
                            },
                            grid: {
                                drawOnChartArea: true
                            }
                        },
                        'y-bp': {
                            type: 'linear',
                            position: 'right',
                            min: 90,
                            max: 180,
                            ticks: {
                                stepSize: 10,
                                callback: function(value) {
                                    return value + ' mmHg';
                                }
                            },
                            title: {
                                display: true,
                                text: 'TD Systolic (mmHg)',
                                color: '#FFC700'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        'y-temp': {
                            type: 'linear',
                            position: 'right',
                            min: 35,
                            max: 40,
                            ticks: {
                                stepSize: 0.5,
                                callback: function(value) {
                                    return value + '°C';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Suhu (°C)',
                                color: '#50CD89'
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            display: false
                        },
                        x: {
                            type: 'linear',
                            title: {
                                display: true,
                                text: 'Jam'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' jam';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        }
    }

    // Load and update chart data
    function loadChartData() {
        $.ajax({
            url: `/partograf/${laborRecordId}/chart-data`,
            method: 'GET',
            success: function(data) {
                updateCharts(data);
            },
            error: function(xhr) {
                console.error('Failed to load chart data:', xhr);
            }
        });
    }

    function updateCharts(data) {
        // Update FHR Chart
        if (fhrChart && data.fhrData) {
            const fhrPoints = data.fhrData.map(item => ({
                x: item.time,
                y: item.rate
            }));
            fhrChart.data.datasets[0].data = fhrPoints;
            fhrChart.update();
        }

        // Update Cervical Chart
        if (cervicalChart && data.cervicalData) {
            // Map cervicalData to {x, y} format
            const cervicalPoints = data.cervicalData.map(item => ({
                x: item.time,
                y: item.dilatation
            }));
            cervicalChart.data.datasets[0].data = cervicalPoints;

            // Update alert line
            if (data.alertLine && data.alertLine.length > 0) {
                const alertPoints = data.alertLine.map(item => ({
                    x: item.time,
                    y: item.dilatation
                }));
                cervicalChart.data.datasets[1].data = alertPoints;
            }

            // Update action line
            if (data.actionLine && data.actionLine.length > 0) {
                const actionPoints = data.actionLine.map(item => ({
                    x: item.time,
                    y: item.dilatation
                }));
                cervicalChart.data.datasets[2].data = actionPoints;
            }

            cervicalChart.update();
        }

        // Update Vitals Chart
        if (vitalsChart && data.vitalData) {
            const pulseData = data.vitalData.map(item => ({
                x: item.time,
                y: item.pulse || item.pulse_rate
            }));
            const bpData = data.vitalData.map(item => ({
                x: item.time,
                y: item.bloodPressure || item.blood_pressure_systolic
            }));
            const tempData = data.vitalData.map(item => ({
                x: item.time,
                y: item.temperature
            }));

            vitalsChart.data.datasets[0].data = pulseData;
            vitalsChart.data.datasets[1].data = bpData;
            vitalsChart.data.datasets[2].data = tempData;
            vitalsChart.update();
        }
    }

    function refreshChart() {
        loadChartData();
        toastr.success('Chart berhasil direfresh');
    }

    // Initialize on page load
    $(document).ready(function() {
        initializeCharts();
        loadChartData();

        // Refresh charts every 30 seconds
        setInterval(loadChartData, 30000);
    });
</script>

<!-- Chart.js v3 Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>

@endsection