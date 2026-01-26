<div class="modal fade" id="modalTambahKaryawan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                     <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
             <form id="formKaryawan" action="{{ route('karyawan.store') }}" method="POST">
                @csrf
                <input type="hidden" id="karyawan_id" name="karyawan_id">
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3" id="modal-title">Tambah Karyawan</h1>
                        <div class="text-muted fw-semibold fs-5">Formulir manajemen data karyawan (Perawat, Bidan, Staff)</div>
                    </div>
                    
                    <div class="row g-9 mb-8">
                         <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">NIP / Kode Pegawai</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Misal: P001" name="nip" id="nip" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Nama Karyawan" name="nama_karyawan" id="nama_karyawan" required />
                        </div>
                    </div>
                    
                     <div class="row g-9 mb-8">
                        <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-semibold mb-2">Kategori</label>
                             <select name="kategori" id="kategori" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Perawat">Perawat</option>
                                <option value="Bidan">Bidan</option>
                                <option value="Farmasi">Farmasi</option>
                                <option value="Laboratorium">Laboratorium</option>
                                <option value="Radiologi">Radiologi</option>
                                <option value="Gizi">Gizi</option>
                                <option value="Fisioterapi">Fisioterapi</option>
                                <option value="Administrasi">Administrasi</option>
                                <option value="Umum">Umum</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Jabatan</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Misal: Perawat Pelaksana" name="jabatan" id="jabatan" required />
                        </div>
                    </div>
                    
                     <div class="row g-9 mb-8">
                         <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Bagian / Unit</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Misal: Rawat Inap, IGD" name="bagian" id="bagian" required />
                        </div>
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-semibold mb-2">Status Aktif</label>
                             <select name="status" id="status" class="form-select form-select-solid">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- User Account Section -->
                    <div id="user-account-section" class="bg-light-primary p-5 rounded mt-5 border border-dashed border-primary">
                         <h3 class="fw-bold fs-5 text-primary mb-3">Akun Pengguna Sistem</h3>
                         <div class="mb-2 text-muted fs-7">Wajib diisi untuk karyawan baru agar bisa login ke sistem.</div>
                          <div class="row g-9">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Username</label>
                                <input type="text" name="username" id="username" class="form-control form-control-solid" placeholder="Username Login" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-solid" placeholder="******" />
                            </div>
                             <div class="col-md-12 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-solid" placeholder="email@rs-sulaiman.com" />
                            </div>
                          </div>
                    </div>

                </div>
                 <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan Data</span>
                        <span class="indicator-progress">Loading... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
