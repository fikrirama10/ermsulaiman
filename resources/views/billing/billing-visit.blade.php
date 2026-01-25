@extends('layouts.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Catatan Kunjungan & Billing Pasien</h5>
                    <span class="badge bg-light text-primary">{{ $kunjungan->idkunjungan }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Pasien</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150">No. RM</td>
                                    <td>: <strong>{{ $kunjungan->no_rm }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Nama Pasien</td>
                                    <td>: {{ $kunjungan->pasien->nama_pasien }}</td>
                                </tr>
                                <tr>
                                    <td>Usia saat Kunjungan</td>
                                    <td>: {{ $kunjungan->usia_kunjungan }} Tahun</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>Detail Kunjungan</h6>
                            <p class="mb-1">Tanggal: {{ \Carbon\Carbon::parse($kunjungan->tgl_kunjungan)->format('d F Y') }}</p>
                            <p class="mb-0 text-muted">Didaftarkan oleh: {{ $kunjungan->user->detail->nama ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="fas fa-route me-2"></i>Timeline Perawatan</h6>
                    <div class="timeline">
                        @foreach($rawatEpisodes as $episode)
                        <div class="episode-item mb-4 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                                <div>
                                    <span class="badge bg-info text-white me-2">
                                        @if($episode->idjenisrawat == 1) Rawat Jalan
                                        @elseif($episode->idjenisrawat == 3) UGD
                                        @else Rawat Inap @endif
                                    </span>
                                    <strong>{{ $episode->poli->nama ?? 'Poli/Unit Umum' }}</strong>
                                    <span class="text-dark ms-2">({{ \Carbon\Carbon::parse($episode->tglmasuk)->format('d-m-Y H:i') }})</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-dark d-block">Dokter: {{ $episode->dokter->nama_dokter ?? '-' }}</small>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <p class="mb-0 small text-muted font-weight-bold">Tindakan:</p>
                                        <button type="button" class="btn btn-xs btn-primary py-0" data-bs-toggle="modal" data-bs-target="#addTindakanModal{{ $episode->id }}">
                                            <i class="fas fa-plus fa-xs"></i>
                                        </button>
                                    </div>
                                    <ul class="list-unstyled small">
                                        @forelse($episode->riwayat_tindakan as $tindakan)
                                        <li class="d-flex justify-content-between align-items-center mb-1">
                                            <span>- {{ $tindakan->tarif->nama_tarif ?? 'Tindakan' }}</span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">Rp {{ number_format($tindakan->tarif->tarif ?? 0, 0, ',', '.') }}</span>
                                                <form action="{{ route('billing.delete-tindakan', $tindakan->id) }}" method="POST" onsubmit="return confirm('Hapus tindakan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0"><i class="fas fa-trash-alt fa-xs"></i></button>
                                                </form>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="text-muted font-italic">Tidak ada tindakan</li>
                                        @endforelse
                                    </ul>

                                    <!-- Modal Add Tindakan -->
                                    <div class="modal fade" id="addTindakanModal{{ $episode->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('billing.add-tindakan') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="idrawat" value="{{ $episode->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tambah Tindakan - {{ $episode->poli->nama ?? 'Unit' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pilih Tindakan</label>
                                                            <select name="idtindakan" class="form-select select2-modal" required>
                                                                <option value="">-- Pilih Tindakan --</option>
                                                                @foreach($tarifs as $tarif)
                                                                <option value="{{ $tarif->id }}">{{ $tarif->nama_tarif }} (Rp {{ number_format($tarif->tarif, 0, ',', '.') }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Pelaksana/Dokter</label>
                                                            <select name="iddokter" class="form-select">
                                                                <option value="">-- Pilih Dokter --</option>
                                                                @foreach($dokters as $dr)
                                                                <option value="{{ $dr->id }}" {{ $dr->id == $episode->iddokter ? 'selected' : '' }}>{{ $dr->nama_dokter }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Metode Bayar</label>
                                                            <select name="idbayar" class="form-select" required>
                                                                @foreach($bayars as $b)
                                                                <option value="{{ $b->id }}" {{ $b->id == $episode->idbayar ? 'selected' : '' }}>{{ $b->bayar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Tindakan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 small text-muted font-weight-bold">Ruangan/Bed:</p>
                                    <ul class="list-unstyled small">
                                        @forelse($episode->riwayat_ruangan as $ruangan)
                                        <li class="d-flex justify-content-between">
                                            <span>- {{ $ruangan->ruangan->nama ?? 'Ruangan' }} ({{ $ruangan->kelas->nama ?? '-' }})</span>
                                            <span>{{ $ruangan->los }} Hari</span>
                                        </li>
                                        @empty
                                        <li class="text-muted font-italic">Tidak ada perpindahan ruangan</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 small text-muted font-weight-bold">Resep Obat:</p>
                                    <ul class="list-unstyled small">
                                        @forelse($episode->riwayat_resep as $resep)
                                        <li>- {{ $resep->kode_resep }} ({{ $resep->tgl_resep }})</li>
                                        @empty
                                        <li class="text-muted font-italic">Tidak ada resep</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-7">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6>Metode Pembayaran & Diskon</h6>
                                    <form id="finishBillingForm" action="{{ route('billing.finish', $kunjungan->idkunjungan) }}" method="POST">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small">Tipe Pembayaran</label>
                                                <select name="payment_method" id="payment_method" class="form-select form-select-sm" onchange="toggleSplitFields()">
                                                    <option value="1">Full Tunai / Umum</option>
                                                    <option value="2">Full Klaim (BPJS/Asuransi)</option>
                                                    <option value="3">Split Payment (BPJS + Tunai)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small">Diskon Tambahan</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" name="discount" id="discount_input" class="form-control" placeholder="0" oninput="calculateTotal()">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="split_fields" style="display: none;" class="p-3 border rounded bg-white mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label small">Ditanggung BPJS/Asuransi</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="amount_bpjs" id="amount_bpjs" class="form-control" oninput="recomputeSplit()">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small">Dibayar Tunai (Sisa)</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="amount_cash" id="amount_cash" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card bg-dark text-white border-0">
                                <div class="card-body">
                                    <h6 class="border-bottom border-secondary pb-2">Ringkasan Billing</h6>
                                    <div class="d-flex justify-content-between py-1 small">
                                        <span class="text-muted">Total Tindakan:</span>
                                        <span>Rp {{ number_format($totalTindakan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 small">
                                        <span class="text-muted">Total Farmasi:</span>
                                        <span>Rp {{ number_format($totalFarmasi, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 small">
                                        <span class="text-muted">Total Akomodasi:</span>
                                        <span>Rp {{ number_format($totalRuangan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 small border-bottom border-secondary mb-2">
                                        <span class="text-muted">Diskon:</span>
                                        <span class="text-danger">- Rp <span id="discount_display">0</span></span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2">
                                        <strong>Total Harus Dibayar:</strong>
                                        <strong class="text-success h4 mb-0">Rp <span id="grand_total_display">{{ number_format($totalTindakan + $totalFarmasi + $totalRuangan, 0, ',', '.') }}</span></strong>
                                    </div>
                                    <input type="hidden" id="base_total" value="{{ $totalTindakan + $totalFarmasi + $totalRuangan }}">
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <a href="{{ route('billing.cetak', $kunjungan->idkunjungan) }}" target="_blank" class="btn btn-outline-secondary me-2"><i class="fas fa-print me-1"></i> Cetak Billing</a>
                                <button type="button" onclick="document.getElementById('finishBillingForm').submit()" class="btn btn-primary"><i class="fas fa-check-circle me-1"></i> Selesaikan & Tutup Billing</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .episode-item {
        background: #fdfdfd;
        transition: all 0.2s ease;
    }

    .episode-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    function calculateTotal() {
        const baseTotal = parseFloat(document.getElementById('base_total').value);
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;
        const grandTotal = Math.max(0, baseTotal - discount);

        document.getElementById('discount_display').innerText = discount.toLocaleString('id-ID');
        document.getElementById('grand_total_display').innerText = grandTotal.toLocaleString('id-ID');

        // Auto update split cash if in split mode
        if (document.getElementById('payment_method').value == "3") {
            recomputeSplit();
        }
    }

    function toggleSplitFields() {
        const method = document.getElementById('payment_method').value;
        const splitFields = document.getElementById('split_fields');
        if (method == "3") {
            splitFields.style.display = 'block';
            recomputeSplit();
        } else {
            splitFields.style.display = 'none';
        }
    }

    function recomputeSplit() {
        const baseTotal = parseFloat(document.getElementById('base_total').value);
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;
        const netTotal = Math.max(0, baseTotal - discount);

        const bpjsAmount = parseFloat(document.getElementById('amount_bpjs').value) || 0;
        const cashAmount = Math.max(0, netTotal - bpjsAmount);

        document.getElementById('amount_cash').value = cashAmount;
    }
</script>
@endsection