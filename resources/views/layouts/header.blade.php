<div id="kt_app_header" class="app-header" style="background: linear-gradient(to right, #ffffff, #f8f9fa); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <!--begin::Logo image-->
            <a href="{{ url('/dashboard') }}">
                <span class="fw-bold fs-6 text-success">E-RM</span>
            </a>
            <!--end::Logo image-->
        </div>
        <!--end::Sidebar toggle-->

        <!--begin::Mobile Logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
             <div class="d-none d-lg-flex align-items-center gap-3">
                <div class="symbol symbol-45px symbol-circle">
    <img src="{{ asset('image/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png')}}" alt="Logo RSAU" />
</div>
                <div class="d-flex flex-column">
                    <div class="fs-6 fw-bolder text-dark">RS AU dr. Norman T Lubis</div>
                    <div class="fs-8 fw-semibold text-muted">Sistem Informasi Manajemen RS</div>
                </div>
            </div>
        </div>
        <!--end::Mobile Logo-->

        <!--begin::Navbar-->
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">

            <!-- Global Search Bar -->
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
                <div class="d-flex align-items-center w-100 mw-lg-400px">
                     <div class="position-relative w-100">
                        <span class="position-absolute top-50 translate-middle-y ms-4">
                            <i class="bi bi-search text-gray-500 fs-3"></i>
                        </span>
                        <input type="text" class="form-control form-control-solid ps-12 border-0 bg-light-secondary"
                               name="search" id="global_search_input" placeholder="Cari Pasien / Menu..."
                               data-kt-search-element="input" autocomplete="off">
                        <!-- Search Shortcut Hint -->
                        <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                             <span class="badge badge-light-secondary text-muted border">/</span>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-100"
                             id="global_search_results" style="position: absolute; top: 100%; left: 0; z-index: 105;">
                            <!-- Results will be injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Clock (Compact) -->
            <div class="app-navbar-item ms-1 ms-md-3">
                 <div class="d-none d-lg-flex flex-column align-items-end me-5">
                    <span class="fs-7 fw-bold text-gray-800" id="header_clock">00:00:00</span>
                    <span class="fs-8 fw-semibold text-muted" id="header_date">...</span>
                 </div>
            </div>

            <!-- Quick Actions (Grid) -->
            <div class="app-navbar-item ms-1 ms-md-3">
                <div class="btn btn-icon btn-custom btn-active-light-primary w-35px h-35px w-md-40px h-md-40px"
                     data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <i class="bi bi-grid-3x3-gap fs-2"></i>
                </div>
                <!-- Quick Menu Dropdown -->
                <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                     <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset('assets/media/misc/menu-header-bg.jpg') }}'); background-size: cover;">
                        <h3 class="text-white fw-semibold px-9 mt-10 mb-6">Quick Actions
                        <span class="fs-8 opacity-75 ps-3">Pintasan Menu</span></h3>
                    </div>
                    <div class="row g-0">
                        <div class="col-6">
                            <a href="{{ route('pendaftaran.create') }}" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end border-bottom">
                                <i class="bi bi-person-plus fs-3x text-primary mb-2"></i>
                                <span class="fs-5 fw-semibold text-gray-800 mb-0">Pasien Baru</span>
                                <span class="fs-7 text-gray-400">Registrasi Pasien</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pendaftaran.index') }}" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
                                <i class="bi bi-clipboard-pulse fs-3x text-success mb-2"></i>
                                <span class="fs-5 fw-semibold text-gray-800 mb-0">Kunjungan</span>
                                <span class="fs-7 text-gray-400">Monitoring Hari Ini</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('dokter.index') }}" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-end">
                                <i class="bi bi-people fs-3x text-info mb-2"></i>
                                <span class="fs-5 fw-semibold text-gray-800 mb-0">Dokter</span>
                                <span class="fs-7 text-gray-400">Jadwal & Data</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light">
                                <i class="bi bi-file-earmark-bar-graph fs-3x text-warning mb-2"></i>
                                <span class="fs-5 fw-semibold text-gray-800 mb-0">Laporan</span>
                                <span class="fs-7 text-gray-400">Rekapitulasi</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="app-navbar-item ms-1 ms-md-3">
                <div class="btn btn-icon btn-custom btn-active-light-primary w-35px h-35px w-md-40px h-md-40px position-relative"
                     data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <i class="bi bi-bell fs-2"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger w-15px h-15px ms-n4 mt-3">3</span>
                </div>
                <!-- Notif Dropdown (Sample) -->
                 <div class="menu menu-sub menu-sub-dropdown menu-column w-350px" data-kt-menu="true">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Notifikasi</h3>
                        </div>
                        <div class="card-body p-0">
                             <div class="scroll-y mh-325px my-5 px-8">
                                <div class="d-flex flex-stack py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-4">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="bi bi-info-circle text-primary"></i>
                                            </span>
                                        </div>
                                        <div class="mb-0 me-2">
                                            <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Update Sistem</a>
                                            <div class="text-gray-400 fs-7">Versi terbaru tersedia</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-light fs-8">1 Jam</span>
                                </div>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- User Menu -->
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-35px symbol-md-45px border border-gray-300 border-dashed p-1 bg-light"
                     data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                     <!-- Use Auth User Image if available, else generic -->
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" class="rounded-circle" alt="user" />
                </div>

                <!-- Use existing User Menu Structure but refined -->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->detail->nama ?? 'User' }}</div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->detail->email ?? '-' }}</a>
                                <span class="badge badge-light-success fw-bold fs-8 mt-1">{{ auth()->user()->detail->poli->poli ?? 'Staff' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                        <a href="#" class="menu-link px-5">Profil Saya</a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="{{ route('two-factor.index') }}" class="menu-link px-5">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-shield-tick fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-text">Two-Factor Authentication</span>
                            @if(auth()->user()->hasTwoFactorEnabled())
                                <span class="menu-badge">
                                    <span class="badge badge-success badge-circle fw-bold fs-8">✓</span>
                                </span>
                            @endif
                        </a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-text">Tema</span>
                            <span class="menu-badge">
                                <span class="badge badge-light-danger badge-circle fw-bold fs-7">!</span>
                            </span>
                        </a>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                         <a class="menu-link px-5 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Navbar-->
    </div>
</div>

<script>
    function updateHeaderClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
        const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

        const clockEl = document.getElementById('header_clock');
        const dateEl = document.getElementById('header_date');

        if(clockEl) clockEl.textContent = timeString;
        if(dateEl) dateEl.textContent = dateString;
    }

    // Update every second
    setInterval(updateHeaderClock, 1000);
    // Initial call
    updateHeaderClock();

    // Simple keyboard shortcut for search (/)
    document.addEventListener('keydown', function(event) {
        if (event.key === '/') {
            const searchInput = document.getElementById('global_search_input');
            if (searchInput && document.activeElement !== searchInput) {
                event.preventDefault(); // Prevent '/' character input
                searchInput.focus();
            }
        }
    });

    // Global Search AJAX Logic
    let globalSearchTimeout;
    const searchInput = document.getElementById('global_search_input');
    const searchResults = document.getElementById('global_search_results');

    searchInput.addEventListener('keyup', function() {
        clearTimeout(globalSearchTimeout);
        const query = this.value;

        if (query.length < 3) {
            searchResults.innerHTML = '';
            searchResults.classList.remove('show');
            return;
        }

        searchResults.innerHTML = '<div class="px-5 py-3 text-muted">Searching...</div>';
        searchResults.classList.add('show');

        globalSearchTimeout = setTimeout(() => {
            fetch(`{{ route('global.search') }}?keyword=${query}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            html += `
                                <div class="menu-item px-3">
                                    <a href="${item.url}" class="menu-link px-3">
                                        <span class="menu-icon">
                                            <i class="bi ${item.icon} fs-3"></i>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-6 fw-bold text-gray-800">${item.title}</span>
                                            <span class="fs-7 fw-semibold text-muted">${item.subtitle}</span>
                                        </div>
                                        <span class="badge badge-light-primary ms-auto">${item.category}</span>
                                    </a>
                                </div>
                            `;
                        });
                        html += '<div class="separator my-2"></div><div class="text-center py-2"><small class="text-muted">Tekan Enter untuk melihat semua hasil</small></div>';
                    } else {
                        html = '<div class="px-5 py-3 text-center text-muted">Tidak ditemukan hasil untuk "' + query + '"</div>';
                    }
                    searchResults.innerHTML = html;
                })
                .catch(err => {
                    console.error('Search Error:', err);
                    searchResults.innerHTML = '<div class="px-5 py-3 text-center text-danger">Error fetching results</div>';
                });
        }, 400); // Debounce 400ms
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('show');
        }
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.length >= 3 && searchResults.innerHTML !== '') {
            searchResults.classList.add('show');
        }
    });

</script>
