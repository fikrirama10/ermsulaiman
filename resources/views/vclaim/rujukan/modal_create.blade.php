<form id="form_create_rujukan">
    @csrf
    <input type="hidden" name="no_sep" value="{{ $no_sep }}">
    <input type="hidden" name="no_kartu" value="{{ $no_kartu }}">

    <!-- Row 1: Dates & Type -->
    <div class="row mb-5">
        <div class="col-md-4">
            <label class="form-label required">Tgl Rujukan</label>
            <input type="date" class="form-control form-control-solid" placeholder="Pilih Tanggal" id="tgl_rujukan"
                name="tgl_rujukan" value="{{ date('Y-m-d') }}" />
        </div>
        <div class="col-md-4">
            <label class="form-label required">Tgl Rencana Kunjungan</label>
            <input type="date" class="form-control form-control-solid" placeholder="Pilih Tanggal" id="tgl_rencana"
                name="tgl_rencana" value="{{ date('Y-m-d') }}" />
        </div>
        <div class="col-md-4">
            <label class="form-label required">Tipe Rujukan</label>
            <select class="form-select form-select-solid" name="tipe_rujukan">
                <option value="0">0. Penuh</option>
                <option value="1">1. Partial</option>
                <option value="2">2. Rujuk Balik</option>
            </select>
        </div>
    </div>

    <!-- Row 2: Destination -->
    <div class="separator my-5"></div>
    <h4 class="mb-5">Tujuan Rujukan</h4>

    <div class="row mb-5">
        <div class="col-md-12 mb-5">
            <label class="form-label required">Dirujuk Ke (RS/Faskes)</label>
            <select class="form-select form-select-solid" id="ppk_dirujuk" name="ppk_dirujuk"
                data-placeholder="Ketik Nama RS Tujuan...">
                <option></option>
            </select>
        </div>

        <div class="col-md-6 mb-5">
            <label class="form-label required">Poliklinik</label>
            <select class="form-select form-select-solid" id="poli_rujukan" name="poli_rujukan"
                data-placeholder="Pilih Poli..." disabled>
                <option></option>
            </select>
        </div>
        <div class="col-md-6 mb-5">
            <label class="form-label">Jenis Pelayanan</label>
            <select class="form-select form-select-solid" name="jns_pelayanan">
                <option value="2" selected>Rawat Jalan</option>
                <option value="1">Rawat Inap</option>
            </select>
        </div>
    </div>

    <!-- Row 3: Diagnosis & Notes -->
    <div class="separator my-5"></div>
    <div class="row mb-5">
        <div class="col-md-12 mb-3">
            <label class="form-label required">Diagnosa Rujukan (ICD-10)</label>
            <select class="form-select form-select-solid" id="diag_rujukan" name="diag_rujukan"
                data-placeholder="Ketik Kode/Nama Diagnosa">
                <option></option>
            </select>
        </div>
        <div class="col-md-12">
            <label class="form-label required">Catatan</label>
            <textarea class="form-control form-control-solid" name="catatan" rows="2"></textarea>
        </div>
    </div>

    <div class="modal-footer p-0 pt-5">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary" id="btn_submit_rujukan">
            <span class="indicator-label">Simpan Rujukan</span>
            <span class="indicator-progress">Loading... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script>
    // Diagnosa Search
    $('#diag_rujukan').select2({
        dropdownParent: $('#modal_rujukan'),
        ajax: {
            url: "{{ route('vclaim.rujukan.cari-diagnosa') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results // Controller returns ['results' => ...]
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    // RS Search (Faskes)
    $('#ppk_dirujuk').select2({
        dropdownParent: $('#modal_rujukan'),
        ajax: {
            url: "{{ route('vclaim.rujukan.cari-rs') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    // On RS Change -> Load Poli
    $('#ppk_dirujuk').on('select2:select', function(e) {
        var ppk = e.params.data.id;
        var tgl = $('#tgl_rencana').val();

        $('#poli_rujukan').val(null).trigger('change').prop('disabled', true);

        if (!tgl) {
            Swal.fire('Error', 'Pilih Tanggal Rencana Kunjungan terlebih dahulu', 'error');
            return;
        }

        $.ajax({
            url: "{{ route('vclaim.rujukan.cari-poli') }}",
            data: {
                ppk_rujukan: ppk,
                tgl_rujukan: tgl
            },
            success: function(response) {
                $('#poli_rujukan').empty().append('<option></option>'); // Clear

                if (response.error) {
                    Swal.fire('Info', response.error, 'warning');
                    return;
                }

                // If response is straight array of objects
                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(function(item) {
                        var newOption = new Option(item.text, item.id, false, false);
                        $('#poli_rujukan').append(newOption);
                    });
                    $('#poli_rujukan').prop('disabled', false);
                } else {
                    Swal.fire('Info', 'Tidak ada poli tersedia untuk RS ini pada tanggal tersebut',
                        'warning');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat list poli', 'error');
            }
        });
    });

    // Note: Doctor is NOT standard in BPJS Create Rujukan V2.0. 
    // They only ask for Poli Rujukan. The assignment of doctor happens at the destination hospital.
    // So we stop at Poli selection.

    // Form Submit
    $('#form_create_rujukan').on('submit', function(e) {
        e.preventDefault();

        var btn = $('#btn_submit_rujukan');
        btn.prop('disabled', true).attr('data-kt-indicator', 'on');

        $.ajax({
            url: "{{ route('vclaim.rujukan.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if (response.metaData && response.metaData.code == 200) {
                    Swal.fire('Berhasil', 'Rujukan Berhasil Dibuat: ' + response.response.rujukan
                            .noRujukan, 'success')
                        .then(function() {
                            $('#modal_rujukan').modal('hide');
                            reloadTableHistory();
                            // Open Print
                            window.open("{{ url('vclaim/rujukan/print') }}/" + response.response
                                .rujukan.noRujukan, '_blank');
                        });
                } else {
                    var msg = response.metaData ? response.metaData.message : (response.error ?
                        response.error : 'Terjadi kesalahan');
                    Swal.fire('Gagal', msg, 'error');
                }
            },
            error: function() {
                Swal.fire('Gagal', 'Terjadi kesalahan server', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).removeAttr('data-kt-indicator');
            }
        });
    });
</script>
