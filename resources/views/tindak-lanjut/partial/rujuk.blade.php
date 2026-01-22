<div id="rujuk">
    <div class="row mb-5">
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-alt text-primary me-1"></i>
                Tanggal Rujukan
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_kontrol_rujuk'
                   id='tgl_kontrol_rujuk'
                   min="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Tanggal rencana rujukan ke faskes lain
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label fw-bold">
                <i class="fas fa-hospital text-primary me-1"></i>
                Tujuan Faskes Rujukan
                <span class="text-danger">*</span>
            </label>
            <select name="tujuan_rujuk"
                    data-control="select2"
                    data-placeholder="Cari fasilitas kesehatan..."
                    class="form-select form-select-solid"
                    id="tujuan_rujuk"
                    required>
                <option value=""></option>
            </select>
            <div class="form-text text-muted">
                <i class="fas fa-search"></i> Ketik untuk mencari faskes
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">
                <i class="fas fa-clinic-medical text-primary me-1"></i>
                Poli Tujuan
                <span class="text-danger">*</span>
            </label>
            <select name="poli_rujuk"
                    data-control="select2"
                    data-placeholder="Pilih poli..."
                    class="form-select form-select-solid"
                    id="poli_rujuk"
                    required>
                <option value=""></option>
            </select>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Pilih poli di faskes tujuan
            </div>
        </div>
    </div>
</div>

