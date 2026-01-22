# MODUL MONITORING AKTIVITAS SISTEM SIMRS

## 📋 Deskripsi
Modul monitoring komprehensif untuk memantau semua aktivitas di sistem SIMRS termasuk:
- Kunjungan pasien
- Input dokter & perawat
- Rekam medis
- Tindak lanjut
- Perubahan data
- Dan semua aktivitas lainnya

## 🎯 Fitur Utama

### 1. **Auto-Logging**
- Otomatis mencatat setiap CREATE, UPDATE, DELETE pada model penting
- Menyimpan data before & after untuk tracking perubahan
- Capture IP address, user agent, dan URL

### 2. **Dashboard Monitoring**
- Statistik real-time (total aktivitas, kunjungan, rekam medis, tindak lanjut)
- Timeline aktivitas dengan detail lengkap
- Filter multi-kriteria (periode, kategori, event, role, search)
- Export ke Excel

### 3. **Detail Tracking**
- User yang melakukan (nama & role: dokter/perawat/coder)
- Model yang diubah (polymorphic relation)
- Field apa saja yang berubah (old vs new value)
- Informasi medis (No RM, Poli, Dokter)
- Timestamp akurat

## 📁 Struktur File

```
app/
├── Models/
│   └── ActivityLog.php                    # Model untuk activity logs
├── Traits/
│   └── LogsActivity.php                   # Trait untuk auto-logging
├── Http/Controllers/
│   └── ActivityLogController.php          # Controller monitoring
└── Exports/
    └── ActivityLogExport.php              # Export Excel (optional)

database/migrations/
└── 2026_01_20_100218_create_activity_logs_table.php

resources/views/monitoring/
├── index.blade.php                        # Dashboard utama
├── show.blade.php                         # Detail aktivitas (optional)
├── by-patient.blade.php                   # Filter per pasien (optional)
└── by-rawat.blade.php                     # Filter per kunjungan (optional)

routes/web.php                             # Routes monitoring
```

## 🗄️ Database Schema

### Tabel: `activity_logs`

| Field | Type | Deskripsi |
|-------|------|-----------|
| id | bigint | Primary key |
| log_name | varchar | Kategori (kunjungan, rekam_medis, tindak_lanjut, dll) |
| description | text | Deskripsi aktivitas |
| subject_type | varchar | Model yang diubah (polymorphic) |
| subject_id | bigint | ID model yang diubah |
| causer_type | varchar | Model user (polymorphic) |
| causer_id | bigint | User ID yang melakukan |
| event | varchar | created, updated, deleted, viewed |
| properties | json | Data before & after |
| batch_uuid | varchar | Untuk group operasi bulk |
| user_id | bigint | ID user |
| user_name | varchar | Nama user |
| user_role | varchar | Role (dokter, perawat, coder) |
| ip_address | varchar(45) | IP address |
| user_agent | varchar | Browser/device info |
| url | varchar | URL yang diakses |
| method | varchar(10) | HTTP method (GET, POST, dll) |
| no_rm | varchar | No Rekam Medis (for medical context) |
| idrawat | bigint | ID kunjungan |
| poli | varchar | Nama poli |
| dokter | varchar | Nama dokter |
| created_at | timestamp | Waktu aktivitas |
| updated_at | timestamp | - |

### Indexes
- log_name, event, created_at
- user_id, no_rm, idrawat
- subject_type + subject_id (polymorphic)
- causer_type + causer_id (polymorphic)

## 🚀 Cara Pakai

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Tambahkan Trait ke Model

Untuk model yang ingin ditrack, tambahkan trait `LogsActivity`:

```php
<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class NamaModel extends Model
{
    use LogsActivity;
    
    // ... model code
}
```

**Model yang sudah diterapkan:**
- ✅ `TindakLanjut` - Tindak lanjut pasien
- ✅ `Rawat` - Kunjungan pasien
- ✅ `RekapMedis` - Rekam medis

**Model yang direkomendasikan:**
- `DetailRekapMedis` - Detail rekam medis
- `Pasien` - Data pasien
- `RawatInap` - Rawat inap
- `Resep` - Resep obat
- Model penting lainnya

### 3. Akses Dashboard Monitoring

**URL:** `/monitoring`

**Filter yang tersedia:**
- Periode: Hari ini, Minggu ini, Bulan ini
- Tanggal: Range custom
- Kategori: Kunjungan, Rekam Medis, Tindak Lanjut, Pasien
- Event: Created, Updated, Deleted, Viewed
- Role: Dokter, Perawat, Coder
- Search: Nama, RM, Poli, Dokter

### 4. Custom Logging

Untuk logging manual di controller:

```php
// Log custom activity
$model->logCustomActivity('Mencetak berkas rekam medis', [
    'action' => 'print',
    'document' => 'rekam_medis',
    'format' => 'PDF'
]);
```

