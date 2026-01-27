<!DOCTYPE html>
<html>

<head>
    <title>Cetak SPRI - {{ $spri->no_spri }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
        }

        .content {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            width: 150px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
        }

        .ttd {
            width: 200px;
            float: right;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">RUMAH SAKIT ANGKATAN UDARA DR. NORMAN T. LUBIS</div>
        <div class="subtitle">Jl. Terusan Kopo No. 500 Lanud Sulaiman, Margahayu, Bandung</div>
        <div class="title" style="margin-top: 15px; text-decoration: underline;">SURAT PERINTAH RAWAT INAP (SPRI)</div>
        <div>Nomor: {{ $spri->no_spri }}</div>
    </div>

    <div class="content">
        <p>Mohon dilakukan pendaftaran Rawat Inap untuk pasien berikut:</p>
        <table class="table">
            <tr>
                <td class="label">Nama Pasien</td>
                <td>: {{ $spri->pasien->nama_pasien }}</td>
            </tr>
            <tr>
                <td class="label">No RM</td>
                <td>: {{ $spri->pasien->no_rm }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir</td>
                <td>: {{ $spri->pasien->tgllahir }} ({{ $spri->pasien->usia_tahun }} Thn)</td>
            </tr>
            <tr>
                <td class="label">Rencana Rawat</td>
                <td>: {{ date('d-m-Y', strtotime($spri->tgl_rawat)) }}</td>
            </tr>
            <tr>
                <td class="label">Dokter DPJP</td>
                <td>: {{ $spri->dokter->nama_dokter }}</td>
            </tr>
            <tr>
                <td class="label">Poli Asal</td>
                <td>: {{ $spri->poli->poli ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Penanggung</td>
                <td>: {{ $spri->idbayar == 2 ? 'BPJS Kesehatan' : ($spri->idbayar == 1 ? 'Umum/Tunai' : 'Lainnya') }}
                </td>
            </tr>
            {{-- <tr>
                <td class="label">Diagnosa Awal</td>
                <td>: {{ $spri->diagnosa ?? '-' }}</td>
            </tr> --}}
        </table>

        <p style="margin-top: 20px;">
            Harap surat ini diserahkan ke bagian Pendaftaran Rawat Inap.
        </p>
    </div>

    <div class="footer">
        <div class="ttd">
            <p>Bandung, {{ date('d-m-Y') }}</p>
            <p>Dokter Penanggung Jawab,</p>
            <br><br><br>
            <p>( {{ $spri->dokter->nama_dokter }} )</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
