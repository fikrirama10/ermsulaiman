@extends('layouts.index')

@section('css')
    <style>
        .search-result-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-result-item:hover {
            background-color: #f1faff;
        }

        /* Disabled state for the form wrapper */
        .form-disabled {
            opacity: 0.6;
            pointer-events: none;
            filter: grayscale(100%);
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Toolbar -->
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Pendaftaran Rawat Inap
                        </h1>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-sm btn-light">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid mt-5">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <form action="{{ route('pendaftaran.store') }}" method="POST" id="formPendaftaran">
                    @csrf
                    <input type="hidden" name="jenis_rawat" value="2"> {{-- Hardcoded Rawat Inap --}}
                    <input type="hidden" name="idpasien" id="selected_idpasien">

                    <div class="row g-5">
                        <!-- LEFT COLUMN: Patient Search -->
                        <div class="col-lg-4">
                            <!-- Search Card -->
                            <div class="card shadow-sm mb-5" id="card_search">
                                <div class="card-header min-h-50px">
                                    <h3 class="card-title fs-5">1. Cari Pasien</h3>
                                </div>
                                <div class="card-body">
                                    <div class="position-relative mb-4">
                                        <span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-solid ps-12"
                                            id="search_pasien" placeholder="Nama / RM / NIK (Min 3 Karakter)"
                                            autocomplete="off">
                                    </div>

                                    <div id="loading_search" class="text-center d-none py-3">
                                        <span class="spinner-border spinner-border-sm text-primary"></span> Mencari...
                                    </div>

                                    <div id="search_results" class="list-group list-group-flush border rounded d-none"
                                        style="max-height: 300px; overflow-y: auto;">
                                        <!-- Results injected here -->
                                    </div>

                                    <div class="separator my-4"></div>

                                    <div class="text-center">
                                        <a href="{{ route('pasien.tambah-pasien') }}" class="small fw-bold text-primary">
                                            <i class="fas fa-plus-circle me-1"></i>Pasien Baru
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Patient Card (Hidden initially) -->
                            <div class="card shadow-sm bg-primary text-white d-none" id="card_selected_patient">
                                <div class="card-body p-5">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex flex-column">
                                            <h3 class="text-white fw-bold fs-4 mb-1" id="lbl_nama_pasien">Nama Pasien</h3>
                                            <span class="opacity-75" id="lbl_no_rm">RM: 000000</span>
                                            <span class="opacity-75 small mt-1" id="lbl_nik">NIK: -</span>
                                            <span class="opacity-75 small" id="lbl_tgl_lahir">Tgl Lahir: -</span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light btn-icon text-primary"
                                            onclick="resetPatient()" title="Ganti Pasien">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Admission Form -->
                        <div class="col-lg-8">
                            <div class="card shadow-sm form-disabled" id="card_admission">
                                <div class="card-header min-h-50px">
                                    <h3 class="card-title fs-5">2. Data Masuk Rawat Inap</h3>
                                </div>
                                <div class="card-body">
                                    <!-- SPRI Search Row -->
                                    <div
                                        class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-5">
                                        <div class="d-flex flex-column pe-0 pe-sm-10">
                                            <h5 class="mb-1">Cari SPRI (Opsional)</h5>
                                            <span class="text-gray-600 small">Jika pasien memiliki SPRI, data dokter dan
                                                jaminan akan terisi otomatis.</span>
                                        </div>
                                        <div class="flex-shrink-0 ms-sm-auto mt-3 mt-sm-0">
                                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                                <input type="text" class="form-control" id="no_spri_display"
                                                    placeholder="Pilih SPRI..." readonly>
                                                <input type="hidden" name="id_spri" id="id_spri">
                                                <button class="btn btn-primary" type="button" onclick="openSpriModal()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <button class="btn btn-light" type="button" onclick="clearSpri()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label required">Tanggal Masuk</label>
                                            <input type="date" class="form-control form-control-solid" name="tglmasuk"
                                                value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Penanggung / Bayar</label>
                                            <select class="form-select form-select-solid" name="penanggung"
                                                id="select_penanggung" required>
                                                <option value="1">Umum / Pribadi</option>
                                                <option value="2">BPJS Kesehatan</option>
                                                <option value="3">Asuransi Lain</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label required">Dokter DPJP</label>
                                            <select class="form-select form-select-solid" name="iddokter"
                                                id="select_dokter" required>
                                                <option value="">-- Pilih Dokter --</option>
                                                @foreach ($dokter as $d)
                                                    <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Diagnosa Awal (ICD-10)</label>
                                            <select class="form-select form-select-solid js-data-example-ajax"
                                                name="diagnosa_masuk" id="diagnosa_masuk" required>
                                                <option value="" selected disabled>Ketik Kode ICD-10 / Diagnosa...
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="separator my-5"></div>

                                    <h5 class="mb-4 text-gray-700">Ruangan & Bed</h5>
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label class="form-label required">Ruangan</label>
                                            <select class="form-select form-select-solid" name="idruangan"
                                                id="select_ruangan" required>
                                                <option value="">-- Pilih Ruangan --</option>
                                                @foreach ($ruangan as $r)
                                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }} (Kelas
                                                        {{ $r->idkelas }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Bed / Tempat Tidur</label>
                                            <select class="form-select form-select-solid" name="idbed" id="select_bed"
                                                required>
                                                <option value="">-- Pilih Ruangan Dulu --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end pt-5">
                                        <button type="submit" class="btn btn-primary" id="btn_submit">
                                            <i class="fas fa-save me-2"></i>Simpan Pendaftaran
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cari SPRI -->
    <div class="modal fade" id="modalSpri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="fas fa-times fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y pt-0 pb-15">
                    <div class="text-center mb-10">
                        <h2>Cari SPRI Pasien</h2>
                        <div class="text-muted fw-bold fs-5">Pilih SPRI yang valid untuk pasien ini</div>
                    </div>

                    <div id="spri_loading" class="text-center d-none">
                        <span class="spinner-border text-primary"></span>
                    </div>

                    <div id="spri_empty" class="alert alert-info d-none text-center">
                        Tidak ditemukan SPRI aktif.
                    </div>

                    <div id="spri_list" class="list-group">
                        <!-- Ajax items -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let searchTimeout;

        // 1. Patient Search Logic
        $('#search_pasien').on('keyup', function() {
            clearTimeout(searchTimeout);
            let val = $(this).val();
            if (val.length < 3) {
                $('#search_results').addClass('d-none');
                return;
            }

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
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action py-3 search-result-item" onclick='selectPatient(${JSON.stringify(p)})'>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold fs-6 text-dark">${p.nama_pasien}</div>
                                            <div class="text-muted small">RM: ${p.no_rm} | Tgl Lahir: ${p.tgllahir || '-'}</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </a>
                            `;
                            });
                            $('#search_results').html(html).removeClass('d-none');
                        } else {
                            $('#search_results').html(
                                '<div class="p-3 text-center text-muted small">Data tidak ditemukan</div>'
                            ).removeClass('d-none');
                        }
                    },
                    error: function() {
                        $('#loading_search').addClass('d-none');
                    }
                });
            }, 500);
        });

        function selectPatient(p) {
            // Set Hidden ID
            $('#selected_idpasien').val(p.id);

            // Update UI Labels
            $('#lbl_nama_pasien').text(p.nama_pasien);
            $('#lbl_no_rm').text('RM: ' + p.no_rm);
            $('#lbl_nik').text('NIK: ' + (p.nik || '-'));
            $('#lbl_tgl_lahir').text('Tgl Lahir: ' + (p.tgllahir || '-'));

            // Toggle Cards
            $('#card_search').addClass('d-none');
            $('#card_selected_patient').removeClass('d-none');

            // Enable Admission Form
            $('#card_admission').removeClass('form-disabled');

            // Clear search
            $('#search_pasien').val('');
            $('#search_results').addClass('d-none');
        }

        function resetPatient() {
            $('#selected_idpasien').val('');

            // Toggle Cards
            $('#card_search').removeClass('d-none');
            $('#card_selected_patient').addClass('d-none');

            // Disable Admission Form and Reset some fields if needed
            $('#card_admission').addClass('form-disabled');

            // Optional: Reset form fields?
            // $('#formPendaftaran')[0].reset();
            // Just clearing SPRI and Bed might be enough
            clearSpri();
        }

        // 2. Ruangan & Bed Logic
        $('#select_ruangan').change(function() {
            let id = $(this).val();
            let bedSelect = $('#select_bed');

            bedSelect.html('<option value="">Loading...</option>');

            if (id) {
                $.get("{{ route('pendaftaran.get-beds', '') }}/" + id, function(res) {
                    let opts = '<option value="">-- Pilih Bed --</option>';
                    if (res.length > 0) {
                        res.forEach(b => {
                            opts += `<option value="${b.id}">${b.kodebed}</option>`;
                        });
                    } else {
                        opts = '<option value="">-- Penuh / Tidak Ada Bed --</option>';
                    }
                    bedSelect.html(opts);
                }).fail(function() {
                    bedSelect.html('<option value="">Error memuat bed</option>');
                });
            } else {
                bedSelect.html('<option value="">-- Pilih Ruangan Dulu --</option>');
            }
        });

        // 3. SPRI Logic
        function openSpriModal() {
            let no_rm_text = $('#lbl_no_rm').text();
            let no_rm = no_rm_text.replace('RM: ', '').trim();

            if (!no_rm || no_rm === '000000') {
                Swal.fire('Error', 'Pilih pasien terlebih dahulu', 'error');
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
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action" onclick='selectSpri(${JSON.stringify(s)})'>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 fw-bold text-primary">${s.no_spri}</h5>
                                        <small class="text-muted">${s.tgl_rawat}</small>
                                    </div>
                                    <p class="mb-1 text-gray-700">Dokter: ${s.dokter ? s.dokter.nama_dokter : '-'}</p>
                                    <small>Poli: ${s.poli ? s.poli.poli : '-'}</small>
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

            // Auto-fill form based on SPRI
            if (spri.iddokter) {
                $('#select_dokter').val(spri.iddokter);
            }
            if (spri.idbayar) {
                $('#select_penanggung').val(spri.idbayar);
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Data SPRI diterapkan',
                showConfirmButton: false,
                timer: 1500
            });
        }

        function clearSpri() {
            $('#id_spri').val('');
            $('#no_spri_display').val('');
        }

        // 4. Submit Validation
        $('#formPendaftaran').on('submit', function(e) {
            if (!$('#selected_idpasien').val()) {
                e.preventDefault();
                Swal.fire('Error', 'Silahkan pilih pasien terlebih dahulu', 'warning');
                return;
            }

            // Basic Confirm
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: "Pastikan data rawat inap sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button
                    $('#btn_submit').attr('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');
                    this.submit();
                }
            });
        });

        // 5. ICD-10 Search Select2
        $(document).ready(function() {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: 'https://new-simrs.rsausulaiman.com/auth/listdiagnosa2',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.result.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Ketik Kode ICD-10 / Diagnosa ...',
                allowClear: true
            });
        });

        // Show alerts from session
        @if (session('error'))
            Swal.fire('Gagal', "{{ session('error') }}", 'error');
        @endif
        @if (session('success'))
            Swal.fire('Berhasil', "{{ session('success') }}", 'success');
        @endif
    </script>
@endsection
