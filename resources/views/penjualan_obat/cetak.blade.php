<!DOCTYPE html>
<html>
<head>
    <title>Faktur Penjualan - {{ $penjualan->nomor_faktur }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; }
        .header p { margin: 2px; }
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { padding: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 4px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h3>RSAU dr. M. Salamun</h3>
        <p>Jl. Raya Ciumbuleuit No.203, Ciumbuleuit, Kec. Cidadap, Kota Bandung, Jawa Barat 40142</p>
        <hr>
        <h4>FAKTUR PENJUALAN OBAT</h4>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">No. Faktur</td>
            <td width="2%">:</td>
            <td>{{ $penjualan->nomor_faktur }}</td>
            <td width="15%">Tanggal</td>
            <td width="2%">:</td>
            <td>{{ date('d-m-Y', strtotime($penjualan->tanggal)) }}</td>
        </tr>
        <tr>
            <td>Pembeli</td>
            <td>:</td>
            <td>{{ $penjualan->nama_pembeli }}</td>
            <td>Kasir</td>
            <td>:</td>
            <td>{{ $penjualan->user->name }}</td>
        </tr>
        @if($penjualan->keterangan)
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td colspan="4">{{ $penjualan->keterangan }}</td>
        </tr>
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th width="5%">No</th>
                <th>Nama Obat</th>
                <th width="15%">Harga</th>
                <th width="10%">Qty</th>
                <th width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detail as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->obat->nama_obat }}</td>
                <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><strong>Total Akhir</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Bandung, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>({{ $penjualan->user->name }})</p>
    </div>
</body>
</html>
