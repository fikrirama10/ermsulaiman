@extends('layouts.index')
@section('css')
<style>
    .dokter-card {
        transition: all 0.3s ease;
    }
    .dokter-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Management Dokter</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">Menu</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Data Master</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Management Dokter</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!-- Stats Cards -->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-xl-3">
                    <div class="card stats-card h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="bi bi-person-badge fs-2x text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-2x fw-bold text-white" id="total-dokter">0</div>
                                    <div class="fs-7 text-white-75">Total Dokter</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card bg-success h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="bi bi-check-circle fs-2x text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-2x fw-bold text-white" id="dokter-aktif">0</div>
                                    <div class="fs-7 text-white-75">Dokter Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card bg-warning h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="bi bi-stethoscope fs-2x text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-2x fw-bold text-white" id="dokter-spesialis">0</div>
                                    <div class="fs-7 text-white-75">Dokter Spesialis</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card bg-info h-100">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-info">
                                        <i class="bi bi-calendar3 fs-2x text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-2x fw-bold text-white" id="jadwal-aktif">0</div>
                                    <div class="fs-7 text-white-75">Jadwal Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Data Dokter</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Kelola data dokter, jadwal, dan kuota</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahDokter">
                            <i class="bi bi-plus-circle"></i> Tambah Dokter
                        </button>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <!-- Filters -->
                    <div class="d-flex gap-3 mb-4 p-3 bg-light rounded">
                        <div class="flex-grow-1" style="max-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Filter Status</label>
                            <select id="filter_status" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option selected value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="flex-grow-1" style="max-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Filter Spesialis</label>
                            <select id="filter_spesialis" class="form-select form-select-sm">
                                <option value="">Semua Spesialis</option>
                                @foreach($spesialis as $spes)
                                    <option value="{{ $spes->id }}">{{ $spes->spesialis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1" style="max-width: 200px;">
                            <label class="form-label fw-bold fs-7 mb-1">Filter User</label>
                            <select id="filter_user" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <option value="with_user">Sudah Punya User</option>
                                <option value="without_user">Belum Punya User</option>
                            </select>
                        </div>
                        <div class="vr my-2"></div>
                        <div>
                            <label class="form-label fw-bold fs-7 mb-1">Aksi Massal</label>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                                    <i class="bi bi-check-circle"></i> Aktifkan
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                                    <i class="bi bi-x-circle"></i> Nonaktifkan
                                </button>
                            </div>
                        </div>
                        <div class="vr my-2"></div>
                        <div>
                            <label class="form-label fw-bold fs-7 mb-1">Sinkronisasi</label>
                            <button type="button" class="btn btn-sm btn-primary" onclick="syncAllUsers()">
                                <i class="bi bi-arrow-repeat"></i> Sinkron Semua User
                            </button>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="tbl-dokter" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                            <thead class="border bg-light">
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th width="3%">
                                        <input type="checkbox" class="form-check-input" id="check-all-dokter">
                                    </th>
                                    <th width="5%">No</th>
                                    <th width="12%">Kode Dokter</th>
                                    <th width="20%">Nama Dokter</th>
                                    <th width="12%">Spesialis</th>
                                    <th width="12%">Status User</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Dokter -->
<div class="modal fade" id="modalTambahDokter" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="bi bi-person-plus"></i> <span id="modal-title">Tambah Dokter</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDokter" action="{{ route('dokter.store') }}" method="POST">
                @csrf
                <input type="hidden" id="dokter_id" name="dokter_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Kode Dokter <span class="text-danger">*</span></label>
                                <input type="text" name="kode_dokter" id="kode_dokter" class="form-control" placeholder="Masukkan kode dokter" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Nama Dokter <span class="text-danger">*</span></label>
                                <input type="text" name="nama_dokter" id="nama_dokter" class="form-control" placeholder="Masukkan nama dokter" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Spesialis</label>
                                <select name="idspesialis" id="idspesialis" class="form-select">
                                    <option value="">Pilih Spesialis (Opsional)</option>
                                    @foreach($spesialis as $spes)
                                        <option value="{{ $spes->id }}">{{ $spes->spesialis }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Poli Utama</label>
                                <select name="idpoli" id="idpoli" class="form-select">
                                    <option value="">Pilih Poli Utama</option>
                                    @foreach($poli as $p)
                                        <option value="{{ $p->id }}">{{ $p->poli }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">SIP</label>
                                <input type="text" name="sip" id="sip" class="form-control" placeholder="Masukkan SIP">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="user-account-section">
                        <hr class="my-4">
                        <h6 class="text-primary mb-3"><i class="bi bi-person-lock"></i> Akun User (Wajib untuk dokter baru)</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username login">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="email@example.com">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bi bi-info-circle fs-3 me-2"></i>
                            <div>
                                <strong>Info:</strong> Akun user akan dibuat otomatis dengan idpriv = 7 (Dokter). Password minimal 6 karakter.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(function(){
    let table = $("#tbl-dokter").DataTable({
        "language": {
            "lengthMenu": "Show _MENU_",
            "emptyTable": "Tidak ada data dokter"
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex align-items-center justify-content-end'f>>" +
               "<'table-responsive'tr>" +
               "<'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('dokter.index') }}',
            data: function (d) {
                d.filter_status = $('#filter_status').val();
                d.filter_spesialis = $('#filter_spesialis').val();
                d.filter_user = $('#filter_user').val();
            }
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'kode_dokter' },
            { data: 'nama_dokter' },
            { data: 'spesialis_name' },
            { data: 'user_status' },
            { data: 'status_badge' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        drawCallback: function() {
            updateStats();
        }
    });

    // Filters
    $('#filter_status, #filter_spesialis').on('change', function() {
        table.ajax.reload();
    });

    $('#filter_user').on('change', function() {
        table.ajax.reload();
    });

    // Check all
    $('#check-all-dokter').on('click', function() {
        $('.dokter-checkbox').prop('checked', this.checked);
    });

    // Form submission
    $('#formDokter').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let url = $('#dokter_id').val() ? '{{ route("dokter.update", ":id") }}'.replace(':id', $('#dokter_id').val()) : '{{ route("dokter.store") }}';

        if($('#dokter_id').val()) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modalTambahDokter').modal('hide');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
                $('#formDokter')[0].reset();
                $('#dokter_id').val('');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = '';
                for(let key in errors) {
                    errorMsg += errors[key][0] + '\n';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMsg
                });
            }
        });
    });

    // Update stats
    function updateStats() {
        // This would be called via AJAX to get real stats
        // For demo purposes, using table data
        let totalRows = table.data().count();
        $('#total-dokter').text(totalRows);
    }
});

