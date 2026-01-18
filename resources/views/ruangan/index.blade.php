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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Ruangan</h1>
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
                        <h5 class="card-title">Data Ruangan</h5>
                    </div>
                    <div class="card-toolbar">
                        @canany(['rekammedis'])
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah-ruangan">
                                <i class="bi bi-plus-circle"></i> Tambah Ruangan
                            </button>
                        @endcanany
                    </div>
                </div>
                <!--begin::Body-->
                <div class="card-body pt-2">
                    <!-- Filter Section - Compact -->
                    <div class="d-flex gap-3 mb-4 p-3 bg-light rounded">
                        <div class="flex-grow-1" style="max-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Filter Status</label>
                            <select id="filter_status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="vr my-2"></div>
                        <div>
                            <label class="form-label fw-bold fs-7 mb-1">Aksi Massal</label>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-success" onclick="bulkActionRuangan('activate')" title="Aktifkan ruangan terpilih">
                                    <i class="bi bi-check-circle"></i> Aktifkan
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="bulkActionRuangan('deactivate')" title="Nonaktifkan ruangan terpilih">
                                    <i class="bi bi-x-circle"></i> Nonaktifkan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-ruangan" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                            <thead class="border bg-light">
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th width="3%">
                                        <input type="checkbox" class="form-check-input" id="check-all-ruangan">
                                    </th>
                                    <th width="5%">No</th>
                                    <th width="18%">Nama Ruangan</th>
                                    <th width="10%">Gender</th>
                                    <th width="8%">Bed<br><small>(Kosong/Total)</small></th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border fs-7">

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

