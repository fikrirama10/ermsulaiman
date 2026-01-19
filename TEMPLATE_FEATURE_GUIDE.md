# 📋 Panduan Fitur Template Rekam Medis

## ✨ Fitur yang Telah Dioptimasi

### 1. **Copy Template yang Lebih Lengkap**
Template sekarang menyalin semua data penting:
- ✅ SOAP (Subjective, Objective, Assessment, Plan) - Format JSON
- ✅ ICD-X (Diagnosa primer dan sekunder)
- ✅ ICD-9 (Prosedur/Tindakan)
- ✅ Anamnesa Dokter
- ✅ Pemeriksaan Fisik
- ✅ Pemeriksaan Fisioterapi
- ✅ Kategori Penyakit
- ✅ Rencana Pemeriksaan
- ✅ Terapi
- ✅ Tindakan
- ✅ Prosedur

**Data yang TIDAK di-copy** (karena sifatnya personal/per-kunjungan):
- ❌ Triase
- ❌ Alergi
- ❌ Riwayat kesehatan
- ❌ Order radiologi/laborat/fisio
- ❌ Resep obat (optional - bisa diaktifkan)

### 2. **Query Template yang Dioptimasi**
Template sekarang dipilih berdasarkan:
- 🔢 Jumlah penggunaan (paling sering digunakan)
- 📅 Tanggal terakhir digunakan (paling baru)
- 👨‍⚕️ Dokter yang sama
- 🏥 Poli yang sama
- 👤 Pasien yang sama

### 3. **UI Template yang Lebih Informatif**
Tampilan baru menampilkan:
- 📊 Card layout yang lebih rapi
- 🏷️ Badge menampilkan tanggal terakhir digunakan
- ✅ Badge menampilkan jumlah penggunaan
- 👁️ Tombol preview untuk melihat detail template
- ✔️ Tombol terapkan dengan konfirmasi

### 4. **Preview Template**
Fitur baru untuk melihat detail template sebelum diterapkan:
- Menampilkan SOAP lengkap
- Menampilkan ICD-X dan ICD-9
- Menampilkan anamnesa, pemeriksaan, dan terapi
- Modal popup yang informatif

## 🚀 Cara Menggunakan

### A. Menyimpan Template
Template otomatis tersimpan saat dokter menyimpan rekam medis yang lengkap dengan:
- Diagnosa
- SOAP data
- ICD-X (minimal 1 untuk dokter)

### B. Menggunakan Template
1. **Buka halaman rekam medis pasien**
2. **Lihat section "Template Diagnosa Tersedia"** di bagian atas
3. **Pilih salah satu template** yang sesuai:
   - Klik **Preview** untuk melihat detail
   - Klik **Terapkan** untuk menyalin data ke form

### C. Menandai Template Favorit (Opsional)
Gunakan route: `/rekam-medis/{id}/update-template`
- Toggle status template (favorit/tidak)
- Template favorit bisa diprioritaskan

## 🔧 Konfigurasi Lanjutan

### Copy Resep Obat (Optional)
Secara default, resep obat TIDAK di-copy. Untuk mengaktifkan:

```php
// Di RekapMedisController@copy_template
// Uncomment baris ini:
$this->copyResepObat($id, $idrawatbaru);
```

### Menambah Field Custom
Edit method `copy_template` di controller:

```php
// Tambahkan field baru
$detail_baru->field_custom = $originalPost->field_custom;
```

## 📊 Struktur Data

### SOAP JSON Format
```json
{
    "subjective": "Keluhan pasien...",
    "objective": "Hasil pemeriksaan...",
    "assessment": "Diagnosa kerja...",
    "plan": "Rencana tindakan...",
    "created_at": "2026-01-19T10:30:00.000Z"
}
```

### ICD-X JSON Format
```json
[
    {
        "diagnosa_icdx": "A01.0",
        "jenis_diagnosa": "P"
    },
    {
        "diagnosa_icdx": "A01.1",
        "jenis_diagnosa": "S"
    }
]
```

## 🛠️ Error Handling

### Kesalahan Umum dan Solusi

**1. "Template tidak ditemukan"**
- Pastikan template source masih ada di database
- Pastikan rekam medis memiliki detail

**2. "Data gagal disalin. Harap perawat mengisi data terlebih dahulu"**
- Perawat harus input data vital signs terlebih dahulu
- Rekap medis target harus sudah dibuat

**3. "Detail template tidak ditemukan"**
- Template tidak memiliki data detail
- Pastikan rekam medis source lengkap

## 📈 Performa

### Query Optimization
- Join efisien dengan index yang tepat
- Group by pada diagnosa untuk menghindari duplikasi
- Limit 10 untuk performa optimal
- Order by usage_count dan last_used untuk relevansi

### Caching (Future Enhancement)
```php
// Bisa ditambahkan di masa depan:
Cache::remember("templates_{$pasien_id}_{$dokter_id}", 3600, function() {
    // Query template...
});
```

## 🔐 Keamanan

### Validasi
- ✅ Check ownership (pasien, dokter, poli)
- ✅ Try-catch untuk error handling
- ✅ Log error untuk debugging
- ✅ Konfirmasi sebelum apply template

### Audit Trail
Pertimbangkan menambah logging:
```php
Log::info('Template applied', [
    'user_id' => auth()->id(),
    'template_id' => $rekap_lama->id,
    'target_rawat' => $idrawatbaru
]);
```

## 📝 Changelog

### Version 2.0 (19 Jan 2026)
- ✅ Copy SOAP data lengkap (JSON format)
- ✅ Copy ICD-X dan ICD-9
- ✅ Query template yang dioptimasi
- ✅ UI template dengan card layout
- ✅ Preview template sebelum apply
- ✅ Badge informasi (tanggal, usage count)
- ✅ Better error handling & logging
- ✅ Method get_template_detail untuk preview
- ✅ Helper method copyResepObat (optional)

### Version 1.0 (Sebelumnya)
- ⚠️ Hanya copy diagnosa, ICD-X, ICD-9 basic
- ⚠️ UI sederhana (button list)
- ⚠️ Tidak ada preview
- ⚠️ Query belum optimal

## 🎯 Best Practices

1. **Konsistensi Data**: Pastikan dokter mengisi data lengkap agar template berkualitas
2. **Review Template**: Selalu review data yang di-copy sebelum menyimpan
3. **Update Template**: Hapus template yang sudah tidak relevan
4. **Dokumentasi**: Catat diagnosa dengan jelas dan konsisten
5. **Testing**: Test template sebelum digunakan untuk kasus penting

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Check error log di `storage/logs/laravel.log`
2. Periksa console browser untuk error JavaScript
3. Validasi data template di database

---

**Dibuat oleh**: Tim Development SIMRS
**Terakhir diupdate**: 19 Januari 2026
**Versi**: 2.0
