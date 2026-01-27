@extends('layouts.index')

@section('content')
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3>Buat SPRI Baru</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('spri.index') }}" class="btn btn-light-primary">
                    Kembali
                </a>
            </div>
        </div>
        <div class="card-body py-4">
            <form action="{{ route('spri.store') }}" method="POST">
                @csrf

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label required">Cari Pasien (Nama/NO RM/NIK)</label>
                        <select class="form-select" name="no_rm" id="select_pasien" data-control="select2"
                            data-placeholder="Pilih Pasien" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Tanggal Rencana Rawat</label>
                        <input type="date" class="form-control" name="tgl_rawat" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="form-label required">Dokter DPJP</label>
                        <select class="form-select @error('iddokter') is-invalid @enderror" name="iddokter"
                            data-control="select2" data-placeholder="Pilih Dokter" required>
                            <option value="">-- Pilih Dokter --</option>
                            @foreach ($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_dokter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Poli Asal / Unit</label>
                        <select class="form-select @error('idpoli') is-invalid @enderror" name="idpoli"
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
                        <select class="form-select" name="idbayar" data-control="select2">
                            <option value="1">Umum / Tunai</option>
                            <option value="2">BPJS Kesehatan</option>
                            <option value="3">Asuransi Lain</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan SPRI</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
                }
            });
        });
    </script>
@endpush
