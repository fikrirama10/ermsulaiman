@extends('layouts.index')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .card-stats {
        transition: transform 0.2s;
    }
    .card-stats:hover {
        transform: translateY(-5px);
    }
    .badge-rawat {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!-- Toolbar -->
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Dashboard Pendaftaran
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">Home</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Pendaftaran</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Pendaftaran Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            <!-- Filters -->
            <div class="card mb-5 mb-xl-8 border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 my-2">
                             <label class="form-label fs-7 fw-bold text-muted">Tanggal Kunjungan</label>
                             <input type="date" class="form-control form-control-solid" id="filter_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 my-2">
                            <label class="form-label fs-7 fw-bold text-muted">Jenis Layanan</label>
                            <select class="form-select form-select-solid" id="filter_jenis">
                                <option value="">Semua Layanan</option>
                                <option value="1">Rawat Jalan</option>
                                <option value="2">Rawat Inap</option>
                                <option value="3">UGD</option>
                            </select>
                        </div>
                        <div class="col-md-6 my-2 text-end">
                             <button class="btn btn-light-primary btn-sm mt-6" onclick="reloadTable()">
                                <i class="fas fa-filter me-2"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card card-xl-stretch shadow-sm border-0">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Daftar Kunjungan Pasien</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Monitoring kunjungan pasien hari ini</span>
                    </h3>
                     <div class="card-toolbar">
                        <!-- Toolbar content if needed -->
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="tableKunjungan" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-100px">No Antrian</th>
                                    <th class="min-w-100px">Waktu Daftar</th>
                                    <th class="min-w-150px">Pasien</th>
                                    <th class="min-w-100px">Jenis</th>
                                    <th class="min-w-150px">Tujuan / Dokter</th>
                                    <th class="min-w-100px">Penanggung</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-100px text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    var table;
    $(document).ready(function() {
        table = $('#tableKunjungan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pendaftaran.data') }}",
                data: function(d) {
                    d.date = $('#filter_date').val();
                    d.jenis_rawat = $('#filter_jenis').val();
                }
            },
            columns: [
                { data: 'no_antrian', name: 'rawat.no_antrian',
                  render: function(data, type, row) {
                      return '<span class="fw-bold text-dark fs-5">' + (data ? data : '-') + '</span>';
                  }
                },
                { data: 'tglmasuk', name: 'rawat.tglmasuk' },
                { data: 'nama_pasien', name: 'pasien.nama_pasien', 
                  render: function(data, type, row) {
                      return '<div class="d-flex flex-column">' +
                             '<span class="text-dark fw-bold mb-1">' + data + '</span>' +
                             '<span class="text-muted fs-7">RM: ' + row.no_rm + '</span>' +
                             '</div>';
                  }
                },
                { data: 'jenis_badge', name: 'jenis_badge', orderable: false, searchable: false },
                { data: 'nama_dokter', name: 'dokter.nama_dokter',
                   render: function(data, type, row) {
                       let location = '';
                       if(row.idjenisrawat == 1) location = row.nama_poli;
                       if(row.idjenisrawat == 2) location = row.nama_ruangan;
                       if(row.idjenisrawat == 3) location = 'IGD';
                       
                       return '<div class="d-flex flex-column">' +
                             '<span class="text-gray-800 mb-1">' + (location || '-') + '</span>' +
                             '<span class="text-muted fs-7">' + (data || '-') + '</span>' +
                             '</div>';
                   }
                },
                { data: 'bayar', name: 'rawat_bayar.bayar' },
                { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[1, 'desc']], // Sort by time desc
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });

    function reloadTable() {
        table.ajax.reload();
    }

    function batalkanKunjungan(id) {
        Swal.fire({
            title: 'Batalkan Kunjungan?',
            text: "Kunjungan yang dibatalkan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('pendaftaran.batal', '') }}/" + id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', 'Kunjungan telah dibatalkan.', 'success');
                        reloadTable();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        })
    }
</script>
@endsection
