<div id="prb">
    <div class="alert alert-primary d-flex align-items-center mb-5">
        <i class="fas fa-sync-alt fs-2 me-3"></i>
        <div>
            <strong>Program Rujuk Balik (PRB)</strong><br>
            <small>Pasien BPJS dengan penyakit kronis yang dapat dikembalikan ke Faskes Tingkat I untuk pengobatan rutin</small>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-day text-primary me-1"></i>
                Tanggal Kunjungan Berikutnya
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_kontrol'
                   id='tgl_kontrol_prb'
                   min="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Jadwal kontrol di Faskes Tingkat I
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-12">
            <label class="form-label fw-bold">
                <i class="fas fa-file-medical text-primary me-1"></i>
                Alasan Belum Dapat Dikembalikan ke Faskes Perujuk
                <span class="text-danger">*</span>
            </label>
            <textarea class="form-control form-control-solid"
                      name='alasan'
                      id='alasan'
                      rows="4"
                      required
                      placeholder="Jelaskan mengapa pasien belum dapat dikembalikan ke faskes tingkat I (mis: kondisi belum stabil, butuh pemeriksaan lanjutan, dll)"></textarea>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Jelaskan kondisi medis atau alasan klinis yang relevan
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-12">
            <label class="form-label fw-bold">
                <i class="fas fa-tasks text-primary me-1"></i>
                Rencana Tindak Lanjut Pada Kunjungan Selanjutnya
                <span class="text-danger">*</span>
            </label>
            <textarea class="form-control form-control-solid"
                      name='rencana_selanjutnya'
                      id='rencana_selanjutnya'
                      rows="4"
                      required
                      placeholder="Jelaskan rencana pemeriksaan, terapi, atau tindakan yang akan dilakukan pada kunjungan berikutnya"></textarea>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Tuliskan rencana perawatan dan target yang ingin dicapai
            </div>
        </div>
    </div>
</div>