// Edit dokter
function editDokter(id) {
    $.get('{{ route("dokter.edit", ":id") }}'.replace(':id', id), function(data) {
        $('#modal-title').text('Edit Dokter');
        $('#dokter_id').val(data.id);
        $('#kode_dokter').val(data.kode_dokter);
        $('#nama_dokter').val(data.nama_dokter);
        $('#idspesialis').val(data.idspesialis);
        $('#nip').val(data.nip);
        $('#str').val(data.str);
        $('#sip').val(data.sip);
        $('#status').val(data.status);

        // Hide user account section for edit
        $('#user-account-section').hide();
        $('#username').removeAttr('required');
        $('#password').removeAttr('required');

        $('#modalTambahDokter').modal('show');
    });
}

// Sync all users
function syncAllUsers() {
    Swal.fire({
        title: 'Konfirmasi Sinkronisasi',
        text: 'Apakah Anda yakin ingin membuat akun user untuk semua dokter yang belum memiliki akun?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Sinkronkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("dokter.sync-all-users") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#tbl-dokter').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: response.message + '<br><small class="text-muted">Username: (nama dokter tanpa spasi)<br>Password: dokter123</small>',
                        timer: 5000
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Gagal melakukan sinkronisasi'
                    });
                }
            });
        }
    });
}

// Toggle status
function toggleStatus(id) {
    $.post('{{ route("dokter.toggle", ":id") }}'.replace(':id', id), {
        _token: '{{ csrf_token() }}'
    }, function(response) {
        $('#tbl-dokter').DataTable().ajax.reload();
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message,
            timer: 1500
        });
    });
}

// Bulk actions
function bulkAction(action) {
    let selected = [];
    $('.dokter-checkbox:checked').each(function() {
        selected.push($(this).val());
    });

    if (selected.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Pilih minimal satu dokter terlebih dahulu'
        });
        return;
    }

    const actionText = action === 'activate' ? 'mengaktifkan' : 'menonaktifkan';

    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin ${actionText} ${selected.length} dokter?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, lanjutkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("dokter.bulk-toggle") }}', {
                _token: '{{ csrf_token() }}',
                ids: selected.join(','),
                action: action
            }, function(response) {
                $('#tbl-dokter').DataTable().ajax.reload();
                $('#check-all-dokter').prop('checked', false);
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
            });
        }
    });
}

// Reset modal on close
$('#modalTambahDokter').on('hidden.bs.modal', function() {
    $('#formDokter')[0].reset();
    $('#dokter_id').val('');
    $('#modal-title').text('Tambah Dokter');

    // Show user account section and make fields required again
    $('#user-account-section').show();
    $('#username').attr('required', 'required');
    $('#password').attr('required', 'required');
});
</script>
@endsection
