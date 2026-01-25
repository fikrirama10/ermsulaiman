<!--begin::Modal - Fetal Monitoring-->
<div class="modal fade" id="fetalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h3 class="modal-title text-white">
                    <i class="fas fa-baby me-2"></i> Monitoring Janin
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formFetal">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Observasi</label>
                        <input type="datetime-local" name="observation_time" class="form-control" required>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">DJJ (Denyut Jantung Janin)</label>
                        <input type="number" name="fetal_heart_rate" class="form-control" 
                               min="60" max="220" required placeholder="120-160 bpm (normal)">
                        <div class="form-text">Normal: 120-160 bpm</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Warna Air Ketuban</label>
                        <select name="amniotic_fluid_color" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="intact">U - Utuh (belum pecah)</option>
                            <option value="clear">J - Jernih</option>
                            <option value="meconium">M - Mekonium (kehijauan)</option>
                            <option value="blood">D - Darah</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan kondisi janin..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--end::Modal-->
