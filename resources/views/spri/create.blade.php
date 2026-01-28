@extends('layouts.index')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>Buat SPRI Baru</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('spri.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body py-4">
            <form action="{{ route('spri.store') }}" method="POST">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label required">Cari Pasien (Nama/NO RM/NIK)</label>
                        {{-- Removed data-control="select2" to init manually --}}
                        <select class="form-select form-select-solid" name="no_rm" id="select_pasien"
                            data-placeholder="Pilih Pasien" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Tanggal Rencana Rawat</label>
                        <input type="date" class="form-control form-control-solid" name="tgl_rawat"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label required">Dokter DPJP</label>
                        <select class="form-select form-select-solid @error('iddokter') is-invalid @enderror"
                            name="iddokter" data-control="select2" data-placeholder="Pilih Dokter" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach ($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Poli Asal / Unit</label>
                        <select class="form-select form-select-solid @error('idpoli') is-invalid @enderror" name="idpoli"
                            data-control="select2" data-placeholder="Pilih Poli" required>
                            <option value="">-- Pilih Poli --</option>
                            @foreach ($poli as $p)
                                <option value="{{ $p->id }}">{{ $p->poli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label">Penanggung Biaya</label>
                        <select class="form-select form-select-solid" name="idbayar" data-control="select2">
                            <option value="1">Umum / Tunai</option>
                            <option value="2">BPJS Kesehatan</option>
                            <option value="3">Asuransi Lain</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-5 mt-8">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="bayi_lahir" value="1"
                                    id="check_bayi" />
                                <label class="form-check-label fw-bold" for="check_bayi">
                                    Bayi Baru Lahir
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="operasi" value="1"
                                    id="check_operasi" />
                                <label class="form-check-label fw-bold" for="check_operasi">
                                    Rencana Operasi
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden input for Tindakan Operasi --}}
                <div class="row mb-5 d-none" id="div_tindakan_operasi">
                    <div class="col-md-12">
                        <label class="form-label required">Nama Tindakan Operasi</label>
                        <input type="text" class="form-control form-control-solid" name="nama_tindakan"
                            placeholder="Contoh: Appendectomy" id="input_tindakan_operasi">
                    </div>
                </div>


                <div class="d-flex justify-content-end border-top pt-5">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan SPRI
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            console.log('SPRI Create Page JS Loaded');

            // Flash Message Handling (Success/Error)
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: "{{ session('warning') }}",
                });
            @endif

            // Fix Select2 Search Pasien
            $('#select_pasien').select2({
                ajax: {
                    url: "{{ route('pendaftaran.search-pasien') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama_pasien + ' (' + item.no_rm + ') - ' + item
                                        .tgllahir,
                                    id: item.no_rm
                                }
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                placeholder: 'Ketik Nama / RM / NIK...',
            });

            // Toggle Tindakan Operasi
            $('#check_operasi').change(function() {
                if ($(this).is(':checked')) {
                    $('#div_tindakan_operasi').removeClass('d-none');
                    $('#input_tindakan_operasi').prop('required', true);
                } else {
                    $('#div_tindakan_operasi').addClass('d-none');
                    $('#input_tindakan_operasi').prop('required', false).val('');
                }
            });

            // Confirmation on Submit
            $('form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                Swal.fire({
                    title: 'Konfirmasi Simpan',
                    text: "Apakah data SPRI sudah benar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
