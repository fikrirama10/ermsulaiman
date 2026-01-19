@extends('layouts.index')
@section('css')
<style>
    /* Compact Form Styling */
    .form-control:read-only {
        background-color: #f5f8fa;
        cursor: not-allowed;
    }
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
    .patient-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
    }
    .info-row {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding: 0.75rem 0;
    }
    .info-row:last-child {
        border-bottom: none;
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
{{-- @dd($rekap_medis) --}}
@section('content')
    {{-- Check if user can edit based on role and status --}}
    @php
        $canEdit = false;
        $isCompleted = false;

        // Check if rawat exists and has status
        if(isset($rawat)) {
            $isCompleted = in_array($rawat->status, [4, 5]); // Status 4 or 5 means completed
        }

        // Determine edit permission based on role and signature status
        $userRole = auth()->user()->idpriv;
        $isDoctorSigned = false;
        $isNurseSigned = false;

        // Check signature status based on role
        if (in_array($userRole, [7])) {
            // Doctor role - check if doctor has signed
            $isDoctorSigned = ($rekap_medis->dokter == 1);
            if (!$isCompleted && !$isDoctorSigned) {
                $canEdit = true;
            }
        } elseif (in_array($userRole, [14, 18, 29])) {
            // Nurse role - check if nurse has signed
            $isNurseSigned = ($rekap_medis->perawat == 1);
            if (!$isCompleted && !$isNurseSigned) {
                $canEdit = true;
            }
        } elseif (in_array($userRole, [20])) {
            // Coder role - can edit if not completed
            if (!$isCompleted) {
                $canEdit = true;
            }
        }

        $readonlyAttr = $canEdit ? '' : 'readonly';
        $disabledAttr = $canEdit ? '' : 'disabled';

        // Parse SOAP data if exists
        $soap_data = null;
        if ($rekap->soap_data && $rekap->soap_data != 'null' && $rekap->soap_data != '[]') {
            $soap_data = json_decode($rekap->soap_data);
        }
    @endphp

    <div class="d-flex flex-column flex-column-fluid"
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Detail
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
                            @if($isCompleted)
                                <span class="badge badge-light-success me-3">
                                    <i class="ki-duotone ki-check-circle fs-5 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Sudah Diselesaikan
                                </span>
                            @else
                                <span class="badge badge-light-warning me-3">
                                    <i class="ki-duotone ki-information-5 fs-5 text-warning">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Belum Diselesaikan
                                </span>
                            @endif
                            <a href="{{ route('rekam-medis-poli', $rekap->idrawat) }}" class="btn btn-sm btn-light-secondary">
                                <i class="ki-duotone ki-arrow-left fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Kembali
                            </a>
                        </div>
                    </div>
                    <!--begin::Body-->
                    <div class="card-body p-lg-6">
                        @if(!$canEdit)
                            <div class="alert alert-warning d-flex align-items-center mb-7">
                                <i class="ki-duotone ki-shield-cross fs-2hx text-warning me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-dark">Mode Tampilan Saja</h4>
                                    <span>Rekam medis ini sudah diselesaikan atau Anda tidak memiliki akses untuk mengedit.</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('detail-rekap-medis-update', $rekap->id) }}" method="post"
                            autocomplete="off" id="frm-data">
                            @csrf
                            <input type="hidden" name="kategori" value="{{ $rekap->rekapMedis->kategori->id }}">

                            {{-- Kategori Alert --}}
                            <div class="alert bg-light-primary border border-primary border-dashed d-flex align-items-center p-5 mb-10">
                                <i class="ki-duotone ki-pulse fs-2hx text-primary me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <h3 class="mb-1 text-dark">{{ $rekap->rekapMedis->kategori->nama }}</h3>
                                    <span class="text-muted">Kategori Rekam Medis</span>
                                </div>
                            </div>

                            {{-- Patient Info Card --}}
                            <div class="card bg-light-primary mb-5">
                                <div class="card-body p-5">
                                    <h2 class="text-dark mb-5">
                                        <i class="ki-duotone ki-profile-user fs-2x me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        {{ $rekap->rekapMedis->pasien->nama_pasien }}
                                    </h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">NIK:</span>
                                                    <span class="fw-bold">{{ $rekap->rekapMedis->pasien->nik }}</span>
                                                </div>
                                            </div>
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">No. RM:</span>
                                                    <span class="fw-bold">{{ $rekap->rekapMedis->pasien->no_rm }}</span>
                                                </div>
                                            </div>
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">No. BPJS:</span>
                                                    <span class="fw-bold">{{ $rekap->rekapMedis->pasien->no_bpjs }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">Tgl. Lahir:</span>
                                                    <span class="fw-bold">
                                                        {{ $rekap->rekapMedis->pasien->tgllahir }}
                                                        ({{ $rekap->rekapMedis->pasien->usia_tahun }}Th {{ $rekap->rekapMedis->pasien->usia_bulan }}Bln {{ $rekap->rekapMedis->pasien->usia_hari }}Hr)
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">No. HP:</span>
                                                    <span class="fw-bold">{{ $rekap->rekapMedis->pasien->nohp }}</span>
                                                </div>
                                            </div>
                                            <div class="info-row">
                                                <div class="d-flex justify-content-between">
                                                    <span class="opacity-75">Alamat:</span>
                                                    <span class="fw-bold text-end">{{ $rekap->rekapMedis->pasien->alamat->alamat ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-10"></div>
                            @if (auth()->user()->idpriv == 20)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold required">Kategori Penyakit</label>
                                        <select class="form-select" data-control="select2" name="kategori_penyakit"
                                            data-placeholder="Pilih kategori penyakit" {{ $disabledAttr }}>
                                            <option></option>
                                            @foreach ($kategori_diagnosa as $kd)
                                                <option value="{{ $kd->id }}">{{ $kd->jenisdiagnosa }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <span class="d-inline-block position-relative mb-7">
                                <!--begin::Label-->
                                <span class="d-inline-block mb-2 fs-4 fw-bold">
                                    Anamnesa @if ($rawat->idjenisrawat == 3) & Pemeriksaan Fisik @endif
                                </span>
                                <!--end::Label-->

                                <!--begin::Line-->
                                <span
                                    class="d-inline-block position-absolute h-5px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                <!--end::Line-->
                            </span>
                            <!--end::Underline-->
                            @if (auth()->user()->idpriv == 7)
                            @if ($rawat->idjenisrawat == 3)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Anamnesa</label>
                                        <textarea name="anamnesa_dokter" data-kt-autosize="true" rows="3"
                                            class="form-control" placeholder="Masukkan anamnesa..." {{ $readonlyAttr }}>{{ $rekap->anamnesa_dokter }}</textarea>
                                    </div>
                                </div>
                            @endif
                                @if ($rawat->idjenisrawat == 3)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Pemeriksaan Fisik</label>
                                        <textarea name="pemeriksaan_fisik" rows="3"
                                            class="form-control" placeholder="Masukkan hasil pemeriksaan fisik..." {{ $readonlyAttr }}>{{ $rekap->pemeriksaan_fisik_dokter }}</textarea>
                                    </div>
                                </div>
                                @endif
                                @if ($rawat->idpoli == 12)
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Pemeriksaan Fisik</label>
                                            <textarea data-kt-autosize="true" name="pemeriksaan_fisik" rows="3" class="form-control"
                                                placeholder="Pemeriksaan Fisik" {{ $readonlyAttr }}>{{ $pemeriksaan_fisio->pemeriksaan_fisik }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Pemeriksaan Uji Fungsi</label>
                                            <textarea data-kt-autosize="true" name="pemeriksaan_uji_fungsi" rows="3" class="form-control"
                                                placeholder="Pemeriksaan Uji Fungsi" {{ $readonlyAttr }}>{{ $pemeriksaan_fisio->pemeriksaan_uji_fungsi }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Tata Laksanan KFR (ICD ) CM </label>
                                            <textarea data-kt-autosize="true" name="tata_laksana" rows="3" class="form-control"
                                                placeholder="Tata Laksana" {{ $readonlyAttr }}>{{ $pemeriksaan_fisio->tata_laksana }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Anjuran</label>
                                            <textarea data-kt-autosize="true" name="anjuran" rows="3" class="form-control"
                                                placeholder="Anjuran" {{ $readonlyAttr }}>{{ $pemeriksaan_fisio->anjuran }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Evaluasi</label>
                                            <textarea data-kt-autosize="true" name="evaluasi" rows="3" class="form-control"
                                                placeholder="Evaluasi" {{ $readonlyAttr }}>{{ $pemeriksaan_fisio->evaluasi }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <!--begin::Repeater-->
                                        <div id="fisio_repeater">
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="fisio">
                                                    @if ($rekap->fisio && $rekap->fisio != 'null' && $rekap->fisio != '[]')
                                                        {{-- {{ dd($rekap->laborat) }} --}}
                                                        @foreach (json_decode($rekap->fisio) ?? [] as $val)
                                                            <div data-repeater-item>
                                                                <div class="form-group row mb-5">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Fisio Terapi</label>
                                                                        {{-- <input type="text" name="tindakan_fisio" class="form-control" value="{{ $val->tindakan_fisio  }}" id=""> --}}
                                                                        <select name="tindakan_fisio" class="form-select"
                                                                            data-kt-repeater="select2fisio"
                                                                            data-placeholder="-Pilih-" required>
                                                                            <option></option>
                                                                            @foreach ($fisio as $f)
                                                                                <option value="{{ $f->id }}"
                                                                                    {{ $val->tindakan_fisio == $f->id ? 'selected' : '' }}>
                                                                                    {{ $f->nama_tarif }}</option>
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
                                                        @endforeach
                                                    @else
                                                        <div data-repeater-item>
                                                            <div class="form-group row mb-5">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Fisio Terapi</label>
                                                                    {{-- <input type="text" name="tindakan_fisio" class="form-control" value="" id=""> --}}
                                                                    <select name="tindakan_fisio" class="form-select"
                                                                        data-kt-repeater="select2fisio"
                                                                        data-placeholder="-Pilih-" required>
                                                                        <option></option>
                                                                        @foreach ($fisio as $fisio)
                                                                            <option value="{{ $fisio->id }}">
                                                                                {{ $fisio->nama_tarif }}</option>
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
                                                    @endif

                                                </div>
                                            </div>
                                            <!--end::Form group-->

                                            <!--begin::Form group-->
                                            <div class="form-group mt-5">
                                                <a href="javascript:;" data-repeater-create class="btn btn-light-info">
                                                    <i class="ki-duotone ki-plus fs-3"></i>
                                                    Tambah Fisio
                                                </a>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                        <!--end::Repeater-->
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
                                            data-bs-target="#kt_accordion_medical_body_1" aria-expanded="false" aria-controls="kt_accordion_medical_body_1" {{ $disabledAttr }}>
                                            <i class="ki-duotone ki-shield-tick fs-2 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            1. SOAP (Subjective, Objective, Assessment, Plan)
                                            <span class="badge badge-light-primary ms-3">{{ $soap_data ? 'Terisi' : 'Wajib' }}</span>
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_medical_body_1" class="accordion-collapse collapse {{ $soap_data ? 'show' : '' }}"
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
                                                        placeholder="Keluhan utama pasien, riwayat penyakit sekarang..." {{ $readonlyAttr }} required>{{ $soap_data->subjective ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-info me-2">O</span>
                                                        Objective (Pemeriksaan Fisik)
                                                    </label>
                                                    <textarea name="soap[objective]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Hasil pemeriksaan fisik, vital signs..." {{ $readonlyAttr }} required>{{ $soap_data->objective ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-warning me-2">A</span>
                                                        Assessment (Diagnosis)
                                                    </label>
                                                    <textarea name="soap[assessment]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Diagnosis kerja, diagnosis banding..." {{ $readonlyAttr }} required>{{ $soap_data->assessment ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">
                                                        <span class="badge badge-danger me-2">P</span>
                                                        Plan (Rencana Tindakan)
                                                    </label>
                                                    <textarea name="soap[plan]" rows="4" class="form-control form-control-sm"
                                                        placeholder="Rencana pemeriksaan, terapi medikamentosa..." {{ $readonlyAttr }} required>{{ $soap_data->plan ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Accordion Item 1-->

                                <!--begin::Accordion Item 2 - Diagnosis & ICD-->
                                <div class="accordion-item mb-3" id="kt_accordion_medical_item_2">
                                    <h2 class="accordion-header" id="kt_accordion_medical_header_2">
                                        <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#kt_accordion_medical_body_2" aria-expanded="false" aria-controls="kt_accordion_medical_body_2" {{ $disabledAttr }}>
                                            <i class="ki-duotone ki-abstract-26 fs-2 me-2 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            2. Diagnosis & Kode ICD
                                            @php
                                                $icdxData = $rekap->icdx && $rekap->icdx != 'null' && $rekap->icdx != '[]' ? json_decode($rekap->icdx) : null;
                                                $icd9Data = $rekap->icd9 && $rekap->icd9 != 'null' && $rekap->icd9 != '[]' ? json_decode($rekap->icd9) : null;
                                            @endphp
                                            <span class="badge badge-light-success ms-3">{{ $icdxData ? count($icdxData) . ' ICD-X' : 'Wajib ICD-X' }}</span>
                                            @if($icd9Data)
                                            <span class="badge badge-light-info ms-2">{{ count($icd9Data) }} ICD-9</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="kt_accordion_medical_body_2" class="accordion-collapse collapse {{ $icdxData ? 'show' : '' }}"
                                        aria-labelledby="kt_accordion_medical_header_2" data-bs-parent="#kt_accordion_medical">
                                        <div class="accordion-body">
                                            <!--begin::Diagnosa Text-->
                                            <div class="mb-5">
                                                <label class="form-label fw-bold required">Diagnosa</label>
                                                <textarea name="diagnosa" data-kt-autosize="true" rows="3"
                                                    class="form-control form-control-sm" placeholder="Masukkan diagnosa utama..." {{ $readonlyAttr }}>{{ $rekap->diagnosa }}</textarea>
                                            </div>
                                            <!--end::Diagnosa Text-->

                                            <!--begin::ICD-X Repeater-->
                                            <div class="mb-5">
                                                <label class="form-label fw-bold required">
                                                    <i class="ki-duotone ki-medical-records fs-3 text-success me-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Kode ICD-X (Diagnosis)
                                                </label>
                                                <div id="icdx_repeater">
                                                    <div class="form-group">
                                                        <div data-repeater-list="icdx">
                                                            @if($icdxData)
                                                                @foreach($icdxData as $index => $val)
                                                                <div data-repeater-item class="mb-3">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-7">
                                                                            <label class="form-label fs-7">ICD-X</label>
                                                                            <select name="diagnosa_icdx" class="form-select form-select-sm icdx-diagnosa"
                                                                                data-kt-repeater="select22" data-placeholder="Cari diagnosa ICD-X..." {{ $disabledAttr }}>
                                                                                <option value="{{ $val->diagnosa_icdx }}">{{ $val->diagnosa_icdx }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fs-7">Jenis</label>
                                                                            <select name="jenis_diagnosa" class="form-select form-select-sm icdx-jenis-diagnosa" {{ $disabledAttr }}>
                                                                                <option value="P" {{ $val->jenis_diagnosa == 'P' ? 'selected' : '' }}>Primer</option>
                                                                                <option value="S" {{ $val->jenis_diagnosa == 'S' ? 'selected' : '' }}>Sekunder</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fs-7 opacity-0">Aksi</label>
                                                                            <button type="button" data-repeater-delete
                                                                                class="btn btn-sm btn-light-danger w-100 icdx-delete-btn" {{ $disabledAttr }}>
                                                                                <i class="ki-duotone ki-trash fs-5">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                                Hapus
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                                <div data-repeater-item class="mb-3">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-7">
                                                                            <label class="form-label fs-7">ICD-X</label>
                                                                            <select name="diagnosa_icdx" class="form-select form-select-sm icdx-diagnosa"
                                                                                data-kt-repeater="select22" data-placeholder="Cari diagnosa ICD-X..." {{ $disabledAttr }}>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fs-7">Jenis</label>
                                                                            <select name="jenis_diagnosa" class="form-select form-select-sm icdx-jenis-diagnosa" {{ $disabledAttr }}>
                                                                                <option value="P" selected>Primer</option>
                                                                                <option value="S">Sekunder</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fs-7 opacity-0">Aksi</label>
                                                                            <button type="button" data-repeater-delete
                                                                                class="btn btn-sm btn-light-danger w-100 icdx-delete-btn" {{ $disabledAttr }}>
                                                                                <i class="ki-duotone ki-trash fs-5">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                                Hapus
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($canEdit)
                                                    <div class="form-group mt-3">
                                                        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            Tambah ICD-X
                                                        </button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--end::ICD-X Repeater-->

                                            <!--begin::ICD-9 Repeater-->
                                            <div class="mb-5">
                                                <label class="form-label fw-bold">
                                                    <i class="ki-duotone ki-pill fs-3 text-info me-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Kode ICD-9 (Prosedur/Tindakan)
                                                    <span class="badge badge-light-secondary ms-2">Opsional</span>
                                                </label>
                                                <div id="icd9_repeater">
                                                    <div class="form-group">
                                                        <div data-repeater-list="icd9">
                                                            @if($icd9Data)
                                                                @foreach($icd9Data as $val)
                                                                <div data-repeater-item class="mb-3">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-10">
                                                                            <label class="form-label fs-7">ICD-9</label>
                                                                            <select name="diagnosa_icd9" class="form-select form-select-sm"
                                                                                data-kt-repeater="select2icd9" data-placeholder="Cari prosedur ICD-9..." {{ $disabledAttr }}>
                                                                                <option value="{{ $val->diagnosa_icd9 }}">{{ $val->diagnosa_icd9 }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fs-7 opacity-0">Aksi</label>
                                                                            <button type="button" data-repeater-delete
                                                                                class="btn btn-sm btn-light-danger w-100" {{ $disabledAttr }}>
                                                                                <i class="ki-duotone ki-trash fs-5">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                                Hapus
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                                <div data-repeater-item class="mb-3">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-10">
                                                                            <label class="form-label fs-7">ICD-9</label>
                                                                            <select name="diagnosa_icd9" class="form-select form-select-sm"
                                                                                data-kt-repeater="select2icd9" data-placeholder="Cari prosedur ICD-9..." {{ $disabledAttr }}>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label class="form-label fs-7 opacity-0">Aksi</label>
                                                                            <button type="button" data-repeater-delete
                                                                                class="btn btn-sm btn-light-danger w-100" {{ $disabledAttr }}>
                                                                                <i class="ki-duotone ki-trash fs-5">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                                Hapus
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($canEdit)
                                                    <div class="form-group mt-3">
                                                        <button type="button" data-repeater-create class="btn btn-sm btn-light-info">
                                                            <i class="ki-duotone ki-plus fs-3"></i>
                                                            Tambah ICD-9
                                                        </button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--end::ICD-9 Repeater-->

                                            <!--begin::Tindakan/Prosedur-->
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Tindakan / Prosedur Medis</label>
                                                <textarea data-kt-autosize="true" name="tindakan_prc" rows="3"
                                                    class="form-control form-control-sm" placeholder="Masukkan tindakan atau prosedur medis..." {{ $readonlyAttr }}>{{ $rekap->prosedur }}</textarea>
                                            </div>
                                            <!--end::Tindakan/Prosedur-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Accordion Item 2-->

                            </div>
                            <!--end::Accordion Sections-->
                            @endif
                            <!--begin::Underline-->

                            @if (auth()->user()->idpriv == 20)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Diagnosa</label>
                                        <textarea name="diagnosa" data-kt-autosize="true" rows="3" class="form-control" placeholder="...">{{ $rekap->diagnosa }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <div id="icdx_repeater">
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="icdx">
                                                    @if ($rekap->icdx && $rekap->icdx != 'null' && $rekap->icdx != '[]')
                                                        {{-- {{ dd($rekap->laborat) }} --}}
                                                        @foreach (json_decode($rekap->icdx) ?? [] as $val)
                                                            <div data-repeater-item>
                                                                <div class="form-group row mb-5">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">ICD X</label>
                                                                        <select name="diagnosa_icdx" class="form-select"
                                                                            data-kt-repeater="select22"
                                                                            data-placeholder="-Pilih-" required>
                                                                            <option value="{{ $val->diagnosa_icdx }}">
                                                                                {{ $val->diagnosa_icdx }}</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label class="form-label">Jenis Diagnosa</label>
                                                                        <div class="input-group mb-5">
                                                                            <select name="jenis_diagnosa" required
                                                                                class="form-select" id="">
                                                                                <option
                                                                                    {{ $val->jenis_diagnosa == 'P' ? 'selected' : '' }}
                                                                                    value="P">Primer</option>
                                                                                <option
                                                                                    {{ $val->jenis_diagnosa == 'S' ? 'selected' : '' }}
                                                                                    value="S">Sekunder</option>
                                                                            </select>
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
                                                        @endforeach
                                                    @else
                                                        <div data-repeater-item>
                                                            <div class="form-group row mb-5">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">ICD X</label>
                                                                    <select name="diagnosa_icdx" class="form-select"
                                                                        data-kt-repeater="select22"
                                                                        data-placeholder="-Pilih-" required>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Jenis Diagnosa</label>
                                                                    <div class="input-group mb-5">
                                                                        <select name="jenis_diagnosa" class="form-select"
                                                                            id="">
                                                                            <option value="P">Primer</option>
                                                                            <option value="S">Sekunder</option>
                                                                        </select>
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
                                                    @endif

                                                </div>
                                            </div>
                                            <!--end::Form group-->

                                            <!--begin::Form group-->
                                            <div class="form-group mt-5">
                                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                                    <i class="ki-duotone ki-plus fs-3"></i>
                                                    Tambah ICD X
                                                </a>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Tindakan / Prosedur</label>
                                    <textarea data-kt-autosize="true" name="tindakan_prc" rows="3" class="form-control" placeholder="...">{{ $rekap->prosedur }}</textarea>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <div id="icd9_repeater">
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="icd9">
                                                    @if ($rekap->icd9 && $rekap->icd9 != 'null' && $rekap->icd9 != '[]')
                                                        {{-- {{ dd($rekap->laborat) }} --}}
                                                        @foreach (json_decode($rekap->icd9) ?? [] as $val)
                                                            <div data-repeater-item>
                                                                <div class="form-group row mb-5">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">ICD 9</label>
                                                                        <select name="diagnosa_icd9" class="form-select"
                                                                            data-kt-repeater="select2icd9"
                                                                            data-placeholder="-Pilih-" required>
                                                                            <option value="{{ $val->diagnosa_icd9 }}">
                                                                                {{ $val->diagnosa_icd9 }}</option>
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
                                                        @endforeach
                                                    @else
                                                        <div data-repeater-item>
                                                            <div class="form-group row mb-5">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">ICD 9</label>
                                                                    <select name="diagnosa_icd9" class="form-select"
                                                                        data-kt-repeater="select2icd9"
                                                                        data-placeholder="-Pilih-" required>
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
                                                    @endif

                                                </div>
                                            </div>
                                            <!--end::Form group-->

                                            <!--begin::Form group-->
                                            <div class="form-group mt-5">
                                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                                    <i class="ki-duotone ki-plus fs-3"></i>
                                                    Tambah ICD 9
                                                </a>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->idpriv == 14 || auth()->user()->idpriv == 18 || auth()->user()->idpriv == 29)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Anamnesa</label>
                                        <textarea name="anamnesa" rows="3"
                                            class="form-control" placeholder="Alasan Masuk Rumah Sakit" {{ $readonlyAttr }}>{{ $rekap->anamnesa }}</textarea>
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->idpriv == 14 || auth()->user()->idpriv == 18 || auth()->user()->idpriv == 29)
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Obat Yang Dikonsumsi</label>
                                        <textarea name="obat_yang_dikonsumsi" rows="3"
                                            class="form-control" placeholder="Masukkan obat yang dikonsumsi pasien..." {{ $readonlyAttr }}>{{ $rekap->obat_yang_dikonsumsi }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-5 p-lg-5">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h5 class="card-title">Alergi</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="obat"
                                                            id="obat" {{ $alergi->value_obat ? 'checked' : '' }} />
                                                        <label class="form-check-label" for="obat">
                                                            Obat
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="value_obat" id="value_obat"
                                                        class="form-control" value="{{ $alergi->value_obat }}"
                                                        placeholder="...."
                                                        style="display: {{ $alergi->value_obat ? '' : 'none' }};">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="makanan"
                                                            id="makanan"
                                                            {{ $alergi->value_makanan ? 'checked' : '' }} />
                                                        <label class="form-check-label" for="makanan">
                                                            Makanan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="value_makanan" id="value_makanan"
                                                        class="form-control" value="{{ $alergi->value_makanan }}"
                                                        placeholder="...."
                                                        style="display: {{ $alergi->value_makanan ? '' : 'none' }};">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="lain"
                                                            id="lain" {{ $alergi->value_lain ? 'checked' : '' }} />
                                                        <label class="form-check-label" for="lain">
                                                            Lain - Lain
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="value_lain" id="value_lain"
                                                        class="form-control"value="{{ $alergi->value_lain }}"
                                                        placeholder="...."
                                                        style="display: {{ $alergi->value_lain ? '' : 'none' }};">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Pasien Sedang</label>
                                        <input name="pasien_sedang" rows="3" class="form-control"
                                            placeholder="...." value="{{ $rekap->pasien_sedang }}">
                                    </div>
                                </div>
                                <div class="row mb-5 p-lg-5">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h5 class="card-title">Pemeriksaan Fisik</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row md-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tekanan Darah</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control" name="tekanan_darah"
                                                            value="{{ $pfisik->tekanan_darah }}" placeholder="...."
                                                            aria-label="...." aria-describedby="tdarah" />
                                                        <span class="input-group-text" id="tdarah">mmHg</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nadi</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control" name="nadi"
                                                            value="{{ $pfisik->nadi }}" placeholder="...."
                                                            aria-label="...." aria-describedby="nadi" />
                                                        <span class="input-group-text" id="nadi">x/Menit</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Pernapasan</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control" name="pernapasan"
                                                            value="{{ $pfisik->pernapasan }}" placeholder="...."
                                                            aria-label="...." aria-describedby="pernapasan" />
                                                        <span class="input-group-text" id="pernapasan">x/Menit</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row md-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Suhu</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control" name="suhu"
                                                            value="{{ $pfisik->suhu }}" placeholder="...."
                                                            aria-label="...." aria-describedby="suhu" />
                                                        <span class="input-group-text" id="suhu">Derajat</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Berat Badan</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control"
                                                            onkeyup="calculateBMI()" id='berat_badan_val'
                                                            name="berat_badan" value="{{ $pfisik->berat_badan }}"
                                                            placeholder="...." aria-label="...."
                                                            aria-describedby="berat_badan" />
                                                        <span class="input-group-text" id="berat_badan">Kg</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tinggi Badan</label>
                                                    <div class="input-group mb-5">
                                                        <input type="text" class="form-control"
                                                            onkeyup="calculateBMI()" id='tinggi_badan_val'
                                                            name="tinggi_badan" value="{{ $pfisik->tinggi_badan }}"
                                                            placeholder="...." aria-label="....e"
                                                            aria-describedby="tinggi_badan" />
                                                        <span class="input-group-text" id="tinggi_badan">Cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row col-md-4">
                                                <label class="form-label">BMI</label>
                                                <div class="input-group mb-5">
                                                    <input type="text" class="form-control" id='bmi_val'
                                                        name="bmi" value="{{ $pfisik->bmi }}" placeholder="...."
                                                        aria-label="....e" aria-describedby="bmi" />
                                                    <span class="input-group-text" id="bmi">Kg/M2</span>
                                                </div>
                                            </div>
                                            <div class="row col-md-4">
                                                <label class="form-label">saturasi oksigen (SpO2) </label>
                                                <div class="input-group mb-5">
                                                    <input type="text" class="form-control"
                                                        name="spo2" value="{{ isset($pfisik->spo2) ? $pfisik->spo2:'' }}" placeholder="...." aria-label="....e"
                                                        aria-describedby="spo2" />
                                                    <span class="input-group-text" id="spo2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5 p-lg-5">
                                    <div class="card card-bordered">
                                        <div class="card-header">
                                            <h5 class="card-title">Riwayat Kesehatan</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Riwayat penyakit yang lalu</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="1"
                                                                id="riwayat-1" name="riwayat_1"
                                                                {{ $rkesehatan->riwayat_1 == 1 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Ya
                                                            </label>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="0"
                                                                id="riwayat-1" name="riwayat_1"
                                                                {{ $rkesehatan->riwayat_1 == 0 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="value_riwayat_1"
                                                        value="{{ $rkesehatan->value_riwayat_1 }}" id="value_riwayat_1"
                                                        class="form-control" placeholder="...."
                                                        style="display: {{ $rkesehatan->riwayat_1 != 0 ? '' : 'none' }};">
                                                </div>
                                            </div>

                                            <div class="separator separator-dashed border-secondary mb-5"></div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Pernah dirawat</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="1"
                                                                id="riwayat-2" name="riwayat_2"
                                                                {{ $rkesehatan->riwayat_2 == 1 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Ya
                                                            </label>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="0"
                                                                id="riwayat-2" name="riwayat_2"
                                                                {{ $rkesehatan->riwayat_2 == 0 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="value_riwayat_2"
                                                        value="{{ $rkesehatan->value_riwayat_2 }}" id="value_riwayat_2"
                                                        class="form-control" placeholder="...."
                                                        style="display: {{ $rkesehatan->riwayat_2 != 0 ? '' : 'none' }};">
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed border-secondary mb-5"></div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Pernah dioperasi</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="1"
                                                                id="riwayat-3" name="riwayat_3"
                                                                {{ $rkesehatan->riwayat_3 == 1 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Ya
                                                            </label>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="0"
                                                                id="riwayat-3" name="riwayat_3"
                                                                {{ $rkesehatan->riwayat_3 == 0 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="value_riwayat_3"
                                                        value="{{ $rkesehatan->value_riwayat_3 }}" id="value_riwayat_3"
                                                        class="form-control" placeholder="...."
                                                        style="display: {{ $rkesehatan->riwayat_3 != 0 ? '' : 'none' }};">
                                                </div>
                                            </div>
                                            <div class="separator separator-dashed border-secondary mb-5"></div>
                                            <div class="row mb-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Dalam pengobatan khusus</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="d-flex justify-content-start">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="1"
                                                                id="riwayat-4" name="riwayat_4"
                                                                {{ $rkesehatan->riwayat_4 == 1 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Ya
                                                            </label>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="radio" value="0"
                                                                id="riwayat-4" name="riwayat_4"
                                                                {{ $rkesehatan->riwayat_4 == 0 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="value_riwayat_4"
                                                        value="{{ $rkesehatan->value_riwayat_4 }}" id="value_riwayat_4"
                                                        class="form-control" placeholder="...."
                                                        style="display: {{ $rkesehatan->riwayat_4 != 0 ? '' : 'none' }};">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!--begin::Underline-->
                            @if (auth()->user()->idpriv == 7)
                                <span class="d-inline-block position-relative mb-7">
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
                                    <div class="col-md-12">
                                        <textarea name="rencana_pemeriksaan" rows="3" class="form-control"
                                            placeholder="Hasil Pemeriksaan Penunjang (yang relevan dengan diagnosis dan terapi)" {{ $readonlyAttr }}>{{ $rekap->rencana_pemeriksaan }}</textarea>
                                    </div>
                                </div>
                                <!--begin::Underline-->
                                <span class="d-inline-block position-relative mb-7 d-none">
                                    <!--begin::Label-->
                                    <span class="d-inline-block mb-2 fs-4 fw-bold">
                                        Terapi
                                    </span>
                                    <!--end::Label-->

                                    <!--begin::Line-->
                                    <span
                                        class="d-inline-block position-absolute h-5px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                    <!--end::Line-->
                                </span>
                                <!--end::Underline-->
                                <div class="row mb-5">

                                </div>

                            @endif
                    </div>
                    <!--end::Body-->

                    <!--begin::Sticky Footer-->
                    <div class="card-footer bg-light border-top sticky-bottom" style="position: sticky; bottom: 0; z-index: 95; box-shadow: 0 -2px 10px rgba(0,0,0,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($isDoctorSigned)
                                    <span class="badge badge-light-primary fs-6">
                                        <i class="ki-duotone ki-verify fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Telah Ditandatangani Dokter - Tidak Dapat Diedit
                                    </span>
                                @elseif($isNurseSigned)
                                    <span class="badge badge-light-success fs-6">
                                        <i class="ki-duotone ki-verify fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Telah Ditandatangani Perawat - Tidak Dapat Diedit
                                    </span>
                                @elseif($isCompleted)
                                    <span class="badge badge-light-info fs-6">
                                        <i class="ki-duotone ki-check-circle fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Rawat Jalan Selesai - Tidak Dapat Diedit
                                    </span>
                                @elseif(!$canEdit)
                                    <span class="badge badge-light-danger">
                                        <i class="ki-duotone ki-lock fs-5 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Mode Tampilan Saja
                                    </span>
                                @else
                                    <span class="badge badge-light-success">
                                        <i class="ki-duotone ki-shield-tick fs-5 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Mode Edit Aktif
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                @if($canEdit)
                                    <button type="submit" class="btn btn-success">
                                        <i class="ki-duotone ki-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Simpan Perubahan
                                    </button>
                                @endif
                                <a href="{{ route('rekam-medis-poli', $rekap->idrawat) }}" class="btn btn-light-secondary">
                                    <i class="ki-duotone ki-arrow-left fs-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Kembali
                                </a>
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
            @if ($rawat->idpoli == 12)
                $('#fisio_repeater').repeater({
                    initEmpty: {{ (!$rekap->fisio || $rekap->fisio == 'null' || $rekap->fisio == '[]') ? 'true' : 'false' }},

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

            $('#icd9_repeater').repeater({
                initEmpty: {{ (!$rekap->icd9 || $rekap->icd9 == 'null' || $rekap->icd9 == '[]') ? 'true' : 'false' }},

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

            $('#kt_docs_repeater_basic').repeater({
                initEmpty: {{ (!$rekap->terapi_obat || $rekap->terapi_obat == 'null' || $rekap->terapi_obat == '[]') ? 'true' : 'false' }},
                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2"]').select2();
                    @if ($rekap->terapi_obat && $rekap->terapi_obat != 'null' && $rekap->terapi_obat != '[]')
                        @foreach (json_decode($rekap->terapi_obat) ?? [] as $val)
                        new Tagify(this.querySelector('[data-kt-repeater="tagify{{ $loop->iteration }}"]'));
                        @endforeach
                    @else
                        new Tagify(this.querySelector('[data-kt-repeater="tagify"]'));
                    @endif

                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2"]').select2();
                    @if ($rekap->terapi_obat && $rekap->terapi_obat != 'null' && $rekap->terapi_obat != '[]')
                        @foreach (json_decode($rekap->terapi_obat) ?? [] as $val)
                        new Tagify(document.querySelector('[data-kt-repeater="tagify{{ $loop->iteration }}"]'));

                        @endforeach
                    @else
                    new Tagify(document.querySelector('[data-kt-repeater="tagify"]'));

                    @endif

                }
            });

            $('#radiologi_repeater').repeater({
                initEmpty: {{ (!$rekap->radiologi || $rekap->radiologi == 'null' || $rekap->radiologi == '[]') ? 'true' : 'false' }},

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

            $('#lab_repeater').repeater({
                initEmpty: {{ (!$rekap->laborat || $rekap->laborat == 'null' || $rekap->laborat == '[]') ? 'true' : 'false' }},

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

            // ICDX Repeater
            $('#icdx_repeater').repeater({
                initEmpty: {{ (!$rekap->icdx || $rekap->icdx == 'null' || $rekap->icdx == '[]') ? 'true' : 'false' }},

                show: function() {
                    $(this).slideDown();

                    // Update jenis diagnosa untuk item baru
                    updateJenisDiagnosa();

                    $(this).find('[data-kt-repeater="select22"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listdiagnosa2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
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
                        placeholder: 'Cari diagnosa ICD-X...'
                    });
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement, function() {
                        $(this).remove();
                        updateJenisDiagnosa();
                    });
                },

                ready: function() {
                    $('[data-kt-repeater="select22"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listdiagnosa2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
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
                        placeholder: 'Cari diagnosa ICD-X...'
                    });

                    updateJenisDiagnosa();
                }
            });

            // ICD9 Repeater
            $('#icd9_repeater').repeater({
                initEmpty: {{ (!$rekap->icd9 || $rekap->icd9 == 'null' || $rekap->icd9 == '[]') ? 'true' : 'false' }},

                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-kt-repeater="select2icd9"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listprocedure',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.result.map(function(proc) {
                                        return {
                                            id: proc.id,
                                            text: proc.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Cari prosedur ICD-9...'
                    });
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },

                ready: function() {
                    $('[data-kt-repeater="select2icd9"]').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/auth/listprocedure',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.result.map(function(proc) {
                                        return {
                                            id: proc.id,
                                            text: proc.text
                                        };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Cari prosedur ICD-9...'
                    });
                }
            });

            // Function untuk update jenis diagnosa otomatis
            function updateJenisDiagnosa() {
                $('#icdx_repeater [data-repeater-item]').each(function(index) {
                    var jenisSelect = $(this).find('.icdx-jenis-diagnosa');
                    if (index === 0) {
                        // Set Primer untuk item pertama, tapi jangan disable agar tetap terkirim
                        jenisSelect.val('P');
                        // Tambahkan atribut readonly style jika ingin terlihat disabled tapi tetap submit
                        jenisSelect.css('pointer-events', 'none').css('background-color', '#f5f8fa');
                    } else {
                        // Set Sekunder untuk item lainnya dan enable
                        jenisSelect.val('S');
                        jenisSelect.css('pointer-events', '').css('background-color', '');
                    }
                });
            }

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
                    $('#value_obat').val('');
                }
            });

            $('#makanan').change(function() {
                if (this.checked) {
                    $('#value_makanan').show();
                } else {
                    $('#value_makanan').hide();
                    $('#value_makanan').val('');
                }
            });

            $('#lain').change(function() {
                if (this.checked) {
                    $('#value_lain').show();
                } else {
                    $('#value_lain').hide();
                    $('#value_lain').val('');
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
    </script>
@endsection
