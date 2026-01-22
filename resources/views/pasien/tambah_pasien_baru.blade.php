@extends('layouts.index')
@section('css')
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Tambah Pasien</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('pasien.index') }}" class="text-muted text-hover-primary">Pasien</a>
                            </li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                            <li class="breadcrumb-item text-muted">Tambah Baru</li>
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
                    
                    <!-- Card: Identitas Utama (Top Section) -->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Identitas Utama</h3>
                            </div>
                        </div>
                        <div id="kt_account_profile_details" class="collapse show">
                            <div class="card-body border-top p-9">
                                <!-- Row 1: Search & Auto-fill -->
                                <div class="row mb-8 p-5 bg-light-primary rounded border border-primary border-dashed">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <label class="form-label fs-6 fw-bold mb-3">Pencarian Capil (NIK)</label>
                                        <div class="input-group">
                                            <input type="text" name='nik' id='nik' class="form-control form-control-solid" placeholder="Masukkan NIK KTP"/>
                                            <button class="btn btn-primary" type="button" id="btn-cari-nik"><i class="ki-outline ki-magnifier fs-2"></i> Cari NIK</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label fs-6 fw-bold mb-3">Pencarian BPJS</label>
                                        <div class="input-group">
                                            <input type="text" name='bpjs' id='bpjs' class="form-control form-control-solid" placeholder="Masukkan No Kartu BPJS"/>
                                            <button class="btn btn-success" type="button" id="btn-cari-bpjs"><i class="ki-outline ki-magnifier fs-2"></i> Cari BPJS</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: No RM & Nama -->
                                <div class="row mb-6">
                                    <div class="col-lg-4">
                                        <label class="form-label required fw-bold fs-6">No Rekam Medis</label>
                                        <div class="input-group">
                                            <input type="text" readonly name='no_rm' value="{{ $kodepasien }}" class="form-control form-control-solid" id='pasien-kodepasien' required />
                                            <button class="btn btn-warning btn-icon" type="button" id="btn-edit-rm" title="Edit Manual"><i class="ki-outline ki-pencil fs-2"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <label class="form-label required fw-bold fs-6">Nama Lengkap</label>
                                        <input type="text" name="nama_pasien" id="nama_pasien" class="form-control form-control-lg form-control-solid" placeholder="Nama Pasien sesuai KTP" required />
                                    </div>
                                </div>

                                <!-- Row 3: TTL & JK -->
                                <div class="row mb-6">
                                    <div class="col-lg-4">
                                        <label class="form-label required fw-bold fs-6">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control form-control-solid" required />
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label required fw-bold fs-6">Tanggal Lahir</label>
                                         <div class="input-group">
                                            <input class="form-control form-control-solid" name='tgl_lahir' id='tgl_lahir' placeholder="YYYY-MM-DD" />
                                            <span class="input-group-text">
                                                <i class="ki-outline ki-calendar fs-2"></i>
                                            </span>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name='baru_lahir' value="1" id="checkBaruLahir" />
                                            <label class="form-check-label text-gray-600" for="checkBaruLahir">Bayi Baru Lahir?</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label required fw-bold fs-6">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select form-select-solid" required>
                                            <option value="">Pilih</option>
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                     <div class="col-lg-2">
                                        <label class="form-label required fw-bold fs-6">Gol Darah</label>
                                        <select name="golongan_darah" class="form-select form-select-solid" required>
                                            <option value="">Pilih</option>
                                            @foreach ($gol_darah as $gl)
                                                <option value="{{ $gl->id }}">{{ $gl->golongan_darah }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Row 4: Data Sosial -->
                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <label class="form-label required fw-bold fs-6">Agama</label>
                                        <select name="id_agama" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Agama" required>
                                            <option></option>
                                            @foreach ($data_agama as $da)
                                                <option value="{{ $da->id }}">{{ $da->agama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="form-label required fw-bold fs-6">Etnis / Suku</label>
                                        <select name="id_etnis" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Etnis" required>
                                            <option></option>
                                            @foreach ($data_etnis as $de)
                                                <option value="{{ $de->id }}">{{ $de->etnis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="form-label required fw-bold fs-6">Pendidikan</label>
                                         <select name="id_pendidikan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Pendidikan" required>
                                            <option></option>
                                            @foreach ($data_pendidikan as $dp)
                                                <option value="{{ $dp->id }}">{{ $dp->pendidikan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="form-label required fw-bold fs-6">Status Pernikahan</label>
                                        <select name="id_hubungan_pernikakan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Status" required>
                                            <option></option>
                                            @foreach ($data_hubungan as $dh)
                                                <option value="{{ $dh->id }}">{{ $dh->hubungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5">
                        <!-- Col Left: Kontak & Alamat -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title fw-bold">Kontak & Alamat</h3>
                                </div>
                                <div class="card-body p-9">
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label required fw-bold fs-6">No HP / WhatsApp</label>
                                            <input type="text" name="no_hp" id="no_hp" class="form-control form-control-solid" placeholder="08..." required />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required fw-bold fs-6">Email</label>
                                            <input type="email" name="email" id="email" class="form-control form-control-solid" placeholder="nama@email.com" required />
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label required fw-bold fs-6">Kelurahan</label>
                                        <select class="form-select form-select-solid" name="id_kel" id='id_kel' data-control="select2" data-placeholder="Cari Kelurahan..." required>
                                            <option></option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-5">
                                        <label class="form-label required fw-bold fs-6">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" class="form-control form-control-solid" rows="3" required></textarea>
                                        <div class="form-text">Isi nama jalan, RT/RW, dan No. Rumah.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Col Right: Pekerjaan & Lainnya -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="card-title fw-bold">Pekerjaan & Data Tambahan</h3>
                                </div>
                                <div class="card-body p-9">
                                   <div class="mb-5">
                                        <label class="form-label required fw-bold fs-6">Pekerjaan</label>
                                        <select name="id_pekerjaan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Pekerjaan" required>
                                            <option></option>
                                            @foreach ($data_pekerjaan as $dp)
                                                <option value="{{ $dp->id }}">{{ $dp->pekerjaan }}</option>
                                            @endforeach
                                        </select>
                                   </div>

                                   <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold fs-6">NRP / NIP</label>
                                            <input type="text" name="nrp" class="form-control form-control-solid" placeholder="Optional" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold fs-6">Pangkat / Golongan</label>
                                            <input type="text" name="pangkat" class="form-control form-control-solid" placeholder="Optional" />
                                        </div>
                                   </div>

                                   <div class="mb-5">
                                        <label class="form-label fw-bold fs-6">Kesatuan / Instansi</label>
                                        <input type="text" name="kesatuan" class="form-control form-control-solid" placeholder="Optional" />
                                   </div>

                                   <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label required fw-bold fs-6">Status Pasien</label>
                                            <select name="status_pasien" class="form-select form-select-solid" required>
                                                @foreach ($data_status as $ds)
                                                    <option value="{{ $ds->id }}">{{ $ds->d_status }}</option>
                                                @endforeach
                                            </select>
                                             <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name='pasien_lama' value="1" id="checkPasienLama" />
                                                <label class="form-check-label text-gray-600" for="checkPasienLama">Pasien Lama?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold fs-6">Info Kepesertaan BPJS</label>
                                            <input type="text" readonly name="kepesertaan_bpjs" id="kepesertaan_bpjs" class="form-control form-control-solid" />
                                        </div>
                                   </div>
                                    
                                     <div class="mb-5">
                                        <label class="form-label required fw-bold fs-6">Hambatan Komunikasi</label>
                                        <select name="id_hambatan" class="form-select form-select-solid" data-placeholder="Pilih Hambatan">
                                             @foreach ($data_hambatan as $dh)
                                                <option value="{{ $dh->id }}">{{ $dh->hambatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Penanggung Jawab -->
                     <div class="card mt-5 mb-5 mb-xl-10">
                        <div class="card-header">
                             <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Penanggung Jawab Pasien</h3>
                            </div>
                        </div>
                        <div class="card-body p-9">
                            <div class="row mb-5">
                                <div class="col-md-4">
                                     <label class="form-label required fw-bold fs-6">Nama Penanggung Jawab</label>
                                     <input type="text" name="penanggung_jawab" class="form-control form-control-solid" required />
                                </div>
                                <div class="col-md-4">
                                     <label class="form-label required fw-bold fs-6">Hubungan dengan Pasien</label>
                                     <select name="hubungan" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Hubungan" required>
                                         <option></option>
                                         @foreach ($pasien_penanggungjawab as $dh)
                                            <option value="{{ $dh->id }}">{{ $dh->penaggungjawab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required fw-bold fs-6">No Telp / HP</label>
                                    <input type="text" name="no_tlp_penanggung_jawab" class="form-control form-control-solid" required />
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-12">
                                     <label class="form-label required fw-bold fs-6">Alamat Penanggung Jawab</label>
                                     <input type="text" name="alamat_penanggung_jawab" class="form-control form-control-solid" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card border-0 bg-transparent">
                          <div class="card-body p-0 d-flex justify-content-end gap-2">
                             <a href="{{ route('pasien.index') }}" class="btn btn-light-secondary btn-active-light-primary">Batal</a>
                             <button type="button" id='button-tambah' class="btn btn-primary"><i class="ki-outline ki-check-circle fs-2"></i> Simpan Data Pasien</button>
                        </div>
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
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    nama_pasien: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' },
                            stringLength: { max: 255 }
                        }
                    },
                     no_rm: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi'
                            }
                        }
                    },
                    jenis_kelamin: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    golongan_darah: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                     tempat_lahir: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                     tgl_lahir: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    id_agama: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    id_etnis: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    id_pendidikan: {
                        validators: {
                            notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    id_hubungan_pernikakan: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    no_hp: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    email: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' },
                             emailAddress: { message: 'Format email tidak valid' }
                        }
                    },
                    id_kel: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    alamat: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    id_pekerjaan: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    penanggung_jawab: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    hubungan: {
                        validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    no_tlp_penanggung_jawab: {
                         validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    },
                    alamat_penanggung_jawab: {
                         validators: {
                             notEmpty: { message: 'Wajib diisi' }
                        }
                    }
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Revalidate Select2
        $(form.querySelectorAll('.form-select')).each(function() {
            $(this).on('change', function() {
                validator.revalidateField($(this).attr('name'));
            });
        });

        // Submit Button Logic
        const submitButton = document.getElementById('button-tambah');
        submitButton.addEventListener('click', function(e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function(status) {
                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;

                        setTimeout(function() {
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;

                           Swal.fire({
                                text: "Data pasien berhasil disimpan!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, Siap!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                             form.submit(); 
                        }, 1000);
                    } else {
                        Swal.fire({
                            text: "Mohon lengkapi data yang wajib diisi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });

        // JS Logic for Search NIK/BPJS
        $(document).on('click', '#btn-edit-rm', function() {
            $('#pasien-kodepasien').prop('readonly', false);
            $('#pasien-kodepasien').val('P-');
            $('#pasien-kodepasien').focus();
            $('#pasien-kodepasien').removeClass('form-control-solid');
            $(this).attr("id", "auto-rm");
             $(this).html('<i class="ki-outline ki-check fs-2"></i>');
             $(this).attr("title", "Simpan No RM Manual");
             $(this).removeClass("btn-warning").addClass("btn-success");
        });

        $(document).on('click', '#auto-rm', function() {
            $('#pasien-kodepasien').prop('readonly', true);
             // Logic to restore original RM if needed, or keep edited
            // $('#pasien-kodepasien').val('{{ $kodepasien }}');
            $('#pasien-kodepasien').addClass('form-control-solid');
            $(this).attr("id", "btn-edit-rm");
             $(this).html('<i class="ki-outline ki-pencil fs-2"></i>');
             $(this).attr("title", "Edit Manual");
             $(this).removeClass("btn-success").addClass("btn-warning");
        });

        // Search NIK
        $(document).on('click', '#btn-cari-nik', function() {
            var nik = $('#nik').val();
            if (!nik) {
                toastr.error('Harap isi NIK');
                return;
            }
            performSearch(nik, 'nik');
        });

        // Search BPJS
        $(document).on('click', '#btn-cari-bpjs', function() {
            var bpjs = $('#bpjs').val();
            if (!bpjs) {
                toastr.error('Harap isi Nomor BPJS');
                return;
            }
            performSearch(bpjs, 'bpjs');
        });

        function performSearch(value, type) {
             $.ajax({
                url: "{{ route('pasien.get-by-nik') }}",
                type: 'GET',
                data: {
                    nik: value,
                    jenis: type
                },
                beforeSend: function() {
                    $.blockUI({ message: '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border text-primary me-3"></div> Loading Data...</div>' });
                },
                success: function(data) {
                    $.unblockUI();
                    if (data.status == true) {
                        toastr.success(data.message);
                        populateForm(data.data.peserta);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    $.unblockUI();
                    toastr.error('Terjadi kesalahan: ' + error);
                }
            });
        }

        function populateForm(data) {
            $('#nik').val(data.nik);
            $('#bpjs').val(data.noKartu);
            $('#nama_pasien').val(data.nama);
            $('#jenis_kelamin').val(data.sex).trigger('change');
            $('#tgl_lahir').val(data.tglLahir);
            $('#no_hp').val(data.mr.noTelepon);
            
             // Handle RM if exists
            if (data.mr.noMR) {
                 $('#pasien-kodepasien').val("P-" + data.mr.noMR);
            }
             
            // Handle Info BPJS
            if (data.jenisPeserta) {
                 $('#kepesertaan_bpjs').val(data.jenisPeserta.keterangan);
            }
        }

        // Initialize Plugins
        $(function() {
             $("#id_kel").select2({
                ajax: {
                    url: " {{ route('pasien.cari-kelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return {
                            results: data.result.map(function(item) {
                                return { id: item.id, text: item.text };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                placeholder: 'Ketik Nama Kelurahan...'
            });

            $("#tgl_lahir").flatpickr({
                maxDate: "today"
            });
        });

        // Flash Messages
        @if (session('gagal'))
            Swal.fire({ text: '{{ session('gagal') }}', icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
        @endif
        @if (session('berhasil'))
            Swal.fire({ text: '{{ session('berhasil') }}', icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
        @endif

    </script>
@endsection
