@extends('layouts.index')

@section('custom-style')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Riwayat Penjualan Obat (Direct Sale)</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Farmasi</a></li>
                    <li class="breadcrumb-item active">Penjualan Obat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Daftar Transaksi</h4>
                    <a href="{{ route('penjualan-obat.create') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus font-size-16 align-middle mr-2"></i> Transaksi Baru
                    </a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Faktur</th>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th>Total Harga</th>
                            <th>Kasir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('penjualan-obat.index') }}",
            columns: [
                { data: 'nomor_faktur', name: 'nomor_faktur' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'nama_pembeli', name: 'nama_pembeli' },
                { data: 'total_harga', name: 'total_harga', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
                { data: 'user', name: 'user' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ],
            order: [[ 1, 'desc' ]] // Order by date desc
        });
    });
</script>
@endsection
