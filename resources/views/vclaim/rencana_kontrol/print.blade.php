<!DOCTYPE html>
<html>

<head>
    <title>Surat Rencana Kontrol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .logo {
            width: 200px;
            margin-right: 20px;
        }

        .title {
            flex-grow: 1;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .surat-no {
            font-size: 14px;
            font-weight: bold;
        }

        .content {
            margin-left: 5px;
        }

        .row {
            display: flex;
            margin-bottom: 5px;
        }

        .label {
            width: 150px;
        }

        .value {
            flex-grow: 1;
            font-weight: bold;
        }

        .footer {
            margin-top: 5%;
            display: flex;
            justify-content: flex-end;
            margin-right: 50px;
        }

        .signature {
            text-align: center;
            width: 200px;
        }

        .meta {
            margin-top: 5%;
            font-size: 10px;
            font-style: italic;
        }

        @media print {
            @page {
                size: A4 portrait;
                /* Sample looks landscape-ish or wide */
                margin: 1cm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        $data = $surat['response'] ?? [];
        $peserta = $data['peserta'] ?? [];
    @endphp

    <div class="header">
        <img src="https://new-simrs.rsausulaiman.com/frontend/images/logo%20bpjs-02.png" alt="BPJS Kesehatan"
            class="logo">
        <!-- Adjust path if needed or use text -->
        <div class="title">
            SURAT RENCANA KONTROL<br>
            RS LANUD SULAIMAN
        </div>
        <div class="surat-no">
            No. {{ $data['noSuratKontrol'] ?? '-' }}
        </div>
    </div>

    <div class="content">
        <div class="row" style="margin-bottom: 20px;">
            <div class="label">Kepada Yth</div>
            <div class="value">
                {{ $data['namaDokter'] ?? '-' }}<br>
                {{ $data['poliTujuan'] ?? '-' }}
            </div>
        </div>

        <div style="margin-bottom: 15px;">Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</div>

        <div class="row">
            <div class="label">No.Kartu</div>
            <div class="value">: {{ $rawat->pasien->no_bpjs ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="label">Nama Peserta</div>
            <div class="value">: {{ $rawat->pasien->nama_pasien ?? '-' }} ({{ $rawat->pasien->jenis_kelamin ?? '-' }})
            </div>
        </div>
        <div class="row">
            <div class="label">Tgl.Lahir</div>
            <div class="value">: {{ $rawat->pasien->tgllahir ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="label">Diagnosa</div>
            <div class="value">: {{ $data['sep']['diagnosa'] ?? ($data['diagnosa'] ?? '-') }}</div>
            {{-- Note: API response structure varies. Sometimes diagnosa is top level, sometimes inside sep (if detail view) --}}
        </div>
        <div class="row">
            <div class="label">Rencana Kontrol</div>
            <div class="value">: {{ $data['tglRencanaKontrol'] ?? '-' }}</div>
        </div>

        <div style="margin-top: 5px;">
            Demikian atas bantuannya,diucapkan banyak terima kasih.
        </div>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui DPJP,</p>
            <br><br><br>
            <p>___________________</p>
        </div>
    </div>

    <div class="meta">
        Tgl.Entri: {{ $data['tglTerbit'] ?? date('Y-m-d') }} | Tgl.Cetak: {{ date('d-m-Y h:i A') }}
    </div>

</body>

</html>
