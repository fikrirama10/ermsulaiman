# 📱 Two-Factor Authentication (2FA) - Panduan Lengkap

## 🎯 Overview

Fitur Two-Factor Authentication (2FA) telah berhasil diimplementasikan menggunakan **Google Authenticator**. Fitur ini memberikan layer keamanan tambahan untuk akun user dengan memerlukan kode verifikasi 6-digit dari aplikasi Google Authenticator setiap kali login.

### ✨ Fitur Utama

- ✅ **Aktivasi/Nonaktifkan Opsional** - User dapat memilih untuk mengaktifkan atau menonaktifkan 2FA
- ✅ **QR Code Scanning** - Setup mudah dengan scan QR Code
- ✅ **Manual Entry** - Opsi input manual jika QR scan tidak tersedia
- ✅ **Recovery Codes** - 8 kode backup untuk akses darurat
- ✅ **Secure Storage** - Secret key di-encrypt di database
- ✅ **Rate Limiting** - Proteksi terhadap brute force attack (3 percobaan)
- ✅ **Session Management** - 2FA verification per session
- ✅ **User Friendly UI** - Interface yang intuitif dan mudah dipahami

---

## 📦 Packages Terinstall

```json
"pragmarx/google2fa-laravel": "^2.3",
"bacon/bacon-qr-code": "^2.0"
```

**Sudah terinstall via Composer** ✅

---

## 🗄️ Database Schema

### Migration File
`database/migrations/2026_01_22_155539_add_two_factor_columns_to_user_table.php`

### Kolom Baru di Tabel `user`:

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `two_factor_secret` | TEXT (nullable) | Secret key Google 2FA (encrypted) |
| `two_factor_recovery_codes` | TEXT (nullable) | Recovery codes dalam format JSON (encrypted) |
| `two_factor_enabled` | BOOLEAN (default: false) | Status aktif/nonaktif 2FA |
| `two_factor_confirmed_at` | TIMESTAMP (nullable) | Waktu konfirmasi aktivasi 2FA |

### ⚠️ Jalankan Migration:
```bash
php artisan migrate
```

---

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── TwoFactorController.php           # Main controller untuk 2FA
│   └── Middleware/
│       └── TwoFactorAuthentication.php       # Middleware check 2FA
├── Models/
│   └── User.php                              # Updated dengan trait TwoFactorAuthenticatable
├── Services/
│   └── TwoFactorService.php                  # Business logic untuk 2FA
└── Traits/
    └── TwoFactorAuthenticatable.php          # Helper methods untuk User model

resources/
└── views/
    ├── auth/
    │   └── two-factor-challenge.blade.php    # Halaman input OTP saat login
    └── profile/
        ├── two-factor.blade.php              # Halaman pengaturan 2FA
        ├── two-factor-enable.blade.php       # Halaman aktivasi 2FA dengan QR
        └── two-factor-recovery-codes.blade.php # Halaman recovery codes

routes/
└── web.php                                   # Routes untuk 2FA
```

---

## 🔐 Security Features

### 1. **Encryption**
- Secret key di-encrypt menggunakan Laravel `Crypt::encryptString()`
- Recovery codes disimpan dalam format JSON

### 2. **Rate Limiting**
- Maksimal 3 percobaan input OTP gagal
- Setelah 3x gagal, user harus login ulang

### 3. **Session Management**
- 2FA verification tersimpan di session
- Session ter-regenerate setiap kali login
- Logout akan clear semua session 2FA

### 4. **Recovery Mechanism**
- 8 recovery codes unik (10 karakter)
- Setiap code hanya bisa digunakan 1x
- Recovery code bisa di-regenerate kapan saja

---

## 🚀 Cara Penggunaan

### A. Untuk User - Mengaktifkan 2FA

1. **Login ke aplikasi**
2. **Klik menu User** (pojok kanan atas)
3. **Pilih "Two-Factor Authentication"**
4. **Klik "Aktifkan Two-Factor Authentication"**
5. **Download Google Authenticator**:
   - Android: [Google Play Store](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)
   - iOS: [App Store](https://apps.apple.com/app/google-authenticator/id388497605)
6. **Scan QR Code** atau masukkan kode manual
7. **Input kode 6-digit** dari aplikasi untuk verifikasi
8. **Simpan Recovery Codes** yang ditampilkan (PENTING!)

### B. Untuk User - Login dengan 2FA

1. **Login normal** dengan username & password
2. **Sistem akan redirect** ke halaman "Verifikasi 2FA"
3. **Buka Google Authenticator** di HP
4. **Input kode 6-digit** yang ditampilkan
5. **Klik "Verifikasi"**
6. **Login berhasil** → redirect ke dashboard

### C. Untuk User - Nonaktifkan 2FA

1. **Masuk ke halaman** "Two-Factor Authentication"
2. **Klik "Nonaktifkan 2FA"** (tombol merah)
3. **Input password** untuk konfirmasi
4. **Input kode OTP** atau recovery code
5. **2FA dinonaktifkan**

### D. Menggunakan Recovery Code

Jika kehilangan akses ke Google Authenticator:
1. Di halaman "Verifikasi 2FA"
2. **Input recovery code** (10 karakter) bukan OTP
3. Recovery code tersebut akan terhapus setelah digunakan
4. Segera regenerate recovery codes baru

---

## 🛠️ Konfigurasi

### Routes

```php
// Guest routes (saat belum login/sedang verify 2FA)
Route::middleware('guest')->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorController::class, 'showChallenge'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorController::class, 'verifyChallenge'])
        ->name('two-factor.verify');
});

