{{-- Hasil Pemeriksaan Penunjang Tab Content --}}
<div class="card">
    <div class="card-body">
        {{-- Lab Results --}}
        <h5 class="mb-4">
            <i class="ki-duotone ki-test-tube fs-2 text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            Hasil Pemeriksaan Lab
        </h5>
        <div class="separator separator-dashed my-5"></div>

        @if ($pemeriksaan_lab)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pemeriksaan</th>
                        <th>Tgl Pemeriksaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemeriksaan_lab as $pl)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pl->labid }}</td>
                            <td>{{ $pl->tgl_hasil }}</td>
                            <td>
                                <button onclick="modalHasilLab({{ $pl->id }})" class="btn btn-sm btn-info">
                                    <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">Belum ada hasil pemeriksaan lab</div>
        @endif

        <div class="separator separator-dashed my-8"></div>

        {{-- Radiologi Results --}}
        <h5 class="mb-4">
            <i class="ki-duotone ki-scan-barcode fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
            Hasil Pemeriksaan Radiologi
        </h5>
        <div class="separator separator-dashed my-5"></div>

        @if ($pemeriksaan_radiologi)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pemeriksaan</th>
                        <th>Tgl Pemeriksaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemeriksaan_radiologi as $pr)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pr->idhasil }}</td>
                            <td>{{ $pr->tgl_hasil }}</td>
                            <td>
                                <button onclick="modalHasilRad({{ $pr->id }})" class="btn btn-sm btn-success">
                                    <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">Belum ada hasil pemeriksaan radiologi</div>
        @endif
    </div>
</div>
