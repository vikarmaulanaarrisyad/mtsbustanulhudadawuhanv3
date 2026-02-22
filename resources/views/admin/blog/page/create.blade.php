@extends('layouts.app')

@section('title', 'Buat Postingan')
@section('subtitle', 'Buat Postingan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Blog</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <form id="post-form" action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            {{-- ================= LEFT CONTENT ================= --}}
            <div class="col-lg-8">
                <x-card>
                    <x-slot name="header">
                        FORM HALAMAN
                    </x-slot>

                    <div class="form-group">
                        <label for="title" class="font-weight-semibold">
                            Judul Halaman
                        </label>
                        <input id="title" type="text" name="title" class="form-control"
                            placeholder="Masukkan judul halaman..." autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label for="body" class="font-weight-semibold">
                            Isi Halaman
                        </label>
                        <textarea name="body" id="body" rows="15" class="form-control summernote"></textarea>
                    </div>
                </x-card>
            </div>


            {{-- ================= RIGHT SIDEBAR ================= --}}
            <div class="col-lg-4">
                <x-card>
                    <div class="d-flex flex-column gap-2">

                        <a href="{{ route('pages.index') }}" class="btn btn-warning btn-sm mb-2">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>

                        <button type="button" onclick="resetPostForm()" class="btn btn-secondary btn-sm mb-2">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Atur Ulang
                        </button>

                        <button type="button" onclick="submitForm(this.form)" class="btn btn-success btn-sm"
                            id="submitBtn">

                            <span id="spinner-border" class="spinner-border spinner-border-sm d-none mr-1"
                                role="status"></span>

                            <i class="fas fa-save mr-1"></i>
                            Simpan
                        </button>

                    </div>
                </x-card>
            </div>

        </div>
    </form>
@endsection

@include('includes.summernote')

@push('scripts')
    <script>
        let button = '#submitBtn';

        function submitForm(form) {
            // let form = document.getElementById('post-form');
            $('#submitBtn').prop('disabled', true);
            $('#spinner-border').removeClass('d-none');

            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    Swal.close();
                    $('#submitBtn').prop('disabled', false);
                    $('#spinner-border').addClass('d-none');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Data berhasil disimpan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect || '{{ route('pages.index') }}';
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    $('#submitBtn').prop('disabled', false);
                    $('#spinner-border').addClass('d-none');

                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                        timer: 3000
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
        }

        function resetPostForm() {
            const form = document.getElementById('post-form');

            // Reset native form
            form.reset();

            // Reset Summernote
            $('#post_content').summernote('reset');

            // Uncheck all checkboxes (tags dan kategori)
            $('#post-form input[type=checkbox]').prop('checked', false);

            // Reset select dropdown ke default
            $('#post_status').val('publish');
            $('#post_visibility').val('public');
            $('#post_comment_status').val('open');

            // Clear file input
            $('#post_image').val('');

            // Hapus error validasi
            $('#post-form .is-invalid').removeClass('is-invalid');
            $('#post-form .invalid-feedback').remove();
        }
    </script>
@endpush
