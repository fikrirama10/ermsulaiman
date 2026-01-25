<!--begin::Modal - Progress-->
<div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white">
                    <i class="fas fa-heartbeat me-2"></i> Input Kemajuan Persalinan
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProgress">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Observasi</label>
                        <input type="datetime-local" name="observation_time" class="form-control" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Pembukaan Serviks (cm)</label>
                        <input type="number" name="cervical_dilatation" class="form-control"
                            min="0" max="10" step="0.5" required placeholder="0-10">
                        <div class="form-text">Pembukaan serviks dalam sentimeter (0-10)</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Penurunan Kepala Janin</label>
                        <select name="fetal_head_descent" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="5/5">5/5 - Di atas PAP</option>
                            <option value="4/5">4/5 - Masuk PAP</option>
                            <option value="3/5">3/5 - Di hodge I-II</option>
                            <option value="2/5">2/5 - Di hodge II-III</option>
                            <option value="1/5">1/5 - Di hodge III+</option>
                            <option value="0/5">0/5 - Di perineum</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Molding (Molase)</label>
                        <select name="molding" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="0">0 - Tulang terpisah</option>
                            <option value="1">1 - Tulang bersentuhan</option>
                            <option value="2">2 - Tulang tumpang tindih (masih dapat dipisahkan)</option>
                            <option value="3">3 - Tulang tumpang tindih (tidak dapat dipisahkan)</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Posisi Janin</label>
                        <input type="text" name="position" class="form-control"
                            placeholder="LOA, ROA, LOP, ROP, dll" maxlength="10">
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Catatan observasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--end::Modal-->