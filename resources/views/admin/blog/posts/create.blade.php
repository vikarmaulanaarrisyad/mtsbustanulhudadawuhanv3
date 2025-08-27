@extends('layouts.app')

@section('title', 'Buat Postingan')
@section('subtitle', 'Buat Postingan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="#">Blog</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <x-card>
                    <div class="form-group">
                        <label for="post_title">Judul Postingan</label>
                        <input id="post_title" class="form-control" type="text" name="post_title" autocomplete="off"
                            placeholder="Masukkan judul postingan">
                    </div>

                    <div class="form-group">
                        <label for="post_content">Isi Postingan</label>
                        <textarea name="post_content" id="post_content" cols="50" rows="10" class="form-control summernote"></textarea>
                    </div>
                </x-card>
            </div>

            <div class="col-lg-4">
                <x-card>
                    <x-slot name="header">KATEGORI</x-slot>
                    <div id="category-list">
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]"
                                    value="{{ $category->id }}" id="category-{{ $category->id }}">
                                <label class="form-check-label" for="category-{{ $category->id }}">
                                    {{ $category->category_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <x-slot name="footer">
                        <button type="button"
                            onclick="addFormCategories('{{ route('categories.store') }}', 'Kategori Tulisan')"
                            class="btn btn-sm btn-info float-right">
                            <i class="fas fa-plus-circle"></i> Tambah Kategori
                        </button>
                    </x-slot>
                </x-card>

                <x-card>
                    <x-slot name="header">TAGS</x-slot>
                    <div id="tag-list">
                        @foreach ($tags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    id="tag-{{ $tag->id }}">
                                <label class="form-check-label" for="tag-{{ $tag->id }}">
                                    {{ $tag->tag_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <x-slot name="footer">
                        <button type="button" onclick="addFormTags('{{ route('tags.store') }}', 'Tag Tulisan')"
                            class="btn btn-sm btn-info float-right">
                            <i class="fas fa-plus-circle"></i> Tambah Tags
                        </button>
                    </x-slot>
                </x-card>

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

                            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-undo-alt"></i> Kembali
                            </a>

                            <button type="button" onclick="resetPostForm()" class="btn btn-sm btn-warning">
                                <i class="fas fa-undo-alt"></i> Atur Ulang
                            </button>

                            <button type="button" onclick="submitPostForm(this.form)" class="btn btn-sm btn-outline-info"
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

    @include('admin.blog.categories.form')
    @include('admin.blog.tags.form')
@endsection

@include('includes.summernote')

@push('scripts')
    <script>
        let modalCategory = '#modal-categories';
        let modalTags = '#modal-tags';
        let button = '#submitBtn';

        function addFormCategories(url, title = 'Form') {
            $(modalCategory).modal('show');
            $(`${modalCategory} .modal-title`).text(title);
            $(`${modalCategory} form`).attr('action', url);
            $(`${modalCategory} [name=_method]`).val('post');
            $(`${modalCategory} #spinner-border`).hide();

            resetForm(`${modalCategory} form`);
        }

        function addFormTags(url, title = 'Form') {
            $(modalTags).modal('show');
            $(`${modalTags} .modal-title`).text(title);
            $(`${modalTags} form`).attr('action', url);
            $(`${modalTags} [name=_method]`).val('post');
            $(`${modalTags} #spinner-border`).hide();

            resetForm(`${modalTags} form`);
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            $(`${modalCategory} #spinner-border`).show();
            $(`${modalTags} #spinner-border`).show();

            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST',
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    Swal.close();
                    $(modalCategory).modal('hide');
                    $(modalTags).modal('hide');
                    $(button).prop('disabled', false);

                    const item = response.data;
                    const checkbox = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="${item.type}[]" value="${item.id}" id="${item.type}-${item.id}">
                        <label class="form-check-label" for="${item.type}-${item.id}">
                            ${item.name}
                        </label>
                    </div>
                `;
                    $(`#${item.type}-list`).append(checkbox);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.reload();
                    });

                    $(`${modalCategory} #spinner-border`).hide();
                    $(`${modalTags} #spinner-border`).hide();
                },
                error: function(xhr) {
                    Swal.close();
                    $(button).prop('disabled', false);
                    $(`${modalCategory} #spinner-border`).hide();
                    $(`${modalTags} #spinner-border`).hide();
                    let errorMessage = xhr.responseJSON?.message || "Terjadi kesalahan!";
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
        }

        function submitPostForm(form) {
            // let form = document.getElementById('post-form');
            $('#submitBtn').prop('disabled', true);
            $('#spinner-border').removeClass('d-none');

            Swal.fire({
                title: 'Menyimpan Postingan...',
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
                        text: response.message || 'Postingan berhasil disimpan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect || '{{ route('posts.index') }}';
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
