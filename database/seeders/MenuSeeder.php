<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Clear existing data
        DB::table('role_menu_permissions')->delete();
        DB::table('menus')->delete();

        // Root/Parent Menus
        $menus = [
            // 1. Dashboard
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'url' => '/dashboard',
                'icon' => 'ki-outline ki-element-11',
                'parent_id' => null,
                'order_index' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 2. Monitoring Integrasi
            [
                'name' => 'Monitoring Integrasi',
                'slug' => 'monitoring-integrasi',
                'url' => '/monitoring/integrasi',
                'icon' => 'ki-outline ki-chart-line-star',
                'parent_id' => null,
                'order_index' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 3. Pendaftaran
            [
                'name' => 'Pendaftaran',
                'slug' => 'pendaftaran',
                'url' => '/pendaftaran',
                'icon' => 'ki-outline ki-calendar-add',
                'parent_id' => null,
                'order_index' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 4. Billing & Journey
            [
                'name' => 'Billing & Journey',
                'slug' => 'billing',
                'url' => '/billing',
                'icon' => 'ki-outline ki-bill',
                'parent_id' => null,
                'order_index' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 5. Data Master (Parent)
            [
                'name' => 'Data Master',
                'slug' => 'data-master',
                'url' => '#',
                'icon' => 'ki-outline ki-data',
                'parent_id' => null,
                'order_index' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 6. Management Dokter (Parent)
            [
                'name' => 'Management Dokter',
                'slug' => 'management-dokter',
                'url' => '#',
                'icon' => 'ki-outline ki-user-square',
                'parent_id' => null,
                'order_index' => 6,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 7. Pasien (Parent)
            [
                'name' => 'Pasien',
                'slug' => 'pasien',
                'url' => '#',
                'icon' => 'ki-outline ki-profile-user',
                'parent_id' => null,
                'order_index' => 7,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // 8. Farmasi (Parent)
            [
                'name' => 'Farmasi',
                'slug' => 'farmasi',
                'url' => '#',
                'icon' => 'ki-outline ki-office-bag',
                'parent_id' => null,
                'order_index' => 8,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('menus')->insert($menus);

        // Get parent IDs
        $datamaster_id = DB::table('menus')->where('slug', 'data-master')->value('id');
        $dokter_id = DB::table('menus')->where('slug', 'management-dokter')->value('id');
        $pasien_id = DB::table('menus')->where('slug', 'pasien')->value('id');
        $farmasi_id = DB::table('menus')->where('slug', 'farmasi')->value('id');

        // Submenu Data Master
        $datamaster_submenus = [
            [
                'name' => 'Ruangan',
                'slug' => 'data-master-ruangan',
                'url' => '/data-master/ruangan',
                'icon' => null,
                'parent_id' => $datamaster_id,
                'order_index' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Submenu Management Dokter
        $dokter_submenus = [
            [
                'name' => 'Data Dokter',
                'slug' => 'dokter-data',
                'url' => '/dokter',
                'icon' => null,
                'parent_id' => $dokter_id,
                'order_index' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Submenu Pasien (Multiple conditional submenus)
        $pasien_submenus = [
            // Lab
            [
                'name' => 'Antrian Lab',
                'slug' => 'pasien-antrian-lab',
                'url' => '/penunjang/antrian/Lab',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Pemeriksaan Lab',
                'slug' => 'pasien-pemeriksaan-lab',
                'url' => '/laboratorium/list-pemeriksaan',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'List Pasien Lab',
                'slug' => 'pasien-list-lab',
                'url' => '/laboratorium/list-pasien/Lab',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Fisio Terapi
            [
                'name' => 'Fisio Terapi',
                'slug' => 'pasien-fisio-terapi',
                'url' => '/penunjang/antrian/Fisio',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Radiologi
            [
                'name' => 'Antrian Radiologi',
                'slug' => 'pasien-antrian-radiologi',
                'url' => '/radiologi/antrian',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'List Hasil Radiologi',
                'slug' => 'pasien-hasil-radiologi',
                'url' => '/radiologi',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 6,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Farmasi - List Pasien Rawat
            [
                'name' => 'List Pasien Rawat',
                'slug' => 'pasien-list-pasien-rawat',
                'url' => '/farmasi/list-pasien-rawat',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 7,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Rekam Medis - List Pasien
            [
                'name' => 'List Pasien',
                'slug' => 'pasien-list-pasien',
                'url' => '/pasien',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 8,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Perawat Ruangan
            [
                'name' => 'Keperawatan',
                'slug' => 'pasien-keperawatan',
                'url' => '/rawat-inap',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 9,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Pasien Pulang',
                'slug' => 'pasien-pulang',
                'url' => '/rawat-inap-pulang',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Dokter/Poli
            [
                'name' => 'List Pasien Poli',
                'slug' => 'pasien-list-poli',
                'url' => '/rawat-jalan/poli',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 11,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Semua Pasien',
                'slug' => 'pasien-semua',
                'url' => '/rawat-jalan/poli-semua',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 12,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Pasien Perawatan',
                'slug' => 'pasien-perawatan',
                'url' => '/rawat-inap',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 13,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Pasien Raber',
                'slug' => 'pasien-raber',
                'url' => '/rawat-bersama',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 14,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Bidan
            [
                'name' => 'Kebidanan',
                'slug' => 'pasien-kebidanan',
                'url' => '/rawat-inap',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 15,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Ruang OK
            [
                'name' => 'Antrian Operasi',
                'slug' => 'pasien-antrian-operasi',
                'url' => '/pasien/operasi',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 16,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Template Operasi',
                'slug' => 'pasien-template-operasi',
                'url' => '/pasien/template',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 17,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Template Anastesi',
                'slug' => 'pasien-template-anastesi',
                'url' => '/pasien/template/template-anastesi',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 18,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Master BHP',
                'slug' => 'pasien-master-bhp',
                'url' => '/pasien/bhp',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 19,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            // Gizi
            [
                'name' => 'Gizi Ranap',
                'slug' => 'pasien-gizi-ranap',
                'url' => '/pasien/gizi/2',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Gizi Rajal',
                'slug' => 'pasien-gizi-rajal',
                'url' => '/pasien/gizi/1',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 21,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Gizi UGD',
                'slug' => 'pasien-gizi-ugd',
                'url' => '/pasien/gizi/3',
                'icon' => null,
                'parent_id' => $pasien_id,
                'order_index' => 22,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Submenu Farmasi
        $farmasi_submenus = [
            [
                'name' => 'Antrian Resep',
                'slug' => 'farmasi-antrian-resep',
                'url' => '/farmasi/antrian-resep',
                'icon' => null,
                'parent_id' => $farmasi_id,
                'order_index' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'List Resep',
                'slug' => 'farmasi-list-resep',
                'url' => '/farmasi/list-resep',
                'icon' => null,
                'parent_id' => $farmasi_id,
                'order_index' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Obat Obatan',
                'slug' => 'farmasi-obat-obatan',
                'url' => '/farmasi/list-obat',
                'icon' => null,
                'parent_id' => $farmasi_id,
                'order_index' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Penjualan Obat',
                'slug' => 'farmasi-penjualan-obat',
                'url' => '/penjualan-obat',
                'icon' => null,
                'parent_id' => $farmasi_id,
                'order_index' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        // Insert all submenus
        DB::table('menus')->insert(array_merge(
            $datamaster_submenus,
            $dokter_submenus,
            $pasien_submenus,
            $farmasi_submenus
        ));

        echo "Menu seeder completed! Total menus: " . (count($menus) + count($datamaster_submenus) + count($dokter_submenus) + count($pasien_submenus) + count($farmasi_submenus)) . "\n";
    }
}
