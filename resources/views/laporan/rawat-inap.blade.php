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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Laporan Rawat Inap</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('laporan.index') }}" class="text-muted text-hover-primary">Laporan</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Rawat Inap</li>
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
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="fw-bold">Filter Laporan</h5>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Filter Form-->
                    <div class="row g-3 mb-5">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ruangan</label>
                            <select class="form-select" id="idruangan" name="idruangan">
                                <option value="">Semua</option>
                                @foreach($ruangan as $r)
                                <option value="{{ $r->id }}">{{ $r->namaruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Dokter</label>
                            <select class="form-select" id="iddokter" name="iddokter">
                                <option value="">Semua</option>
                                @foreach($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->namadokter }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cara Keluar</label>
                            <select class="form-select" id="cara_keluar" name="cara_keluar">
                                <option value="">Semua</option>
                                <option value="Pulang">Pulang</option>
                                <option value="Dirujuk">Dirujuk</option>
                                <option value="Pulang Paksa">Pulang Paksa</option>
                                <option value="Meninggal">Meninggal</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="fas fa-search"></i> Tampilkan
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-reset">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="button" class="btn btn-success" id="btn-excel">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                    <!--end::Filter Form-->

                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table-rawat-inap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Ruangan</th>
                                    <th>Dokter</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Pulang</th>
                                    <th>LOS (Hari)</th>
                                    <th>Cara Keluar</th>
                                    <th>Cara Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
@endsection
@section('js')
<script>
$(document).ready(function() {
    function loadTable() {
        const tanggal_mulai = $('#tanggal_mulai').val();
        const tanggal_selesai = $('#tanggal_selesai').val();
        const idruangan = $('#idruangan').val();
        const iddokter = $('#iddokter').val();
        const cara_keluar = $('#cara_keluar').val();

        $.ajax({
            url: '{{ route('laporan.data-rawat-inap') }}',
            type: 'GET',
            data: {
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai,
                idruangan: idruangan,
                iddokter: iddokter,
                cara_keluar: cara_keluar
            },
            success: function(response) {
                const data = response.data;
                let html = '';
                let no = 1;

                data.forEach(function(item) {
                    const tglMasuk = item.tglmasuk ? moment(item.tglmasuk).format('DD/MM/YYYY HH:mm') : '-';
                    const tglPulang = item.tglpulang ? moment(item.tglpulang).format('DD/MM/YYYY HH:mm') : '-';
                    const los = item.los || 0;
                    const caraBayar = item.bayar ? item.bayar.namabayar : '-';

                    html += `
                        <tr>
                            <td>${no++}</td>
                            <td>${item.no_rm || '-'}</td>
                            <td>${item.pasien ? item.pasien.nama_pasien : '-'}</td>
                            <td>${item.ruangan ? item.ruangan.namaruangan : '-'}</td>
                            <td>${item.dokter ? item.dokter.namadokter : '-'}</td>
                            <td>${tglMasuk}</td>
                            <td>${tglPulang}</td>
                            <td>${los}</td>
                            <td>${item.cara_keluar || '-'}</td>
                            <td>${caraBayar}</td>
                        </tr>
                    `;
                });

                $('#table-rawat-inap tbody').html(html);
            }
        });
    }

    $('#btn-filter').on('click', function() {
        loadTable();
    });

    $('#btn-reset').on('click', function() {
        $('#tanggal_mulai').val('');
        $('#tanggal_selesai').val('');
        $('#idruangan').val('');
        $('#iddokter').val('');
        $('#cara_keluar').val('');
        loadTable();
    });

    $('#btn-excel').on('click', function() {
        const tanggal_mulai = $('#tanggal_mulai').val();
        const tanggal_selesai = $('#tanggal_selesai').val();
        const idruangan = $('#idruangan').val();
        const iddokter = $('#iddokter').val();
        const cara_keluar = $('#cara_keluar').val();

        const params = new URLSearchParams({
            tanggal_mulai: tanggal_mulai,
            tanggal_selesai: tanggal_selesai,
            idruangan: idruangan,
            iddokter: iddokter,
            cara_keluar: cara_keluar
        });

        window.location.href = '{{ route('laporan.export-rawat-inap') }}?' + params.toString();
    });
});
</script>
@endsection