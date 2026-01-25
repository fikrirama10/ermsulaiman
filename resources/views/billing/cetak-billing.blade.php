<!DOCTYPE html>
<html>
<head>
    <title>Invoice Billing - {{ $kunjungan->idkunjungan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .patient-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE BILLING PERAWATAN</h2>
        <p>No. Kunjungan: {{ $kunjungan->idkunjungan }}</p>
    </div>

    <div class="patient-info">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 100px;">No. RM</td>
                <td style="border: none;">: {{ $kunjungan->no_rm }}</td>
                <td style="border: none; width: 100px;">Tanggal</td>
                <td style="border: none;">: {{ date('d/m/Y', strtotime($kunjungan->tgl_kunjungan)) }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Nama Pasien</td>
                <td style="border: none;">: {{ $kunjungan->pasien->nama_pasien ?? $kunjungan->pasien->nama }}</td>
                <td style="border: none;">Kasir</td>
                <td style="border: none;">: {{ auth()->user()->name }}</td>
            </tr>
        </table>
    </div>

    <h4>Rincian Layanan</h4>
    <table>
        <thead>
            <tr>
                <th>Layanan / Unit</th>
                <th>Tgl Masuk</th>
                <th>Item</th>
                <th class="text-right">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rawatEpisodes as $episode)
                @php $first = true; @endphp
                
                {{-- Tindakan --}}
                @foreach($episode->riwayat_tindakan as $tindakan)
                <tr>
                    <td>{{ $first ? ($episode->poli->poli ?? ($episode->ruangan->nama_ruangan ?? 'Unit')) : '' }}</td>
                    <td>{{ $first ? date('d-m-Y', strtotime($episode->tglmasuk)) : '' }}</td>
                    <td>Tindakan: {{ $tindakan->tarif->nama_tarif }}</td>
                    <td class="text-right">{{ number_format($tindakan->tarif->tarif, 0, ',', '.') }}</td>
                </tr>
                @php $first = false; @endphp
                @endforeach

                {{-- Ruangan --}}
                @foreach($episode->riwayat_ruangan as $ruangan)
                <tr>
                    <td>{{ $first ? ($episode->poli->poli ?? ($episode->ruangan->nama_ruangan ?? 'Unit')) : '' }}</td>
                    <td>{{ $first ? date('d-m-Y', strtotime($episode->tglmasuk)) : '' }}</td>
                    <td>Akomodasi: {{ $ruangan->ruangan->nama_ruangan }} ({{ $ruangan->los }} Hari)</td>
                    <td class="text-right">{{ number_format(($ruangan->los ?? 0) * ($ruangan->kelas->harga ?? 0), 0, ',', '.') }}</td>
                </tr>
                @php $first = false; @endphp
                @endforeach
            @endforeach

            {{-- Farmasi Summary (Condensed) --}}
            @if($totalFarmasi > 0)
            <tr>
                <td colspan="3"><strong>Total Biaya Farmasi / Obat</strong></td>
                <td class="text-right"><strong>{{ number_format($totalFarmasi, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($totalTindakan + $totalRuangan + $totalFarmasi, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <br><br><br>
        <p>( _______________________ )</p>
        <p>Petugas Kasir</p>
    </div>
</body>
</html>
