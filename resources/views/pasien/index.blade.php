@extends('layouts.index')

@section('css')
    <style>
        .spoiler {
            transition: all 0.3s ease;
            filter: blur(4px);
            cursor: pointer;
            user-select: none;
        }

        .spoiler:hover,
        .spoiler.revealed {
            filter: blur(0);
        }

        .hover-elevate-up {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-elevate-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Pendaftaran Pasien
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ url('/dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Pasien</li>
                        </ul>
                    </div>
                     <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('pasien.tambah-pasien') }}" class="btn btn-sm fw-bold btn-primary">
                            <i class="ki-outline ki-plus fs-2"></i> Pasien Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- Statistics Row -->
                <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
                    <!-- Total Pasien -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-md-100 hover-elevate-up">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="total-pasien">0</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Pasien</span>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <div class="d-flex flex-center me-5 pt-2">
                                     <span class="badge badge-light-success fs-base">
                                        <i class="ki-outline ki-profile-user fs-5 text-success ms-n1"></i> Registered
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Pasien Baru -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-md-100 hover-elevate-up">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="pasien-baru">0</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Pasien Baru (Bulan Ini)</span>
                                </div>
                            </div>
                             <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <div class="d-flex flex-center me-5 pt-2">
                                     <span class="badge badge-light-primary fs-base">
                                        <i class="ki-outline ki-user-tick fs-5 text-primary ms-n1"></i> New
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Kunjungan Hari Ini -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-md-100 hover-elevate-up">
                             <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="kunjungan-hari-ini">0</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Kunjungan Hari Ini</span>
                                </div>
                            </div>
                             <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <div class="d-flex flex-center me-5 pt-2">
                                     <span class="badge badge-light-warning fs-base">
                                        <i class="ki-outline ki-calendar fs-5 text-warning ms-n1"></i> Today
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- Total Kunjungan -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-md-100 hover-elevate-up">
                             <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="total-kunjungan">0</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Kunjungan (Bulan Ini)</span>
                                </div>
                            </div>
                             <div class="card-body pt-2 pb-4 d-flex align-items-center">
                                <div class="d-flex flex-center me-5 pt-2">
                                     <span class="badge badge-light-info fs-base">
                                        <i class="ki-outline ki-graph-up fs-5 text-info ms-n1"></i> Traffic
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">
                     <!-- Main Content: Filter & Table -->
                    <div class="col-xl-8">
                        <div class="card card-flush h-xl-100">
                            <!-- Card Header -->
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Data Master Pasien</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Cari dan kelola data pasien</span>
                                </h3>
                                <div class="card-toolbar">
                                     <a class="btn btn-sm btn-light-primary" data-bs-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
                                        <i class="ki-outline ki-filter fs-2"></i> Filter Pencarian
                                    </a>
                                </div>
                            </div>

                             <!-- Filter Section (Collapsible) -->
                            <div class="collapse show" id="filterCollapse">
                                <div class="card-body border-top p-9">
                                    <div class="row g-5">
                                        <div class="col-md-4">
                                            <label class="form-label fs-6 fw-semibold">No Rekam Medis:</label>
                                            <input type="text" class="form-control form-control-solid" id="filter_no_rm" placeholder="Cth: 123456" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-6 fw-semibold">NIK:</label>
                                            <input type="text" class="form-control form-control-solid" id="filter_nik" placeholder="Nomor Induk Kependudukan" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fs-6 fw-semibold">Nama Pasien:</label>
                                            <input type="text" class="form-control form-control-solid" id="filter_nama" placeholder="Nama Lengkap" />
                                        </div>
                                         <div class="col-md-4">
                                            <label class="form-label fs-6 fw-semibold">No BPJS:</label>
                                            <input type="text" class="form-control form-control-solid" id="filter_bpjs" placeholder="Nomor Kartu BPJS" />
                                        </div>
                                         <div class="col-md-4">
                                             <label class="form-label fs-6 fw-semibold">Usia:</label>
                                             <select class="form-select form-select-solid" id="filter_usia">
                                                <option value="">Semua</option>
                                                <option value="balita">Balita</option>
                                                <option value="anak">Anak</option>
                                                <option value="remaja">Remaja</option>
                                                <option value="dewasa">Dewasa</option>
                                                <option value="lansia">Lansia</option>
                                             </select>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end justify-content-end">
                                             <button type="button" class="btn btn-primary w-100" id="btn-apply-filter">
                                                <i class="ki-outline ki-magnifier fs-2"></i> Cari
                                             </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Body -->
                            <div class="card-body pt-2">
                                <div class="table-responsive">
                                    <table id="kt_table_pasien" class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-100px">Pasien</th>
                                                <th class="min-w-100px">Identitas (NIK/BPJS)</th>
                                                <th class="min-w-100px">Kontak</th>
                                                <th class="min-w-50px">Usia</th>
                                                <th class="text-end min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <!-- DataTables -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Recent Visits -->
                     <div class="col-xl-4">
                        <div class="card card-flush h-xl-100">
                             <div class="card-header pt-7 mb-3">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Kunjungan Terakhir</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Pasien yang baru saja mendaftar</span>
                                </h3>
                            </div>
                            <div class="card-body pt-0">
                                <!-- Timeline Content -->
                                <div id="recent-visits-list" class="timeline-label">
                                     <!-- Loaded by AJAX -->
                                     <div class="d-flex justify-content-center py-10">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(function() {
        loadStatistics();
        loadRecentVisits();

        var table = $('#kt_table_pasien').DataTable({
            processing: true,
            serverSide: true,
            order: [], // Disable default sorting
            ajax: {
                url: "{{ route('pasien.index') }}",
                data: function(d) {
                    d.no_rm = $('#filter_no_rm').val();
                    d.nik = $('#filter_nik').val();
                    d.nama = $('#filter_nama').val();
                    d.no_bpjs = $('#filter_bpjs').val();
                    d.usia = $('#filter_usia').val();
                }
            },
            columns: [
                {
                    data: 'nama_pasien',
                    name: 'nama_pasien',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex flex-column">
                                <a href="#" class="text-gray-800 text-hover-primary mb-1 fw-bold">${data}</a>
                                <span>${row.no_rm}</span>
                            </div>
                        `;
                    }
                },
                {
                    data: 'nik',
                    name: 'nik',
                    render: function(data, type, row) {
                         let bpjs = row.no_bpjs ? `<span class="badge badge-light-primary text-muted spoiler mt-1">${row.no_bpjs}</span>` : '';
                         return `
                            <div class="d-flex flex-column">
                                <span class="spoiler text-gray-600">${data}</span>
                                ${bpjs}
                            </div>
                         `;
                    }
                },
                 {
                    data: 'nohp',
                    name: 'nohp',
                    render: function(data) {
                        return `<span class="spoiler">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'usia_tahun',
                    name: 'usia_tahun',
                     render: function(data) {
                        return `<span class="badge badge-light fw-bold">${data} Thn</span>`;
                    }
                },
                {
                    data: 'opsi',
                    name: 'opsi',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],
            language: {
                zeroRecords: "Tidak ada data pasien ditemukan",
                processing: "Memuat data...",
            }
        });

        $('#btn-apply-filter').on('click', function() {
            table.draw();
        });

        // Trigger filter on Enter key
        $('#filter_no_rm, #filter_nik, #filter_nama, #filter_bpjs').on('keyup', function(e) {
            if (e.key === 'Enter') {
                table.draw();
            }
        });

        // Reset Filter (Optional, can add button later)
        
        // Spoiler Toggle
        $(document).on('click', '.spoiler', function() {
            $(this).toggleClass('revealed');
        });
    });

    function loadStatistics() {
        $.get('{{ route('pasien.statistics') }}', function(response) {
            $('#total-pasien').text(response.total_pasien);
            $('#pasien-baru').text(response.pasien_baru);
            $('#kunjungan-hari-ini').text(response.kunjungan_hari_ini);
            $('#total-kunjungan').text(response.total_kunjungan);
        });
    }

    function loadRecentVisits() {
        $.get('{{ route('pasien.recent-visits') }}', function(response) {
            let html = '';
            if (response.data.length === 0) {
                 html = `<div class="alert alert-light-info d-flex align-items-center p-5 mb-0">
                            <i class="ki-outline ki-shield-cross fs-2hx text-info me-4"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">Belum ada data</span>
                                <span>Tidak ada kunjungan tercatat hari ini.</span>
                            </div>
                        </div>`;
            } else {
                 response.data.forEach(function(item) {
                    html += `
                        <div class="timeline-item">
                            <div class="timeline-label fw-bold text-gray-800 fs-6">${item.jam}</div>
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-${item.status_class || 'primary'} fs-1"></i>
                            </div>
                            <div class="timeline-content d-flex flex-column ps-3">
                                <span class="fw-bold text-gray-800 fs-6">${item.nama}</span>
                                <span class="text-muted fs-7">RM: ${item.no_rm} - ${item.poli}</span>
                            </div>
                        </div>
                    `;
                });
                // Wrap in timeline div
                 html = `<div class="timeline-label">` + html + `</div>`;
            }
            $('#recent-visits-list').html(html);
        });
    }
</script>
@endsection
