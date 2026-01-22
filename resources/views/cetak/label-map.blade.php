<!DOCTYPE html>
<html>
<head>
    <title>Label Map - {{ $pasien->nama_pasien }}</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .map-label {
            width: 15cm;
            height: 10cm;
            border: 2px solid #000;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .rm-giant { font-size: 80px; font-weight: bold; margin: 20px 0; letter-spacing: 5px; }
        .name-large { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .meta-large { font-size: 18px; margin-top: 10px; }
        @media print {
            body { display: block; height: auto; }
            .map-label { border: none; width: 100%; height: auto; page-break-after: always; }
            @page { margin: 2cm; }
        }
    </style>
</head>
<body>
    <div class="map-label">
        <div style="font-size: 16px; font-weight: bold;">REKAM MEDIS</div>
        <div style="font-size: 14px;">RSAU dr. NORMAN T. LUBIS</div>
        
        <div class="rm-giant">{{ $pasien->no_rm }}</div>
        
        <div class="name-large">{{ $pasien->nama_pasien }}</div>
        <div class="meta-large">
            {{ date('d-m-Y', strtotime($pasien->tgllahir)) }} / {{ $pasien->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
        </div>
        <div class="meta-large" style="margin-top: 20px;">
            {{ $pasien->alamat?->alamat ?? '' }}
        </div>
    </div>
    <script>window.print();</script>
</body>
</html>
