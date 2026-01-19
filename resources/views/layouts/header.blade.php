<div id="kt_app_header" class="app-header">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--begin::Logo image-->
            <a href="#">
                {{-- <img alt="Logo" src="{{ asset('assets/media/logos/demo38-small.svg') }}" class="h-30px" /> --}}
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->

        <!--begin::Hospital Info-->
        <div class="d-none d-lg-flex align-items-center me-5">
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-40px symbol-circle bg-light-primary">
                    <i class="bi bi-hospital-fill fs-2 text-primary"></i>
                </div>
                <div>
                    <div class="fs-7 text-gray-600">RS AU dr. Norman T Lubis</div>
                    <div class="fs-8 text-gray-500 fw-semibold">Sistem Informasi Manajemen</div>
                </div>
            </div>
        </div>
        <!--end::Hospital Info-->

        <!--begin::Navbar-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <!-- Real-time Clock & Date -->
                <div class="d-none d-lg-flex align-items-center me-5">
                    <div class="border border-gray-300 rounded px-4 py-2 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-center">
                                <i class="bi bi-calendar3 fs-2 text-primary"></i>
                            </div>
                            <div class="border-end border-gray-300 pe-3">
                                <div class="fs-7 text-gray-600 fw-semibold">Tanggal</div>
                                <div class="fs-6 fw-bold text-gray-800" id="current-date">Loading...</div>
                            </div>
                            <div class="text-center">
                                <i class="bi bi-clock fs-2 text-success"></i>
                            </div>
                            <div>
                                <div class="fs-7 text-gray-600 fw-semibold">Waktu</div>
                                <div class="fs-6 fw-bold text-gray-800" id="current-time">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Icon -->
            <div class="app-navbar-item ms-1 ms-md-3">
                <div class="btn btn-icon btn-custom btn-active-color-primary w-35px h-35px w-md-40px h-md-40px position-relative">
                    <i class="bi bi-bell fs-2"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger" style="font-size: 8px; padding: 3px 5px;">
                        3
                    </span>
                </div>
            </div>

            <!-- Quick Settings -->
            <div class="app-navbar-item ms-1 ms-md-3">
                <div class="btn btn-icon btn-custom btn-active-color-primary w-35px h-35px w-md-40px h-md-40px">
                    <i class="bi bi-gear fs-2"></i>
                </div>
            </div>

            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <span class="fw-bold me-3 d-none d-md-inline">{{ auth()->user()->detail->nama }}</span>
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="user" />
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-300px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-3">
                                <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                                <div class="symbol-badge bg-success start-100 top-100 border-4 h-8px w-8px ms-n2 mt-n2"></div>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-6">{{ auth()->user()->detail->nama }}</div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->detail->email }}</a>
                                @if(auth()->user()->roles)
                                    <span class="badge badge-light-primary badge-sm mt-1">{{ ucfirst(auth()->user()->roles->first()->name) }}</span>
                                @endif
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-icon">
                                <i class="bi bi-person fs-4"></i>
                            </span>
                            <span class="menu-title">Profil Saya</span>
                        </a>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-icon">
                                <i class="bi bi-gear fs-4"></i>
                            </span>
                            <span class="menu-title">Pengaturan</span>
                        </a>
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a class="menu-link px-5 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span class="menu-icon">
                                <i class="bi bi-box-arrow-right fs-4"></i>
                            </span>
                            <span class="menu-title">Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::Navbar-->
        <!--begin::Separator-->
        <div class="app-navbar-separator separator d-none d-lg-flex"></div>
        <!--end::Separator-->
    </div>
    <!--end::Header container-->
</div>
