@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="login-page-wrapper">
        <div class="login-bg-overlay"></div>

        <div class="login-card" style="max-width: 550px;">
            <div class="text-center">
                <a href="{{ url('/') }}">
                    @if ($setting->path_image)
                        <img src="{{ Storage::url($setting->path_image) }}" alt="Logo" class="login-logo">
                    @else
                        <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="login-logo">
                    @endif
                </a>
                <h3 class="font-weight-bold mb-1">Daftar Akun Baru</h3>
                <p class="text-muted mb-4">Silahkan lengkapi data diri Anda untuk memulai pendaftaran PPDB</p>
            </div>

            <form id="registerForm" action="{{ route('register') }}" method="post">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" placeholder="Nama sesuai ijazah" autocomplete="off">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}" placeholder="Buat username unik" autocomplete="off">
                            @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control @error('password') is-invalid @enderror password"
                                id="password" name="password" placeholder="••••••••" autocomplete="off">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror password"
                                id="password_confirmation" name="password_confirmation" placeholder="••••••••" autocomplete="off">
                            @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label for="customCheck1" class="custom-control-label text-muted small" style="cursor: pointer;">Tampilkan Password</label>
                    </div>
                </div>

                <button type="button" onclick="daftar()" id="daftarButton" class="btn btn-login">
                    <span id="buttonText"><i class="fas fa-user-plus mr-2"></i> Daftar Akun Sekarang</span>
                    <span id="loadingSpinner" style="display:none;">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                    </span>
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-primary-custom">Masuk disini</a>
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
                daftar();
            }
        });

        // Fungsi untuk daftar
        function daftar() {
            let username = $('#email').val();
            let password = $('.password').val();

            if (!username) {
                toastr.info('Emsil wajib diisi');
                return;
            }

            if (!password) {
                toastr.info('Password wajib diisi');
                return;
            }

            // Disable the button to prevent multiple clicks during the Ajax request
            const daftarButton = $('#daftarButton');
            const buttonText = $('#buttonText');
            const loadingSpinner = $('#loadingSpinner');

            daftarButton.attr('disabled', true);
            buttonText.hide();
            loadingSpinner.show();

            $.ajax({
                type: 'POST',
                url: '{{ route('register') }}',
                data: $('#registerForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Daftar akun berhasil',
                        text: 'Selamat anda berhasil daftar ke dalam sistem kami',
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
                    daftarButton.attr('disabled', false);
                    buttonText.show();
                    loadingSpinner.hide();
                }
            });
        }
    </script>
@endpush
