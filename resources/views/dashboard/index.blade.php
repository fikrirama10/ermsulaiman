@extends('layouts.index')

@section('css')
    <style>
        /* Dashboard Container */
        .executive-dashboard {
            background: #f8f9fa;
            min-height: calc(100vh - 100px);
        }

        /* Statistics Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-card.primary {
            border-left-color: #3699FF;
        }

        .stat-card.success {
            border-left-color: #1BC5BD;
        }

        .stat-card.warning {
            border-left-color: #FFA800;
        }

        .stat-card.danger {
            border-left-color: #F64E60;
        }

        .stat-card.info {
            border-left-color: #8950FC;
        }

        .stat-card.purple {
            border-left-color: #6f42c1;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: rgba(54, 153, 255, 0.1);
            color: #3699FF;
        }

        .stat-icon.success {
            background: rgba(27, 197, 189, 0.1);
            color: #1BC5BD;
        }

        .stat-icon.warning {
            background: rgba(255, 168, 0, 0.1);
            color: #FFA800;
        }

        .stat-icon.danger {
            background: rgba(246, 78, 96, 0.1);
            color: #F64E60;
        }

        .stat-icon.info {
            background: rgba(137, 80, 252, 0.1);
            color: #8950FC;
        }

        .stat-icon.purple {
            background: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #181C32;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #7E8299;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Chart Cards */
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #181C32;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f1f2;
        }

        /* Bed Availability Grid */
        .bed-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .bed-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .bed-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .bed-card.available {
            border-color: #1BC5BD;
        }

        .bed-card.full {
            border-color: #F64E60;
            background: #fff5f5;
        }

        .bed-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: #181C32;
        }

        .bed-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
        }

        .bed-stat {
            text-align: center;
        }

        .bed-stat-value {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .bed-stat-label {
            font-size: 0.7rem;
            color: #7E8299;
            text-transform: uppercase;
        }

        /* Loading State */
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
            height: 20px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Table Styles */
        .diagnosa-table {
            width: 100%;
        }

        .diagnosa-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.875rem;
            color: #181C32;
            padding: 0.75rem;
            border-bottom: 2px solid #e4e6ef;
        }

        .diagnosa-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e4e6ef;
            font-size: 0.875rem;
        }

        .diagnosa-table tr:hover {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.5rem;
            }

            .bed-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        /* Quick Actions */
        .quick-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 100;
        }

        .quick-action-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        /* Section Spacing */
        .dashboard-section {
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid executive-dashboard">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard Eksekutif
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <span class="text-muted">Rumah Sakit AU dr. Sulaiman</span>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">

                    <button class="btn btn-sm btn-light-primary" onclick="refreshDashboard()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- Statistics Cards -->
                <div class="dashboard-section">
                    <div class="row g-4">
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card primary">
                                <div class="stat-icon primary">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="stat-value" id="total-pasien">
                                    <div class="loading-skeleton" style="width: 80px;"></div>
                                </div>
                                <div class="stat-label">Total Pasien</div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card success">
                                <div class="stat-icon success">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="stat-value" id="kunjungan-hari-ini">
                                    <div class="loading-skeleton" style="width: 60px;"></div>
                                </div>
                                <div class="stat-label">Kunjungan Hari Ini</div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card warning">
                                <div class="stat-icon warning">
                                    <i class="bi bi-hospital"></i>
                                </div>
                                <div class="stat-value" id="rawat-inap-aktif">
                                    <div class="loading-skeleton" style="width: 60px;"></div>
                                </div>
                                <div class="stat-label">Rawat Inap Aktif</div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card danger">
                                <div class="stat-icon danger">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <div class="stat-value" id="pasien-baru">
                                    <div class="loading-skeleton" style="width: 60px;"></div>
                                </div>
                                <div class="stat-label">Pasien Baru Bulan Ini</div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card info">
                                <div class="stat-icon info">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div class="stat-value" id="kunjungan-bulan-ini">
                                    <div class="loading-skeleton" style="width: 60px;"></div>
                                </div>
                                <div class="stat-label">Kunjungan Bulan Ini</div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6">
                            <div class="stat-card purple">
                                <div class="stat-icon purple">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </div>
                                <div class="stat-value" id="rawat-jalan-hari-ini">
                                    <div class="loading-skeleton" style="width: 60px;"></div>
                                </div>
                                <div class="stat-label">Rawat Jalan Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 1 -->
                <div class="dashboard-section">
                    <div class="row g-4">
                        <div class="col-xl-8">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-bar-chart-line text-primary me-2"></i>
                                    Kunjungan Bulanan {{ date('Y') }}
                                </h3>
                                <canvas id="chartKunjunganBulanan" height="80"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-pie-chart text-success me-2"></i>
                                    Demografi Gender
                                </h3>
                                <canvas id="chartGender" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="dashboard-section">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-hospital text-info me-2"></i>
                                    Top 10 Poli (Kunjungan Terbanyak)
                                </h3>
                                <canvas id="chartPoli" height="120"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-people text-warning me-2"></i>
                                    Demografi Usia
                                </h3>
                                <canvas id="chartUsia" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 3 -->
                <div class="dashboard-section">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-credit-card text-danger me-2"></i>
                                    Distribusi Cara Bayar
                                </h3>
                                <canvas id="chartCaraBayar" height="120"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="chart-card">
                                <h3 class="chart-title">
                                    <i class="bi bi-clipboard-data text-purple me-2"></i>
                                    Top 10 Diagnosa Bulan Ini
                                </h3>
                                <div class="table-responsive">
                                    <table class="diagnosa-table">
                                        <thead>
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Kode ICD-X</th>
                                                <th width="100" class="text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody id="diagnosa-tbody">
                                            <tr>
                                                <td colspan="3" class="text-center">
                                                    <div class="loading-skeleton mx-auto" style="width: 200px;"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bed Availability -->
                <div class="dashboard-section">
                    <div class="chart-card">
                        <h3 class="chart-title">
                            <i class="bi bi-hospital text-primary me-2"></i>
                            Ketersediaan Tempat Tidur Rawat Inap
                        </h3>
                        <div class="bed-grid" id="bed-grid">
                            <!-- Loading skeleton -->
                            <div class="bed-card">
                                <div class="loading-skeleton mb-2"></div>
                                <div class="loading-skeleton" style="height: 40px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end::Content-->
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions d-none d-lg-block">
        <a href="{{ route('pendaftaran.create') }}" class="quick-action-btn btn btn-primary" title="Pendaftaran Baru">
            <i class="bi bi-person-plus fs-3"></i>
        </a>
        <a href="{{ route('laporan.index') }}" class="quick-action-btn btn btn-info" title="Laporan">
            <i class="bi bi-file-earmark-bar-graph fs-3"></i>
        </a>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let charts = {};

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const dateStr = now.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            $('#current-time').text(`${dateStr} ${timeStr}`);
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Load Statistics
        function loadStatistics() {
            $.ajax({
                url: '{{ route('dashboard.statistics') }}',
                method: 'GET',
                success: function(response) {
                    $('#total-pasien').text(response.total_pasien.toLocaleString('id-ID'));
                    $('#kunjungan-hari-ini').text(response.kunjungan_hari_ini.toLocaleString('id-ID'));
                    $('#rawat-inap-aktif').text(response.rawat_inap_aktif.toLocaleString('id-ID'));
                    $('#pasien-baru').text(response.pasien_baru_bulan_ini.toLocaleString('id-ID'));
                    $('#kunjungan-bulan-ini').text(response.kunjungan_bulan_ini.toLocaleString('id-ID'));
                    $('#rawat-jalan-hari-ini').text(response.rawat_jalan_hari_ini.toLocaleString('id-ID'));
                }
            });
        }

        // Load Kunjungan Bulanan Chart
        function loadKunjunganBulanan() {
            $.ajax({
                url: '{{ route('dashboard.kunjungan-bulanan') }}',
                method: 'GET',
                success: function(response) {
                    const ctx = document.getElementById('chartKunjunganBulanan').getContext('2d');
                    if (charts.kunjunganBulanan) charts.kunjunganBulanan.destroy();

                    charts.kunjunganBulanan = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Kunjungan',
                                data: response.data,
                                borderColor: '#3699FF',
                                backgroundColor: 'rgba(54, 153, 255, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 13
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Load Gender Chart
        function loadGenderChart() {
            $.ajax({
                url: '{{ route('dashboard.demografi-gender') }}',
                method: 'GET',
                success: function(response) {
                    const ctx = document.getElementById('chartGender').getContext('2d');
                    if (charts.gender) charts.gender.destroy();

                    charts.gender = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                data: response.data,
                                backgroundColor: ['#3699FF', '#F64E60'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 12
                                        },
                                        padding: 15
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Load Poli Chart
        function loadPoliChart() {
            $.ajax({
                url: '{{ route('dashboard.kunjungan-poli') }}',
                method: 'GET',
                success: function(response) {
                    const ctx = document.getElementById('chartPoli').getContext('2d');
                    if (charts.poli) charts.poli.destroy();

                    charts.poli = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Kunjungan',
                                data: response.data,
                                backgroundColor: '#1BC5BD',
                                borderRadius: 6
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                y: {
                                    ticks: {
                                        font: {
                                            size: 10
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Load Usia Chart
        function loadUsiaChart() {
            $.ajax({
                url: '{{ route('dashboard.demografi-usia') }}',
                method: 'GET',
                success: function(response) {
                    const ctx = document.getElementById('chartUsia').getContext('2d');
                    if (charts.usia) charts.usia.destroy();

                    charts.usia = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Jumlah Pasien',
                                data: response.data,
                                backgroundColor: ['#FFA800', '#3699FF', '#1BC5BD', '#F64E60',
                                    '#8950FC'
                                ],
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 10
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Load Cara Bayar Chart
        function loadCaraBayarChart() {
            $.ajax({
                url: '{{ route('dashboard.cara-bayar') }}',
                method: 'GET',
                success: function(response) {
                    const ctx = document.getElementById('chartCaraBayar').getContext('2d');
                    if (charts.caraBayar) charts.caraBayar.destroy();

                    const colors = ['#3699FF', '#1BC5BD', '#FFA800', '#F64E60', '#8950FC', '#6f42c1'];

                    charts.caraBayar = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                data: response.data,
                                backgroundColor: colors,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                }
                            }
                        }
                    });
                }
            });
        }

        // Load Top Diagnosa
        function loadTopDiagnosa() {
            $.ajax({
                url: '{{ route('dashboard.top-diagnosa') }}',
                method: 'GET',
                success: function(response) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>';
                    } else {
                        response.data.forEach((item, index) => {
                            html += `
                            <tr>
                                <td class="fw-bold text-muted">${index + 1}</td>
                                <td class="fw-semibold">${item.icdx}</td>
                                <td class="text-end">
                                    <span class="badge badge-light-primary">${item.total}</span>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $('#diagnosa-tbody').html(html);
                }
            });
        }

        // Load Bed Availability
        function loadBedAvailability() {
            $.ajax({
                url: '{{ route('dashboard.bed-availability') }}',
                method: 'GET',
                success: function(response) {
                    let html = '';
                    response.data.forEach(bed => {
                        const statusClass = bed.status === 'Tersedia' ? 'available' : 'full';
                        const statusBadge = bed.status === 'Tersedia' ? 'success' : 'danger';

                        html += `
                        <div class="bed-card ${statusClass}">
                            <div class="bed-name">${bed.nama_ruangan}</div>
                            <span class="badge badge-light-info badge-sm mb-2">${bed.kelas}</span>
                            <div class="bed-stats">
                                <div class="bed-stat">
                                    <div class="bed-stat-value text-success">${bed.kosong}</div>
                                    <div class="bed-stat-label">Tersedia</div>
                                </div>
                                <div class="bed-stat">
                                    <div class="bed-stat-value text-danger">${bed.terisi}</div>
                                    <div class="bed-stat-label">Terisi</div>
                                </div>
                                <div class="bed-stat">
                                    <div class="bed-stat-value text-primary">${bed.total}</div>
                                    <div class="bed-stat-label">Total</div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: ${bed.percentage}%"></div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="badge badge-${statusBadge} badge-sm">${bed.status}</span>
                            </div>
                        </div>
                    `;
                    });
                    $('#bed-grid').html(html);
                }
            });
        }

        // Refresh Dashboard
        function refreshDashboard() {
            loadStatistics();
            loadKunjunganBulanan();
            loadGenderChart();
            loadPoliChart();
            loadUsiaChart();
            loadCaraBayarChart();
            loadTopDiagnosa();
            loadBedAvailability();

            // Show toast notification
            toastr.success('Dashboard berhasil diperbarui', 'Refresh');
        }

        // Initialize Dashboard
        $(document).ready(function() {
            refreshDashboard();

            // Auto refresh every 5 minutes
            setInterval(refreshDashboard, 300000);
        });
    </script>
@endsection
