<form id="form_edit_rencana_kontrol" class="form" action="#">
    <input type="hidden" name="no_surat" value="{{ $no_surat }}">
    <input type="hidden" name="no_sep" value="{{ $no_sep_asal }}">

    <div class="row mb-5">
        <!-- SEP Info -->
        <div class="col-md-6">
            <label class="form-label fw-bold">No Surat Kontrol</label>
            <input type="text" class="form-control form-control-solid" value="{{ $no_surat }}" readonly />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">No SEP Asal</label>
            <input type="text" class="form-control form-control-solid" value="{{ $no_sep_asal }}" readonly />
        </div>
    </div>

    @if ($sep && isset($sep['response']))
        <div class="alert alert-info d-flex align-items-center p-5 mb-5">
            <i class="ki-outline ki-shield-tick fs-2hx text-info me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-info">Info SEP</h4>
                <span>Pasien: {{ $sep['response']['peserta']['nama'] ?? '-' }}
                    ({{ $sep['response']['peserta']['noKartu'] ?? '-' }})</span>
                <span>Poli: {{ $sep['response']['poli'] ?? '-' }} | Diagnosa:
                    {{ $sep['response']['diagnosa'] ?? '-' }}</span>
            </div>
        </div>
    @endif

    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label required fw-bold">Jenis Kontrol</label>
            <select class="form-select form-select-solid" id="rk_edit_jenis" name="jns_kontrol" disabled>
                <!-- Usually cannot change type on update -->
                <option value="2" {{ ($surat['response']['jnsKontrol'] ?? '') == '2' ? 'selected' : '' }}>Rencana
                    Kontrol</option>
                <option value="1" {{ ($surat['response']['jnsKontrol'] ?? '') == '1' ? 'selected' : '' }}>SPRI
                </option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label required fw-bold">Tanggal Rencana Kontrol</label>
            <input type="date" class="form-control form-control-solid" id="rk_edit_tgl" name="tgl_kontrol"
                value="{{ $surat['response']['tglRencanaKontrol'] ?? '' }}" />
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label required fw-bold">Poli Tujuan</label>
            <select class="form-select form-select-solid" id="rk_edit_poli" name="kode_poli" data-control="select2"
                data-dropdown-parent="#modal_rencana_kontrol">
                <option value="">Pilih Poli</option>
                @foreach ($polis as $poli)
                    <option value="{{ $poli->kode ?? $poli->id }}"
                        {{ ($poli->kode ?? $poli->id) == ($surat['response']['poliTujuan'] ?? '') ? 'selected' : '' }}>
                        {{ $poli->poli }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label required fw-bold">Dokter</label>
            <select class="form-select form-select-solid" id="rk_edit_dokter" name="kode_dokter" data-control="select2"
                data-dropdown-parent="#modal_rencana_kontrol">
                <option value="{{ $surat['response']['kodeDokter'] ?? '' }}" selected>
                    {{ $surat['response']['namaDokter'] ?? 'Pilih Dokter' }}</option>
            </select>
            <div class="text-muted fs-7 mt-2" id="rk_edit_kuota_info"></div>
        </div>
    </div>

    <div class="text-center pt-10">
        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_update_rk" onclick="updateRencanaKontrol()">
            <span class="indicator-label">Update Rencana Kontrol</span>
            <span class="indicator-progress">Please wait... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#rk_edit_poli').change(function() {
            checkJadwalEdit();
        });
        $('#rk_edit_tgl').change(function() {
            checkJadwalEdit();
        });

        // Initial check to load doctors for current selection (if needed) or just trust pre-filled
        // But better to reload doctors to ensure validity and quota
        checkJadwalEdit(true);
    });

    function checkJadwalEdit(initial = false) {
        let tgl = $('#rk_edit_tgl').val();
        let poli = $('#rk_edit_poli').val();
        let jenis = $('#rk_edit_jenis').val();
        let currentDokter = "{{ $surat['response']['kodeDokter'] ?? '' }}";

        if (tgl && poli) {
            if (!initial) $('#rk_edit_dokter').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('vclaim.rencana-kontrol.check-schedule') }}",
                type: "GET",
                data: {
                    tgl: tgl,
                    kd_poli: poli,
                    jns_kontrol: jenis
                },
                success: function(response) {
                    let options = '<option value="">Pilih Dokter</option>';
                    if (response.response && response.response.list) {
                        response.response.list.forEach(function(dokter) {
                            let selected = (dokter.kodeDokter == currentDokter) ? 'selected' : '';
                            options +=
                                `<option value="${dokter.kodeDokter}" ${selected}>${dokter.namaDokter} (${dokter.jadwalPraktek}) - Kuota: ${dokter.kapasitas}</option>`;
                        });
                        $('#rk_edit_dokter').html(options);
                        $('#rk_edit_kuota_info').text('Jadwal ditemukan');
                    } else {
                        $('#rk_edit_dokter').html('<option value="">Tidak ada jadwal</option>');
                        $('#rk_edit_kuota_info').text(response.metaData ? response.metaData.message :
                            'Jadwal tidak ditemukan');
                    }
                },
                error: function() {
                    $('#rk_edit_dokter').html('<option value="">Error memuat jadwal</option>');
                }
            });
        }
    }

    function updateRencanaKontrol() {
        let data = $('#form_edit_rencana_kontrol').serializeArray();
        let tgl = $('#rk_edit_tgl').val();
        let dokter = $('#rk_edit_dokter').val();
        let poli = $('#rk_edit_poli').val();

        if (!tgl || !dokter || !poli) {
            Swal.fire('Warning', 'Lengkapi semua data', 'warning');
            return;
        }

        // Show loading
        let btn = $('#btn_update_rk');
        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('vclaim.rencana-kontrol.update') }}",
            type: "POST", // Method spoofing usually or just POST if route is PUT supported via _method
            data: $.param(data) + '&_method=PUT&_token={{ csrf_token() }}',
            success: function(response) {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);

                if (response.metaData && response.metaData.code == 200) {
                    Swal.fire('Berhasil', 'Data berhasil diperbarui', 'success')
                        .then(() => {
                            $('#modal_rencana_kontrol').modal('hide');
                            reloadTableHistory();
                        });
                } else {
                    Swal.fire('Gagal', response.metaData ? response.metaData.message :
                        'Terjadi kesalahan sistem', 'error');
                }
            },
            error: function(xhr) {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        });
    }
</script>
