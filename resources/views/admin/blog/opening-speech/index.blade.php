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
                <form id="sambutanForm">
                    @csrf
                    <div class="form-group">
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
    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <script>
        $(document).ready(function() {
            // Inisialisasi Summernote dengan value dari DB
            $('#sambutan').summernote({
                height: 450,
                placeholder: 'Tulis sambutan kepala madrasah di sini...'
            });

            // Submit dengan AJAX
            $('#sambutanForm').on('submit', function(e) {
                e.preventDefault();

                let sambutan = $('#sambutan').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                // Swal loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Harap tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('opening_speech.update', $data->id) }}", // sesuaikan dengan route
                    method: "PUT",
                    data: {
                        _token: token,
                        sambutan: sambutan
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Sambutan berhasil disimpan!'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan. Coba lagi.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
