<div id="interm">
    <div class="alert alert-info d-flex align-items-center mb-5">
        <i class="fas fa-info-circle fs-2 me-3"></i>
        <div>
            <strong>Rujuk Internal (Interm)</strong><br>
            <small>Rujukan antar poli di dalam rumah sakit untuk pemeriksaan atau konsultasi lebih lanjut</small>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label fw-bold">
                <i class="fas fa-clinic-medical text-primary me-1"></i>
                Poli Tujuan
                <span class="text-danger">*</span>
            </label>
            <select class="form-select form-select-solid"
                    data-control="select2"
                    data-placeholder="Pilih poli tujuan..."
                    name='poli_rujuk'
                    id='poli_rujuk_interm'
                    required>
                <option value=""></option>
                @foreach ($poli as $item)
                    <option value="{{ $item->kode }}">{{ $item->poli }}</option>
                @endforeach
            </select>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Pilih poli untuk rujukan internal
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-check text-primary me-1"></i>
                Tanggal Kontrol
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_kontrol_intem'
                   id='tgl_kontrol_intem'
                   min="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Jadwal kontrol ke poli tujuan
            </div>
        </div>
    </div>
</div>
