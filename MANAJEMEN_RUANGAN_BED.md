# Dokumentasi Manajemen Master Data Ruangan & Bed

## Fitur yang Telah Ditambahkan

### 1. Manajemen Ruangan
**Halaman:** `/data-master/ruangan`

#### Fitur:
- ✅ **Tambah Ruangan Baru** - Modal form untuk menambahkan ruangan baru dengan pilihan:
  - Jenis Ruangan
  - Nama Ruangan
  - Gender (Pria/Wanita/Umum)
  - Kelas Ruangan
  - Status (Aktif/Nonaktif)
  - Keterangan

- ✅ **Aktif/Nonaktif Ruangan** - Tombol untuk mengaktifkan atau menonaktifkan ruangan
  - Tombol **Aktifkan** (hijau) untuk ruangan yang nonaktif
  - Tombol **Nonaktifkan** (kuning) untuk ruangan yang aktif
  - Status ditampilkan dengan badge: 
    - Badge hijau untuk "Aktif"
    - Badge merah untuk "Nonaktif"

- ✅ **Kelola Bed** - Tombol untuk masuk ke halaman manajemen bed di ruangan tersebut

#### Kolom Tabel:
- No
- Nama Ruangan
- Kelas
- Ruangan Jenis
- Gender
- Kapasitas
- Status (Badge Aktif/Nonaktif)
- Opsi (Tombol Aktifkan/Nonaktifkan + Kelola Bed)

---

### 2. Manajemen Bed
**Halaman:** `/data-master/ruangan/bed/{id_ruangan}`

#### Fitur:
- ✅ **Informasi Ruangan** - Menampilkan detail ruangan:
  - Nama Ruangan
  - Kapasitas
  - Gender
  - Kelas
  - Ruangan Jenis
  - Keterangan

- ✅ **Tambah Bed Baru** - Modal form untuk menambahkan bed baru:
  - Kode Bed (otomatis generate)
  - Bayi (Ya/Tidak)
  - Status (Aktif/Nonaktif)

- ✅ **Aktif/Nonaktif Bed** - Tombol untuk mengaktifkan atau menonaktifkan bed:
  - Tombol **Aktifkan** (hijau) untuk bed yang nonaktif
  - Tombol **Nonaktifkan** (kuning) untuk bed yang aktif

- ✅ **Edit Bed** - Tombol untuk mengedit data bed (Bayi & Status)

- ✅ **Status Terisi/Kosong** - Menampilkan status apakah bed sedang terisi atau kosong:
  - Badge merah untuk "Terisi"
  - Badge hijau untuk "Kosong"

#### Kolom Tabel Bed:
- No
- Kode BED
- Status (Badge Aktif/Nonaktif)
- Terisi (Badge Terisi/Kosong)
- Opsi (Tombol Aktifkan/Nonaktifkan + Edit)

---

## Route yang Ditambahkan

### Route Ruangan:
```php
// Toggle status aktif/nonaktif ruangan
Route::post('/ruangan/{id}/toggle-status', [RuanganController::class, 'toggleStatus'])
    ->name('toggle.ruangan.status');
```

### Route Bed:
```php
// Toggle status aktif/nonaktif bed
Route::post('/ruangan/bed/{id}/toggle-status', [RuanganBedController::class, 'toggleBedStatus'])
    ->name('toggle.bed.status');
```

---

## Method Controller yang Ditambahkan

### RuanganController:
```php
// Aktif/Nonaktifkan ruangan
public function toggleStatus($id)
{
    $ruangan = Ruangan::findOrFail($id);
    $ruangan->status = $ruangan->status ? 0 : 1;
    $ruangan->save();
    return back();
}
```

### RuanganBedController:
```php
// Aktif/Nonaktifkan bed
public function toggleBedStatus($id)
{
    $bed = RuanganBed::findOrFail($id);
    $bed->status = $bed->status ? 0 : 1;
    $bed->save();
    return redirect()->back()->with('berhasil', 'Status BED berhasil diubah!');
}
```

---

## Cara Penggunaan

### Mengelola Ruangan:
1. Akses halaman `/data-master/ruangan`
2. Klik **"Tambah Data Ruangan"** untuk menambah ruangan baru
3. Klik tombol **"Aktifkan"** atau **"Nonaktifkan"** untuk mengubah status ruangan
4. Klik tombol **"Kelola Bed"** untuk masuk ke halaman manajemen bed

### Mengelola Bed:
1. Dari halaman ruangan, klik **"Kelola Bed"** pada ruangan yang ingin dikelola
2. Klik **"Tambah Data BED"** untuk menambah bed baru
3. Klik tombol **"Aktifkan"** atau **"Nonaktifkan"** untuk mengubah status bed
4. Klik tombol **"Edit"** untuk mengubah data bed (Bayi & Status)
5. Lihat status **"Terisi"** atau **"Kosong"** pada kolom terisi

---

## Catatan Penting

### Status Field di Database:
- **ruangan.status**: 
  - `1` = Aktif
  - `0` = Nonaktif

- **ruangan_bed.status**: 
  - `1` = Aktif
  - `0` = Nonaktif

- **ruangan_bed.terisi**: 
  - `1` = Terisi
  - `0` = Kosong

- **ruangan_bed.bayi**: 
  - `1` = Ya (untuk bayi)
  - `0` = Tidak (bukan untuk bayi)

### Kode Otomatis:
- **Kode Ruangan**: Auto-generate dengan format `RG001`, `RG002`, dst.
- **Kode Bed**: Auto-generate dengan format `BED0001`, `BED0002`, dst.

---

## Testing

Setelah implementasi, silakan test fitur berikut:

1. ✅ Tambah ruangan baru
2. ✅ Aktifkan/Nonaktifkan ruangan
3. ✅ Masuk ke halaman kelola bed
4. ✅ Tambah bed baru
5. ✅ Aktifkan/Nonaktifkan bed
6. ✅ Edit data bed
7. ✅ Lihat status terisi/kosong bed

---

## Update Terakhir
Tanggal: 18 Januari 2026
