@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="login-page-wrapper">
        <div class="login-bg-overlay"></div>

        <div class="login-card">
            <div class="text-center">
                <a href="{{ url('/') }}">
                    @if ($setting->path_image)
                        <img src="{{ Storage::url($setting->path_image) }}" alt="Logo" class="login-logo">
                    @else
                        <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="login-logo">
                    @endif
                </a>
                <h3 class="font-weight-bold mb-1">Selamat Datang</h3>
                <p class="text-muted mb-4">Silahkan masuk ke akun Anda</p>
            </div>

            <form id="loginForm" action="{{ route('login') }}" method="post">
                @csrf

                <div class="form-group mb-3">
                    <label for="auth" class="form-label">Username atau Email</label>
                    <input type="text" class="form-control @error('auth') is-invalid @enderror"
                        id="auth" name="auth" value="{{ old('auth') }}" 
                        placeholder="Masukkan username" autocomplete="off">

                    @error('auth')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror password"
                            id="password" name="password" placeholder="••••••••" autocomplete="off">
                        @error('password')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label for="customCheck1" class="custom-control-label text-muted small" style="cursor: pointer;">Tampilkan Password</label>
                    </div>
                    <a href="#" class="small text-primary-custom">Lupa Password?</a>
                </div>

                <button type="button" onclick="login()" id="loginButton" class="btn btn-login">
                    <span id="buttonText"><i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang</span>
                    <span id="loadingSpinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                    </span>
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-primary-custom">Daftar disini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Menangani keypress event
        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                login();
            }
        });

        // Fungsi untuk login
        function login() {
            let auth = $('#auth').val();
            let password = $('.password').val();

            if (!auth) {
                toastr.info('Username atau Email wajib diisi');
                return;
            }

            if (!password) {
                toastr.info('Password wajib diisi');
                return;
            }

            // Disable the button to prevent multiple clicks during the Ajax request
            const loginButton = $('#loginButton');
            const buttonText = $('#buttonText');
            const loadingSpinner = $('#loadingSpinner');

            loginButton.attr('disabled', true);
            buttonText.hide();
            loadingSpinner.show();

            $.ajax({
                type: 'POST',
                url: '{{ route('login') }}',
                data: $('#loginForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login berhasil',
                        text: 'Selamat anda berhasil login ke dalam sistem kami',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = '{{ route('home') }}';
                    });
                },
                error: function(errors) {
                    // Handle the error response
                    loopErrors(errors.responseJSON.errors);
                    toastr.error(errors.responseJSON.message);
                },
                complete: function() {
                    // Re-enable the button and hide the loading indicator
                    loginButton.attr('disabled', false);
                    buttonText.show();
                    loadingSpinner.hide();
                }
            });
        }
    </script>
@endpush
