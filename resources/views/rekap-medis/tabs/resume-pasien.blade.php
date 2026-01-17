{{-- Resume Pasien Tab Content --}}
<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ki-duotone ki-document fs-2 text-primary me-2"><span class="path1"></span><span
                    class="path2"></span></i>
            Resume Medis Pasien
        </h3>
    </div>
    <div class="card-body">
        @if (!$resume_medis)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4"><span class="path1"></span><span
                        class="path2"></span><span class="path3"></span></i>
                <div>
                    <h4 class="mb-1 text-dark">Resume Belum Dibuat</h4>
                    <span>Silakan klik tombol di bawah untuk membuat resume medis</span>
                </div>
            </div>
            <form action="{{ route('post.resume-poli') }}" id="frmResume" method="post">
                @csrf
                <input type="hidden" name="idrawat" value="{{ $rawat->id }}">
                <input type="hidden" name="idkategori" value="{{ $rawat->idjenisrawat == 1 ? 1 : 3 }}">
                <input type="hidden" name="idpasien" value="{{ $pasien->id }}">
                <button type="submit" class="btn btn-warning">
                    <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                    Input Resume
                </button>
            </form>
        @else
            @if ($resume_detail)
                <div class="d-flex gap-2 mb-5">
                    <a class="btn btn-primary btn-sm" href="{{ route('detail-rekap-medis-cetak', $resume_detail->id) }}"
                        target="_blank">
                        <i class="ki-duotone ki-printer fs-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        Print
                    </a>
                    @php
                        $canEdit = false;
                        // Dokter dan Coder bisa edit kapanpun
                        if (auth()->user()->idpriv == 20 || auth()->user()->idpriv == 7) {
                            $canEdit = true;
                        }
                        // Perawat bisa edit jika belum selesai
                        if (in_array(auth()->user()->idpriv, [14, 18, 29]) && $resume_medis->perawat != 1) {
                            $canEdit = true;
                        }
                    @endphp
                    @if ($canEdit)
                        <a class="btn btn-light-success btn-sm"
                            href="{{ route('detail-rekap-medis-show', $resume_detail->id) }}">
                            <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span
                                    class="path2"></span></i>
                            Edit
                        </a>
                    @endif
                </div>

                <div class="separator separator-dashed my-5"></div>

                {{-- Accordion for Compact Display --}}
                <div class="accordion accordion-icon-toggle" id="kt_accordion_resume">

                    {{-- SOAP Data --}}
                    @if ($resume_detail->soap_data)
                        @php
                            $soapData = is_string($resume_detail->soap_data)
                                ? json_decode($resume_detail->soap_data, true)
                                : $resume_detail->soap_data;
                        @endphp
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="kt_accordion_1_header_1">
                                <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#kt_accordion_1_body_1" aria-expanded="false">
                                    <i class="ki-duotone ki-pulse fs-3 text-success me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    SOAP (Subjective, Objective, Assessment, Plan)
                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" data-bs-parent="#kt_accordion_resume">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="bg-light-primary rounded p-3">
                                                <span class="badge badge-primary badge-sm mb-2">S</span>
                                                <small class="d-block text-gray-700">{{ $soapData['subjective'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light-info rounded p-3">
                                                <span class="badge badge-info badge-sm mb-2">O</span>
                                                <small class="d-block text-gray-700">{{ $soapData['objective'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light-warning rounded p-3">
                                                <span class="badge badge-warning badge-sm mb-2">A</span>
                                                <small class="d-block text-gray-700">{{ $soapData['assessment'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light-success rounded p-3">
                                                <span class="badge badge-success badge-sm mb-2">P</span>
                                                <small class="d-block text-gray-700">{{ $soapData['plan'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Diagnosa & ICD --}}
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="kt_accordion_1_header_2">
                            <button class="accordion-button fs-5 fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#kt_accordion_1_body_2" aria-expanded="true">
                                <i class="ki-duotone ki-medical-folder fs-3 text-danger me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Diagnosa & Kode ICD
                            </button>
                        </h2>
                        <div id="kt_accordion_1_body_2" class="accordion-collapse collapse show" data-bs-parent="#kt_accordion_resume">
                            <div class="accordion-body">
                                @if ($resume_detail->diagnosa)
                                    <div class="mb-3">
                                        <label class="fw-bold text-gray-700 fs-7 mb-1">Diagnosa:</label>
                                        <div class="alert alert-primary py-2 px-3 mb-0 d-flex align-items-center">
                                            <i class="ki-duotone ki-shield-tick fs-2 text-primary me-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <span class="fw-bold fs-6">{{ $resume_detail->diagnosa }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($resume_detail->icdx)
                                    @php
                                        $icdxData = is_string($resume_detail->icdx)
                                            ? json_decode($resume_detail->icdx, true)
                                            : $resume_detail->icdx;
                                    @endphp
                                    <div class="mb-3">
                                        <label class="fw-bold text-gray-700 fs-7 mb-1">ICD-X (Diagnosa):</label>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gs-0 gy-2">
                                                <thead>
                                                    <tr class="fw-bold fs-7 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th>Diagnosa</th>
                                                        <th class="w-100px">Jenis</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($icdxData) && is_array($icdxData))
                                                        @foreach ($icdxData as $icdx)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge badge-light-primary fs-8">
                                                                        {{ $icdx['diagnosa_icdx'] ?? '-' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-sm {{ ($icdx['jenis_diagnosa'] ?? '') == 'P' ? 'badge-success' : 'badge-secondary' }}">
                                                                        {{ ($icdx['jenis_diagnosa'] ?? '') == 'P' ? 'Primer' : (($icdx['jenis_diagnosa'] ?? '') == 'S' ? 'Sekunder' : '-') }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                @if ($resume_detail->icd9)
                                    @php
                                        $icd9Data = is_string($resume_detail->icd9)
                                            ? json_decode($resume_detail->icd9, true)
                                            : $resume_detail->icd9;
                                    @endphp
                                    <div class="mb-0">
                                        <label class="fw-bold text-gray-700 fs-7 mb-1">ICD-9 (Prosedur):</label>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-row-bordered table-row-gray-300 align-middle gs-0 gy-2">
                                                <thead>
                                                    <tr class="fw-bold fs-7 text-gray-800 border-bottom-2 border-gray-200">
                                                        <th>Prosedur</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($icd9Data))
                                                        @foreach ($icd9Data as $icd9)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge badge-light-info fs-8">
                                                                        {{ $icd9['diagnosa_icd9'] ?? '-' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Anamnesa, Pemeriksaan Fisik & Riwayat --}}
                    @if ($resume_detail->anamnesa_dokter || $resume_detail->anamnesa || $resume_detail->pemeriksaan_fisik || $resume_detail->pemeriksaan_fisik_dokter || $resume_detail->riwayat_kesehatan)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="kt_accordion_1_header_3">
                                <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#kt_accordion_1_body_3" aria-expanded="false">
                                    <i class="ki-duotone ki-notepad fs-3 text-info me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                    Anamnesa & Pemeriksaan
                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_3" class="accordion-collapse collapse" data-bs-parent="#kt_accordion_resume">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        @if ($resume_detail->anamnesa_dokter)
                                            @php
                                                $anamnesaDokter = is_string($resume_detail->anamnesa_dokter)
                                                    ? json_decode($resume_detail->anamnesa_dokter, true)
                                                    : $resume_detail->anamnesa_dokter;
                                            @endphp
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Anamnesa Dokter:</label>
                                                <div class="bg-light rounded p-3">
                                                    @if(is_array($anamnesaDokter))
                                                        @foreach($anamnesaDokter as $key => $value)
                                                            @if($value && $value != 'null')
                                                                <div class="mb-1">
                                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}:</small>
                                                                    <small class="d-block text-gray-800">{{ $value }}</small>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-gray-700">{!! nl2br(e($anamnesaDokter)) !!}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->anamnesa)
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Anamnesa Perawat:</label>
                                                <div class="bg-light rounded p-3">
                                                    <small class="text-gray-700">{!! nl2br(e($resume_detail->anamnesa)) !!}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->pemeriksaan_fisik_dokter)
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Pemeriksaan Fisik Dokter:</label>
                                                <div class="bg-light-warning rounded p-3">
                                                    <small class="text-gray-700">{!! nl2br(e($resume_detail->pemeriksaan_fisik_dokter)) !!}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->pemeriksaan_fisik)
                                            @php
                                                $pemeriksaanFisik = is_string($resume_detail->pemeriksaan_fisik)
                                                    ? json_decode($resume_detail->pemeriksaan_fisik, true)
                                                    : $resume_detail->pemeriksaan_fisik;
                                            @endphp
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Pemeriksaan Fisik Perawat:</label>
                                                <div class="bg-light-warning rounded p-3">
                                                    @if(is_array($pemeriksaanFisik))
                                                        <div class="row g-2">
                                                            @foreach($pemeriksaanFisik as $key => $value)
                                                                @if($value && $value != 'null')
                                                                    <div class="col-6">
                                                                        <small class="text-muted d-block">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                                        <small class="fw-bold text-gray-800">{{ $value }}</small>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <small class="text-gray-700">{!! nl2br(e($pemeriksaanFisik)) !!}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->riwayat_kesehatan)
                                            @php
                                                $riwayatKesehatan = is_string($resume_detail->riwayat_kesehatan)
                                                    ? json_decode($resume_detail->riwayat_kesehatan, true)
                                                    : $resume_detail->riwayat_kesehatan;
                                            @endphp
                                            <div class="col-md-12">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">
                                                    <i class="ki-duotone ki-calendar-tick fs-5 text-primary me-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                    Riwayat Kesehatan:
                                                </label>
                                                <div class="bg-light-primary rounded p-3">
                                                    @if(is_array($riwayatKesehatan))
                                                        <div class="row g-2">
                                                            @foreach($riwayatKesehatan as $key => $value)
                                                                @if($value && $value != 'null')
                                                                    <div class="col-md-3 col-6">
                                                                        <small class="text-muted d-block">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                                        <small class="fw-bold text-gray-800">{{ is_array($value) ? json_encode($value) : $value }}</small>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <small class="text-gray-700">{!! nl2br(e($riwayatKesehatan)) !!}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Terapi & Penunjang --}}
                    @if ($resume_detail->terapi || $resume_detail->terapi_obat || $resume_detail->radiologi || $resume_detail->laborat || $resume_detail->fisio)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="kt_accordion_1_header_4">
                                <button class="accordion-button fs-5 fw-bold collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#kt_accordion_1_body_4" aria-expanded="false">
                                    <i class="ki-duotone ki-capsule fs-3 text-success me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Terapi & Pemeriksaan Penunjang
                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" data-bs-parent="#kt_accordion_resume">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        @if ($resume_detail->terapi)
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Terapi:</label>
                                                <div class="bg-light-success rounded p-3">
                                                    <small class="text-gray-700">{!! nl2br(e($resume_detail->terapi)) !!}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->terapi_obat && $resume_detail->terapi_obat != 'null')
                                            @php
                                                $terapiObat = is_string($resume_detail->terapi_obat)
                                                    ? json_decode($resume_detail->terapi_obat, true)
                                                    : $resume_detail->terapi_obat;
                                            @endphp
                                            <div class="col-md-6">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Terapi Obat:</label>
                                                <div class="bg-light-success rounded p-3">
                                                    @if(is_array($terapiObat))
                                                        @foreach($terapiObat as $key => $value)
                                                            @if($value && $value != 'null')
                                                                <small class="d-block text-gray-700 mb-1">{{ $value }}</small>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-gray-700">{!! nl2br(e($terapiObat)) !!}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->radiologi)
                                            <div class="col-md-4">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Radiologi:</label>
                                                <div class="bg-light-info rounded p-3">
                                                    <small class="text-gray-700">{{ $resume_detail->radiologi }}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->laborat)
                                            <div class="col-md-4">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Laboratorium:</label>
                                                <div class="bg-light-warning rounded p-3">
                                                    <small class="text-gray-700">{{ $resume_detail->laborat }}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($resume_detail->fisio)
                                            <div class="col-md-4">
                                                <label class="fw-bold text-gray-700 fs-7 mb-1">Fisioterapi:</label>
                                                <div class="bg-light-success rounded p-3">
                                                    <small class="text-gray-700">{{ $resume_detail->fisio }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Kategori & Triase --}}
                    @if ($resume_detail->kategori_penyakit || $resume_detail->triase)
                        <div class="row g-3">
                            @if ($resume_detail->kategori_penyakit)
                                <div class="col-md-6">
                                    <label class="fw-bold text-gray-700 fs-7 mb-1">Kategori Penyakit:</label>
                                    <div class="bg-light rounded p-3 text-center">
                                        <span class="badge badge-lg badge-light-primary">{{ $resume_detail->kategori_penyakit }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($resume_detail->triase)
                                <div class="col-md-6">
                                    <label class="fw-bold text-gray-700 fs-7 mb-1">Triase:</label>
                                    <div class="bg-light rounded p-3 text-center">
                                        <span class="badge badge-lg badge-light-danger">{{ $resume_detail->triase }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
                {{-- End Accordion --}}
            @else
                <form action="{{ route('post.resume-poli') }}" id="frmResume" method="post">
                    @csrf
                    <input type="hidden" name="idrawat" value="{{ $rawat->id }}">
                    <input type="hidden" name="idkategori" value="{{ $rawat->idjenisrawat == 1 ? 1 : 3 }}">
                    <input type="hidden" name="idpasien" value="{{ $pasien->id }}">
                    <button type="submit" class="btn btn-warning">
                        <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span
                                class="path2"></span></i>
                        Input Resume
                    </button>
                </form>
            @endif
        @endif
        </div>
</div>
