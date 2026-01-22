{{-- Tindak Lanjut Tab Content --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-calendar-tick fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
            Rencana Tindak Lanjut
        </h3>
    </div>
    <div class="card-body">
        @if (!$tindak_lanjut)
            <div class="alert alert-warning">
                <i class="ki-duotone ki-information-5 fs-2x me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Belum ada rencana tindak lanjut
            </div>
            <a href="{{ route('tindak-lanjut.index', ['idrawat' => $rawat->id]) }}" class="btn btn-info">
                <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                Tambah Tindak Lanjut
            </a>
        @else
            <table class="table table-striped table-row-bordered gy-3 gs-5 border rounded">
                <thead class="border">
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>Tindak Lanjut</th>
                        <th>Tujuan</th>
                        <th>Poli Tujuan</th>
                        <th>Tgl Tindak Lanjut</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $tindak_lanjut->tindak_lanjut }}</td>
                        <td>{{ $tindak_lanjut->tujuan_tindak_lanjut }}</td>
                        <td>{{ $tindak_lanjut->poli_rujuk }}</td>
                        <td>{{ $tindak_lanjut->tgl_tindak_lanjut }}</td>
                        <td>
                            <a href="{{ route('tindak-lanjut.edit_tindak_lanjut', $tindak_lanjut->id) }}" class="btn btn-info btn-sm">
                                <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Edit
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>
