@extends('layouts.index')

@section('title', 'Monitoring Pendaftaran Rawat Jalan')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!-- Dashboard Stats -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10 bg-body">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"
                                        id="stat_total">{{ $stats['total'] }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Pasien</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10 bg-body">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-success me-2 lh-1 ls-n2"
                                        id="stat_online">{{ $stats['online'] }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Pasien Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10 bg-body">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-primary me-2 lh-1 ls-n2"
                                        id="stat_bpjs">{{ $stats['bpjs'] }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Pasien BPJS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-md-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10 bg-body">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-info me-2 lh-1 ls-n2"
                                        id="stat_umum">{{ $stats['umum'] }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Pasien Umum/Lainnya</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient List & Filters -->
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3">List Pasien</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Monitoring pendaftaran pasien rawat
                                    jalan</span>
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-calendar-8 fs-2 position-absolute ms-4"></i>
                                <input class="form-control form-control-solid ps-12 w-250px" placeholder="Pilih Tanggal"
                                    id="date_range" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-4">
                        <!-- Filters -->
                        <div class="d-flex flex-wrap gap-3 mb-5 bg-light p-4 rounded border">
                            <div class="w-100 w-md-200px">
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Filter Poli" id="filter_poli">
                                    <option></option>
                                    <option value="">Semua Poli</option>
                                    @foreach ($polis as $poli)
                                        <option value="{{ $poli->id }}">{{ $poli->poli }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 w-md-200px">
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Filter Dokter" id="filter_dokter">
                                    <option></option>
                                    <option value="">Semua Dokter</option>
                                    @foreach ($dokters as $dokter)
                                        <option value="{{ $dokter->id }}">{{ $dokter->nama_dokter }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 w-md-200px">
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Filter Penjamin" id="filter_bayar">
                                    <option></option>
                                    <option value="">Semua Penjamin</option>
                                    @foreach ($bayars as $bayar)
                                        <option value="{{ $bayar->id }}">{{ $bayar->bayar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 w-md-150px">
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Status" id="filter_status">
                                    <option></option>
                                    <option value="">Semua Status</option>
                                    <option value="1">Antrian</option>
                                    <option value="2">Diproses</option>
                                    <option value="3">Selesai</option>
                                    <option value="0">Batal</option>
                                </select>
                            </div>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary" onclick="refreshTable()">
                                    <i class="ki-outline ki-magnifier fs-2"></i> Cari
                                </button>
                                <button type="button" class="btn btn-light" onclick="resetFilter()">
                                    <i class="ki-outline ki-arrows-circle fs-2"></i> Reset
                                </button>
                            </div>
                        </div>

                        <!-- New Tab Style -->
                        <ul class="nav nav-pills nav-pills-custom mb-3">
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-active-color-primary flex-column overflow-hidden w-150px h-85px pt-5 pb-2 active"
                                    id="tab_all" href="javascript:;" onclick="loadData('all')">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-outline ki-profile-user fs-1"></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Semua Pasien</span>
                                </a>
                            </li>
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-active-color-primary flex-column overflow-hidden w-150px h-85px pt-5 pb-2"
                                    id="tab_online" href="javascript:;" onclick="loadData('online')">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-outline ki-wifi fs-1"></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">Pasien Online</span>
                                </a>
                            </li>
                        </ul>

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_monitor">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Antrian</th>
                                        <th>Waktu</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Poliklinik</th>
                                        <th>Dokter</th>
                                        <th>Penjamin</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th class="min-w-100px text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail -->
                <div class="modal fade" id="modal_detail" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Detail Pendaftaran</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="ki-outline ki-cross fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                <form id="form_detail" class="form" action="#">
                                    <input type="hidden" id="detail_id" />
                                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="modal_detail_scroll">

                                        <!-- Patient Info -->
                                        <div class="mb-5">
                                            <h4 class="mb-3">Data Pasien</h4>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">No RM</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_norm"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Nama Pasien</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_nama"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Tgl Lahir</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_tgllahir"></span>
                                                </div>
                                            </div>
                                            {{-- <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_alamat"></span>
                                                </div>
                                            </div> --}}
                                        </div>

                                        <div class="separator my-5"></div>

                                        <!-- Registration Info -->
                                        <div class="mb-5">
                                            <h4 class="mb-3">Informasi Pendaftaran</h4>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">No Antrian</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_noantrian"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Poli Tujuan</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_poli"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Dokter</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_dokter"></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Penjamin</label>
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800" id="det_bayar"></span>
                                                </div>
                                            </div>

                                            <!-- Editable Date -->
                                            <div class="row mb-2 align-items-center">
                                                <label class="col-lg-4 fw-semibold text-muted">Tanggal Masuk</label>
                                                <div class="col-lg-6">
                                                    <input type="date" class="form-control form-control-solid"
                                                        id="det_tglmasuk" />
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="button" class="btn btn-icon btn-sm btn-light-primary"
                                                        title="Simpan Tanggal" onclick="saveDate()">
                                                        <i class="ki-outline ki-check fs-2"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-lg-4 fw-semibold text-muted">Status</label>
                                                <div class="col-lg-8">
                                                    <span class="badge badge-light-primary" id="det_status"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="text-center pt-15">
                                        <button type="button" class="btn btn-light me-3"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-danger me-3" onclick="confirmCancel()">
                                            <i class="ki-outline ki-trash fs-2"></i> Pembatalan
                                        </button>
                                        <button type="button" class="btn btn-success" onclick="checkIn()">
                                            <i class="ki-outline ki-check-circle fs-2"></i> Check In
                                        </button>
                                    </div>
                                </form>
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
        var currentFilter = 'all';
        var table;
        var startDate = "{{ $startDate }}";
        var endDate = "{{ $endDate }}";

        $(document).ready(function() {
            // Init Date Picker
            $("#date_range").flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [startDate, endDate],
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        startDate = instance.formatDate(selectedDates[0], "Y-m-d");
                        endDate = instance.formatDate(selectedDates[1], "Y-m-d");
                        refreshTable();
                        updateStats();
                    }
                }
            });

            initTable();
        });

        function initTable() {
            table = $('#kt_table_monitor').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('monitoring.pendaftaran.data') }}",
                    data: function(d) {
                        d.filter = currentFilter;
                        d.start_date = startDate;
                        d.end_date = endDate;
                        d.idpoli = $('#filter_poli').val();
                        d.iddokter = $('#filter_dokter').val();
                        d.idbayar = $('#filter_bayar').val();
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [{
                        data: 'no_antrian',
                        name: 'rawat.no_antrian'
                    },
                    {
                        data: 'tglmasuk',
                        name: 'rawat.tglmasuk'
                    },
                    {
                        data: 'no_rm',
                        name: 'rawat.no_rm'
                    },
                    {
                        data: 'nama_pasien',
                        name: 'pasien.nama_pasien'
                    },
                    {
                        data: 'nama_poli',
                        name: 'poli.poli'
                    },
                    {
                        data: 'nama_dokter',
                        name: 'dokter.nama_dokter'
                    },
                    {
                        data: 'bayar',
                        name: 'rawat_bayar.bayar'
                    },
                    {
                        data: 'online_status',
                        name: 'online_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        }

        function loadData(filter) {
            currentFilter = filter;

            $('.nav-link').removeClass('active');
            if (filter === 'all') {
                $('#tab_all').addClass('active');
            } else if (filter === 'online') {
                $('#tab_online').addClass('active');
            }

            table.draw();
        }

        function refreshTable() {
            table.draw();
        }

        function updateStats() {
            $.ajax({
                url: "{{ route('monitoring.pendaftaran.index') }}",
                type: "GET",
                data: {
                    get_stats: true,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    $('#stat_total').text(response.total);
                    $('#stat_online').text(response.online);
                    $('#stat_bpjs').text(response.bpjs);
                    $('#stat_umum').text(response.umum);
                }
            });
        }

        function resetFilter() {
            $('#filter_poli').val('').trigger('change');
            $('#filter_dokter').val('').trigger('change');
            $('#filter_bayar').val('').trigger('change');
            $('#filter_status').val('').trigger('change');
            refreshTable();
        }

        function openDetail(id) {
            $('#detail_id').val(id);

            // Show loading or clear previous data
            $('#det_norm').text('Loading...');
            $('#det_nama').text('');

            $.ajax({
                url: "{{ url('monitoring/pendaftaran') }}/" + id,
                type: "GET",
                success: function(data) {
                    $('#det_norm').text(data.no_rm);
                    $('#det_nama').text(data.nama_pasien);
                    $('#det_tgllahir').text(data.tgl_lahir);

                    $('#det_noantrian').text(data.no_antrian);
                    $('#det_poli').text(data.nama_poli);
                    $('#det_dokter').text(data.nama_dokter);
                    $('#det_bayar').text(data.nama_bayar);

                    // Set Date input (remove time part if exists)
                    let datePart = data.tglmasuk.split(' ')[0];
                    $('#det_tglmasuk').val(datePart);

                    // Set Status Badge
                    let statusText = 'Unknown';
                    let statusClass = 'badge-light-secondary';
                    if (data.status == 1) {
                        statusText = 'Antrian';
                        statusClass = 'badge-light-warning';
                    } else if (data.status == 2) {
                        statusText = 'Diproses';
                        statusClass = 'badge-light-info';
                    } else if (data.status == 3) {
                        statusText = 'Selesai';
                        statusClass = 'badge-light-success';
                    } else if (data.status == 0) {
                        statusText = 'Batal';
                        statusClass = 'badge-light-danger';
                    }

                    $('#det_status')
                        .removeClass(
                            'badge-light-warning badge-light-info badge-light-success badge-light-danger badge-light-secondary'
                        )
                        .addClass(statusClass)
                        .text(statusText);

                    $('#modal_detail').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat data', 'error');
                }
            });
        }

        function saveDate() {
            let id = $('#detail_id').val();
            let newDate = $('#det_tglmasuk').val();

            if (!newDate) {
                Swal.fire('Warning', 'Tanggal tidak boleh kosong', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('monitoring.pendaftaran.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    action: 'edit_date',
                    tglmasuk: newDate
                },
                success: function(response) {
                    Swal.fire('Success', response.message, 'success');
                    refreshTable();
                    openDetail(id); // Reload modal data
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal mengubah tanggal', 'error');
                }
            });
        }

        function confirmCancel() {
            let id = $('#detail_id').val();

            Swal.fire({
                title: 'Batalkan Pendaftaran?',
                text: "Status akan berubah menjadi Batal!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    performAction(id, 'cancel');
                }
            })
        }

        function checkIn() {
            let id = $('#detail_id').val();
            performAction(id, 'checkin');
        }

        function performAction(id, action) {
            $.ajax({
                url: "{{ route('monitoring.pendaftaran.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    action: action
                },
                success: function(response) {
                    Swal.fire('Success', response.message, 'success');
                    $('#modal_detail').modal('hide');
                    refreshTable();
                    updateStats();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal melakukan aksi', 'error');
                }
            });
        }
    </script>
@endsection
