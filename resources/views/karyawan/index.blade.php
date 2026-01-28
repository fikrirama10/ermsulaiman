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
                                        <i class="ki-outline ki-profile-user fs-3x text-white"></i>
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
                                            <h1 class="text-white fs-2 fw-bold me-1">Manajemen Karyawan</h1>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <a href="#"
                                                class="d-flex align-items-center text-white text-opacity-75 text-hover-white me-5 mb-2">
                                                <i
                                                    class="ki-outline ki-profile-circle fs-4 me-1 text-white text-opacity-75"></i>
                                                Kelola Data Perawat, Bidan & Staff Lainnya
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-sm btn-white btn-color-gray-700 btn-primary shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
                                            <i class="ki-outline ki-plus-square fs-4 me-1"></i> Tambah Karyawan Baru
                                        </button>
                                    </div>
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
                                <input type="text" data-kt-karyawan-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Cari Karyawan..." />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-karyawan-table-toolbar="base">
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
                                            <label class="form-label fs-6 fw-semibold">Kategori:</label>
                                            <select class="form-select form-select-solid" data-kt-select2="true"
                                                data-placeholder="Pilih Kategori" data-allow-clear="true"
                                                id="filter_kategori">
                                                <option></option>
                                                @foreach ($kategori as $k)
                                                    <option value="{{ $k }}">{{ $k }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fs-6 fw-semibold">Bagian:</label>
                                            <select class="form-select form-select-solid" data-kt-select2="true"
                                                data-placeholder="Pilih Bagian" data-allow-clear="true" id="filter_bagian">
                                                <option></option>
                                                @foreach ($bagian as $b)
                                                    <option value="{{ $b }}">{{ $b }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fs-6 fw-semibold">Status Karyawan:</label>
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
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4">
                        <table id="tbl-karyawan" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" id="check-all-karyawan"
                                                value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-200px">Profil Karyawan</th>
                                    <th class="min-w-100px">Kategori</th>
                                    <th class="min-w-125px">Jabatan & Bagian</th>
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

    <!-- Modal Tambah/Edit Karyawan -->
    @include('karyawan.modal')
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

            let table = $("#tbl-karyawan").DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('karyawan.index') }}',
                    data: function(d) {
                        d.filter_status = $('#filter_status').val();
                        d.filter_bagian = $('#filter_bagian').val();
                        d.filter_kategori = $('#filter_kategori').val();
                    }
                },
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'align-middle ps-4'
                    },
                    {
                        data: 'nama_karyawan',
                        render: function(data, type, row) {
                            return `
                        <div class="d-flex align-items-center">
                            <div class="table-avatar bg-light-info text-info">${getInitials(row.nama_karyawan)}</div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold mb-1">${row.nama_karyawan}</span>
                                <span class="text-muted fs-7">${row.nip}</span>
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        data: 'kategori',
                        render: function(data) {
                            return `<span class="badge badge-light-primary fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'jabatan',
                        render: function(data, type, row) {
                            return `<div class="d-flex flex-column">
                        <span class="fw-bold text-gray-800">${row.jabatan}</span>
                        <span class="text-muted fs-7">${row.bagian}</span>
                    </div>`;
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
                    KTMenu.createInstances();
                }
            });

            // Custom Search Input
            $('[data-kt-karyawan-table-filter="search"]').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filters Apply
            $('#btn-apply-filter').click(function() {
                table.ajax.reload();
            });
            $('#btn-reset-filter').click(function() {
                $('#filter_status, #filter_bagian, #filter_kategori').val('').trigger('change');
                table.ajax.reload();
            });

            // Form Submit
            $('#formKaryawan').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = $('#karyawan_id').val() ? '{{ route('karyawan.update', ':id') }}'.replace(':id',
                    $('#karyawan_id').val()) : '{{ route('karyawan.store') }}';
                // Laravel Method Spoofing for Update
                if ($('#karyawan_id').val()) formData.append('_method', 'PUT');

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
                        $('#modalTambahKaryawan').modal('hide');
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
            window.editKaryawan = function(id) {
                $.get('{{ route('karyawan.edit', ':id') }}'.replace(':id', id), function(data) {
                    $('#modal-title').text('Edit Karyawan');
                    $('#karyawan_id').val(data.id);
                    $('#nip').val(data.nip);
                    $('#nama_karyawan').val(data.nama_karyawan);
                    $('#kategori').val(data.kategori).trigger('change');
                    $('#jabatan').val(data.jabatan);
                    $('#bagian').val(data.bagian);
                    $('#status').val(data.status).trigger('change');

                    // Handle Photo Preview
                    if (data.foto) {
                        $('#preview-foto').css('background-image', 'url(/storage/' + data.foto + ')');
                    } else {
                        $('#preview-foto').css('background-image',
                            'url(/assets/media/svg/avatars/blank.svg)');
                    }

                    $('#user-account-section').hide();
                    $('#username, #password').removeAttr('required');

                    $('#modalTambahKaryawan').modal('show');
                });
            };

            // Toggle Status Global
            window.toggleStatus = function(id) {
                $.post('{{ route('karyawan.toggle', ':id') }}'.replace(':id', id), {
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    table.ajax.reload();
                    toastr.success(response.message);
                });
            };

            // Reset Modal
            $('#modalTambahKaryawan').on('hidden.bs.modal', function() {
                resetModal();
            });
        });

        function resetModal() {
            $('#formKaryawan')[0].reset();
            $('#karyawan_id').val('');
            $('#modal-title').text('Tambah Karyawan');
            $('#user-account-section').show();
            $('#username, #password').attr('required', 'required');
            $('#status').val('1').trigger('change');
            $('#kategori').val('').trigger('change');
            $('#preview-foto').css('background-image', 'url(/assets/media/svg/avatars/blank.svg)');
        }
    </script>
@endsection
```
