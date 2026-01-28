@extends('layouts.index')

@section('title', 'Rencana Kontrol BPJS')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!-- Tabs -->
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_buat_baru">
                            <i class="ki-outline ki-plus-square fs-2 me-2"></i> Buat Rencana Kontrol
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_history">
                            <i class="ki-outline ki-time fs-2 me-2"></i> Riwayat & List Surat
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
                                        <span class="text-muted mt-1 fw-semibold fs-7">Pilih pasien untuk dibuatkan Rencana
                                            Kontrol (Pasien dengan SEP)</span>
                                    </h3>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex align-items-center position-relative my-1 me-3">
                                        <i class="ki-outline ki-calendar-8 fs-2 position-absolute ms-4"></i>
                                        <input class="form-control form-control-solid ps-12 w-250px"
                                            placeholder="Tanggal Masuk" id="filter_date_source" />
                                    </div>
                                    <div class="w-200px">
                                        <select class="form-select form-select-solid" data-control="select2"
                                            data-placeholder="Filter Poli" id="filter_poli">
                                            <option value="">Semua Poli</option>
                                            @foreach ($polis as $poli)
                                                <option value="{{ $poli->id }}">{{ $poli->poli }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary ms-3" onclick="reloadTableSource()">
                                        <i class="ki-outline ki-magnifier fs-2"></i> Cari
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
                                    <h3 class="card-label fw-bold fs-3">List Surat Rencana Kontrol (VClaim)</h3>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex align-items-center position-relative my-1 me-3">
                                        <i class="ki-outline ki-calendar-8 fs-2 position-absolute ms-4"></i>
                                        <input class="form-control form-control-solid ps-12 w-200px" placeholder="Tanggal"
                                            id="filter_date_history" value="{{ date('Y-m-d') }}" />
                                    </div>
                                    <div class="w-150px">
                                        <select class="form-select form-select-solid" id="filter_jenis_history">
                                            <option value="2">Rencana Kontrol</option>
                                            <option value="1">SPRI</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary ms-3" onclick="reloadTableHistory()">
                                        <i class="ki-outline ki-magnifier fs-2"></i> Cari
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_history">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th>No</th>
                                                <th>No Surat Kontrol</th>
                                                <th>No SEP Asal</th>
                                                <th>Tgl Rencana Kontrol</th>
                                                <th>Poli Tujuan</th>
                                                <th>Dokter</th>
                                                <th>Terbit SEP</th>
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

    <!-- Modal Rencana Kontrol Container -->
    <div class="modal fade" id="modal_rencana_kontrol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-800px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Buat Rencana Kontrol</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7" id="modal_rencana_kontrol_body">
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
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}",
            });

            initTableSource();
            initTableHistory();
        });

        function initTableSource() {
            tableSource = $('#table_source').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vclaim.rencana-kontrol.index') }}",
                    data: function(d) {
                        d.source = true;
                        // Parse Flatpickr range
                        let dates = $("#filter_date_source").val().split(' to ');
                        if (dates.length > 0) d.start_date = dates[0];
                        if (dates.length > 1) d.end_date = dates[1];
                        else d.end_date = dates[0];

                        d.idpoli = $('#filter_poli').val();
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
                    url: "{{ route('vclaim.rencana-kontrol.index') }}",
                    data: function(d) {
                        d.date = $("#filter_date_history").val();
                        d.filter = $("#filter_jenis_history").val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'noSuratKontrol',
                        name: 'noSuratKontrol'
                    },
                    {
                        data: 'noSepAsalKontrol',
                        name: 'noSepAsalKontrol'
                    },
                    {
                        data: 'tglRencanaKontrol',
                        name: 'tglRencanaKontrol'
                    },
                    {
                        data: 'namaPoliTujuan',
                        name: 'namaPoliTujuan'
                    },
                    {
                        data: 'namaDokter',
                        name: 'namaDokter'
                    },
                    {
                        data: 'terbitSEP',
                        name: 'terbitSEP'
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

        function openRencanaKontrol(no_sep, no_kartu) {
            $('#modal_rencana_kontrol').modal('show');
            $('#modal_rencana_kontrol_body').html(
                '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

            $.ajax({
                url: "{{ route('vclaim.rencana-kontrol.modal') }}",
                type: "GET",
                data: {
                    no_sep: no_sep,
                    no_kartu: no_kartu
                },
                success: function(response) {
                    $('#modal_rencana_kontrol_body').html(response);
                    // Re-init select2
                    KTApp.init();
                    $('#modal_rencana_kontrol_body select[data-control="select2"]').select2({
                        dropdownParent: $('#modal_rencana_kontrol')
                    });
                },
                error: function() {
                    $('#modal_rencana_kontrol_body').html(
                        '<div class="alert alert-danger">Gagal memuat form</div>');
                }
            });
        }
    </script>
@endsection
