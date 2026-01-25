@extends('layouts.index')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    <i class="fas fa-edit text-primary me-2"></i> Edit Data Partograf
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
                    <li class="breadcrumb-item text-muted">Edit Data</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <form action="{{ route('partograf.update', $laborRecord->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-5">
                    <div class="col-lg-8 offset-lg-2">
                        <!--begin::Patient Info-->
                        <div class="card card-flush mb-5">
                            <div class="card-header bg-light-primary">
                                <h3 class="card-title">Informasi Pasien</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted">No. RM:</label>
                                        <div class="fw-bold">{{ $laborRecord->patient_no_rm }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted">Nama Pasien:</label>
                                        <div class="fw-bold">{{ $laborRecord->visit->pasien->nama_pasien ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Patient Info-->

                        <!--begin::Pregnancy Data-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Data Kehamilan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-5 mb-5">
                                    <div class="col-md-4">
                                        <label class="form-label required">Gravida</label>
                                        <input type="number" name="gravida" class="form-control" 
                                               value="{{ old('gravida', $laborRecord->gravida) }}" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Para</label>
                                        <input type="number" name="para" class="form-control" 
                                               value="{{ old('para', $laborRecord->para) }}" min="0" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Abortus</label>
                                        <input type="number" name="abortus" class="form-control" 
                                               value="{{ old('abortus', $laborRecord->abortus) }}" min="0" required>
                                    </div>
                                </div>

                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="form-label required">Usia Kehamilan (Minggu)</label>
                                        <input type="number" name="gestational_age" class="form-control" 
                                               value="{{ old('gestational_age', $laborRecord->gestational_age) }}" 
                                               min="20" max="45" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu Pecah Ketuban</label>
                                        <input type="datetime-local" name="membrane_rupture_time" class="form-control" 
                                               value="{{ old('membrane_rupture_time', $laborRecord->membrane_rupture_time?->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Pregnancy Data-->

                        <!--begin::Labor Data-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Data Persalinan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-5 mb-5">
                                    <div class="col-md-6">
                                        <label class="form-label required">Waktu Mulai Persalinan</label>
                                        <input type="datetime-local" name="labor_start_time" class="form-control" 
                                               value="{{ old('labor_start_time', $laborRecord->labor_start_time->format('Y-m-d\TH:i')) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu Selesai Persalinan</label>
                                        <input type="datetime-local" name="labor_end_time" class="form-control" 
                                               value="{{ old('labor_end_time', $laborRecord->labor_end_time?->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>

                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="form-label">Metode Persalinan</label>
                                        <select name="delivery_method" class="form-select">
                                            <option value="">-- Belum Ditentukan --</option>
                                            <option value="normal" {{ old('delivery_method', $laborRecord->delivery_method) == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="vacuum" {{ old('delivery_method', $laborRecord->delivery_method) == 'vacuum' ? 'selected' : '' }}>Vacuum</option>
                                            <option value="forceps" {{ old('delivery_method', $laborRecord->delivery_method) == 'forceps' ? 'selected' : '' }}>Forceps</option>
                                            <option value="cesarean" {{ old('delivery_method', $laborRecord->delivery_method) == 'cesarean' ? 'selected' : '' }}>Cesarean</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="ongoing" {{ old('status', $laborRecord->status) == 'ongoing' ? 'selected' : '' }}>Berlangsung</option>
                                            <option value="completed" {{ old('status', $laborRecord->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="referred" {{ old('status', $laborRecord->status) == 'referred' ? 'selected' : '' }}>Rujukan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Labor Data-->

                        <!--begin::Outcome-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Kondisi Bayi & Komplikasi</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-5">
                                    <label class="form-label">Kondisi Bayi</label>
                                    <input type="text" name="baby_condition" class="form-control" 
                                           value="{{ old('baby_condition', $laborRecord->baby_condition) }}" 
                                           placeholder="Contoh: Sehat, APGAR 8/9, BB 3200gr">
                                </div>
                                <div class="mb-5">
                                    <label class="form-label">Komplikasi</label>
                                    <textarea name="complications" class="form-control" rows="3" 
                                              placeholder="Catatan komplikasi jika ada...">{{ old('complications', $laborRecord->complications) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--end::Outcome-->

                        <!--begin::Medical Staff-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Tenaga Medis</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <label class="form-label required">Bidan/Perawat</label>
                                        <select name="midwife_id" class="form-select" required>
                                            @foreach($midwives as $midwife)
                                            <option value="{{ $midwife->id }}" 
                                                {{ old('midwife_id', $laborRecord->midwife_id) == $midwife->id ? 'selected' : '' }}>
                                                {{ $midwife->detail->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dokter Supervisor</label>
                                        <select name="doctor_id" class="form-select">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" 
                                                {{ old('doctor_id', $laborRecord->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->nama_dokter }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Medical Staff-->

                        <!--begin::Notes-->
                        <div class="card card-flush mb-5">
                            <div class="card-header">
                                <h3 class="card-title">Catatan Tambahan</h3>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control" rows="4" 
                                          placeholder="Catatan tambahan...">{{ old('notes', $laborRecord->notes) }}</textarea>
                            </div>
                        </div>
                        <!--end::Notes-->

                        <!--begin::Actions-->
                        <div class="card card-flush">
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('partograf.show', $laborRecord->id) }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Data
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Actions-->
                    </div>
                </div>
            </form>

        </div>
    </div>
    <!--end::Content-->
</div>
@endsection
