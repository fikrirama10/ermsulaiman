<!DOCTYPE html>
<html>

<head>
    <title>SEP {{ $data['noSep'] ?? 'Document' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            /* Slightly smaller for PDF to fit nicely */
            color: #000;
            padding: 20px;
        }

        .sep-header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .sep-logo {
            width: 180px;
            vertical-align: middle;
        }

        .sep-title-container {
            display: inline-block;
            vertical-align: middle;
            margin-left: 20px;
        }

        .sep-title {
            font-size: 16px;
            font-weight: bold;
        }

        .sep-subtitle {
            font-size: 14px;
        }

        .sep-content {
            width: 100%;
        }

        .sep-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sep-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .label {
            width: 110px;
        }

        .colon {
            width: 10px;
            text-align: center;
        }

        .value {
            font-weight: bold;
        }

        .sep-footer {
            margin-top: 30px;
            width: 100%;
        }

        .sep-note {
            font-size: 9px;
            font-style: italic;
            width: 65%;
            float: left;
        }

        .sep-signature {
            width: 30%;
            float: right;
            text-align: center;
        }

        .qr-code img {
            width: 70px;
            height: 70px;
        }

        /* Helper for two column layout using table since float can be tricky in dompdf sometimes */
        .layout-table {
            width: 100%;
        }

        .col-left {
            width: 55%;
            vertical-align: top;
        }

        .col-right {
            width: 45%;
            vertical-align: top;
            padding-left: 10px;
        }
    </style>
</head>

<body>
    @php
        $data = $sep['response'] ?? [];
    @endphp

    <div class="sep-header">
        {{-- Use base64 for logo if needed, or absolute path. Assuming remote URL works if allow_url_fopen is on --}}
        {{-- If remote image fails, we might need to use public_path() --}}
        <img src="https://new-simrs.rsausulaiman.com/frontend/images/logo%20bpjs-02.png" class="sep-logo" alt="BPJS">
        <div class="sep-title-container">
            <div class="sep-title">SURAT ELIGIBILITAS PESERTA</div>
            <div class="sep-subtitle">RSAU LANUD SULAIMAN</div>
        </div>
    </div>

    <table class="layout-table">
        <tr>
            <td class="col-left">
                <table class="sep-table">
                    <tr>
                        <td class="label">No.SEP</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['noSep'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tgl.SEP</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['tglSep'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">No.Kartu</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['peserta']['noKartu'] ?? '-' }} ( MR.
                            {{ $data['peserta']['noMr'] ?? '-' }} )</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Peserta</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['peserta']['nama'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tgl.Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['peserta']['tglLahir'] ?? '-' }} &nbsp; Kelamin:
                            {{ $data['peserta']['kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td class="label">No.Telepon</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $rawat->visit->pasien->nohp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Sub/Spesialis</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['poli'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Dokter</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['dokter'] ?? ($rawat->dokter->nama_dokter ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Faskes Perujuk</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['provPerujuk']['nmProvider'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Diagnosa Awal</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['diagnosa'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Catatan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['catatan'] ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td class="col-right">
                <table class="sep-table">
                    <tr>
                        <td class="label">Peserta</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['peserta']['jnsPeserta'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jns.Rawat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['jnsPelayanan'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jns.Kunjungan</td>
                        <td class="colon">:</td>
                        <td class="value">-</td>
                    </tr>
                    <tr>
                        <td class="label">Poli Perujuk</td>
                        <td class="colon">:</td>
                        <td class="value">-</td>
                    </tr>
                    <tr>
                        <td class="label">Kls.Hak</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['peserta']['hakKelas'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kls.Rawat</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['kelasRawat'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Penjamin</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data['penjamin'] ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="sep-footer">
        <div class="sep-note">
            *Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
            *SEP Bukan sebagai bukti penjamin peserta<br><br>
            Cetakan ke 1 {{ date('d-m-Y H:i:s') }}
        </div>
        <div class="sep-signature">
            <div style="margin-bottom: 5px;">Pasien/Keluarga Pasien</div>
            <div class="qr-code">
                {{-- Generate QR Code using SimpleSoftwareIO/QrCode --}}
                {{-- We use svg or png format. Img src data uri is robust for dompdf --}}
                <img src="data:image/svg+xml;base64, {!! base64_encode(
                    QrCode::format('svg')->size(80)->errorCorrection('H')->generate($data['noSep'] ?? 'SEP-RSAU'),
                ) !!} ">
            </div>
            <div style="font-weight: bold; margin-top: 5px;">{{ $data['peserta']['nama'] ?? 'Pasien' }}</div>
        </div>
    </div>
</body>

</html>
