@extends('layouts.index')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h3 class="card-title">Buat Surat Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat.store') }}" method="POST" id="formSurat">
                        @csrf
                        
                        <!-- Header Inputs -->
                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="required form-label">Jenis Surat</label>
                                <select name="jenis_surat" id="jenis_surat" class="form-select form-select-solid" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="sakit">Surat Keterangan Sakit</option>
                                    <option value="lahir">Surat Keterangan Lahir</option>
                                    <option value="kematian">Surat Kematian</option>
                                    <option value="rujukan">Surat Rujukan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                             <div class="col-md-4">
                                <label class="required form-label">Nomor Surat</label>
                                <div class="input-group">
                                    <input type="text" name="no_surat" id="no_surat" class="form-control form-control-solid" required placeholder="Akan digenerate otomatis..." readonly />
                                    <button type="button" class="btn btn-secondary" id="btn-generate">Generate</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" class="form-control form-control-solid" value="{{ date('Y-m-d') }}" required />
                            </div>
                        </div>

                        <div class="separator mb-5"></div>

                        <!-- Patient & Doctor Info -->
                         <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="required form-label">Pasien</label>
                                <div class="input-group mb-2">
                                    <input type="text" id="search_pasien" class="form-control form-control-solid" placeholder="Cari No RM / Nama Pasien..." />
                                    <button type="button" class="btn btn-primary" id="btn-search-pasien"><i class="ki-outline ki-magnifier fs-2"></i></button>
                                </div>
                                <input type="text" name="nama_pasien" id="nama_pasien" class="form-control form-control-solid" placeholder="Nama Pasien (Otomatis/Manual)" required />
                                <input type="hidden" name="id_pasien" id="id_pasien" value=""> 
                                <div class="text-muted fs-7 mt-1">Cari berdasarkan No RM atau Nama, lalu pilih dari hasil pencarian.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Dokter Penanggung Jawab</label>
                                <select name="id_dokter" class="form-select form-select-solid" data-control="select2" required>
                                    <option value="">Pilih Dokter</option>
                                    @foreach($dokters as $d)
                                        <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Dynamic Content Sections -->
                        
                        <!-- Section: Sakit -->
                        <div id="section_sakit" class="surat-section d-none">
                            <h4 class="mb-3 text-primary">Detail Keterangan Sakit</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Istirahat Dari Tanggal</label>
                                    <input type="date" name="sakit_dari_tgl" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Sampai Tanggal</label>
                                    <input type="date" name="sakit_sampai_tgl" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Lama Istirahat (Hari)</label>
                                    <input type="number" name="sakit_lama" class="form-control form-control-solid" placeholder="Contoh: 3" />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Diagnosa (Opsional)</label>
                                    <input type="text" name="sakit_diagnosa" class="form-control form-control-solid" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Lahir -->
                        <div id="section_lahir" class="surat-section d-none">
                            <h4 class="mb-3 text-primary">Detail Kelahiran</h4>
                             <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Bayi</label>
                                    <input type="text" name="lahir_nama_bayi" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="lahir_jk" class="form-select form-select-solid">
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                 <div class="col-md-3 mb-3">
                                    <label class="form-label">Anak Ke-</label>
                                    <input type="number" name="lahir_anak_ke" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="lahir_tgl" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Jam Lahir</label>
                                    <input type="time" name="lahir_jam" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Berat Badan (gram)</label>
                                    <input type="number" name="lahir_bb" class="form-control form-control-solid" placeholder="3000" />
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Panjang Badan (cm)</label>
                                    <input type="number" name="lahir_pb" class="form-control form-control-solid" placeholder="50" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" name="lahir_ayah" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" name="lahir_ibu" class="form-control form-control-solid" />
                                </div>
                            </div>
                        </div>

                         <!-- Section: Kematian -->
                        <div id="section_kematian" class="surat-section d-none">
                            <h4 class="mb-3 text-primary">Detail Kematian</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Waktu Kematian</label>
                                    <input type="datetime-local" name="mati_waktu" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Tempat Kematian</label>
                                    <input type="text" name="mati_tempat" class="form-control form-control-solid" placeholder="Misal: IGD RS..." />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Sebab Kematian</label>
                                    <textarea name="mati_sebab" class="form-control form-control-solid" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Rujukan -->
                        <div id="section_rujukan" class="surat-section d-none">
                            <h4 class="mb-3 text-primary">Detail Rujukan</h4>
                             <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Faskes Tujuan</label>
                                    <input type="text" name="rujuk_faskes" class="form-control form-control-solid" placeholder="RS Umum Daerah..." />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Poli / Unit Tujuan</label>
                                    <input type="text" name="rujuk_poli" class="form-control form-control-solid" placeholder="Poli Penyakit Dalam" />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Diagnosa / Masalah</label>
                                    <input type="text" name="rujuk_diagnosa" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tindakan / Terapi Sementara</label>
                                    <textarea name="rujuk_tindakan" class="form-control form-control-solid" rows="2"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alasan Rujukan</label>
                                    <textarea name="rujuk_alasan" class="form-control form-control-solid" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        
                         <!-- Section: Lainnya -->
                        <div id="section_lainnya" class="surat-section d-none">
                            <h4 class="mb-3 text-primary">Detail Surat</h4>
                             <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Perihal</label>
                                    <input type="text" name="lain_perihal" class="form-control form-control-solid" />
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Isi Surat</label>
                                    <textarea name="lain_isi" class="form-control form-control-solid" rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-5">
                            <a href="{{ route('surat.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Surat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Toggle Sections based on Type and Auto Generate Number
        $('#jenis_surat').change(function() {
            var type = $(this).val();
            $('.surat-section').addClass('d-none');
            // Remove required attribute from hidden inputs to prevent validation block
            $('.surat-section input, .surat-section textarea, .surat-section select').prop('required', false);
            
            if(type) {
                $('#section_' + type).removeClass('d-none');
                // Trigger auto generate number
                generateNumber(type);
            } else {
                $('#no_surat').val('');
            }
        });

        // Generate Number Button Click
        $('#btn-generate').click(function() {
            var type = $('#jenis_surat').val();
            if(!type) {
                Swal.fire('Pilih Jenis Surat', 'Silakan pilih jenis surat terlebih dahulu', 'warning');
                return;
            }
            generateNumber(type);
        });

        function generateNumber(type) {
             $.ajax({
                url: '{{ route("surat.generate-number") }}',
                data: { type: type, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    $('#no_surat').val(res.number);
                }
            });
        }

        // Search Pasien
        $('#btn-search-pasien').click(function() {
            var term = $('#search_pasien').val();
            if(term.length < 3) { Swal.fire('Info', 'Masukkan minimal 3 karakter', 'info'); return; }

            $.ajax({
                url: '{{ route("surat.search-pasien") }}',
                data: { term: term },
                beforeSend: function() { $('#btn-search-pasien').prop('disabled', true); },
                success: function(data) {
                    if(data.length === 0) {
                        Swal.fire('Tidak Ditemukan', 'Data pasien tidak ditemukan', 'warning');
                    } else if(data.length === 1) {
                        selectPasien(data[0]);
                    } else {
                        // Show modal or simpler: dropdown selection simulation with Swal
                        let options = {};
                        data.forEach(p => { options[p.id] = p.no_rm + ' - ' + p.nama_pasien; });
                        
                        Swal.fire({
                            title: 'Pilih Pasien',
                            input: 'select',
                            inputOptions: options,
                            inputPlaceholder: 'Pilih pasien...',
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed && result.value) {
                                let selected = data.find(p => p.id == result.value);
                                selectPasien(selected);
                            }
                        });
                    }
                },
                complete: function() { $('#btn-search-pasien').prop('disabled', false); }
            });
        });

        function selectPasien(p) {
            $('#id_pasien').val(p.id);
            $('#nama_pasien').val(p.nama_pasien);
            
            // Auto-fill other fields if sections are visible/available logic could be added here
            // Example:
            // if($('#jenis_surat').val() == 'lahir') { ... } 
            
            Swal.fire({ icon: 'success', title: 'Pasien Dipilih', text: p.nama_pasien + ' (' + p.no_rm + ')', timer: 1500, showConfirmButton: false });
        }
    });
</script>
@endsection
