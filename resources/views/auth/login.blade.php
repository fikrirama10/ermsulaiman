<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>Login - RSAU dr. Norman T Lubis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('{{ asset('assets/media/auth/bg10.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        /* Overlay for better readability */
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
            overflow: hidden;
        }
        .form-control.bg-transparent {
            background-color: #f5f8fa !important;
            border-color: #f5f8fa;
            color: #5e6278;
            transition: all 0.2s ease;
        }
        .form-control.bg-transparent:focus {
            background-color: #eef3f7 !important;
            border-color: #009ef7;
            box-shadow: none;
        }
        .btn-login {
            background: linear-gradient(to right, #009ef7, #007bc0);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 158, 247, 0.4);
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="app-blank">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid flex-center py-10">
            
            <!--begin::Card-->
            <div class="login-card w-lg-500px p-10 p-lg-15 mx-auto animate__animated animate__fadeInUp">
                <!--begin::Form-->
                <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ url('/login') }}" method="POST">
                    @csrf
                    
                    <!--begin::Header-->
                    <div class="text-center mb-10">
                        <!--begin::Logo-->
                        <div class="mb-5">
                            <img alt="Logo" src="{{ asset('image/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png') }}" class="h-100px" />
                        </div>
                        <!--end::Logo-->
                        
                        <h1 class="text-dark fw-bolder mb-3 fs-2">E-Rekam Medis</h1>
                        <div class="text-gray-500 fw-semibold fs-6">RSAU dr. Norman T Lubis</div>
                    </div>
                    <!--end::Header-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-8">
                        <label class="form-label fs-6 fw-bold text-dark">Username / Email</label>
                        <input type="text" placeholder="Masukkan username anda" name="username" autocomplete="off" class="form-control bg-transparent" />
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-10">
                        <div class="d-flex flex-stack mb-2">
                            <label class="form-label fw-bold text-dark fs-6 mb-0">Password</label>
                            {{-- <a href="#" class="link-primary fs-6 fw-bold">Lupa Password?</a> --}}
                        </div>
                        <input type="password" placeholder="Masukkan password" name="password" autocomplete="off" class="form-control bg-transparent" />
                    </div>
                    <!--end::Input group-->

                    <!--begin::Actions-->
                    <div class="d-grid mb-10">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary btn-login">
                            <span class="indicator-label fs-5 fw-bold">Masuk Aplikasi</span>
                            <span class="indicator-progress">Harap Tunggu...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->

                    <!--begin::Footer-->
                    <div class="text-center text-gray-500 fs-7">
                        &copy; {{ date('Y') }} RSAU dr. Norman T Lubis. <br/>All rights reserved.
                    </div>
                    <!--end::Footer-->

                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->

        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Javascript-->
</body>
<!--end::Body-->
</html>
