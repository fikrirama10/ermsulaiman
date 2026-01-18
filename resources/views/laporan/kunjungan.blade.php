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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Laporan Kunjungan</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('laporan.index') }}" class="text-muted text-hover-primary">Laporan</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Kunjungan</li>
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
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Poli</label>
                            <select class="form-select" id="idpoli" name="idpoli">
                                <option value="">Semua</option>
                                @foreach($poli as $p)
                                <option value="{{ $p->id }}">{{ $p->poli }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dokter</label>
                            <select class="form-select" id="iddokter" name="iddokter">
                                <option value="">Semua</option>
                                @foreach($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jenis Rawat</label>
                            <select class="form-select" id="idjenisrawat" name="idjenisrawat">
                                <option value="">Semua</option>
                                <option value="1">Rawat Inap</option>
                                <option value="2">Rawat Jalan</option>
                                <option value="3">UGD</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Cara Datang</label>
                            <select class="form-select" id="cara_datang" name="cara_datang">
                                <option value="">Semua</option>
                                <option value="Datang Sendiri">Datang Sendiri</option>
                                <option value="Rujukan">Rujukan</option>
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
                        <table class="table table-bordered table-hover" id="table-kunjungan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>No RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Poli</th>
                                    <th>Dokter</th>
                                    <th>Jenis Rawat</th>
                                    <th>Cara Datang</th>
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
    let table;

    function loadTable() {
        const tanggal_mulai = $('#tanggal_mulai').val();
        const tanggal_selesai = $('#tanggal_selesai').val();
        const idpoli = $('#idpoli').val();
        const iddokter = $('#iddokter').val();
        const idjenisrawat = $('#idjenisrawat').val();
        const cara_datang = $('#cara_datang').val();

        $.ajax({
            url: '{{ route('laporan.data-kunjungan') }}',
            type: 'GET',
            data: {
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai,
                idpoli: idpoli,
                iddokter: iddokter,
                idjenisrawat: idjenisrawat,
                cara_datang: cara_datang
            },
            success: function(response) {
                const data = response.data;
                let html = '';
                let no = 1;

                data.forEach(function(item) {
                    const tgl = item.tglmasuk ? moment(item.tglmasuk).format('DD/MM/YYYY HH:mm') : '-';
                    const jenisRawat = item.idjenisrawat == 1 ? 'Rawat Inap' : (item.idjenisrawat == 2 ? 'Rawat Jalan' : 'UGD');
                    const caraBayar = item.bayar ? item.bayar.bayar : '-';

                    html += `
                        <tr>
                            <td>${no++}</td>
                            <td>${tgl}</td>
                            <td>${item.no_rm || '-'}</td>
                            <td>${item.pasien ? item.pasien.nama_pasien : '-'}</td>
                            <td>${item.poli ? item.poli.poli : '-'}</td>
                            <td>${item.dokter ? item.dokter.nama_dokter : '-'}</td>
                            <td>${jenisRawat}</td>
                            <td>${item.cara_datang || '-'}</td>
                            <td>${caraBayar}</td>
                        </tr>
                    `;
                });

                $('#table-kunjungan tbody').html(html);
            }
        });
    }

    $('#btn-filter').on('click', function() {
        loadTable();
    });

    $('#btn-reset').on('click', function() {
        $('#tanggal_mulai').val('');
        $('#tanggal_selesai').val('');
        $('#idpoli').val('');
        $('#iddokter').val('');
        $('#idjenisrawat').val('');
        $('#cara_datang').val('');
        loadTable();
    });

    $('#btn-excel').on('click', function() {
        const tanggal_mulai = $('#tanggal_mulai').val();
        const tanggal_selesai = $('#tanggal_selesai').val();
        const idpoli = $('#idpoli').val();
        const iddokter = $('#iddokter').val();
        const idjenisrawat = $('#idjenisrawat').val();
        const cara_datang = $('#cara_datang').val();

        const params = new URLSearchParams({
            tanggal_mulai: tanggal_mulai,
            tanggal_selesai: tanggal_selesai,
            idpoli: idpoli,
            iddokter: iddokter,
            idjenisrawat: idjenisrawat,
            cara_datang: cara_datang
        });

        window.location.href = '{{ route('laporan.export-kunjungan') }}?' + params.toString();
    });
});
</script>
@endsection
