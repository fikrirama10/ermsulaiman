<div class="modal fade" id="modal-sbar" tabindex="-1" aria-labelledby="sbarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="sbarModalLabel">
                    <i class="ki-duotone ki-notepad fs-1 text-primary me-2">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    SBAR
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="frm-data" action="{{ route('detail-rekap-medis.post_sbar', $rawat->id) }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label required">Situation</label>
                        <textarea name="situation" class="form-control" required rows="4" placeholder="Masukkan situasi pasien..."></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Background</label>
                        <textarea name="background" class="form-control" required rows="4" placeholder="Masukkan latar belakang..."></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Assessment</label>
                        <textarea name="assesment" class="form-control" required rows="4" placeholder="Masukkan assessment..."></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Recommendation</label>
                        <textarea name="recomendation" class="form-control" required rows="4" placeholder="Masukkan rekomendasi..."></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Instruksi / Anjuran</label>
                        <textarea name="intruksi" class="form-control" required rows="4" placeholder="Masukkan instruksi atau anjuran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
