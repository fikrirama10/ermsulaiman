@extends('layouts.index')
@section('css')
<style>
    .dashboard-bed-card {
        min-height: 140px;
        padding: 0.75rem !important;
        border-radius: 0.5rem;
    }
    .dashboard-bed-card .fs-2x { font-size: 1.25rem !important; }
    .dashboard-bed-card .fs-7 { font-size: 0.75rem !important; }
    .dashboard-bed-card .badge { font-size: 0.7rem; padding: 0.2em 0.5em; }
    .dashboard-bed-card .progress { height: 4px !important; }
    .dashboard-bed-card .mb-2 { margin-bottom: 0.3rem !important; }
    .dashboard-bed-card .pt-2 { padding-top: 0.3rem !important; }
    .dashboard-bed-card .p-3 { padding: 0.35rem !important; }
    .dashboard-bed-card .symbol-45px { width: 28px; height: 28px; }
    .dashboard-bed-card h5 { font-size: 0.9rem; line-height: 1.1; }
    .dashboard-bed-card .card-body { padding: 0.75rem !important; }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    @media (min-width: 768px) {
        .dashboard-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (min-width: 992px) {
        .dashboard-grid { grid-template-columns: repeat(5, 1fr); }
    }
    @media (min-width: 1200px) {
        .dashboard-grid { grid-template-columns: repeat(6, 1fr); }
    }
    @media (min-width: 1400px) {
        .dashboard-grid { grid-template-columns: repeat(7, 1fr); }
    }
    .dashboard-container-fit {
        height: calc(100vh - 140px);
        overflow-y: auto;
    }
    .app-toolbar { padding-top: 1rem !important; padding-bottom: 0.5rem !important; }
    .dashboard-title { margin-bottom: 0.75rem !important; }
    .dashboard-title h3 { margin-bottom: 0 !important; font-size: 1.1rem; }
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-2 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-4 m-0">Dashboard</h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="dashboard-title d-flex flex-wrap flex-stack">
                <!--begin::Title-->
                <h3 class="fw-bold">Ketersediaan Tempat Tidur</h3>
                <!--end::Title-->
            </div>
            <div class="dashboard-container-fit">
                <div class="dashboard-grid">
                @foreach ($ruangan as $val)
                    @php
                        $total = $val->bed_count;
                        $kosong = $val->bed_kosong_count;
                        $terisi = $total - $kosong;
                        $percentage = $total > 0 ? round(($kosong / $total) * 100) : 0;
                    @endphp
                    <div>
                        <div class="card dashboard-bed-card shadow-sm hover-elevate-up h-100">
                            <div class="card-body">
                                <div class="text-center mb-2">
                                    <h5 class="mb-1 fw-bold text-gray-800">{{ $val->nama_ruangan }}</h5>
                                    <span class="badge badge-light-info badge-sm">{{ $val->kelas->kelas }}</span>
                                </div>
                                <div class="row g-1 mb-2">
                                    <div class="col-6">
                                        <div class="bg-light-success rounded p-3 text-center">
                                            <div class="fs-2x fw-bold text-success">{{ $kosong }}</div>
                                            <div class="fs-7 text-gray-600 fw-semibold">Tersedia</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light-danger rounded p-3 text-center">
                                            <div class="fs-2x fw-bold text-danger">{{ $terisi }}</div>
                                            <div class="fs-7 text-gray-600 fw-semibold">Terisi</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-7 fw-semibold text-primary">{{ $percentage }}%</span>
                                        <span class="fs-7 text-gray-600">{{ $total }} Total</span>
                                    </div>
                                    <div class="progress bg-light-primary mt-1">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="text-center pt-2 border-top border-gray-300">
                                    <span class="badge badge-{{ $kosong > 0 ? 'success' : 'danger' }}">
                                        {{ $kosong > 0 ? 'Tersedia' : 'Penuh' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
@endsection
@section('js')
<script>
    $(function(){

    });
</script>
@endsection
