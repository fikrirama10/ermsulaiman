<div class="modal fade" tabindex="-1" id="modal_penunjang">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="ki-duotone ki-flask fs-1 text-primary me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Tambah Tindakan Penunjang
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id='frmpenunjang' action="{{ route('postOrderPenunjang.rawat-inap', $rawat->id) }}" method="post">
                @csrf
                <div class="modal-body">
                    {{-- Radiologi Section --}}
                    <div class="mb-8">
                        <h4 class="mb-4">
                            <i class="ki-duotone ki-scan-barcode fs-2 text-success me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Radiologi
                        </h4>
                        <div id="radiologi_repeater">
                            <div class="form-group">
                                <div data-repeater-list="radiologi">
                                    <div data-repeater-item>
                                        <div class="form-group row mb-5 align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label required">Tindakan Radiologi</label>
                                                <select name="tindakan_rad" class="form-select"
                                                        data-kt-repeater="select2radiologi"
                                                        data-placeholder="Pilih tindakan..." required>
                                                    <option></option>
                                                    @foreach ($radiologi as $rad)
                                                        <option value="{{ $rad->id }}">{{ $rad->nama_tindakan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label required">Klinis</label>
                                                <input type="text" name="klinis" class="form-control"
                                                       placeholder="Masukkan klinis..." required />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Posisi</label>
                                                <select name="posisi" class="form-select">
                                                    <option value="">Pilih posisi...</option>
                                                    <option value="Kanan">Kanan</option>
                                                    <option value="Kiri">Kiri</option>
                                                    <option value="Bilateral">Bilateral</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:;" data-repeater-delete
                                                   class="btn btn-sm btn-icon btn-light-danger">
                                                    <i class="ki-duotone ki-trash fs-5">
                                                        <span class="path1"></span><span class="path2"></span>
                                                        <span class="path3"></span><span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="javascript:;" data-repeater-create class="btn btn-light-success">
                                    <i class="ki-duotone ki-plus fs-3"></i>
                                    Tambah Radiologi
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-5"></div>

                    {{-- Lab Section --}}
                    <div class="mb-5">
                        <h4 class="mb-4">
                            <i class="ki-duotone ki-test-tube fs-2 text-info me-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            Laboratorium
                        </h4>
                        <div id="lab_repeater">
                            <div class="form-group">
                                <div data-repeater-list="lab">
                                    <div data-repeater-item>
                                        <div class="form-group row mb-5 align-items-end">
                                            <div class="col-md-10">
                                                <label class="form-label required">Tindakan Lab</label>
                                                <select name="tindakan_lab" class="form-select"
                                                        data-kt-repeater="select2lab"
                                                        data-placeholder="Pilih pemeriksaan..." required>
                                                    <option></option>
                                                    @foreach ($lab as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_pemeriksaan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:;" data-repeater-delete
                                                   class="btn btn-sm btn-icon btn-light-danger w-100">
                                                    <i class="ki-duotone ki-trash fs-5">
                                                        <span class="path1"></span><span class="path2"></span>
                                                        <span class="path3"></span><span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                    <i class="ki-duotone ki-plus fs-3"></i>
                                    Tambah Lab
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-check fs-2"><span class="path1"></span><span class="path2"></span></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
