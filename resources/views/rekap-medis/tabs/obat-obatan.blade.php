{{-- Obat-Obatan Tab Content --}}
@if(auth()->user()->idpriv == 7 && ($rawat->status != 4 && $rawat->status != 5))
    {{-- Form Input Obat Non-Racikan --}}
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ki-duotone ki-capsule fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                Input Obat Non-Racikan
            </h3>
        </div>
        <div class="card-body">
            <form id="frmNonracikan" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label required">Nama Obat</label>
                        <select name="obat_non" id="nama_obat" class="form-select form-select-sm selectObat" required></select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label required">Jumlah</label>
                        <input type="number" name="jumlah_obat" class="form-control form-control-sm" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required">Dosis</label>
                        <input type="text" name="dosis_obat" class="form-control form-control-sm" placeholder="1x1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required">Sediaan</label>
                        <select name="takaran_obat" class="form-select form-select-sm" required>
                            <option value="">Pilih</option>
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="bungkus">Bungkus</option>
                            <option value="tetes">Tetes</option>
                            <option value="ml">ML</option>
                            <option value="sendok takar 5ml">Sendok Takar 5ml</option>
                            <option value="sendok takar 15ml">Sendok Takar 15ml</option>
                            <option value="oles">Oles</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Aturan Pakai</label>
                        <div class="d-flex gap-2">
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Pagi" id="pagi">
                                <label class="form-check-label" for="pagi">Pagi</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Siang" id="siang">
                                <label class="form-check-label" for="siang">Siang</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Malam" id="malam">
                                <label class="form-check-label" for="malam">Malam</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Diminum</label>
                        <select name="takaran" class="form-select form-select-sm">
                            <option value="Sebelum">Sebelum Makan</option>
                            <option value="Sesudah">Sesudah Makan</option>
                            <option value="Saat">Saat Makan</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan tambahan">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-primary w-100" id="upload">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Tambah Obat
                            </span>
                            <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Input Obat Racikan --}}
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ki-duotone ki-test-tube fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Input Obat Racikan
            </h3>
        </div>
        <div class="card-body">
            <form id="frmRacikan" method="POST">
                @csrf
                <div id="obat_repeater">
                    <div data-repeater-list="obat">
                        <div data-repeater-item class="mb-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label">Nama Obat</label>
                                    <select name="obat[]" class="form-select form-select-sm selectObat"></select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah_obat[]" class="form-control form-control-sm" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" data-repeater-delete class="btn btn-sm btn-danger w-100">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Tambah Obat
                        </button>
                    </div>
                </div>

                <div class="separator my-4"></div>

                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label required">DTD</label>
                        <input type="number" name="dtd" class="form-control form-control-sm" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required">Dosis</label>
                        <input type="text" name="dosis_obat" class="form-control form-control-sm" placeholder="1x1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required">Sediaan</label>
                        <select name="takaran_obat" class="form-select form-select-sm" required>
                            <option value="">Pilih</option>
                            <option value="bungkus">Bungkus</option>
                            <option value="kapsul">Kapsul</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Pemberian</label>
                        <select name="pemberian" class="form-select form-select-sm">
                            <option value="Oral">Oral</option>
                            <option value="Injeksi">Injeksi</option>
                            <option value="Topikal">Topikal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Aturan Pakai</label>
                        <div class="d-flex gap-2">
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Pagi" id="pagi_racik">
                                <label class="form-check-label" for="pagi_racik">P</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Siang" id="siang_racik">
                                <label class="form-check-label" for="siang_racik">S</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="checkbox" name="diminum[]" value="Malam" id="malam_racik">
                                <label class="form-check-label" for="malam_racik">M</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Diminum</label>
                        <select name="takaran" class="form-select form-select-sm">
                            <option value="Sebelum">Sebelum Makan</option>
                            <option value="Sesudah">Sesudah Makan</option>
                            <option value="Saat">Saat Makan</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-10">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan tambahan">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-success w-100" id="upload_racikan">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-plus fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Simpan Racikan
                            </span>
                            <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Daftar Resep Obat --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-capsule fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
            Daftar Resep Obat
        </h3>
        <div class="card-toolbar">
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modal_riwayat_resep">
                <i class="ki-duotone ki-time fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Riwayat Resep
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id='list_resep' class="table table-sm table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr class="fw-bold">
                        <th>Nama Obat</th>
                        <th class="w-100px">Jumlah</th>
                        <th>Dosis</th>
                        <th>Sediaan</th>
                        <th>Aturan Pakai</th>
                        <th>Diminum</th>
                        <th>Catatan</th>
                        <th class="w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($resep_dokter) && count($resep_dokter) > 0)
                        @foreach ($resep_dokter as $rd)
                            <tr id='li{{ $rd->id }}'>
                                <td>
                                    @if($rd->jenis == 'Racik')
                                        @php
                                            // Decode nama_obat yang berisi struktur {"obat":[{"obat":"422","jumlah_obat":"1"},{"obat":"273","jumlah_obat":"2"}],"jumlah":null}
                                            $nama_obat_data = json_decode($rd->nama_obat);
                                            $obat_list = [];

                                            if ($nama_obat_data && isset($nama_obat_data->obat) && is_array($nama_obat_data->obat)) {
                                                foreach ($nama_obat_data->obat as $item) {
                                                    if (is_object($item)) {
                                                        $obat_id = isset($item->obat) ? $item->obat : (isset($item->{'obat['}) ? $item->{'obat['} : null);
                                                        $jumlah = isset($item->jumlah_obat) ? $item->jumlah_obat : (isset($item->{'jumlah_obat['}) ? $item->{'jumlah_obat['} : 0);

                                                        if ($obat_id) {
                                                            $obat_list[] = [
                                                                'id' => $obat_id,
                                                                'jumlah' => $jumlah
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if(count($obat_list) > 0)
                                            @foreach($obat_list as $index => $obat_item)
                                                @php
                                                    $obat = \App\Models\Obat\Obat::find($obat_item['id']);
                                                @endphp
                                                {{ $obat ? $obat->nama_obat : 'Obat tidak ditemukan' }} ({{ $obat_item['jumlah'] }})
                                                @if(!$loop->last) + @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">Data racikan tidak valid</span>
                                        @endif
                                        <span class="badge badge-success badge-sm ms-2">Racikan</span>
                                    @else
                                        {{ $rd->nama_obat }}
                                    @endif
                                </td>
                                <td>
                                    @if($rd->jenis == 'Racik')
                                        @php
                                            // Hitung total jumlah dari array obat
                                            $nama_obat_data = json_decode($rd->nama_obat);
                                            $total_jumlah = 0;

                                            if ($nama_obat_data && isset($nama_obat_data->obat) && is_array($nama_obat_data->obat)) {
                                                foreach ($nama_obat_data->obat as $item) {
                                                    if (is_object($item)) {
                                                        $jumlah = isset($item->jumlah_obat) ? (int)$item->jumlah_obat : (isset($item->{'jumlah_obat['}) ? (int)$item->{'jumlah_obat['} : 0);
                                                        $total_jumlah += $jumlah;
                                                    }
                                                }
                                            }
                                        @endphp
                                        {{ $total_jumlah }}
                                    @else
                                        @if(auth()->user()->idpriv == 7 && ($rawat->status != 4 && $rawat->status != 5))
                                            <input type="number" class="form-control form-control-sm number-jumlah" data-id="{{ $rd->id }}" value="{{ $rd->jumlah }}" style="width: 80px;">
                                        @else
                                            {{ $rd->jumlah }}
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $rd->dosis }}</td>
                                <td>{{ $rd->takaran }}</td>
                                <td>
                                    @php
                                        $signa = json_decode($rd->signa);
                                        echo is_array($signa) ? implode(', ', $signa) : $rd->signa;
                                    @endphp
                                </td>
                                <td>{{ $rd->diminum }}</td>
                                <td>{{ $rd->catatan }}</td>
                                <td>
                                    @if (auth()->user()->idpriv == 7 && ($rawat->status != 4 && $rawat->status != 5))
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-danger btn-hapus" id='{{ $rd->id }}'>
                                                <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="ki-duotone ki-information-5 fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <p>Belum ada resep obat</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Modal Riwayat Resep --}}
<div class="modal fade" id="modal_riwayat_resep" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ki-duotone ki-time fs-2 text-warning me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat Resep Pasien
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table id="tblRiwayatResep" class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Resep</th>
                            <th>Tanggal</th>
                            <th>Obat</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
