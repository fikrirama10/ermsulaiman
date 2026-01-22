<div id="rawat">
    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label fw-bold">
                <i class="fas fa-user-md text-primary me-1"></i>
                Dokter Penanggung Jawab Pelayanan (DPJP)
                <span class="text-danger">*</span>
            </label>
            <select name="iddokter"
                    data-control="select2"
                    data-placeholder="Pilih dokter DPJP..."
                    class="form-select form-select-solid"
                    id="iddokter"
                    required>
                <option value=""></option>
                @foreach ($dokter as $d)
                    <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                @endforeach
            </select>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Pilih dokter yang akan menangani rawat inap
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">
                <i class="fas fa-calendar-plus text-primary me-1"></i>
                Tanggal Rencana Rawat Inap
                <span class="text-danger">*</span>
            </label>
            <input type="date"
                   class="form-control form-control-solid"
                   name='tgl_rawat'
                   id='tgl_rawat'
                   min="{{ date('Y-m-d') }}"
                   required>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Tanggal mulai rawat inap
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="form-check form-check-custom form-check-solid mb-3">
                <input type="hidden" name="operasi" value="0">
                <input class="form-check-input"
                       type="checkbox"
                       value="1"
                       name="operasi"
                       id="checkbox_operasi" />
                <label class="form-check-label fw-bold" for="checkbox_operasi">
                    <i class="fas fa-procedures text-danger me-1"></i>
                    Memerlukan Tindakan Operasi
                </label>
            </div>
        </div>
    </div>
    <div class="row mb-5" id="operasi_detail" style="display: none;">
        <div class="col-md-12">
            <label class="form-label fw-bold">
                <i class="fas fa-notes-medical text-primary me-1"></i>
                Detail Tindakan Operasi
                <span class="text-danger">*</span>
            </label>
            <textarea name="value_operasi"
                      id="value_operasi"
                      class="form-control form-control-solid"
                      rows="3"
                      placeholder="Jelaskan jenis operasi atau tindakan yang akan dilakukan..."></textarea>
            <div class="form-text text-muted">
                <i class="fas fa-info-circle"></i> Sebutkan jenis operasi, prosedur, dan catatan penting lainnya
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#checkbox_operasi').change(function() {
        if($(this).is(':checked')) {
            $('#operasi_detail').slideDown();
            $('#value_operasi').prop('required', true);
        } else {
            $('#operasi_detail').slideUp();
            $('#value_operasi').prop('required', false).val('');
        }
    });
});
</script>
