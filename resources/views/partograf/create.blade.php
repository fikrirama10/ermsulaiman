@extends('layouts.index')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    <i class="fas fa-file-medical text-primary me-2"></i> Admisi Persalinan Baru
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('partograf.index') }}" class="text-muted text-hover-primary">Partograf</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Admisi Baru</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Terdapat kesalahan pada input:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('partograf.store') }}" method="POST" id="formAdmisi">
                @csrf

                <div class="row g-5">
                    <!--begin::Sidebar-->
                    <div class="col-lg-4">
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Pilih Pasien</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-5">
                                    <label class="form-label required">Kunjungan Rawat</label>
                                    <select name="visit_id" id="visit_id" class="form-select form-select-solid" required>
                                        <option value="">-- Pilih Pasien --</option>
                                        @foreach($visits as $visit)
                                        <option value="{{ $visit->id }}"
                                            data-norm="{{ $visit->no_rm }}"
                                            data-nama="{{ $visit->pasien->nama_pasien ?? '-' }}"
                                            data-tgllahir="{{ $visit->pasien->tgllahir ?? '' }}"
                                            data-jeniskelamin="{{ $visit->pasien->jenis_kelamin ?? '' }}"
                                            {{ old('visit_id') == $visit->id ? 'selected' : '' }}>
                                            {{ $visit->no_rm }} - {{ $visit->pasien->nama_pasien ?? '-' }}
                                            ({{ \Carbon\Carbon::parse($visit->tglmasuk)->format('d/m/Y H:i') }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Pilih kunjungan pasien yang akan melahirkan</div>
                                </div>

                                <div id="patientInfo" class="d-none">
                                    <div class="separator my-4"></div>
                                    <h4 class="mb-3">Informasi Pasien</h4>
                                    <div class="d-flex flex-column gap-3">
                                        <div>
                                            <label class="text-muted fs-7">No. RM:</label>
                                            <div class="fw-bold" id="infoNoRM">-</div>
                                        </div>
                                        <div>
                                            <label class="text-muted fs-7">Nama:</label>
                                            <div class="fw-bold" id="infoNama">-</div>
                                        </div>
                                        <div>
                                            <label class="text-muted fs-7">Tanggal Lahir:</label>
                                            <div class="fw-bold" id="infoTglLahir">-</div>
                                        </div>
                                        <div>
                                            <label class="text-muted fs-7">Jenis Kelamin:</label>
                                            <div class="fw-bold" id="infoJK">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Catatan</h3>
                            </div>
                            <div class="card-body">
                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                    <i class="fas fa-info-circle fs-2tx text-warning me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <div class="fs-6 text-gray-700">
                                                Data yang terisi dengan tanda (<span class="text-danger">*</span>) wajib diisi.
                                                Pastikan data yang diinput benar dan sesuai.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Sidebar-->

                    <!--begin::Main content-->
                    <div class="col-lg-8">
                        <!--begin::Data Kehamilan-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Data Kehamilan</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="patient_no_rm" id="patient_no_rm" value="{{ old('patient_no_rm') }}">

                                <div class="row g-5 mb-5">
                                    <div class="col-md-4">
                                        <label class="form-label required">Gravida (G)</label>
                                        <input type="number" name="gravida" class="form-control form-control-solid"
                                            min="1" required value="{{ old('gravida') }}" placeholder="1">
                                        <div class="form-text">Jumlah kehamilan</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Para (P)</label>
                                        <input type="number" name="para" class="form-control form-control-solid"
                                            min="0" required value="{{ old('para') }}" placeholder="0">
                                        <div class="form-text">Jumlah persalinan</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Abortus (A)</label>
                                        <input type="number" name="abortus" class="form-control form-control-solid"
                                            min="0" required value="{{ old('abortus') }}" placeholder="0">
                                        <div class="form-text">Jumlah keguguran</div>
                                    </div>
                                </div>

                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="form-label required">Usia Kehamilan (Minggu)</label>
                                        <input type="number" name="gestational_age" class="form-control form-control-solid"
                                            min="20" max="45" required value="{{ old('gestational_age') }}" placeholder="38">
                                        <div class="form-text">Usia kehamilan dalam minggu (20-45)</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu Pecah Ketuban</label>
                                        <input type="datetime-local" name="membrane_rupture_time"
                                            class="form-control form-control-solid" value="{{ old('membrane_rupture_time') }}">
                                        <div class="form-text">Kosongkan jika ketuban belum pecah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Data Kehamilan-->

                        <!--begin::Data Persalinan-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Data Persalinan</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-5">
                                    <label class="form-label required">Waktu Mulai Persalinan</label>
                                    <input type="datetime-local" name="labor_start_time"
                                        class="form-control form-control-solid" required
                                        value="{{ old('labor_start_time', now()->format('Y-m-d\TH:i')) }}">
                                    <div class="form-text">Waktu mulai fase aktif persalinan</div>
                                </div>
                            </div>
                        </div>
                        <!--end::Data Persalinan-->

                        <!--begin::Penilaian Awal Risiko-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Penilaian Awal Risiko</h3>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-warning">Opsional</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="notice d-flex bg-light-info rounded border-info border border-dashed p-4 mb-5">
                                    <i class="fas fa-exclamation-triangle fs-2tx text-info me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <div class="fs-6 text-gray-700">
                                                Centang faktor risiko yang ditemukan pada pasien. Penilaian ini membantu mengidentifikasi kondisi berisiko tinggi saat persalinan.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    @php
                                    $riskFactors = config('partograf.risk_factors');
                                    $half = ceil(count($riskFactors) / 2);
                                    $leftColumn = array_slice($riskFactors, 0, $half, true);
                                    $rightColumn = array_slice($riskFactors, $half, null, true);
                                    @endphp

                                    <div class="col-md-6">
                                        @foreach($leftColumn as $key => $label)
                                        <div class="form-check form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                name="initial_risk_assessment[]"
                                                value="{{ $key }}"
                                                id="risk_{{ $key }}"
                                                {{ is_array(old('initial_risk_assessment')) && in_array($key, old('initial_risk_assessment')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="risk_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="col-md-6">
                                        @foreach($rightColumn as $key => $label)
                                        <div class="form-check form-check-custom form-check-solid mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                name="initial_risk_assessment[]"
                                                value="{{ $key }}"
                                                id="risk_{{ $key }}"
                                                {{ is_array(old('initial_risk_assessment')) && in_array($key, old('initial_risk_assessment')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="risk_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="separator my-5"></div>

                                <div>
                                    <label class="form-label">Catatan Penilaian Risiko</label>
                                    <textarea name="initial_assessment_notes" class="form-control form-control-solid" rows="3"
                                        placeholder="Catatan tambahan terkait faktor risiko yang ditemukan...">{{ old('initial_assessment_notes') }}</textarea>
                                    <div class="form-text">Opsional - Tambahkan keterangan atau penjelasan jika diperlukan</div>
                                </div>
                            </div>
                        </div>
                        <!--end::Penilaian Awal Risiko-->

                        <!--begin::Tenaga Medis-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Tenaga Medis</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="form-label required">Bidan/Perawat yang Bertugas</label>
                                        <select name="midwife_id" class="form-select form-select-solid" required>
                                            <option value="">-- Pilih Bidan/Perawat --</option>
                                            @foreach($midwives as $midwife)
                                            <option value="{{ $midwife->id }}" {{ old('midwife_id') == $midwife->id ? 'selected' : '' }}>
                                                {{ $midwife->detail->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dokter Supervisor</label>
                                        <select name="doctor_id" class="form-select form-select-solid">
                                            <option value="">-- Pilih Dokter (Opsional) --</option>
                                            @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->nama_dokter }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Tenaga Medis-->

                        <!--begin::Catatan Tambahan-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Catatan Tambahan</h3>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control form-control-solid" rows="4"
                                    placeholder="Catatan atau keterangan tambahan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <!--end::Catatan Tambahan-->

                        <!--begin::Actions-->
                        <div class="card card-flush">
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('partograf.index') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan & Mulai Monitoring
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Main content-->
                </div>
            </form>

        </div>
    </div>
    <!--end::Content-->
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // When visit is selected, populate patient info
        $('#visit_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const norm = selectedOption.data('norm');
            const nama = selectedOption.data('nama');
            const tgllahir = selectedOption.data('tgllahir');
            const jeniskelamin = selectedOption.data('jeniskelamin');

            if (norm) {
                $('#patient_no_rm').val(norm);
                $('#infoNoRM').text(norm);
                $('#infoNama').text(nama);
                $('#infoTglLahir').text(tgllahir ? new Date(tgllahir).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }) : '-');
                $('#infoJK').text(jeniskelamin === 'L' ? 'Laki-laki' : (jeniskelamin === 'P' ? 'Perempuan' : '-'));
                $('#patientInfo').removeClass('d-none');
            } else {
                $('#patient_no_rm').val('');
                $('#patientInfo').addClass('d-none');
            }
        });

        // Trigger on page load if there's old input
        @if(old('visit_id'))
        $('#visit_id').trigger('change');
        @endif

        // Form validation
        $('#formAdmisi').on('submit', function(e) {
            const gravida = parseInt($('input[name="gravida"]').val());
            const para = parseInt($('input[name="para"]').val());
            const abortus = parseInt($('input[name="abortus"]').val());

            // Validation: G should be >= P + A
            if (gravida < (para + abortus)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Gravida (G) harus lebih besar atau sama dengan Para + Abortus (P + A)',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });

        // Initialize Select2 if available
        if ($.fn.select2) {
            $('#visit_id, select[name="midwife_id"], select[name="doctor_id"]').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                },
                allowClear: true
            });
        }
    });
</script>
@endsection