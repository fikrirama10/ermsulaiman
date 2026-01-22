<div id="kontrol">
    <div class="row mb-5">
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-check text-primary me-1"></i>
                Tanggal Kontrol Kembali
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_kontrol'
                   id='tgl_kontrol'
                   min="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Pilih tanggal untuk kontrol berikutnya
            </div>
        </div>
    </div>
</div>
