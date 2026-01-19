@extends('layouts.index')

@section('css')
<style>
    .patient-info-row {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e4e6ef;
    }
    .patient-info-row:last-child {
        border-bottom: none;
    }
    .patient-info-label {
        width: 150px;
        font-weight: 600;
        color: #5e6278;
    }
    .patient-info-value {
        flex: 1;
        font-weight: 600;
        color: #181c32;
    }
    .tab-loading {
        display: none;
        text-align: center;
        padding: 3rem;
    }
    .medication-card {
        border-left: 3px solid #009ef7;
        background-color: #f9f9f9;
    }
    .table-compact {
        font-size: 0.9rem;
    }
    .table-compact th, .table-compact td {
        padding: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Rekam Medis Pasien
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Menu</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Pasien</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">Kategori Rekam Medis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="card">
                {{-- Card Header --}}
                <div class="card-header">
                    <h3 class="card-title">Data Kategori Rekam Medis Pasien</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('poliklinik') }}" class="btn btn-sm btn-secondary me-2">
                            <i class="ki-duotone ki-arrow-left fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Kembali
                        </a>
                        @if ($resume_medis)
                            <a id="confirmButton" class="btn btn-sm btn-light-primary me-2" href="#">
                                <i class="ki-duotone ki-cloud-download fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Update Template
                            </a>
                            @include('rekap-medis.partials.selesai-button', ['resume_medis' => $resume_medis])
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
{{-- Template Section - Compact Sidebar Style --}}
                    @if((auth()->user()->idpriv == 7 || auth()->user()->idpriv == 14 || auth()->user()->idpriv == 18 || auth()->user()->idpriv == 29) && ($rawat->status != 4 && $rawat->status != 5) && count($get_template) > 0)
                        <div class="mb-5">
                            <div class="card card-flush border border-gray-300">
                                <div class="card-header collapsible cursor-pointer" data-bs-toggle="collapse" data-bs-target="#kt_template_section">
                                    <h3 class="card-title text-gray-800 fs-6 fw-semibold">
                                        <i class="ki-duotone ki-file-added fs-4 text-primary me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Template Diagnosa
                                        <span class="badge badge-circle badge-light-primary ms-2">{{ count($get_template) }}</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <span class="text-muted fs-8 me-2">Klik untuk lihat</span>
                                        <i class="ki-duotone ki-down fs-3 text-gray-600"></i>
                                    </div>
                                </div>
                                <div id="kt_template_section" class="collapse">
                                    <div class="card-body py-4 px-5">
                                        <div class="row g-3">
                                            @foreach ($get_template as $index => $temp)
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center p-3 bg-light-primary rounded border border-primary border-dashed">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="symbol symbol-35px symbol-circle" style="background: #009ef7;">
                                                                <span class="symbol-label fs-7 fw-bold text-gray-600">{{ $index + 1 }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 me-3">
                                                            <div class="text-gray-900 fw-semibold fs-7 mb-1">
                                                                {{ \Illuminate\Support\Str::limit($temp->diagnosa, 50) }}
                                                            </div>
                                                            <div class="d-flex gap-2 fs-8 text-muted">
                                                                <span>{{ \Carbon\Carbon::parse($temp->last_used ?? $temp->created_at)->format('d/m/Y') }}</span>
                                                                @if(isset($temp->usage_count) && $temp->usage_count > 1)
                                                                    <span>• {{ $temp->usage_count }}x</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-sm btn-light-info btn-preview-template"
                                                                    data-id="{{ $temp->id }}"
                                                                    title="Lihat Detail Template">
                                                                <i class="ki-duotone ki-eye fs-6 me-1">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                </i>
                                                                Detail
                                                            </button>
                                                            <a href="{{ route('copy-template', [$temp->idrawat, $rawat->id]) }}"
                                                               class="btn btn-sm btn-success"
                                                               title="Terapkan Template ke Form"
                                                               onclick="return confirm('Terapkan template ini? Data akan disalin ke form.')">
                                                                <i class="ki-duotone ki-check-circle fs-6 me-1">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                                Terapkan
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Patient Details Card --}}
                    @include('rekap-medis.partials.patient-info', ['pasien' => $pasien])

                    <div class="separator separator-dashed my-8"></div>

                    {{-- Main Tab Content --}}
                    <div class="rounded border p-5">
                        @include('rekap-medis.partials.main-tabs', [
                            'rawat' => $rawat,
                            'pasien' => $pasien,
                            'resume_medis' => $resume_medis,
                            'resume_detail' => $resume_detail,
                            'tindak_lanjut' => $tindak_lanjut,
                            'resep_dokter' => $resep_dokter,
                            'riwayat_berobat' => $riwayat_berobat,
                            'pemeriksaan_lab' => $pemeriksaan_lab,
                            'pemeriksaan_radiologi' => $pemeriksaan_radiologi,
                            'pemeriksaan_luar' => $pemeriksaan_luar,
                        ])

                        <div class="separator separator-dashed my-8"></div>

                        <button id='icare-view'
                                data-id="{{ $rawat->dokter?->kode_dpjp }}"
                                data-bpjs="{{ $pasien->no_bpjs }}"
                                class="btn btn-light-success">
                            <i class="ki-duotone ki-shield-tick fs-3"><span class="path1"></span><span class="path2"></span></i>
                            I CARE
                        </button>
                    </div>

                    {{-- History Section --}}
                    <div class="separator separator-dashed my-8"></div>
                    <h4 class="mb-5">Histori Pasien</h4>
                    <table id="tbl-rekap" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                        <thead class="border">
                            <tr class="fw-bold fs-6 text-gray-800 px-7">
                                <th>No</th>
                                <th>Kunjungan</th>
                                <th>Tanggal</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="border"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@include('rekap-medis.modals.sbar-modal', ['rawat' => $rawat])
