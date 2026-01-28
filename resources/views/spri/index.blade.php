@extends('layouts.index')

@section('content')
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>Daftar SPRI (Surat Perintah Rawat Inap)</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <a href="{{ route('spri.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah SPRI
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-4">
            <form id="filter-form" class="mb-5">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tgl_awal" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 mt-8">
                        <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_spri">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th>No SPRI</th>
                            <th>Tanggal Rawat</th>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Dokter DPJP</th>
                            <th>Poli Asal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var table = $('#kt_table_spri').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('spri.index') }}",
                    data: function(d) {
                        d.tgl_awal = $('input[name="tgl_awal"]').val();
                        d.tgl_akhir = $('input[name="tgl_akhir"]').val();
                    }
                },
                columns: [{
                        data: 'no_spri',
                        name: 'no_spri'
                    },
                    {
                        data: 'tgl_rawat',
                        name: 'tgl_rawat'
                    },
                    {
                        data: 'no_rm',
                        name: 'no_rm'
                    },
                    {
                        data: 'pasien.nama_pasien',
                        name: 'pasien.nama_pasien',
                        defaultContent: '-'
                    },
                    {
                        data: 'dokter.nama_dokter',
                        name: 'dokter.nama_dokter',
                        defaultContent: '-'
                    },
                    {
                        data: 'poli.poli',
                        name: 'poli.poli',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#btn-filter').click(function() {
                table.draw();
            });
        });
    </script>
@endsection
