@extends('layouts.index')
@section('css')
<style>
    .kuota-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .progress-circle {
        position: relative;
        width: 80px;
        height: 80px;
    }
    .progress-bar-circle {
        transform: rotate(-90deg);
    }
    .kuota-stats {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 10px;
    }
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Kuota Dokter</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dokter.index') }}" class="text-muted text-hover-primary">Management Dokter</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Kuota</li>
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
            <div class="card kuota-card text-white mb-5">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="symbol symbol-70px">
                                <div class="symbol-label bg-white bg-opacity-20">
                                    <i class="bi bi-person-badge fs-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-1 text-white">{{ $dokter->nama_dokter }}</h4>
                            <div class="d-flex gap-3">
                                <span class="badge bg-white text-primary">{{ $dokter->kode_dokter }}</span>
                                @if($dokter->idspesialis)
                                    <span class="badge bg-white bg-opacity-20 text-white">Spesialis</span>
                                @else
                                    <span class="badge bg-white bg-opacity-20 text-white">Dokter Umum</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="kuota-stats p-3 text-center">
                                <div class="d-flex justify-content-around">
                                    <div>
                                        <div class="fw-bold fs-2 text-white" id="totalKuota">0</div>
                                        <div class="text-white-50 fs-7">Total Kuota</div>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-2 text-warning" id="kuotaTerpakai">0</div>
                                        <div class="text-white-50 fs-7">Terpakai</div>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-2 text-success" id="kuotaSisa">0</div>
                                        <div class="text-white-50 fs-7">Sisa</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kuota Card -->
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Kuota Harian</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Kelola kuota praktek harian</span>
                    </h3>
                    <div class="card-toolbar">
                        {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKuota">
                            <i class="bi bi-plus-circle"></i> Tambah Kuota
                        </button> --}}
                    </div>
                </div>

                <div class="card-body pt-2">
                    <!-- Filter -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Filter Tanggal</label>
                            <input type="date" id="filterTanggal" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Filter Status</label>
                            <select id="filterStatus" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="resetFilter()">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tbl-kuota" class="table table-sm table-striped table-row-bordered gy-3 gs-5 border rounded">
                            <thead class="border bg-light">
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th width="5%">No</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">Hari</th>
                                    <th width="15%">Kuota</th>
                                    <th width="20%">Progress</th>
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

<!-- Modal Tambah/Edit Kuota -->
<div class="modal fade" id="modalTambahKuota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="bi bi-calendar-plus"></i> <span id="modal-kuota-title">Tambah Kuota</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formKuota" action="{{ route('dokter.kuota.store') }}" method="POST">
                @csrf
                <input type="hidden" name="iddokter" value="{{ $dokter->id }}">
                <input type="hidden" id="kuota_id" name="kuota_id">
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Kuota Pasien <span class="text-danger">*</span></label>
                        <input type="number" name="kuota" id="kuota" class="form-control" min="1" max="100" required>
                        <div class="form-text">Maksimal 100 pasien per hari</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Poli <span class="text-danger">*</span></label>
                        <select name="idpoli" id="idpoli_kuota" class="form-select" required>
                            <option value="">Pilih Poli</option>
                            @foreach($poli as $p)
                                <option value="{{ $p->id }}">{{ $p->poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
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
    let table = $("#tbl-kuota").DataTable({
        "language": {
            "lengthMenu": "Show _MENU_",
            "emptyTable": "Belum ada kuota untuk dokter ini"
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex align-items-center justify-content-end'f>>" +
               "<'table-responsive'tr>" +
               "<'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        processing: true,
        serverSide: true,
        order: [[1, 'desc']], // Order by tanggal descending
        ajax: {
            url: '{{ route("dokter.kuota", $dokter->id) }}',
            data: function(d) {
                d.tanggal = $('#filterTanggal').val();
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'tanggal_formatted' },
            { data: 'hari_nama' },
            { data: 'kuota' },
            { data: 'progress' },
            { data: 'status_badge' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    // Filter handlers
    $('#filterTanggal, #filterStatus').on('change', function() {
        table.ajax.reload();
    });

    // Load summary stats
    loadKuotaSummary();

    // Form submission
    $('#formKuota').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("dokter.kuota.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modalTambahKuota').modal('hide');
                table.ajax.reload();
                loadKuotaSummary();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
                $('#formKuota')[0].reset();
                $('#kuota_id').val('');
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

function loadKuotaSummary() {
    $.ajax({
        url: '{{ route("dokter.kuota.summary", $dokter->id) }}',
        type: 'GET',
        success: function(response) {
            $('#totalKuota').text(response.total_kuota || 0);
            $('#kuotaTerpakai').text(response.kuota_terpakai || 0);
            $('#kuotaSisa').text(response.kuota_sisa || 0);
        }
    });
}

function resetFilter() {
    $('#filterTanggal').val('');
    $('#filterStatus').val('');
    $("#tbl-kuota").DataTable().ajax.reload();
}

function editKuota(id) {
    // Implementation for edit kuota
    console.log('Edit kuota:', id);
}

function deleteKuota(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menghapus kuota ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementation for delete kuota
            console.log('Delete kuota:', id);
        }
    });
}

// Reset modal
$('#modalTambahKuota').on('hidden.bs.modal', function() {
    $('#formKuota')[0].reset();
    $('#kuota_id').val('');
    $('#modal-kuota-title').text('Tambah Kuota');
});
</script>
@endsection
