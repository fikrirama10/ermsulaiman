<div class="modal-body">
    <style>
        .sep-container {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            color: #000;
        }

        .sep-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .sep-logo {
            width: 200px;
            margin-right: 20px;
        }

        .sep-title {
            font-size: 16px;
            font-weight: bold;
        }

        .sep-subtitle {
            font-size: 14px;
        }

        .sep-content {
            display: flex;
            width: 100%;
        }

        .sep-column {
            flex: 1;
            padding-right: 10px;
        }

        .sep-row {
            display: flex;
            margin-bottom: 5px;
        }

        .sep-label {
            width: 120px;
            flex-shrink: 0;
        }

        .sep-colon {
            width: 10px;
            text-align: center;
            margin-right: 5px;
        }

        .sep-value {
            flex: 1;
            font-weight: bold;
        }

        .sep-footer {
            display: flex;
            margin-top: 30px;
            justify-content: space-between;
        }

        .sep-note {
            font-size: 10px;
            font-style: italic;
            width: 60%;
        }

        .sep-signature {
            text-align: center;
            width: 30%;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            margin-bottom: 5px;
        }

        @media print {

            .modal-header,
            .modal-footer {
                display: none;
            }

            .modal-body {
                padding: 0;
            }
        }
    </style>

    @php
        $data = $sep['response'] ?? [];
    @endphp

    <div class="sep-container" id="printableArea">
        <div class="sep-header">
            <img src="https://new-simrs.rsausulaiman.com/frontend/images/logo%20bpjs-02.png" alt="BPJS Kesehatan"
                class="sep-logo">
            <div>
                <div class="sep-title">SURAT ELIGIBILITAS PESERTA</div>
                <div class="sep-subtitle">RSAU LANUD SULAIMAN</div>
            </div>
        </div>

        <div class="sep-content">
            <div class="sep-column">
                <div class="sep-row">
                    <div class="sep-label">No.SEP</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['noSep'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Tgl.SEP</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['tglSep'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">No.Kartu</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['peserta']['noKartu'] ?? '-' }} ( MR.
                        {{ $data['peserta']['noMr'] ?? '-' }} )</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Nama Peserta</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['peserta']['nama'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Tgl.Lahir</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['peserta']['tglLahir'] ?? '-' }} &nbsp;&nbsp; Kelamin:
                        {{ $data['peserta']['kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">No.Telepon</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $rawat->visit->pasien->nohp ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Sub/Spesialis</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['poli'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Dokter</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['dokter'] ?? ($rawat->dokter->nama_dokter ?? '-') }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Faskes Perujuk</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['provPerujuk']['nmProvider'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Diagnosa Awal</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['diagnosa'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Catatan</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['catatan'] ?? '-' }}</div>
                </div>
            </div>

            <div class="sep-column">
                <div class="sep-row">
                    <div class="sep-label">Peserta</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['peserta']['jnsPeserta'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Jns.Rawat</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['jnsPelayanan'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Jns.Kunjungan</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">-</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Poli Perujuk</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">-</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Kls.Hak</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['peserta']['hakKelas'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Kls.Rawat</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['kelasRawat'] ?? '-' }}</div>
                </div>
                <div class="sep-row">
                    <div class="sep-label">Penjamin</div>
                    <div class="sep-colon">:</div>
                    <div class="sep-value">{{ $data['penjamin'] ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="sep-footer">
            <div class="sep-note">
                *Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
                *SEP Bukan sebagai bukti penjamin peserta<br><br>
                Cetakan ke 1 {{ date('d-m-Y H:i:s') }}
            </div>
            <div class="sep-signature">
                <div style="margin-bottom: 5px;">Pasien/Keluarga Pasien</div>
                <!-- Placeholder for QR Code, ideally generated dynamically -->
                <div id="qrcode" class="d-flex justify-content-center mb-2"></div>
                <div style="font-weight: bold;">{{ $data['peserta']['nama'] ?? 'Pasien' }}</div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-primary"
        onclick="window.open('{{ route('pasien.print-sep-pdf') }}?sep={{ $data['noSep'] ?? '' }}', '_blank')">Cetak
        PDF</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function printSep() {
        var printContents = document.getElementById("printableArea").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        // Reload to restore events/scripts 
        location.reload();
    }

    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $data['noSep'] ?? 'SEP-RSAU' }}",
        width: 80,
        height: 80,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