@include('rekap-medis.modals.view-modal')
@include('rekap-medis.modals.penunjang-modal', ['rawat' => $rawat, 'radiologi' => $radiologi, 'lab' => $lab])

@endsection

@section('js')
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/blokUi.js') }}"></script>
<script src="{{ asset('js/rekap-medis/poliklinik.js') }}"></script>
<script>
    // Configuration
    const CONFIG = {
        routes: {
            copyTemplate: "{{ route('copy-template', ['', '']) }}",
            updateTemplate: "{{ route('update-template', $resume_medis ? $resume_medis->id : '') }}",
            getTemplateDetail: "{{ route('get-template-detail', '__ID__') }}",
            getIcare: "{{ route('get-icare', ['id' => '__DOKTER_ID__', 'bpjs' => '__BPJS_NO__']) }}",
            getDataObat: "{{ route('get-data-obat', '') }}",
            getDataRacikObat: "{{ route('get-data-racik-obat', '') }}",
            getHasilLab: "{{ route('get-hasil-lab', '') }}",
            getHasilRad: "{{ route('get-hasil-rad', '') }}",
            getHasil: "{{ route('get-hasil', '') }}",
            getPenunjang: "{{ route('detail.get-penunjang', '') }}",
            hapusPenunjang: "{{ route('detail.hapus-penunjang', '') }}",
            postResepNonRacikan: "{{ route('rekap-medis.post_resep_non_racikan', $rawat->id) }}",
            postResepRacikan: "{{ route('rekap-medis.post_resep_racikan', $rawat->id) }}",
            editJumlah: "{{ route('post.edit-jumlah') }}",
            deleteResep: "{{ route('post.delete-resep') }}",
            dataResep: "{{ route('rekam-medis-poli.data-resep', [$rawat->no_rm, $rawat->id]) }}",
            copyData: "{{ route('post.copy-data', $rawat->id) }}",
        },
        user: {
            idpriv: {{ auth()->user()->idpriv }},
        },
        rawat: {
            status: {{ $rawat->status }},
            id: {{ $rawat->id }},
        }
    };

    // Initialize page
    $(function() {
        initializePage();
    });

    function initializePage() {
        initializeDataTables();
        initializeFormHandlers();
        initializeRepeaters();
        initializeEventHandlers();
        initializeSelect2();
    }

    // DataTables Initialization
    function initializeDataTables() {
        const dtConfig = {
            language: { lengthMenu: "Show _MENU_" },
            dom: "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
            search: { return: true }
        };

        // Initialize history table


        // Initialize recap table
        $("#tbl-rekap").DataTable({
            ...dtConfig,
            processing: true,
            serverSide: true,
            ajax: window.location.href,
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'kategori', name: 'kategori.nama' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'opsi', name: 'opsi', orderable: false, searcheable: false }
            ]
        });

        // Initialize prescription history table
        $("#tblRiwayatResep").DataTable({
            ...dtConfig,
            processing: true,
            serverSide: true,
            ajax: CONFIG.routes.dataResep,
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'kode_resep' },
                { data: 'tgl' },
                { data: 'data_obat' },
                { data: 'total_harga' },
                { data: 'aksi' }
            ]
        });
    }

    // Form Handlers
    function initializeFormHandlers() {
        const forms = [
            { id: 'frmSelesai', title: 'Selesai Pemeriksaan', message: 'Apakah Anda yakin menyelesaikan pemeriksaan? Data tidak dapat diubah setelah disimpan' },
            { id: 'frmResume', title: 'Input Resume', message: 'Apakah Anda yakin menginput resume?' },
            { id: 'frmTindakan', title: 'Simpan Tindakan', message: 'Yakin menyimpan tindakan?' },
            { id: 'frmTindakLanjut', title: 'Tindak Lanjut', message: 'Konfirmasi tindak lanjut?' },
            { id: 'frmPengantarluar', title: 'Upload Pengantar', message: 'Upload pengantar?' },
            { id: 'frm-data', title: 'Simpan SBAR', message: 'Yakin menyimpan data SBAR?' },
            { id: 'frmpenunjang', title: 'Order Penunjang', message: 'Apakah Anda yakin akan order pemeriksaan penunjang?' },
            { id: 'hps-tindak-lanjut', title: 'Hapus Data', message: 'Apakah Anda yakin akan hapus data ini?' },
            { id: 'frmDeletefile', title: 'Hapus File', message: 'Apakah Anda yakin akan hapus file ini?' }
        ];

        forms.forEach(form => {
            $(`#${form.id}`).on('submit', function(e) {
                e.preventDefault();
                showConfirmation(form.title, form.message, () => {
                    showLoadingOverlay();
                    this.submit();
                });
            });
        });

        // Copy form handlers
        @foreach ($riwayat_berobat as $rb)
            $("#formCopy{{ $rb->id }}").on("submit", function(e) {
                e.preventDefault();
                showConfirmation('Copy Data Pemeriksaan', 'Apakah Anda yakin menyalin data?', () => {
                    showLoadingOverlay();
                    this.submit();
                });
            });
        @endforeach
    }

    // Repeaters
    function initializeRepeaters() {
        // Tindakan repeater
        $('#kt_tindakan_repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                $(this).find('[data-kt-repeater="select22"]').select2({ allowClear: true });
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                $('[data-kt-repeater="select22"]').select2({ allowClear: true });
            }
        });

        // Obat Racikan repeater
        $('#obat_repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                // Initialize Select2 for new row
                $(this).find('.selectObat').select2({
                    ajax: {
                        url: 'https://new-simrs.rsausulaiman.com/dashboard/rest/list-obat2',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return { q: params.term };
                        },
                        processResults: function(data) {
                            return {
                                results: data.result.map(user => ({
                                    id: user.id,
                                    text: user.nama
                                }))
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1,
                    placeholder: 'Cari obat...'
                });
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                // Initialize Select2 on existing rows inside the repeater
                $('#obat_repeater [data-repeater-item]').each(function() {
                    $(this).find('.selectObat').select2({
                        ajax: {
                            url: 'https://new-simrs.rsausulaiman.com/dashboard/rest/list-obat2',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return { q: params.term };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.result.map(user => ({
                                        id: user.id,
                                        text: user.nama
                                    }))
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 1,
                        placeholder: 'Cari obat...'
                    });
                });
            }
        });

        // Radiologi repeater
        $('#radiologi_repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();
                $(this).find('[data-kt-repeater="select2radiologi"]').select2({
                    allowClear: true,
                    dropdownParent: $('#modal_penunjang')
                });
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                $('[data-kt-repeater="select2radiologi"]').select2({
                    allowClear: true,
                    dropdownParent: $('#modal_penunjang')
                });
            }
        });

        // Lab repeater
        $('#lab_repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();
                $(this).find('[data-kt-repeater="select2lab"]').select2({
                    allowClear: true,
                    dropdownParent: $('#modal_penunjang')
                });
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function() {
                $('[data-kt-repeater="select2lab"]').select2({
                    allowClear: true,
                    dropdownParent: $('#modal_penunjang')
                });
            }
        });
    }

    // Event Handlers
    function initializeEventHandlers() {
        // Confirm button
        @if($resume_medis)
        $("#confirmButton").on("click", function() {
            showConfirmation('Simpan Template', 'Yakin Menyimpan Template', () => {
                showLoadingOverlay();
                window.location.href = CONFIG.routes.updateTemplate;
            });
        });
        @endif

        // I-Care view
        $(document).on('click', '#icare-view', function() {
            const dokter = $(this).attr('data-id');
            const bpjs = $(this).attr('data-bpjs');            if (!dokter || !bpjs) {
                showError('Data dokter atau BPJS tidak tersedia');
                return;
            }            console.log(dokter, bpjs);
            loadModal(CONFIG.routes.getIcare.replace('__DOKTER_ID__', dokter).replace('__BPJS_NO__', bpjs));
        });

        // Edit medication buttons
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).attr('data-id');
            if (!id) {
                showError('ID obat tidak ditemukan');
                return;
            }
            loadModal(CONFIG.routes.getDataObat + "/" + id);
        });

        $(document).on('click', '.btn-edit-racik', function() {
            const id = $(this).attr('data-id');
            if (!id) {
                showError('ID obat racikan tidak ditemukan');
                return;
            }
            loadModal(CONFIG.routes.getDataRacikObat + "/" + id);
        });

        // Delete medication
        $(document).on('click', '.btn-hapus', function() {
            const id = $(this).attr('id') || $(this).attr('data-id');
            if (!id) {
                showError('ID obat tidak ditemukan');
                return;
            }
            showConfirmation('Hapus Obat', 'Yakin menghapus obat ini?', () => {
                $.post(CONFIG.routes.deleteResep, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                }).done(function() {
                    showSuccess('Hapus berhasil');
                    $('#li' + id).remove();
                }).fail(function() {
                    showError('Gagal menghapus obat');
                });
            });
        });

        // Edit quantity
        $(document).on('keypress', '.number-jumlah', function(e) {
            if (e.key === 'Enter') {
                const id = $(this).attr('data-id');
                const value = $(this).val();
                if (!id || !value || value < 1) {
                    showError('Jumlah tidak valid');
                    return;
                }
                $.post(CONFIG.routes.editJumlah, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    value: value
                }).done(function(res) {
                    if (res.status == 'ok') {
                        showSuccess('Edit berhasil');
                    } else {
                        showError('Gagal mengubah jumlah');
                    }
                }).fail(function() {
                    showError('Terjadi kesalahan');
                });
            }
        });

        // Penunjang buttons
        $(".btn-edit-penunjang").on("click", function(e) {
            e.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                showError('ID penunjang tidak ditemukan');
                return;
            }
            loadModal(CONFIG.routes.getPenunjang + "/" + id);
        });

        $(".btn-hapus-penunjang").on("click", function(e) {
            e.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                showError('ID penunjang tidak ditemukan');
                return;
            }
            showConfirmation('Hapus Data', 'Yakin menghapus data?', () => {
                showLoadingOverlay();
                $.get(CONFIG.routes.hapusPenunjang + "/" + id).done(() => {
                    location.reload();
                }).fail(() => {
                    $.unblockUI();
                    showError('Gagal menghapus data');
                });
            });
        });
    }

    // Select2 Initialization
    function initializeSelect2() {
        // Select2 for non-racikan obat
        $('#nama_obat').select2({
            ajax: {
                url: 'https://new-simrs.rsausulaiman.com/dashboard/rest/list-obat2',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.result.map(user => ({
                            id: user.id,
                            text: user.nama
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: 'Cari obat...'
        });
    }

    // AJAX Form Submissions
    $('#frmNonracikan').on('submit', function(e) {
        e.preventDefault();
        submitMedicationForm(this, CONFIG.routes.postResepNonRacikan, 'upload');
    });

    $('#frmRacikan').on('submit', function(e) {
        e.preventDefault();
        submitMedicationForm(this, CONFIG.routes.postResepRacikan, 'upload_racikan');
    });

    function submitMedicationForm(form, url, buttonId) {
        const submitButton = document.getElementById(buttonId);

        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(form),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
            },
            success: function(data) {
                submitButton.setAttribute('data-kt-indicator', 'off');
                submitButton.disabled = false;

                if (data.status === 'error') {
                    showError(data.message || 'Terjadi kesalahan');
                    return;
                }

                showSuccess('Obat Tersimpan');

                // Reset form
                $(form)[0].reset();
                $('#nama_obat').val(null).trigger('change');
                $('input[type="checkbox"], input[type="radio"]').prop('checked', false);

                // For racikan form, reset select2 in repeater
                if (buttonId === 'upload_racikan') {
                    $('#obat_repeater').find('.selectObat').val(null).trigger('change');
                }

                // Add to list
                if (data.data) {
                    $('#list_resep > tbody:last-child').append(data.data);
                } else {
                    // Refresh page if no data returned
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                submitButton.setAttribute('data-kt-indicator', 'off');
                submitButton.disabled = false;
                const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                showError(errorMsg);
            }
        });
    }

    // Utility Functions
    function loadModal(url) {
        if (!url) {
            showError('URL tidak valid');
            return;
        }

        $("#modal-hasil").html('<div class="text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $("#modal_lihat").modal('show');

        $.get(url)
            .done(function(data) {
                if (data.status === false) {
                    $("#modal-hasil").html('<div class="alert alert-warning"><i class="ki-duotone ki-information-5 fs-2x me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>Data Tidak Ditemukan</div>');
                } else {
                    $("#modal-hasil").html(data);
                }
            })
            .fail(function(xhr) {
                $("#modal-hasil").html('<div class="alert alert-danger"><i class="ki-duotone ki-cross-circle fs-2x me-3"><span class="path1"></span><span class="path2"></span></i>Gagal memuat data: ' + (xhr.statusText || 'Terjadi kesalahan') + '</div>');
            });
    }

    function showConfirmation(title, message, callback) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) callback();
        });
    }

    function showLoadingOverlay() {
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
    }

    function showSuccess(message) {
        Swal.fire({
            text: message,
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: { confirmButton: "btn btn-primary" }
        });
    }

    function showError(message) {
        Swal.fire({
            text: message,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok",
            customClass: { confirmButton: "btn btn-primary" }
        });
    }

    // Modal functions
    window.modalHasilLab = function(id) {
        if (!id) {
            showError('ID pemeriksaan lab tidak ditemukan');
            return;
        }
        loadModal(CONFIG.routes.getHasilLab + "/" + id);
    };

    window.modalHasilRad = function(id) {
        if (!id) {
            showError('ID pemeriksaan radiologi tidak ditemukan');
            return;
        }
        loadModal(CONFIG.routes.getHasilRad + "/" + id);
    };

    window.modalHasil = function(id) {
        if (!id) {
            showError('ID pemeriksaan tidak ditemukan');
            return;
        }
        loadModal(CONFIG.routes.getHasil + "/" + id);
    };

