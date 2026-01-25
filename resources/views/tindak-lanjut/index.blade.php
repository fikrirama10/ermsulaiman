@extends('layouts.index')
@section('css')
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Tindak
                            Lanjut</h1>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="#" class="text-muted text-hover-primary">Menu</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Pasien</li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">Tindak Lanjut</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page title-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::FAQ card-->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="card-title">
                            <h3 class="fw-bold">
                                <i class="fas fa-file-medical-alt text-primary me-2"></i>
                                Form Tindak Lanjut Pasien
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('rekam-medis-poli', $rawat->id) }}"
                                class="btn btn-sm btn-light-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="p-0">
                                <!--begin::Underline-->
                                <span class="d-inline-block position-relative mb-5">
                                    <!--begin::Label-->
                                    <span class="d-inline-block mb-2 fs-2 fw-bold">
                                        Detail Pasien
                                    </span>
                                    <!--end::Label-->

                                    <!--begin::Line-->
                                    <span
                                        class="d-inline-block position-absolute h-5px bottom-0 end-0 start-0 bg-primary translate rounded"></span>
                                    <!--end::Line-->
                                </span>
                                <!--end::Underline-->
                            </div>
                            <div class="p-2">
                                @canany(['dokter', 'perawat'])
                                    {{-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#tambah-rekap">
                                        Tambah Data Kategori Rekam Medis
                                    </button> --}}
                                @endcanany
                            </div>
                        </div>

                        <div class="row">
                            <div class="row mb-3">
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-4">{{ $pasien->nama_pasien }}</span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <!--begin::Label-->
                                <label class="col-lg-2 fw-semibold text-muted">NIK</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $pasien->nik }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-5">
                                <!--begin::Label-->
                                <label class="col-lg-2 fw-semibold text-muted">No BPJS</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $pasien->no_bpjs }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-5">
                                <!--begin::Label-->
                                <label class="col-lg-2 fw-semibold text-muted">No Handphone</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $pasien->nohp }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-5">
                                <!--begin::Label-->
                                <label class="col-lg-2 fw-semibold text-muted">Alamat</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $pasien->alamat->alamat }}</span>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <div class="separator separator-dashed border-secondary mb-5"></div>
                        <div class="rounded border border-gray-300 p-6 bg-light-subtle">
                            <form action="{{ route('tindak-lanjut.post_tindak_lanjut', $rawat->id) }}" method="post"
                                id='frmTindak'>
                                @csrf
                                <input type="hidden" name="idrekapmedis" value="{{ $rawat->rekapMedis->id ?? '' }}">

                                <div class="row mb-7">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold fs-5 required">
                                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                                            Rencana Tindak Lanjut
                                        </label>
                                        <select name="rencana_tindak_lanjut"
                                                data-control="select2"
                                                data-placeholder="Pilih rencana tindak lanjut..."
                                                class="form-select form-select-lg form-select-solid"
                                                id="rencana_tindak_lanjut"
                                                required>
                                            <option value=""></option>
                                            <option value="Kontrol Kembali">
                                                <i class="fas fa-calendar-check"></i> Pasien Kontrol Kembali
                                            </option>
                                            <option value="Dirujuk">
                                                <i class="fas fa-ambulance"></i> Pasien Dirujuk Keluar
                                            </option>
                                            <option value="Interm">
                                                <i class="fas fa-exchange-alt"></i> Pasien Dirujuk Internal (Interm)
                                            </option>
                                            @if ($rawat->idjenisrawat == 1)
                                                <option value="Prb">
                                                    <i class="fas fa-sync-alt"></i> Pasien Rujuk Balik (PRB)
                                                </option>
                                            @else
                                                <option value="Meninggal">
                                                    <i class="fas fa-sad-tear"></i> Pasien Meninggal
                                                </option>
                                            @endif
                                            <option value="Dirawat">
                                                <i class="fas fa-bed"></i> Pasien Dirawat Inap
                                            </option>
                                        </select>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Pilih jenis tindak lanjut yang sesuai dengan kondisi pasien
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic form sections will appear here -->
                                <div id="interm" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Rujuk</label>
                                            <input type="date" class="form-control" name='tgl_kontrol_intem'
                                                id='tgl_kontrol'>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Poli Rujuk</label>
                                            <select name="poli_rujuk" data-control="select2"
                                                data-placeholder="Select an option" class="form-select"
                                                id="poli_rujuk_interm" arial-placeholder="Poli Rujuk">
                                                <option value=""></option>
                                                @foreach ($poli as $p)
                                                    <option value="{{ $p->kode }}">{{ $p->poli }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="rujukan" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Rujukan</label>
                                            <input type="date" placeholder="Pilih Tgl Rujukan" class="form-control"
                                                name='tgl_kontrol_rujuk' id='tgl_kontrol_rujuk'>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Tujuan Rujuk</label>
                                            <select disabled name="tujuan_rujuk" data-control="select2"
                                                data-placeholder="Select an option" class="form-select" id="tujuan_rujuk"
                                                arial-placeholder="Tujuan Rujuk">
                                                <option value=""></option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Poli Rujuk</label>
                                            <select disabled name="poli_rujuk" data-control="select2"
                                                data-placeholder="Select an option" class="form-select" id="poli_rujuk"
                                                arial-placeholder="Poli Rujuk">
                                                {{-- @foreach ($poli as $p)
                                                    <option value="{{ $p->id }}">{{ $p->poli }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="kontrol" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Kontrol</label>
                                            <input type="date" class="form-control" name='tgl_kontrol'
                                                id='tgl_kontrol'>
                                        </div>
                                    </div>
                                </div>

                                <div id="rawat" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">DPJP</label>
                                            <select name="iddokter" class="form-select" id=""
                                                data-control="select2" data-placeholder="Select an option"
                                                class="form-select" id="dpjp"
                                                arial-placeholder="Rencana Tindak Lanjut">
                                                <option value=""></option>
                                                @foreach ($dokter as $d)
                                                    <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Rencana Rawat</label>
                                            <input type="date" class="form-control" name='tgl_rawat'
                                                id='tgl_kontrol'>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="operasi"
                                                    id="obat" />
                                                <label class="form-check-label" for="obat">
                                                    Operasi
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="value_operasi" id="value_operasi"
                                                class="form-control" placeholder="...." style="display: none;">
                                        </div>
                                    </div>
                                </div>

                                <div id="meninggal" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Meninggal</label>
                                            <input type="date" class="form-control" name='tgl_kontrol'
                                                id='tgl_kontrol'>
                                        </div>
                                    </div>
                                </div>
                                <div id="prb" class="d-none">
                                    <div class="row mb-5">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Tgl.Kunjungan</label>
                                            <input type="date" placeholder="Pilih Tgl Rujukan" class="form-control"
                                                name='tgl_kontrol' id='tgl_kontrol_rujuk'>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Belum Dapat di kembalikan ke Fasilitas
                                                Perujuk dengan alasan </label>
                                            <textarea name="alasan" rows="3" class="form-control" placeholder=""></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Rencana Tindak Lanjut pada kunjungan
                                                selanjutnya</label>
                                            <textarea name="rencana_selanjutnya" rows="3" class="form-control" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="tindak_lanjut"></div>

                                <div class="separator separator-dashed border-gray-300 my-7"></div>

                                <div class="row mb-7">
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold fs-6">
                                            <i class="fas fa-sticky-note text-warning me-2"></i>
                                            Catatan Tambahan
                                        </label>
                                        <textarea name="catatan"
                                                  rows="4"
                                                  class="form-control form-control-solid"
                                                  placeholder="Tulis catatan atau informasi tambahan yang diperlukan..."></textarea>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Opsional - Tambahkan catatan jika diperlukan
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-5">
                                    <a href="{{ route('rekam-medis-poli', $rawat->id) }}"
                                       class="btn btn-light-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        Simpan Tindak Lanjut
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!--end::Body-->
                </div>
                <!--end::FAQ card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection
@section('js')
    <script type="text/javascript"
        src="{{ asset('assets/js/custom/blokUi.js') }}"></script>
    <script>
        //doc ready function

        $(document).ready(function() {
            $('#tujuan_rujuk').select2({
                ajax: {
                    url: '{{ route('list-faskes') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {

                        return {
                            q: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(user) {
                                return {
                                    id: user.id,
                                    text: user.nama
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Search for a user...'
            });

            $('#tgl_kontrol_rujuk').on('change', function() {
                $('#tujuan_rujuk').prop('disabled', false);
            });
            $('#tujuan_rujuk').on('change', function() {
                tgl = $('#tgl_kontrol_rujuk').val();
                kode_faskes = $('#tujuan_rujuk').val();
                var langArray = kode_faskes.split('-');
                var url = '{{ route('spesialistik-faskes', [':b_id', ':p_no']) }}';
                url = url.replace(':b_id', langArray[0]);
                url = url.replace(':p_no', tgl);
                $.get(url).done(function(data) {
                    console.log(data);
                    if (data != 0) {
                        $("select#poli_rujuk").html(data);
                    }

                });
                $('#poli_rujuk').prop('disabled', false);
            });
        });
        // Handle tindak lanjut selection change
        $('#rencana_tindak_lanjut').on('change', function() {
            const aksi = $(this).val();

            // Hide all sections first
            $('#rujukan, #rawat, #interm, #prb, #kontrol, #meninggal').addClass('d-none');

            // Show selected section with animation
            switch(aksi) {
                case 'Dirujuk':
                    $('#rujukan').removeClass('d-none').hide().slideDown(300);
                    break;
                case 'Dirawat':
                    $('#rawat').removeClass('d-none').hide().slideDown(300);
                    break;
                case 'Interm':
                    $('#interm').removeClass('d-none').hide().slideDown(300);
                    break;
                case 'Prb':
                    $('#prb').removeClass('d-none').hide().slideDown(300);
                    break;
                case 'Kontrol Kembali':
                    $('#kontrol').removeClass('d-none').hide().slideDown(300);
                    break;
                case 'Meninggal':
                    $('#meninggal').removeClass('d-none').hide().slideDown(300);
                    break;
            }
        });

        // Handle operasi checkbox (this is now handled in partial rawat.blade.php)
        // Keep for backward compatibility if checkbox ID is different
        $('#obat').change(function() {
            if (this.checked) {
                $('#value_operasi').show();
                $('#value_operasi').prop('required', true);
            } else {
                $('#value_operasi').hide();
                $('#value_operasi').prop('required', false);
                $('#value_operasi').val('');
            }
        });


        @if ($message = session('gagal'))
            Swal.fire({
                text: '{{ $message }}',
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @endif
        @if ($message = session('berhasil'))
            Swal.fire({
                text: '{{ $message }}',
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @endif

        $("#frmTindak").on("submit", function(event) {
            event.preventDefault();
            var blockUI = new KTBlockUI(document.querySelector("#kt_app_body"));
            Swal.fire({
                title: 'Simpan Data',
                text: "Apakah Anda yakin akan menyimpan data ini ?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI({
                        css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff',
                            fontSize: '16px'
                        },
                        message: "<img src='{{ asset('assets/img/loading.gif') }}' width='10%' height='auto'> Tunggu . . .",
                        baseZ: 9000,
                    });
                    this.submit();
                }
            });
        });
    </script>
@endsection
