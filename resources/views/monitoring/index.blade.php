@extends('layouts.index')

@section('css')
<style>
.stat-card {
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
.timeline-item {
    border-left: 2px solid #e5e7eb;
    padding-left: 1.5rem;
    position: relative;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #3b82f6;
}
</style>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Monitoring Aktivitas Sistem
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">Dashboard</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Statistics Cards-->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-xl-3">
                    <div class="card stat-card bg-primary shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-white">
                                <div class="fs-2hx fw-bold">{{ number_format($stats['total']) }}</div>
                                <div class="fs-6 fw-semibold opacity-75">Total Aktivitas</div>
                                <div class="fs-7 opacity-50">{{ request('periode', 'today') == 'today' ? 'Hari ini' : 'Periode dipilih' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card stat-card bg-success shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-white">
                                <div class="fs-2hx fw-bold">{{ number_format($stats['kunjungan']) }}</div>
                                <div class="fs-6 fw-semibold opacity-75">Kunjungan Pasien</div>
                                <div class="fs-7 opacity-50">Registrasi & pemeriksaan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card stat-card bg-info shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-white">
                                <div class="fs-2hx fw-bold">{{ number_format($stats['rekam_medis']) }}</div>
                                <div class="fs-6 fw-semibold opacity-75">Rekam Medis</div>
                                <div class="fs-7 opacity-50">Input dokter & perawat</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card stat-card bg-warning shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-white">
                                <div class="fs-2hx fw-bold">{{ number_format($stats['tindak_lanjut']) }}</div>
                                <div class="fs-6 fw-semibold opacity-75">Tindak Lanjut</div>
                                <div class="fs-7 opacity-50">Rujukan & kontrol</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Filter Card-->
            <div class="card shadow-sm mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filter Aktivitas
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-redo me-1"></i>
                            Reset Filter
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('monitoring.index') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Periode</label>
                                <select name="periode" class="form-select form-select-solid">
                                    <option value="">Pilih Periode</option>
                                    <option value="today" {{ request('periode') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ request('periode') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ request('periode') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control form-control-solid" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control form-control-solid" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="log_name" class="form-select form-select-solid">
                                    <option value="">Semua Kategori</option>
                                    <option value="kunjungan" {{ request('log_name') == 'kunjungan' ? 'selected' : '' }}>Kunjungan</option>
                                    <option value="rekam_medis" {{ request('log_name') == 'rekam_medis' ? 'selected' : '' }}>Rekam Medis</option>
                                    <option value="tindak_lanjut" {{ request('log_name') == 'tindak_lanjut' ? 'selected' : '' }}>Tindak Lanjut</option>
                                    <option value="pasien" {{ request('log_name') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Event</label>
                                <select name="event" class="form-select form-select-solid">
                                    <option value="">Semua Event</option>
                                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                                    <option value="viewed" {{ request('event') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Role</label>
                                <select name="user_role" class="form-select form-select-solid">
                                    <option value="">Semua Role</option>
                                    <option value="dokter" {{ request('user_role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="perawat" {{ request('user_role') == 'perawat' ? 'selected' : '' }}>Perawat</option>
                                    <option value="coder" {{ request('user_role') == 'coder' ? 'selected' : '' }}>Coder</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Cari</label>
                                <input type="text" name="search" class="form-control form-control-solid" placeholder="Nama, RM, Poli, Dokter..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!--begin::Activity Timeline-->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history text-primary me-2"></i>
                        Timeline Aktivitas
                        <span class="badge badge-light ms-2">{{ $activities->total() }} aktivitas</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('monitoring.export', request()->query()) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel me-1"></i>
                            Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($activities as $activity)
                    <div class="timeline-item pb-5 {{ !$loop->last ? 'mb-5' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                {!! $activity->event_icon !!}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1">{!! $activity->formatted_description !!}</h5>
                                        <div class="text-muted fs-7">
                                            <i class="fas fa-user me-1"></i>
                                            <strong>{{ $activity->user_name }}</strong>
                                            <span class="badge badge-light-{{ $activity->user_role == 'dokter' ? 'primary' : ($activity->user_role == 'perawat' ? 'success' : 'secondary') }} ms-2">
                                                {{ ucfirst($activity->user_role ?? 'staff') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted fs-7">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </div>
                                        <div class="text-muted fs-8">
                                            {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                        </div>
                                    </div>
                                </div>

                                @if($activity->no_rm || $activity->poli || $activity->dokter)
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @if($activity->no_rm)
                                    <span class="badge badge-light-info">
                                        <i class="fas fa-id-card me-1"></i>
                                        RM: {{ $activity->no_rm }}
                                    </span>
                                    @endif
                                    @if($activity->poli)
                                    <span class="badge badge-light-primary">
                                        <i class="fas fa-clinic-medical me-1"></i>
                                        {{ $activity->poli }}
                                    </span>
                                    @endif
                                    @if($activity->dokter)
                                    <span class="badge badge-light-success">
                                        <i class="fas fa-user-md me-1"></i>
                                        {{ $activity->dokter }}
                                    </span>
                                    @endif
                                </div>
                                @endif

                                @if($activity->changes && count($activity->changes) > 0)
                                <div class="bg-light-warning rounded p-3 mt-2">
                                    <div class="fw-bold text-warning mb-2">
                                        <i class="fas fa-edit me-1"></i>
                                        Perubahan Data:
                                    </div>
                                    <table class="table table-sm table-borderless mb-0">
                                        @foreach($activity->changes as $field => $change)
                                        <tr>
                                            <td class="fw-bold" style="width: 150px;">{{ ucfirst(str_replace('_', ' ', $field)) }}:</td>
                                            <td>
                                                <span class="text-danger text-decoration-line-through">{{ $change['old'] ?? '-' }}</span>
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                <span class="text-success fw-bold">{{ $change['new'] ?? '-' }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif

                                <div class="text-muted fs-8 mt-2">
                                    <i class="fas fa-network-wired me-1"></i>
                                    IP: {{ $activity->ip_address }} |
                                    <i class="fas fa-link me-1"></i>
                                    {{ $activity->method }} {{ Str::limit($activity->url, 50) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <i class="fas fa-inbox fs-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada aktivitas yang ditemukan</p>
                    </div>
                    @endforelse

                    @if($activities->hasPages())
                    <div class="mt-5">
                        {{ $activities->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Auto refresh every 30 seconds
    // setTimeout(function() {
    //     location.reload();
    // }, 30000);
});
</script>
@endsection
