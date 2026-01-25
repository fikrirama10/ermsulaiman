<!--begin::Modal - Maternal Vitals-->
<div class="modal fade" id="vitalsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title text-white">
                    <i class="fas fa-stethoscope me-2"></i> Vital Signs Ibu
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formVitals">
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Waktu Observasi</label>
                        <input type="datetime-local" name="observation_time" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label">Tekanan Darah Sistolik</label>
                            <input type="number" name="blood_pressure_systolic" class="form-control" 
                                   min="50" max="250" placeholder="120">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tekanan Darah Diastolik</label>
                            <input type="number" name="blood_pressure_diastolic" class="form-control" 
                                   min="30" max="150" placeholder="80">
                        </div>
                    </div>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label">Nadi (per menit)</label>
                            <input type="number" name="pulse_rate" class="form-control" 
                                   min="40" max="200" placeholder="60-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Suhu (°C)</label>
                            <input type="number" name="temperature" class="form-control" 
                                   min="35" max="42" step="0.1" placeholder="36.5">
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Respirasi (per menit)</label>
                        <input type="number" name="respiration_rate" class="form-control" 
                               min="10" max="60" placeholder="16-20">
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Catatan vital signs..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