## 📊 Statistik yang Ditampilkan

1. **Total Aktivitas** - Jumlah total aktivitas dalam periode
2. **Kunjungan Pasien** - Jumlah aktivitas kunjungan
3. **Rekam Medis** - Jumlah input dokter & perawat
4. **Tindak Lanjut** - Jumlah tindak lanjut dibuat
5. **By Event** - Distribusi created/updated/deleted
6. **By User** - Top 5 user paling aktif
7. **By Hour** - Distribusi aktivitas per jam

## 🔍 Contoh Use Case

### 1. Audit Trail Rekam Medis
Melihat siapa saja yang mengubah rekam medis pasien tertentu:
- Filter by No RM
- Lihat timeline perubahan
- Bandingkan data lama vs baru

### 2. Monitoring Produktivitas
Melihat aktivitas dokter/perawat dalam periode tertentu:
- Filter by Role + Periode
- Lihat jumlah input
- Export untuk laporan

### 3. Investigasi Error
Jika ada data salah, tracking siapa yang input:
- Cari berdasarkan No RM atau Pasien
- Lihat waktu dan user
- Check IP address dan device

### 4. Compliance & Reporting
Generate laporan aktivitas untuk compliance:
- Export Excel dengan filter lengkap
- Include timestamp, user, dan changes
- Dokumentasi audit

## 🎨 Tampilan UI

### Dashboard Features:
- ✅ Card statistik warna-warni (total, kunjungan, rekam medis, tindak lanjut)
- ✅ Filter panel dengan 8+ filter options
- ✅ Timeline dengan icons dan badges
- ✅ Detail perubahan data (old → new)
- ✅ Badge untuk role (dokter/perawat/coder)
- ✅ Medical context (RM, Poli, Dokter)
- ✅ Pagination
- ✅ Export Excel button

### Icons & Colors:
- 🟢 Created = Green
- 🔵 Updated = Blue  
- 🔴 Deleted = Red
- ℹ️ Viewed = Info

## 🔒 Security & Performance

### Security:
- ✅ Middleware `auth` di semua routes
- ✅ Role-based access (bisa ditambahkan)
- ✅ Capture IP address untuk forensik
- ✅ User agent tracking

### Performance:
- ✅ Indexed columns untuk query cepat
- ✅ Pagination (50 per page)
- ✅ Lazy loading relationships
- ✅ Query optimization dengan select specific fields

### Maintenance:
- Cleanup old logs via route `/monitoring/cleanup`
- Default: hapus log > 90 hari (configurable)

## 📝 Routes

```php
GET  /monitoring                    # Dashboard
GET  /monitoring/show/{id}          # Detail aktivitas
GET  /monitoring/patient/{no_rm}    # Filter per pasien
GET  /monitoring/rawat/{idrawat}    # Filter per kunjungan
GET  /monitoring/export             # Export Excel
POST /monitoring/cleanup            # Hapus log lama
GET  /monitoring/chart-data         # API untuk chart
```

## ⚙️ Konfigurasi

### Customize Kategori Log
Edit di `LogsActivity.php` method `getLogName()`:

```php
protected function getLogName(): string
{
    $categories = [
        'Rawat' => 'kunjungan',
        'RekapMedis' => 'rekam_medis',
        // Tambah kategori baru
        'Resep' => 'resep_obat',
    ];
    
    return $categories[$modelName] ?? Str::snake($modelName);
}
```

### Customize Description
Edit di `LogsActivity.php` method `getActivityDescription()`.

### Disable Logging untuk Model Tertentu
Hapus trait `LogsActivity` dari model, atau tambahkan property:

```php
protected static $recordEvents = []; // Disable all events
```

## 🎯 Best Practices

1. **Gunakan untuk Model Penting Saja** - Jangan track semua model untuk performa
2. **Regular Cleanup** - Hapus log lama secara berkala
3. **Monitor Ukuran Tabel** - activity_logs bisa besar, pantau growth
4. **Index Management** - Pastikan index sesuai query pattern
5. **Backup** - Backup tabel activity_logs secara terpisah

## 📞 Support

Untuk pertanyaan atau issue:
1. Check dokumentasi ini
2. Review code di `app/Traits/LogsActivity.php`
3. Test di environment development dulu

## 🚦 Status

- ✅ Migration dibuat
- ✅ Model & Trait selesai
- ✅ Controller lengkap
- ✅ View dashboard responsive
- ✅ Routes configured
- ✅ Trait diterapkan di 3 model utama
- ⏳ Migration perlu dijalankan
- ⏳ Testing fitur
- ⏳ Export Excel (optional - perlu install Maatwebsite/Excel)

---

**Version:** 1.0.0  
**Last Updated:** 20 Januari 2026  
**Developer:** SIMRS Development Team
