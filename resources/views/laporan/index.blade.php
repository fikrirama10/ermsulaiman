@extends('layouts.index')
@section('css')
<!-- Highcharts CSS -->
<link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css" />
<style>
    .chart-container {
        min-height: 400px;
    }
</style>
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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Laporan</h1>
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
                        <li class="breadcrumb-item text-muted">Laporan</li>
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

            <!--begin::Charts Row-->
            <div class="row g-5 mb-5">
                <!-- Chart Kunjungan Bulanan -->
                <div class="col-lg-6">
                    <div class="card card-stretch mb-5 mb-xxl-8">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="fw-bold">Statistik Kunjungan Bulanan</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-kunjungan-bulanan" class="chart-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart Kunjungan per Poli -->
                <div class="col-lg-6">
                    <div class="card card-stretch mb-5 mb-xxl-8">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="fw-bold">Kunjungan per Poli</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-kunjungan-poli" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Charts Row 2-->
            <div class="row g-5 mb-5">
                <!-- Chart Demografi - Jenis Kelamin -->
                <div class="col-lg-4">
                    <div class="card card-stretch mb-5 mb-xxl-8">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="fw-bold">Demografi - Jenis Kelamin</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-demografi-gender" class="chart-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart Demografi - Kategori Usia -->
                <div class="col-lg-4">
                    <div class="card card-stretch mb-5 mb-xxl-8">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="fw-bold">Demografi - Kategori Usia</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-demografi-usia" class="chart-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Chart Cara Bayar -->
                <div class="col-lg-4">
                    <div class="card card-stretch mb-5 mb-xxl-8">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="fw-bold">Kunjungan per Cara Bayar</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-cara-bayar" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::FAQ card-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="card-title">Pilih Jenis Laporan</h5>
                    </div>
                </div>
                <!--begin::Body-->
                <div class="card-body p-lg-15">
                    <div class="row g-5">
                        <!-- Laporan Kunjungan -->
                        <div class="col-md-4">
                            <div class="card card-stretch mb-5 mb-xxl-8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-success">
                                                <span class="svg-icon svg-icon-3x svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-dark fw-bold">Laporan Kunjungan</h4>
                                            <p class="text-muted fs-7">Data kunjungan pasien rawat jalan & rawat inap</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('laporan.kunjungan') }}" class="btn btn-sm btn-success w-100">Tampilkan Laporan</a>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Rawat Inap -->
                        <div class="col-md-4">
                            <div class="card card-stretch mb-5 mb-xxl-8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <span class="svg-icon svg-icon-3x svg-icon-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-dark fw-bold">Laporan Rawat Inap</h4>
                                            <p class="text-muted fs-7">Data pasien rawat inap berdasarkan ruangan</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('laporan.rawat-inap') }}" class="btn btn-sm btn-primary w-100">Tampilkan Laporan</a>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Demografi -->
                        <div class="col-md-4">
                            <div class="card card-stretch mb-5 mb-xxl-8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-info">
                                                <span class="svg-icon svg-icon-3x svg-icon-info">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-dark fw-bold">Laporan Demografi</h4>
                                            <p class="text-muted fs-7">Data demografi pasien berdasarkan usia & gender</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('laporan.demografi') }}" class="btn btn-sm btn-info w-100">Tampilkan Laporan</a>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Diagnosa -->
                        <div class="col-md-4">
                            <div class="card card-stretch mb-5 mb-xxl-8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <span class="svg-icon svg-icon-3x svg-icon-warning">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-dark fw-bold">10 Diagnosa Terbanyak</h4>
                                            <p class="text-muted fs-7">Data diagnosa terbanyak berdasarkan ICD-X</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('laporan.diagnosa') }}" class="btn btn-sm btn-warning w-100">Tampilkan Laporan</a>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan BPJS -->
                        <div class="col-md-4">
                            <div class="card card-stretch mb-5 mb-xxl-8">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-danger">
                                                <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-dark fw-bold">Laporan BPJS</h4>
                                            <p class="text-muted fs-7">Data pasien BPJS berdasarkan SEP</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('laporan.bpjs') }}" class="btn btn-sm btn-danger w-100">Tampilkan Laporan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::FAQ card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
@endsection
@section('js')
<!-- Highcharts Library -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
$(function(){
    // Chart Kunjungan Bulanan
    $.ajax({
        url: '{{ route('laporan.data-chart-kunjungan-bulanan') }}',
        type: 'GET',
        success: function(response) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const data = response.data;

            Highcharts.chart('chart-kunjungan-bulanan', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Statistik Kunjungan Bulanan (Tahun Berjalan)'
                },
                xAxis: {
                    categories: months
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Kunjungan'
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    data: data,
                    color: '#00CDF2'
                }],
                credits: {
                    enabled: false
                }
            });
        }
    });

    // Chart Kunjungan per Poli
    $.ajax({
        url: '{{ route('laporan.data-chart-kunjungan-poli') }}',
        type: 'GET',
        success: function(response) {
            const data = response.data;

            Highcharts.chart('chart-kunjungan-poli', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Kunjungan per Poli'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Kunjungan'
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    data: data.map(item => ({
                        name: item.poli,
                        y: item.total
                    })),
                    color: '#41A7F5'
                }],
                credits: {
                    enabled: false
                }
            });
        }
    });

    // Chart Demografi - Jenis Kelamin
    $.ajax({
        url: '{{ route('laporan.data-chart-demografi-gender') }}',
        type: 'GET',
        success: function(response) {
            const data = response.data;

            Highcharts.chart('chart-demografi-gender', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Demografi Pasien<br>berdasarkan Jenis Kelamin'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    colorByPoint: true,
                    data: data.map(item => ({
                        name: item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                        y: item.total
                    }))
                }],
                credits: {
                    enabled: false
                }
            });
        }
    });

    // Chart Demografi - Kategori Usia
    $.ajax({
        url: '{{ route('laporan.data-chart-demografi-usia') }}',
        type: 'GET',
        success: function(response) {
            const data = response.data;

            Highcharts.chart('chart-demografi-usia', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Demografi Pasien<br>berdasarkan Kategori Usia'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Pasien'
                    }
                },
                series: [{
                    name: 'Pasien',
                    data: data.map(item => ({
                        name: item.kategori_usia,
                        y: item.total
                    })),
                    colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8']
                }],
                credits: {
                    enabled: false
                }
            });
        }
    });

    // Chart Cara Bayar
    $.ajax({
        url: '{{ route('laporan.data-chart-cara-bayar') }}',
        type: 'GET',
        success: function(response) {
            const data = response.data;

            Highcharts.chart('chart-cara-bayar', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Kunjungan<br>berdasarkan Cara Bayar'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    colorByPoint: true,
                    data: data.map(item => ({
                        name: item.cara_bayar,
                        y: item.total
                    }))
                }],
                credits: {
                    enabled: false
                }
            });
        }
    });
});
</script>
@endsection
