@extends('layouts.index')
@section('css')
<style>
    /* Compact Form Styling */
    .accordion-button:not(.collapsed) {
        background-color: #f1faff;
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 0, 0, .125);
    }
    .accordion-item {
        border: 1px solid #e4e6ef;
        border-radius: 0.475rem !important;
    }
    .form-control-sm, .form-select-sm {
        font-size: 0.925rem;
    }
    .input-group-sm .input-group-text {
        font-size: 0.85rem;
        padding: 0.4rem 0.75rem;
    }
    /* Sticky Footer Shadow */
    .sticky-bottom {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.95) !important;
    }
    /* Badge compact */
    .badge {
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    /* Smooth transitions */
    .accordion-collapse {
        transition: all 0.3s ease-in-out;
    }
    /* Compact spacing for repeater */
    [data-repeater-item] {
        background-color: #f9fafb;
        padding: 0.75rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }
    /* Focus states */
    .form-control:focus, .form-select:focus {
        border-color: #009ef7;
        box-shadow: 0 0 0 0.15rem rgba(0, 158, 247, 0.15);
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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Tambah
                            Rekam Medis Pasien</h1>
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
                            <li class="breadcrumb-item text-muted">Pasien</li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Rekam Medis</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::FAQ card-->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="card-title">
                            <h5 class="card-title">Rekam Medis Pasien</h5>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('rekam-medis-poli', $data->idrawat) }}"
                                class="btn btn-sm btn-secondary">Kembali</a>
                        </div>
                    </div>
                    <!--begin::Body-->
                    <div class="card-body p-lg-6">
                        <form action="{{ route('detail-rekap-medis-store', $data->id) }}" method="post" autocomplete="off"
                            id="frm-data">
                            @csrf
                            <input type="hidden" name="kategori" value="{{ $kategori->id }}">

                            <!--begin::Compact Patient Info Card-->
                            <div class="card bg-light-primary mb-5">
                                <div class="card-body p-5">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ki-duotone ki-user fs-2x text-primary me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <div>
                                                    <h4 class="fw-bold mb-0">{{ $pasien->nama_pasien }}</h4>
                                                    <span class="text-muted fs-7">RM: {{ $pasien->no_rm }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <span class="text-muted fs-8">NIK:</span>
                                                    <span class="fw-semibold fs-7 d-block">{{ $pasien->nik }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted fs-8">No BPJS:</span>
                                                    <span class="fw-semibold fs-7 d-block">{{ $pasien->no_bpjs }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted fs-8">HP:</span>
                                                    <span class="fw-semibold fs-7 d-block">{{ $pasien->nohp }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted fs-8">Alamat:</span>
                                                    <span class="fw-semibold fs-7 d-block text-truncate" title="{{ $pasien->alamat->alamat }}">{{ $pasien->alamat->alamat }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Compact Patient Info Card-->
                            @if ($rawat->idjenisrawat == 3)
                            @if (auth()->user()->idpriv == 14 || auth()->user()->idpriv == 18 || auth()->user()->idpriv == 29)
                                    <div class="mb-5">
                                        <label class="form-label fw-bold fs-6">Triase</label>
                                        <select class="form-select form-select-sm" name="triase" data-control="select2"
                                            data-placeholder="Select an option">
                                            <option></option>
                                            @foreach ($triase as $t)
                                                <option value="{{ $t->id }}">{{ $t->triase }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif

                            @if (auth()->user()->idpriv == 7)
                            <!--begin::Accordion Sections-->
                            <div class="accordion accordion-icon-toggle" id="kt_accordion_medical">

                                <!--begin::Accordion Item 1 - SOAP-->
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="kt_accordion_medical_header_1">
                                        <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_medical_body_1" aria-expanded="false" aria-controls="kt_accordion_medical_body_1">
                                            <i class="ki-duotone ki-shield-tick fs-2 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            1. SOAP (Subjective, Objective, Assessment, Plan)
                                            <span class="badge badge-light-primary ms-3">Wajib</span>
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_medical_body_1" class="accordion-collapse collapse"
                                        aria-labelledby="kt_accordion_medical_header_1" data-bs-parent="#kt_accordion_medical">
                                        <div class="accordion-body">
                                            <div class="alert alert-info d-flex align-items-center p-4 mb-5">
                                                <i class="ki-duotone ki-information fs-2 text-info me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                <span class="fs-7">Format standar dokumentasi medis untuk mencatat kondisi dan rencana perawatan pasien</span>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-success me-2">S</span>
                                                        Subjective (Keluhan Pasien)
                                                    </label>
                                                    <textarea name="soap[subjective]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Keluhan utama pasien, riwayat penyakit sekarang..." required></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-info me-2">O</span>
                                                        Objective (Pemeriksaan Fisik)
                                                    </label>
                                                    <textarea name="soap[objective]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Hasil pemeriksaan fisik, vital signs..." required></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-warning me-2">A</span>
                                                        Assessment (Diagnosis)
                                                    </label>
                                                    <textarea name="soap[assessment]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Diagnosis kerja, diagnosis banding..." required></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-danger me-2">P</span>
                                                        Plan (Rencana Tindakan)
                                                    </label>
                                                    <textarea name="soap[plan]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Rencana pemeriksaan, terapi medikamentosa..." required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Accordion Item 1-->

                                <!--begin::Accordion Item 2 - Diagnosis & ICD-->
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="kt_accordion_medical_header_2">
                                        <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_medical_body_2" aria-expanded="false" aria-controls="kt_accordion_medical_body_2">
                                            <i class="ki-duotone ki-abstract-26 fs-2 me-2 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            2. Diagnosis & Kode ICD
                                            <span class="badge badge-light-success ms-3">Wajib</span>
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_medical_body_2" class="accordion-collapse collapse"
                                        aria-labelledby="kt_accordion_medical_header_2" data-bs-parent="#kt_accordion_medical">
                                        <div class="accordion-body">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold fs-6">Diagnosa</label>
                                                <textarea name="diagnosa" rows="2" class="form-control form-control-sm" placeholder="Tulis diagnosa..."></textarea>
                                            </div>

                                            <div class="alert alert-primary d-flex align-items-center p-3 mb-3">
                                                <i class="ki-duotone ki-information-5 fs-3 text-primary me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                <span class="fs-7"><strong>Info ICD X:</strong> Minimal 1 diagnosa wajib diisi. Diagnosa pertama otomatis <strong>Primer</strong>, berikutnya <strong>Sekunder</strong></span>
                                            </div>

                                            <div id="icdx_repeater">
                                                <div class="form-group">
                                                    <div data-repeater-list="icdx">
                                                        <div data-repeater-item>
                                                            <div class="row g-2 mb-2 align-items-end">
                                                                <div class="col-md-5">
                                                                    <label class="form-label fw-bold fs-7 required">ICD X</label>
                                                                    <select name="diagnosa_icdx" class="form-select form-select-sm icdx-diagnosa"
                                                                        data-kt-repeater="select22" data-placeholder="-Pilih-" >
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label fw-bold fs-7 required">Jenis</label>
                                                                    <select name="jenis_diagnosa"
                                                                        class="form-select form-select-sm icdx-jenis-diagnosa">
                                                                        <option value="P" selected>Primer</option>
                                                                        <option value="S">Sekunder</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <a href="javascript:;" data-repeater-delete
                                                                        class="btn btn-sm btn-light-danger icdx-delete-btn">
                                                                        <i class="ki-duotone ki-trash fs-6">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                            <span class="path4"></span>
                                                                            <span class="path5"></span>
                                                                        </i>
                                                                        Hapus
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                        <i class="ki-duotone ki-plus fs-4"></i>
                                                        Tambah ICD X
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="separator my-5"></div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold fs-6">Tindakan / Prosedur</label>
                                                <textarea name="tindakan_prc" rows="2" class="form-control form-control-sm" placeholder="Tulis tindakan/prosedur..."></textarea>
                                            </div>

                                            <div id="icd9_repeater">
                                                <div class="form-group">
                                                    <div data-repeater-list="icd9">
                                                        <div data-repeater-item>
                                                            <div class="row g-2 mb-2 align-items-end">
                                                                <div class="col-md-8">
                                                                    <label class="form-label fw-bold fs-7">ICD 9</label>
                                                                    <select name="diagnosa_icd9" class="form-select form-select-sm"
                                                                        data-kt-repeater="select2icd9" data-placeholder="-Pilih-">
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <a href="javascript:;" data-repeater-delete
                                                                        class="btn btn-sm btn-light-danger">
                                                                        <i class="ki-duotone ki-trash fs-6">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                            <span class="path4"></span>
                                                                            <span class="path5"></span>
                                                                        </i>
                                                                        Hapus
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                        <i class="ki-duotone ki-plus fs-4"></i>
                                                        Tambah ICD 9
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Accordion Item 2-->

                                <!--begin::Accordion Item 3 - Anamnesa & Pemeriksaan-->
                                @if ($rawat->idjenisrawat == 3 || $rawat->idpoli == 12)
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="kt_accordion_medical_header_3">
                                        <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_medical_body_3" aria-expanded="false" aria-controls="kt_accordion_medical_body_3">
                                            <i class="ki-duotone ki-stethoscope fs-2 me-2 text-info">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            3. Anamnesa & Pemeriksaan Tambahan
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_medical_body_3" class="accordion-collapse collapse"
                                        aria-labelledby="kt_accordion_medical_header_3" data-bs-parent="#kt_accordion_medical">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold fs-6">Anamnesa</label>
                                                <textarea name="anamnesa_dokter" rows="2" class="form-control form-control-sm" placeholder="Anamnesa Dokter"></textarea>
                                            </div>

                                            @if ($rawat->idjenisrawat == 3)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold fs-6">Pemeriksaan Fisik</label>
                                                <textarea name="pemeriksaan_fisik" rows="2" class="form-control form-control-sm" placeholder="Pemeriksaan Fisik"></textarea>
                                            </div>
                                            @endif

                                            @if ($rawat->idpoli == 12)
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold fs-6">Pemeriksaan Fisik</label>
                                                        <textarea name="pemeriksaan_fisik" rows="2" class="form-control form-control-sm" placeholder="Pemeriksaan Fisik"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold fs-6">Pemeriksaan Uji Fungsi</label>
                                                        <textarea name="pemeriksaan_uji_fungsi" rows="2" class="form-control form-control-sm" placeholder="Pemeriksaan Uji Fungsi"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold fs-6">Tata Laksana KFR (ICD CM)</label>
                                                        <textarea name="tata_laksana" rows="2" class="form-control form-control-sm" placeholder="Tata Laksana"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold fs-6">Anjuran</label>
                                                        <textarea name="anjuran" rows="2" class="form-control form-control-sm" placeholder="Anjuran"></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold fs-6">Evaluasi</label>
                                                        <textarea name="evaluasi" rows="2" class="form-control form-control-sm" placeholder="Evaluasi"></textarea>
                                                    </div>
                                                </div>

                                                <div class="separator my-4"></div>

                                                <div id="fisio_repeater">
                                                    <div class="form-group">
                                                        <div data-repeater-list="fisio">
                                                            <div data-repeater-item>
                                                                <div class="row g-2 mb-2 align-items-end">
                                                                    <div class="col-md-8">
                                                                        <label class="form-label fw-bold fs-7">Permintaan Terapi</label>
                                                                        <select name="tindakan_fisio" class="form-select form-select-sm"
                                                                            data-kt-repeater="select2fisio" data-placeholder="-Pilih-" required>
                                                                            <option></option>
                                                                            @foreach ($fisio as $fisio)
                                                                                <option value="{{ $fisio->id }}">{{ $fisio->nama_tarif }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <a href="javascript:;" data-repeater-delete
                                                                            class="btn btn-sm btn-light-danger">
                                                                            <i class="ki-duotone ki-trash fs-6">
                                                                                <span class="path1"></span>
                                                                                <span class="path2"></span>
                                                                                <span class="path3"></span>
                                                                                <span class="path4"></span>
                                                                                <span class="path5"></span>
                                                                            </i>
                                                                            Hapus
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-3">
                                                        <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-info">
                                                            <i class="ki-duotone ki-plus fs-4"></i>
                                                            Tambah Terapi Fisio
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!--end::Accordion Item 3-->
                            </div>
                            <!--end::Accordion Sections-->
                            @endif

                            <!--end::Underline-->
                            @if (auth()->user()->idpriv == 14 || auth()->user()->idpriv == 18 || auth()->user()->idpriv == 29)
                                <!--begin::Nurse Accordion-->
                                <div class="accordion accordion-icon-toggle" id="kt_accordion_nurse">

                                    <!--begin::Accordion Item 1 - Anamnesa & Kondisi-->
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="kt_accordion_nurse_header_1">
                                            <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#kt_accordion_nurse_body_1" aria-expanded="false">
                                                <i class="ki-duotone ki-pulse fs-2 me-2 text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                1. Anamnesa & Kondisi Pasien
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_nurse_body_1" class="accordion-collapse collapse"
                                            aria-labelledby="kt_accordion_nurse_header_1" data-bs-parent="#kt_accordion_nurse">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold fs-6">Anamnesa</label>
                                                    <textarea name="anamnesa" rows="2" class="form-control form-control-sm" placeholder="Alasan Masuk Rumah Sakit"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold fs-6">Obat Yang Dikonsumsi</label>
                                                    <textarea name="obat_yang_dikonsumsi" rows="2" class="form-control form-control-sm" placeholder="Obat yang sedang dikonsumsi"></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold fs-6">Pasien Dalam Kondisi Tertentu</label>
                                                    <input name="pasien_sedang" class="form-control form-control-sm" placeholder="Kondisi khusus pasien">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--begin::Accordion Item 2 - Alergi-->
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="kt_accordion_nurse_header_2">
                                            <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#kt_accordion_nurse_body_2" aria-expanded="false">
                                                <i class="ki-duotone ki-shield-cross fs-2 me-2 text-danger">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                2. Riwayat Alergi
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_nurse_body_2" class="accordion-collapse collapse"
                                            aria-labelledby="kt_accordion_nurse_header_2" data-bs-parent="#kt_accordion_nurse">
                                            <div class="accordion-body">
                                                <div class="row g-2 mb-2">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="obat" id="obat" />
                                                            <label class="form-check-label fw-bold" for="obat">Obat</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" name="value_obat" id="value_obat"
                                                            class="form-control form-control-sm" placeholder="Sebutkan obat..." style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="makanan" id="makanan" />
                                                            <label class="form-check-label fw-bold" for="makanan">Makanan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" name="value_makanan" id="value_makanan"
                                                            class="form-control form-control-sm" placeholder="Sebutkan makanan..." style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="lain" id="lain" />
                                                            <label class="form-check-label fw-bold" for="lain">Lain-lain</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <input type="text" name="value_lain" id="value_lain"
                                                            class="form-control form-control-sm" placeholder="Sebutkan lainnya..." style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--begin::Accordion Item 3 - Vital Signs-->
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="kt_accordion_nurse_header_3">
                                            <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#kt_accordion_nurse_body_3" aria-expanded="false">
                                                <i class="ki-duotone ki-heart-pulse fs-2 me-2 text-success">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                3. Pemeriksaan Fisik & Vital Signs
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_nurse_body_3" class="accordion-collapse collapse"
                                            aria-labelledby="kt_accordion_nurse_header_3" data-bs-parent="#kt_accordion_nurse">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Tekanan Darah</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="tekanan_darah" placeholder="120/80" />
                                                            <span class="input-group-text">mmHg</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Nadi</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="nadi" placeholder="80" />
                                                            <span class="input-group-text">x/mnt</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Pernapasan</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="pernapasan" placeholder="20" />
                                                            <span class="input-group-text">x/mnt</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Suhu</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="suhu" placeholder="36.5" />
                                                            <span class="input-group-text">°C</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Berat Badan</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" onkeyup="calculateBMI()"
                                                                id="berat_badan_val" name="berat_badan" placeholder="70" />
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">Tinggi Badan</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" onkeyup="calculateBMI()"
                                                                id="tinggi_badan_val" name="tinggi_badan" placeholder="170" />
                                                            <span class="input-group-text">Cm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">BMI</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" id='bmi_val' name="bmi" placeholder="Auto" readonly />
                                                            <span class="input-group-text">Kg/M²</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold fs-7">SpO2</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="spo2" placeholder="98" />
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--begin::Accordion Item 4 - Riwayat Kesehatan-->
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="kt_accordion_nurse_header_4">
                                            <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#kt_accordion_nurse_body_4" aria-expanded="false">
                                                <i class="ki-duotone ki-notepad fs-2 me-2 text-warning">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                                4. Riwayat Kesehatan
                                            </button>
                                        </h2>
                                        <div id="kt_accordion_nurse_body_4" class="accordion-collapse collapse"
                                            aria-labelledby="kt_accordion_nurse_header_4" data-bs-parent="#kt_accordion_nurse">
                                            <div class="accordion-body">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold fs-7">Riwayat penyakit yang lalu</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="1" id="riwayat-1" name="riwayat_1" />
                                                                <label class="form-check-label fs-7" for="riwayat-1">Ya</label>
                                                            </div>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="5" name="riwayat_1" />
                                                                <label class="form-check-label fs-7">Tidak</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="value_riwayat_1" id="value_riwayat_1"
                                                            class="form-control form-control-sm" placeholder="Sebutkan..." style="display: none;">
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold fs-7">Pernah dirawat</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="1" id="riwayat-2" name="riwayat_2" />
                                                                <label class="form-check-label fs-7" for="riwayat-2">Ya</label>
                                                            </div>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="0" name="riwayat_2" />
                                                                <label class="form-check-label fs-7">Tidak</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="value_riwayat_2" id="value_riwayat_2"
                                                            class="form-control form-control-sm" placeholder="Sebutkan..." style="display: none;">
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold fs-7">Pernah dioperasi</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="1" id="riwayat-3" name="riwayat_3" />
                                                                <label class="form-check-label fs-7" for="riwayat-3">Ya</label>
                                                            </div>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="0" name="riwayat_3" />
                                                                <label class="form-check-label fs-7">Tidak</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="value_riwayat_3" id="value_riwayat_3"
                                                            class="form-control form-control-sm" placeholder="Sebutkan..." style="display: none;">
                                                    </div>
                                                </div>

                                                <div class="row g-2">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold fs-7">Dalam pengobatan khusus</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="1" id="riwayat-4" name="riwayat_4" />
                                                                <label class="form-check-label fs-7" for="riwayat-4">Ya</label>
                                                            </div>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" value="0" name="riwayat_4" />
                                                                <label class="form-check-label fs-7">Tidak</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" name="value_riwayat_4" id="value_riwayat_4"
                                                            class="form-control form-control-sm" placeholder="Sebutkan..." style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Nurse Accordion-->
                            @endif
                            @if (auth()->user()->idpriv == 7)
                                <!--begin::Underline-->
                                {{-- <span class="d-inline-block position-relative mb-7">
                                    <!--begin::Label-->
                                    <span class="d-inline-block mb-2 fs-4 fw-bold">
                                        Rencana Pemeriksaan
                                    </span>
                                    <!--end::Label-->

                                    <!--begin::Line-->
                                    <span
                                        class="d-inline-block position-absolute h-5px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                    <!--end::Line-->
                                </span>
                                <!--end::Underline-->
                                <div class="row mb-5">
                                    <!--begin::Repeater-->
                                    <div id="radiologi_repeater">
                                        <!--begin::Form group-->
                                        <div class="form-group">
                                            <div data-repeater-list="radiologi">
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-5">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Tindakan Radiologi</label>
                                                            <select name="tindakan_rad" class="form-select"
                                                                data-kt-repeater="select2radiologi"
                                                                data-placeholder="-Pilih-" required>
                                                                <option></option>
                                                                @foreach ($radiologi as $rad)
                                                                    <option value="{{ $rad->id }}">
                                                                        {{ $rad->nama_tindakan }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label" for="">Klinis</label>
                                                            <input type="text" name="klinis" required  class="form-control" id="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:;" data-repeater-delete
                                                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                <i class="ki-duotone ki-trash fs-5"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span><span
                                                                        class="path3"></span><span
                                                                        class="path4"></span><span
                                                                        class="path5"></span></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Form group-->

                                        <!--begin::Form group-->
                                        <div class="form-group mt-5">
                                            <a href="javascript:;" data-repeater-create class="btn btn-light-success">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                Tambah Radiologi
                                            </a>
                                        </div>
                                        <!--end::Form group-->
                                    </div>
                                    <!--end::Repeater-->
                                </div>
                                <div class="row mb-5">
                                    <!--begin::Repeater-->
                                    <div id="lab_repeater">
                                        <!--begin::Form group-->
                                        <div class="form-group">
                                            <div data-repeater-list="lab">
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-5">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Tindakan Lab</label>
                                                            <select name="tindakan_lab" class="form-select"
                                                                data-kt-repeater="select2lab" data-placeholder="-Pilih-"
                                                                required>
                                                                <option></option>
                                                                @foreach ($lab as $lab)
                                                                    <option value="{{ $lab->id }}">
                                                                        {{ $lab->nama_pemeriksaan }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete
                                                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                <i class="ki-duotone ki-trash fs-5"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span><span
                                                                        class="path3"></span><span
                                                                        class="path4"></span><span
                                                                        class="path5"></span></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Form group-->

                                        <!--begin::Form group-->
                                        <div class="form-group mt-5">
                                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                Tambah Lab
                                            </a>
                                        </div>
                                        <!--end::Form group-->
                                    </div>
                                    <!--end::Repeater-->
                                </div> --}}
                                @if ($rawat->idpoli == 12)
                                @endif
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <textarea name="rencana_pemeriksaan" rows="3" class="form-control"
                                            placeholder="Hasil Pemeriksaan Penunjang (yang relevan dengan diagnosis dan terapi)"></textarea>
                                    </div>
                                </div>
                                <!--begin::Underline-->
                                {{-- <span class="d-inline-block position-relative mb-7">
                                    <!--begin::Label-->
                                    <span class="d-inline-block mb-2 fs-4 fw-bold">
                                        Terapi
                                    </span>
                                    <!--end::Label-->

                                    <!--begin::Line-->
                                    <span
                                        class="d-inline-block position-absolute h-5px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                    <!--end::Line-->
                                </span> --}}
                                <!--end::Underline-->
                                <div class="row mb-5">
                                    {{-- <table class="table table-bordered fs-9 gs-2 gy-2 gx-2" id="kt_docs_repeater_basic">
                                        <thead class="text-center align-middle">
                                            <tr>
                                                <th rowspan="2">Nama Obat</th>
                                                <th rowspan="2" width=100>Jumlah</th>
                                                <th rowspan="2" width=100>Dosis</th>
                                                <th rowspan="2" width=200>Takaran</th>
                                                <th width=50 colspan="3">Signa</th>
                                                <th rowspan="2" width=100>Diminum</th>
                                                <th rowspan="2" width=100>Catatan</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th width=10>P</th>
                                                <th width=10>S</th>
                                                <th width=10>M</th>
                                            </tr>

                                        </thead>
                                        <tbody data-repeater-list="terapi_obat" class="align-middle">
                                            <tr data-repeater-item>
                                                <td>
                                                    <select name="obat" multiple="multiple" class="form-select form-select-sm" data-kt-repeater="select2"
                                                        data-placeholder="-Pilih-" required>
                                                        @foreach ($obat as $val)
                                                        <option value="{{ $val->id }}">
                                                            {{ $val->nama_obat }} - {{ $val->satuan->satuan }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="jumlah_obat" step=".01" class="form-control form-control-sm mb-2 mb-md-0"
                                                        data-kt-repeater="tagify" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="dosis_obat"  placeholder="dosis"
                                                        class="form-control form-control-sm  mb-2 mb-md-0" min="0">
                                                </td>
                                                <td>
                                                    <select name="takaran_obat" required class="form-select form-select-sm">
                                                        <option value="">Pilih Takaran</option>
                                                        <option value="tablet">tablet</option>
                                                        <option value="kapsul">kapsul</option>
                                                        <option value="bungkus">bungkus</option>
                                                        <option value="tetes">tetes</option>
                                                        <option value="ml">ml</option>
                                                        <option value="sendok takar 5ml">sendok takar 5ml</option>
                                                        <option value="sendok takar 15ml">sendok takar 15ml</option>
                                                        <option value="Oles">Oles</option>
                                                    </select>

                                                </td>
                                                <td class="text-center align-middle"><input name="diminum" class="form-check-input form-check-input-sm" type="checkbox"
                                                        value="P" id="flexCheckDefault" /></td>
                                                <td class="text-center align-middle"><input class="form-check-input form-check-input-sm" type="checkbox"
                                                        value="S" name="diminum" id="flexCheckDefault" /></td>
                                                <td class="text-center align-middle"><input class="form-check-input form-check-input-sm" type="checkbox"
                                                        value="M" name="diminum" id="flexCheckDefault" /></td>
                                                <td>
                                                    <div class="form-check form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="takaran" id="tablet" value="sebelum">
                                                        <label class="form-check-label" for="tablet">Sebelum</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="takaran" id="kapsul" value="sesudah">
                                                        <label class="form-check-label" for="kapsul">Sesudah</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="catatan" class="form-control form-control-sm mb-2 mb-md-0" min="0">
                                                </td>
                                                <td>
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                        <i class="ki-duotone ki-trash fs-5">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>
                                                    </a>
                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                                                        <i class="ki-duotone ki-plus fs-3"></i>
                                                        Tambah Obat
                                                    </a></td>
                                            </tr>
                                        </tfoot>
                                    </table> --}}
                                    <!--begin::Repeater-->
                                    {{-- <div id="kt_docs_repeater_basic">
                                        <!--begin::Form group-->
                                        <div class="form-group">
                                            <div data-repeater-list="terapi_obat">
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-5">
                                                        <div class="col-md-8">
                                                            <div class="inner-repeater">
                                                                <div data-repeater-list="terapi_obat_racikan"
                                                                    class="">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                            <div class="col-md-8">
                                                                                <label class="form-label">Obat</label>
                                                                                <select name="obat" class="form-select"
                                                                                    data-kt-repeater="select2"
                                                                                    data-placeholder="-Pilih-" required>
                                                                                    <option></option>
                                                                                    @foreach ($obat as $val)
                                                                                        <option
                                                                                            value="{{ $val->id }}">
                                                                                            {{ $val->nama_obat }}
                                                                                            - {{ $val->satuan->satuan }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="form-label">Jumlah</label>
                                                                                <div class="input-group pb-3">

                                                                                    <input type="number"
                                                                                        name="jumlah_obat" step=".01"
                                                                                        class="form-control mb-5 mb-md-0"
                                                                                        min="0" required>
                                                                                        <input type="text"
                                                                                            name="dosis_obat" placeholder="dosis"
                                                                                            class="form-control mb-5 mb-md-0"
                                                                                            min="0" >
                                                                                    <button
                                                                                        class="border border-secondary input-group-text btn btn-icon btn-flex btn-light-danger"
                                                                                        data-repeater-delete
                                                                                        type="button">
                                                                                        <i
                                                                                            class="ki-duotone ki-trash fs-5"><span
                                                                                                class="path1"></span><span
                                                                                                class="path2"></span><span
                                                                                                class="path3"></span><span
                                                                                                class="path4"></span><span
                                                                                                class="path5"></span></i>
                                                                                    </button>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                    </div>



                                                                </div>
                                                                <button class="btn btn-sm btn-flex btn-light-success"
                                                                    data-repeater-create type="button">
                                                                    <i class="ki-duotone ki-plus fs-5"></i>
                                                                    Tambah Racikan
                                                                </button>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Signa</label>
                                                            <div class="input-group mb-5">
                                                                <input type="text" class="form-control" name='signa1'
                                                                    placeholder="...." aria-label="Username">
                                                                <span class="input-group-text">-</span>
                                                                <input type="text" class="form-control" name='signa2'
                                                                    placeholder="...." aria-label="Server">
                                                                <span class="input-group-text">-</span>
                                                                <input type="text" class="form-control" name='signa3'
                                                                    placeholder="...." aria-label="Server">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete
                                                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                <i class="ki-duotone ki-trash fs-5"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span><span
                                                                        class="path3"></span><span
                                                                        class="path4"></span><span
                                                                        class="path5"></span></i>
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Form group-->

                                        <!--begin::Form group-->
                                        <div class="form-group mt-5">
                                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                Tambah Obat
                                            </a>
                                        </div>
                                        <!--end::Form group-->
                                    </div> --}}
                                    <!--end::Repeater-->
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <textarea name="terapi" data-kt-autosize="true" rows="3" class="form-control"
                                            placeholder="Tindakan , Rehabilitasi dan Diet"></textarea>
                                    </div>
                                </div> --}}
                            @endif
                    </div>
                    <!--end::Body-->

                    <!--begin::Sticky Footer-->
                    <div class="card-footer bg-light border-top sticky-bottom" style="position: sticky; bottom: 0; z-index: 95; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted fs-7">
                                    <i class="ki-duotone ki-information-5 fs-5 text-primary me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    Pastikan semua data wajib sudah terisi
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('rekam-medis-poli', $data->idrawat) }}" class="btn btn-light btn-sm">
                                    <i class="ki-duotone ki-arrow-left fs-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="ki-duotone ki-check fs-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Simpan Rekam Medis
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <!--end::Sticky Footer-->
                </div>
                <!--end::FAQ card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('assets/js/custom/blokUi.js') }}"></script>
    <script>
        $(function() {

            alergi();
            riwayat_kesehatan();
            calculateBMI();

            $("#frm-data").on("submit", function(event) {
                event.preventDefault();

                // Validasi SOAP untuk dokter
                @if (auth()->user()->idpriv == 7)
                var soapSubjective = $('textarea[name="soap[subjective]"]').val();
                var soapObjective = $('textarea[name="soap[objective]"]').val();
                var soapAssessment = $('textarea[name="soap[assessment]"]').val();
                var soapPlan = $('textarea[name="soap[plan]"]').val();

                if (!soapSubjective || !soapObjective || !soapAssessment || !soapPlan) {
                    Swal.fire({
                        text: 'Semua field SOAP (Subjective, Objective, Assessment, Plan) wajib diisi!',
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return false;
                }

                // Konversi SOAP ke JSON dan simpan ke hidden input
                var soapData = {
                    subjective: soapSubjective,
                    objective: soapObjective,
                    assessment: soapAssessment,
                    plan: soapPlan,
                    created_at: new Date().toISOString()
                };

                // Hapus field SOAP individual dan ganti dengan JSON
                $('textarea[name="soap[subjective]"]').remove();
                $('textarea[name="soap[objective]"]').remove();
                $('textarea[name="soap[assessment]"]').remove();
                $('textarea[name="soap[plan]"]').remove();

                // Tambahkan hidden input dengan data JSON
                $('#frm-data').append('<input type="hidden" name="soap_data" value=\'' + JSON.stringify(soapData) + '\'>');
                @endif

                // Validasi ICDX minimal 1
                var icdxItems = $('#icdx_repeater [data-repeater-item]:visible');
                var icdxFilled = 0;

                icdxItems.each(function() {
                    var diagnosaValue = $(this).find('.icdx-diagnosa').val();
                    if (diagnosaValue && diagnosaValue !== '') {
                        icdxFilled++;
                    }
                });

                if (icdxFilled === 0) {
                    Swal.fire({
                        text: 'Minimal 1 diagnosa ICD X harus diisi!',
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return false;
                }

                var blockUI = new KTBlockUI(document.querySelector("#kt_app_body"));
                Swal.fire({
                    title: 'Simpan Data',
                    text: "Apakah Anda yakin akan menyimpan data ini ?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan Data',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.blockUI({
                            css: {
                                border: 'none',
                                padding: '15px',
                                backgroundColor: '#000',
                                '-webkit-border-radius': '10px',
                                '-moz-border-radius': '10px',
                                opacity: .5,
                                color: '#fff',
                                fontSize: '16px'
                            },
                            message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Tunggu . . .",
                            baseZ: 9000,
                        });
                        this.submit();
                    }
                });
            });

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: true,

                repeaters: [{
                    selector: '.inner-repeater',
                    show: function() {
                        $(this).slideDown();
                        $(this).find('[data-kt-repeater="select2"]').select2();
                    },

                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }],
                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2"]').select2();
                    new Tagify(this.querySelector('[data-kt-repeater="tagify"]'));
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2"]').select2();
                    new Tagify(document.querySelector('[data-kt-repeater="tagify"]'));

                }
            });

            $('#radiologi_repeater').repeater({
                initEmpty: true,

                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2radiologi"]').select2();
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2radiologi"]').select2();
                }
            });

            $('#icd9_repeater').repeater({
                initEmpty: true,

                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2icd9"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listprosedur2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {

                                return {
                                    q: params.term, // search term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(user) {
                                        return {
                                            id: user.id,
                                            text: user.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Search for a user...'
                    });
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2icd9"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listprosedur2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {

                                return {
                                    q: params.term, // search term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(user) {
                                        return {
                                            id: user.id,
                                            text: user.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Search for a user...'
                    });
                }
            });

            $('#lab_repeater').repeater({
                initEmpty: true,

                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2lab"]').select2();
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2lab"]').select2();
                }
            });
            @if ($rawat->idpoli == 12)
                $('#fisio_repeater').repeater({
                    initEmpty: true,

                    show: function() {
                        $(this).slideDown();

                        $(this).find('[data-kt-repeater="select2fisio"]').select2();
                    },

                    hide: function(deleteElement) {
                        $(this).slideUp(deleteElement);
                    },

                    ready: function() {
                        $('[data-kt-repeater="select2fisio"]').select2();
                    }
                });
            @endif
            $('#icdx_repeater').repeater({
                initEmpty: false, // Minimal 1 item harus ada

                show: function() {
                    $(this).slideDown();

                    // Inisialisasi Select2
                    $(this).find('[data-kt-repeater="select22"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listdiagnosa2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.result.map(function(user) {
                                        return {
                                            id: user.id,
                                            text: user.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Cari diagnosa ICD X...'
                    });

                    // Update jenis diagnosa setelah item ditambahkan
                    updateICDXJenisDiagnosa();
                },

                hide: function(deleteElement) {
                    // Cek jumlah item sebelum hapus
                    var itemCount = $('#icdx_repeater [data-repeater-item]').length;

                    if (itemCount <= 1) {
                        Swal.fire({
                            text: 'Minimal 1 diagnosa ICD X harus ada!',
                            icon: "warning",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        return false;
                    }

                    $(this).slideUp(deleteElement, function() {
                        // Update jenis diagnosa setelah item dihapus
                        updateICDXJenisDiagnosa();
                    });
                },

                ready: function() {
                    // Inisialisasi Select2 untuk item yang sudah ada
                    $('[data-kt-repeater="select22"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listdiagnosa2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.result.map(function(user) {
                                        return {
                                            id: user.id,
                                            text: user.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Cari diagnosa ICD X...'
                    });

                    // Set jenis diagnosa awal
                    updateICDXJenisDiagnosa();
                }
            });

            // Fungsi untuk update jenis diagnosa (Primer/Sekunder)
            function updateICDXJenisDiagnosa() {
                var items = $('#icdx_repeater [data-repeater-item]:visible');
                var itemCount = items.length;

                items.each(function(index) {
                    var jenisSelect = $(this).find('.icdx-jenis-diagnosa');
                    var deleteBtn = $(this).find('.icdx-delete-btn');

                    // Hapus badge yang ada sebelum menambahkan yang baru
                    jenisSelect.closest('.fv-row').find('.badge').remove();

                    if (index === 0) {
                        // Item pertama = Primer (tidak bisa diubah)
                        jenisSelect.val('P');
                        jenisSelect.prop('disabled', true);
                        jenisSelect.attr('data-jenis', 'primer');

                        // Tambahkan badge info Primer
                        jenisSelect.closest('.fv-row').append(
                            '<div class="mt-2"><span class="badge badge-light-primary">Diagnosa Primer (Utama)</span></div>'
                        );
                    } else {
                        // Item selanjutnya = Sekunder (otomatis)
                        jenisSelect.val('S');
                        jenisSelect.prop('disabled', true);
                        jenisSelect.attr('data-jenis', 'sekunder');

                        // Tambahkan badge info Sekunder
                        jenisSelect.closest('.fv-row').append(
                            '<div class="mt-2"><span class="badge badge-light-info">Diagnosa Sekunder</span></div>'
                        );
                    }

                    // Disable delete button jika hanya ada 1 item
                    if (itemCount <= 1) {
                        deleteBtn.addClass('disabled').attr('disabled', true);
                        deleteBtn.attr('title', 'Minimal 1 diagnosa harus ada');
                    } else {
                        deleteBtn.removeClass('disabled').removeAttr('disabled');
                        deleteBtn.removeAttr('title');
                    }
                });

                // Update badge info
                updateICDXBadgeCount();
            }

            // Fungsi untuk update badge jumlah diagnosa
            function updateICDXBadgeCount() {
                var totalItems = $('#icdx_repeater [data-repeater-item]:visible').length;
                var primerCount = $('#icdx_repeater [data-repeater-item]:visible .icdx-jenis-diagnosa[data-jenis="primer"]').length;
                var sekunderCount = totalItems - primerCount;

                // Tampilkan info di console untuk debugging
                console.log('Total ICDX:', totalItems, '| Primer:', primerCount, '| Sekunder:', sekunderCount);
            }

            // Monitor perubahan pada jenis diagnosa (prevent manual change)
            $(document).on('change', '.icdx-jenis-diagnosa', function() {
                // Refresh untuk memastikan tidak ada perubahan manual
                updateICDXJenisDiagnosa();
            });


        });

        function calculateBMI() {
            // alert($('#tinggi_badan').val())
            // Ambil nilai tinggi dan berat badan dari input
            var height = parseFloat($('#tinggi_badan_val').val()) || 0;
            var weight = parseFloat($('#berat_badan_val').val()) || 0;

            // Hitung BMI
            var bmi = weight / ((height / 100) * (height / 100));
            // alert(bmi)
            // Tampilkan hasil BMI
            $('#bmi_val').val(bmi.toFixed(2));
        }

        function riwayat_kesehatan() {
            $('input[type=radio][name="riwayat_1"]').change(function() {
                if ($(this).val() == '1') {
                    $('#value_riwayat_1').show();
                } else {
                    $('#value_riwayat_1').val("");
                    $('#value_riwayat_1').hide();
                }
            })
            $('input[type=radio][name="riwayat_2"]').change(function() {
                if ($(this).val() == '1') {
                    $('#value_riwayat_2').show();
                } else {
                    $('#value_riwayat_2').val("");
                    $('#value_riwayat_2').hide();
                }
            })
            $('input[type=radio][name="riwayat_3"]').change(function() {
                if ($(this).val() == '1') {
                    $('#value_riwayat_3').show();
                } else {
                    $('#value_riwayat_3').val("");
                    $('#value_riwayat_3').hide();
                }
            })
            $('input[type=radio][name="riwayat_4"]').change(function() {
                if ($(this).val() == '1') {
                    $('#value_riwayat_4').show();
                } else {
                    $('#value_riwayat_4').val("");
                    $('#value_riwayat_4').hide();
                }
            })
        }

        function alergi() {
            $('#obat').change(function() {
                if (this.checked) {
                    $('#value_obat').show();
                } else {
                    $('#value_obat').hide();
                }
            });

            $('#makanan').change(function() {
                if (this.checked) {
                    $('#value_makanan').show();
                } else {
                    $('#value_makanan').hide();
                }
            });

            $('#lain').change(function() {
                if (this.checked) {
                    $('#value_lain').show();
                } else {
                    $('#value_lain').hide();
                }
            });
        }

        @if ($message = session('gagal'))
            Swal.fire({
                text: '{{ $message }}',
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @endif
        @if ($message = session('berhasil'))
            Swal.fire({
                text: '{{ $message }}',
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @endif

        // Keyboard Shortcuts untuk User-Friendly Navigation
        document.addEventListener('keydown', function(e) {
            // Ctrl + S untuk Submit
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('frm-data').dispatchEvent(new Event('submit'));
            }

            // Ctrl + 1-4 untuk buka accordion (untuk dokter)
            if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '4') {
                e.preventDefault();
                const accordionNum = e.key;
                const targetAccordion = document.querySelector(`#kt_accordion_medical_body_${accordionNum}`);
                if (targetAccordion) {
                    const bsCollapse = new bootstrap.Collapse(targetAccordion, {
                        toggle: true
                    });
                }
            }

            // ESC untuk kembali
            if (e.key === 'Escape') {
                if (confirm('Yakin ingin kembali? Data yang belum disimpan akan hilang.')) {
                    window.location.href = '{{ route('rekam-medis-poli', $data->idrawat) }}';
                }
            }
        });

        // Auto-expand accordion yang memiliki error
        $(document).ready(function() {
            // Cek jika ada input required yang kosong saat submit
            $('#frm-data').on('invalid', function(e) {
                e.preventDefault();
                // Cari accordion terdekat dari input yang error
                const errorInput = $(e.target);
                const closestAccordion = errorInput.closest('.accordion-collapse');
                if (closestAccordion.length) {
                    closestAccordion.collapse('show');
                    // Scroll ke input yang error
                    setTimeout(function() {
                        errorInput[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        errorInput.focus();
                    }, 350);
                }
            }, true);

            // Tooltip untuk shortcuts
            const helpTooltip = `
                <div class="position-fixed" style="bottom: 70px; right: 20px; z-index: 90;">
                    <div class="badge badge-light-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                        title="Keyboard Shortcuts:\nCtrl+S = Simpan\nCtrl+1-4 = Buka Section\nESC = Kembali">
                        <i class="ki-duotone ki-information-5 fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Shortcuts
                    </div>
                </div>
            `;
            $('body').append(helpTooltip);

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    html: true
                });
            });
        });
    </script>
@endsection
