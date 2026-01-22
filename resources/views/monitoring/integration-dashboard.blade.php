@extends('layouts.index')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <!-- Page Title -->
            <div class="d-flex flex-stack mb-5">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Monitoring Integrasi
                    </h1>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                     <a href="{{ route('monitoring.integrasi') }}" class="btn btn-sm fw-bold btn-primary">Refresh</a>
                </div>
            </div>

            <!-- BPJS Section -->
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">BPJS V-Claim Monitoring</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Real-time status of BPJS V-Claim Bridge</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="row g-5 g-xl-8 mb-5">
                        <div class="col-xl-3">
                            <div class="card bg-light-primary card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-primary">{{ $bpjsTotal }}</div>
                                    <div class="fw-semibold fs-6 text-primary lh-1 display-7">Total Request Hari Ini</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card bg-light-success card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-success">{{ $bpjsSuccessRate }}%</div>
                                    <div class="fw-semibold fs-6 text-success lh-1 display-7">Success Rate</div>
                                </div>
                            </div>
                        </div>
                         <div class="col-xl-3">
                            <div class="card bg-light-danger card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-danger">{{ $bpjsError }}</div>
                                    <div class="fw-semibold fs-6 text-danger lh-1 display-7">Errors</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Waktu</th>
                                    <th class="min-w-140px">Action</th>
                                    <th class="min-w-120px">Method</th>
                                    <th class="min-w-120px">URL</th>
                                    <th class="min-w-100px">Code</th>
                                    <th class="min-w-100px">Duration</th>
                                    <th class="min-w-100px text-end">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bpjsLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('H:i:s d-m-Y') }}</td>
                                    <td><span class="text-dark fw-bold text-hover-primary fs-6">{{ $log->name ?? '-' }}</span></td>
                                    <td><span class="badge badge-light fw-bold">{{ $log->methhod }}</span></td>
                                    <td><span class="text-muted fw-semibold text-muted d-block fs-7">{{ Str::limit($log->url, 40) }}</span></td>
                                    <td>
                                        @if($log->code == '200')
                                            <span class="badge badge-light-success">200 OK</span>
                                        @else
                                            <span class="badge badge-light-danger">{{ $log->code }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->time_request }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                            data-bs-toggle="tooltip" title="{{ Str::limit($log->response, 100) }}">
                                            <i class="ki-outline ki-eye fs-2"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data log untuk hari ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SATUSEHAT Section -->
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">SATUSEHAT Monitoring</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Real-time status of Kemenkes SatuSehat Bridge</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                     <div class="row g-5 g-xl-8 mb-5">
                        <div class="col-xl-3">
                            <div class="card bg-light-primary card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-primary">{{ $ssTotal }}</div>
                                    <div class="fw-semibold fs-6 text-primary lh-1 display-7">Total Request Hari Ini</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card bg-light-success card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-success">{{ $ssSuccessRate }}%</div>
                                    <div class="fw-semibold fs-6 text-success lh-1 display-7">Success Rate</div>
                                </div>
                            </div>
                        </div>
                         <div class="col-xl-3">
                            <div class="card bg-light-danger card-xl-stretch mb-xl-8">
                                <div class="card-body my-3">
                                    <div class="fs-2hx fw-bold text-danger">{{ $ssError }}</div>
                                    <div class="fw-semibold fs-6 text-danger lh-1 display-7">Errors</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Waktu</th>
                                    <th class="min-w-140px">Action</th>
                                    <th class="min-w-120px">Method</th>
                                    <th class="min-w-120px">URL</th>
                                    <th class="min-w-100px">Code</th>
                                    <th class="min-w-100px">Duration</th>
                                    <th class="min-w-100px text-end">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ssLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('H:i:s d-m-Y') }}</td>
                                    <td><span class="text-dark fw-bold text-hover-primary fs-6">{{ $log->name ?? '-' }}</span></td>
                                    <td><span class="badge badge-light fw-bold">{{ $log->method }}</span></td>
                                    <td><span class="text-muted fw-semibold text-muted d-block fs-7">{{ Str::limit($log->url, 40) }}</span></td>
                                    <td>
                                        @if($log->code == '200' || $log->code == '201')
                                            <span class="badge badge-light-success">{{ $log->code }} OK</span>
                                        @else
                                            <span class="badge badge-light-danger">{{ $log->code }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->time }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                            data-bs-toggle="tooltip" title="{{ Str::limit($log->response, 100) }}">
                                            <i class="ki-outline ki-eye fs-2"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data log untuk hari ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
