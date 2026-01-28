<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-header pt-8 pb-2" id="kt_app_sidebar_header">
        <!--begin::Logo-->
        <div class=" d-flex flex-stack d-lg-flex bg-white rounded p-5">
            <a href="{{ url('/dashboard') }}" class="app-sidebar-logo">
                <img alt="Logo" src="{{ asset('assets/img/ragariksa-text-tg.png') }}"
                    class="h-40px d-none d-sm-inline" style="width:100%" />
            </a>
            <!--end::Logo-->
            <!--begin::Sidebar toggle-->
            <div id="kt_app_sidebar_toggle"
                class="app-sidebar-toggle btn btn-sm btn-icon bg-light btn-color-gray-700 btn-active-color-success d-none d-lg-flex rotate"
                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-outline ki-text-align-right rotate-180 fs-1"></i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
    </div>
    <!--begin::Navs-->
    <div class="app-sidebar-navs flex-column-fluid py-6" id="kt_app_sidebar_navs">
        <div id="kt_app_sidebar_navs_wrappers" class="app-sidebar-wrapper hover-scroll-y my-2" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_header" data-kt-scroll-wrappers="#kt_app_sidebar_navs"
            data-kt-scroll-offset="5px">
            <!--begin::Sidebar menu-->
            <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
                class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary">
                <!--begin::Heading-->
                <div class="menu-item mb-2">
                    <div class="menu-heading text-white text-uppercase fs-7 fw-bold">Menu</div>
                    <!--begin::Separator-->
                    <div class="app-sidebar-separator separator"></div>
                    <!--end::Separator-->
                </div>
                <!--end::Heading-->
                @if (config('erm.enable_dynamic_menu'))
                    @foreach ($userMenus as $menu)
                        @if (isset($menu->accessibleChildren) && $menu->accessibleChildren->count() > 0)
                            <div data-kt-menu-trigger="click"
                                class="menu-item menu-accordion {{ Request::is(trim($menu->url, '/') . '*') ? 'show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="{{ $menu->icon ?? 'ki-outline ki-element-11' }} fs-2"></i>
                                    </span>
                                    <span class="menu-title">{{ $menu->name }}</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    @foreach ($menu->accessibleChildren as $child)
                                        <div class="menu-item">
                                            <a class="menu-link {{ Request::is(trim($child->url, '/') . '*') ? 'active' : '' }}"
                                                href="{{ url($child->url) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $child->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="menu-item">
                                <a class="menu-link {{ Request::is(trim($menu->url, '/') . '*') ? 'active' : '' }}"
                                    href="{{ url($menu->url) }}">
                                    <span class="menu-icon">
                                        <i class="{{ $menu->icon ?? 'ki-outline ki-element-11' }} fs-2"></i>
                                    </span>
                                    <span class="menu-title">{{ $menu->name }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- STATIC MENU IMPLEMENTATION -->

                    <!-- Dashboard -->
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('dashboard') ? 'active' : '' }}"
                            href="{{ url('dashboard') }}">
                            <span class="menu-icon"><i class="ki-outline ki-element-11 fs-2"></i></span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>

                    <!-- Monitoring -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('monitoring*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-chart-line-star fs-2"></i></span>
                            <span class="menu-title">Monitoring</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('monitoring.integrasi') ? 'active' : '' }}"
                                    href="{{ route('monitoring.integrasi') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Integrasi</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('monitoring.pendaftaran.index') ? 'active' : '' }}"
                                    href="{{ route('monitoring.pendaftaran.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Pendaftaran</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pendaftaran -->
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('pendaftaran*') ? 'active' : '' }}"
                            href="{{ route('pendaftaran.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-calendar-add fs-2"></i></span>
                            <span class="menu-title">Pendaftaran</span>
                        </a>
                    </div>

                    <!-- Pasien -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('pasien*') && !Request::is('pasien/gizi*') && !Request::is('pasien/operasi*') && !Request::is('pasien/template*') && !Request::is('pasien/bhp*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-profile-user fs-2"></i></span>
                            <span class="menu-title">Pasien</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('pasien.index') ? 'active' : '' }}"
                                    href="{{ route('pasien.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Pasien</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('pasien.tambah-pasien') ? 'active' : '' }}"
                                    href="{{ route('pasien.tambah-pasien') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Register Pasien Baru</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Rawat Jalan -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('rawat-jalan*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-stethoscope fs-2"></i></span>
                            <span class="menu-title">Rawat Jalan</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('poliklinik') ? 'active' : '' }}"
                                    href="{{ route('poliklinik') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Poliklinik</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('poliklinik-semua') ? 'active' : '' }}"
                                    href="{{ route('poliklinik-semua') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Semua Pasien Poli</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Rawat Inap -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('rawat-inap*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-hospital fs-2"></i></span>
                            <span class="menu-title">Rawat Inap</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.rawat-inap') ? 'active' : '' }}"
                                    href="{{ route('index.rawat-inap') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Dashboard Ranap</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.rawat-bersama') ? 'active' : '' }}"
                                    href="{{ route('index.rawat-bersama') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Pasien Raber</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.rawat-inap-pulang') ? 'active' : '' }}"
                                    href="{{ route('index.rawat-inap-pulang') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Pasien Pulang</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kamar Operasi -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('operasi*') || Request::is('pasien/operasi*') || Request::is('template*') || Request::is('pasien/template*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-mask fs-2"></i></span>
                            <span class="menu-title">Kamar Operasi</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.operasi') ? 'active' : '' }}"
                                    href="{{ route('index.operasi') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Jadwal & Laporan</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.template') ? 'active' : '' }}"
                                    href="{{ route('index.template') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Template Operasi</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.template-anastesi') ? 'active' : '' }}"
                                    href="{{ route('index.template-anastesi') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Template Anastesi</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Penunjang -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('penunjang*') || Request::is('laboratorium*') || Request::is('radiologi*') || Request::is('fisio*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-test-tubes fs-2"></i></span>
                            <span class="menu-title">Penunjang</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <!-- Lab -->
                            <div data-kt-menu-trigger="click"
                                class="menu-item menu-accordion {{ Request::is('laboratorium*') || (Request::is('penunjang*') && request('jenis') == 'Lab') ? 'show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Laboratorium</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('penunjang.antrian') && request('jenis') == 'Lab' ? 'active' : '' }}"
                                            href="{{ route('penunjang.antrian', 'Lab') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Antrian Lab</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('laboratorium.list-pemeriksaan') ? 'active' : '' }}"
                                            href="{{ route('laboratorium.list-pemeriksaan') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Pemeriksaan</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Radiologi -->
                            <div data-kt-menu-trigger="click"
                                class="menu-item menu-accordion {{ Request::is('radiologi*') ? 'show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Radiologi</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('radiologi.antrian') ? 'active' : '' }}"
                                            href="{{ route('radiologi.antrian') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Antrian Radiologi</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('radiologi.index') ? 'active' : '' }}"
                                            href="{{ route('radiologi.index') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Hasil Radiologi</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Fisio -->
                            <div data-kt-menu-trigger="click"
                                class="menu-item menu-accordion {{ Request::is('fisio*') || (Request::is('penunjang*') && request('jenis') == 'Fisio') ? 'show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Fisioterapi</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('penunjang.antrian') && request('jenis') == 'Fisio' ? 'active' : '' }}"
                                            href="{{ route('penunjang.antrian', 'Fisio') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Antrian Fisio</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link {{ Request::routeIs('fisio.index') ? 'active' : '' }}"
                                            href="{{ route('fisio.index') }}">
                                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                            <span class="menu-title">Assesmen Fisio</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farmasi -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('farmasi*') || Request::is('penjualan-obat*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-pill fs-2"></i></span>
                            <span class="menu-title">Farmasi</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('farmasi.antrian-resep') ? 'active' : '' }}"
                                    href="{{ route('farmasi.antrian-resep') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Antrian Resep</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('farmasi.list-resep') ? 'active' : '' }}"
                                    href="{{ route('farmasi.list-resep') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Resep</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('farmasi.list-obat') ? 'active' : '' }}"
                                    href="{{ route('farmasi.list-obat') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Data Obat</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('penjualan-obat.index') ? 'active' : '' }}"
                                    href="{{ route('penjualan-obat.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Penjualan Obat (POS)</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Gizi -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('gizi*') || Request::is('pasien/gizi*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-coffee fs-2"></i></span>
                            <span class="menu-title">Gizi</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::is('pasien/gizi/2') ? 'active' : '' }}"
                                    href="{{ route('index.gizi', 2) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Gizi Ranap</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::is('pasien/gizi/1') ? 'active' : '' }}"
                                    href="{{ route('index.gizi', 1) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Gizi Rajal</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::is('pasien/gizi/3') ? 'active' : '' }}"
                                    href="{{ route('index.gizi', 3) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Gizi UGD</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Billing -->
                    <div class="menu-item">
                        <a class="menu-link {{ Request::is('billing*') ? 'active' : '' }}"
                            href="{{ route('billing.index') }}">
                            <span class="menu-icon"><i class="ki-outline ki-bill fs-2"></i></span>
                            <span class="menu-title">Billing & Journey</span>
                        </a>
                    </div>

                    <!-- Rekam Medis -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('rekap-medis*') || Request::is('surat*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-folder-added fs-2"></i></span>
                            <span class="menu-title">Rekam Medis</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('rekap-medis-index') ? 'active' : '' }}"
                                    href="{{ route('rekap-medis-index', 0) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Berkas RM</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('surat.index') ? 'active' : '' }}"
                                    href="{{ route('surat.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Surat & Dokumen</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- VClaim / BPJS -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('vclaim*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-security-check fs-2"></i></span>
                            <span class="menu-title">VClaim (BPJS)</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('vclaim.rencana-kontrol.index') ? 'active' : '' }}"
                                    href="{{ route('vclaim.rencana-kontrol.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Rencana Kontrol</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('vclaim.rujukan.index') ? 'active' : '' }}"
                                    href="{{ route('vclaim.rujukan.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Rujukan Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('laporan*') || Request::is('partograf*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-chart-pie-simple fs-2"></i></span>
                            <span class="menu-title">Laporan</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('laporan.index') ? 'active' : '' }}"
                                    href="{{ route('laporan.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Kunjungan</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('laporan.bor_los_toi') ? 'active' : '' }}"
                                    href="{{ route('laporan.bor_los_toi') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">RL (Bor/Los/Toi)</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('partograf.index') ? 'active' : '' }}"
                                    href="{{ route('partograf.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Laporan Partograf</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Data Master & Settings -->
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('data-master*') || Request::is('dokter*') || Request::is('karyawan*') || Request::is('bhp*') || Request::is('admin*') || Request::is('settings*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="ki-outline ki-setting-2 fs-2"></i></span>
                            <span class="menu-title">Pengaturan & Master</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.ruangan') ? 'active' : '' }}"
                                    href="{{ route('index.ruangan') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Master Ruangan</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('dokter.index') ? 'active' : '' }}"
                                    href="{{ route('dokter.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Master Dokter</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('dokter.jadwal') ? 'active' : '' }}"
                                    href="{{ route('dokter.jadwal', 2) }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Jadwal Dokter</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('karyawan.index') ? 'active' : '' }}"
                                    href="{{ route('karyawan.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Master Karyawan</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('index.bhp') ? 'active' : '' }}"
                                    href="{{ route('index.bhp') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Tarif & BHP</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('admin.roles.index') ? 'active' : '' }}"
                                    href="{{ route('admin.roles.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Role & Menu</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Request::routeIs('settings.index') ? 'active' : '' }}"
                                    href="{{ route('settings.index') }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">App Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>

                @endif
            </div>
            <!--end::Sidebar menu-->
        </div>
    </div>
    <!--end::Navs-->
    <!--begin::Logout Button-->
    <div class="app-sidebar-logout-sticky p-4">
        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-power fs-5"></i>
                <span class="menu-title"></span>
            </button>
        </form>
    </div>
    <!--end::Logout Button-->
</div>
