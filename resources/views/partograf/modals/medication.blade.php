<!--begin::Modal - Medication-->
<div class="modal fade" id="medicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-white">
                    <i class="fas fa-pills me-2"></i> Pemberian Obat & Cairan
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMedication">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Pemberian</label>
                        <input type="datetime-local" name="administration_time" class="form-control" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Jenis</label>
                        <select name="medication_type" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="drug">Obat</option>
                            <option value="iv_fluid">Cairan IV</option>
                            <option value="oxytocin">Oksitosin</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Nama Obat/Cairan</label>
                        <input type="text" name="medication_name" class="form-control" required 
                               placeholder="Contoh: RL, Oksitosin, Cefazolin">
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Dosis</label>
                        <input type="text" name="dosage" class="form-control" 
                               placeholder="Contoh: 500ml, 10 IU, 1 gram">
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Rute Pemberian</label>
                        <select name="route" class="form-select" required>
                            <option value="">-- Pilih Rute --</option>
                            <option value="oral">Per Oral</option>
                            <option value="iv">Intravena (IV)</option>
                            <option value="im">Intramuskular (IM)</option>
                            <option value="sc">Subkutan (SC)</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan pemberian obat/cairan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

