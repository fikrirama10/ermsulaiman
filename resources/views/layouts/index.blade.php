<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>RagaRiksa - Rsau dr.Noman T Lubis</title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{ asset('assets/img/ragariksa.ico') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> --}}
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    {{-- <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" /> --}}
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->

    {{-- <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--end::Global Stylesheets Bundle-->
    <style>
        /* Helper Menu Styles */
        .helper-menu {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 100;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            padding: 8px;
            transition: all 0.3s ease;
        }

        .helper-menu:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .helper-menu.position-top-left {
            top: 80px;
            left: 20px;
            right: auto;
        }

        .helper-menu.position-top-right {
            top: 80px;
            right: 20px;
            left: auto;
        }

        .helper-menu.position-bottom-left {
            top: auto;
            bottom: 20px;
            left: 20px;
            right: auto;
        }

        .helper-menu.position-bottom-right {
            top: auto;
            bottom: 20px;
            right: 20px;
            left: auto;
        }

        .helper-menu.position-middle-left {
            top: 50%;
            left: 20px;
            right: auto;
            transform: translateY(-50%);
        }

        .helper-menu.position-middle-right {
            top: 50%;
            right: 20px;
            left: auto;
            transform: translateY(-50%);
        }

        .helper-toggle {
            background: none;
            border: none;
            padding: 10px;
            cursor: pointer;
            color: #009ef7;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 4px;
        }

        .helper-toggle:hover {
            background-color: #f1faff;
        }

        .helper-toggle span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .helper-items {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .helper-menu.show .helper-items {
            max-height: 500px;
            opacity: 1;
        }

        .helper-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: #5e6278;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .helper-item:hover {
            background-color: #f1faff;
            color: #009ef7;
            transform: translateX(4px);
        }

        .helper-item svg {
            margin-right: 10px;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .helper-item span {
            white-space: nowrap;
        }

        .helper-divider {
            height: 1px;
            background-color: #f1f5f9;
            margin: 8px 0;
        }

        .helper-label {
            font-size: 0.65rem;
            color: #a1a5b7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            padding: 8px 12px 4px;
        }
    </style>
    @yield('css')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-minimize="on"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            @include('layouts.header')
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                @include('layouts.sidebar')
                <!--end::Sidebar-->
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    @yield('content')
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    @include('layouts.footer')
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js?v='.rand().'') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js?v='.rand().'') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script> --}}
    <script>
        $(document).ready(function() {
			$("#tgl_kontrol").flatpickr();
        });

        // Real-time Clock and Date
        function updateDateTime() {
            const now = new Date();

            // Format tanggal: Senin, 18 Jan 2026
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const formattedDate = `${dayName}, ${date} ${monthName} ${year}`;

            // Format waktu: 14:30:45 WIB
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const formattedTime = `${hours}:${minutes}:${seconds} WIB`;

            // Update DOM
            $('#current-date').text(formattedDate);
            $('#current-time').text(formattedTime);
        }

        // Update setiap detik
        setInterval(updateDateTime, 1000);

        // Jalankan pertama kali
        updateDateTime();

        // Helper Menu Toggle
        let helperMenuOpen = false;

        function toggleHelperMenu() {
            const menu = document.getElementById('helperMenu');
            if (!menu) return;

            const btn = menu.querySelector('.helper-toggle svg');

            helperMenuOpen = !helperMenuOpen;

            if (helperMenuOpen) {
                menu.classList.add('show');
                // Change to X icon
                btn.innerHTML = `
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                `;
            } else {
                menu.classList.remove('show');
                // Change back to hamburger icon
                btn.innerHTML = `
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                `;
            }
        }

        // Close helper menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.helper-menu').length && helperMenuOpen) {
                toggleHelperMenu();
            }
        });

    </script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    {{-- <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js?v='.rand().'') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/high-contrast-light.js"></script> --}}
        <!--end::Vendors Javascript-->
    @yield('js')

    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
