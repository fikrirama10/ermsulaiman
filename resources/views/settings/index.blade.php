@extends('layouts.index')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan Sistem</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($settings as $setting)
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bold">{{ $setting->description }}</label>
                            <input type="text" class="form-control form-control-solid" name="{{ $setting->key }}" value="{{ $setting->value }}">
                            @if($setting->key == 'surat_format')
                            <div class="form-text text-muted mt-2">
                                Gunakan placeholder berikut:
                                <ul>
                                    <li><code>{SEQUENCE}</code> : Nomor urut (001, 002, dst)</li>
                                    <li><code>{CODE}</code> : Kode Jenis Surat (SAK, LAH, MAT, RUJ)</li>
                                    <li><code>{ROMAN}</code> : Bulan (I, II, ... XII)</li>
                                    <li><code>{MONTH}</code> : Bulan Angka (01, 02, ... 12)</li>
                                    <li><code>{YEAR}</code> : Tahun (2026)</li>
                                </ul>
                                Contoh: <code>{SEQUENCE}/SURAT-{CODE}/{ROMAN}/{YEAR}</code>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
