<!--begin::Modal - Contraction-->
<div class="modal fade" id="contractionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h3 class="modal-title text-white">
                    <i class="fas fa-wave-square me-2"></i> Input Kontraksi Uterus
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formContraction">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Observasi</label>
                        <input type="datetime-local" name="observation_time" class="form-control" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Jumlah Kontraksi per 10 Menit</label>
                        <input type="number" name="contractions_per_10min" class="form-control" 
                               min="0" max="10" required placeholder="0-10">
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Durasi Kontraksi (detik)</label>
                        <input type="number" name="duration_seconds" class="form-control" 
                               min="0" max="120" placeholder="20-40 detik">
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Intensitas</label>
                        <select name="intensity" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="weak">Lemah</option>
                            <option value="moderate">Sedang</option>
                            <option value="strong">Kuat</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan observasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--end::Modal-->
