@extends('layouts.index')

@section('title', 'Rujukan Keluar BPJS')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!-- Tabs -->
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_buat_baru">
                            <i class="ki-outline ki-plus-square fs-2 me-2"></i> Buat Rujukan Baru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_history">
                            <i class="ki-outline ki-time fs-2 me-2"></i> Riwayat Rujukan
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- Tab Buat Baru (Source: Kunjungan) -->
                    <div class="tab-pane fade show active" id="tab_buat_baru" role="tabpanel">
                        <div class="card card-flush">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bold fs-3">Pilih Kunjungan</span>
                                        <span class="text-muted mt-1 fw-semibold fs-7">Pilih pasien untuk dirujuk ke Luar
                                            (Pasien dengan SEP)</span>
                                    </h3>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex align-items-center position-relative my-1 me-3">
                                        <i class="ki-outline ki-calendar-8 fs-2 position-absolute ms-4"></i>
                                        <input class="form-control form-control-solid ps-12 w-250px"
                                            placeholder="Tanggal Masuk" id="filter_date_source" />
                                    </div>
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-2 position-absolute ms-4"></i>
                                        <input type="text" class="form-control form-control-solid ps-12 w-200px"
                                            placeholder="Cari Pasien/SEP" id="filter_keyword_source" />
                                    </div>
                                    <button type="button" class="btn btn-primary ms-3" onclick="reloadTableSource()">
                                        <i class="ki-outline ki-magnifier fs-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_source">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Tgl Masuk</th>
                                                <th>No SEP</th>
                                                <th>No RM</th>
                                                <th>Nama Pasien</th>
                                                <th>Poli</th>
                                                <th>Dokter</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab History (Source: VClaim API) -->
                    <div class="tab-pane fade" id="tab_history" role="tabpanel">
                        <div class="card card-flush">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <h3 class="card-label fw-bold fs-3">Riwayat Rujukan Keluar (VClaim)</h3>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex align-items-center position-relative my-1 me-3">
                                        <i class="ki-outline ki-calendar-8 fs-2 position-absolute ms-4"></i>
                                        <input class="form-control form-control-solid ps-12 w-250px"
                                            placeholder="Tanggal Rujukan" id="filter_date_history" />
                                    </div>
                                    <button type="button" class="btn btn-primary ms-3" onclick="reloadTableHistory()">
                                        <i class="ki-outline ki-magnifier fs-2"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_history">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th>No</th>
                                                <th>No Rujukan</th>
                                                <th>Tgl Rujukan</th>
                                                <th>No SEP</th>
                                                <th>No Kartu</th>
                                                <th>Nama</th>
                                                <th>RS Tujuan</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Rujukan Container -->
    <div class="modal fade" id="modal_rujukan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Buat Rujukan Keluar</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7" id="modal_rujukan_body">
                    <!-- Content loaded via AJAX -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var tableSource;
        var tableHistory;

        $(document).ready(function() {
            // Init Date Pickers
            $("#filter_date_source").flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: ["{{ date('Y-m-d', strtotime('-7 days')) }}", "{{ date('Y-m-d') }}"],
            });

            $("#filter_date_history").flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: ["{{ date('Y-m-d') }}", "{{ date('Y-m-d') }}"],
            });

            initTableSource();
            initTableHistory();

            // Enter key trigger
            $('#filter_keyword_source').on('keypress', function(e) {
                if (e.which === 13) reloadTableSource();
            });
        });

        function initTableSource() {
            tableSource = $('#table_source').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('vclaim.rujukan.index') }}",
                    data: function(d) {
                        d.source = true;
                        let dates = $("#filter_date_source").val().split(' to ');
                        if (dates.length > 0) d.start_date = dates[0];
                        if (dates.length > 1) d.end_date = dates[1];
                        else d.end_date = dates[0];

                        d.keyword = $('#filter_keyword_source').val();
                    }
                },
                columns: [{
                        data: 'tglmasuk',
                        name: 'rawat.tglmasuk'
                    },
                    {
                        data: 'no_sep',
                        name: 'rawat.no_sep'
                    },
                    {
                        data: 'no_rm',
                        name: 'rawat.no_rm'
                    },
                    {
                        data: 'nama_pasien',
                        name: 'pasien.nama_pasien'
                    },
                    {
                        data: 'nama_poli',
                        name: 'poli.poli'
                    },
                    {
                        data: 'nama_dokter',
                        name: 'dokter.nama_dokter'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        }

        function initTableHistory() {
            tableHistory = $('#table_history').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vclaim.rujukan.index') }}",
                    data: function(d) {
                        let dates = $("#filter_date_history").val().split(' to ');
                        if (dates.length > 0) d.start_date = dates[0];
                        if (dates.length > 1) d.end_date = dates[1];
                        else d.end_date = dates[0];
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { // noRujukan, tglRujukan, tglRencanaKunjungan, noSep, noKartu, nama, poliTujuan, namaPoliTujuan, ppkDirujuk, namaPpkDirujuk
                        data: 'noRujukan',
                        name: 'noRujukan'
                    },
                    {
                        data: 'tglRujukan',
                        name: 'tglRujukan'
                    },
                    {
                        data: 'noSep',
                        name: 'noSep'
                    },
                    {
                        data: 'noKartu',
                        name: 'noKartu'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'namaPpkDirujuk',
                        name: 'namaPpkDirujuk'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    },
                ]
            });
        }

        function reloadTableSource() {
            tableSource.draw();
        }

        function reloadTableHistory() {
            tableHistory.draw();
        }

        function openModalRujukan(no_sep, no_kartu) {
            $('#modal_rujukan').modal('show');
            $('#modal_rujukan_body').html(
                '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
            );

            $.ajax({
                url: "{{ route('vclaim.rujukan.modal') }}",
                type: "GET",
                data: {
                    no_sep: no_sep,
                    no_kartu: no_kartu
                },
                success: function(response) {
                    $('#modal_rujukan_body').html(response);
                    // Re-init generic plugins if needed
                    KTApp.init();
                },
                error: function() {
                    $('#modal_rujukan_body').html(
                        '<div class="alert alert-danger">Gagal memuat form</div>');
                }
            });
        }
    </script>
@endsection
