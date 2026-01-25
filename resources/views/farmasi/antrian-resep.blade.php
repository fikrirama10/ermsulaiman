@extends('layouts.index')

@section('custom-style')
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .nav-tabs-custom .nav-item .nav-link {
        border: none;
        color: #495057;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
    }
    .nav-tabs-custom .nav-item .nav-link.active {
        color: #556ee6;
        background-color: #fff;
        border-bottom: 3px solid #556ee6;
    }
    .badge-soft-primary { color: #556ee6; background-color: rgba(85,110,230,.18); font-weight: 600; }
    .badge-soft-danger { color: #f46a6a; background-color: rgba(244,106,106,.18); font-weight: 600; }
    .badge-soft-success { color: #34c38f; background-color: rgba(52,195,143,.18); font-weight: 600; }
    
    /* Compact table styling */
    .table-compact th, .table-compact td {
        padding: 0.5rem;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .table-compact thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .queue-number {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .patient-name {
        font-weight: 600;
        color: #495057;
    }
</style>
@endsection

@section('content')
    @if ($total_antrian > 0)
        <audio autoplay>
            <source src="{{ asset('assets/media/FGTP7RQ-notification.mp3') }}" type="audio/mp3">
        </audio>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Antrian Resep Farmasi</h4>
                <div class="page-title-right">
                     <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Farmasi</a></li>
                        <li class="breadcrumb-item active">Antrian Resep</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#rajal" role="tab">
                                <span class="d-none d-sm-block">
                                    <i class="bx bx-clinic mr-1"></i> Rawat Jalan 
                                    <span class="badge badge-pill badge-primary ml-1">{{ count($resep_rajal) }}</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#ugd" role="tab">
                                <span class="d-none d-sm-block">
                                    <i class="bx bx-first-aid mr-1"></i> UGD 
                                    <span class="badge badge-pill badge-danger ml-1">{{ count($resep_ugd) }}</span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#ranap" role="tab">
                                <span class="d-none d-sm-block">
                                    <i class="bx bx-building-house mr-1"></i> Rawat Inap 
                                    <span class="badge badge-pill badge-success ml-1">{{ count($resep_ranap) }}</span>
                                </span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content p-3">
                        
                        <!-- Tab Rawat Jalan -->
                        <div class="tab-pane active" id="rajal" role="tabpanel">
                            <table id="table-rajal" class="table table-bordered table-hover table-compact dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="10%">No. Antrian</th>
                                        <th width="12%">No. RM</th>
                                        <th width="25%">Nama Pasien</th>
                                        <th width="20%">Poliklinik</th>
                                        <th width="20%">Dokter</th>
                                        <th width="13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resep_rajal as $rr)
                                        @php $cek_rawat = App\Models\Rawat::find($rr->idrawat); @endphp
                                        @if ($cek_rawat)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge badge-soft-primary queue-number">{{ $rr->no_antrian }}</span>
                                            </td>
                                            <td>{{ $rr->no_rm }}</td>
                                            <td class="patient-name">{{ $rr->pasien?->nama_pasien }}</td>
                                            <td>{{ $rr->rawat?->poli?->poli }}</td>
                                            <td><small>{{ $rr->rawat?->dokter?->nama_dokter }}</small></td>
                                            <td class="text-center">
                                                <a href="{{ route('farmasi.status-rajal', $rr->idrawat) }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-show-alt"></i> Proses
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Tab UGD -->
                        <div class="tab-pane" id="ugd" role="tabpanel">
                            <table id="table-ugd" class="table table-bordered table-hover table-compact dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="10%">No. Antrian</th>
                                        <th width="12%">No. RM</th>
                                        <th width="25%">Nama Pasien</th>
                                        <th width="20%">Unit</th>
                                        <th width="20%">Dokter</th>
                                        <th width="13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resep_ugd as $rr)
                                        @php $cek_rawat = App\Models\Rawat::find($rr->idrawat); @endphp
                                        @if ($cek_rawat)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge badge-soft-danger queue-number">{{ $rr->no_antrian }}</span>
                                            </td>
                                            <td>{{ $rr->no_rm }}</td>
                                            <td class="patient-name">{{ $rr->pasien?->nama_pasien }}</td>
                                            <td>UGD</td>
                                            <td><small>{{ $rr->rawat?->dokter?->nama_dokter }}</small></td>
                                            <td class="text-center">
                                                <a href="{{ route('farmasi.status-rajal', $rr->idrawat) }}" class="btn btn-danger btn-sm">
                                                    <i class="bx bx-show-alt"></i> Proses
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Tab Ranap -->
                        <div class="tab-pane" id="ranap" role="tabpanel">
                            <table id="table-ranap" class="table table-bordered table-hover table-compact dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="10%">No. Antrian</th>
                                        <th width="12%">No. RM</th>
                                        <th width="25%">Nama Pasien</th>
                                        <th width="20%">Ruangan</th>
                                        <th width="20%">Dokter</th>
                                        <th width="13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resep_ranap as $rr)
                                        @php $cek_rawat = App\Models\Rawat::find($rr->idrawat); @endphp
                                        @if ($cek_rawat)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success queue-number">{{ $rr->no_antrian }}</span>
                                            </td>
                                            <td>{{ $rr->no_rm }}</td>
                                            <td class="patient-name">{{ $rr->pasien?->nama_pasien }}</td>
                                            <td>{{ $rr->rawat?->ruangan?->nama_ruangan ?? 'N/A' }}</td>
                                            <td><small>{{ $rr->rawat?->dokter?->nama_dokter }}</small></td>
                                            <td class="text-center">
                                                <a href="{{ route('farmasi.status-ranap', $rr->idrawat) }}" class="btn btn-success btn-sm">
                                                    <i class="bx bx-show-alt"></i> Proses
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Common DataTable options
        var dtOptions = {
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data antrian",
                zeroRecords: "Tidak ada data yang sesuai"
            },
            order: [] // No default sort
        };

        // Initialize DataTables
        $('#table-rajal').DataTable(dtOptions);
        $('#table-ugd').DataTable(dtOptions);
        $('#table-ranap').DataTable(dtOptions);

        // Audio context for notification
        @if ($total_antrian > 0)
            var audioContext = new(window.AudioContext || window.webkitAudioContext)();
        @endif
        
        // Auto refresh every 30 seconds
        setTimeout(function(){
           window.location.reload();
        }, 30000);
    });
</script>
@endsection
