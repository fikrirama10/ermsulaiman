# Form SOAP - Dokumentasi

## Overview
Form SOAP telah ditambahkan ke sistem rekam medis untuk membantu dokter mendokumentasikan pemeriksaan pasien dengan format standar medis.

## Apa itu SOAP?

SOAP adalah metode dokumentasi medis yang terdiri dari 4 komponen:

### S - Subjective (Keluhan Pasien)
- Informasi yang disampaikan langsung oleh pasien atau keluarga
- Contoh: Keluhan utama, riwayat penyakit sekarang, anamnesis

### O - Objective (Pemeriksaan Fisik & Penunjang)
- Data objektif hasil pemeriksaan fisik dan penunjang
- Contoh: Vital signs, hasil laboratorium, hasil radiologi

### A - Assessment (Diagnosis & Evaluasi)
- Penilaian klinis dan diagnosis berdasarkan data S dan O
- Contoh: Diagnosis kerja, diagnosis banding, evaluasi kondisi pasien

### P - Plan (Rencana Tindakan & Terapi)
- Rencana penatalaksanaan dan tindak lanjut
- Contoh: Rencana pemeriksaan lanjutan, terapi medikamentosa, tindakan medis, edukasi pasien

## Implementasi

### Database
- **Tabel**: `demo_detail_rekap_medis`
- **Kolom**: `soap_data` (TEXT, nullable)
- **Format**: JSON

### Form Fields
Semua field SOAP adalah **required** untuk dokter (idpriv == 7):
- `soap[subjective]` - Textarea
- `soap[objective]` - Textarea
- `soap[assessment]` - Textarea
- `soap[plan]` - Textarea

### Data Flow

1. **Input**: User mengisi form SOAP
2. **Validasi**: JavaScript memvalidasi semua field harus diisi
3. **Konversi**: Data dikonversi menjadi JSON sebelum submit
4. **Storage**: Disimpan ke database dalam format JSON
5. **Display**: JSON di-decode saat ditampilkan

### Contoh JSON yang Disimpan
```json
{
    "subjective": "Pasien mengeluh demam sejak 3 hari yang lalu, disertai batuk berdahak",
    "objective": "TD: 120/80 mmHg, Nadi: 88x/menit, Suhu: 38.5°C, RR: 20x/menit. Pemeriksaan fisik: Ronki (+) di kedua lapang paru",
    "assessment": "Diagnosis: Bronkitis Akut (J20.9). Kondisi umum pasien stabil, tidak ada tanda bahaya",
    "plan": "1. Antibiotik Amoxicillin 500mg 3x1 selama 5 hari\n2. Obat batuk OBH 3x1 sendok\n3. Paracetamol 500mg bila demam\n4. Kontrol 3 hari lagi atau jika keluhan memburuk",
    "created_at": "2026-01-17T10:30:00.000Z"
}
```

## Cara Menggunakan

### Untuk Developer

1. **Menjalankan Migration**:
   ```bash
   php artisan migrate
   ```

2. **Menampilkan Data SOAP**:
   ```php
   // Di Controller
   $soap_data = json_decode($rekap->soap_data);
   
   // Di View
   @if($soap_data)
       <h4>Subjective:</h4>
       <p>{{ $soap_data->subjective }}</p>
       
       <h4>Objective:</h4>
       <p>{{ $soap_data->objective }}</p>
       
       <h4>Assessment:</h4>
       <p>{{ $soap_data->assessment }}</p>
       
       <h4>Plan:</h4>
       <p>{{ $soap_data->plan }}</p>
   @endif
   ```

### Untuk User (Dokter)

1. Buka form rekam medis pasien
2. Isi semua field SOAP (wajib):
   - **S**: Tulis keluhan pasien
   - **O**: Tulis hasil pemeriksaan fisik
   - **A**: Tulis diagnosis dan penilaian
   - **P**: Tulis rencana tindakan dan terapi
3. Klik tombol **Simpan**
4. Data akan tersimpan dalam format JSON

## Files Modified

1. **View**: `resources/views/detail-rekap-medis/create.blade.php`
   - Added SOAP form section
   - Added JavaScript validation
   - Added JSON conversion logic

2. **Controller**: `app/Http/Controllers/DetailRekapMedisController.php`
   - Added `soap_data` handling in `store()` method
   - Added `soap_data` handling in `update()` method
   - Added `soap_data` to `show()` method

3. **Migration**: `database/migrations/2026_01_17_000001_add_soap_data_to_demo_detail_rekap_medis_table.php`
   - Added `soap_data` column to `demo_detail_rekap_medis` table

## Benefits

1. ✅ **Standarisasi**: Menggunakan format dokumentasi medis yang standar
2. ✅ **Terstruktur**: Data tersimpan secara terstruktur dalam JSON
3. ✅ **Mudah Dibaca**: Format yang jelas dan mudah dipahami
4. ✅ **Validasi**: Semua field wajib diisi untuk memastikan dokumentasi lengkap
5. ✅ **Fleksibel**: Data JSON dapat dengan mudah diproses atau diexport

## Troubleshooting

### Field SOAP tidak muncul
- Pastikan user memiliki `idpriv == 7` (dokter)

### Data tidak tersimpan
- Pastikan migration sudah dijalankan
- Cek apakah kolom `soap_data` ada di tabel `demo_detail_rekap_medis`

### Error saat submit
- Pastikan semua field SOAP sudah diisi
- Cek console browser untuk error JavaScript
