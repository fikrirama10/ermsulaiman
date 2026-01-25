# 🚀 Two-Factor Authentication - Quick Start Guide

## ⚡ Setup Cepat (5 Menit)

### 1️⃣ Jalankan Migration
```bash
php artisan migrate
```

### 2️⃣ Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3️⃣ Akses Menu 2FA
Login → Klik User Menu (pojok kanan atas) → "Two-Factor Authentication"

---

## 👤 Panduan User

### ✅ Cara Aktifkan 2FA (Pertama Kali)

1. **Download Google Authenticator** di HP
   - Android: Play Store
   - iOS: App Store

2. **Buka Settings 2FA**
   - Login ke aplikasi
   - User Menu → "Two-Factor Authentication"
   - Klik "Aktifkan Two-Factor Authentication"

3. **Scan QR Code**
   - Buka Google Authenticator
   - Tap tombol "+"
   - Pilih "Scan QR Code"
   - Scan QR yang ditampilkan di layar

4. **Verifikasi**
   - Input kode 6-digit dari Google Authenticator
   - Klik "Verifikasi & Aktifkan"

5. **Simpan Recovery Codes** ⚠️ PENTING!
   - Download atau print recovery codes
   - Simpan di tempat aman
   - Jangan share ke siapapun

✅ **2FA Aktif!**

---

### 🔐 Cara Login dengan 2FA

1. Input username & password seperti biasa
2. ✨ **Halaman baru muncul**: "Verifikasi 2FA"
3. Buka **Google Authenticator** di HP
4. Input **kode 6-digit** yang muncul
5. Klik "Verifikasi"
6. ✅ Login berhasil!

**Tips:** Kode berubah setiap 30 detik. Jika expired, tunggu kode baru muncul.

---

### 🔑 Cara Menggunakan Recovery Code

**Kapan digunakan?**
- HP hilang
- Ganti HP baru
- Aplikasi Google Authenticator terhapus

**Cara pakai:**
1. Di halaman "Verifikasi 2FA"
2. Input **recovery code** (10 karakter, bukan 6 digit)
3. Contoh: `A1B2C3D4E5`
4. Klik "Verifikasi"

⚠️ **Setiap recovery code hanya bisa digunakan 1x**

Setelah pakai recovery code:
- Login segera ke aplikasi
- **Regenerate recovery codes baru**
- Setup ulang Google Authenticator

---

### ❌ Cara Nonaktifkan 2FA

1. Login ke aplikasi (dengan 2FA)
2. User Menu → "Two-Factor Authentication"
3. Scroll ke bawah → Klik **"Nonaktifkan 2FA"** (tombol merah)
4. Input:
   - Password Anda
   - Kode OTP dari Google Authenticator
5. Klik "Nonaktifkan 2FA"
6. ✅ 2FA dinonaktifkan

---

## 🔄 Maintenance

### Regenerate Recovery Codes

**Lakukan setiap 6 bulan atau jika:**
- Recovery code sudah digunakan
- Merasa codes tidak aman

**Cara:**
1. User Menu → "Two-Factor Authentication"
2. Klik "Lihat Recovery Codes"
3. Klik "Regenerate Recovery Codes"
4. Input password
5. Simpan codes baru

---

## ⚠️ Troubleshooting Cepat

### ❗ "Kode OTP tidak valid"

**Penyebab:**
- Kode sudah expired (>30 detik)
- Waktu HP tidak sinkron

**Solusi:**
1. Tunggu kode baru di Google Authenticator
2. Check waktu/timezone HP sudah benar
3. Sinkronkan waktu: Settings HP → Date & Time → Auto

---

### ❗ "Terlalu banyak percobaan gagal"

**Penyebab:**
- Input salah 3x berturut-turut

**Solusi:**
1. Kembali ke halaman login
2. Login ulang dengan username & password
3. Input OTP yang benar atau gunakan recovery code

---

### ❗ "Secret key tidak ditemukan"

**Penyebab:**
- Session expired saat setup

**Solusi:**
1. Kembali ke halaman settings 2FA
2. Klik "Aktifkan 2FA" lagi
3. Ulangi proses dari awal

---

### ❗ HP Hilang / Ganti HP

**Solusi 1: Pakai Recovery Code**
1. Login dengan username & password
2. Input recovery code (bukan OTP)
3. Setelah masuk, setup ulang 2FA di HP baru

**Solusi 2: Minta Bantuan Admin**
1. Hubungi admin IT
2. Admin akan disable 2FA dari sistem
3. Login normal tanpa 2FA
4. Setup 2FA di HP baru

---

## 🎯 Tips & Best Practices

### ✅ DO (Lakukan)
- ✅ Simpan recovery codes di tempat aman (password manager, safe)
- ✅ Screenshot atau print recovery codes
- ✅ Test login dengan 2FA setelah setup
- ✅ Regenerate recovery codes setiap 6 bulan
- ✅ Aktifkan 2FA untuk keamanan maksimal

### ❌ DON'T (Jangan)
- ❌ Share QR code ke orang lain
- ❌ Screenshot QR code di device yang tidak aman
- ❌ Share recovery codes ke siapapun
- ❌ Simpan recovery codes di email/chat
- ❌ Panik jika lupa - ada recovery code!

---

## 📱 Download Google Authenticator

### Android
🔗 https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2

### iOS
🔗 https://apps.apple.com/app/google-authenticator/id388497605

---

## 🆘 Butuh Bantuan?

### Self-Service
1. Gunakan recovery code
2. Baca dokumentasi lengkap: `TWO_FACTOR_AUTHENTICATION_GUIDE.md`

### Hubungi Admin
- Email: [IT Support Email]
- Phone: [IT Support Phone]
- Ticket System: [Support URL]

**Info yang perlu disiapkan:**
- Username Anda
- Deskripsi masalah
- Screenshot error (jika ada)

---

## 📋 Quick Reference

| Aksi | URL | Keterangan |
|------|-----|------------|
| Settings 2FA | `/profile/two-factor` | Halaman utama pengaturan |
| Aktifkan 2FA | `/profile/two-factor/enable` | Setup 2FA baru |
| Recovery Codes | `/profile/two-factor/recovery-codes` | Lihat/regenerate codes |
| Login 2FA | `/two-factor-challenge` | Halaman input OTP saat login |

---

## 🔢 Format & Panjang

| Item | Format | Contoh |
|------|--------|--------|
| OTP Code | 6 digit angka | `123456` |
| Recovery Code | 10 karakter huruf/angka | `A1B2C3D4E5` |
| Secret Key | 16 karakter base32 | `JBSWY3DPEHPK3PXP` |

---

## ⏱️ Timeout & Limits

| Item | Durasi | Keterangan |
|------|--------|------------|
| OTP Validity | 30 detik | Kode berubah tiap 30 detik |
| Failed Attempts | 3x | Maksimal percobaan gagal |
| Session 2FA | 1 session | Per login session |
| Recovery Codes | 8 codes | Total codes default |

---

**Last Updated:** 22 Januari 2026
**Version:** 1.0.0

---

🎉 **Selamat! Akun Anda sekarang lebih aman dengan 2FA!** 🔐
