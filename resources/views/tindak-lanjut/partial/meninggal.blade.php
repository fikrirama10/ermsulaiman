<div id="meninggal">
    <div class="alert alert-danger d-flex align-items-center mb-5">
        <i class="fas fa-exclamation-circle fs-2 me-3"></i>
        <div>
            <strong class="fs-5">PERHATIAN: Data Pasien Meninggal</strong><br>
            <small>Status ini akan mencatat pasien sebagai meninggal dalam sistem. Pastikan data yang dimasukkan sudah benar dan sesuai dengan dokumen resmi.</small>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-times text-danger me-1"></i>
                Tanggal Meninggal
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_kontrol'
                   id='tgl_meninggal'
                   max="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Tanggal tidak boleh di masa depan
            </div>
        </div>
    </div>
    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4">
        <i class="fas fa-clipboard-check fs-2tx text-warning me-4"></i>
        <div class="d-flex flex-stack flex-grow-1">
            <div class="fw-semibold">
                <h4 class="text-gray-900 fw-bold">Dokumen Pendukung</h4>
                <div class="fs-6 text-gray-700">Pastikan dokumen surat keterangan meninggal telah dilengkapi dan disimpan sesuai prosedur.</div>
        </div>
    </div>
</div>
