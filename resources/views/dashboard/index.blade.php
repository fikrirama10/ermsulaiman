@extends('layouts.index')
@section('css')
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Dashboard</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Menu</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Dashboard</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
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
            <div class="d-flex flex-wrap flex-stack mb-6" data-select2-id="select2-data-136-zz50">
                <!--begin::Title-->
                <h3 class="fw-bold my-2">
                    Ketersediaan Tempat Tidur
                </h3>
                <!--end::Title-->
            </div>
            <div class="row g-4">
                @foreach ($ruangan as $val)
                    @php
                        $total = $val->bed_count;
                        $kosong = $val->bed_kosong_count;
                        $terisi = $total - $kosong;
                        $percentage = $total > 0 ? round(($kosong / $total) * 100) : 0;
                    @endphp
                    <div class="col-sm-6 col-xl-4">
                        <!--begin::Card-->
                        <div class="card shadow-sm hover-elevate-up h-100">
                            <!--begin::Card body-->
                            <div class="card-body p-5">
                                <!-- Header -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="symbol symbol-45px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="bi bi-hospital fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fw-bold text-gray-800">{{ $val->nama_ruangan }}</h5>
                                        <span class="badge badge-light-info badge-sm">{{ $val->kelas->kelas }}</span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="row g-3 mb-3">
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

                                <!-- Progress Bar -->
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fs-7 fw-semibold text-gray-700">Ketersediaan</span>
                                        <span class="fs-7 fw-bold text-primary">{{ $percentage }}%</span>
                                    </div>
                                    <div class="progress h-8px bg-light-primary">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                <!-- Footer Info -->
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top border-gray-300">
                                    <span class="fs-7 text-gray-600">
                                        <i class="bi bi-border-all fs-6 me-1"></i>Total: <span class="fw-bold">{{ $total }} Bed</span>
                                    </span>
                                    <span class="badge badge-light-{{ $kosong > 0 ? 'success' : 'danger' }}">
                                        {{ $kosong > 0 ? 'Tersedia' : 'Penuh' }}
                                    </span>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                @endforeach
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
