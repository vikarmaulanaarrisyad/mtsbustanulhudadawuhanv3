@extends($layout)

@section('title', 'Profil & Sambutan Kepala Madrasah')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Front-End Madrasah</li>
    <li class="breadcrumb-item active">Profil Kepala</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-amber overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-tie mr-2 animate__animated animate__fadeInDown"></i> 
                            Executive Profile & Sambutan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola profil dan pesan resmi dari Kepala Madrasah yang akan ditampilkan sebagai representasi institusi di website utama.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-quote-right fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<form id="sambutanForm" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
    @csrf
    @if(isset($data) && $data->id)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="row">
        <!-- LEFT PANEL: EXECUTIVE PROFILE -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 premium-card h-100">
                <div class="card-header bg-white py-4 border-bottom text-center">
                    <h4 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-id-badge text-amber mr-2"></i> Identitas Pimpinan
                    </h4>
                </div>
                <div class="card-body p-4 bg-light-soft text-center">
                    
                    <!-- Avatar Upload Area -->
                    <div class="avatar-upload-container mb-4 mx-auto position-relative">
                        <div class="avatar-preview-circle shadow-lg-premium bg-white d-flex align-items-center justify-content-center overflow-hidden mx-auto border-amber" style="width: 180px; height: 180px; border-radius: 50%; border: 4px solid #f59e0b;">
                            @if (isset($data) && $data->path_image)
                                <img src="{{ asset('storage/' . $data->path_image) }}" id="imagePreview" alt="Foto Kepala" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="" id="imagePreview" alt="Preview" class="w-100 h-100 object-fit-cover" style="display:none;">
                                <i class="fas fa-user-tie fa-4x text-muted opacity-50" id="defaultIcon"></i>
                            @endif
                        </div>
                        <div class="mt-3">
                            <label for="path_image" class="btn btn-outline-amber rounded-pill font-weight-bold px-4 py-2 cursor-pointer shadow-sm">
                                <i class="fas fa-camera mr-2"></i> Ganti Foto Profil
                            </label>
                            <input type="file" name="path_image" id="path_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG/PNG. Rasio ideal 1:1 (Persegi)</small>
                    </div>

                    <!-- Name Input -->
                    <div class="form-group text-left mb-0 mt-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-user text-amber opacity-50"></i>
                            <input type="text" name="name" id="name" class="form-control border-0 font-weight-bold text-lg text-dark" value="{{ $data->name ?? '' }}" placeholder="Contoh: Dr. H. Fulan, M.Pd." required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: SPEECH CONTENT -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 premium-card h-100">
                <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-comment-dots text-amber mr-2"></i> Naskah Sambutan
                    </h4>
                    <button type="submit" class="btn btn-amber rounded-pill px-5 font-weight-bold shadow-amber-light text-white" id="submitBtn">
                        <i class="fas fa-paper-plane mr-2"></i> TERBITKAN PROFIL
                    </button>
                </div>
                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="alert bg-soft-amber border-0 rounded-10 d-flex align-items-center p-3 mb-4 shadow-sm">
                        <i class="fas fa-info-circle text-amber fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1">Panduan Penulisan</h6>
                            <p class="mb-0 text-muted small">Gunakan bahasa formal dan inspiratif. Naskah ini akan dibaca oleh calon siswa, orang tua, dan masyarakat umum yang mengunjungi website Anda.</p>
                        </div>
                    </div>

                    <div class="premium-textarea-wrapper">
                        <textarea id="sambutan" name="sambutan" class="form-control summernote border-0">
                            {{ $data->content ?? '' }}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    /* Premium Amber/Gold Design System */
    .bg-gradient-amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
    .bg-soft-amber { background: #fef3c7 !important; }
    .text-amber { color: #f59e0b !important; }
    .btn-amber { background: #f59e0b; color: #fff; border: none; }
    .btn-amber:hover { background: #d97706; color: #fff; }
    .btn-outline-amber { color: #d97706; border-color: #f59e0b; background: transparent; }
    .btn-outline-amber:hover { background: #fef3c7; color: #d97706; }
    .shadow-amber-light { box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4); }
    .border-amber { border-color: #f59e0b !important; }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .shadow-lg-premium { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .rounded-10 { border-radius: 10px; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .cursor-pointer { cursor: pointer; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 50px;
    }
    .input-group-premium i { font-size: 18px; margin-right: 15px; }
    .input-group-premium input { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    .input-group-premium:focus-within { border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
    
    /* Premium Textarea / Summernote Wrapper */
    .premium-textarea-wrapper { border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden; }
    .premium-textarea-wrapper:focus-within { border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
    .note-editor.note-frame { border: none !important; margin-bottom: 0 !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
</style>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    // Live Image Preview function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
                if($('#defaultIcon').length) { $('#defaultIcon').hide(); }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        $('#sambutan').summernote({
            height: 400,
            placeholder: 'Mulai menuliskan naskah sambutan resmi di sini...',
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#sambutanForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append('sambutan', $('#sambutan').summernote('code'));

            let url = "{{ isset($data) && $data->id ? route('opening_speech.update', $data->id) : route('opening_speech.store') }}";
            let btn = $('#submitBtn');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...');

            Swal.fire({
                title: 'Menerbitkan Profil...', text: 'Harap tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: url, type: 'POST', data: formData, processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire({ icon: 'success', title: 'Terbit!', text: 'Profil dan sambutan berhasil diperbarui.', showConfirmButton: false, timer: 2000 })
                    .then(() => window.location.reload());
                },
                error: function(xhr) {
                    let errMsg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem. Coba lagi.';
                    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: errMsg });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> TERBITKAN PROFIL');
                }
            });
        });
    });
</script>
@endpush
