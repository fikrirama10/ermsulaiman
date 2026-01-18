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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">List Pasien</h1>
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
                        <li class="breadcrumb-item text-muted">Pasien</li>
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
            <!--begin::FAQ card-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="card-title">Data Pasien</h5>
                    </div>
                </div>
                <!--begin::Body-->
                <div class="card-body pt-2">
                    <!-- Filter Section -->
                    <div class="d-flex gap-3 mb-4 p-3 bg-light rounded align-items-end">
                        <div style="min-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Tanggal Masuk</label>
                            <input type="date" id="filter_tglmasuk" class="form-control form-control-sm" value="">
                        </div>
                        <div style="min-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Status Pemeriksaan</label>
                            <select id="filter_status_periksa" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="1">Antrian</option>
                                <option value="3">Pemeriksaan</option>
                                <option value="4">Selesai</option>
                            </select>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" onclick="applyFilters()">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                    <table id="tbl-pasien" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                        <thead class="border bg-light">
                            <tr class="fw-bold fs-7 text-gray-800">
                                <th width="8%">No RM</th>
                                <th width="15%">Nama Pasien</th>
                                <th width="10%">Tgl.Masuk</th>
                                <th width="10%">Jenis Rawat</th>
                                <th width="12%">Poli</th>
                                <th width="10%">Bayar</th>
                                <th width="8%">Status</th>
                                <th width="17%">Pemeriksaan</th>
                                <th width="10%">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="fs-7">

                        </tbody>
                    </table>
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
<script>
    var table;
    $(function(){
        table = $("#tbl-pasien").DataTable({
            "language": {
                "lengthMenu": "Show _MENU_",
                "processing": "Memuat data...",
                "search": "Cari:",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            },
            "dom":
                "<'row mb-3'" +
                "<'col-sm-12 col-md-6 d-flex align-items-center'l>" +
                "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>" +
                ">" +
                "<'table-responsive'tr>" +
                "<'row mt-3'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            search: {
                return: true
            },
            ajax: {
                url: '{{ url()->current() }}',
                data: function (d) {
                    d.filter_tglmasuk = $('#filter_tglmasuk').val();
                    d.filter_status_periksa = $('#filter_status_periksa').val();
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables Error:', error, thrown);
                }
            },
            columns: [
                { data: 'no_rm', name: 'pasien.no_rm', className: 'text-center' },
                { data: 'nama_pasien', name: 'pasien.nama_pasien' },
                {
                    data: 'tglmasuk',
                    name: 'rawat.tglmasuk',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data) {
                            var date = new Date(data);
                            var day = ("0" + date.getDate()).slice(-2);
                            var month = ("0" + (date.getMonth() + 1)).slice(-2);
                            var year = date.getFullYear();
                            return day + '/' + month + '/' + year;
                        }
                        return data;
                    }
                },
                { data: 'jenis_rawat', name: 'jenis_rawat', className: 'text-center' },
                { data: 'poli', name: 'poli.poli' },
                { data: 'bayar', name: 'rawat_bayar.bayar', className: 'text-center' },
                {
                    data: 'status',
                    name: 'rawat_status.status',
                    className: 'text-center',
                    render: function(data, type, row){
                        if(data == 'Pulang'){
                            return '<span class="badge badge-success">Selesai</span>';
                        }else{
                            return '<span class="badge badge-secondary">'+data+'</span>';
                        }
                    }
                },
                { data: 'status_pemeriksaan', name: 'status_pemeriksaan', className: 'text-center' },
                { data: 'opsi', name: 'opsi', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[2, 'desc']]
        });

        // Filter otomatis saat memilih tanggal
        $('#filter_tglmasuk').on('change', function() {
            table.ajax.reload();
        });

        // Filter otomatis saat memilih status
        $('#filter_status_periksa').on('change', function() {
            table.ajax.reload();
        });
    });

    function applyFilters() {
        table.ajax.reload();
    }

    function resetFilters() {
        $('#filter_tglmasuk').val('{{ date('Y-m-d') }}');
        $('#filter_status_periksa').val('');
        table.ajax.reload();
    }
</script>
@endsection
