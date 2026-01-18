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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Laporan Demografi Pasien</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('laporan.index') }}" class="text-muted text-hover-primary">Laporan</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Demografi</li>
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
                            <label class="form-label">Tanggal Daftar Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Daftar Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Semua</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori Usia</label>
                            <select class="form-select" id="kategori_usia" name="kategori_usia">
                                <option value="">Semua</option>
                                <option value="balita">Balita (0-5)</option>
                                <option value="anak">Anak (6-12)</option>
                                <option value="remaja">Remaja (13-18)</option>
                                <option value="dewasa">Dewasa (19-60)</option>
                                <option value="lansia">Lansia (>60)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Agama</label>
                            <select class="form-select" id="idagama" name="idagama">
                                <option value="">Semua</option>
                                @foreach($agama as $a)
                                <option value="{{ $a->id }}">{{ $a->agama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" class="form-control" id="idkelurahan" name="idkelurahan" placeholder="Kode Kelurahan">
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
                        <table class="table table-bordered table-hover" id="table-demografi">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No RM</th>
                                    <th>Nama Pasien</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Usia</th>
                                    <th>Kategori Usia</th>
                                    <th>Agama</th>
                                    <th>Kelurahan</th>
                                    <th>No HP</th>
                                    <th>Tanggal Daftar</th>
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
        const jenis_kelamin = $('#jenis_kelamin').val();
        const kategori_usia = $('#kategori_usia').val();
        const idagama = $('#idagama').val();
        const idkelurahan = $('#idkelurahan').val();

        $.ajax({
            url: '{{ route('laporan.data-demografi') }}',
            type: 'GET',
            data: {
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai,
                jenis_kelamin: jenis_kelamin,
                kategori_usia: kategori_usia,
                idagama: idagama,
                idkelurahan: idkelurahan
            },
            success: function(response) {
                const data = response.data;
                let html = '';
                let no = 1;

                data.forEach(function(item) {
                    const tglLahir = item.tgllahir ? moment(item.tgllahir).format('DD/MM/YYYY') : '-';
                    const tglDaftar = item.tgldaftar ? moment(item.tgldaftar).format('DD/MM/YYYY') : '-';
                    const jk = item.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                    const usia = item.usia_tahun ? item.usia_tahun + ' Tahun' : '-';

                    // Menentukan kategori usia
                    let kategoriUsia = '-';
                    if (item.usia_tahun) {
                        if (item.usia_tahun <= 5) {
                            kategoriUsia = 'Balita (0-5)';
                        } else if (item.usia_tahun >= 6 && item.usia_tahun <= 12) {
                            kategoriUsia = 'Anak (6-12)';
                        } else if (item.usia_tahun >= 13 && item.usia_tahun <= 18) {
                            kategoriUsia = 'Remaja (13-18)';
                        } else if (item.usia_tahun >= 19 && item.usia_tahun <= 60) {
                            kategoriUsia = 'Dewasa (19-60)';
                        } else {
                            kategoriUsia = 'Lansia (>60)';
                        }
                    }

                    const agama = item.agama ? item.agama.agama : '-';

                    html += `
                        <tr>
                            <td>${no++}</td>
                            <td>${item.no_rm || '-'}</td>
                            <td>${item.nama_pasien || '-'}</td>
                            <td>${item.nik || '-'}</td>
                            <td>${jk}</td>
                            <td>${tglLahir}</td>
                            <td>${usia}</td>
                            <td>${kategoriUsia}</td>
                            <td>${agama}</td>
                            <td>${item.idkelurahan || '-'}</td>
                            <td>${item.nohp || '-'}</td>
                            <td>${tglDaftar}</td>
                        </tr>
                    `;
                });

                $('#table-demografi tbody').html(html);
            }
        });
    }

    $('#btn-filter').on('click', function() {
        loadTable();
    });

    $('#btn-reset').on('click', function() {
        $('#tanggal_mulai').val('');
        $('#tanggal_selesai').val('');
        $('#jenis_kelamin').val('');
        $('#kategori_usia').val('');
        $('#idagama').val('');
        $('#idkelurahan').val('');
        loadTable();
    });

    $('#btn-excel').on('click', function() {
        const tanggal_mulai = $('#tanggal_mulai').val();
        const tanggal_selesai = $('#tanggal_selesai').val();
        const jenis_kelamin = $('#jenis_kelamin').val();
        const kategori_usia = $('#kategori_usia').val();
        const idagama = $('#idagama').val();
        const idkelurahan = $('#idkelurahan').val();

        const params = new URLSearchParams({
            tanggal_mulai: tanggal_mulai,
            tanggal_selesai: tanggal_selesai,
            jenis_kelamin: jenis_kelamin,
            kategori_usia: kategori_usia,
            idagama: idagama,
            idkelurahan: idkelurahan
        });

        window.location.href = '{{ route('laporan.export-demografi') }}?' + params.toString();
    });
});
</script>
@endsection