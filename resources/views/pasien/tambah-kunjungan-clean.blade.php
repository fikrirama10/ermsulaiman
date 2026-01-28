@extends('layouts.index')
@section('css')
    <style>
        .patient-card-header {
            background: linear-gradient(135deg, #009ef7 0%, #006096 100%);
            color: white;
            border-radius: 0.625rem 0.625rem 0 0;
            padding: 1.5rem;
        }

        .patient-avatar {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e4e6ef;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #7e8299;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .info-val {
            font-weight: 500;
            color: #181c32;
            text-align: right;
        }

        /* Form Styling */
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #181c32;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }

        .form-section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: #009ef7;
            margin-right: 10px;
            border-radius: 2px;
        }

        .flatpickr-input[readonly] {
            background-color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-5 mb-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Registrasi Kunjungan</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted"><a href="#"
                                    class="text-muted text-hover-primary">Pasien</a></li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                            <li class="breadcrumb-item text-muted">Tambah Kunjungan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-7">

                    <!-- LEFT COL: Patient Summary -->
                    <div class="col-lg-4">
                        <!-- Patient Info Card -->
                        <div class="card card-flush shadow-sm mb-5">
                            <div class="patient-card-header">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="patient-avatar me-4">
                                        <i class="ki-outline ki-profile-user fs-3x text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold text-white mb-1 fs-5">{{ $pasien->nama_pasien }}</h3>
                                        <div class="d-flex align-items-center text-white text-opacity-75 fs-7">
                                            <i class="ki-outline ki-barcode me-1 text-white"></i> {{ $pasien->no_rm }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-white btn-color-gray-700 btn-active-opacity w-100"
                                        data-bs-toggle="modal" data-bs-target="#general_concent">
                                        <i class="ki-outline ki-file-signature text-gray-700 fs-4 me-1"></i> General Consent
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <!-- Alerts -->
                                @if (isset($cek_finger_print) && isset($cek_finger_print['metaData']['code']) && $cek_finger_print['metaData']['code'] == 200 && isset($cek_finger_print['response']['kode']))
                                    @php
                                        $alertType = ($cek_finger_print['response']['kode'] ?? 0) == 0 ? 'danger' : 'success';
                                        $alertStatus = $cek_finger_print['response']['status'] ?? 'Status tidak tersedia';
                                    @endphp
                                    <div id='alert-finger'
                                        class="alert alert-{{ $alertType }} d-flex align-items-center p-3 mb-4 rounded border-dashed border-{{ $alertType }} d-none">
                                        <i
                                            class="ki-outline ki-fingerprint-scanning fs-2hx text-{{ $alertType }} me-3"></i>
                                        <div class="d-flex flex-column">
                                            <h5 class="mb-1 text-{{ $alertType }} fs-7">
                                                Validasi Finger Print</h5>
                                            <span class="fs-8 text-muted">{{ $alertStatus }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <div class="text-muted fw-bold fs-7 text-uppercase mb-2">Identitas & Kontak</div>
                                    <div class="info-row"><span class="info-label">NIK</span><span
                                            class="info-val">{{ $pasien->nik }}</span></div>
                                    <div class="info-row"><span class="info-label">BPJS</span><span
                                            class="info-val">{{ $pasien->no_bpjs }}</span></div>
                                    <div class="info-row"><span class="info-label">No HP</span><span
                                            class="info-val">{{ $pasien->nohp }}</span></div>
                                    <div class="info-row"><span class="info-label">Lahir</span><span
                                            class="info-val">{{ date('d M Y', strtotime($pasien->tgllahir)) }}</span></div>
                                    <div class="info-row"><span class="info-label">Usia</span><span
                                            class="info-val">{{ $pasien->usia_tahun }} th {{ $pasien->usia_bulan }}
                                            bln</span></div>
                                </div>

                                <div class="separator separator-dashed my-4"></div>

                                <div class="mb-4">
                                    <div class="text-muted fw-bold fs-7 text-uppercase mb-2">Keanggotaan</div>
                                    <div class="info-row"><span class="info-label">Kesatuan</span><span
                                            class="info-val">{{ $pasien->kesatuan }}</span></div>
                                    <div class="info-row"><span class="info-label">Pangkat</span><span
                                            class="info-val">{{ $pasien->pangkat }}</span></div>
                                    <div class="info-row"><span class="info-label">NRP</span><span
                                            class="info-val">{{ $pasien->nrp }}</span></div>
                                </div>

                                <div class="separator separator-dashed my-4"></div>

                                <div>
                                    <div class="text-muted fw-bold fs-7 text-uppercase mb-2">Alamat Utama</div>
                                    <p class="text-gray-800 fs-7 mb-0">{{ $pasien->alamat?->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COL: Visit Form -->
                    <div class="col-lg-8">
                        <form action="{{ route('pasien.post-tambah-kunjungan') }}" method="post" id="formInputKunjungan">
                            @csrf
                            <input type="hidden" name="no_rm" id="no_rm" value="{{ $pasien->no_rm }}">

                            <!-- MAIN FORM CARD -->
                            <div class="card shadow-sm mb-5" id="tambah_kunjungan">
                                <div class="card-header pt-6">
                                    <h3 class="card-title fw-bold">Detail Kunjungan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-4">
                                                <label class="required form-label fw-bold">Tanggal Kunjungan</label>
                                                <div class="position-relative d-flex align-items-center">
                                                    <i class="ki-outline ki-calendar-8 position-absolute ms-4 fs-2"></i>
                                                    <input class="form-control form-control-solid ps-12 flatpickr-input"
                                                        name="tglmasuk" placeholder="Pilih Tanggal" id="kt_datepicker_1" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fv-row mb-4">
                                                <label class="required form-label fw-bold">Penanggung Jawab</label>
                                                <select name="penanggung" class="form-select form-select-solid"
                                                    id="penanggung">
                                                    <option value="">-- Pilih Penanggung --</option>
                                                    <option value="2">BPJS Kesehatan</option>
                                                    <option value="1">UMUM / Pribadi</option>
                                                </select>
                                                <div
                                                    class="form-check form-switch form-check-custom form-check-solid mt-3">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="buat_sep" name="buat_sep" />
                                                    <label class="form-check-label fw-semibold text-gray-700"
                                                        for="buat_sep">
                                                        Buat Surat Eligibilitas (SEP)?
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="fv-row mb-4">
                                                <label class="required form-label fw-bold">Cara Datang</label>
                                                <select name="cara_datang" class="form-select form-select-solid"
                                                    id="cara_datang">
                                                    <option value="">-- Pilih Cara Datang --</option>
                                                    <option value="Datang Sendiri">Datang Sendiri</option>
                                                    <option value="Diantar Keluarga">Diantar Keluarga</option>
                                                    <option value="Diantar Polisi">Diantar Polisi</option>
                                                    <option value="Ambulans">Ambulans</option>
                                                    <option value="Rujukan">Rujukan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-4">
                                                <label class="required form-label fw-bold">Unit Pelayanan</label>
                                                <select name="jenis_rawat" class="form-select form-select-solid"
                                                    onchange="updateOptions()" id="jenis_rawat">
                                                    <option value="">-- Pilih Unit --</option>
                                                    <option value="1">Rawat Jalan</option>
                                                    <option value="3">IGD (Gawat Darurat)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fv-row mb-4">
                                                <label class="required form-label fw-bold">Poli Tujuan</label>
                                                <select name="idpoli" class="form-select form-select-solid"
                                                    id="poli">
                                                    <option value="">Pilih Poli</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="fv-row mb-4">
                                        <label class="required form-label fw-bold">Dokter Pemeriksa</label>
                                        <input type="hidden" name='iddokter' id='iddokter'>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light"><i
                                                    class="ki-outline ki-stethoscope fs-2"></i></span>
                                            <input type="text" name='dokter' id='dokter' readonly
                                                class="form-control form-control-solid" placeholder="Pilih Dokter..."
                                                required style="background-color: #f5f8fa; cursor: pointer;">
                                            <button class="btn btn-primary" disabled type="button" id="btn-cari-dokter">
                                                <i class="ki-outline ki-magnifier fs-2"></i> Cari Jadwal Dokter
                                            </button>
                                        </div>
                                        <div class="form-text text-muted">Pastikan tanggal dan poli sudah dipilih untuk
                                            mencari jadwal dokter</div>
                                    </div>

                                    <div class="d-flex align-items-center bg-light-warning rounded p-3 mb-4">
                                        <i class="ki-outline ki-information-2 fs-2x text-warning me-3"></i>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="anggota"
                                                value="1" id="flexCheckDefault" />
                                            <label class="form-check-label fw-bold text-gray-700" for="flexCheckDefault">
                                                Tandai sebagai Pasien Anggota?
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <button type="button" id="button-simpan" class="btn btn-lg btn-primary">
                                        <span class="indicator-label"><i class="ki-outline ki-save-2 fs-2 me-1"></i>
                                            Simpan Pendaftaran</span>
                                        <span class="indicator-progress">Menyimpan... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>

                            <!-- SEP / Rujukan Section (Initially Hidden) -->
                            <div id="card_buat_sep" class="card shadow-sm border-dashed border-primary d-none mt-5">
                                <div class="card-header bg-light-primary">
                                    <h3 class="card-title fw-bold text-primary">Pembuatan SEP BPJS</h3>
                                    <div class="card-toolbar">
                                        <button type="button" id='btn-sep-manual'
                                            class="btn btn-sm btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning">
                                            <i class="ki-outline ki-warning-2 fs-4 me-1"></i> Mode Manual / IGD
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="sep" id='sep'>
                                    <div id="sep_rujukan">
                                        <div class="fv-row mb-5">
                                            <label class="form-label fw-bold">Asal Rujukan</label>
                                            <select name="faskes" class="form-select form-select-solid" id="faskes">
                                                <option value="">-- Pilih Asal Rujukan --</option>
                                                <option value="1">Faskes Tingkat 1 (Puskesmas/Klinik)</option>
                                                <option value="2">Faskes Tingkat 2 (RS Lain)</option>
                                            </select>
                                        </div>
                                        <div id="list_data_rujukan" class="mb-5"></div>
                                        <div id="insert_rujukan"></div>
                                    </div>
                                    <div id="insert_sep_manual"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Preserved) -->
    <div class="modal fade" tabindex="-1" id="general_concent">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id='kt_block_ui_4_target_consent'>
                <div class="modal-header">
                    <h3 class="modal-title fw-bold">PERSETUJUAN UMUM (GENERAL CONSENT)</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mh-400px">
                    <p><strong>I. PERSETUJUAN ASUHAN KESEHATAN</strong></p>
                    <p>Saya menyadari bahwa tindakan kedokteran adalah beresiko, meliputi tindakan medis berupa preventif,
                        diagnostik, terapeutik atau rehabilitatif yang dilakukan oleh dokter atau dokter gigi terhadap
                        pasien.</p>
                    <p>Saya menyetujui segala pelayanan medis di Rumah Sakit Lanud Adi Soemarmo sebagaiman sesuai dengan
                        keadaan saya selama mendapatkan pelayanan medis di Rumah Sakit Lanud Adi Soemarmo.</p>
                    <p>Saya dengan ini memberikan persetujuan (kecuali yang membutuhkan persetujuan khusu/tertulis) dengan
                        tidak dapat ditarik kembali kepada Rumah Sakit Lnud Adi Soemarmo dalam memberikan pelayanan medis,
                        pemeriksaan fisik, yang dapat dilakukan oleh dokter atau perawat, dan melakukan prosedur diagnostik,
                        atau terapi dan tatalaksana sesuai pertimbangan dokter yang diperlukan atau disarankan pada
                        pelayanan medis untuk saya. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik rutin,
                        termasuk namun tidak terbatas pada x-ray, pemberian dan atau tindakan kedokteran serta penyuntikan
                        (intramuskular, intravena, danprosedur invasif lainnya) produk farmasi dan obat-obatan, pemasangan
                        alat medis, dan pengambilan darah untuk pemeriksaan laboratorium atau pemeriksaan patologi yang
                        dibutuhkan untuk pelayanan medis saya.</p>
                    <p>Saya mempercayakan pada semua tenaga kesehatan rumah sakit untuk memberikan perawatan, diagnostik dan
                        terapi kepada saya sebagai pasien rawat inap atau rawat jalan atau Instalasi Gawat Darurat (IGD),
                        termasuk semua pemeriksaan penunjang, yang dibutuhkan untuk pengobatan dan tindakan yang diperlukan.
                    </p>
                    <p><strong>II. KEJADIAN TIDAK TERDUGA / DIHARAPKAN</strong></p>
                    <p>Saya mengerti dan menyadari bahwa dalam tindakan kedokteran dapat terjadi kejadian tidak terduga /
                        diharapkan (unanticipated outcame) yang dapat merupakan efek samping dari tindakan kedokteran yang
                        tidak dapat diduga sebelumnya (termasuk antara lain, namun tidak terbatas pada Steven Johnson
                        Syndrome dan Syok Anafilatik).</p>
                    <p>Saya mengerti bahwa hasil asuhan dan pengobatan termasuk kejadian yang tidak terduga /diharapkan akan
                        di beritahukan kepada saya dan keluarga oleh Dokter Penanggung Jawab Pasien (DPJP).</p>
                    <p><strong>SAYA TELAH DIJELASKAN, MEMBACA, MEMAHAMI, dan SEPENUHNYA SETUJU terhadap pernyataan tersebut
                            di atas.</strong></p>
                    <br>
                    <form action="{{ route('pasien.post-form-concent') }}" id="form-consent" method="POST">
                        @csrf
                        <div class="fv-row bg-light p-4 rounded">
                            <label class="form-label fw-bold">Nama Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab"
                                placeholder="Masukkan nama penanggung jawab...">
                            <input type="hidden" value="{{ $pasien->id }}" name="idpasien">
                            <input type="hidden" value="Persetujuan Umum" name="general_consent">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div id="consent" class="w-100"></div>
                    @if ($cek_general_concent)
                        <div
                            class="alert alert-dismissible bg-light-success border border-success border-3 d-flex flex-column flex-sm-row w-100 p-5 mb-0">
                            <i class="ki-outline ki-check-circle fs-2hx text-success me-4"><span
                                    class="path1"></span><span class="path2"></span></i>
                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h5 class="mb-1 text-success">Sudah Disetujui</h5>
                                <span>General Consent sudah ditandatangani oleh pasien/keluarga.</span>
                            </div>
                        </div>
                    @else
                        <button type="button" class="btn btn-primary w-100" id="btn-setuju-consent">Saya Setuju &
                            Simpan</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jadwal Dokter -->
    <div class="modal fade" tabindex="-1" id="modal-dokter">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="kt_block_ui_4_target">
                <div class="modal-header">
                    <h3 class="modal-title">Jadwal Dokter Tersedia</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <div id="modal-hasil"></div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="" id="poli_rujukan">
@endsection

@section('js')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.js"></script>
    <script>
        // --- Original JS Logic Preserved & Optimized ---

        const UIHelper = {
            showLoading: (message = 'Processing...') => {
                $.blockUI({
                    message: `<div class="d-flex align-items-center justify-content-center py-3"><div class="spinner-border text-white me-3"></div> <span class="text-white fs-6 fw-bold">${message}</span></div>`,
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .7,
                        color: '#fff'
                    }
                });
            },
            showElementLoading: (selector, message = 'Processing...') => {
                $(selector).block({
                    message: `<div class="d-flex align-items-center justify-content-center"><div class="spinner-border text-primary me-2"></div> <span>${message}</span></div>`,
                    css: {
                        border: 'none',
                        padding: '10px',
                        backgroundColor: 'rgba(255,255,255,0.8)',
                        opacity: 1,
                        color: '#333'
                    }
                });
            },
            hideLoading: () => $.unblockUI(),
            hideElementLoading: (selector) => $(selector).unblock(),
            setButtonState: (button, isLoading) => {
                const btn = $(button); // Make sure it's jQuery object if utilizing jQuery methods, or raw DOM
                if (isLoading) {
                    btn.attr('data-kt-indicator', 'on');
                    btn.prop('disabled', true);
                } else {
                    btn.removeAttr('data-kt-indicator');
                    btn.prop('disabled', false);
                }
            }
        };

        const AlertHelper = {
            confirmSave: (title = 'Simpan Data', text = 'Pastikan data yang diinput sudah benar.') => {
                return Swal.fire({
                    title,
                    text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                });
            },
            getConsentSuccessHTML: () =>
                `<div class="alert alert-dismissible bg-light-success border border-success border-3 d-flex flex-column flex-sm-row w-100 p-5 mb-0"><i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i><div class="d-flex flex-column pe-0 pe-sm-10"><h5 class="mb-1 text-success">Sudah Disetujui</h5><span>General Consent sudah ditandatangani.</span></div></div>`
        };

        const AjaxHelper = {
            request: (options) => {
                const defaultOptions = {
                    method: 'GET',
                    loadingMessage: 'Loading ...',
                    showLoading: true
                };
                const config = {
                    ...defaultOptions,
                    ...options
                };
                return $.ajax({
                    type: config.method,
                    url: config.url,
                    data: config.data || {},
                    beforeSend: () => {
                        if (config.showLoading) UIHelper.showLoading(config.loadingMessage);
                        if (config.beforeSend) config.beforeSend();
                    },
                    success: (response) => {
                        if (config.showLoading) UIHelper.hideLoading();
                        if (config.success) config.success(response);
                    },
                    error: (xhr, status, error) => {
                        if (config.showLoading) UIHelper.hideLoading();
                        const errorMessage = xhr.responseJSON?.message || error;
                        toastr.error(errorMessage);
                        if (config.error) config.error(xhr, status, error);
                    }
                });
            }
        };

        const FormValidationSetup = {
            createValidator: (formElement, fields) => {
                return FormValidation.formValidation(formElement, {
                    fields,
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                });
            },
            setupConsentValidation: () => {
                const form = document.getElementById('form-consent');
                return FormValidationSetup.createValidator(form, {
                    'penanggung_jawab': {
                        validators: {
                            notEmpty: {
                                message: 'Wajib diisi'
                            }
                        }
                    }
                });
            },
            setupMainFormValidation: () => {
                const form = document.getElementById('formInputKunjungan');
                // Removed 'icdx', 'no_surat', 'txtnmdpjp', 'status_kecelakaan' as they might be dynamic/not initially present
                // Validation logic for dynamic fields should ideally be conditional or attached later, but for now we validate base fields.
                const requiredFields = ['tglmasuk', 'penanggung', 'cara_datang', 'jenis_rawat', 'idpoli', 'dokter'];
                const fields = {};
                requiredFields.forEach(field => {
                    fields[field] = {
                        validators: {
                            notEmpty: {
                                message: 'Wajib diisi'
                            }
                        }
                    };
                });
                return FormValidationSetup.createValidator(form, fields);
            }
        };

        // SEP Wizard Logic (Preserved)
        const SEPWizard = {
            showTujuanKunjungan: () => Swal.fire({
                title: 'Tujuan Kunjungan',
                html: `<select id="firstSelect" class="form-select"><option value="0" disabled selected>-- Pilih Tujuan --</option><option value="1">Prosedur</option><option value="2">Konsul Dokter</option></select>`,
                preConfirm: () => {
                    const val = $('#firstSelect').val();
                    if (!val) Swal.showValidationMessage('Pilih tujuan');
                    return val;
                }
            }),
            showAlasanTidakSelesai: () => Swal.fire({
                title: 'Alasan Belum Selesai',
                html: `<select id="secondSelect" class="form-select"><option value="" disabled selected>-- Pilih Alasan --</option><option value="1">Poli spesialis tidak tersedia</option><option value="2">Jam Poli telah berakhir</option><option value="3">Dokter Spesialis tidak praktek</option><option value="4">Atas Instruksi RS</option><option value="5">Tujuan Kontrol</option></select>`,
                showCancelButton: true,
                preConfirm: () => {
                    const val = $('#secondSelect').val();
                    if (!val) Swal.showValidationMessage('Pilih alasan');
                    return val;
                }
            }),
            showProsedurBerkelanjutan: () => Swal.fire({
                title: 'Prosedur Berkelanjutan',
                html: `<select id="prosedur" class="form-control"><option value="">-- Pilih Prosedur --</option><option value="1">Radioterapi</option><option value="2">Kemoterapi</option><option value="3">Rehabilitasi Medik</option><option value="4">Rehabilitasi Psikososial</option><option value="5">Transfusi Darah</option><option value="6">Pelayanan Gigi</option><option value="12">HEMODIALISA</option></select>`,
                showCancelButton: true,
                preConfirm: () => {
                    const val = $('#prosedur').val();
                    if (!val) Swal.showValidationMessage('Pilih prosedur');
                    return val;
                }
            }),
            showProsedurTidakBerkelanjutan: () => Swal.fire({
                title: 'Prosedur Tidak Berkelanjutan',
                html: `<select id="nonprosedur" class="form-control"><option value="">-- Pilih Prosedur --</option><option value="7">Laboratorium</option><option value="8">USG</option><option value="9">Farmasi</option><option value="10">Lain-Lain</option><option value="11">MRI</option></select>`,
                showCancelButton: true,
                preConfirm: () => {
                    const val = $('#nonprosedur').val();
                    if (!val) Swal.showValidationMessage('Pilih prosedur');
                    return val;
                }
            }),
            showBerkelanjutanChoice: (onYes, onNo) => Swal.fire({
                title: 'Prosedur berkelanjutan?',
                html: `<button id="yesButton" class="btn btn-primary me-2">Ya</button><button id="noButton" class="btn btn-light">Tidak</button>`,
                showConfirmButton: false,
                didRender: () => {
                    $('#yesButton').click(() => {
                        Swal.close();
                        onYes();
                    });
                    $('#noButton').click(() => {
                        Swal.close();
                        onNo();
                    });
                }
            })
        };

        const FormSubmissionHandler = {
            submitFormData: (selections = {}) => {
                const form = document.getElementById('formInputKunjungan');
                const submitButton = document.getElementById('button-simpan');
                AlertHelper.confirmSave().then((result) => {
                    if (!result.isConfirmed) return;
                    UIHelper.setButtonState(submitButton, true);
                    let formData = $(form).serialize();
                    if (Object.keys(selections).length > 0) Object.entries(selections).forEach(([key,
                        value
                    ]) => {
                        formData += `&${key}=${value || ''}`;
                    });

                    AjaxHelper.request({
                        method: form.getAttribute('method'),
                        url: form.getAttribute('action'),
                        data: formData,
                        loadingMessage: 'Menyimpan Data ...',
                        success: (response) => {
                            UIHelper.setButtonState(submitButton, false);
                            if (response.status === 'failed') {
                                toastr.error(response.message);
                                return;
                            }
                            if (response.error) {
                                toastr.error(response.error);
                                return;
                            }
                            toastr.success(response.message);
                            if (response.redirect_url) {
                                setTimeout(() => {
                                    window.location.href = response.redirect_url;
                                }, 1000); // Optional delay for user to see success message
                            }
                        },
                        error: () => UIHelper.setButtonState(submitButton, false)
                    });
                });
            },
            handleRujukanFlow: (noSurat) => {
                const selections = {
                    tujuanKunjungan: '',
                    alasanTidakSelesai: '',
                    prosedur: '',
                    prosedurBerkelanjutan: '',
                    prosedurTidakBerkelanjutan: ''
                };
                if (noSurat) {
                    SEPWizard.showTujuanKunjungan().then((result) => {
                        if (!result.isConfirmed) return;
                        selections.tujuanKunjungan = result.value;
                        if (result.value == 2) {
                            SEPWizard.showAlasanTidakSelesai().then((result2) => {
                                if (result2.isConfirmed) {
                                    selections.alasanTidakSelesai = result2.value;
                                    FormSubmissionHandler.submitFormData(selections);
                                }
                            });
                        } else {
                            SEPWizard.showBerkelanjutanChoice(
                                () => {
                                    selections.prosedur = 1;
                                    SEPWizard.showProsedurBerkelanjutan().then((res) => {
                                        if (res.isConfirmed) {
                                            selections.prosedurBerkelanjutan = res.value;
                                            FormSubmissionHandler.submitFormData(selections);
                                        }
                                    });
                                },
                                () => {
                                    selections.prosedur = 0;
                                    SEPWizard.showProsedurTidakBerkelanjutan().then((res) => {
                                        if (res.isConfirmed) {
                                            selections.prosedurTidakBerkelanjutan = res.value;
                                            FormSubmissionHandler.submitFormData(selections);
                                        }
                                    });
                                }
                            );
                        }
                    });
                } else {
                    SEPWizard.showAlasanTidakSelesai().then((result) => {
                        if (result.isConfirmed) {
                            selections.tujuanKunjungan = 0;
                            selections.alasanTidakSelesai = result.value;
                            FormSubmissionHandler.submitFormData(selections);
                        }
                    });
                }
            }
        };

        const EventHandlers = {
            setupConsentSubmit: (validator) => {
                $('#btn-setuju-consent').click((e) => {
                    e.preventDefault();
                    validator.validate().then((status) => {
                        if (status !== 'Valid') return;
                        AlertHelper.confirmSave('Simpan Consent').then((result) => {
                            if (!result.isConfirmed) return;
                            AjaxHelper.request({
                                method: 'POST',
                                url: $('#form-consent').attr('action'),
                                data: $('#form-consent').serialize(),
                                showLoading: false,
                                beforeSend: () => UIHelper.showElementLoading(
                                    '#kt_block_ui_4_target_consent'),
                                success: (response) => {
                                    UIHelper.hideElementLoading(
                                        '#kt_block_ui_4_target_consent');
                                    if (response.status === 'success') {
                                        $('#btn-setuju-consent').addClass(
                                            'd-none');
                                        $('#general_concent').modal('hide');
                                        $('#consent').html(AlertHelper
                                            .getConsentSuccessHTML());
                                    }
                                    toastr.success(response.message);
                                },
                                error: () => UIHelper.hideElementLoading(
                                    '#kt_block_ui_4_target_consent')
                            });
                        });
                    });
                });
            },
            setupMainFormSubmit: (validator) => {
                $('#button-simpan').click((e) => {
                    e.preventDefault();
                    validator.validate().then((status) => {
                        if (status !== 'Valid') {
                            toastr.warning('Periksa kembali kelengkapan data form.');
                            return;
                        }
                        const noSurat = $('#no_surat').val();
                        const isRujukan = $('#is_rujukan')
                            .val(); // Logic note: #is_rujukan element might be dynamically added by SEP form
                        // If SEP section logic adds #is_rujukan, we use it. Otherwise fallback.
                        if (isRujukan) FormSubmissionHandler.handleRujukanFlow(noSurat);
                        else FormSubmissionHandler.submitFormData();
                    });
                });
            },
            setupSepManual: () => {
                $('#btn-sep-manual').click(function() {
                    const btn = $(this);
                    const isManual = btn.data('mode') === 'manual';

                    if (isManual) {
                        // Switch back to Rujukan Mode
                        $('#sep_rujukan').removeClass('d-none');
                        $('#insert_sep_manual').empty();
                        btn.data('mode', 'rujukan');
                        btn.html('<i class="ki-outline ki-warning-2 fs-4 me-1"></i> Mode Manual / IGD')
                            .removeClass('btn-warning').addClass('btn-outline-warning');

                        // Reset form defaults if needed
                        $('#jenis_rawat').val('');
                        $('#poli').empty().append('<option value="">Pilih Poli</option>');
                        toastr.info('Kembali ke Mode Rujukan');

                    } else {
                        // Switch to Manual Mode
                        $('#sep_rujukan').addClass('d-none');
                        AjaxHelper.request({
                            url: '{{ route('buat-sep-manual') }}',
                            data: {
                                no_rm: '{{ $pasien->no_rm }}'
                            },
                            success: (response) => {
                                if (response.status === 'failed') {
                                    $('#insert_sep_manual').empty();
                                    toastr.error('Gagal memuat form manual');
                                } else {
                                    $('#insert_sep_manual').html(response);
                                    $('#btn-cari-dokter').prop('disabled', false);
                                    $('#jenis_rawat').val(
                                        3
                                    ); // Auto select IGD or let user choose? Assuming IGD based on request context but user said "Manual / IGD"
                                    // Trigger change event to populate poli if needed, or set manually
                                    // $('#jenis_rawat').trigger('change'); 

                                    // For Manual Mode usually we might want to let them select freely, but code snippet set IGD. 
                                    // Let's stick to previous logic of setting IGD but allow flexibility if the manual form allows it.
                                    // The previous code hardcoded IGD. 
                                    $('#poli').html('<option selected value="1">UGD</option>');
                                    $('#kt_datepicker_1').val("{{ date('Y-m-d') }}");

                                    btn.data('mode', 'manual');
                                    btn.html(
                                        '<i class="ki-outline ki-arrow-left fs-4 me-1"></i> Kembali ke Mode Rujukan'
                                    ).removeClass('btn-outline-warning').addClass(
                                        'btn-warning');
                                    toastr.info('Mode Manual / IGD diaktifkan');
                                }
                            }
                        });
                    }
                });
            },
            setupBuatSep: () => {
                $('#buat_sep').change(function() {
                    const checked = $(this).is(':checked');
                    if (checked) {
                        const penanggung = $('#penanggung').val();
                        if (!penanggung || penanggung == 1) {
                            toastr.error('Penanggung harus BPJS untuk membuat SEP!');
                            $(this).prop('checked', false);
                            return;
                        }
                        $('#card_buat_sep').removeClass('d-none');
                        $('#sep').val(1);
                        $('html, body').animate({
                            scrollTop: $("#card_buat_sep").offset().top - 100
                        }, 500);
                    } else {
                        $('#card_buat_sep').addClass('d-none');
                        $('#list_data_rujukan').empty();
                        $('#sep').val(null);
                    }
                });
            },
            setupPoliChange: () => {
                $('#poli').change(function() {
                    const selectedValue = $(this).val();
                    const poliRujukan = $('#poli_rujukan').val();
                    if (selectedValue > 0) {
                        if (poliRujukan != selectedValue) {
                            $('.rujukan_kontrol').addClass('d-none');
                            $('#no_surat, #txtkddpjp, #txtnmdpjp').val('');
                        } else {
                            $('.rujukan_kontrol').removeClass('d-none');
                        }
                        $('#btn-cari-dokter').prop('disabled', false);
                    } else {
                        $('#btn-cari-dokter').prop('disabled', true);
                    }
                    $('#dokter, #iddokter').val('');
                });
            },
            setupFaskesChange: () => {
                $('#faskes').change(function() {
                    const faskes = $(this).val();
                    if (!faskes) {
                        toastr.error('Pilih faskes');
                        $('#list_data_rujukan').empty();
                        return;
                    }
                    AjaxHelper.request({
                        url: '{{ route('get-rujukan-faskes') }}',
                        data: {
                            faskes: faskes,
                            no_bpjs: "{{ $pasien->no_bpjs }}"
                        },
                        success: (response) => {
                            if (response.status === 'failed') {
                                toastr.error(response.message);
                                $('#list_data_rujukan').empty();
                            } else {
                                $('#list_data_rujukan').html(response.response);
                                toastr.success(response.message);
                            }
                        }
                    });
                });
            },
            setupCariDokter: () => {
                $('#btn-cari-dokter').click(function() {
                    const btn = $(this);
                    const tgl = $('#kt_datepicker_1').val();
                    const poli = $('#poli').val();
                    const penanggung = $('#penanggung').val();
                    if (!tgl || !penanggung) {
                        toastr.error('Lengkapi Tanggal dan Penanggung!');
                        return;
                    }

                    UIHelper.setButtonState(btn, true);
                    AjaxHelper.request({
                        url: '{{ route('get-jadwal-dokter') }}',
                        data: {
                            poli,
                            tgl,
                            penanggung
                        },
                        showLoading: false,
                        success: (response) => {
                            $('#modal-hasil').html(response);
                            $('#modal-dokter').modal('show');
                            UIHelper.setButtonState(btn, false);
                        },
                        error: () => UIHelper.setButtonState(btn, false)
                    });
                });
            }
        };

        const PoliManager = {
            options: {
                '1': [{
                    value: '',
                    text: 'Pilih Poli / Unit'
                }, {
                    value: '2',
                    text: 'BEDAH'
                }, {
                    value: '3',
                    text: 'GIGI'
                }, {
                    value: '4',
                    text: 'OBGYN'
                }, {
                    value: '5',
                    text: 'PENYAKIT DALAM'
                }, {
                    value: '6',
                    text: 'ANAK'
                }, {
                    value: '7',
                    text: 'Saraf'
                }, {
                    value: '8',
                    text: 'JIWA'
                }, {
                    value: '9',
                    text: 'PARU'
                }, {
                    value: '10',
                    text: 'ORTOPEDI'
                }, {
                    value: '11',
                    text: 'MATA'
                }, {
                    value: '12',
                    text: 'REHABMEDIK'
                }, {
                    value: '13',
                    text: 'GIGI PRSOSTHODONTI'
                }, {
                    value: '14',
                    text: 'KULIT'
                }],
                '3': [{
                    value: '',
                    text: 'Pilih Poli / Unit'
                }, {
                    value: '1',
                    text: 'UGD'
                }]
            },
            updateOptions: () => {
                const jenisRawat = $('#jenis_rawat').val();
                const poliSelect = $('#poli');
                poliSelect.empty();
                const options = PoliManager.options[jenisRawat] || [];
                options.forEach(opt => {
                    poliSelect.append($('<option>', {
                        value: opt.value,
                        text: opt.text
                    }));
                });
            }
        };

        $(document).ready(function() {
            $("#kt_datepicker_1").flatpickr();
            const consentValidator = FormValidationSetup.setupConsentValidation();
            const mainValidator = FormValidationSetup.setupMainFormValidation();
            EventHandlers.setupConsentSubmit(consentValidator);
            EventHandlers.setupMainFormSubmit(mainValidator);
            EventHandlers.setupSepManual();
            EventHandlers.setupBuatSep();
            EventHandlers.setupPoliChange();
            EventHandlers.setupFaskesChange();
            EventHandlers.setupCariDokter();
            @if ($cek_finger_print['metaData']['code'] == 200)
                $('#alert-finger').removeClass('d-none');
            @endif
        });
        window.updateOptions = PoliManager.updateOptions;
    </script>
@endsection
