@extends('layouts.index')

@section('content')
<div class="container-xxl">
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Aktifkan Two-Factor Authentication</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Scan QR Code dengan Google Authenticator</span>
                    </h3>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-danger">Error</h4>
                                <span>{{ $errors->first('code') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-danger">Error</h4>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Left Column: Instructions -->
                        <div class="col-lg-6 mb-10">
                            <h3 class="fw-bold mb-5">Langkah-Langkah Aktivasi:</h3>

                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-mobile fs-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">1. Download Aplikasi</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2">
                                                    Download <strong>Google Authenticator</strong> dari Play Store atau App Store
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-scan-barcode fs-2 text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                                <span class="path6"></span>
                                                <span class="path7"></span>
                                                <span class="path8"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">2. Scan QR Code</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2">
                                                    Buka aplikasi dan scan QR Code di sebelah kanan atau masukkan kode manual
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-line w-40px"></div>
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-info">
                                            <i class="ki-duotone ki-verify fs-2 text-info">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="timeline-content mb-10 mt-n1">
                                        <div class="pe-3 mb-5">
                                            <div class="fs-5 fw-bold mb-2">3. Verifikasi Kode</div>
                                            <div class="d-flex align-items-center mt-1 fs-6">
                                                <div class="text-muted me-2">
                                                    Masukkan kode 6-digit yang muncul di aplikasi untuk memverifikasi
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: QR Code and Form -->
                        <div class="col-lg-6">
                            <div class="card card-bordered">
                                <div class="card-body text-center">
                                    <h4 class="fw-bold mb-5">Scan QR Code</h4>

                                    <div class="mb-10 p-5 bg-light rounded">
                                        {!! $qrCodeSvg !!}
                                    </div>

                                    <div class="separator my-10">
                                        <span class="text-gray-500 fs-7 fw-semibold">ATAU</span>
                                    </div>

                                    <h5 class="fw-bold mb-3">Masukkan Kode Manual</h5>
                                    <div class="p-4 bg-light-primary rounded mb-10">
                                        <code class="fs-4 fw-bold text-primary">{{ $secret }}</code>
                                    </div>

                                    <form method="POST" action="{{ route('two-factor.confirm') }}">
                                        @csrf

                                        <div class="mb-5">
                                            <label class="form-label fw-bold required">Kode Verifikasi (6 digit)</label>
                                            <input type="text"
                                                   name="code"
                                                   class="form-control form-control-lg text-center"
                                                   placeholder="000000"
                                                   maxlength="6"
                                                   pattern="[0-9]{6}"
                                                   required
                                                   autofocus />
                                            <div class="form-text">Masukkan kode 6-digit dari Google Authenticator</div>
                                        </div>

                                        <div class="d-flex gap-3">
                                            <a href="{{ route('two-factor.index') }}" class="btn btn-light flex-grow-1">
                                                Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary flex-grow-1">
                                                <i class="ki-duotone ki-verify fs-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Verifikasi & Aktifkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
