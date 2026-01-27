@extends('layouts.index')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f1f1f2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #7e8299;
        }

        .step-item.current .step-icon {
            background: #009ef7;
            color: #fff;
        }

        .step-item.current .step-label {
            color: #3f4254;
            font-weight: bold;
        }

        .search-result-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-result-item:hover {
            background-color: #f1faff;
        }

        .service-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .service-card.selected {
            border-color: #009ef7;
            background-color: #f1faff;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Pendaftaran Pasien Baru
                        </h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('pendaftaran.index') }}"
                                    class="text-muted text-hover-primary">Pendaftaran</a>
                            </li>
                            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                            <li class="breadcrumb-item text-muted">Baru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- Form Header (Steps) -->
                        <div class="d-flex justify-content-center mb-10">
                            <div class="d-flex align-items-center me-10 step-item current" id="step1-header">
                                <div class="step-icon me-3">1</div>
                                <div class="step-label text-gray-600">Cari Pasien</div>
                            </div>
                            <div class="d-flex align-items-center step-item" id="step2-header">
                                <div class="step-icon me-3">2</div>
                                <div class="step-label text-gray-600">Data Kunjungan</div>
                            </div>
                        </div>

                        <!-- Step 1: Cari Pasien -->
                        <div id="step1-content">
                            <div class="text-center mb-8">
                                <h3 class="fs-2hx text-dark mb-5">Cari Data Pasien</h3>
                                <div class="text-gray-400 fw-bold fs-5">Cari berdasarkan No RM, NIK, atau Nama Pasien</div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="position-relative w-100 mb-8">
                                        <span
                                            class="svg-icon svg-icon-2 svg-icon-gray-500 position-absolute top-50 translate-middle-y ms-4">
                                            <i class="fas fa-search fs-4"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-lg form-control-solid ps-12"
                                            id="search_pasien" placeholder="Ketik minimal 3 karakter..." autocomplete="off">
                                    </div>

                                    <div id="search_results" class="list-group list-group-flush shadow-sm d-none">
                                        <!-- Results injected here -->
                                    </div>
                                    <div id="loading_search" class="text-center d-none">
                                        <span class="spinner-border text-primary" role="status"></span>
                                    </div>

                                    <div class="text-center mt-5">
                                        <span class="text-muted">Pasien belum terdaftar?</span>
                                        <a href="{{ route('pasien.tambah-pasien') }}" class="fw-bold text-primary ms-1">Buat
                                            Pasien Baru</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Form Pendaftaran -->
                        <div id="step2-content" class="d-none">
                            <form action="{{ route('pendaftaran.store') }}" method="POST" id="formPendaftaran">
                                @csrf
                                <input type="hidden" name="idpasien" id="selected_idpasien">

                                <!-- Selected Patient Info -->
                                <div class="alert alert-primary d-flex align-items-center p-5 mb-10">
                                    <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                                        <i class="fas fa-user-circle fs-1"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-primary" id="selected_patient_name">Nama Pasien</h4>
                                        <span id="selected_patient_rm">RM: -</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-primary ms-auto"
                                        onclick="resetStep1()" title="Ganti Pasien">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <!-- Service Type Selection -->
                                <div class="row g-5 mb-10">
                                    <div class="col-md-4">
                                        <div class="card shadow-none bg-light service-card selected"
                                            onclick="selectService(1)" id="card_rj">
                                            <div class="card-body text-center py-5">
                                                <i class="fas fa-clinic-medical fs-1 text-primary mb-3"></i>
                                                <div class="fs-4 fw-bold text-gray-800">Rawat Jalan</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-none bg-light service-card" onclick="selectService(3)"
                                            id="card_igd">
                                            <div class="card-body text-center py-5">
                                                <i class="fas fa-ambulance fs-1 text-danger mb-3"></i>
                                                <div class="fs-4 fw-bold text-gray-800">IGD / UGD</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card shadow-none bg-light service-card" onclick="selectService(2)"
                                            id="card_ri">
                                            <div class="card-body text-center py-5">
                                                <i class="fas fa-procédure fs-1 text-warning mb-3"></i>
                                                <!-- procdure icon? using bed icon if fail -->
                                                <i class="fas fa-bed fs-1 text-warning mb-3"></i>
                                                <div class="fs-4 fw-bold text-gray-800">Rawat Inap</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="jenis_rawat" id="jenis_rawat" value="1">

                                <!-- Dynamic Inputs -->
                                <div class="row mb-5">
                                    <div class="col-md-6 mb-5" id="group_poli">
                                        <label class="form-label required">Poliklinik</label>
                                        <select class="form-select form-select-solid" name="idpoli" id="select_poli">
                                            <option value="">-- Pilih Poliklinik --</option>
                                            @foreach ($poli as $p)
                                                <option value="{{ $p->id }}">{{ $p->poli }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-5 d-none" id="group_ruangan">
                                        <label class="form-label required">Ruangan / Kamar</label>
                                        <select class="form-select form-select-solid" name="idruangan"
                                            id="select_ruangan">
                                            <option value="">-- Pilih Ruangan --</option>
                                            @foreach ($ruangan as $r)
                                                <option value="{{ $r->id }}">{{ $r->nama_ruangan }} (Kelas
                                                    {{ $r->idkelas }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-5">
                                        <label class="form-label required">Dokter</label>
                                        <select class="form-select form-select-solid" name="iddokter" id="select_dokter">
                                            <option value="">-- Pilih Dokter --</option>
                                            @foreach ($dokter as $d)
                                                <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text" id="dokter_helper">Pilih Poli terlebih dahulu untuk filter
                                            dokter.</div>
                                    </div>

                                    <div class="col-md-6 mb-5">
                                        <label class="form-label required">Penanggung</label>
                                        <select class="form-select form-select-solid" name="penanggung" required>
                                            <option value="1">Umum / Pribadi</option>
                                            <option value="2">BPJS Kesehatan</option>
                                            <option value="3">Asuransi Lain</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-5">
                                        <label class="form-label">Tanggal Masuk</label>
                                        <input type="date" class="form-control form-control-solid" name="tglmasuk"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                    <input type="date" class="form-control form-control-solid" name="tglmasuk"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6 mb-5 d-none" id="group_bed">
                                    <label class="form-label required">Bed / Tempat Tidur</label>
                                    <select class="form-select form-select-solid" name="idbed" id="select_bed">
                                        <option value="">-- Pilih Ruangan Dulu --</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-5 d-none" id="group_spri">
                                    <label class="form-label">Nomor SPRI (Opsional)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_spri_display"
                                            placeholder="Cari SPRI..." readonly style="cursor: pointer;"
                                            onclick="openSpriModal()">
                                        <input type="hidden" name="id_spri" id="id_spri">
                                        <button class="btn btn-secondary" type="button" onclick="clearSpri()"><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                    <div class="form-text">Klik untuk mencari SPRI pasien ini.</div>
                                </div>

                                <div class="col-md-12 mb-5 d-none" id="group_diagnosa">
                                    <label class="form-label">Diagnosa Masuk</label>
                                    <textarea class="form-control form-control-solid" name="diagnosa_masuk" rows="2"
                                        placeholder="Diagnosa awal masuk..."></textarea>
                                </div>
                        </div>

                        <!-- Modal Cari SPRI -->
                        <div class="modal fade" id="modalSpri" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cari SPRI Pasien</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="spri_list" class="list-group">
                                            <!-- Ajax content -->
                                        </div>
                                        <div id="spri_loading" class="text-center d-none">
                                            <span class="spinner-border text-primary"></span>
                                        </div>
                                        <div id="spri_empty" class="alert alert-info d-none">
                                            Tidak ditemukan SPRI aktif untuk pasien ini.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-3" onclick="resetStep1()">Kembali</button>
                            <button type="submit" class="btn btn-primary" id="btn_submit">
                                <span class="indicator-label">Simpan Pendaftaran</span>
                                <span class="indicator-progress">Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        let searchTimeout;

        // Search Patient
        $('#search_pasien').on('keyup', function() {
            clearTimeout(searchTimeout);
            let val = $(this).val();
            if (val.length < 3) return;

            $('#loading_search').removeClass('d-none');
            $('#search_results').addClass('d-none');

            searchTimeout = setTimeout(() => {
                $.ajax({
                    url: "{{ route('pendaftaran.search-pasien') }}",
                    data: {
                        term: val
                    },
                    success: function(res) {
                        $('#loading_search').addClass('d-none');
                        let html = '';
                        if (res.length > 0) {
                            res.forEach(p => {
                                html += `
                                <div class="list-group-item search-result-item" onclick='selectPatient(${JSON.stringify(p)})'>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark">${p.nama_pasien}</div>
                                            <div class="small text-muted">RM: ${p.no_rm} | NIK: ${p.nik || '-'}</div>
                                        </div>
                                        <div class="text-end">
                                             <div class="badge badge-light-primary">Pilih</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            });
                            $('#search_results').html(html).removeClass('d-none');
                        } else {
                            $('#search_results').html(
                                '<div class="list-group-item text-muted text-center">Data tidak ditemukan</div>'
                            ).removeClass('d-none');
                        }
                    }
                });
            }, 500);
        });

        function selectPatient(patient) {
            // console.log(patient);
            $('#selected_idpasien').val(patient.id);
            $('#selected_patient_name').text(patient.nama_pasien);
            $('#selected_patient_rm').text('RM: ' + patient.no_rm);

            $('#step1-content').addClass('d-none');
            $('#step2-content').removeClass('d-none');
            $('#step1-header').removeClass('current');
            $('#step2-header').addClass('current');
        }

        function resetStep1() {
            $('#step2-content').addClass('d-none');
            $('#step1-content').removeClass('d-none');
            $('#step2-header').removeClass('current');
            $('#step1-header').addClass('current');
            $('#search_pasien').val('').focus();
            $('#search_results').addClass('d-none');
        }

        $('.indicator-progress').show();

        // Handle Ruangan Change -> Load Bed
        $('#select_ruangan').change(function() {
            let id_ruangan = $(this).val();
            if (!id_ruangan) return;

            // Clear Beds
            $('#select_bed').html('<option value="">Loading...</option>');

            $.get("{{ route('pendaftaran.get-beds', '') }}/" + id_ruangan, function(res) {
                let opts = '<option value="">-- Pilih Bed --</option>';
                if (res.length > 0) {
                    res.forEach(b => {
                        opts += `<option value="${b.id}">${b.kodebed}</option>`;
                    });
                } else {
                    opts = '<option value="">-- Penuh / Tidak Ada Bed --</option>';
                }
                $('#select_bed').html(opts);
            });
        });

        // SPRI Logic
        function openSpriModal() {
            let id_pasien = $('#selected_idpasien').val();
            // We need NO_RM actually, assume we have it in a hidden input or text?
            // Step 1 selected_patient_rm text is "RM: 123456"
            let txt = $('#selected_patient_rm').text();
            let no_rm = txt.replace('RM: ', '').trim();

            if (!no_rm) {
                alert('Pasien belum dipilih');
                return;
            }

            $('#modalSpri').modal('show');
            $('#spri_list').html('');
            $('#spri_loading').removeClass('d-none');
            $('#spri_empty').addClass('d-none');

            $.ajax({
                url: "{{ route('pendaftaran.search-spri') }}",
                data: {
                    no_rm: no_rm
                },
                success: function(res) {
                    $('#spri_loading').addClass('d-none');
                    if (res.length > 0) {
                        let html = '';
                        res.forEach(s => {
                            html += `
                                    <a href="#" class="list-group-item list-group-item-action" onclick='selectSpri(${JSON.stringify(s)})'>
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">${s.no_spri}</h5>
                                            <small>${s.tgl_rawat}</small>
                                        </div>
                                        <p class="mb-1">Dokter: ${s.dokter ? s.dokter.nama_dokter : '-'}</p>
                                    </a>
                                `;
                        });
                        $('#spri_list').html(html);
                    } else {
                        $('#spri_empty').removeClass('d-none');
                    }
                }
            });
        }

        function selectSpri(spri) {
            $('#id_spri').val(spri.id);
            $('#no_spri_display').val(spri.no_spri);
            $('#modalSpri').modal('hide');

            // Auto Select Dokter DPJP if matches
            if (spri.iddokter) {
                $('#select_dokter').val(spri.iddokter).trigger('change');
            }

            // Auto Penanggung
            if (spri.idbayar) {
                $('select[name="penanggung"]').val(spri.idbayar).trigger('change');
            }
        }

        function clearSpri() {
            $('#id_spri').val('');
            $('#no_spri_display').val('');
        }

        // Modified Service Selection
        function selectService(type) {
            $('.service-card').removeClass('selected');
            $('#jenis_rawat').val(type);

            if (type == 1) { // RJ
                $('#card_rj').addClass('selected');
                $('#group_poli').removeClass('d-none');
                $('#group_ruangan').addClass('d-none');
                $('#group_bed').addClass('d-none');
                $('#group_spri').addClass('d-none');
                $('#group_diagnosa').addClass('d-none');
                $('#select_dokter').empty().append('<option value="">-- Pilih Dokter --</option>');
                $('#dokter_helper').text('Pilih Poli untuk memuat dokter.');
                populateAllDoctorsContext(false);
            } else if (type == 2) { // RI
                $('#card_ri').addClass('selected');
                $('#group_poli').addClass('d-none');
                $('#group_ruangan').removeClass('d-none');
                $('#group_bed').removeClass('d-none');
                $('#group_spri').removeClass('d-none');
                $('#group_diagnosa').removeClass('d-none');
                populateAllDoctorsContext(true); // Load all doctors or DPJP
                $('#dokter_helper').text('Pilih DPJP.');
            } else if (type == 3) { // UGD
                $('#card_igd').addClass('selected');
                $('#group_poli').addClass('d-none');
                $('#group_ruangan').addClass('d-none');
                $('#group_bed').addClass(
                    'd-none'
                    ); // UGD usually doesn't select bed at registration or maybe does? User said "pemilihan ruangan dan juga bed" for rawat inap. UGD usually has "Bed UGD" implicit.
                $('#group_spri').addClass('d-none');
                $('#group_diagnosa').addClass('d-none');
                populateAllDoctorsContext(true); // Load all doctors or Jaga
                $('#dokter_helper').text('Pilih Dokter Jaga.');
            }
        }

        // Load Doctors on Poli Change
        $('#select_poli').change(function() {
            let id_poli = $(this).val();
            if (!id_poli) return;

            $.get("{{ route('pendaftaran.get-dokter', '') }}/" + id_poli, function(res) {
                let opts = '<option value="">-- Pilih Dokter --</option>';
                res.forEach(d => {
                    opts += `<option value="${d.id}">${d.nama_dokter}</option>`;
                });
                $('#select_dokter').html(opts);
            });
        });

        // Helper to revert doctor list to initial (all) if needed
        // For simplicity, I put all doctors in the blade variable $dokter initially.
        // If RJ selected, we clear and load by poli. If UGD/RI selected, we might want to show all again.
        const allDoctors = @json($dokter);

        function populateAllDoctorsContext(showAll) {
            if (showAll) {
                let opts = '<option value="">-- Pilih Dokter --</option>';
                allDoctors.forEach(d => {
                    opts += `<option value="${d.id}">${d.nama_dokter}</option>`;
                });
                $('#select_dokter').html(opts);
            }
        }

        // Submit handler
        $('#formPendaftaran').on('submit', function() {
            $('#btn_submit').attr('disabled', true);
            $('.indicator-label').hide();
            $('.indicator-progress').show();
        });
    </script>
@endsection
