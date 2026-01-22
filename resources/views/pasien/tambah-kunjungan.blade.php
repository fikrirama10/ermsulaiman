@extends('layouts.index')
@section('css')
    <style>
        #tambah_kunjungan {
            transition: all 0.2s ease;
            /* Tambahkan transisi untuk semua properti */
        }

        .swal2-popup {
            width: 600px !important;
            /* Adjust the width as needed */
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Rekammedis</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Menu</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Tambah Kunjungan Pasien BPJS</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-lg-3">

                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <div class="row g-5">

                    <div class="col-md-4">
                        <div class="card  mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Data Pasien</h4>

                                <div class="card-toolbar">
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#general_concent">General Consent</button>
                                    {{-- <button class="btn btn-warning btn-sm ">Cek Finger Print</button> --}}
                                </div>
                            </div>
                            <div class="card-body p-9">
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    @if ($cek_finger_print['metaData']['code'] == 200 )
                                    <div id='alert-finger' class="col-md-12 d-none">
                                        <div
                                            class="alert alert-{{ $cek_finger_print['response']['kode'] == 0 ? 'danger' : 'success' }} d-flex align-items-center p-5 mb-10">
                                            <i
                                                class="ki-duotone ki-fingerprint-scanning   fs-2hx text-{{ $cek_finger_print['response']['kode'] == 0 ? 'danger' : 'success' }} me-4">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                            <div class="d-flex flex-column">
                                                <h4
                                                    class="mb-1 text-{{ $cek_finger_print['response']['kode'] == 0 ? 'danger' : 'success' }}">
                                                    Validasi Finger Print</h4>
                                                <span>{{ $cek_finger_print['response']['status'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Nama Lengkap</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->nama_pasien }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Input group-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">No. Rekammedis</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-semibold text-gray-800 fs-6">{{ $pasien->no_rm }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">NIK</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-semibold text-gray-800 fs-6">{{ $pasien->nik }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">No. BPJS</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <span class="fw-semibold text-gray-800 fs-6">{{ $pasien->no_bpjs }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">
                                        Kontak Pasien

                                        <span class="ms-1" data-bs-toggle="tooltip"
                                            aria-label="Phone number must be active"
                                            data-bs-original-title="Phone number must be active" data-kt-initialized="1">
                                            <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span></i> </span>
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <span class="fw-bold fs-6 text-gray-800 me-2">{{ $pasien->nohp }}</span>
                                        {{-- <span class="badge badge-success">Verified</span> --}}
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span
                                            class="fw-bold fs-6 text-gray-800">{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'perempuan' }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">
                                        Tanggal Lahir
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <span
                                            class="fw-bold fs-6 me-3 text-gray-800">{{ date('d F Y', strtotime($pasien->tgllahir)) }}
                                        </span>
                                        <span class="badge badge-success  ">{{ $pasien->usia_tahun }} th
                                            {{ $pasien->usia_bulan }}bln {{ $pasien->usia_hari }}hr</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Kepercayaan</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->agama->agama }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Data Alamat</h4>

                                <div class="card-toolbar">

                                </div>
                            </div>
                            <div class="card-body p-9">
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Alamat Utama</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->alamat?->alamat }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Data Anggota</h4>

                                <div class="card-toolbar">

                                </div>
                            </div>
                            <div class="card-body p-9">
                                <!--begin::Row-->
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Kesatuan</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->kesatuan }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">Pangkat</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->pangkat }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-7">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 fw-semibold text-muted">NRP</label>
                                    <!--end::Label-->

                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <span class="fw-bold fs-6 text-gray-800">{{ $pasien->nrp }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('pasien.post-tambah-kunjungan') }}" method="post"
                            id="formInputKunjungan">
                            <div class="row g-5">
                                @csrf
                                <input type="hidden" name="no_rm" id="no_rm" value="{{ $pasien->no_rm }}">
                                <div id="tambah_kunjungan" class="col-md-12">
                                    <div class="card card-stretch">
                                        <div class="card-header">
                                            <h3 class="card-title">Tambah Kunjungan</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2 fv-row">
                                                <label for="kt_datepicker_1"
                                                    class="required form-label">Tgl.Kunjungan</label>
                                                <input class="form-control form-control-sm" name="tglmasuk"
                                                    placeholder="Pick a date" id="kt_datepicker_1" />
                                            </div>
                                            <div class="mb-2 fv-row">
                                                <label for="penanggung" class="required form-label">Penanggung</label>
                                                <div class="input-group">
                                                    <select name="penanggung" class="form-select form-select-sm me-3"
                                                        id="penanggung">
                                                        <option value="">-- Pilih Penanggung --</option>
                                                        <option value="2">BPJS</option>
                                                        <option value="1">UMUM</option>
                                                    </select>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="buat_sep" name="buat_sep" />
                                                        <label class="form-check-label" for="buat_sep">
                                                            Buat SEP
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2 fv-row">
                                                <label for="jenis_rawat" class="required form-label">Jenis Rawat</label>
                                                <select name="jenis_rawat" class="form-select form-select-sm"
                                                    onchange="updateOptions()" id="jenis_rawat">
                                                    <option value="">-- Pilih Poli --</option>
                                                    <option value="1">Rawat Jalan</option>
                                                    <option value="3">UGD</option>
                                                </select>
                                            </div>
                                            <div class="mb-2 fv-row">
                                                <label for="poli" class="required form-label">Poli</label>
                                                <select name="idpoli" class="form-select form-select-sm" id="poli">
                                                </select>
                                            </div>
                                            <div class="mb-3 fv-row">
                                                <label for="dokter" class="required form-label">Dokter</label>
                                                <input type="hidden" name='iddokter' id='iddokter'>
                                                <div class="input-group">
                                                    <input type="text" name='dokter' id='dokter' readonly
                                                        class="form-control form-control-sm form-control-solid" required>
                                                    <button class="btn btn-sm btn-success" disabled type="button"
                                                        id="btn-cari-dokter">
                                                        <span class="indicator-label">
                                                            Cari
                                                        </span>
                                                        <span class="indicator-progress">
                                                            Harap Tunggu <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-2 fv-row form-check">
                                                <input class="form-check-input" type="checkbox" name="anggota"
                                                    value="1" id="flexCheckDefault" />
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Anggota ?
                                                </label>
                                            </div>

                                        </div>
                                        <div class="card-footer">
                                            <button type="button" id="button-simpan" class="btn btn-primary">Simpan
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="card_buat_sep" class="col-md-7 d-none">
                                    <div class="card card-stretch">
                                        <div class="card-header">
                                            <h3 class="card-title">SEP</h3>
                                            <div class="card-toolbar">
                                                <button type="button" id='btn-sep-manual'
                                                    class="btn btn-warning">Rujukan Manual/IGD</button>
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="flexCheckDefault" />
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        Buat SEP
                                                    </label>
                                                </div> --}}

                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="sep" id='sep'>
                                            <div id="sep_rujukan">
                                                {{-- 1. Manual
                                                2. Rujukan Pertama
                                                3. Kontrol
                                                4. Post Ranap --}}

                                                <div class="mb-5 fv-row">
                                                    <label for="">Asal Rujukan</label>
                                                    <select name="faskes" class="form-select" id="faskes">
                                                        <option value="">Pilih Asal Rujukan</option>
                                                        <option value="1">Faskes 1</option>
                                                        <option value="2">Faskes 2</option>
                                                    </select>
                                                </div>
                                                <div class="separator separator-dashed my-4"></div>
                                                <div id="list_data_rujukan"></div>
                                                <div id="insert_rujukan"></div>
                                            </div>
                                            <div id="insert_sep_manual"></div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" tabindex="-1" id="general_concent">
        <div class="modal-dialog modal-lg   ">
            <div class="modal-content" id='kt_block_ui_4_target_consent'>
                <div class="modal-header">
                    <h3 class="modal-title">PERSETUJUAN UMUM (GENERAL CONSENT)</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
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
                        <div class="fv-row">
                            <label for="">Nama Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab">
                            <input type="hidden" value="{{ $pasien->id }}" class="form-control" name="idpasien">
                            <input type="hidden" value="Persetujuan Umum" class="form-control" name="general_consent">
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <div id="consent"></div>
                    @if ($cek_general_concent)
                    <div class="alert alert-dismissible bg-light-success border border-success border-3 d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                        <!--begin::Icon-->
                        <i class="ki-duotone ki-pencil fs-2hx text-success me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Content-->
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h5 class="mb-1">General Consent sudah disetujui</h5>
                            <span>Pasien / Keluarga pasien sudah menyetujui general consent</span>
                        </div>
                        <!--end::Content-->

                        <!--begin::Close-->
                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                            <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>                    </button>
                        <!--end::Close-->
                        <button type="button" class="btn btn-primary d-none" id="btn-setuju-consent">Setuju</button>
                    </div>
                    @else
                    <button type="button" class="btn btn-primary" id="btn-setuju-consent">Setuju</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="modal-dokter">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="kt_block_ui_4_target">
                <div id="modal-hasil"></div>
            </div>
        </div>
    </div>

    <input type="hidden" value="" id="poli_rujukan">
    {{-- <button id="openPopup">Open Popup</button> --}}
