<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verifikasi 2FA - RSAU dr. Norman T Lubis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('assets/media/auth/bg10.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(0, 158, 247, 0.8), rgba(0, 0, 0, 0.6));
            z-index: 0;
        }
        .login-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .otp-input {
            font-size: 2rem;
            text-align: center;
            letter-spacing: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body id="kt_body" class="app-blank">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-column-fluid flex-center py-10">
            <div class="login-card w-lg-500px p-10 p-lg-15 mx-auto">
                <form class="form w-100" novalidate="novalidate" method="POST" action="{{ route('two-factor.verify') }}">
                    @csrf

                    <div class="text-center mb-10">
                        <div class="mb-5">
                            <i class="ki-duotone ki-shield-tick fs-5x text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <h1 class="text-gray-900 fw-bolder mb-3">Verifikasi Two-Factor Authentication</h1>
                        <div class="text-gray-500 fw-semibold fs-6">
                            Masukkan kode 6-digit dari aplikasi Google Authenticator Anda
                            <br>atau gunakan recovery code (10 karakter)
                        </div>
                    </div>

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

                    <div class="fv-row mb-8">
                        <label class="form-label fs-6 fw-bolder text-gray-900">Kode Verifikasi</label>
                        <input class="form-control form-control-lg form-control-solid otp-input"
                               type="text"
                               name="code"
                               autocomplete="off"
                               placeholder="000000"
                               maxlength="10"
                               required
                               autofocus />
                    </div>

                    <div class="d-grid mb-5">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="indicator-label">Verifikasi</span>
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="link-primary fs-6 fw-bold">
                            Kembali ke Login
                        </a>
                    </div>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
