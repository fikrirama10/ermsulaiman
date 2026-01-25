@extends('layouts.index')

@section('content')
<div class="container-xxl">
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Two-Factor Authentication (2FA)</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Amankan akun Anda dengan autentikasi dua faktor</span>
                    </h3>
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">Sukses</h4>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($twoFactorEnabled)
                        <!-- 2FA is Enabled -->
                        <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-6 mb-10">
                            <i class="ki-duotone ki-shield-tick fs-2tx text-success me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Two-Factor Authentication Aktif</h4>
                                    <div class="fs-6 text-gray-700">
                                        Akun Anda telah dilindungi dengan autentikasi dua faktor.
                                        Anda memerlukan kode dari Google Authenticator saat login.
                                        <br>
                                        <span class="badge badge-success mt-2">
                                            <i class="ki-duotone ki-check fs-2"></i> Diaktifkan pada: {{ $user->two_factor_confirmed_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="card card-bordered h-100">
                                    <div class="card-body">
                                        <h4 class="card-title">Recovery Codes</h4>
                                        <p class="text-gray-600 fs-6 mb-5">
                                            Gunakan recovery code jika Anda kehilangan akses ke aplikasi authenticator.
                                        </p>
                                        <a href="{{ route('two-factor.recovery-codes') }}" class="btn btn-sm btn-primary">
                                            <i class="ki-duotone ki-eye fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Lihat Recovery Codes
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-bordered h-100 border-danger">
                                    <div class="card-body">
                                        <h4 class="card-title text-danger">Nonaktifkan 2FA</h4>
                                        <p class="text-gray-600 fs-6 mb-5">
                                            Menonaktifkan 2FA akan mengurangi keamanan akun Anda.
                                        </p>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#disableModal">
                                            <i class="ki-duotone ki-shield-cross fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Nonaktifkan 2FA
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- 2FA is Disabled -->
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-10">
                            <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <h4 class="text-gray-900 fw-bold">Two-Factor Authentication Tidak Aktif</h4>
                                    <div class="fs-6 text-gray-700">
                                        Aktifkan 2FA untuk meningkatkan keamanan akun Anda.
                                        Dengan 2FA, Anda memerlukan kode verifikasi tambahan dari aplikasi Google Authenticator saat login.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('two-factor.enable') }}" class="btn btn-lg btn-primary">
                                <i class="ki-duotone ki-shield-tick fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Aktifkan Two-Factor Authentication
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disable 2FA Modal -->
<div class="modal fade" id="disableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nonaktifkan Two-Factor Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-shield-cross fs-2hx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <span>Menonaktifkan 2FA akan mengurangi keamanan akun Anda.</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Password</label>
                        <input type="password" name="password" class="form-control" required />
                        @error('password')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Kode OTP atau Recovery Code</label>
                        <input type="text" name="code" class="form-control" placeholder="000000" required />
                        <div class="form-text">Masukkan kode 6-digit dari Google Authenticator atau recovery code</div>
                        @error('code')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Nonaktifkan 2FA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
