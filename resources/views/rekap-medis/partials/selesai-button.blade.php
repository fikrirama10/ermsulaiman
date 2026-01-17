<form action="{{ route('rekap-medis-selesai', $resume_medis->id) }}" id='frmSelesai' method="POST" class="d-inline">
    @csrf

    @if ($resume_medis->perawat != 1)
        @if (in_array(auth()->user()->idpriv, [14, 18, 29]))
            <input type="hidden" name="jenis" value="perawat">
            <button type="submit" class="btn btn-sm btn-light-success">
                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                Selesai (Perawat)
            </button>
        @endif
    @endif

    @if ($resume_medis->bpjs != 1)
        @if (auth()->user()->idpriv == 20)
            <input type="hidden" name="jenis" value="bpjs">
            <button type="submit" class="btn btn-sm btn-light-success">
                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                Selesai (BPJS)
            </button>
        @endif
    @endif

    @if ($resume_medis->dokter != 1)
        @if (auth()->user()->idpriv == 7)
            <input type="hidden" name="jenis" value="dokter">
            <button type="submit" class="btn btn-sm btn-light-success">
                <i class="ki-duotone ki-check fs-5"><span class="path1"></span><span class="path2"></span></i>
                Selesai (Dokter)
            </button>
        @endif
    @endif
</form>
