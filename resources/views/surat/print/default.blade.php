<!DOCTYPE html>
<html>
<head>
    <title>Cetak Surat</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double black; padding-bottom: 10px; }
        .header h2, .header h3, .header p { margin: 0; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .nomor { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; text-align: justify; }
        .footer { text-align: right; margin-top: 50px; }
        .ttd { height: 80px; }
        table { width: 100%; }
        td { vertical-align: top; }
        .label { width: 150px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>RSAU dr. NORMAN T. LUBIS</h3>
        <p>Jl. Terusan Kopo KM 10 No. 469, Sulaiman, Kec. Margahayu, Bandung</p>
        <p>Telp: (022) 5409608</p>
    </div>

    @switch($surat->jenis_surat)
        @case('sakit')
            <h3 class="title">SURAT KETERANGAN SAKIT</h3>
            @break
        @case('lahir')
            <h3 class="title">SURAT KETERANGAN KELAHIRAN</h3>
            @break
        @case('kematian')
            <h3 class="title">SURAT KETERANGAN KEMATIAN</h3>
            @break
        @case('rujukan')
            <h3 class="title">SURAT RUJUKAN</h3>
            @break
        @default
            <h3 class="title">SURAT KETERANGAN</h3>
    @endswitch
    
    <div class="nomor">Nomor: {{ $surat->no_surat }}</div>

    <div class="content">
        @if($surat->jenis_surat == 'sakit')
            <p>Yang bertanda tangan di bawah ini, Dokter RSAU dr. Norman T. Lubis menerangkan bahwa:</p>
            <table>
                <tr><td class="label">Nama</td><td>: {{ $surat->nama_pasien }}</td></tr>
                {{-- Add age, address from DB if available --}}
            </table>
            <p>Perlu istirahat karena sakit selama {{ $surat->konten->lama_istirahat }} hari, terhitung mulai tanggal {{ date('d-m-Y', strtotime($surat->konten->dari_tgl)) }} sampai dengan tanggal {{ date('d-m-Y', strtotime($surat->konten->sampai_tgl)) }}.</p>
            @if(isset($surat->konten->diagnosa)) <p>Diagnosa: {{ $surat->konten->diagnosa }}</p> @endif
        
        @elseif($surat->jenis_surat == 'lahir')
            <p>Yang bertanda tangan di bawah ini, menerangkan bahwa pada:</p>
            <table>
                <tr><td class="label">Hari / Tanggal</td><td>: {{ \Carbon\Carbon::parse($surat->konten->tgl_lahir)->isoFormat('dddd, D MMMM Y') }}</td></tr>
                <tr><td class="label">Pukul</td><td>: {{ $surat->konten->jam_lahir }} WIB</td></tr>
            </table>
            <p>Telah lahir seorang bayi:</p>
            <table>
                <tr><td class="label">Nama</td><td>: <b>{{ $surat->konten->nama_bayi }}</b></td></tr>
                <tr><td class="label">Jenis Kelamin</td><td>: {{ $surat->konten->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td></tr>
                <tr><td class="label">Berat Badan</td><td>: {{ $surat->konten->berat_bb }} gram</td></tr>
                <tr><td class="label">Panjang Badan</td><td>: {{ $surat->konten->panjang_pb }} cm</td></tr>
                <tr><td class="label">Anak Ke-</td><td>: {{ $surat->konten->anak_ke }}</td></tr>
            </table>
            <br>
            <table>
                <tr><td class="label">Nama Ibu</td><td>: {{ $surat->konten->nama_ibu }}</td></tr>
                <tr><td class="label">Nama Ayah</td><td>: {{ $surat->konten->nama_ayah }}</td></tr>
            </table>
        
        @elseif($surat->jenis_surat == 'kematian')
            <p>Yang bertanda tangan di bawah ini, Dokter RSAU dr. Norman T. Lubis menerangkan bahwa:</p>
            <table>
                 <tr><td class="label">Nama</td><td>: {{ $surat->nama_pasien }}</td></tr>
            </table>
            <p>Telah meninggal dunia pada:</p>
             <table>
                <tr><td class="label">Waktu</td><td>: {{ \Carbon\Carbon::parse($surat->konten->waktu_kematian)->isoFormat('dddd, D MMMM Y H:mm') }} WIB</td></tr>
                <tr><td class="label">Tempat</td><td>: {{ $surat->konten->tempat_kematian }}</td></tr>
                <tr><td class="label">Penyebab</td><td>: {{ $surat->konten->sebab_kematian }}</td></tr>
            </table>

        @elseif($surat->jenis_surat == 'rujukan')
            <p>Yth. TS Dokter Poli {{ $surat->konten->poli_tujuan }}</p>
            <p>Di {{ $surat->konten->faskes_tujuan }}</p>
            <p>Mohon pemeriksaan dan penanganan lebih lanjut penderita:</p>
             <table>
                 <tr><td class="label">Nama</td><td>: {{ $surat->nama_pasien }}</td></tr>
            </table>
            <p>Diagnosa: {{ $surat->konten->diagnosa }}</p>
            <p>Tindakan/Terapi yang telah diberikan: {{ $surat->konten->tindakan }}</p>
            <p>Alasan Rujukan: {{ $surat->konten->alasan }}</p>

        @else
            <p>{{ $surat->konten->isi ?? 'Isi surat...' }}</p>
        @endif
        
        <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="footer">
        <p>Bandung, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->isoFormat('D MMMM Y') }}</p>
        <p>Dokter Pemeriksa,</p>
        <div class="ttd">
            {{-- Signature Image if available --}}
        </div>
        <p><b>{{ $surat->nama_dokter }}</b></p>
    </div>
</body>
</html>
