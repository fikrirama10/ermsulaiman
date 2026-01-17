{{-- Tab Navigation --}}
<div class="mb-5 hover-scroll-x">
    <div class="d-grid">
        <ul class="nav nav-tabs text-nowrap fw-bold" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active"
                   data-bs-toggle="tab" href="#kt_tab_pane_1" aria-selected="true" role="tab">
                    <i class="ki-duotone ki-medical-sign fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Data Berobat
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_2" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-document fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Resume Pasien
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_resep" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-capsule fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Obat Obatan
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_rb" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-time fs-3 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat Berobat
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_3" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-calendar-tick fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Rencana Tindak Lanjut
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_4" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-syringe fs-3 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Tindakan
                </a>
            </li>
            @if (auth()->user()->idpriv == 7)
                <li class="nav-item" role="presentation">
                    <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                       data-bs-toggle="tab" href="#kt_tab_pane_61" aria-selected="false" role="tab" tabindex="-1">
                        <i class="ki-duotone ki-flask fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                        Order Tindakan Penunjang
                    </a>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_5" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-test-tube fs-3 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Hasil Pemeriksaan Penunjang
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                   data-bs-toggle="tab" href="#kt_tab_pane_6" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ki-duotone ki-file-up fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Upload Hasil Pemeriksaan Penunjang Luar
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- Tab Content --}}
<div class="tab-content" id="myTabContent">
    {{-- Tab 1: Data Berobat --}}
    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
        <div class="card card-flush mb-5">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-lg-3 fw-semibold text-muted">Poli</label>
                    <div class="col-lg-9">
                        <span class="fw-bold fs-6 text-gray-800">{{ $rawat->poli?->poli }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 fw-semibold text-muted">Dokter</label>
                    <div class="col-lg-9">
                        <span class="fw-bold fs-6 text-gray-800">{{ $rawat->dokter?->nama_dokter }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 fw-semibold text-muted">Tgl. Berobat</label>
                    <div class="col-lg-9">
                        @php setlocale(LC_ALL, 'IND'); @endphp
                        <span class="fw-bold fs-6 text-gray-800">
                            {{ \Carbon\Carbon::parse($rawat->tglmasuk)->formatLocalized('%A %d %B %Y') }}
                            {{ date('H:i:s', strtotime($rawat->tglmasuk)) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-lg-3 fw-semibold text-muted">Penanggung</label>
                    <div class="col-lg-9">
                        <span class="fw-bold fs-6 text-gray-800">{{ $rawat->bayar->bayar }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 2: Resume Pasien --}}
    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
        @include('rekap-medis.tabs.resume-pasien', [
            'resume_medis' => $resume_medis,
            'resume_detail' => $resume_detail ?? null,
            'rawat' => $rawat,
            'pasien' => $pasien
        ])
    </div>

    {{-- Tab 3: Obat Obatan --}}
    <div class="tab-pane fade" id="kt_tab_pane_resep" role="tabpanel">
        @include('rekap-medis.tabs.obat-obatan', [
            'resep_dokter' => $resep_dokter ?? [],
            'rawat' => $rawat
        ])
    </div>

    {{-- Tab 4: Riwayat Berobat --}}
    <div class="tab-pane fade" id="kt_tab_pane_rb" role="tabpanel">
        @include('rekap-medis.tabs.riwayat-berobat', [
            'riwayat_berobat' => $riwayat_berobat ?? [],
            'rawat' => $rawat,
            'resume_medis' => $resume_medis
        ])
    </div>

    {{-- Tab 5: Rencana Tindak Lanjut --}}
    <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
        @include('rekap-medis.tabs.tindak-lanjut', [
            'tindak_lanjut' => $tindak_lanjut ?? null,
            'rawat' => $rawat
        ])
    </div>

    {{-- Tab 6: Tindakan --}}
    <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
        @include('rekap-medis.tabs.tindakan', [
            'resume_medis' => $resume_medis,
            'rawat' => $rawat,
            'tarif_all' => $tarif_all ?? [],
            'dokter' => $dokter ?? [],
            'tarif' => $tarif ?? []
        ])
    </div>

    {{-- Tab 7: Order Penunjang (Only for Doctor) --}}
    @if (auth()->user()->idpriv == 7)
        <div class="tab-pane fade" id="kt_tab_pane_61" role="tabpanel">
            <div class="alert alert-info">
                <i class="ki-duotone ki-information-5 fs-2x text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Silakan klik tombol di bawah untuk menambahkan order penunjang
            </div>
            <button class="btn btn-primary btn-sm mb-5" data-bs-toggle="modal" data-bs-target="#modal_penunjang">
                <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                Tambah Penunjang
            </button>
            @include('rawat-inap.menu.penunjang')
        </div>
    @endif

    {{-- Tab 8: Hasil Pemeriksaan Penunjang --}}
    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
        @include('rekap-medis.tabs.hasil-penunjang', [
            'pemeriksaan_lab' => $pemeriksaan_lab ?? null,
            'pemeriksaan_radiologi' => $pemeriksaan_radiologi ?? null
        ])
    </div>

    {{-- Tab 9: Upload Hasil Penunjang Luar --}}
    <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
        @include('rekap-medis.tabs.upload-penunjang', [
            'rawat' => $rawat,
            'pemeriksaan_luar' => $pemeriksaan_luar ?? []
        ])
    </div>
</div>
