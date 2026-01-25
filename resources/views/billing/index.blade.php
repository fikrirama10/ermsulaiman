@extends('layouts.index')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card card-flush shadow-sm border-0">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" data-kt-billing-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Pasien/RM..." />
                            </div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-150px">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-billing-table-filter="status" id="status_filter">
                                    <option value="all">Semua Status</option>
                                    <option value="1">Antrian</option>
                                    <option value="2">Dirawat</option>
                                    <option value="3">Pemeriksaan</option>
                                    <option value="4">Pulang</option>
                                    <option value="5">Batal</option>
                                </select>
                            </div>
                            <div class="input-group w-250px">
                                <input class="form-control form-control-solid rounded rounded-end-0" placeholder="Pilih Rentang Tanggal" id="kt_billing_datepicker" />
                                <button class="btn btn-icon btn-light" id="kt_billing_datepicker_clear">
                                    <i class="ki-outline ki-cross fs-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_billing_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs- GS-0">
                                    <th class="min-w-100px">Tgl. Kunjungan</th>
                                    <th class="min-w-100px">ID Kunjungan</th>
                                    <th class="min-w-150px">Pasien</th>
                                    <th class="min-w-80px">Jenis</th>
                                    <th class="min-w-200px">Unit / Layanan</th>
                                    <th class="min-w-100px text-center">Status</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        var table = $('#kt_billing_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('billing.data') }}",
                data: function(d) {
                    d.status = $('#status_filter').val() !== 'all' ? $('#status_filter').val() : '';
                    d.start_date = $('#kt_billing_datepicker').data('start');
                    d.end_date = $('#kt_billing_datepicker').data('end');
                }
            },
            columns: [{
                    data: 'tgl_kunjungan',
                    name: 'tgl_kunjungan'
                },
                {
                    data: 'idkunjungan',
                    name: 'idkunjungan'
                },
                {
                    data: 'nama_pasien',
                    name: 'nama_pasien'
                },
                {
                    data: 'jenis_rawat',
                    name: 'jenis_rawat',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'list_unit',
                    name: 'list_unit',
                    orderable: false
                },
                {
                    data: 'status_badge',
                    name: 'status',
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search...",
                processing: '<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
            },
            dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        // Custom search
        $('[data-kt-billing-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Date Range Picker
        $("#kt_billing_datepicker").flatpickr({
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            mode: "range",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    $(instance.element).data('start', instance.formatDate(selectedDates[0], "Y-m-d"));
                    $(instance.element).data('end', instance.formatDate(selectedDates[1], "Y-m-d"));
                    table.draw();
                }
            }
        });

        // Clear Datepicker
        $('#kt_billing_datepicker_clear').on('click', function() {
            var fp = document.querySelector("#kt_billing_datepicker")._flatpickr;
            fp.clear();
            $("#kt_billing_datepicker").data('start', '');
            $("#kt_billing_datepicker").data('end', '');
            table.draw();
        });

        // Status filter
        $('#status_filter').on('change', function() {
            table.draw();
        });
    });
</script>
@endsection
@endsection