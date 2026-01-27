@extends('layouts.index')

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3>Laporan BOR LOS TOI Per Ruangan</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-solid w-150px me-2"
                        value="{{ date('Y-m-01') }}" />
                    <span class="fs-7 me-2">s/d</span>
                    <input type="date" name="end_date" id="end_date"
                        class="form-control form-control-solid w-150px me-2" value="{{ date('Y-m-d') }}" />
                    <button type="button" class="btn btn-primary" id="btn-filter">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded" id="table-report">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800 px-7">
                            <th>Ruangan</th>
                            <th>TT (Bed)</th>
                            <th>HP (Hari Perawatan)</th>
                            <th>Pasien Keluar</th>
                            <th>BOR (%)</th>
                            <th>AVLOS (Hari)</th>
                            <th>TOI (Hari)</th>
                            <th>BTO (Kali)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            loadData();

            $('#btn-filter').click(function() {
                loadData();
            });

            function loadData() {
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();

                if (!startDate || !endDate) {
                    alert('Harap pilih periode tanggal!');
                    return;
                }

                $.ajax({
                    url: "{{ route('laporan.data_bor_los_toi') }}",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    beforeSend: function() {
                        $('#table-report tbody').html(
                            '<tr><td colspan="8" class="text-center">Loading...</td></tr>');
                    },
                    success: function(response) {
                        let rows = '';
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, item) {
                                rows += `
                                <tr>
                                    <td>${item.ruangan}</td>
                                    <td>${item.bed_count}</td>
                                    <td>${item.hp}</td>
                                    <td>${item.pasien_keluar}</td>
                                    <td>${item.bor}%</td>
                                    <td>${item.alos}</td>
                                    <td>${item.toi}</td>
                                    <td>${item.bto}</td>
                                </tr>
                            `;
                            });
                        } else {
                            rows =
                                '<tr><td colspan="8" class="text-center">Tidak ada data untuk periode ini</td></tr>';
                        }
                        $('#table-report tbody').html(rows);
                    },
                    error: function(xhr) {
                        $('#table-report tbody').html(
                            '<tr><td colspan="8" class="text-center text-danger">Terjadi kesalahan saat memuat data</td></tr>'
                        );
                        console.error(xhr);
                    }
                });
            }
        });
    </script>
@endsection
