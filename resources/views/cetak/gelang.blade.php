<!DOCTYPE html>
<html>
<head>
    <title>Gelang Pasien - {{ $pasien->nama_pasien }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .wristband {
            width: 25cm; /* Panjang gelang */
            height: 2.5cm; /* Lebar area cetak */
            /* border: 1px solid #000;  Debug border */
            display: flex;
            align-items: center;
            padding-left: 5cm; /* Gap untuk area perekat */
        }
        .content {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .info { font-size: 12px; font-weight: bold; }
        .rm { font-size: 18px; font-weight: bold; }
        .barcode { height: 40px; border: 1px solid #000; padding: 0 10px; display: flex; align-items: center; font-family: 'Courier New', Courier, monospace; letter-spacing: 3px; }
        @media print {
            @page { size: landscape; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="wristband">
        <div class="content">
            <div class="info">
                <div style="font-size: 14px; margin-bottom: 2px;">{{ substr($pasien->nama_pasien, 0, 25) }}</div>
                <div>{{ date('d-m-Y', strtotime($pasien->tgllahir)) }} ({{ $pasien->jenis_kelamin }})</div>
            </div>
            <div class="barcode">
                {{ $pasien->no_rm }}
            </div>
            <div class="info" style="font-size: 10px;">
                RSAU LUBIS
            </div>
        </div>
    </div>
    <script>window.print();</script>
</body>
</html>
