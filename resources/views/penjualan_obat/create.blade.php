@extends('layouts.index')

@section('custom-style')
<link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Transaksi Penjualan Baru</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('penjualan-obat.index') }}">Penjualan Obat</a></li>
                    <li class="breadcrumb-item active">Baru</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form id="form-penjualan">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Pembeli</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis_umum" name="jenis_pembeli" class="custom-control-input" value="umum" checked>
                                        <label class="custom-control-label" for="jenis_umum">Umum (Walk-in)</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis_rawat" name="jenis_pembeli" class="custom-control-input" value="rawat">
                                        <label class="custom-control-label" for="jenis_rawat">Terkait Kunjungan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="wrapper-umum">
                                <label>Nama Pembeli</label>
                                <input type="text" class="form-control" name="nama_pembeli" placeholder="Masukkan Nama Pembeli">
                            </div>
                            <div class="form-group d-none" id="wrapper-rawat">
                                <label>Cari Kunjungan / Pasien</label>
                                <select class="form-control select2-rawat" name="id_rawat" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="font-size-14 mb-3">Item Obat / BHP</h5>
                    <div class="row align-items-end bg-light p-3 mb-3 rounded">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label>Cari Obat</label>
                                <select class="form-control select2-obat" id="input-obat" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Harga Satuan</label>
                                <input type="text" class="form-control" id="input-harga" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label>Jumlah</label>
                                <input type="number" class="form-control" id="input-jumlah" value="1" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success btn-block" id="btn-add-item">
                                <i class="bx bx-cart-alt font-size-16 align-middle mr-2"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-items">
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row-empty">
                                    <td colspan="5" class="text-center">Belum ada item ditambahkan</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Akhir</th>
                                    <th colspan="2" class="text-right">
                                        <h4 class="m-0" id="text-total">Rp 0</h4>
                                        <input type="hidden" name="total_final" id="input-total">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Catatan / Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                                <i class="bx bx-save font-size-16 align-middle mr-2"></i> SIMPAN TRANSAKSI
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    let cartItems = [];
    let currentObat = null;

    $(document).ready(function() {
        // Toggle Jenis Pembeli
        $('input[name="jenis_pembeli"]').change(function() {
            if ($(this).val() == 'umum') {
                $('#wrapper-umum').removeClass('d-none');
                $('#wrapper-rawat').addClass('d-none');
            } else {
                $('#wrapper-umum').addClass('d-none');
                $('#wrapper-rawat').removeClass('d-none');
            }
        });

        // Initialize Select2 Rawat
        $('.select2-rawat').select2({
            placeholder: 'Cari No RM / Nama Pasien',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('penjualan-obat.search-rawat') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) { return { search: params.term }; },
                processResults: function(data) { return { results: data.results }; },
                cache: true
            }
        });

        // Initialize Select2 Obat
        $('.select2-obat').select2({
            placeholder: 'Cari Nama Obat',
            minimumInputLength: 3,
            ajax: {
                url: "{{ route('penjualan-obat.search-obat') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) { return { search: params.term }; },
                processResults: function(data) { return { results: data.results }; },
                cache: true
            }
        });

        // On Select Obat
        $('.select2-obat').on('select2:select', function(e) {
            currentObat = e.params.data;
            $('#input-harga').val(currentObat.harga);
            $('#input-jumlah').focus();
        });

        // Add Item Logic
        $('#btn-add-item').click(function() {
            if (!currentObat) {
                Swal.fire('Error', 'Silakan pilih obat terlebih dahulu', 'error');
                return;
            }

            let jumlah = parseInt($('#input-jumlah').val());
            if (jumlah <= 0) {
                Swal.fire('Error', 'Jumlah harus lebih dari 0', 'error');
                return;
            }

            // Check if item already exists
            let existingIndex = cartItems.findIndex(item => item.id == currentObat.id);
            if (existingIndex !== -1) {
                cartItems[existingIndex].jumlah += jumlah;
                cartItems[existingIndex].subtotal = cartItems[existingIndex].jumlah * cartItems[existingIndex].harga;
            } else {
                cartItems.push({
                    id: currentObat.id,
                    text: currentObat.text,
                    harga: parseInt(currentObat.harga),
                    jumlah: jumlah,
                    subtotal: parseInt(currentObat.harga) * jumlah
                });
            }

            renderTable();
            resetInputItem();
        });

        // Submit Form
        $('#form-penjualan').submit(function(e) {
            e.preventDefault();

            if (cartItems.length === 0) {
                Swal.fire('Error', 'Keranjang masih kosong', 'error');
                return;
            }

            // Prepare Data
            let formData = {
                _token: $('{{ csrf_token() }}').val(),
                tanggal: $('input[name="tanggal"]').val(),
                jenis_pembeli: $('input[name="jenis_pembeli"]:checked').val(),
                nama_pembeli: $('input[name="nama_pembeli"]').val(),
                id_rawat: $('.select2-rawat').val(),
                keterangan: $('textarea[name="keterangan"]').val(),
                items: cartItems.map(item => ({
                    id_obat: item.id,
                    jumlah: item.jumlah
                }))
            };

            // AJAX Post
            $.ajax({
                url: "{{ route('penjualan-obat.store') }}",
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).text('Menyimpan...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Transaksi berhasil disimpan!',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Cetak Struk',
                            cancelButtonText: 'Transaksi Baru'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open("{{ url('penjualan-obat') }}/" + response.id + "/cetak", '_blank');
                                window.location.href = "{{ route('penjualan-obat.create') }}";
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                        $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save font-size-16 align-middle mr-2"></i> SIMPAN TRANSAKSI');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    $('button[type="submit"]').prop('disabled', false).html('<i class="bx bx-save font-size-16 align-middle mr-2"></i> SIMPAN TRANSAKSI');
                }
            });
        });
    });

    function renderTable() {
        let html = '';
        let grandTotal = 0;

        if (cartItems.length > 0) {
            $('#row-empty').hide();
        } else {
            $('#row-empty').show();
        }

        cartItems.forEach((item, index) => {
            grandTotal += item.subtotal;
            html += `
                <tr>
                    <td>${item.text}</td>
                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
                    <td>${item.jumlah}</td>
                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#table-items tbody tr:not(#row-empty)').remove();
        $('#table-items tbody').append(html);
        $('#text-total').text('Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal));
    }

    function removeItem(index) {
        cartItems.splice(index, 1);
        renderTable();
    }

    function resetInputItem() {
        $('.select2-obat').val(null).trigger('change');
        $('#input-harga').val('');
        $('#input-jumlah').val(1);
        currentObat = null;
    }
</script>
@endsection
