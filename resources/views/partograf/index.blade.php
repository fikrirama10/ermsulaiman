@extends('layouts.index')

@section('css')
    <style>
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.475rem;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-ongoing {
            background-color: #E8FFF3;
            color: #50CD89;
        }

        .status-completed {
            background-color: #F1FAFF;
            color: #009EF7;
        }

        .status-referred {
            background-color: #FFF5F8;
            color: #F1416C;
        }

        .alert-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .alert-warning {
            background-color: #FFC700;
        }

        .alert-danger {
            background-color: #F1416C;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        <i class="fas fa-heartbeat text-primary me-2"></i> Partograf
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Monitoring Persalinan</li>
                    </ul>
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('partograf.create') }}" class="btn btn-sm fw-bold btn-primary">
                        <i class="fas fa-plus"></i> Admisi Persalinan Baru
                    </a>
                </div>
                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" id="search" class="form-control form-control-solid w-250px ps-13"
                                    placeholder="Cari pasien...">
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end gap-2" data-kt-subscription-table-toolbar="base">
                                <!--begin::Filter-->
                                <select class="form-select form-select-solid w-150px" id="filterStatus">
                                    <option value="">Semua Status</option>
                                    <option value="ongoing">Berlangsung</option>
                                    <option value="completed">Selesai</option>
                                    <option value="referred">Rujukan</option>
                                </select>
                                <!--end::Filter-->
                                <!--begin::Date Filter-->
                                <input type="date" class="form-control form-control-solid w-150px" id="filterDate"
                                    placeholder="Filter Tanggal">
                                <!--end::Date Filter-->
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="partografTable">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-125px">No. RM</th>
                                    <th class="min-w-150px">Nama Pasien</th>
                                    <th class="min-w-100px">G/P/A</th>
                                    <th class="min-w-125px">Tgl Masuk</th>
                                    <th class="min-w-100px">Durasi</th>
                                    <th class="min-w-100px">Bidan</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-50px">Alert</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($laborRecords as $index => $labor)
                                    <tr>
                                        <td>{{ $laborRecords->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ $labor->patient_no_rm }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('partograf.show', $labor->id) }}"
                                                    class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $labor->visit->pasien->nama ?? '-' }}
                                                </a>
                                                <span class="text-muted fs-7">
                                                    {{ $labor->gestational_age }} minggu
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info">
                                                G{{ $labor->gravida }}P{{ $labor->para }}A{{ $labor->abortus }}
                                            </span>
                                        </td>
                                        <td>{{ $labor->admission_date->format('d M Y H:i') }}</td>
                                        <td>
                                            <span class="text-gray-800">{{ $labor->labor_duration }} jam</span>
                                        </td>
                                        <td>{{ $labor->midwife->name ?? '-' }}</td>
                                        <td>
                                            @if ($labor->status == 'ongoing')
                                                <span class="status-badge status-ongoing">Berlangsung</span>
                                            @elseif($labor->status == 'completed')
                                                <span class="status-badge status-completed">Selesai</span>
                                            @else
                                                <span class="status-badge status-referred">Rujukan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($labor->is_action)
                                                <span class="alert-indicator alert-danger" data-bs-toggle="tooltip"
                                                    title="Garis bertindak!"></span>
                                            @elseif($labor->is_alert)
                                                <span class="alert-indicator alert-warning" data-bs-toggle="tooltip"
                                                    title="Garis waspada"></span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="#"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Aksi
                                                <i class="ki-outline ki-down fs-5 ms-1"></i>
                                            </a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('partograf.show', $labor->id) }}"
                                                        class="menu-link px-3">
                                                        <i class="fas fa-eye me-2"></i> Lihat Monitoring
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('partograf.edit', $labor->id) }}"
                                                        class="menu-link px-3">
                                                        <i class="fas fa-edit me-2"></i> Edit Data
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('partograf.pdf', $labor->id) }}"
                                                        class="menu-link px-3" target="_blank">
                                                        <i class="fas fa-file-pdf me-2"></i> Export PDF
                                                    </a>
                                                </div>
                                                @if ($labor->status == 'ongoing')
                                                    <div class="separator my-2"></div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('partograf.destroy', $labor->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="menu-link px-3 w-100 text-start text-danger">
                                                                <i class="fas fa-trash me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox fs-3x text-muted mb-4"></i>
                                                <span class="text-muted fs-5">Belum ada data partograf</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->

                        <!--begin::Pagination-->
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div class="text-muted">
                                Menampilkan {{ $laborRecords->firstItem() ?? 0 }} - {{ $laborRecords->lastItem() ?? 0 }}
                                dari {{ $laborRecords->total() }} data
                            </div>
                            {{ $laborRecords->links() }}
                        </div>
                        <!--end::Pagination-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#search').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#partografTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Filter by status
            $('#filterStatus').on('change', function() {
                const status = $(this).val().toLowerCase();
                if (status === '') {
                    $('#partografTable tbody tr').show();
                } else {
                    $('#partografTable tbody tr').each(function() {
                        const rowStatus = $(this).find('.status-badge').text().toLowerCase();
                        if (status === 'ongoing' && rowStatus.includes('berlangsung')) {
                            $(this).show();
                        } else if (status === 'completed' && rowStatus.includes('selesai')) {
                            $(this).show();
                        } else if (status === 'rujukan' && rowStatus.includes('rujukan')) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });

            // Filter by date
            $('#filterDate').on('change', function() {
                const selectedDate = $(this).val();
                if (selectedDate) {
                    window.location.href = "{{ route('partograf.index') }}?date=" + selectedDate;
                }
            });

            // Delete confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data partograf ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#F1416C',
                    cancelButtonColor: '#7E8299',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endsection
