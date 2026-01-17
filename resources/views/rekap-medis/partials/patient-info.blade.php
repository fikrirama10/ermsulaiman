<div class="card mb-5 mb-xl-10">
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bold m-0">Detail Pasien</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-dark fw-bold">{{ $pasien->nama_pasien }}</h2>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="patient-info-row">
                    <div class="patient-info-label">NIK</div>
                    <div class="patient-info-value">{{ $pasien->nik ?? '-' }}</div>
                </div>
                <div class="patient-info-row">
                    <div class="patient-info-label">No. RM</div>
                    <div class="patient-info-value">{{ $pasien->no_rm }}</div>
                </div>
                <div class="patient-info-row">
                    <div class="patient-info-label">Tgl. Lahir</div>
                    <div class="patient-info-value">
                        {{ $pasien->tgllahir }}
                        <span class="badge badge-light-primary">
                            {{ $pasien->usia_tahun }}Th {{ $pasien->usia_bulan }}Bln {{ $pasien->usia_hari }}Hr
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="patient-info-row">
                    <div class="patient-info-label">No. BPJS</div>
                    <div class="patient-info-value">{{ $pasien->no_bpjs ?? '-' }}</div>
                </div>
                <div class="patient-info-row">
                    <div class="patient-info-label">No. Handphone</div>
                    <div class="patient-info-value">{{ $pasien->nohp ?? '-' }}</div>
                </div>
                <div class="patient-info-row">
                    <div class="patient-info-label">Alamat</div>
                    <div class="patient-info-value">{{ $pasien->alamat->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
