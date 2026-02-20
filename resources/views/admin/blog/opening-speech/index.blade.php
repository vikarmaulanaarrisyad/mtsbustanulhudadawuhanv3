@extends('layouts.app')

@section('title', 'Sambutan Kepala Madrasah')
@section('subtitle', 'Sambutan Kepala Madrasah')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Blog</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card>
                <form id="sambutanForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Kepala Madrasah --}}
                    <div class="form-group">
                        <label for="name">Nama Kepala Madrasah</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $data->name ?? '' }}" placeholder="Masukkan nama kepala madrasah">
                    </div>

                    {{-- Upload Foto --}}
                    <div class="form-group mt-3">
                        <label for="path_image">Foto Kepala Madrasah</label>
                        <input type="file" name="path_image" id="path_image" class="form-control" accept="image/*">

                        @if (isset($data) && $data->path_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $data->path_image) }}" alt="Foto Kepala" width="150"
                                    class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    {{-- Isi Sambutan --}}
                    <div class="form-group mt-3">
                        <label for="sambutan">Isi Sambutan</label>
                        <textarea id="sambutan" name="sambutan" class="form-control">
                            {{ $data->content ?? '' }}
                        </textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('#sambutan').summernote({
                height: 450,
                placeholder: 'Tulis sambutan kepala madrasah di sini...'
            });

            $('#sambutanForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append('sambutan', $('#sambutan').summernote('code'));

                let url =
                    "{{ $data && $data->id ? route('opening_speech.update', $data->id) : route('opening_speech.store') }}";
                let method = "{{ $data && $data->id ? 'POST' : 'POST' }}";

                @if (isset($data) && $data->id)
                    formData.append('_method', 'PUT');
                @endif

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Harap tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil disimpan!'
                        });
                    },
                    error: function(xhr) {
                        let errMsg = 'Terjadi kesalahan. Coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errMsg
                        });
                    }
                });
            });
        });
    </script>
@endpush