@endsection
@section('js')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.js"></script>
    <script>
        // ==================== UTILITY FUNCTIONS ====================

        // Block UI dengan loading message
        const showLoading = (message = 'Loading ...') => {
            $.blockUI({
                message: `<i class="fa fa-spinner fa-spin"></i> ${message}`,
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
        };

        // Block UI untuk elemen tertentu
        const showElementLoading = (selector, message = 'Menyimpan Data ...') => {
            $(selector).block({
                message: `<i class="fa fa-spinner fa-spin"></i> ${message}`,
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
        };

        // Hide loading
        const hideLoading = () => $.unblockUI();
        const hideElementLoading = (selector) => $(selector).unblock();

        // Setup button indicator
        const setButtonLoading = (button, isLoading) => {
            if (isLoading) {
                button.setAttribute('data-kt-indicator', 'on');
                button.disabled = true;
            } else {
                button.removeAttribute('data-kt-indicator');
                button.disabled = false;
            }
        };

        // Konfirmasi simpan data dengan SweetAlert
        const confirmSave = (title = 'Simpan Data', text = 'Apakah Anda yakin simpan data?') => {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data',
                cancelButtonText: 'Tidak'
            });
        };

        // Generic AJAX request handler
        const ajaxRequest = (options) => {
            return $.ajax({
                type: options.method || 'GET',
                url: options.url,
                data: options.data || {},
                beforeSend: options.beforeSend || (() => showLoading(options.loadingMessage)),
                success: options.success,
                error: (xhr, status, error) => {
                    hideLoading();
                    const errorMessage = xhr.responseJSON?.message || error;
                    toastr.error(errorMessage);
                    if (options.error) options.error(xhr, status, error);
                }
            });
        };

        // HTML untuk General Consent Success Alert
        const getConsentSuccessAlert = () => {
            return `<div class="alert alert-dismissible bg-light-success border border-success border-3 d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                <i class="ki-duotone ki-pencil fs-2hx text-success me-4 mb-5 mb-sm-0">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h5 class="mb-1">General Consent sudah disetujui</h5>
                    <span>Pasien / Keluarga pasien sudah menyetujui general consent</span>
                </div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>`;
        };

        // ==================== FORM VALIDATION SETUP ====================

        const setupFormValidation = () => {
            const form_consent = document.getElementById('form-consent');
            const form = document.getElementById('formInputKunjungan');

            const validationConfig = {
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            };

            const validator_consent = FormValidation.formValidation(form_consent, {
                fields: {
                    'penanggung_jawab': {
                        validators: {
                            notEmpty: { message: 'Nama Penanggung Jawab harus diisi' }
                        }
                    }
                },
                ...validationConfig
            });

            const validator = FormValidation.formValidation(form, {
                fields: {
                    'icdx': {
                        validators: { notEmpty: { message: 'icdx Harus diisi' } }
                    },
                    'no_surat': {
                        validators: { notEmpty: { message: 'no_surat Harus diisi' } }
                    },
                    'txtnmdpjp': {
                        validators: { notEmpty: { message: 'txtnmdpjp Harus diisi' } }
                    },
                    'status_kecelakaan': {
                        validators: { notEmpty: { message: 'status_kecelakaan Harus diisi' } }
                    },
                    'tglmasuk': {
                        validators: { notEmpty: { message: 'Tgl Masuk Harus diisi' } }
                    },
                    'penanggung': {
                        validators: { notEmpty: { message: 'Penanggung Harus diisi' } }
                    },
                    'jenis_rawat': {
                        validators: { notEmpty: { message: 'Jenis Rawat Harus diisi' } }
                    },
                    'idpoli': {
                        validators: { notEmpty: { message: 'Poliklinik Harus diisi' } }
                    },
                    'dokter': {
                        validators: { notEmpty: { message: 'Dokter Harus diisi' } }
                    }
                },
                ...validationConfig
            });

            return { validator_consent, validator, form_consent, form };
        };

        // ==================== CONSENT FORM HANDLER ====================

        const handleConsentSubmit = (validator_consent, form_consent) => {
            const submitConsent = document.getElementById('btn-setuju-consent');

            submitConsent.addEventListener('click', function(e) {
                e.preventDefault();

                if (!validator_consent) return;

                validator_consent.validate().then(function(status) {
                    if (status !== 'Valid') return;

                    confirmSave('Simpan Data General Consent').then((result) => {
                        if (!result.isConfirmed) return;

                        const formData = $(form_consent).serialize();
                        const url = form_consent.getAttribute('action');
                        const method = form_consent.getAttribute('method');

                        ajaxRequest({
                            method: method,
                            url: url,
                            data: formData,
                            beforeSend: () => showElementLoading('#kt_block_ui_4_target_consent', 'Menyimpan Data ...'),
                            success: (response) => {
                                hideElementLoading('#kt_block_ui_4_target_consent');

                                if (response.status === 'success') {
                                    $('#btn-setuju-consent').addClass('d-none');
                                    $('#general_concent').modal('hide');
                                    $('#consent').html(getConsentSuccessAlert());
                                }

                                toastr.success(response.message);
                            },
                            error: (xhr, status, error) => {
                                hideElementLoading('#kt_block_ui_4_target_consent');
                                console.error('Error submitting consent:', error);
                            }
                        });
                    });
                });
            });
        };

        // ==================== SEP WIZARD FUNCTIONS ====================

        // Prompt untuk tujuan kunjungan
        const showTujuanKunjunganPrompt = () => {
            return Swal.fire({
                title: 'Apa tujuan kunjungan ini?',
                html: `<select id="firstSelect" class="form-select">
                    <option value="0" data-value="" disabled selected>-- Apa tujuan kunjungan ini? --</option>
                    <option value="1" data-value="Prosedur">Prosedur</option>
                    <option value="2" data-value="Konsul Dokter">Konsul Dokter</option>
                </select>`,
                preConfirm: () => {
                    const selectedValue = $('#firstSelect').val();
                    const selectedText = $('#firstSelect option:selected').data('value');
                    if (!selectedValue) {
                        Swal.showValidationMessage('Please select an option');
                    }
                    return { selectedValue, selectedText };
                }
            });
        };

        // Prompt untuk alasan tidak selesai
        const showAlasanTidakSelesaiPrompt = () => {
            return Swal.fire({
                title: 'Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya?',
                html: `<select id="secondSelect" class="form-select">
                    <option value="" disabled selected>-- Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya? --</option>
                    <option value="1">Poli spesialis tidak tersedia pada hari sebelumnya</option>
                    <option value="2">Jam Poli telah berakhir pada hari sebelumnya</option>
                    <option value="3">Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya</option>
                    <option value="4">Atas Instruksi RS</option>
                    <option value="5">Tujuan Kontrol</option>
                </select>`,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const selectedValue = $('#secondSelect').val();
                    if (!selectedValue) {
                        Swal.showValidationMessage('Please select an option');
                    }
                    return selectedValue;
                }
            });
        };

        // Prompt untuk prosedur berkelanjutan
        const showProsedurBerkelanjutanPrompt = () => {
            return Swal.fire({
                title: 'Pilih Prosedur Berkelanjutan',
                html: `<select id="prosedur" name="prosedur" class="form-control">
                    <option value="">-- Prosedur Berkelanjutan --</option>
                    <option value="1">Radioterapi</option>
                    <option value="2">Kemoterapi</option>
                    <option value="3">Rehabilitasi Medik</option>
                    <option value="4">Rehabilitasi Psikososial</option>
                    <option value="5">Transfusi Darah</option>
                    <option value="6">Pelayanan Gigi</option>
                    <option value="12">HEMODIALISA</option>
                </select>`,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const selectedValue = $('#prosedur').val();
                    if (!selectedValue) {
                        Swal.showValidationMessage('Please select an option');
                    }
                    return selectedValue;
                }
            });
        };

        // Prompt untuk prosedur tidak berkelanjutan
        const showProsedurTidakBerkelanjutanPrompt = () => {
            return Swal.fire({
                title: 'Pilih Prosedur Tidak Berkelanjutan',
                html: `<select id="nonprosedur" name="nonprosedur" class="form-control">
                    <option value="">-- Prosedur tidak berkelanjutan --</option>
                    <option value="7">Laboratorium</option>
                    <option value="8">USG</option>
                    <option value="9">Farmasi</option>
                    <option value="10">Lain-Lain</option>
                    <option value="11">MRI</option>
                </select>`,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const selectedValue = $('#nonprosedur').val();
                    if (!selectedValue) {
                        Swal.showValidationMessage('Please select an option');
                    }
                    return selectedValue;
                }
            });
        };

        // Prompt untuk berkelanjutan ya/tidak
        const showBerkelanjutanPrompt = (selections) => {
            return Swal.fire({
                title: 'Apakah Kunjungan ini untuk prosedur dan terapi berkelanjutan?',
                html: `<button id="yesButton" class="swal2-confirm swal2-styled" style="display: inline-block; margin-right: 10px;">Ya</button>
                    <button id="noButton" class="swal2-cancel swal2-styled" style="display: inline-block;">Tidak</button>`,
                showCancelButton: false,
                showConfirmButton: false,
                didRender: () => {
                    $('#yesButton').on('click', () => {
                        selections.prosedur = 1;
                        Swal.close();
                        showProsedurBerkelanjutanPrompt().then((result) => {
                            if (result.isConfirmed) {
                                selections.prosedurBerkelanjutan = result.value;
                                return submitFormData(selections);
                            }
                        });
                    });

                    $('#noButton').on('click', () => {
                        selections.prosedur = 0;
                        Swal.close();
                        showProsedurTidakBerkelanjutanPrompt().then((result) => {
                            if (result.isConfirmed) {
                                selections.prosedurTidakBerkelanjutan = result.value;
                                return submitFormData(selections);
                            }
                        });
                    });
                }
            });
        };

        // Submit form data dengan selections
        const submitFormData = (selections = {}) => {
            const form = document.getElementById('formInputKunjungan');
            const submitButton = document.getElementById('button-simpan');

            return confirmSave().then((result) => {
                if (!result.isConfirmed) return;

                setButtonLoading(submitButton, true);

                let formData = $(form).serialize();

                // Tambahkan selections jika ada
                if (Object.keys(selections).length > 0) {
                    formData += `&tujuanKunjungan=${selections.tujuanKunjungan || ''}`;
                    formData += `&alasanTidakSelesai=${selections.alasanTidakSelesai || ''}`;
                    formData += `&prosedur=${selections.prosedur || ''}`;
                    formData += `&prosedurTidakBerkelanjutan=${selections.prosedurTidakBerkelanjutan || ''}`;
                    formData += `&prosedurBerkelanjutan=${selections.prosedurBerkelanjutan || ''}`;
                }

                const url = form.getAttribute('action');
                const method = form.getAttribute('method');

                ajaxRequest({
                    method: method,
                    url: url,
                    data: formData,
                    loadingMessage: 'Mengirim Data ...',
                    success: (response) => {
                        hideLoading();
                        setButtonLoading(submitButton, false);

                        if (response.status === 'failed') {
                            toastr.error(response.message);
                            return;
                        }

                        if (response.error) {
                            toastr.error(response.error);
                            return;
                        }

                        toastr.success(response.message);
                    },
                    error: () => {
                        setButtonLoading(submitButton, false);
                    }
                });
            });
        };

        // ==================== MAIN FORM HANDLER ====================

        const handleMainFormSubmit = (validator) => {
            const submitButton = document.getElementById('button-simpan');

            submitButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (!validator) return;

                validator.validate().then(function(status) {
                    if (status !== 'Valid') return;

                    const no_surat = $('#no_surat').val();
                    const is_rujukan = $('#is_rujukan').val();

                    if (is_rujukan) {
                        handleRujukanFlow(no_surat);
                    } else {
                        submitFormData();
                    }
                });
            });
        };

        // Handle alur rujukan dengan wizard
        const handleRujukanFlow = (no_surat) => {
            const selections = {
                tujuanKunjungan: '',
                alasanTidakSelesai: '',
                prosedur: '',
                prosedurBerkelanjutan: '',
                prosedurTidakBerkelanjutan: ''
            };

            if (no_surat) {
                // Dengan surat rujukan
                showTujuanKunjunganPrompt().then((result) => {
                    if (!result.isConfirmed) return;

                    selections.tujuanKunjungan = result.value.selectedValue;

                    if (result.value.selectedValue == 2) {
                        // Konsul Dokter
                        showAlasanTidakSelesaiPrompt().then((result2) => {
                            if (result2.isConfirmed) {
                                selections.alasanTidakSelesai = result2.value;
                                submitFormData(selections);
                            }
                        });
                    } else {
                        // Prosedur
                        showBerkelanjutanPrompt(selections);
                    }
                });
            } else {
                // Tanpa surat rujukan
                showAlasanTidakSelesaiPrompt().then((result) => {
                    if (result.isConfirmed) {
                        selections.tujuanKunjungan = 0;
                        selections.alasanTidakSelesai = result.value;
                        submitFormData(selections);
                    }
                });
            }
        };

        // ==================== EVENT HANDLERS ====================

        // Handler untuk SEP Manual
        const handleSepManual = () => {
                                                    Swal.showValidationMessage(
                                                        'Please select an option');
                                                }
                                                selections.tujuanKunjungan =
                                                    selectedValue;
                                                return {
                                                    selectedValue,
                                                    selectedText
                                                };
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                if (result.value.selectedValue == 2) {
                                                    var select_option =
                                                        '<select id="secondSelect" class="form-select">' +
                                                        '<option value="" disabled selected>-- Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya? --</option>' +
                                                        '<option value="1">Poli spesialis tidak tersedia pada hari sebelumnya</option>' +
                                                        '<option value="2">Jam Poli telah berakhir pada hari sebelumnya</option>' +
                                                        '<option value="3">Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya</option>' +
                                                        '<option value="4">Atas Instruksi RS</option>' +
                                                        '<option value="5">Tujuan Kontrol</option>' +
                                                        '</select>';
                                                    Swal.fire({
                                                        title: 'Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya?',
                                                        html: select_option,
                                                        preConfirm: () => {
                                                            const
                                                                selectedValue =
                                                                $(
                                                                    '#secondSelect'
                                                                )
                                                                .val();
                                                            if (!
                                                                selectedValue) {
                                                                Swal.showValidationMessage(
                                                                    'Please select an option'
                                                                );
                                                            }
                                                            selections
                                                                .alasanTidakSelesai =
                                                                selectedValue;
                                                            return selectedValue;
                                                        }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            simpanDatasep();
                                                        }
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: 'Apakah Kunjungan ini untuk prosedur dan terapi berkelanjutan?',
                                                        html: '<button id="yesButton" class="swal2-confirm swal2-styled" style="display: inline-block; margin-right: 10px;">Ya</button>' +
                                                            '<button id="noButton" class="swal2-cancel swal2-styled" style="display: inline-block;">Tidak</button>',
                                                        showCancelButton: false,
                                                        showConfirmButton: false,
                                                        didRender: () => {
                                                            const yesButton =
                                                                document
                                                                .getElementById(
                                                                    'yesButton'
                                                                );
                                                            const noButton =
                                                                document
                                                                .getElementById(
                                                                    'noButton');

                                                            yesButton
                                                                .addEventListener(
                                                                    'click',
                                                                    () => {
                                                                        selections
                                                                            .prosedur =
                                                                            1;
                                                                        Swal
                                                                            .close();
                                                                        Swal.fire({
                                                                                title: 'Pilih Prosedur Berkelanjutan',
                                                                                html: '<select id="prosedur" name="prosedur" class="form-control">' +
                                                                                    '<option checked="" value="">-- Prosedur Berkelanjutan --</option>' +
                                                                                    '<option value="1">Radioterapi</option>' +
                                                                                    '<option value="2">Kemoterapi</option>' +
                                                                                    '<option value="3">Rehabilitasi Medik</option>' +
                                                                                    '<option value="4">Rehabilitasi Psikososial</option>' +
                                                                                    '<option value="5">Transfusi Darah</option>' +
                                                                                    '<option value="6">Pelayanan Gigi</option>' +
                                                                                    '<option value="12">HEMODIALISA</option>' +
                                                                                    '</select>',
                                                                                showCancelButton: true,
                                                                                cancelButtonText: 'Cancel',
                                                                                preConfirm: () => {
                                                                                    const
                                                                                        selectedValue =
                                                                                        document
                                                                                        .getElementById(
                                                                                            'prosedur'
                                                                                        )
                                                                                        .value;
                                                                                    if (!
                                                                                        selectedValue
                                                                                    ) {
                                                                                        Swal.showValidationMessage(
                                                                                            'Please select an option'
                                                                                        );
                                                                                    }
                                                                                    selections
                                                                                        .prosedurBerkelanjutan =
                                                                                        selectedValue;
                                                                                    return selectedValue;
                                                                                }
                                                                            })
                                                                            .then(
                                                                                (
                                                                                    result
                                                                                ) => {
                                                                                    if (result
                                                                                        .isConfirmed
                                                                                    ) {
                                                                                        simpanDatasep
                                                                                            ();
                                                                                    }
                                                                                }
                                                                            );
                                                                    });

                                                            noButton
                                                                .addEventListener(
                                                                    'click',
                                                                    () => {
                                                                        selections
                                                                            .prosedur =
                                                                            0;
                                                                        Swal
                                                                            .close();
                                                                        Swal.fire({
                                                                                title: 'Pilih Prosedur Tidak Berkelanjutan',
                                                                                html: '<select id="nonprosedur" name="nonprosedur" class="form-control">' +
                                                                                    '<option checked="" value="">-- Prosedur tidak berkelanjutan --</option>' +
                                                                                    '<option value="7">Laboratorium</option>' +
                                                                                    '<option value="8">USG</option>' +
                                                                                    '<option value="9">Farmasi</option>' +
                                                                                    '<option value="10">Lain-Lain</option>' +
                                                                                    '<option value="11">MRI</option>' +
                                                                                    '</select>',
                                                                                showCancelButton: true,
                                                                                cancelButtonText: 'Cancel',
                                                                                preConfirm: () => {
                                                                                    const
                                                                                        selectedValue =
                                                                                        document
                                                                                        .getElementById(
                                                                                            'nonprosedur'
                                                                                        )
                                                                                        .value;
                                                                                    if (!
                                                                                        selectedValue
                                                                                    ) {
                                                                                        Swal.showValidationMessage(
                                                                                            'Please select an option'
                                                                                        );
                                                                                    }
                                                                                    selections
                                                                                        .prosedurTidakBerkelanjutan =
                                                                                        selectedValue;
                                                                                    return selectedValue;
                                                                                }
                                                                            })
                                                                            .then(
                                                                                (
                                                                                    result
                                                                                ) => {
                                                                                    if (result
                                                                                        .isConfirmed
                                                                                    ) {
                                                                                        simpanDatasep
                                                                                            ();
                                                                                    }
                                                                                }
                                                                            );
                                                                    });
                                                        }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            simpanDatasep();
                                                        }
                                                    });

                                                }
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya?',
                                            html: '<select id="secondSelect" class="form-select">' +
                                                '<option value="" disabled selected>-- Mengapa pelayanan ini tidak diselesaikan pada hari yang sama sebelumnya? --</option>' +
                                                '<option value="1">Poli spesialis tidak tersedia pada hari sebelumnya</option>' +
                                                '<option value="2">Jam Poli telah berakhir pada hari sebelumnya</option>' +
                                                '<option value="3">Dokter Spesialis yang dimaksud tidak praktek pada hari sebelumnya</option>' +
                                                '<option value="4">Atas Instruksi RS</option>' +
                                                '<option value="5">Tujuan Kontrol</option>' +
                                                '</select>',
                                            showCancelButton: true,
                                            cancelButtonText: 'Cancel',
                                            preConfirm: () => {
                                                const selectedValue = $('#secondSelect')
                                                    .val();
                                                const selectedText = $(
                                                        '#secondSelect option:selected')
                                                    .data(
                                                        'value');
                                                if (!selectedValue) {
                                                    Swal.showValidationMessage(
                                                        'Please select an option');
                                                }
                                                selections.tujuanKunjungan = 0;
                                                selections.alasanTidakSelesai =
                                                    selectedValue;
                                                return {
                                                    selectedValue,
                                                    selectedText
                                                };
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                simpanDatasep();
                                            }
                                        });
                                    }


                                    function simpanDatasep() {
                                        Swal.fire({
                                            title: 'Simpan Data',
                                            text: "Apakah Anda yakin simpan data ? ",
                                            icon: 'info',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, Simpan Data',
                                            cancelButtonText: 'Tidak'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                submitButton2.setAttribute(
                                                    'data-kt-indicator',
                                                    'on');

                                                // Disable button to avoid multiple click
                                                submitButton2.disabled = true;

                                                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/

                                                // var formData = form.closest('form');
                                                var formDataFix = $(form).serialize();
                                                formDataFix +=
                                                    `&tujuanKunjungan=${selections.tujuanKunjungan}`;
                                                formDataFix +=
                                                    `&alasanTidakSelesai=${selections.alasanTidakSelesai}`;
                                                formDataFix +=
                                                    `&prosedur=${selections.prosedur}`;
                                                formDataFix +=
                                                    `&prosedurTidakBerkelanjutan=${selections.prosedurTidakBerkelanjutan}`;
                                                formDataFix +=
                                                    `&prosedurTidakBerkelanjutan=${selections.prosedurBerkelanjutan}`;
                                                var url = form.getAttribute('action');
                                                var method = form.getAttribute('method');
                                                $.ajax({
                                                    type: method,
                                                    url: url, // Replace with your server endpoint
                                                    data: formDataFix,
                                                    beforeSend: function() {
                                                        $.blockUI({
                                                            message: '<i class="fa fa-spinner fa-spin"></i> Mengirim Data ...',
                                                            css: {
                                                                border: 'none',
                                                                padding: '15px',
                                                                backgroundColor: '#000',
                                                                '-webkit-border-radius': '10px',
                                                                '-moz-border-radius': '10px',
                                                                opacity: .5,
                                                                color: '#fff'
                                                            }
                                                        });
                                                    },
                                                    success: function(response) {
                                                        // console.log(response);
                                                        if (response.error) {
                                                            toastr.success(
                                                                response
                                                                .error);
                                                        }
                                                        $.unblockUI();
                                                        submitButton2.disabled =
                                                            false;
                                                        if (response.status ==
                                                            'failed') {
                                                            toastr.error(
                                                                response
                                                                .message);
                                                            return false
                                                        }else{
                                                            toastr.success(response
                                                            .message);
                                                        $.blockUI({
                                                            message: '<i class="fa fa-spinner fa-spin"></i> Loading ...',
                                                            css: {
                                                                border: 'none',
                                                                padding: '15px',
                                                                backgroundColor: '#000',
                                                                '-webkit-border-radius': '10px',
                                                                '-moz-border-radius': '10px',
                                                                opacity: .5,
                                                                color: '#fff'
                                                            }
                                                        });
                                                        // window.location =
                                                        //     "{{ route('pasien.rekammedis_detail', $pasien->id) }}"
                                                        }

                                                    },
                                                    error: function(error) {
                                                        $.unblockUI();
                                                        toastr.error(error);
                                                        console.log(
                                                            'Error submitting form:',
                                                            error);
                                                    }
                                                });
                                            }
                                        });
                                    }

                                    function displayFinalSelections() {
                                        Swal.fire({
                                            title: 'Your Selections',
                                            html: `<p>Tujuan Kunjungan: ${selections.tujuanKunjungan}</p>
                                            <p>Alasan Tidak Selesai: ${selections.alasanTidakSelesai}</p>
                                            <p>Prosedur: ${selections.prosedur}</p>
                                            <p>Prosedur Berkelanjutan: ${selections.prosedurBerkelanjutan}</p>
                                            <p>Prosedur Tidak Berkelanjutan: ${selections.prosedurTidakBerkelanjutan}</p>`,
                                            icon: 'info'
                                        });
                                    }

                                } else {
                                    Swal.fire({
                                        title: 'Simpan Data',
                                        text: "Apakah Anda yakin simpan data ? ",
                                        icon: 'info',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya, Simpan Data',
                                        cancelButtonText: 'Tidak'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            submitButton2.setAttribute('data-kt-indicator',
                                                'on');

                                            // Disable button to avoid multiple click
                                            submitButton2.disabled = true;

                                            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/

                                            // var formData = form.closest('form');
                                            var formDataFix = $(form).serialize();
                                            var url = form.getAttribute('action');
                                            var method = form.getAttribute('method');
                                            $.ajax({
                                                type: method,
                                                url: url, // Replace with your server endpoint
                                                data: formDataFix,
                                                beforeSend: function() {
                                                    $.blockUI({
                                                        message: '<i class="fa fa-spinner fa-spin"></i> Mengirim Data ...',
                                                        css: {
                                                            border: 'none',
                                                            padding: '15px',
                                                            backgroundColor: '#000',
                                                            '-webkit-border-radius': '10px',
                                                            '-moz-border-radius': '10px',
                                                            opacity: .5,
                                                            color: '#fff'
                                                        }
                                                    });
                                                },
                                                success: function(response) {
                                                    $.unblockUI();
                                                    submitButton2.disabled =
                                                        false;
                                                    if (response.status ==
                                                        'failed') {
                                                        toastr.error(response
                                                            .message);
                                                        return false
                                                    } else if (response.error) {
                                                        toastr.error(response
                                                            .error);
                                                            return false
                                                    } else {
                                                        toastr.success(response
                                                            .message);
                                                            //  window.location =
                                                            // "{{ route('pasien.rekammedis_detail', $pasien->id) }}"
                                                    }


                                                    console.log(response);
                                                },
                                                error: function(error) {
                                                    $.unblockUI();
                                                    toastr.error(error);
                                                    console.log(
                                                        'Error submitting form:',
                                                        error);
                                                }
                                            });
                                        }
                                    });
                                }



                            }
                        });
                    }
                });
            });
        });

        $('#btn-sep-manual').on('click', function() {
            $('#sep_rujukan').empty();

            $.ajax({
                url: '{{ route('buat-sep-manual') }}',
                type: 'GET',
                data: {
                    no_rm: '{{ $pasien->no_rm }}',
                },

                success: function(response) {
                    console.log(response);
                    if (response.status === 'failed') {
                        $('#insert_sep_manual').empty();
                    } else {
                        $('#insert_sep_manual').html(response);
                        $('#btn-cari-dokter').prop('disabled', false);
                        $('#jenis_rawat').val(3);
                        $('#poli').html(`
                            <option selected value="1">UGD</option>
                        `);
                        $('#kt_datepicker_1').val("{{ date('Y-m-d') }}");
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseJSON.message || error);
                    $('#insert_sep_manual').empty();
                }
            });
        });


        $(document).ready(function() {
            $('#openPopup').on('click', function() {
                Swal.fire({
                    title: 'Apa tujuan kunjungan ini?',
                    html: '<select id="firstSelect" class="form-select">' +
                        '<option value="0" data-value="" disabled selected>-- Apa tujuan kunjungan ini? --</option>' +
                        '<option value="1" data-value="Prosedur">Prosedur</option>' +
                        '<option value="2" data-value="Konsul Dokter">Konsul Dokter</option>' +
                        '</select>',
                    preConfirm: () => {
                        const selectedValue = $('#firstSelect').val();
                        const selectedText = $('#firstSelect option:selected').data(
                            'value');
                        if (!selectedValue) {
                            Swal.showValidationMessage('Please select an option');
                        }
                        return selectedValue;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'You selected ' + result.value.selectedText,
                            html: '<select id="secondSelect" class="form-select">' +
                                '<option value="" disabled selected>Select another option</option>' +
                                '<option value="1">Sub-option 1</option>' +
                                '<option value="2">Sub-option 2</option>' +
                                '<option value="3">Sub-option 3</option>' +
                                '</select>',
                            preConfirm: () => {
                                const selectedValue = $('#secondSelect').val();
                                if (!selectedValue) {
                                    Swal.showValidationMessage(
                                        'Please select an option');
                                }
                                return selectedValue;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire(
                                    'Selected!',
                                    'You selected: ' + result.value,
                                    'success'
                                );
                            }
                        });
                    }
                });
            });
        });
        $('#buat_sep').change(function() {
            var penanggung = $('#penanggung').val();
            if ($(this).is(':checked')) {
                var penanggung = $('#penanggung').val();
                if (penanggung == null || penanggung == '' || penanggung == 1) {
                    toastr.error('Harap isi penanggung dengan BPJS');
                    $(this).prop('checked', false);
                    $('#sep').val(null);
                } else {
                    $('#tambah_kunjungan').removeClass('col-md-12').addClass('col-md-5');
                    $('#card_buat_sep').removeClass('d-none');
                    $('#sep').val(1);
                }

            } else {
                $('#tambah_kunjungan').removeClass('col-md-5').addClass('col-md-12');
                $('#card_buat_sep').addClass('d-none');
                $('#list_data_rujukan').empty();
                $('#sep').val(null);
            }
        });
        $('#poli').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue > 0) {
                if ($('#poli_rujukan').val() != selectedValue) {
                    $('.rujukan_kontrol').addClass('d-none');
                    $('#no_surat').val('');
                    $('#txtkddpjp').val('');
                    $('#txtnmdpjp').val('');
                } else {
                    $('.rujukan_kontrol').removeClass('d-none');
                }
                $('#btn-cari-dokter').prop('disabled', false);
            } else {
                $('#btn-cari-dokter').prop('disabled', true);
            }
            $('#dokter').val('');
            $('#iddokter').val('');
        })


        $('#faskes').on('change', function() {
            var faskes = $(this).val();
            var no_bpjs = "{{ $pasien->no_bpjs }}";
            if (faskes == '' || faskes == null) {
                toastr.error('Silahkan pilih faskes');
                $('#list_data_rujukan').empty();
                return false;
            }
            $.ajax({
                url: '{{ route('get-rujukan-faskes') }}',
                type: 'GET',
                data: {
                    faskes: faskes,
                    no_bpjs: no_bpjs
                },
                beforeSend: function() {
                    $.blockUI({
                        message: '<i class="fa fa-spinner fa-spin"></i> Loading ...',
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff'
                        }
                    });
                },
                success: function(response) {
                    $.unblockUI();
                    if (response.status === 'failed') {
                        toastr.error(response.message);
                        $('#list_data_rujukan').empty();
                    } else {
                        $('#list_data_rujukan').html(response.response);
                        toastr.success(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $.unblockUI();
                    toastr.error(xhr.responseJSON.message || error);
                    $('#list_data_rujukan').empty();
                }
            });
        });
        var buttoncaridokter = document.querySelector("#btn-cari-dokter");

        // Handle the click event for the buttoncaridokter
        buttoncaridokter.addEventListener("click", function() {
            // Activate indicator
            buttoncaridokter.setAttribute("data-kt-indicator", "on");

            var tgl = $('#kt_datepicker_1').val();
            var poli = $('#poli').val();
            var penanggung = $('#penanggung').val();
            console.log(tgl);
            if (tgl == null || tgl == '') {
                toastr.error('Harap isi tanggal kunjungan terlebih dahulu');
                buttoncaridokter.removeAttribute("data-kt-indicator"); // Disable indicator on validation error
                return false;
            }
            if (penanggung == null || penanggung == '') {
                toastr.error('Harap isi penanggung terlebih dahulu');
                buttoncaridokter.removeAttribute("data-kt-indicator"); // Disable indicator on validation error
                return false;
            }
            // Make AJAX request
            $.ajax({
                url: '{{ route('get-jadwal-dokter') }}',
                type: 'GET',
                data: {
                    poli: poli,
                    tgl: tgl,
                    penanggung: penanggung
                },
                success: function(response) {
                    // Display the result
                    $('#modal-hasil').html(response);
                    $('#modal-dokter').modal('show');
                    // Disable indicator after successful response
                    buttoncaridokter.removeAttribute("data-kt-indicator");
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                    toastr.error(xhr.responseJSON.message || error);
                    // Disable indicator after error response
                    buttoncaridokter.removeAttribute("data-kt-indicator");
                }
            });

            // Optionally, disable the indicator after a fixed time regardless of AJAX response
            // setTimeout(function() {
            //     button.removeAttribute("data-kt-indicator");
            // }, 3000);
        });

        $("#kt_datepicker_1").flatpickr();

        function updateOptions() {
            var jenisRawat = document.getElementById("jenis_rawat").value;
            var anotherSelect = document.getElementById("poli");
            // Clear existing options
            anotherSelect.innerHTML = '';

            // Define options based on the selected value
            var options = [];

            if (jenisRawat == 1) {
                options = [{
                        value: '',
                        text: 'Pilih Poli'
                    },
                    {
                        value: '2',
                        text: 'BEDAH'
                    },
                    {
                        value: '3',
                        text: 'GIGI'
                    },
                    {
                        value: '4',
                        text: 'OBGYN'
                    },
                    {
                        value: '5',
                        text: 'PENYAKIT DALAM'
                    },
                    {
                        value: '6',
                        text: 'ANAK'
                    },
                    {
                        value: '7',
                        text: 'Saraf'
                    },
                    {
                        value: '8',
                        text: 'JIWA'
                    },
                    {
                        value: '9',
                        text: 'PARU'
                    },
                    {
                        value: '10',
                        text: 'ORTOPEDI'
                    },
                    {
                        value: '11',
                        text: 'MATA'
                    },
                    {
                        value: '12',
                        text: 'REHABMEDIK'
                    },
                    {
                        value: '13',
                        text: 'GIGI PRSOSTHODONTI'
                    },
                    {
                        value: '14',
                        text: 'KULIT'
                    }

                ];
            } else if (jenisRawat == 3) {
                options = [{
                        value: '',
                        text: 'Pilih Poli'
                    },
                    {
                        value: 1,
                        text: 'UGD'
                    }
                ];
            }

            // Add new options to the select element
            options.forEach(function(option) {
                var newOption = document.createElement("option");
                newOption.value = option.value;
                newOption.text = option.text;
                anotherSelect.appendChild(newOption);
            });
        }
    </script>
@endsection
