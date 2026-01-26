@extends('layouts.index')
@section('css')
    <style>
        .blur-background {
            filter: blur(5px);
            transition: filter 0.3s;
        }

        .profile-header-gradient {
            background-color: #202639;
            /* Fallback */
            background: linear-gradient(112.1deg, rgb(32, 38, 57) 11.4%, rgb(63, 76, 119) 70.2%);
        }

        .stat-icon-wrapper {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        /* Refined Timeline */
        .timeline-label:before {
            left: 50px !important;
            background-color: #e4e6ef;
        }

        .timeline-label .timeline-label {
            width: 50px !important;
            color: #7e8299;
            font-weight: 600;
        }

        /* Compact Table for Profile */
        .table-profile td {
            padding: 0.75rem 0.5rem;
            border-bottom: 1px dashed #e4e6ef;
            font-size: 0.95rem;
        }

        .table-profile tr:last-child td {
            border-bottom: none;
        }

        .table-profile td:first-child {
            font-weight: 600;
            color: #5e6278;
            width: 35%;
        }

        .table-profile td:last-child {
            font-weight: 500;
            color: #181c32;
        }

        /* Custom Tab Styling for Clarity */
        .nav-line-tabs .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 2px solid transparent;
        }

        .nav-line-tabs .nav-item .nav-link.active {
            color: #ffffff;
            border-bottom: 2px solid #ffffff;
            font-weight: 700;
        }

        .nav-line-tabs .nav-item .nav-link:hover {
            color: #ffffff;
        }

        /* Stat Card Refinement */
        .card-stat {
            transition: transform 0.2s;
            border: 1px solid #f1f1f4;
        }

        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- Header: Profile Summary -->
                <div class="card mb-5 mb-xl-8 profile-header-gradient border-0 shadow-sm">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    @if ($pasien->jenis_kelamin == 'L')
                                        <div
                                            class="symbol-label bg-info bg-opacity-10 border border-white border-opacity-25">
                                            <i class="ki-outline ki-profile-user fs-5x text-info"></i>
                                        </div>
                                    @else
                                        <div
                                            class="symbol-label bg-danger bg-opacity-10 border border-white border-opacity-25">
                                            <i class="ki-outline ki-profile-user fs-5x text-danger"></i>
                                        </div>
                                    @endif
                                    <div
                                        class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#"
                                                class="text-white text-hover-light fs-1 fw-bold me-1">{{ $pasien->nama_pasien }}</a>
                                            <span
                                                class="badge badge-light-success fw-bold ms-2 fs-7 px-3 py-1 rounded-pill">Pasien
                                                Umum</span>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2 text-white text-opacity-75">
                                            <span class="d-flex align-items-center me-5 mb-2">
                                                <i class="ki-outline ki-barcode fs-4 me-1 text-white text-opacity-75"></i>
                                                {{ $pasien->no_rm }}
                                            </span>
                                            <span class="d-flex align-items-center me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1 text-white text-opacity-75"></i>
                                                {{ $pasien->usia_tahun }} Tahun
                                            </span>
                                            <span class="d-flex align-items-center mb-2">
                                                @if ($pasien->jenis_kelamin == 'L')
                                                    <i class="ki-outline ki-user fs-4 me-1 text-white text-opacity-75"></i>
                                                    Laki-Laki
                                                @else
                                                    <i
                                                        class="ki-outline ki-user-square fs-4 me-1 text-white text-opacity-75"></i>
                                                    Perempuan
                                                @endif
                                            </span>
                                        </div>
                                    </div>


                                    <div class="d-flex my-4">
                                        <a href="{{ route('pasien.index') }}"
                                            class="btn btn-sm btn-light btn-active-light-primary me-2">
                                            <i class="ki-outline ki-arrow-left fs-3"></i> Kembali
                                        </a>

                                        <!-- Dropdown Cetak -->
                                        <div class="me-2">
                                            <button class="btn btn-sm btn-light-info btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                <i class="ki-outline ki-printer fs-3 me-1"></i> Cetak
                                                <span class="svg-icon fs-5 ms-1 me-0"><i
                                                        class="ki-outline ki-down fs-5"></i></span>
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('pasien.cetak-rm', $pasien->id) }}" target="_blank"
                                                        class="menu-link px-3">
                                                        <i class="ki-outline ki-file-added fs-5 me-2"></i> Formulir RM
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#modalCetakLabel" class="menu-link px-3">
                                                        <i class="ki-outline ki-purchase fs-5 me-2"></i> Label Pasien
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('pasien.cetak-map', $pasien->id) }}" target="_blank"
                                                        class="menu-link px-3">
                                                        <i class="ki-outline ki-map fs-5 me-2"></i> Label Map
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('pasien.cetak-gelang', $pasien->id) }}"
                                                        target="_blank" class="menu-link px-3">
                                                        <i class="ki-outline ki-wristband fs-5 me-2"></i> Gelang Pasien
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('pasien.tambah-kunjungan', [$pasien->id, 2]) }}"
                                            class="btn btn-sm btn-primary btn-shadow">
                                            <i class="ki-outline ki-plus-square fs-2"></i> Kunjungan Baru
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap flex-stack">
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <div class="d-flex flex-wrap">
                                            <div
                                                class="bg-dark bg-opacity-25 rounded min-w-125px py-3 px-4 me-6 mb-3 border border-white border-opacity-10">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold text-white counted">
                                                        {{ $pasien->no_bpjs ?? '-' }}</div>
                                                </div>
                                                <div class="fw-semibold fs-7 text-white text-opacity-50">No BPJS</div>
                                            </div>
                                            <div
                                                class="bg-dark bg-opacity-25 rounded min-w-125px py-3 px-4 me-6 mb-3 border border-white border-opacity-10">
                                                <div class="d-flex align-items-center">
                                                    <div class="fs-2 fw-bold text-white counted">{{ $pasien->nohp ?? '-' }}
                                                    </div>
                                                </div>
                                                <div class="fw-semibold fs-7 text-white text-opacity-50">No Handphone</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Tabs -->
                        <div class="d-flex overflow-auto">
                            <ul
                                class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                                <li class="nav-item">
                                    <a class="nav-link text-active-white active pb-4" data-bs-toggle="tab"
                                        data-bs-target="#kt_tab_overview" href="#kt_tab_overview">Ringkasan Medis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-active-white pb-4" data-bs-toggle="tab"
                                        data-bs-target="#kt_tab_profile" href="#kt_tab_profile">Profil Biodata</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-active-white pb-4" data-bs-toggle="tab"
                                        data-bs-target="#kt_tab_history" href="#kt_tab_history">Riwayat Berobat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-active-white pb-4" data-bs-toggle="tab"
                                        data-bs-target="#kt_tab_penunjang" href="#kt_tab_penunjang">Lab & Radiologi</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">

                    <!-- TAB 1: Ringkasan / Overview -->
                    <div class="tab-pane fade show active" id="kt_tab_overview" role="tabpanel">
                        <!-- Vital Signs Row -->
                        @if ($detail_rekap_medis)
                            <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
                                <div class="col-xl-3 col-6">
                                    <div class="card card-stat card-flush h-100">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-danger">
                                                    <i class="ki-outline ki-heart fs-2x text-danger"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-600 fw-bold fs-7">Tekanan Darah</span>
                                                <span
                                                    class="fs-3 fw-bolder text-dark">{{ $pemeriksaan_fisik->tekanan_darah ?? '-' }}
                                                    <small class="fs-6 fw-normal text-muted">mmHg</small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-6">
                                    <div class="card card-stat card-flush h-100">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-arrow-up fs-2x text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-600 fw-bold fs-7">Tinggi Badan</span>
                                                <span
                                                    class="fs-3 fw-bolder text-dark">{{ $pemeriksaan_fisik->tinggi_badan ?? '-' }}
                                                    <small class="fs-6 fw-normal text-muted">cm</small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-6">
                                    <div class="card card-stat card-flush h-100">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-success">
                                                    <i class="ki-outline ki-handcart fs-2x text-success"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-600 fw-bold fs-7">Berat Badan</span>
                                                <span
                                                    class="fs-3 fw-bolder text-dark">{{ $pemeriksaan_fisik->berat_badan ?? '-' }}
                                                    <small class="fs-6 fw-normal text-muted">kg</small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-6">
                                    <div class="card card-stat card-flush h-100">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-light-warning">
                                                    <i class="ki-outline ki-chart-pie-simple fs-2x text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-600 fw-bold fs-7">BMI (Index)</span>
                                                <span
                                                    class="fs-3 fw-bolder text-dark">{{ $pemeriksaan_fisik->bmi == 'NaN' ? '-' : $pemeriksaan_fisik->bmi }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                                <i class="ki-outline ki-message-text-2 fs-2hx text-primary me-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-1">Belum Ada Data Rekam Medis</h5>
                                    <span>Pasien ini belum memiliki riwayat pemeriksaan fisik atau kunjungan yang
                                        selesai.</span>
                                </div>
                            </div>
                        @endif

                        <div class="row g-xl-8">
                            <!-- Timeline Kunjungan Terakhir -->
                            <div class="col-xl-8">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-7">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark fs-3">Kunjungan Terakhir</span>
                                            <span class="text-muted mt-1 fw-semibold fs-7">Ringkasan SOAP dari kunjungan
                                                terakhir</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <span
                                                class="badge badge-light-primary fw-bold me-2">{{ $detail_rekap_medis?->tglmasuk ? date('d M Y', strtotime($detail_rekap_medis?->tglmasuk)) : '-' }}</span>
                                        </div>
                                        <div class="card-body">
                                            @if ($detail_rekap_medis)
                                                <div class="timeline-label">
                                                    <!-- Anamnesa -->
                                                    <div class="timeline-item">
                                                        <div class="timeline-label fw-bold text-gray-800 fs-5">S</div>
                                                        <div class="timeline-badge">
                                                            <i class="ki-outline ki-message-text-2 text-primary fs-1"></i>
                                                        </div>
                                                        <div class="timeline-content ps-3">
                                                            <div class="fw-bold text-gray-800 fs-6 mb-1">Subjective
                                                                (Keluhan)</div>
                                                            <div class="text-gray-600 bg-light p-3 rounded">
                                                                {{ $detail_rekap_medis?->anamnesa ?? 'Tidak ada data' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Diagnosa -->
                                                    <div class="timeline-item">
                                                        <div class="timeline-label fw-bold text-gray-800 fs-5">O</div>
                                                        <div class="timeline-badge">
                                                            <i class="ki-outline ki-pulse text-danger fs-1"></i>
                                                        </div>
                                                        <div class="timeline-content ps-3">
                                                            <div class="fw-bold text-gray-800 fs-6 mb-1">Objective
                                                                (Pemeriksaan Fisik)</div>
                                                            <div class="text-gray-600 bg-light p-3 rounded">
                                                                @if ($pemeriksaan_fisik)
                                                                    <div class="d-flex gap-4">
                                                                        <div><span class="fw-bold">Nadi:</span>
                                                                            {{ $pemeriksaan_fisik->nadi ?? '-' }} bpm</div>
                                                                        <div><span class="fw-bold">Suhu:</span>
                                                                            {{ $pemeriksaan_fisik->suhu ?? '-' }} °C</div>
                                                                        <div><span class="fw-bold">RR:</span>
                                                                            {{ $pemeriksaan_fisik->pernapasan ?? '-' }} x/m
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    Tidak ada data
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Assessment -->
                                                    <div class="timeline-item">
                                                        <div class="timeline-label fw-bold text-gray-800 fs-5">A</div>
                                                        <div class="timeline-badge">
                                                            <i class="ki-outline ki-file-added text-success fs-1"></i>
                                                        </div>
                                                        <div class="timeline-content ps-3">
                                                            <div class="fw-bold text-gray-800 fs-6 mb-1">Assessment
                                                                (Diagnosa)</div>
                                                            <div
                                                                class="text-gray-600 bg-light p-3 rounded fw-bold text-dark">
                                                                {{ $detail_rekap_medis?->diagnosa ?? 'Tidak ada data' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Plan / Terapi -->
                                                    <div class="timeline-item">
                                                        <div class="timeline-label fw-bold text-gray-800 fs-5">P</div>
                                                        <div class="timeline-badge">
                                                            <i class="ki-outline ki-capsule text-info fs-1"></i>
                                                        </div>
                                                        <div class="timeline-content ps-3">
                                                            <div class="fw-bold text-gray-800 fs-6 mb-1">Plan (Terapi/Obat)
                                                            </div>
                                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                                @if ($terapi_obat && json_decode($terapi_obat))
                                                                    @foreach (json_decode($terapi_obat) as $to)
                                                                        @foreach ($obat as $o)
                                                                            @if ($o->id == $to->obat)
                                                                                <div
                                                                                    class="d-flex align-items-center px-3 py-2 border rounded bg-light-info border-info text-info fw-bold">
                                                                                    <i
                                                                                        class="ki-outline ki-capsule fs-4 me-2 text-info"></i>
                                                                                    {{ $o->nama_obat }}
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">Tidak ada data terapi</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-10">
                                                    <img src="{{ asset('assets/media/illustrations/sketchy-1/5.png') }}"
                                                        class="h-150px mb-4" alt="" />
                                                    <div class="text-muted fs-6 fw-bold">Belum ada kunjungan selesai.</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-7">
                                        <h3 class="card-title fw-bold text-dark fs-3">Riwayat Diagnosa</h3>
                                    </div>
                                    <div class="card-body pt-0">
                                        @if (count($soap_icdx) > 0 || count($detail_rekap_medis_all) > 0)
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-hover table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                    <thead>
                                                        <tr class="fw-bold text-muted bg-light">
                                                            <th class="ps-4 rounded-start min-w-50px">Kode ICD-10</th>
                                                            <th class="pe-4 rounded-end min-w-100px text-end">Tanggal
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($soap_icdx as $icd)
                                                            <tr>
                                                                <td class="ps-4"><span
                                                                        class="badge badge-light-danger fw-bolder px-3 py-2">{{ $icd->icd10 }}</span>
                                                                </td>
                                                                <td class="text-end text-muted pe-4 fw-semibold">
                                                                    {{ date('d M Y', strtotime($icd->tglmasuk)) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div
                                                class="d-flex flex-column align-items-center justify-content-center py-10">
                                                <i class="ki-outline ki-document text-gray-300 fs-4x mb-3"></i>
                                                <div class="text-gray-400 fw-bold">Belum ada riwayat ICD-10</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- TAB 2: Profil Lengkap -->
                    <div class="tab-pane fade" id="kt_tab_profile" role="tabpanel">
                        <div class="row g-5 g-xl-8">
                            <div class="col-xl-6">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title fw-bold text-dark"><i
                                                class="ki-outline ki-profile-circle fs-2 me-2 text-primary"></i> Data
                                            Demografis</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-profile">
                                            <tr>
                                                <td>Tempat Lahir</td>
                                                <td>{{ $pasien->tempat_lahir }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Lahir</td>
                                                <td>{{ date('d M Y', strtotime($pasien->tgllahir)) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Agama</td>
                                                <td>{{ $pasien->agama->agama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status Nikah</td>
                                                <td>{{ $pasien->status_pernikahan ?? '-' }}</td>
                                            </tr> <!-- Assumption on field name -->
                                            <tr>
                                                <td>Pendidikan</td>
                                                <td>{{ $pasien->pendidikan->pendidikan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pekerjaan</td>
                                                <td>{{ $pasien->pekerjaan->pekerjaan ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title fw-bold text-dark"><i
                                                class="ki-outline ki-map fs-2 me-2 text-primary"></i> Kontak & Alamat</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-profile">
                                            <tr>
                                                <td>No HP</td>
                                                <td>{{ $pasien->nohp }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>{{ $pasien->email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td>{{ $pasien->alamat?->alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Kelurahan</td>
                                                <td>{{ $pasien->alamat?->kelurahan ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title fw-bold text-dark"><i
                                                class="ki-outline ki-award fs-2 me-2 text-primary"></i> Data Kepangkatan
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-profile">
                                            <tr>
                                                <td>Kesatuan</td>
                                                <td>{{ $pasien->kesatuan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pangkat</td>
                                                <td>{{ $pasien->pangkat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>NRP</td>
                                                <td>{{ $pasien->nrp ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card card-flush h-100 shadow-sm">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title fw-bold text-dark"><i
                                                class="ki-outline ki-people fs-2 me-2 text-primary"></i> Penanggung Jawab
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-profile">
                                            <tr>
                                                <td>Nama</td>
                                                <td>{{ $pasien->penanggung_jawab ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Hubungan</td>
                                                <td>{{ $pasien->hubungan_pj ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>No Telp</td>
                                                <td>{{ $pasien->nohp_penanggungjawab ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td>{{ $pasien->alamat_penanggunjawab ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Riwayat Kunjungan (The Big Table) -->
                    <div class="tab-pane fade" id="kt_tab_history" role="tabpanel">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header pt-5">
                                <h3 class="card-title fw-bold">Semua Riwayat Kunjungan</h3>
                                <div class="card-toolbar">
                                    <button class="btn btn-sm btn-light-success me-2"><i
                                            class="ki-outline ki-file-down fs-4"></i> Export Excel</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tbl-riwayat"
                                        class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4 rounded-start min-w-100px">Jenis</th>
                                                <th class="min-w-100px">Pembayaran</th>
                                                <th class="min-w-150px">Poli / Dokter</th>
                                                <th class="min-w-100px">Masuk</th>
                                                <th class="min-w-100px">Pulang</th>
                                                <th class="pe-4 rounded-end min-w-100px">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- DataTables Server Side -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: Penunjang -->
                    <div class="tab-pane fade" id="kt_tab_penunjang" role="tabpanel">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header pt-5">
                                <h3 class="card-title fw-bold">Riwayat Lab & Radiologi</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 rounded-start">Jenis Pemeriksaan</th>
                                            <th>Kategori</th>
                                            <th>Tanggal</th>
                                            <th class="text-end pe-4 rounded-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($penunjang as $p)
                                            @if ($p->pemeriksaan_penunjang != 'null')
                                                <tr>
                                                    <td class="ps-4 fw-bold text-gray-800">{{ $p->pemeriksaan_penunjang }}
                                                    </td>
                                                    <td>
                                                        @if ($p->jenis_penunjang == 'Radiologi')
                                                            <span
                                                                class="badge badge-light-danger fw-bold">{{ $p->jenis_penunjang }}</span>
                                                        @elseif($p->jenis_penunjang == 'Lab')
                                                            <span
                                                                class="badge badge-light-warning fw-bold">{{ $p->jenis_penunjang }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-light-info fw-bold">{{ $p->jenis_penunjang }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">
                                                        {{ date('d M Y H:i', strtotime($p->created_at)) }}</td>
                                                    <td class="text-end pe-4">
                                                        <button class="btn btn-icon btn-sm btn-light-primary"><i
                                                                class="ki-outline ki-eye fs-4"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if (count($penunjang) == 0)
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-5">
                                                    <i class="ki-outline ki-folder text-gray-300 fs-3x d-block mb-3"></i>
                                                    Tidak ada data penunjang
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" tabindex="-1" id="modal-dokter">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="modal-hasil"></div>
            </div>
        </div>
    </div>

    <!-- Modal Cetak Label -->
    <div class="modal fade" tabindex="-1" id="modalCetakLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Cetak Label Pasien</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pasien.cetak-label', $pasien->id) }}" method="GET" target="_blank">
                        <div class="mb-5">
                            <label class="form-label required">Jumlah Copy</label>
                            <input type="number" name="copies" class="form-control" value="3" min="1"
                                max="10">
                            <div class="form-text">Jumlah stiker yang akan dicetak.</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Ukuran Label</label>
                            <select name="size" class="form-select">
                                <option value="60x40">Label Medis (60x40mm)</option>
                                <option value="standard">Standard (50x30mm)</option>
                            </select>
                            <div class="form-text">Pastikan printer label sudah dikonfigurasi ke ukuran kertas 60mm x 40mm.
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" onclick="$('#modalCetakLabel').modal('hide')">
                                <i class="ki-outline ki-printer fs-2 me-1"></i> Cetak Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.js"></script>
    <script>
        // Password Prompt Logic (Security)
        function promptPassword() {
            document.getElementById('kt_app_content').classList.add('blur-background');
            Swal.fire({
                title: 'Verifikasi Identitas',
                text: 'Masukkan kode akses / password anda',
                icon: 'info',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off',
                    required: 'true'
                },
                showCancelButton: false,
                confirmButtonText: 'Buka Data',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                preConfirm: (password) => {
                    if (!password) {
                        Swal.showValidationMessage('Password wajib diisi');
                        return false;
                    }
                    return new Promise((resolve) => {
                        $.ajax({
                            url: '{{ route('pasien.check-password') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                password: password,
                                pasien_id: "{{ $pasien->id }}"
                            },
                            success: function(response) {
                                if (response.status === 'success') resolve(password);
                                else {
                                    Swal.showValidationMessage(
                                        'Password salah / Akses ditolak');
                                    resolve(false);
                                }
                            },
                            error: function() {
                                Swal.showValidationMessage('Terjadi kesalahan server');
                                resolve(false);
                            }
                        });
                    });
                }
            }).then((result) => {
                document.getElementById('kt_app_content').classList.remove('blur-background');
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Akses Diberikan',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else promptPassword();
            });
        }

        if ({!! $cek_credential !!} < 1) {
            promptPassword();
        }

        // Force Tab Initialization (Fix for "Tabs Not Working")
        var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        });

        // DataTable Riwayat
        $(function() {
            $("#tbl-riwayat").DataTable({
                language: {
                    zeroRecords: "<div class='text-center py-5'>Tidak ada riwayat kunjungan</div>",
                    processing: "Memuat data...",
                    search: "",
                    searchPlaceholder: "Cari riwayat...",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Prev"
                    }
                },
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: 'jenis',
                        name: 'rawat_jenis.jenis',
                        render: function(data) {
                            return `<span class="badge badge-light-primary fw-bold px-3 py-2">${data}</span>`;
                        }
                    },
                    {
                        data: 'bayar',
                        name: 'rawat_bayar.bayar',
                        render: function(data, type, row) {
                            let badge = data.includes('BPJS') ? 'badge-light-success text-success' :
                                'badge-light-info text-info';
                            let sepLink = row.no_sep ?
                                `<div class="mt-2"><a href="javascript:void(0)" onclick="handleSepClick('${row.no_sep}')" class="badge badge-outline badge-primary hover-elevate-up" style="cursor:pointer"><i class="ki-outline ki-file fs-7 me-1"></i> ${row.no_sep}</a></div>` :
                                '';
                            return `<span class="badge ${badge} fw-bolder">${data}</span>${sepLink}`;
                        }
                    },
                    {
                        data: 'poli_dokter',
                        name: 'poli_dokter',
                        render: function(data, type, row) {
                            return `<div class="d-flex flex-column"><a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6">${row.nama_dokter}</a><span class="text-muted fw-semibold fs-7">${row.poli}</span></div>`;
                        }
                    },
                    {
                        data: 'tglmasuk',
                        name: 'tglmasuk'
                    },
                    {
                        data: 'tglpulang',
                        name: 'tglpulang'
                    },
                    {
                        data: 'status',
                        name: 'rawat_status.status',
                        render: function(data) {
                            let color = 'success';
                            if (data && data.toLowerCase().includes('rawat')) color = 'warning';
                            if (data && data.toLowerCase().includes('rujuk')) color = 'danger';
                            return `<span class="badge badge-light-${color} fw-bold px-3">${data}</span>`;
                        }
                    }
                ]
            });
        });

        function handleSepClick(sep) {
            $.blockUI({
                message: '<div class="d-flex align-items-center justify-content-center py-3"><div class="spinner-border text-primary me-3"></div> Loading Data SEP...</div>',
                css: {
                    backgroundColor: 'transparent',
                    border: 'none',
                    color: '#fff'
                }
            });
            $.ajax({
                url: '{{ url('pasien/show-sep') }}/' + sep,
                type: 'GET',
                success: function(response) {
                    $.unblockUI();
                    $('#modal-hasil').html(response);
                    $('#modal-dokter').modal('show');
                },
                error: function(xhr) {
                    $.unblockUI();
                    toastr.error('Gagal memuat SEP');
                }
            });
        }
    </script>
@endsection
