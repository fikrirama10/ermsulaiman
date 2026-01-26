@extends('layouts.index')

@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif !important;
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f3f6f9;
        }

        /* Metronic-like Card Styling with Modern Touch */
        .card.modern-card {
            border: 0;
            box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
            background-color: #ffffff;
            border-radius: 8px;
            /* Slightly smaller radius */
            margin-bottom: 16px;
            /* Reduced margin */
        }

        .card.modern-card .card-header {
            border-bottom: 1px solid #eff2f5;
            padding: 1rem 1.5rem;
            /* Reduced padding */
            min-height: 50px;
            /* Reduced height */
        }

        .card.modern-card .card-title {
            font-weight: 600;
            font-size: 1rem;
            /* Slightly smaller title */
            color: #181c32;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card.modern-card .card-body {
            padding: 1.5rem;
            /* Reduced padding */
        }

        /* Section Icons */
        .card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            /* Smaller icon container */
            height: 28px;
            border-radius: 6px;
            background-color: rgba(0, 158, 247, 0.1);
            color: #009ef7;
            margin-right: 10px;
        }

        .card-icon i {
            font-size: 1.1rem !important;
            /* Smaller icon */
        }

        /* Form Controls - Compact Look */
        .form-control,
        .form-select {
            border: 1px solid #e1e3ea;
            border-radius: 6px;
            padding: 0.55rem 0.75rem;
            /* Reduced padding */
            font-size: 0.9rem;
            /* Slightly smaller font */
            font-weight: 500;
            color: #5e6278;
            background-color: #ffffff;
            transition: all 0.2s ease;
            min-height: auto;
            /* Allow smaller height */
        }

        .form-control-lg {
            font-size: 0.95rem !important;
            padding: 0.65rem 1rem !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 2px rgba(0, 158, 247, 0.1);
            background-color: #ffffff;
        }

        .form-check.form-check-custom {
            margin-top: 0.25rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            /* Smaller label */
            color: #3f4254;
            margin-bottom: 0.25rem;
            /* Tighter label spacing */
        }

        .required::after {
            content: "*";
            color: #f1416c;
            font-weight: bold;
            margin-left: 3px;
        }

        /* Pill Gender Selector - Compact */
        .gender-selector {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .gender-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 8px 15px;
            /* Compact padding */
            border: 1px solid #e1e3ea;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            color: #7e8299;
            transition: all 0.2s;
            background: #fff;
        }

        .gender-option label i {
            margin-right: 6px;
            font-size: 1rem;
        }

        /* RM Display Compact */
        .rm-box {
            background: #f8f9fa;
            border: 1px dashed #009ef7;
            border-radius: 6px;
            padding: 0.75rem;
        }

        .rm-box .rm-value {
            font-family: 'Courier New', monospace;
            font-size: 1.25rem;
            font-weight: 700;
            color: #009ef7;
            letter-spacing: 1px;
        }

        /* Sticky Action Bar Compact */
        .action-bar {
            position: fixed;
            bottom: 0;
            right: 0;
            left: 0;
            background: white;
            padding: 10px 25px;
            /* Reduced padding */
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05);
            z-index: 999;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #eff2f5;
        }

        /* Adjust content margin for sticky footer */
        #kt_app_content {
            margin-bottom: 60px;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Pendaftaran Pasien
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('pasien.index') }}" class="text-muted text-hover-primary">Database
                                    Pasien</a>
                            </li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                            <li class="breadcrumb-item text-muted">Formulir Pendaftaran</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <form method="POST" id="form-create" action="{{ route('pasien.post-tambah-pasien') }}">
                    @csrf

                    <!-- SECTION: SEARCH & RM -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <div class="card modern-card h-100">
                                <div class="card-header">
                                    <div class="card-title">
                                        <span class="card-icon"><i class="bi bi-search fs-3"></i></span>
                                        <span class="d-flex flex-column">
                                            <span class="fw-bold fs-7">Pencarian Data (Integrasi)</span>
                                            <span class="text-muted fs-8 fw-normal">Cari data via NIK Dukcapil atau
                                                BPJS</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Cari NIK (KTP)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="nik" id="nik"
                                                    placeholder="16 Digit NIK" />
                                                <button class="btn btn-primary btn-sm" type="button" id="btn-cari-nik"><i
                                                        class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Cari No. Kartu BPJS</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="bpjs" id="bpjs"
                                                    placeholder="13 Digit No Kartu" />
                                                <button class="btn btn-success btn-sm" type="button" id="btn-cari-bpjs"><i
                                                        class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card modern-card h-100 bg-light-primary border-primary border-dashed">
                                <div class="card-body d-flex flex-row justify-content-between align-items-center p-4">
                                    <div>
                                        <span class="text-gray-600 fs-8 fw-bold text-uppercase d-block mb-1">No. Rekam
                                            Medis</span>
                                        <h2 class="text-primary fs-2 fw-bolder m-0" id="display-rm">{{ $kodepasien }}</h2>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                        id="btn-edit-rm" title="Ubah Manual">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <input type="hidden" name='no_rm' value="{{ $kodepasien }}" id='pasien-kodepasien'
                                        required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: DATA UTAMA -->
                    <div class="card modern-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon"><i class="bi bi-person-lines-fill fs-3"></i></span>
                                <span>Identitas Utama Pasien</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Lengkap</label>
                                    <input type="text" name="nama_pasien" id="nama_pasien"
                                        class="form-control form-control-sm" placeholder="Sesuai KTP / Identitas"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Jenis Kelamin</label>
                                    <div class="gender-selector">
                                        <div class="gender-option">
                                            <input type="radio" name="jenis_kelamin" id="gender_l" value="L"
                                                required>
                                            <label for="gender_l"><i class="bi bi-gender-male"></i> Laki-laki</label>
                                        </div>
                                        <div class="gender-option">
                                            <input type="radio" name="jenis_kelamin" id="gender_p" value="P"
                                                required>
                                            <label for="gender_p"><i class="bi bi-gender-female"></i> Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label required">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control form-control-sm"
                                        placeholder="Kota Kelahiran" required />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Tanggal Lahir</label>
                                    <input class="form-control form-control-sm" name="tgl_lahir" id="tgl_lahir"
                                        placeholder="YYYY-MM-DD" required />
                                    <div class="form-check form-check-custom form-check-solid mt-1">
                                        <input class="form-check-input h-15px w-15px" type="checkbox" name="baru_lahir"
                                            value="1" id="checkBaruLahir" />
                                        <label class="form-check-label text-gray-600 fs-8" for="checkBaruLahir">
                                            Bayi Baru Lahir (Newborn)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Golongan Darah</label>
                                    <select name="golongan_darah" class="form-select form-select-sm" required>
                                        <option value="">Pilih...</option>
                                        @foreach ($gol_darah as $gl)
                                            <option value="{{ $gl->id }}">{{ $gl->golongan_darah }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="separator my-4"></div>

                            <!-- Sub-Section: Kontak & Alamat -->
                            <h5 class="text-dark fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i> Kontak &
                                Domisili</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nomor Handphone / WA</label>
                                    <input type="text" name="no_hp" id="no_hp"
                                        class="form-control form-control-sm" placeholder="Contoh: 081234567890"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control form-control-sm" placeholder="email@domain.com" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label required">Kelurahan / Desa</label>
                                    <select class="form-select form-select-sm" name="id_kel" id='id_kel'
                                        data-placeholder="Ketik minimal 3 karakter..." required>
                                        <option></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label required">Alamat Lengkap</label>
                                    <textarea name="alamat" id="alamat" class="form-control form-control-sm" rows="2"
                                        placeholder="Nama Jalan, No. Rumah, RT/RW" required></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SECTION: Data Sosial & Tambahan -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card modern-card h-100">
                                <div class="card-header">
                                    <div class="card-title">
                                        <span class="card-icon"><i class="bi bi-folder2-open fs-3"></i></span>
                                        <span>Data Sosial</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label required">Agama</label>
                                        <select name="id_agama" class="form-select form-select-sm"
                                            data-placeholder="Pilih Agama" data-control="select2" required>
                                            <option></option>
                                            @foreach ($data_agama as $da)
                                                <option value="{{ $da->id }}">{{ $da->agama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Etnis / Suku</label>
                                        <select name="id_etnis" class="form-select form-select-sm"
                                            data-placeholder="Pilih Etnis / Suku" data-control="select2" required>
                                            <option></option>
                                            @foreach ($data_etnis as $de)
                                                <option value="{{ $de->id }}">{{ $de->etnis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Pendidikan Terakhir</label>
                                        <select name="id_pendidikan" class="form-select form-select-sm"
                                            data-placeholder="Pilih Pendidikan Terakhir" data-control="select2" required>
                                            <option></option>
                                            @foreach ($data_pendidikan as $dp)
                                                <option value="{{ $dp->id }}">{{ $dp->pendidikan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Status Pernikahan</label>
                                        <select name="id_hubungan_pernikakan" class="form-select form-select-sm"
                                            data-placeholder="Pilih Status Pernikahan" data-control="select2" required>
                                            <option></option>
                                            @foreach ($data_hubungan as $dh)
                                                <option value="{{ $dh->id }}">{{ $dh->hubungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card modern-card h-100">
                                <div class="card-header">
                                    <div class="card-title">
                                        <span class="card-icon"><i class="bi bi-briefcase fs-3"></i></span>
                                        <span>Pekerjaan & Data Lain</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label required">Pekerjaan</label>
                                        <select name="id_pekerjaan" class="form-select form-select-sm"
                                            data-placeholder="Pilih Pekerjaan" data-control="select2" required>
                                            <option></option>
                                            @foreach ($data_pekerjaan as $dp)
                                                <option value="{{ $dp->id }}">{{ $dp->pekerjaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">NRP / NIP</label>
                                            <input type="text" name="nrp" class="form-control form-control-sm"
                                                placeholder="Opsional" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pangkat</label>
                                            <input type="text" name="pangkat" class="form-control form-control-sm"
                                                placeholder="Opsional" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kesatuan / Instansi</label>
                                        <input type="text" name="kesatuan" class="form-control form-control-sm"
                                            placeholder="Opsional" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Status Pasien</label>
                                        <div class="input-group input-group-sm">
                                            <select name="status_pasien" class="form-select form-select-sm" required>
                                                @foreach ($data_status as $ds)
                                                    <option value="{{ $ds->id }}">{{ $ds->d_status }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" name='pasien_lama'
                                                    value="1" id="checkPasienLama" aria-label="Pasien Lama">
                                                <label class="ms-2 mb-0 fs-8" for="checkPasienLama">Lama</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: Penanggung Jawab -->
                    <div class="card modern-card mt-3">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon"><i class="bi bi-people fs-3"></i></span>
                                <span>Penanggung Jawab</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nama Penanggung Jawab</label>
                                    <input type="text" name="penanggung_jawab" class="form-control form-control-sm"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Hubungan</label>
                                    <select name="hubungan" class="form-select form-select-sm"
                                        data-placeholder="Pilih Hubungan" data-control="select2" required>
                                        <option></option>
                                        @foreach ($pasien_penanggungjawab as $dh)
                                            <option value="{{ $dh->id }}">{{ $dh->penaggungjawab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">No. Telepon / HP</label>
                                    <input type="text" name="no_tlp_penanggung_jawab"
                                        class="form-control form-control-sm" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Alamat Penanggung Jawab</label>
                                    <input type="text" name="alamat_penanggung_jawab"
                                        class="form-control form-control-sm" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SPACE UNTUK ACTION BAR -->
                    <div style="height: 60px;"></div>

                    <!-- ACTION BAR -->
                    <div class="action-bar">
                        <a href="{{ route('pasien.index') }}" class="btn btn-light btn-active-light-primary">Batal</a>
                        <button type="button" id='button-tambah' class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Data Pasien
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.js"></script>
    <script>
        const form = document.getElementById('form-create');

        // Setup FormValidation
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    nama_pasien: {
                        validators: {
                            notEmpty: {
                                message: 'Nama harus diisi'
                            }
                        }
                    },
                    no_rm: {
                        validators: {
                            notEmpty: {
                                message: 'No RM harus diisi'
                            }
                        }
                    },
                    jenis_kelamin: {
                        validators: {
                            notEmpty: {
                                message: 'Pilih jenis kelamin'
                            }
                        }
                    },
                    golongan_darah: {
                        validators: {
                            notEmpty: {
                                message: 'Pilih golongan darah'
                            }
                        }
                    },
                    tempat_lahir: {
                        validators: {
                            notEmpty: {
                                message: 'Tempat lahir harus diisi'
                            }
                        }
                    },
                    tgl_lahir: {
                        validators: {
                            notEmpty: {
                                message: 'Tgl lahir harus diisi'
                            }
                        }
                    },
                    id_agama: {
                        validators: {
                            notEmpty: {
                                message: 'Agama harus dipilih'
                            }
                        }
                    },
                    id_etnis: {
                        validators: {
                            notEmpty: {
                                message: 'Etnis harus dipilih'
                            }
                        }
                    },
                    id_pendidikan: {
                        validators: {
                            notEmpty: {
                                message: 'Pendidikan harus dipilih'
                            }
                        }
                    },
                    id_hubungan_pernikakan: {
                        validators: {
                            notEmpty: {
                                message: 'Status pernikahan harus dipilih'
                            }
                        }
                    },
                    no_hp: {
                        validators: {
                            notEmpty: {
                                message: 'No HP harus diisi'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email harus diisi'
                            },
                            emailAddress: {
                                message: 'Format email salah'
                            }
                        }
                    },
                    id_kel: {
                        validators: {
                            notEmpty: {
                                message: 'Kelurahan harus dipilih'
                            }
                        }
                    },
                    alamat: {
                        validators: {
                            notEmpty: {
                                message: 'Alamat harus diisi'
                            }
                        }
                    },
                    id_pekerjaan: {
                        validators: {
                            notEmpty: {
                                message: 'Pekerjaan harus dipilih'
                            }
                        }
                    },
                    penanggung_jawab: {
                        validators: {
                            notEmpty: {
                                message: 'Nama PJ harus diisi'
                            }
                        }
                    },
                    hubungan: {
                        validators: {
                            notEmpty: {
                                message: 'Hubungan harus dipilih'
                            }
                        }
                    },
                    no_tlp_penanggung_jawab: {
                        validators: {
                            notEmpty: {
                                message: 'No Tlp PJ harus diisi'
                            }
                        }
                    },
                    alamat_penanggung_jawab: {
                        validators: {
                            notEmpty: {
                                message: 'Alamat PJ harus diisi'
                            }
                        }
                    }
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.input-group, .row > .col-md-6, .row > .col-md-4, .row > .col-md-12, .mb-4',
                        eleInvalidClass: 'is-invalid',
                        eleValidClass: 'is-valid'
                    })
                }
            }
        );

        // Revalidate Select2 & Radios on change
        $(form).find('select').on('change', function() {
            validator.revalidateField($(this).attr('name'));
        });
        $(form).find('input[type="radio"]').on('change', function() {
            validator.revalidateField($(this).attr('name'));
        });

        // Submit Action
        $('#button-tambah').on('click', function(e) {
            e.preventDefault();
            validator.validate().then(function(status) {
                if (status == 'Valid') {
                    Swal.fire({
                        title: 'Simpan Data?',
                        text: "Pastikan data yang diinput sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-light"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    Swal.fire({
                        text: "Mohon lengkapi kolom yang bertanda merah.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, Cek Lagi",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });

        // Edit RM
        $('#btn-edit-rm').on('click', function() {
            Swal.fire({
                title: 'Edit No. Rekam Medis',
                input: 'text',
                inputValue: $('#pasien-kodepasien').val(),
                showCancelButton: true,
                confirmButtonText: 'Update'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $('#pasien-kodepasien').val(result.value);
                    $('#display-rm').text(result.value);
                }
            });
        });

        // Search Handlers
        function performSearch(val, type) {
            if (!val) {
                toastr.error('Input tidak boleh kosong');
                return;
            }

            $.blockUI({
                message: '<div class="p-3">Sedang mencari data...</div>'
            });

            $.ajax({
                url: "{{ route('pasien.get-by-nik') }}",
                type: 'GET',
                data: {
                    nik: val,
                    jenis: type
                },
                success: function(res) {
                    $.unblockUI();
                    if (res.status) {
                        toastr.success('Data ditemukan!');
                        fillData(res.data.peserta);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function() {
                    $.unblockUI();
                    toastr.error('Gagal menghubungi server');
                }
            });
        }

        function fillData(data) {
            $('#nik').val(data.nik);
            $('#bpjs').val(data.noKartu);
            $('#nama_pasien').val(data.nama);
            $('#tgl_lahir').val(data.tglLahir);
            $('#no_hp').val(data.mr.noTelepon);

            if (data.sex == 'L') $('#gender_l').prop('checked', true);
            else $('#gender_p').prop('checked', true);

            // Revalidate filled fields
            validator.revalidateField('nama_pasien');
            validator.revalidateField('tgl_lahir');
        }

        $('#btn-cari-nik').click(() => performSearch($('#nik').val(), 'nik'));
        $('#btn-cari-bpjs').click(() => performSearch($('#bpjs').val(), 'bpjs'));

        // Init Plugins
        $("#tgl_lahir").flatpickr({
            maxDate: "today",
            dateFormat: "Y-m-d"
        });

        $("#id_kel").select2({
            ajax: {
                url: "{{ route('pasien.cari-kelurahan') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term
                }),
                processResults: data => ({
                    results: data.result.map(x => ({
                        id: x.id,
                        text: x.text
                    }))
                })
            },
            minimumInputLength: 3
        });

        // Show server alerts
        @if (session('berhasil'))
            Swal.fire('Berhasil', '{{ session('berhasil') }}', 'success');
        @endif
        @if (session('gagal'))
            Swal.fire('Gagal', '{{ session('gagal') }}', 'error');
        @endif
    </script>
@endsection
