@extends('layouts.index')
@section('css')
<style>
    .schedule-card {
        border-left: 4px solid #28a745;
    }
    .time-badge {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
    }
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Jadwal Dokter</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dokter.index') }}" class="text-muted text-hover-primary">Management Dokter</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Jadwal</li>
                    </ul>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dokter.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!-- Dokter Info Card -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="symbol symbol-70px">
                                <div class="symbol-label bg-light-primary">
                                    <i class="bi bi-person-badge fs-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-1 text-gray-800">{{ $dokter->nama_dokter }}</h4>
                            <div class="d-flex gap-3">
                                <span class="badge badge-light-info">{{ $dokter->kode_dokter }}</span>
                                @if($dokter->idspesialis)
                                    <span class="badge badge-light-success">Spesialis</span>
                                @else
                                    <span class="badge badge-light-warning">Dokter Umum</span>
                                @endif
                                @if($dokter->status == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Card -->
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Jadwal Praktek</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Kelola jadwal praktek dokter</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                            <i class="bi bi-plus-circle"></i> Tambah Jadwal
                        </button>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table id="tbl-jadwal" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                            <thead class="border bg-light">
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th width="5%">No</th>
                                    <th width="15%">Hari</th>
                                    <th width="20%">Waktu</th>
                                    <th width="25%">Poli</th>
                                    <th width="15%">Kuota</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Aksi</th>
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

<!-- Modal Tambah/Edit Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="bi bi-calendar-plus"></i> <span id="modal-jadwal-title">Tambah Jadwal</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formJadwal" action="{{ route('dokter.jadwal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="iddokter" value="{{ $dokter->id }}">
                <input type="hidden" id="jadwal_id" name="jadwal_id">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Hari <span class="text-danger">*</span></label>
                        <select name="hari" id="hari" class="form-select" required>
                            <option value="">Pilih Hari</option>
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jumat</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Minggu</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Poli <span class="text-danger">*</span></label>
                        <select name="idpoli" id="idpoli" class="form-select" required>
                            <option value="">Pilih Poli</option>
                            @foreach($poli as $p)
                                <option value="{{ $p->id }}">{{ $p->poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Kuota Pasien <span class="text-danger">*</span></label>
                        <input type="number" name="kuota" id="kuota" class="form-control" min="1" max="100" placeholder="Masukkan kuota pasien" required>
                        <div class="form-text">Maksimal 100 pasien per jadwal</div>
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
    let table = $("#tbl-jadwal").DataTable({
        "language": {
            "lengthMenu": "Show _MENU_",
            "emptyTable": "Belum ada jadwal untuk dokter ini"
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex align-items-center justify-content-end'f>>" +
               "<'table-responsive'tr>" +
               "<'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        processing: true,
        serverSide: true,
        ajax: '{{ route("dokter.jadwal", $dokter->id) }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'hari_nama' },
            { data: 'waktu' },
            { data: 'nama_poli' },
            { data: 'kuota' },
            { data: 'status_badge' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    // Form submission
    $('#formJadwal').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let url = $('#jadwal_id').val() ? '{{ route("dokter.jadwal.update", ":id") }}'.replace(':id', $('#jadwal_id').val()) : '{{ route("dokter.jadwal.store") }}';

        if($('#jadwal_id').val()) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modalTambahJadwal').modal('hide');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
                $('#formJadwal')[0].reset();
                $('#jadwal_id').val('');
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
});

function editJadwal(id) {
    $.get('{{ route("dokter.jadwal.edit", ":id") }}'.replace(':id', id), function(data) {
        $('#modal-jadwal-title').text('Edit Jadwal');
        $('#jadwal_id').val(data.id);
        $('#hari').val(data.idhari);
        $('#jam_mulai').val(data.jam_mulai);
        $('#jam_selesai').val(data.jam_selesai);
        $('#idpoli').val(data.idpoli);
        $('#kuota').val(data.kuota);
        $('#modalTambahJadwal').modal('show');
    });
}

function deleteJadwal(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("dokter.jadwal.destroy", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    $('#tbl-jadwal').DataTable().ajax.reload();
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
                        title: 'Error!',
                        text: 'Gagal menghapus jadwal'
                    });
                }
            });
        }
    });
}

// Reset modal
$('#modalTambahJadwal').on('hidden.bs.modal', function() {
    $('#formJadwal')[0].reset();
    $('#jadwal_id').val('');
    $('#modal-jadwal-title').text('Tambah Jadwal');
});
</script>
@endsection
