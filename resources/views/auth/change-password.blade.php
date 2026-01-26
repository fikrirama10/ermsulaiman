@extends('layouts.index')

@section('title', 'Ubah Password')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-10">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success ">
                    <h3 class="card-title mb-0 text-white">
                        <i class="fas fa-key me-2 text-white"></i> Ubah Password
                    </h3>
                </div>
                
                <div class="card-body">
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($daysUntilExpiry !== null && $daysUntilExpiry <= 7 && $daysUntilExpiry > 0)
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-clock me-2"></i>
                            Password Anda akan kadaluarsa dalam <strong>{{ $daysUntilExpiry }} hari</strong>. Segera ganti password Anda.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" id="passwordChangeForm">
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label required">Password Lama</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password"
                                       required
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label required">Password Baru</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       onkeyup="checkPasswordStrength()">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol.</small>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="d-flex align-items-center">
                                    <span class="me-2" style="font-size: 12px;">Kekuatan:</span>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <span id="strengthText" class="ms-2" style="font-size: 12px;"></span>
                                </div>
                            </div>
                            
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label required">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Ubah Password
                            </button>
                            @if(!request()->session()->has('warning'))
                            <a href="{{ route('dashboard') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Requirements Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Persyaratan Password</h6>
                    <ul class="small mb-0">
                        <li>Minimal 8 karakter</li>
                        <li>Mengandung huruf besar (A-Z)</li>
                        <li>Mengandung huruf kecil (a-z)</li>
                        <li>Mengandung angka (0-9)</li>
                        <li>Mengandung simbol (!@#$%^&*)</li>
                        <li>Tidak boleh sama dengan password lama</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = event.currentTarget.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    
    // Length check
    if (password.length >= 8) strength += 20;
    if (password.length >= 12) strength += 10;
    
    // Contains lowercase
    if (/[a-z]/.test(password)) strength += 15;
    
    // Contains uppercase
    if (/[A-Z]/.test(password)) strength += 15;
    
    // Contains numbers
    if (/[0-9]/.test(password)) strength += 20;
    
    // Contains symbols
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 20;
    
    // Update progress bar
    strengthBar.style.width = strength + '%';
    
    // Update color and text
    if (strength < 40) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Lemah';
        strengthText.className = 'ms-2 text-danger';
    } else if (strength < 70) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Sedang';
        strengthText.className = 'ms-2 text-warning';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Kuat';
        strengthText.className = 'ms-2 text-success';
    }
}
</script>
@endpush
@endsection
