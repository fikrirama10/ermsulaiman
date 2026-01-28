<form id="form_rencana_kontrol" class="form" action="#">
    <div class="row mb-5">
        <!-- SEP Info -->
        <div class="col-md-6">
            <label class="form-label fw-bold">No SEP</label>
            <input type="text" class="form-control form-control-solid" value="{{ $no_sep }}" readonly />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">No Kartu</label>
            <input type="text" class="form-control form-control-solid" value="{{ $no_kartu }}" readonly />
        </div>
    </div>

    @if ($sep && isset($sep['response']))
        <div class="alert alert-info d-flex align-items-center p-5 mb-5">
            <i class="ki-outline ki-shield-tick fs-2hx text-info me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-info">Info SEP</h4>
                <span>Poli: {{ $sep['response']['poli'] ?? '-' }} | Diagnosa:
                    {{ $sep['response']['diagnosa'] ?? '-' }}</span>
            </div>
        </div>
    @endif

    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label required fw-bold">Jenis Kontrol</label>
            <select class="form-select form-select-solid" id="rk_jenis" name="jns_kontrol">
                <option value="2" selected>Rencana Kontrol</option>
                <option value="1">SPRI</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label required fw-bold">Tanggal Rencana Kontrol</label>
            <input type="date" class="form-control form-control-solid" id="rk_tgl" name="tgl_kontrol"
                value="{{ date('Y-m-d', strtotime('+1 day')) }}" />
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6">
            <label class="form-label required fw-bold">Poli Tujuan</label>
            <select class="form-select form-select-solid" id="rk_poli" name="kode_poli" data-control="select2"
                data-dropdown-parent="#modal_rencana_kontrol">
                <option value="">Pilih Poli</option>
                @foreach ($polis as $poli)
                    {{-- Assuming Poli model has 'kode' which matches BPJS --}}
                    <option value="{{ $poli->kode ?? $poli->id }}">{{ $poli->poli }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label required fw-bold">Dokter</label>
            <select class="form-select form-select-solid" id="rk_dokter" name="kode_dokter" data-control="select2"
                data-dropdown-parent="#modal_rencana_kontrol">
                <option value="">Pilih Dokter</option>
            </select>
            <div class="text-muted fs-7 mt-2" id="rk_kuota_info"></div>
        </div>
    </div>

    <div class="text-center pt-10">
        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_submit_rk" onclick="submitRencanaKontrol()">
            <span class="indicator-label">Buat Rencana Kontrol</span>
            <span class="indicator-progress">Please wait... <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#rk_poli, #rk_jenis').change(function() {
            checkJadwal();
        });
        $('#rk_tgl').change(function() {
            checkJadwal();
        });
    });

    function checkJadwal() {
        let tgl = $('#rk_tgl').val();
        let poli = $('#rk_poli').val();
        let jenis = $('#rk_jenis').val();

        if (tgl && poli) {
            $('#rk_dokter').html('<option value="">Loading...</option>');

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
                            options +=
                                `<option value="${dokter.kodeDokter}">${dokter.namaDokter} (${dokter.jadwalPraktek}) - Kuota: ${dokter.kapasitas}</option>`;
                        });
                        $('#rk_dokter').html(options);
                        $('#rk_kuota_info').text('Jadwal ditemukan');
                    } else {
                        $('#rk_dokter').html('<option value="">Tidak ada jadwal</option>');
                        $('#rk_kuota_info').text(response.metaData ? response.metaData.message :
                            'Jadwal tidak ditemukan');
                    }
                },
                error: function() {
                    $('#rk_dokter').html('<option value="">Error memuat jadwal</option>');
                }
            });
        }
    }

    function submitRencanaKontrol() {
        let data = $('#form_rencana_kontrol').serializeArray();
        let tgl = $('#rk_tgl').val();
        let dokter = $('#rk_dokter').val();
        let poli = $('#rk_poli').val();
        let no_sep = "{{ $no_sep }}";

        if (!tgl || !dokter || !poli) {
            Swal.fire('Warning', 'Lengkapi semua data', 'warning');
            return;
        }

        // Add additional data needed
        data.push({
            name: 'no_sep',
            value: no_sep
        });

        // Show loading
        let btn = $('#btn_submit_rk');
        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('vclaim.rencana-kontrol.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                no_sep: no_sep,
                tgl_kontrol: tgl,
                kode_dokter: dokter,
                kode_poli: poli,
                jns_kontrol: $('#rk_jenis').val()
            },
            success: function(response) {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);

                if (response.metaData && response.metaData.code == 200) {
                    Swal.fire('Berhasil', 'Surat Rencana Kontrol: ' + response.response.noSuratKontrol,
                            'success')
                        .then(() => {
                            $('#modal_rencana_kontrol').modal('hide');
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