// Authenticated routes dengan 2FA check
Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::prefix('profile/two-factor')->name('two-factor.')->group(function () {
        Route::get('/', [TwoFactorController::class, 'index'])->name('index');
        Route::get('/enable', [TwoFactorController::class, 'enable'])->name('enable');
        Route::post('/confirm', [TwoFactorController::class, 'confirm'])->name('confirm');
        Route::get('/recovery-codes', [TwoFactorController::class, 'showRecoveryCodes'])
            ->name('recovery-codes');
        Route::post('/regenerate-codes', [TwoFactorController::class, 'regenerateRecoveryCodes'])
            ->name('regenerate-codes');
        Route::delete('/disable', [TwoFactorController::class, 'disable'])->name('disable');
    });
});
```

### Middleware Registration

File: `app/Http/Kernel.php`

```php
protected $middlewareAliases = [
    // ... existing middleware
    'two-factor' => \App\Http\Middleware\TwoFactorAuthentication::class,
];
```

### Menambahkan Middleware ke Route

Untuk mengamankan route dengan 2FA, tambahkan middleware `two-factor`:

```php
// Contoh: Dashboard dengan 2FA check
Route::prefix('dashboard')->middleware(['auth', 'two-factor'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
})->name('dashboard');
```

---

## 📊 Flow Diagram

### Login Flow dengan 2FA

```
User Input Credentials
        ↓
    Valid? → NO → Show Error
        ↓ YES
    User has 2FA enabled?
        ↓ YES              ↓ NO
    Redirect to       Login Success
    2FA Challenge     (Normal Flow)
        ↓
    Input OTP/Recovery Code
        ↓
    Valid? → NO → Failed Attempts++
        ↓ YES          ↓ (>3 attempts)
    Login Success   Force Logout
```

### Activation Flow

```
User → Settings → Enable 2FA
        ↓
Generate Secret Key + QR Code
        ↓
User Scan QR / Input Manual
        ↓
User Input OTP for Verification
        ↓
    Valid? → NO → Show Error
        ↓ YES
Save Encrypted Secret + Generate Recovery Codes
        ↓
Show Recovery Codes (Download/Print/Copy)
        ↓
2FA Enabled ✅
```

---

## 🧪 Testing

### Test Case 1: Aktivasi 2FA

```php
// Manual Testing Steps:
1. Login sebagai user test
2. Akses: /profile/two-factor
3. Klik "Aktifkan Two-Factor Authentication"
4. Scan QR code dengan Google Authenticator
5. Input kode OTP
6. Verifikasi bahwa 2FA aktif
7. Simpan recovery codes
```

### Test Case 2: Login dengan 2FA

```php
// Manual Testing Steps:
1. Logout
2. Login dengan username & password
3. Verifikasi redirect ke /two-factor-challenge
4. Input kode OTP yang benar
5. Verifikasi login berhasil
```

### Test Case 3: Recovery Code

```php
// Manual Testing Steps:
1. Logout
2. Login dengan username & password
3. Di halaman 2FA, input recovery code (bukan OTP)
4. Verifikasi login berhasil
5. Verifikasi recovery code terhapus dari database
```

### Test Case 4: Nonaktifkan 2FA

```php
// Manual Testing Steps:
1. Akses /profile/two-factor
2. Klik "Nonaktifkan 2FA"
3. Input password + OTP
4. Verifikasi 2FA berhasil dinonaktifkan
5. Logout dan login lagi
6. Verifikasi tidak ada 2FA challenge
```

---

## 🔧 Troubleshooting

### Problem 1: QR Code tidak muncul

**Solusi:**
- Pastikan package `bacon/bacon-qr-code` terinstall
- Clear cache: `php artisan cache:clear`
- Check browser console untuk error

### Problem 2: OTP selalu invalid

**Kemungkinan Penyebab:**
- Waktu server tidak sinkron
- Timezone tidak match

**Solusi:**
```bash
# Cek timezone server
date
# Atau set timezone di config/app.php
'timezone' => 'Asia/Jakarta',
```

### Problem 3: User terkunci setelah 3x failed attempts

**Solusi:**
- User harus login ulang dari awal
- Atau gunakan recovery code

### Problem 4: Secret key hilang/corrupt

**Solusi:**
- User harus disable 2FA (via admin jika perlu)
- Setup ulang 2FA dari awal

**Admin dapat disable manual via database:**
```sql
UPDATE user 
SET two_factor_enabled = 0, 
    two_factor_secret = NULL, 
    two_factor_recovery_codes = NULL 
