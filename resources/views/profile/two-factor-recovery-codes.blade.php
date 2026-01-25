@extends('layouts.index')

@section('content')
<div class="container-xxl">
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Recovery Codes</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Simpan kode-kode ini di tempat yang aman</span>
                    </h3>
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">Sukses</h4>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('recovery_codes'))
                        <div class="alert alert-primary d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">Kode recovery baru telah dibuat! Simpan kode-kode ini dengan aman.</span>
                            </div>
                        </div>
                    @endif

                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-10">
                        <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Penting!</h4>
                                <div class="fs-6 text-gray-700">
                                    Recovery codes dapat digunakan untuk mengakses akun Anda jika Anda kehilangan akses ke aplikasi authenticator.
                                    <strong>Setiap kode hanya dapat digunakan satu kali.</strong>
                                    <br><br>
                                    <ul class="mb-0">
                                        <li>Simpan kode-kode ini di tempat yang aman</li>
                                        <li>Jangan bagikan kode ini kepada siapapun</li>
                                        <li>Anda dapat men-download atau print kode ini</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="card card-bordered">
                                <div class="card-body">
                                    <h4 class="card-title mb-5 text-center">Recovery Codes Anda</h4>

                                    @php
                                        $codes = session('recovery_codes', $recoveryCodes ?? []);
                                    @endphp

                                    @if(empty($codes))
                                        <div class="alert alert-info">
                                            <i class="ki-duotone ki-information-5 fs-2x text-info me-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                            Tidak ada recovery codes tersedia. Silakan regenerate.
                                        </div>
                                    @else
                                        <div class="p-8 bg-light rounded mb-5" id="recovery-codes-container">
                                            <div class="row g-3">
                                                @foreach($codes as $index => $code)
                                                    <div class="col-md-6">
                                                        <div class="p-4 bg-white rounded border border-gray-300">
                                                            <code class="fs-4 fw-bold text-dark">{{ $code }}</code>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 justify-content-center mb-5">
                                            <button type="button" class="btn btn-light-primary" onclick="copyRecoveryCodes()">
                                                <i class="ki-duotone ki-copy fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                                Copy Semua
                                            </button>
                                            <button type="button" class="btn btn-light-info" onclick="downloadRecoveryCodes()">
                                                <i class="ki-duotone ki-file-down fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Download
                                            </button>
                                            <button type="button" class="btn btn-light-success" onclick="printRecoveryCodes()">
                                                <i class="ki-duotone ki-printer fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                                Print
                                            </button>
                                        </div>
                                    @endif

                                    <div class="separator my-10"></div>

                                    <div class="text-center">
                                        <h5 class="fw-bold mb-5">Regenerate Recovery Codes</h5>
                                        <p class="text-gray-600 mb-5">
                                            Jika Anda merasa recovery codes Anda telah terkompromi,
                                            Anda dapat men-generate ulang kode baru.
                                            <strong class="text-danger">Kode lama akan tidak berlaku.</strong>
                                        </p>

                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#regenerateModal">
                                            <i class="ki-duotone ki-arrows-circle fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Regenerate Recovery Codes
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <a href="{{ route('two-factor.index') }}" class="btn btn-light">
                                    Kembali ke Pengaturan 2FA
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Regenerate Modal -->
<div class="modal fade" id="regenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Regenerate Recovery Codes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('two-factor.regenerate-codes') }}">
                @csrf

                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-information-5 fs-2hx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <span>Regenerating akan membuat semua recovery codes lama tidak valid.</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Password untuk Konfirmasi</label>
                        <input type="password" name="password" class="form-control" required />
                        @error('password')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Regenerate Codes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyRecoveryCodes() {
    const codes = @json(session('recovery_codes', $recoveryCodes ?? []));
    const text = codes.join('\n');

    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Recovery codes telah di-copy ke clipboard',
            timer: 2000,
            showConfirmButton: false
        });
    });
}

function downloadRecoveryCodes() {
    const codes = @json(session('recovery_codes', $recoveryCodes ?? []));
    const text = 'ERM Sulaiman - Recovery Codes\n' +
                 'Generated: ' + new Date().toLocaleString() + '\n\n' +
                 codes.join('\n') + '\n\n' +
                 'IMPORTANT: Keep these codes in a safe place!\n' +
                 'Each code can only be used once.';

    const blob = new Blob([text], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'recovery-codes-' + Date.now() + '.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function printRecoveryCodes() {
    const codes = @json(session('recovery_codes', $recoveryCodes ?? []));
    const printWindow = window.open('', '_blank');

    const html = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Recovery Codes</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; }
                h1 { color: #009ef7; }
                .code {
                    font-size: 18px;
                    font-weight: bold;
                    padding: 10px;
                    background: #f5f5f5;
                    margin: 10px 0;
                    border: 1px solid #ddd;
                }
                .warning {
                    color: #f1416c;
                    font-weight: bold;
                    margin-top: 30px;
                }
            </style>
        </head>
        <body>
            <h1>ERM Sulaiman - Recovery Codes</h1>
            <p>Generated: ${new Date().toLocaleString()}</p>
            <hr>
            ${codes.map(code => '<div class="code">' + code + '</div>').join('')}
            <hr>
            <p class="warning">IMPORTANT: Keep these codes in a safe place!</p>
            <p>Each code can only be used once.</p>
        </body>
        </html>
    `;

    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
@endpush
@endsection