// Template Preview Handler - Elegant & Minimalist
    $(document).on('click', '.btn-preview-template', function(e) {
        e.preventDefault();
        const templateId = $(this).data('id');

        if (!templateId) {
            showError('ID template tidak ditemukan');
            return;
        }

        // Block UI with elegant loader
        Swal.fire({
            title: 'Memuat Preview...',
            html: '<div class="spinner-border text-primary" role="status"></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        // Fetch template detail
        $.ajax({
            url: CONFIG.routes.getTemplateDetail.replace('__ID__', templateId),
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    let htmlContent = `
                        <div class="text-start">
                            <div class="d-flex align-items-center mb-5 pb-3 border-bottom">
                                <div class="symbol symbol-50px me-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="ki-duotone ki-document fs-2x text-white">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="mb-1 fw-bold">${data.diagnosa || 'Diagnosa'}</h4>
                                    <span class="badge badge-light-primary">${data.tanggal}</span>
                                </div>
                            </div>
                    `;

                    // Display SOAP or soap_data
                    if (data.soap || data.soap_data) {
                        const soapData = data.soap || data.soap_data;
                        htmlContent += `
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-notepad fs-3 text-primary me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    SOAP
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-left: 3px solid #667eea;">
                                            <strong class="text-primary d-block mb-2">Subjective</strong>
                                            <p class="mb-0 text-gray-700">${soapData.subjective || '-'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #06a09d15 0%, #06a09d15 100%); border-left: 3px solid #06a09d;">
                                            <strong class="text-success d-block mb-2">Objective</strong>
                                            <p class="mb-0 text-gray-700">${soapData.objective || '-'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #ffc10715 0%, #ffc10715 100%); border-left: 3px solid #ffc107;">
                                            <strong class="text-warning d-block mb-2">Assessment</strong>
                                            <p class="mb-0 text-gray-700">${soapData.assessment || '-'}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #f1416c15 0%, #f1416c15 100%); border-left: 3px solid #f1416c;">
                                            <strong class="text-danger d-block mb-2">Plan</strong>
                                            <p class="mb-0 text-gray-700">${soapData.plan || '-'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    if (data.icdx) {
                        let icdxArray = Array.isArray(data.icdx) ? data.icdx : [data.icdx];
                        htmlContent += `
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-medical-records fs-3 text-success me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    ICD-X Diagnosa
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                        `;
                        icdxArray.forEach((icd, index) => {
                            const jenis = icd.jenis_diagnosa === 'P' ? 'Primer' : 'Sekunder';
                            const badgeClass = icd.jenis_diagnosa === 'P' ? 'badge-primary' : 'badge-info';
                            htmlContent += `
                                <span class="badge ${badgeClass} fs-7 px-3 py-2">
                                    <strong>${jenis}:</strong> ${icd.diagnosa_icdx || icd}
                                </span>
                            `;
                        });
                        htmlContent += `</div></div>`;
                    }

                    if (data.icd9 && data.icd9.length > 0) {
                        htmlContent += `
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-pill fs-3 text-warning me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    ICD-9 Prosedur
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                        `;
                        data.icd9.forEach(icd => {
                            htmlContent += `<span class="badge badge-light-warning fs-7 px-3 py-2">${icd}</span>`;
                        });
                        htmlContent += `</div></div>`;
                    }

                    if (data.anamnesa_dokter) {
                        htmlContent += `
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-message-text fs-3 text-info me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Anamnesa
                                </h6>
                                <div class="p-4 bg-light-info rounded">
                                    <p class="mb-0 text-gray-800">${data.anamnesa_dokter}</p>
                                </div>
                            </div>
                        `;
                    }

                    if (data.rencana_pemeriksaan) {
                        htmlContent += `
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-folder fs-3 text-primary me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Rencana Pemeriksaan
                                </h6>
                                <div class="p-4 bg-light rounded">
                                    <p class="mb-0 text-gray-800">${data.rencana_pemeriksaan}</p>
                                </div>
                            </div>
                        `;
                    }

                    if (data.terapi) {
                        htmlContent += `
                            <div class="mb-3">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="ki-duotone ki-capsule fs-3 text-success me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Terapi
                                </h6>
                                <div class="p-4 bg-light-success rounded">
                                    <p class="mb-0 text-gray-800">${data.terapi}</p>
                                </div>
                            </div>
                        `;
                    }

                    htmlContent += '</div>';

                    Swal.fire({
                        html: htmlContent,
                        width: '900px',
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: '<i class="ki-duotone ki-cross fs-2"></i> Tutup',
                        customClass: {
                            confirmButton: 'btn btn-light-primary',
                            popup: 'rounded'
                        },
                        buttonsStyling: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Gagal memuat detail template',
                        confirmButtonText: 'Tutup',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + (xhr.responseJSON?.message || xhr.statusText),
                    confirmButtonText: 'Tutup',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
        });
    });

    // Flash messages
    @if($message = session('gagal'))
        showError('{{ $message }}');
    @endif

    @if($message = session('berhasil'))
        showSuccess('{{ $message }}');
    @endif
</script>
@endsection