<!-- Modal -->
<div class="modal fade" id="tambah-ruangan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="modal-title-ruangan">
                <i class="bi bi-plus-circle"></i> Tambah Ruangan
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="frm-data" action="{{ route('store.ruangan') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" id="ruangan_id" name="ruangan_id">
                <input type="hidden" id="form_method" name="_method" value="POST">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Jenis Ruangan <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_ruangan" name="jenis_ruangan" data-control="select2" data-placeholder="Pilih Jenis Ruangan" data-dropdown-parent="#tambah-ruangan" required>
                            <option></option>
                            @foreach ($jenis as $val)
                                <option value="{{ $val->id }}">{{ $val->ruangan_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" id="nama_ruangan" name="nama" class="form-control" placeholder="Masukkan nama ruangan" required>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Ruangan Untuk <span class="text-danger">*</span></label>
                        <select class="form-select" id="gender" name="gender" data-control="select2" data-placeholder="Pilih Ruangan" data-dropdown-parent="#tambah-ruangan" required>
                            <option></option>
                            @foreach ($gender as $val)
                                <option value="{{ $val->id }}">{{ $val->gender }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Kelas Ruangan <span class="text-danger">*</span></label>
                        <select class="form-select" id="kelas" name="kelas" data-control="select2" data-placeholder="Pilih Kelas Ruangan" data-dropdown-parent="#tambah-ruangan" required>
                            <option></option>
                            @foreach ($kelas as $val)
                                <option value="{{ $val->id }}">{{ $val->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" name="status" type="radio" value="1" id="status_aktif" required/>
                                <label class="form-check-label" for="status_aktif">
                                    Aktif
                                </label>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" name="status" type="radio" value="0" id="status_nonaktif" required/>
                                <label class="form-check-label" for="status_nonaktif">
                                    Tidak Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Masukkan keterangan (opsional)"></textarea>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-ruangan">
                    <i class="bi bi-save"></i> Simpan
                </button>
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

        var table = $("#tbl-ruangan").DataTable({
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
            ajax: {
                url: '{{ url()->current() }}',
                data: function(d) {
                    d.filter_status = $('#filter_status').val();
                }
            },
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_ruangan',
                    name: 'ruangan.nama_ruangan',
                    searchable: true
                },
                {
                    data: 'gender',
                    name: 'ruangan_gender.gender',
                    searchable: true
                },
                {
                    data: 'jumlah_bed',
                    name: 'jumlah_bed',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status_aktif',
                    name: 'status_aktif',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'opsi',
                    name: 'opsi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Filter status
        $('#filter_status').on('change', function() {
            table.ajax.reload();
        });

        // Check all checkbox
        $('#check-all-ruangan').on('click', function() {
            $('.ruangan-checkbox').prop('checked', this.checked);
        });

        // Uncheck "check all" jika ada yang dilepas
        $(document).on('change', '.ruangan-checkbox', function() {
            if (!this.checked) {
                $('#check-all-ruangan').prop('checked', false);
            }
            if ($('.ruangan-checkbox:checked').length === $('.ruangan-checkbox').length) {
                $('#check-all-ruangan').prop('checked', true);
            }
        });

        $("#frm-data").on( "submit", function(event) {
            event.preventDefault();
            var blockUI = new KTBlockUI(document.querySelector("#kt_app_body"));

            var isEdit = $('#ruangan_id').val() ? true : false;
            var actionText = isEdit ? 'mengupdate' : 'menyimpan';

            Swal.fire({
                title: isEdit ? 'Update Data' : 'Simpan Data',
                text: "Apakah Anda yakin akan " + actionText + " data ini ?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ' + (isEdit ? 'Update' : 'Simpan'),
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

    // Function to edit ruangan
    function editRuangan(id) {
        $.get('{{ route("edit.ruangan", ":id") }}'.replace(':id', id), function(data) {
            $('#modal-title-ruangan').html('<i class="bi bi-pencil"></i> Edit Ruangan');
            $('#ruangan_id').val(data.id);
            $('#form_method').val('PUT');
            $('#frm-data').attr('action', '{{ route("update.ruangan", ":id") }}'.replace(':id', data.id));

            $('#jenis_ruangan').val(data.idjenis).trigger('change');
            $('#nama_ruangan').val(data.nama_ruangan);
            $('#gender').val(data.gender).trigger('change');
            $('#kelas').val(data.idkelas).trigger('change');
            $('#keterangan').val(data.keterangan);

            if(data.status == 1) {
                $('#status_aktif').prop('checked', true);
            } else {
                $('#status_nonaktif').prop('checked', true);
            }

            $('#btn-submit-ruangan').html('<i class="bi bi-save"></i> Update');
            $('#tambah-ruangan').modal('show');
        });
    }

    // Function to delete ruangan
    function deleteRuangan(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus ruangan ini? Semua bed di ruangan ini juga akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("delete.ruangan", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#tbl-ruangan').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus ruangan'
                        });
                    }
                });
            }
        });
    }

    // Reset modal on close
    $('#tambah-ruangan').on('hidden.bs.modal', function() {
        $('#frm-data')[0].reset();
        $('#ruangan_id').val('');
        $('#form_method').val('POST');
        $('#frm-data').attr('action', '{{ route("store.ruangan") }}');
        $('#modal-title-ruangan').html('<i class="bi bi-plus-circle"></i> Tambah Ruangan');
        $('#btn-submit-ruangan').html('<i class="bi bi-save"></i> Simpan');

        // Reset select2
        $('#jenis_ruangan').val('').trigger('change');
        $('#gender').val('').trigger('change');
        $('#kelas').val('').trigger('change');
    });

    // Bulk action ruangan
    function bulkActionRuangan(action) {
        var selected = [];
        $('.ruangan-checkbox:checked').each(function() {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            Swal.fire({
                text: 'Pilih minimal 1 ruangan!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            return;
        }

        var actionText = action === 'activate' ? 'mengaktifkan' : 'menonaktifkan';

        Swal.fire({
            title: 'Konfirmasi Aksi Massal',
            text: `Apakah Anda yakin akan ${actionText} ${selected.length} ruangan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('bulk.ruangan.status') }}'
                });

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'action',
                    'value': action
                }));

                selected.forEach(function(id) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': 'ids[]',
                        'value': id
                    }));
                });

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
