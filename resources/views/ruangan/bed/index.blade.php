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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">BED</h1>
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
                        <li class="breadcrumb-item text-muted">Ruangan</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">BED</li>
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
            <div class="row g-4">
                <div class="col-md-12">
                    <!-- Info Ruangan Card - Compact -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <!-- Icon & Name -->
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="bi bi-hospital fs-2x text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="mb-1 fw-bold">{{ $ruangan->nama_ruangan }}</h5>
                                            <span class="badge badge-light-info">{{ $ruangan->kelas->kelas }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Detail -->
                                <div class="col-md-5">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-gender-ambiguous fs-5 text-info me-2"></i>
                                                <span class="fs-7 text-gray-700">{{ $ruangan->genderRuangan->gender }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-grid-3x3-gap fs-5 text-success me-2"></i>
                                                <span class="fs-7 text-gray-700">{{ $ruangan->jenisRuangan->ruangan_jenis }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-border-all fs-5 text-primary me-2"></i>
                                                <span class="fs-7 text-gray-700">{{ $ruangan->kapasitas }} Bed</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            @if($ruangan->status)
                                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Aktif</span>
                                            @else
                                                <span class="badge badge-secondary"><i class="bi bi-x-circle"></i> Nonaktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Statistik Bed -->
                                <div class="col-md-4">
                                    @php
                                        $total_bed = $ruangan->bed->count();
                                        $bed_aktif = $ruangan->bed->where('status', 1)->count();
                                        $bed_nonaktif = $ruangan->bed->where('status', 0)->count();
                                        $bed_kosong = $ruangan->bed->where('terisi', 0)->where('status', 1)->count();
                                        $bed_terisi = $ruangan->bed->where('terisi', 1)->where('status', 1)->count();
                                        $percentage_available = $bed_aktif > 0 ? round(($bed_kosong / $bed_aktif) * 100) : 0;
                                    @endphp
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="bg-light-success rounded p-2 text-center">
                                                <div class="fs-3 fw-bold text-success">{{ $bed_kosong }}</div>
                                                <div class="fs-8 text-gray-600">Kosong</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-light-danger rounded p-2 text-center">
                                                <div class="fs-3 fw-bold text-danger">{{ $bed_terisi }}</div>
                                                <div class="fs-8 text-gray-600">Terisi</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-light-secondary rounded p-2 text-center">
                                                <div class="fs-3 fw-bold text-secondary">{{ $bed_nonaktif }}</div>
                                                <div class="fs-8 text-gray-600">Nonaktif</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress h-6px bg-light">
                                            <div class="progress-bar bg-success" style="width: {{ $percentage_available }}%"></div>
                                        </div>
                                        <div class="text-center fs-8 text-gray-600 mt-1">{{ $percentage_available }}% Tersedia</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header border-0 pt-5">
                            <div class="card-title">
                                <h5 class="fw-bold m-0"><i class="bi bi-list-ul"></i> Daftar Bed</h5>
                            </div>
                            <div class="card-toolbar d-flex gap-2">
                                <a href="{{ route('index.ruangan') }}" class="btn btn-sm btn-light-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <div class="vr"></div>
                                <button type="button" class="btn btn-sm btn-success" onclick="quickAddBed(1)">
                                    <i class="bi bi-plus"></i> +1 Bed
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="quickAddBed(5)">
                                    <i class="bi bi-plus-lg"></i> +5 Bed
                                </button>
                                @canany(['dokter', 'perawat'])
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah-bed">
                                        <i class="bi bi-plus-circle"></i> Tambah Custom
                                    </button>
                                @endcanany
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <!-- Filter Section - Compact -->
                            <div class="d-flex gap-3 mb-4 p-3 bg-light rounded">
                                <div class="flex-grow-1">
                                    <label class="form-label fw-bold fs-7 mb-1">Status</label>
                                    <select id="filter_status" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="form-label fw-bold fs-7 mb-1">Ketersediaan</label>
                                    <select id="filter_terisi" class="form-select form-select-sm">
                                        <option value="">Semua</option>
                                        <option value="0">Kosong</option>
                                        <option value="1">Terisi</option>
                                    </select>
                                </div>
                                <div class="vr my-2"></div>
                                <div>
                                    <label class="form-label fw-bold fs-7 mb-1">Aksi Massal</label>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-success" onclick="bulkActionBed('activate')" title="Aktifkan bed terpilih">
                                            <i class="bi bi-check-circle"></i> Aktifkan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="bulkActionBed('deactivate')" title="Nonaktifkan bed terpilih">
                                            <i class="bi bi-x-circle"></i> Nonaktifkan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tbl-bed" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                                    <thead class="border bg-light">
                                        <tr class="fw-bold fs-7 text-gray-800">
                                            <th width="3%">
                                                <input type="checkbox" class="form-check-input" id="check-all-bed">
                                            </th>
                                            <th width="6%">No</th>
                                            <th width="22%">Kode BED</th>
                                            <th width="13%">Status</th>
                                            <th width="13%">Ketersediaan</th>
                                            <th width="13%">Bayi</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
            <!--end::FAQ card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>

<!-- Modal Tambah BED -->
<div class="modal fade" id="tambah-bed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"><i class="bi bi-plus-circle"></i> Tambah BED Baru</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="frm-data" action="{{ route('store.ruangan-bed') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold fs-7">Ruangan</label>
                    <input type="hidden" name="idruangan" value="{{ $ruangan->id }}">
                    <input type="hidden" name="idjenis" value="{{ $ruangan->idjenis }}">
                    <input type="text" class="form-control form-control-sm form-control-solid" value="{{ $ruangan->nama_ruangan }}" readonly>
                    <small class="text-muted">Kode BED akan digenerate otomatis</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold fs-7">Jumlah Bed yang Ditambahkan <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_bed" id="jumlah_bed" class="form-control form-control-sm" min="1" max="50" value="1" required>
                    <small class="text-muted">Masukkan jumlah bed (1-50). Semua bed akan memiliki pengaturan yang sama.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold fs-7">Untuk Bayi?</label>
                    <div class="d-flex gap-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" name="bayi" type="radio" value="1" id="bayi_ya" required/>
                            <label class="form-check-label fs-7" for="bayi_ya">
                                Ya
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" name="bayi" type="radio" value="0" id="bayi_tidak" checked required/>
                            <label class="form-check-label fs-7" for="bayi_tidak">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold fs-7">Status</label>
                    <div class="d-flex gap-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" name="status" type="radio" value="1" id="status_aktif" checked required/>
                            <label class="form-check-label fs-7" for="status_aktif">
                                Aktif
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" name="status" type="radio" value="0" id="status_nonaktif" required/>
                            <label class="form-check-label fs-7" for="status_nonaktif">
                                Nonaktif
                            </label>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
            </form>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-bed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit BED</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="frm-edit" action="{{ route('update.ruangan-bed') }}" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Nama Ruangan</label>

                        <input type="hidden" id="id_bed" name="id_bed" value="{{ $ruangan->id }}">
                        <input type="text" id="nama_ruangan" class="form-control form-control-solid" value="" readonly>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label">Jenis Ruangan</label>

                        <input type="text" id="jenis_ruangan" class="form-control form-control-solid" value="" readonly>
                    </div>
                </div>
                <div class="row mt-5">
                    <label class="form-label">Bayi</label>
                    <div class="d-flex">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" id="bayi-1" name="bayi" type="radio" value="1" id="flexRadioDefault" required/>
                            <label class="form-check-label" for="flexRadioDefault">
                                Ya
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" id="bayi-0" name="bayi" type="radio" value="0" id="flexRadioDefault" required/>
                            <label class="form-check-label" for="flexRadioDefault">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <label class="form-label">Status</label>
                    <div class="d-flex">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" id="status-1" name="status" type="radio" value="1" id="flexRadioDefault" required/>
                            <label class="form-check-label" for="flexRadioDefault">
                                Aktif
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" id="status-0" name="status" type="radio" value="0" id="flexRadioDefault" required/>
                            <label class="form-check-label" for="flexRadioDefault">
                                Tidak Aktif
                            </label>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
                <button type="submit" class="btn btn-success">Edit</button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript"
    src="{{ asset('assets/js/custom/blokUi.js') }}">
</script>
<script>
    $(function(){

        var table = $("#tbl-bed").DataTable({
            "language": {
                "lengthMenu": "Show _MENU_",
            },
            "dom":
                "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">",
            processing: true,
            serverSide: true,
            search: {
                return: true
            },
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'kodebed', name: 'kodebed' },
                { data: 'status', name: 'status' },
                { data: 'terisi', name: 'terisi' },
                { data: 'bayi_label', name: 'bayi_label', orderable: false, searcheable: false },
                { data: 'opsi', name: 'opsi', orderable: false, searcheable: false },
            ]
        });

        // Filter status
        $('#filter_status, #filter_terisi').on('change', function() {
            table.ajax.reload();
        });

        // Kirim filter ke server
        table.on('preXhr.dt', function(e, settings, data) {
            data.filter_status = $('#filter_status').val();
            data.filter_terisi = $('#filter_terisi').val();
        });

        // Check all checkbox
        $('#check-all-bed').on('click', function() {
            $('.bed-checkbox').prop('checked', this.checked);
        });

        // Uncheck "check all" jika ada yang dilepas
        $(document).on('change', '.bed-checkbox', function() {
            if (!this.checked) {
                $('#check-all-bed').prop('checked', false);
            }
            if ($('.bed-checkbox:checked').length === $('.bed-checkbox').length) {
                $('#check-all-bed').prop('checked', true);
            }
        });

        $("#frm-data").on( "submit", function(event) {
            event.preventDefault();
            var blockUI = new KTBlockUI(document.querySelector("#kt_app_body"));
            Swal.fire({
                title: 'Simpan Data',
                text: "Apakah Anda yakin akan menyimpan data ini ?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI({
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff',
                            fontSize: '16px'
                        },
                        message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Tunggu . . .",
                        baseZ: 9000,
                    });
                    this.submit();
                }
            });
        });

        $("#frm-edit").on( "submit", function(event) {
            event.preventDefault();
            var blockUI = new KTBlockUI(document.querySelector("#kt_app_body"));
            Swal.fire({
                title: 'Simpan Data',
                text: "Apakah Anda yakin akan menyimpan data ini ?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI({
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff',
                            fontSize: '16px'
                        },
                        message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Tunggu . . .",
                        baseZ: 9000,
                    });
                    this.submit();
                }
            });
        });

    });

    function editBed(id){
        var id_bed = id;
        $.ajax({
            url : '{{ route('edit.ruangan-bed-ajax') }}',
            type : 'GET',
            data : {
                id_bed : id_bed
            },
            beforeSend : function(){
                $.blockUI({
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff',
                        fontSize: '16px'
                    },
                    message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Tunggu . . .",
                    baseZ: 9000,
                });
            },
            success : function(res){
                $.unblockUI();
                console.log(JSON.stringify(res));
                $('#id_bed').val(res.data.id);
                $('#nama_ruangan').val(res.data.ruangan.nama_ruangan);
                $('#jenis_ruangan').val(res.data.jenis_ruangan.ruangan_jenis);
                $('#edit-bed').modal('show');

                if(res.data.bayi == 0 || res.data.bayi == ""){
                    $('#bayi-0').prop('checked',true);
                }

                if(res.data.bayi == 1){
                    $('#bayi-1').prop('checked',true);
                }

                if(res.data.status == 0 || res.data.status == ""){
                    $('#status-0').prop('checked',true);
                }

                if(res.data.status == 1){
                    $('#status-1').prop('checked',true);
                }

            },
            error : function(res){
                console.log('error');
            }
        });
    }

    // Quick add bed function
    function quickAddBed(jumlah) {
        Swal.fire({
            title: 'Tambah '+jumlah+' Bed',
            text: "Apakah Anda yakin ingin menambahkan "+jumlah+" bed ke ruangan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('store.ruangan-bed') }}'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'idruangan',
                    'value': '{{ $ruangan->id }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'idjenis',
                    'value': '{{ $ruangan->idjenis }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'jumlah_bed',
                    'value': jumlah
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'bayi',
                    'value': '0'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'status',
                    'value': '1'
                }));

                $.blockUI({
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff',
                        fontSize: '16px'
                    },
                    message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Menambahkan "+jumlah+" bed...",
                    baseZ: 9000,
                });

                $('body').append(form);
                form.submit();
            }
        });
    }

    // Fungsi bulk action bed (aktifkan/nonaktifkan multiple bed)
    function bulkActionBed(action) {
        let selected = [];
        $('.bed-checkbox:checked').each(function() {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            Swal.fire({
                text: 'Pilih minimal satu bed terlebih dahulu',
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return;
        }

        const actionText = action === 'activate' ? 'mengaktifkan' : 'menonaktifkan';

        Swal.fire({
            text: `Apakah Anda yakin ingin ${actionText} ${selected.length} bed?`,
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Ya, lanjutkan",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-secondary"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $('<form>', {
                    'action': '{{ route('bulk.bed.status') }}',
                    'method': 'POST'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'ids',
                    'value': selected.join(',')
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'action',
                    'value': action
                }));

                $.blockUI({
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff',
                        fontSize: '16px'
                    },
                    message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Memproses...",
                    baseZ: 9000,
                });

                $('body').append(form);
                form.submit();
            }
        });
    }

    @if ($message = session('gagal'))
        Swal.fire({
            text: '{{ $message }}',
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    @endif
    @if ($message = session('berhasil'))
        Swal.fire({
            text: '{{ $message }}',
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    @endif
</script>
@endsection
