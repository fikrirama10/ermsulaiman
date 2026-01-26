@extends('layouts.index')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            <div class="card mb-5 mb-xl-10 shadow-sm border-0">
                <div class="card-body pt-9 pb-0 bg-primary rounded-top">
                     <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                         <div class="me-7 mb-4">
                            <div class="symbol symbol-60px symbol-lg-100px symbol-fixed position-relative">
                                <div class="symbol-label bg-white bg-opacity-10 border border-white border-opacity-25">
                                    <i class="ki-outline ki-document fs-3x text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                             <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <h1 class="text-white fs-2 fw-bold me-1">Surat & Dokumen Medis</h1>
                                    </div>
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <a href="#" class="d-flex align-items-center text-white text-opacity-75 text-hover-white me-5 mb-2">
                                            <i class="ki-outline ki-file fs-4 me-1 text-white text-opacity-75"></i> Kelola Surat Sakit, Rujukan, Lahir, dll.
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                     <a href="{{ route('surat.create') }}" class="btn btn-sm btn-white btn-color-gray-700 btn-active-secondary shadow-sm">
                                        <i class="ki-outline ki-plus-square fs-4 me-1"></i> Buat Surat Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-surat-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Surat..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                         <div class="d-flex justify-content-end" data-kt-surat-table-toolbar="base">
                            <select class="form-select form-select-solid w-150px me-3" id="filter_jenis">
                                <option value="">Semua Jenis</option>
                                <option value="sakit">Sakit</option>
                                <option value="lahir">Keterangan Lahir</option>
                                <option value="kematian">Kematian</option>
                                <option value="rujukan">Rujukan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <input type="date" class="form-control form-control-solid w-150px me-3" id="filter_tanggal" placeholder="Tanggal">
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                     <table id="tbl-surat" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">No Surat</th>
                                <th class="min-w-100px">Tanggal</th>
                                <th class="min-w-100px">Jenis</th>
                                <th class="min-w-150px">Pasien</th>
                                <th class="min-w-150px">Dokter</th>
                                <th class="min-w-100px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function(){
        let table = $("#tbl-surat").DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" },
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('surat.index') }}',
                data: function (d) {
                    d.filter_jenis = $('#filter_jenis').val();
                    d.filter_tanggal = $('#filter_tanggal').val();
                }
            },
            columns: [
                { data: 'no_surat', name: 'no_surat' },
                { data: 'tanggal_surat', name: 'tanggal_surat' },
                { data: 'jenis_surat', name: 'jenis_surat' },
                { data: 'pasien_info', name: 'nama_pasien' },
                { data: 'dokter_nama', name: 'nama_dokter' },
                { data: 'action', orderable: false, searchable: false, className: 'text-end' }
            ]
        });

        $('#filter_jenis, #filter_tanggal').change(function(){
            table.ajax.reload();
        });
        $('[data-kt-surat-table-filter="search"]').keyup(function(){
            table.search($(this).val()).draw();
        });

        window.deleteSurat = function(id) {
            Swal.fire({
                title: 'Hapus Surat?', text: "Data tidak bisa dikembalikan!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('surat.destroy', ':id') }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            table.ajax.reload();
                            Swal.fire('Terhapus!', res.message, 'success');
                        }
                    });
                }
            })
        }
    });
</script>
@endsection
