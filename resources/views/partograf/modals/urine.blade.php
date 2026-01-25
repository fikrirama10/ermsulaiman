<!--begin::Modal - Urine Output-->
<div class="modal fade" id="urineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h3 class="modal-title text-white">
                    <i class="fas fa-flask me-2"></i> Urine Output
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUrine">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Observasi</label>
                        <input type="datetime-local" name="observation_time" class="form-control" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Volume Urine (ml)</label>
                        <input type="number" name="volume_ml" class="form-control" 
                               min="0" placeholder="Volume dalam ml">
                        <div class="form-text">Minimal 30 ml/jam atau 120 ml per 4 jam</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Protein</label>
                        <select name="protein" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="negative">Negatif (-)</option>
                            <option value="trace">Trace (±)</option>
                            <option value="1+">1+</option>
                            <option value="2+">2+</option>
                            <option value="3+">3+</option>
                            <option value="4+">4+</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Keton/Acetone</label>
                        <select name="acetone" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="negative">Negatif (-)</option>
                            <option value="trace">Trace (±)</option>
                            <option value="1+">1+</option>
                            <option value="2+">2+</option>
                            <option value="3+">3+</option>
                            <option value="4+">4+</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan output urine..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--end::Modal-->
