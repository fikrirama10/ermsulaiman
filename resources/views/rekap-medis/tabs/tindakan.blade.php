{{-- Tindakan Tab Content --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-syringe fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            Daftar Tindakan Medis
        </h3>
    </div>
    <div class="card-body">
        @if ($resume_medis)
            @if ($resume_medis->dokter == 1 && $resume_medis->perawat == 1)
                {{-- Display tindakan yang sudah selesai --}}
                @if ($resume_medis?->tindakan != null)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr class="fw-bold">
                                    <th class="w-50px">No</th>
                                    <th>Tindakan</th>
                                    <th>Dokter</th>
                                    <th class="w-100px">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (json_decode($resume_medis->tindakan) as $st)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $st->tindakan }}</td>
                                        <td>{{ $st->dokter }}</td>
                                        <td class="text-center">{{ $st->jumlah }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="ki-duotone ki-information-5 fs-2x text-info me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <span>Belum ada tindakan yang tercatat</span>
                    </div>
                @endif
            @else
                {{-- Form input tindakan --}}
                <div class="alert alert-primary d-flex align-items-center mb-5">
                    <i class="ki-duotone ki-information-5 fs-2x text-primary me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span>Silakan input tindakan medis yang dilakukan pada pasien</span>
                </div>

                <form action="{{ route('post.tindakan', $rawat->id) }}" method="POST" id="frmTindakan">
                    @csrf
                    <div id="kt_tindakan_repeater">
                        <div class="form-group">
                            <div data-repeater-list="tindakan_repeater">
                                @if ($resume_medis?->tindakan != null)
                                    @foreach (json_decode($resume_medis->tindakan) as $st)
                                        <div data-repeater-item>
                                            <div class="form-group row mb-5">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tindakan</label>
                                                    <select name="tindakan" class="form-select" data-kt-repeater="select22" data-placeholder="-Pilih-" required>
                                                        <option></option>
                                                        @foreach ($tarif as $val)
                                                            <option value="{{ $val->id }}" {{ $st->tindakan == $val->id ? 'selected' : '' }}>
                                                                {{ $val->nama_tarif }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Dokter</label>
                                                    <select name="dokter" class="form-select" data-kt-repeater="select22" data-placeholder="-Pilih-" required>
                                                        <option></option>
                                                        @foreach ($dokter as $val)
                                                            <option value="{{ $val->id }}" {{ $st->dokter == $val->id ? 'selected' : '' }}>
                                                                {{ $val->nama_dokter }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Jumlah</label>
                                                    <input type="number" name="jumlah" class="form-control mb-5 mb-md-0" value="{{ $st->jumlah }}" min="1" required />
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                        <i class="ki-duotone ki-trash fs-5">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>
                                                        Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-repeater-item>
                                        <div class="form-group row mb-5">
                                            <div class="col-md-4">
                                                <label class="form-label">Tindakan</label>
                                                <select name="tindakan" class="form-select" data-kt-repeater="select22" data-placeholder="-Pilih-" required>
                                                    <option></option>
                                                    @foreach ($tarif as $val)
                                                        <option value="{{ $val->id }}">
                                                            {{ $val->nama_tarif }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Dokter</label>
                                                <select name="dokter" class="form-select" data-kt-repeater="select22" data-placeholder="-Pilih-" required>
                                                    <option></option>
                                                    @foreach ($dokter as $val)
                                                        <option value="{{ $val->id }}" {{ $rawat->iddokter == $val->id ? 'selected' : '' }}>
                                                            {{ $val->nama_dokter }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number" name="jumlah" class="form-control mb-5 mb-md-0" value="1" min="1" required />
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                    <i class="ki-duotone ki-trash fs-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                    Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
                                <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Tambah Tindakan
                            </button>
                        </div>
                    </div>

                    <div class="separator my-5"></div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-sm btn-secondary">
                            <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Simpan Tindakan
                        </button>
                    </div>
                </form>
            @endif
        @else
            <div class="alert alert-danger d-flex align-items-center">
                <i class="ki-duotone ki-information-5 fs-2x text-danger me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div>
                    <h5 class="mb-1">Resume Medis Belum Dibuat</h5>
                    <span>Silahkan input resume medis terlebih dahulu sebelum menambahkan tindakan</span>
                </div>
            </div>
        @endif
    </div>
</div>
