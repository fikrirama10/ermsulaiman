<!DOCTYPE html>
<html>

<head>
    <title>Label Pasien - {{ $pasien->nama_pasien }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .label {
            width: 60mm;
            height: 40mm;
            /* border: 1px dotted #ccc; Use for preview only */
            padding: 2mm;
            box-sizing: border-box;
            overflow: hidden;
            background: white;
            page-break-after: always;
            /* Ensure 1 label per 'page' sequence for roll printers */
        }

        .header {
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 2mm;
            border-bottom: 1px solid black;
            padding-bottom: 1px;
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .rm-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1mm;
        }

        .rm-label {
            font-size: 10px;
            font-weight: bold;
        }

        .rm-number {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 1mm;
            line-height: 1.1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .meta {
            font-size: 9px;
            line-height: 1.2;
        }

        @media print {
            @page {
                size: 60mm 40mm;
                margin: 0;
            }

            body {
                margin: 0;
            }

            .label {
                border: none;
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    @for ($i = 0; $i < $copies; $i++)
        <div class="label">
            <div class="header">RSAU dr. NORMAN T. LUBIS</div>
            <div class="content">
                <div class="rm-row">
                    <span class="rm-label">No. RM</span>
                    <span class="rm-number">{{ $pasien->no_rm }}</span>
                </div>
                <div class="name">{{ $pasien->nama_pasien }}</div>
                <div class="meta">
                    Lahir: {{ date('d-m-Y', strtotime($pasien->tgllahir)) }} ({{ $pasien->usia_tahun }} Th)<br>
                    JK: {{ $pasien->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}<br>
                    {{ $pasien->alamat?->kelurahan->nama ?? '' }}
                </div>
            </div>
        </div>
    @endfor
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
