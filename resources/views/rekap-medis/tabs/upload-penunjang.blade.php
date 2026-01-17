{{-- Upload Hasil Penunjang Luar Tab Content --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-file-up fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
            Upload Hasil Pemeriksaan Penunjang Luar
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('post.upload-pengantar', $rawat->id) }}" id='frmPengantarluar' method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <label class="form-label required">Deskripsi File</label>
                    <input type="text" required class="form-control" name="keterangan_file" placeholder="Masukkan deskripsi file...">
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Upload File</label>
                    <input type="file" accept=".pdf,.jpeg,.jpg,.png" required class="form-control" name="file_penunjang_luar">
                    <div class="form-text">Format: PDF, JPEG, JPG, PNG. Max: 5MB</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-5">
                <i class="ki-duotone ki-cloud-upload fs-3"><span class="path1"></span><span class="path2"></span></i>
                Upload
            </button>
        </form>

        @if (count($pemeriksaan_luar) > 0)
            <div class="separator separator-dashed my-8"></div>
            <h5 class="mb-4">Daftar File yang Sudah Diupload</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>File</th>
                        <th>Keterangan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemeriksaan_luar as $pl)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a target="_blank" href="{{ asset('storage/file-penunjang-luar/' . $pl->nama_file) }}" class="text-primary">
                                    <i class="ki-duotone ki-file fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                    {{ $pl->nama_file }}
                                </a>
                            </td>
                            <td>{{ $pl->keterangan_file }}</td>
                            <td>
                                <form action="{{ route('post.delete-pengantar') }}" method="post" id='frmDeletefile' class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $pl->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
