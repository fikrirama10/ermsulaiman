@extends('layouts.index')
@section('css')
    <style>
        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }
        .patient-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .patient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .patient-card.selected {
            border-color: #009ef7;
            background-color: #f1faff;
        }
        .visit-type-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .visit-type-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }
        .visit-type-card.selected {
            border-color: #009ef7;
            background-color: #f1faff;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Helper Menu -->
        <div class="helper-menu position-top-right" id="helperMenu">
            <button class="helper-toggle" onclick="toggleHelperMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <span>Menu</span>
            </button>
            <div class="helper-items">
                <div class="helper-label">Registrasi</div>
                <a href="{{ route('pasien.tambah-pasien') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span>Pasien Baru</span>
                </a>
                <a href="{{ route('pasien.index') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Data Pasien</span>
                </a>
                <div class="helper-divider"></div>
                <div class="helper-label">Laporan</div>
                <a href="{{ route('laporan.index') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Laporan RM</span>
                </a>
            </div>
        </div>

        <!-- Toolbar -->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Pendaftaran Pasien
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('pasien.index') }}" class="text-muted text-hover-primary">Pasien</a>
                            </li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                            <li class="breadcrumb-item text-muted">Pendaftaran</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- Search Section -->
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-10">
                        <div class="search-box text-center">
                            <h3 class="fw-bold mb-2">Cari Data Pasien</h3>
                            <p class="text-muted mb-8">Masukkan Nomor Rekam Medis untuk mencari pasien yang sudah terdaftar</p>

                            <div class="input-group input-group-lg mb-4">
                                <input type="text" id="search_no_rm" class="form-control" placeholder="Masukkan No RM..."
                                    autofocus>
                                <button class="btn btn-primary" type="button" onclick="searchPatient()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>

                            <div class="text-muted">
                                <small>Atau belum punya No RM?</small>
                                <a href="#" onclick="showNewPatientForm()" class="text-primary fw-bold ms-1">Daftar Pasien Baru</a>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div id="search_loading" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <!-- Patient Result -->
                        <div id="patient_result" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center p-5 mb-5">
                                <i class="fas fa-check-circle fs-2 me-4"></i>
                                <div>
                                    <h5 class="fw-bold mb-1">Data Pasien Ditemukan</h5>
                                </div>
                            </div>

                            <div class="card patient-card border-2 mb-5">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center mb-3 mb-md-0">
                                            <div class="symbol symbol-100px">
                                                <div class="symbol-label bg-light-success">
                                                    <span class="svg-icon svg-icon-4x svg-icon-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="12" cy="7" r="4"></circle>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <h4 class="fw-bold mb-2" id="patient_nama">-</h4>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">No RM</div>
                                                    <div class="fw-bold" id="patient_no_rm">-</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">NIK</div>
                                                    <div class="fw-bold" id="patient_nik">-</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">Jenis Kelamin</div>
                                                    <div class="fw-bold" id="patient_jk">-</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">Usia</div>
                                                    <div class="fw-bold" id="patient_usia">-</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">No HP</div>
                                                    <div class="fw-bold" id="patient_hp">-</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-muted small">No BPJS</div>
                                                    <div class="fw-bold" id="patient_bpjs">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-4">Pilih Jenis Kunjungan</h5>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card visit-type-card border-2 h-100" onclick="selectVisitType(2)">
                                        <div class="card-body text-center p-5">
                                            <div class="text-primary mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                            </div>
                                            <h5 class="fw-bold mb-2">Rawat Jalan</h5>
                                            <p class="text-muted small">Kunjungan rawat jalan poli</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card visit-type-card border-2 h-100" onclick="selectVisitType(1)">
                                        <div class="card-body text-center p-5">
                                            <div class="text-warning mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                                </svg>
                                            </div>
                                            <h5 class="fw-bold mb-2">Rawat Inap</h5>
                                            <p class="text-muted small">Perawatan di ruang rawat inap</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card visit-type-card border-2 h-100" onclick="selectVisitType(3)">
                                        <div class="card-body text-center p-5">
                                            <div class="text-danger mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                            </div>
                                            <h5 class="fw-bold mb-2">UGD</h5>
                                            <p class="text-muted small">Unit gawat darurat</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Not Found -->
                        <div id="patient_not_found" style="display: none;">
                            <div class="alert alert-warning d-flex align-items-center p-5">
                                <i class="fas fa-exclamation-triangle fs-2 me-4"></i>
                                <div>
                                    <h5 class="fw-bold mb-1">Data Pasien Tidak Ditemukan</h5>
                                    <p class="text-muted mb-0">No RM tidak terdaftar dalam sistem</p>
                                </div>
                            </div>
                            <div class="text-center mt-5">
                                <p class="mb-3">Ingin mendaftarkan pasien baru?</p>
                                <button class="btn btn-primary btn-lg" onclick="showNewPatientForm()">
                                    <i class="fas fa-plus me-2"></i>Daftar Pasien Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let currentPatient = null;

        $(function() {
            // Enter key for search
            $('#search_no_rm').on('keypress', function(e) {
                if(e.which == 13) {
                    searchPatient();
                }
            });
        });

        function searchPatient() {
            const noRm = $('#search_no_rm').val().trim();

            if(!noRm) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan masukkan No RM',
                    confirmButtonColor: '#009ef7'
                });
                return;
            }

            $('#search_loading').show();
            $('#patient_result').hide();
            $('#patient_not_found').hide();

            $.get('{{ route('pasien.search-by-no-rm') }}', { no_rm: noRm }, function(response) {
                $('#search_loading').hide();

                if(response.status) {
                    currentPatient = response.data;
                    displayPatient(currentPatient);
                } else {
                    $('#patient_not_found').fadeIn();
                }
            }).fail(function() {
                $('#search_loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mencari data pasien',
                    confirmButtonColor: '#f1416c'
                });
            });
        }

        function displayPatient(patient) {
            $('#patient_nama').text(patient.nama_pasien);
            $('#patient_no_rm').text(patient.no_rm);
            $('#patient_nik').text(patient.nik || '-');
            $('#patient_jk').text(patient.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
            $('#patient_usia').text(patient.usia_tahun ? patient.usia_tahun + ' Tahun' : '-');
            $('#patient_hp').text(patient.nohp || '-');
            $('#patient_bpjs').text(patient.no_bpjs || '-');

            $('#patient_result').fadeIn();
        }

        function selectVisitType(jenis) {
            if(!currentPatient) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan cari data pasien terlebih dahulu',
                    confirmButtonColor: '#009ef7'
                });
                return;
            }

            window.location.href = '{{ route('pasien.tambah-kunjungan', ['id' => '__id__', 'jenis' => '__jenis__']) }}'
                .replace('__id__', currentPatient.id)
                .replace('__jenis__', jenis);
        }

        function showNewPatientForm() {
            window.location.href = '{{ route('pasien.tambah-pasien') }}';
        }
    </script>
@endsection
