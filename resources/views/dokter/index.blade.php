@extends('layouts.index')
@section('css')
    <style>
        .header-gradient {
            background: linear-gradient(135deg, #009ef7 0%, #006096 100%);
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .table-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f6f9;
            color: #009ef7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            margin-right: 12px;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- Header Section -->
                <div class="card mb-5 mb-xl-10 shadow-sm border-0">
                    <div class="card-body pt-9 pb-0 header-gradient rounded-top">
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-60px symbol-lg-100px symbol-fixed position-relative">
                                    <div class="symbol-label bg-white bg-opacity-10 border border-white border-opacity-25">
                                        <i class="ki-outline ki-medical-file fs-3x text-white"></i>
                                    </div>
                                    <div
                                        class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h1 class="text-white fs-2 fw-bold me-1">Manajemen Dokter</h1>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <a href="#"
                                                class="d-flex align-items-center text-white text-opacity-75 text-hover-white me-5 mb-2">
                                                <i
                                                    class="ki-outline ki-profile-circle fs-4 me-1 text-white text-opacity-75"></i>
                                                Kelola Data Dokter, Jadwal & Akun
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-sm btn-light btn-active-light-primary"
                                            onclick="syncAllUsers()">
                                            <i class="ki-outline ki-cloud-change fs-4 me-1"></i> Sinkron Akun Massal
                                        </button>
                                        <button class="btn btn-sm btn-white btn-color-gray-700 btn-primary shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalTambahDokter">
                                            <i class="ki-outline ki-plus-square fs-4 me-1"></i> Tambah Dokter Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Overlay -->
                        <div class="d-flex overflow-auto h-55px">
                            <ul
                                class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                                <li class="nav-item">
                                    <a class="nav-link text-white text-active-white border-active-white bg-opacity-10 active"
                                        href="#">Overview</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="row g-5 g-xl-8 mb-8" style="margin-top: -30px;">
                    <div class="col-xl-3 col-6">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="icon-box bg-light-primary text-primary me-3">
                                    <i class="ki-outline ki-profile-user fs-2"></i>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-gray-800" id="total-dokter">Loading...</div>
                                    <div class="fs-7 fw-semibold text-muted">Total Dokter</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-6">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="icon-box bg-light-success text-success me-3">
                                    <i class="ki-outline ki-check-circle fs-2"></i>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-gray-800" id="dokter-aktif">Loading...</div>
                                    <div class="fs-7 fw-semibold text-muted">Status Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-6">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="icon-box bg-light-warning text-warning me-3">
                                    <i class="ki-outline ki-award fs-2"></i>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-gray-800" id="dokter-spesialis">Loading...</div>
                                    <div class="fs-7 fw-semibold text-muted">Dokter Spesialis</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-6">
                        <div class="card stat-card h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="icon-box bg-light-info text-info me-3">
                                    <i class="ki-outline ki-calendar-tick fs-2"></i>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-gray-800" id="jadwal-aktif">Loading...</div>
                                    <div class="fs-7 fw-semibold text-muted">Jadwal Aktif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Data Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" data-kt-dokter-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Dokter..." />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-dokter-table-toolbar="base">
                                <!-- Filter Toolbar -->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-filter fs-2"></i> Filter
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">Filter Opsi</div>
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5">
                                        <div class="mb-5">
                                            <label class="form-label fs-6 fw-semibold">Spesialis:</label>
                                            <select class="form-select form-select-solid" data-kt-select2="true"
                                                data-placeholder="Pilih Spesialis" data-allow-clear="true"
                                                id="filter_spesialis">
                                                <option></option>
                                                @foreach ($spesialis as $spes)
                                                    <option value="{{ $spes->id }}">{{ $spes->spesialis }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fs-6 fw-semibold">Status User:</label>
                                            <select class="form-select form-select-solid" id="filter_user">
                                                <option value="">Semua</option>
                                                <option value="with_user">Sudah Punya Akun</option>
                                                <option value="without_user">Belum Punya Akun</option>
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fs-6 fw-semibold">Status Dokter:</label>
                                            <select class="form-select form-select-solid" id="filter_status">
                                                <option value="">Semua</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Nonaktif</option>
                                            </select>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="reset"
                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                data-kt-menu-dismiss="true" id="btn-reset-filter">Reset</button>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true" id="btn-apply-filter">Terapkan</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Bulk Actions (Dropdown) -->
                                <div class="dropdown">
                                    <button class="btn btn-light-warning dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ki-outline ki-category fs-2"></i> Aksi Massal
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item text-success" href="#"
                                                onclick="bulkAction('activate')"><i
                                                    class="ki-outline ki-check-circle me-2 text-success"></i>Aktifkan
                                                Terpilih</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"
                                                onclick="bulkAction('deactivate')"><i
                                                    class="ki-outline ki-cross-circle me-2 text-danger"></i>Nonaktifkan
                                                Terpilih</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4">
                        <table id="tbl-dokter" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" id="check-all-dokter"
                                                value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-200px">Profil Dokter</th>
                                    <th class="min-w-125px">Spesialis & Poli</th>
                                    <th class="min-w-125px">Status Akun</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-100px text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <!-- DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Dokter -->
    <div class="modal fade" id="modalTambahDokter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <form id="formDokter" action="{{ route('dokter.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="dokter_id" name="dokter_id">
                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3" id="modal-title">Tambah Dokter</h1>
                            <div class="text-muted fw-semibold fs-5">Formulir manajemen data dokter internal</div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Kode Dokter</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Misal: D001"
                                    name="kode_dokter" id="kode_dokter" required />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-solid"
                                    placeholder="Nama Dokter + Gelar" name="nama_dokter" id="nama_dokter" required />
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Spesialis</label>
                                <select name="idspesialis" id="idspesialis" class="form-select form-select-solid"
                                    data-control="select2" data-hide-search="true" data-placeholder="Pilih Spesialis">
                                    <option value="">-- Non Spesialis --</option>
                                    @foreach ($spesialis as $spes)
                                        <option value="{{ $spes->id }}">{{ $spes->spesialis }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Poli Utama</label>
                                <select name="idpoli" id="idpoli" class="form-select form-select-solid"
                                    data-control="select2" data-hide-search="true" data-placeholder="Pilih Poli">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach ($poli as $p)
                                        <option value="{{ $p->id }}">{{ $p->poli }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Nomor SIP</label>
                                <input type="text" name="sip" id="sip"
                                    class="form-control form-control-solid" placeholder="Nomor SIP" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Status Aktif</label>
                                <select name="status" id="status" class="form-select form-select-solid">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- User Account Section -->
                        <div id="user-account-section"
                            class="bg-light-primary p-5 rounded mt-5 border border-dashed border-primary">
                            <h3 class="fw-bold fs-5 text-primary mb-3">Akun Pengguna Sistem</h3>
                            <div class="mb-2 text-muted fs-7">Wajib diisi untuk dokter baru agar bisa login ke sistem.
                            </div>
                            <div class="row g-9">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Username</label>
                                    <input type="text" name="username" id="username"
                                        class="form-control form-control-solid" placeholder="Username Login" />
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Password</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-solid" placeholder="******" />
                                </div>
                                <div class="col-md-12 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Email</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control form-control-solid" placeholder="email@rs-sulaiman.com" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Loading... <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // -- DataTables Setup --
        $(function() {
            // Helper to get initials
            function getInitials(name) {
                let names = name.split(' '),
                    initials = names[0].substring(0, 1).toUpperCase();
                if (names.length > 1) initials += names[names.length - 1].substring(0, 1).toUpperCase();
                return initials;
            }

            let table = $("#tbl-dokter").DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dokter.index') }}',
                    data: function(d) {
                        d.filter_status = $('#filter_status').val();
                        d.filter_spesialis = $('#filter_spesialis').val();
                        d.filter_user = $('#filter_user').val();
                    }
                },
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'align-middle ps-4'
                    },
                    {
                        data: 'nama_dokter',
                        render: function(data, type, row) {
                            return `
                        <div class="d-flex align-items-center">
                            <div class="table-avatar bg-light-primary text-primary">${getInitials(row.nama_dokter)}</div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold mb-1">${row.nama_dokter}</span>
                                <span class="text-muted fs-7">${row.kode_dokter}</span>
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        data: 'spesialis_name',
                        render: function(data) {
                            return `<span class="badge badge-light-primary fw-bold px-4 py-3">${data}</span>`;
                        }
                    },
                    {
                        data: 'user_status',
                        className: 'align-middle'
                    },
                    {
                        data: 'status_badge',
                        className: 'align-middle'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-end pe-4'
                    }
                ],
                drawCallback: function() {
                    updateStats();
                    KTMenu.createInstances();
                }
            });

            // Custom Search Input
            $('[data-kt-dokter-table-filter="search"]').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filters Apply
            $('#btn-apply-filter').click(function() {
                table.ajax.reload();
            });
            $('#btn-reset-filter').click(function() {
                $('#filter_status, #filter_spesialis, #filter_user').val('').trigger('change');
                table.ajax.reload();
            });

            // Form Submit
            $('#formDokter').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = $('#dokter_id').val() ? '{{ route('dokter.update', ':id') }}'.replace(':id', $(
                    '#dokter_id').val()) : '{{ route('dokter.store') }}';
                // Laravel Method Spoofing for Update
                if ($('#dokter_id').val()) formData.append('_method', 'PUT');

                // Button loading state
                let btn = $('#kt_modal_new_target_submit');
                btn.attr('data-kt-indicator', 'on').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalTambahDokter').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        resetModal();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan sistem';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).map(val => val[0])
                                .join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    }
                });
            });

            // Edit Function Global
            window.editDokter = function(id) {
                $.get('{{ route('dokter.edit', ':id') }}'.replace(':id', id), function(data) {
                    $('#modal-title').text('Edit Dokter');
                    $('#dokter_id').val(data.id);
                    $('#kode_dokter').val(data.kode_dokter);
                    $('#nama_dokter').val(data.nama_dokter);
                    $('#idspesialis').val(data.idspesialis).trigger('change');
                    $('#idpoli').val(data.idpoli).trigger('change');
                    $('#sip').val(data.sip);
                    $('#status').val(data.status).trigger('change');

                    // User Account Section
                    $('#user-account-section').show();

                    // Populate User Data
                    $('#username').val(data.username);
                    $('#email').val(data.email);

                    // Password handling for edit
                    $('#password').val('').attr('placeholder', 'Isi jika ingin mengganti password');
                    $('#password').removeAttr('required');

                    // If username is empty (no account), make it required to create one, or optional?
                    // Logic: if editing, username/password usually optional if already exists. 
                    // But if no account, maybe we encourage creating one.
                    // For now, let's make username required if it was there, or optional? 
                    // Let's stick to required username if we want them to manage account.
                    if (data.username) {
                        $('#username').attr('required', 'required');
                    } else {
                        // If no user, maybe they want to add one now
                        $('#username').removeAttr(
                        'required'); // Or make it required to force account creation?
                        // Let's leave it optional if they don't want to add account yet, 
                        // but if they type username, they must type password (handled by controller validation logic if we implemented strict validation there)
                        // Re-reading controller: if username filled, it validates.
                    }

                    $('#password').removeAttr('required');

                    $('#modalTambahDokter').modal('show');
                });
            };

            // Toggle Status Global
            window.toggleStatus = function(id) {
                $.post('{{ route('dokter.toggle', ':id') }}'.replace(':id', id), {
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    table.ajax.reload();
                    toastr.success(response.message);
                });
            };

            // Reset Modal
            $('#modalTambahDokter').on('hidden.bs.modal', function() {
                resetModal();
            });
        });

        function resetModal() {
            $('#formDokter')[0].reset();
            $('#dokter_id').val('');
            $('#modal-title').text('Tambah Dokter');
            $('#user-account-section').show();
            $('#username, #password').attr('required', 'required');
            $('#idspesialis, #idpoli, #status').val('').trigger('change');
        }

        function updateStats() {
            // Placeholder for real stats if API provided, else just visually active
        }

        // Sync All Users Global
        window.syncAllUsers = function() {
            Swal.fire({
                title: 'Sinkronisasi Akun?',
                text: 'User akan dibuatkan otomatis untuk dokter yang belum memiliki akun (Password default: dokter123)',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sinkronkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('dokter.sync-all-users') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#tbl-dokter').DataTable().ajax.reload();
                            Swal.fire('Berhasil!', response.message, 'success');
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal sinkronisasi', 'error');
                        }
                    });
                }
            });
        }

        window.bulkAction = function(action) {
            let selected = [];
            $('.dokter-checkbox:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                Swal.fire('Pilih Data', 'Centang minimal satu dokter', 'warning');
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Aksi Massal',
                text: `Anda yakin ingin ${action == 'activate' ? 'mengaktifkan' : 'menonaktifkan'} ${selected.length} dokter?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lakukan'
            }).then((res) => {
                if (res.isConfirmed) {
                    $.post('{{ route('dokter.bulk-toggle') }}', {
                        _token: '{{ csrf_token() }}',
                        ids: selected.join(','),
                        action: action
                    }, function(response) {
                        $('#tbl-dokter').DataTable().ajax.reload();
                        $('#check-all-dokter').prop('checked', false);
                        toastr.success(response.message);
                    });
                }
            });
        }

        // Check All
        $(document).on('click', '#check-all-dokter', function() {
            $('.dokter-checkbox').prop('checked', this.checked);
        });
    </script>
@endsection
