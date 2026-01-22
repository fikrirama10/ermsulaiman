<!DOCTYPE html>
<html>
<head>
    <title>Formulir Rekam Medis - {{ $pasien->nama_pasien }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th, .table td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .no-border td { border: none; padding: 2px 5px; }
        .title { font-weight: bold; background-color: #f0f0f0; }
        @media print {
            @page { margin: 1cm; size: A4; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>RSAU dr. NORMAN T. LUBIS</h3>
        <h2>FORMULIR REKAM MEDIS</h2>
    </div>

    <table class="table no-border" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td width="15%">No. RM</td><td width="35%">: <strong>{{ $pasien->no_rm }}</strong></td>
            <td width="15%">Jaminan</td><td width="35%">: {{ $pasien->kepesertaan_bpjs ?? 'Umum' }}</td>
        </tr>
        <tr>
            <td>Nama</td><td>: {{ $pasien->nama_pasien }}</td>
            <td>Tgl Lahir</td><td>: {{ date('d-m-Y', strtotime($pasien->tgllahir)) }} ({{ $pasien->usia_tahun }} Th)</td>
        </tr>
        <tr>
            <td>JK</td><td>: {{ $pasien->jenis_kelamin }}</td>
            <td>Alamat</td><td>: {{ $pasien->alamat?->alamat ?? '-' }}</td>
        </tr>
    </table>

    <table class="table">
        <tr class="title">
            <td colspan="4">RIWAYAT KUNJUNGAN TERAKHIR</td>
        </tr>
        @if($last_visit)
        <tr>
            <td width="20%">Tanggal</td>
            <td colspan="3">{{ date('d F Y H:i', strtotime($last_visit->tglmasuk)) }}</td>
        </tr>
        <tr>
            <td>Poli / Dokter</td>
            <td colspan="3">{{ $last_visit->poli }} / {{ $last_visit->nama_dokter }}</td>
        </tr>
        <tr>
            <td>Anamnesa (S)</td>
            <td colspan="3">{{ $last_visit->anamnesa ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pemeriksaan (O)</td>
            <td colspan="3">
                @php $fisik = json_decode($last_visit->pemeriksaan_fisik); @endphp
                TD: {{ $fisik->tekanan_darah ?? '-' }} mmHg, 
                Nadi: {{ $fisik->nadi ?? '-' }} x/m, 
                Suhu: {{ $fisik->suhu ?? '-' }} C, 
                RR: {{ $fisik->pernapasan ?? '-' }} x/m
            </td>
        </tr>
        <tr>
            <td>Diagnosa (A)</td>
            <td colspan="3">{{ $last_visit->diagnosa ?? '-' }}</td>
        </tr>
        <tr>
            <td>Terapi (P)</td>
            <!--  -->
        </tr>
        @else
        <tr>
            <td colspan="4" style="text-align: center; padding: 20px;">Belum ada riwayat kunjungan</td>
        </tr>
        @endif
    </table>

    <div style="margin-top: 50px; float: right; text-align: center; width: 200px;">
        <p>Bandung, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>(__________________)</p>
        <p>Petugas Pendaftaran</p>
    </div>
</body>
</html>
