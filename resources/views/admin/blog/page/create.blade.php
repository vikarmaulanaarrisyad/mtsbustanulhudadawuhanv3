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
            <div class="col-lg-8">
                <x-card>
                    <div class="form-group">
                        <label for="title">Judul Halaman</label>
                        <input id="title" class="form-control" type="text" name="title" autocomplete="off"
                            placeholder="Masukkan judul halaman">
                    </div>

                    <div class="form-group">
                        <label for="body">Isi Halaman</label>
                        <textarea name="body" id="body" cols="200" rows="60" class="form-control summernote"></textarea>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-4">
                <x-card>
                    <x-slot name="header">PUBLIKASI</x-slot>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="post_status">Status</label>
                                <select name="post_status" class="form-control" id="post_status">
                                    <option value="draft">Draft</option>
                                    <option value="publish" selected>Diterbitkan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="post_visibility">Akses</label>
                                <select name="post_visibility" class="form-control" id="post_visibility">
                                    <option value="public">Publik</option>
                                    <option value="private">Privat</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post_comment_status">Komentar</label>
                        <select name="post_comment_status" class="form-control" id="post_comment_status">
                            <option value="open">Diizinkan</option>
                            <option value="close">Tidak Diizinkan</option>
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label for="post_image">Gambar</label>
                        <input type="file" name="post_image" class="form-control-file" id="post_image">
                    </div>

                    <x-slot name="footer">
                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('pages.index') }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-undo-alt"></i> Kembali
                            </a>

                            <button type="button" onclick="resetPostForm()" class="btn btn-sm btn-warning">
                                <i class="fas fa-undo-alt"></i> Atur Ulang
                            </button>

                            <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-info"
                                id="submitBtn">
                                <span id="spinner-border" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                        </div>
                    </x-slot>
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
