# Helper Menu Guide

## Overview

Helper Menu adalah menu navigasi cepat yang dapat ditempatkan di halaman manapun untuk memberikan akses cepat ke menu penting. CSS dan JavaScript sudah ditambahkan ke layout utama, sehingga Anda tinggal menambahkan HTML-nya di halaman yang diinginkan.

## Fitur

- **6 Posisi**: Top-left, top-right, bottom-left, bottom-right, middle-left, middle-right
- **Animasi Halus**: Slide-down dengan efek hover
- **Responsif**: Berfungsi di semua ukuran layar
- **Mudah Digunakan**: Cukup copy-paste HTML

## Instalasi

CSS dan JavaScript sudah ditambahkan ke `resources/views/layouts/index.blade.php`, jadi siap digunakan di semua halaman.

## Cara Menggunakan

### 1. Basic Usage

Copy dan paste kode HTML ini ke halaman Anda (di dalam `@section('content')`):

```blade
<div class="helper-menu position-top-right" id="helperMenu">
    <button class="helper-toggle" onclick="toggleHelperMenu()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <span>Menu</span>
    </button>
    <div class="helper-items">
        <!-- Menu items will go here -->
    </div>
</div>
```

**PENTING**: Pastikan menggunakan `id="helperMenu"` agar fungsi JavaScript berjalan dengan baik.

### 2. Mengganti Posisi

Ubah class `position-top-right` dengan posisi yang diinginkan:

```blade
<!-- Top Right (Default) -->
<div class="helper-menu position-top-right" id="helperMenu">

<!-- Top Left -->
<div class="helper-menu position-top-left" id="helperMenu">

<!-- Bottom Right -->
<div class="helper-menu position-bottom-right" id="helperMenu">

<!-- Bottom Left -->
<div class="helper-menu position-bottom-left" id="helperMenu">

<!-- Middle Right -->
<div class="helper-menu position-middle-right" id="helperMenu">

<!-- Middle Left -->
<div class="helper-menu position-middle-left" id="helperMenu">
```

### 3. Menambah Menu Items

Tambahkan menu items di dalam `<div class="helper-items">`:

```blade
<div class="helper-items">
    <!-- Category Label -->
    <div class="helper-label">Registrasi</div>

    <!-- Menu Item 1 -->
    <a href="{{ route('pasien.tambah-pasien') }}" class="helper-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="4"></circle>
            <line x1="20" y1="8" x2="20" y2="14"></line>
            <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
        <span>Pasien Baru</span>
    </a>

    <!-- Menu Item 2 -->
    <a href="{{ route('pasien.pendaftaran') }}" class="helper-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Tambah Kunjungan</span>
    </a>

    <!-- Divider -->
    <div class="helper-divider"></div>

    <!-- Another Category -->
    <div class="helper-label">Laporan</div>

    <!-- Menu Item 3 -->
    <a href="{{ route('laporan.index') }}" class="helper-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Laporan RM</span>
    </a>
</div>
```

### 4. Struktur HTML Lengkap

```blade
<div class="helper-menu [POSITION-CLASS]" id="helperMenu">
    <!-- Toggle Button -->
    <button class="helper-toggle" onclick="toggleHelperMenu()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <span>Menu</span>
    </button>

    <!-- Menu Items Container -->
    <div class="helper-items">
        <!-- Category (Optional) -->
        <div class="helper-label">Category Name</div>

        <!-- Menu Item -->
        <a href="[URL]" class="helper-item">
            [SVG ICON]
            <span>Menu Text</span>
        </a>

        <!-- Divider (Optional) -->
        <div class="helper-divider"></div>
    </div>
</div>
```

## Contoh Lengkap

```blade
@extends('layouts.index')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Helper Menu -->
        <div class="helper-menu position-top-right" id="helperMenu">
            <button class="helper-toggle" onclick="toggleHelperMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <span>Menu</span>
            </button>
            <div class="helper-items">
                <div class="helper-label">Registrasi</div>
                <a href="{{ route('pasien.tambah-pasien') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span>Pasien Baru</span>
                </a>
                <a href="{{ route('pasien.pendaftaran') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Tambah Kunjungan</span>
                </a>
                <div class="helper-divider"></div>
                <div class="helper-label">Laporan</div>
                <a href="{{ route('laporan.index') }}" class="helper-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Laporan RM</span>
                </a>
            </div>
        </div>

        <!-- Your page content here -->
    </div>
@endsection
```

## Daftar Posisi

| Class | Deskripsi |
|-------|-----------|
| `position-top-right` | Pojok kanan atas (default) |
| `position-top-left` | Pojok kiri atas |
| `position-bottom-right` | Pojok kanan bawah |
| `position-bottom-left` | Pojok kiri bawah |
| `position-middle-right` | Tengah sisi kanan |
| `position-middle-left` | Tengah sisi kiri |

## Struktur Menu Item

Setiap menu item memiliki struktur berikut:

```blade
<a href="[URL-LINK]" class="helper-item">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
        fill="none" stroke="currentColor" stroke-width="2">
        <!-- SVG paths here -->
    </svg>
    <span>Menu Text</span>
</a>
```

Komponen:
- **href**: Link tujuan (gunakan `route()` atau URL langsung)
- **helper-item**: Class wajib untuk styling
- **SVG**: Icon menu (gunakan Feather Icons, Heroicons, atau SVG lain)
- **span**: Teks menu

## Tips

1. **ID Harus Unik**: Pastikan menggunakan `id="helperMenu"` agar fungsi toggle berjalan
2. **Hanya Satu per Halaman**: Jangan menggunakan lebih dari satu helper menu di halaman yang sama
3. **Icon SVG**: Gunakan icon yang simpel dan konsisten (Feather Icons recommended)
4. **Group Related Items**: Gunakan category label dan divider untuk mengelompokkan menu
5. **Short Labels**: Gunakan teks yang singkat dan jelas

## Troubleshooting

**Menu tidak muncul?**
- Pastikan CSS dan JavaScript sudah ditambahkan ke layout
- Cek apakah HTML sudah diletakkan di dalam `@section('content')`

**Toggle tidak berfungsi?**
- Pastikan menggunakan `id="helperMenu"`
- Pastikan tidak ada JavaScript error di console
- Cek apakah fungsi `toggleHelperMenu()` sudah ada

**Posisi tidak sesuai?**
- Pastikan class posisi sudah benar (contoh: `position-top-right`)
- Cek apakah ada CSS lain yang meng-overwrite

## Customization

Jika ingin mengubah styling, tambahkan CSS custom di halaman Anda:

```blade
@section('css')
<style>
    .helper-menu {
        /* Custom styles */
        background-color: #your-color;
        border-radius: 20px;
    }

    .helper-item:hover {
        /* Custom hover effect */
        background-color: #your-hover-color;
    }
</style>
@endsection
```
