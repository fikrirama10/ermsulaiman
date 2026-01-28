<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Rujukan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-bpjs {
            width: 200px;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }

        .content {
            margin-top: 10px;
        }

        .row {
            display: flex;
            margin-bottom: 5px;
        }

        .label {
            width: 150px;
            font-weight: bold;
        }

        .value {
            flex: 1;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .signature {
            margin-top: 50px;
        }

        .info-box {
            border: 1px solid black;
            padding: 10px;
            margin-top: 20px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    @if (isset($rujukan['response']['rujukan']))
        @php $data = $rujukan['response']['rujukan']; @endphp
        <div class="header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Logo_BPJS_Kesehatan.png" class="logo-bpjs"
                alt="BPJS">
            <div class="title">SURAT RUJUKAN<br>RS AU Dr. NORMAN T. LUBUK PAKAM</div>
            <div style="width: 200px; text-align: right;">
                <b>No. {{ $data['noRujukan'] }}</b><br>
                Tgl. {{ $data['tglRujukan'] }}
            </div>
        </div>

        <div class="content">
            <div class="row">
                <div class="label">Kepada Yth</div>
                <div class="value">: {{ $data['namaPpkDirujuk'] }}</div>
            </div>
            <div class="row">
                <div class="label"></div>
                <div class="value"> Di Tempat</div>
            </div>

            <p>Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</p>

            <div class="row">
                <div class="label">No. Kartu</div>
                <div class="value">: {{ $data['peserta']['noKartu'] }}</div>
            </div>
            <div class="row">
                <div class="label">Nama Peserta</div>
                <div class="value">: {{ $data['peserta']['nama'] }} ({{ $data['peserta']['kelamin'] }})</div>
            </div>
            <div class="row">
                <div class="label">Tgl. Lahir</div>
                <div class="value">: {{ $data['peserta']['tglLahir'] }}</div>
            </div>
            <div class="row">
                <div class="label">Diagnosa</div>
                <div class="value">: {{ $data['diagnosa']['nama'] }}</div>
            </div>
            <div class="row">
                <div class="label">Keterangan</div>
                <div class="value">: {{ $data['catatan'] }}</div>
            </div>
            <div class="row">
                <div class="label">Rencana Kunjungan</div>
                <div class="value">: {{ $data['tglRencanaKunjungan'] }}</div>
            </div>
        </div>

        <div class="footer">
            <div>
                <br>
                <div class="info-box" style="text-align: left; width: 350px;">
                    Salam Sejawat,<br>
                    {{ $data['tglRujukan'] }}<br><br><br>
                    DPJP Layan
                </div>
            </div>
            <div>
                Mengetahui,<br>
                <br><br><br><br>
                _________________________
            </div>
        </div>

        <div class="info-box">
            * Rujukan Berlaku Selama 90 Hari sejak Tanggal Pembuatan<br>
            * Surat Rujukan ini harus dibawa saat berkunjung ke FKRTL Tujuan
        </div>
    @else
        <div class="alert alert-danger">
            Data Rujukan Tidak Ditemukan.
            <br>
            Debug: {{ json_encode($rujukan) }}
        </div>
    @endif

    <script>
        window.print();
    </script>
</body>

</html>
