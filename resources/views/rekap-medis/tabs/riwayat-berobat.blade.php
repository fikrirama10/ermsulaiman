{{-- Riwayat Berobat Tab Content --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-time fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            Riwayat Berobat Pasien
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tbl_histori" class="table table-rounded table-striped border gy-7 gs-7">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th>Tgl. Kunjungan</th>
                        <th>Poliklinik</th>
                        <th>Dokter</th>
                        <th>Diagnosa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($riwayat_berobat) && count($riwayat_berobat) > 0)
                        @foreach ($riwayat_berobat as $rb)
                            <tr>
                                <td>{{ $rb->created_at }}</td>
                                <td>{{ $rb->rawat?->poli?->poli }}</td>
                                <td>{{ $rb->rawat?->dokter?->nama_dokter }}</td>
                                <td>
                                    @php
                                        $rekap_resume = App\Models\RekapMedis\DetailRekapMedis::where('idrekapmedis', $rb->id)->first();
                                    @endphp
                                    {{ $rekap_resume?->diagnosa ?? '-' }}
                                </td>
                                <td>
                                    <button type="button" onclick="modalHasil({{ $rb->id }})" class="btn btn-success btn-sm">
                                        <i class="ki-duotone ki-eye fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        Lihat
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Belum ada riwayat berobat</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