WHERE id = [user_id];
```

---

## 👨‍💼 Admin Management

### Melihat User dengan 2FA Aktif

```sql
SELECT id, username, two_factor_enabled, two_factor_confirmed_at 
FROM user 
WHERE two_factor_enabled = 1;
```

### Force Disable 2FA untuk User

```sql
UPDATE user 
SET two_factor_enabled = 0,
    two_factor_secret = NULL,
    two_factor_recovery_codes = NULL,
    two_factor_confirmed_at = NULL
WHERE id = [user_id];
```

### Statistik 2FA Usage

```sql
-- Total user dengan 2FA aktif
SELECT COUNT(*) as total_2fa_users 
FROM user 
WHERE two_factor_enabled = 1;

-- Persentase adopsi
SELECT 
    (COUNT(CASE WHEN two_factor_enabled = 1 THEN 1 END) * 100.0 / COUNT(*)) as percentage
FROM user;
```

---

## 🔄 Update & Maintenance

### Update Recovery Codes

User dapat regenerate recovery codes kapan saja:
1. Akses `/profile/two-factor/recovery-codes`
2. Klik "Regenerate Recovery Codes"
3. Input password untuk konfirmasi
4. Simpan recovery codes baru

### Backup Considerations

**Yang perlu di-backup:**
- Tabel `user` (khususnya kolom 2FA)
- Session data (jika menggunakan database session)

**JANGAN backup:**
- Secret keys dalam plain text
- Recovery codes tanpa encryption

---

## 📱 Mobile App Recommendations

### Google Authenticator
- ✅ **Recommended** (Official Google)
- Gratis
- Cross-platform

### Alternatif Lain
- **Authy** - Support cloud backup
- **Microsoft Authenticator** - Integrasi bagus dengan Microsoft account
- **LastPass Authenticator** - Support biometric

---

## 🎨 Customization

### Mengubah Jumlah Recovery Codes

Edit `app/Services/TwoFactorService.php`:

```php
public function generateRecoveryCodes(): array
{
    $recoveryCodes = [];
    
    // Ubah angka 8 menjadi jumlah yang diinginkan
    for ($i = 0; $i < 8; $i++) {
        $recoveryCodes[] = strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
    }

    return $recoveryCodes;
}
```

### Mengubah Format QR Code

Edit `app/Services/TwoFactorService.php`:

```php
public function getQRCodeSvg(User $user, string $secret): string
{
    $appName = config('app.name', 'ERM Sulaiman'); // Ubah nama app
    
    // Ubah size QR code (default: 200)
    $renderer = new ImageRenderer(
        new RendererStyle(300), // Ubah ukuran di sini
        new SvgImageBackEnd()
    );
    
    // ...
}
```

---

## 📝 Best Practices

1. ✅ **Backup Recovery Codes** - Simpan di tempat aman (password manager, safe, dll)
2. ✅ **Regular Regenerate** - Regenerate recovery codes setiap 6 bulan
3. ✅ **Test Regularly** - Test login dengan 2FA secara berkala
4. ✅ **Educate Users** - Berikan training tentang pentingnya 2FA
5. ✅ **Monitor Usage** - Track adopsi 2FA di organisasi
6. ⚠️ **JANGAN Share** - Jangan pernah share QR code atau secret key
7. ⚠️ **JANGAN Screenshot** - Hindari screenshot QR code di device yang tidak aman

---

## 🆘 Support & Contact

Untuk bantuan implementasi atau troubleshooting:
- Dokumentasi Laravel: https://laravel.com/docs
- Google2FA Package: https://github.com/antonioribeiro/google2fa
- Google Authenticator Guide: https://support.google.com/accounts/answer/1066447

---

## ✅ Checklist Implementasi

- [x] Install packages
- [x] Create migration
- [x] Create service class
- [x] Create controller
- [x] Create middleware
- [x] Update User model
- [x] Create views
- [x] Update routes
- [x] Update login controller
- [x] Add menu item
- [ ] **Run migration** (`php artisan migrate`)
- [ ] Test aktivasi 2FA
- [ ] Test login dengan 2FA
- [ ] Test recovery codes
- [ ] Test nonaktifkan 2FA

---

## 📅 Changelog

### Version 1.0.0 (22 Januari 2026)
- ✅ Initial implementation
- ✅ QR Code generation
- ✅ Recovery codes system
- ✅ Rate limiting
- ✅ Complete UI/UX

---

**Implementasi 2FA Completed!** 🎉

Silakan jalankan migration dan mulai testing fitur 2FA.
