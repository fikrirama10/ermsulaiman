@extends('layouts.index')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Detail Transaksi Penjualan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('penjualan-obat.index') }}">Penjualan Obat</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="invoice-title">
                    <h4 class="float-right font-size-16">Faktur #{{ $penjualan->nomor_faktur }}</h4>
                    <div class="mb-4">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo" height="20"/>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            <strong>Pembeli:</strong><br>
                            {{ $penjualan->nama_pembeli }}<br>
                            @if($penjualan->rawat)
                                (Pasien: {{ $penjualan->rawat->pasien->nama_pasien }} / RM: {{ $penjualan->rawat->no_rm }})
                            @endif
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <address>
                            <strong>Tanggal Order:</strong><br>
                            {{ date('d F Y', strtotime($penjualan->tanggal)) }}<br>
                            <br>
                            <strong>Kasir:</strong><br>
                            {{ $penjualan->user->name }}
                        </address>
                    </div>
                </div>

                <div class="py-2 mt-3">
                    <h3 class="font-size-15 font-weight-bold">Rincian Order</h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No.</th>
                                <th>Item</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Jumlah</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->detail as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->obat->nama_obat }}</td>
                                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-right">{{ $item->jumlah }}</td>
                                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right"><strong>Sub Total</strong></td>
                                <td class="text-right"><strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-print-none mt-4">
                    <div class="float-right">
                        <a href="{{ route('penjualan-obat.cetak', $penjualan->id) }}" target="_blank" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i> Cetak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
