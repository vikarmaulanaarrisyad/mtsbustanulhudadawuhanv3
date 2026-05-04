@extends($layout)

@section('title', 'Tulis Halaman Statis')
@section('subtitle', 'Editor Halaman')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Halaman Statis</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
<form id="post-form" action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data" class="animate__animated animate__fadeInUp">
    @csrf

    <div class="row">
        {{-- ================= LEFT CONTENT: EDITOR PANEL ================= --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 premium-card h-100">
                <div class="card-header bg-white py-4 border-bottom d-flex align-items-center">
                    <div class="icon-box bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                        <i class="fas fa-pen-nib"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 font-weight-bold text-dark">Editor Dokumen</h4>
                        <small class="text-muted">Tulis judul dan isi halaman dengan lengkap.</small>
                    </div>
                </div>
                
                <div class="card-body p-4 bg-light-soft">
                    
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Judul Halaman <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-heading text-primary opacity-50"></i>
                            <input id="title" type="text" name="title" class="form-control border-0 font-weight-bold text-lg text-dark"
                                placeholder="Contoh: Profil Sejarah Madrasah..." autocomplete="off" required autofocus>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted uppercase">Isi Halaman <span class="text-danger">*</span></label>
                        <div class="premium-textarea-wrapper bg-white">
                            <textarea name="body" id="body" rows="20" class="form-control summernote border-0"></textarea>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- ================= RIGHT SIDEBAR: PUBLISH PANEL ================= --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 premium-card sticky-top" style="top: 80px; z-index: 10;">
                <div class="card-header bg-gradient-primary text-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-paper-plane mr-2"></i> Publikasi Dokumen</h5>
                </div>
                <div class="card-body p-4 bg-white">
                    
                    <div class="alert bg-light-primary border-0 rounded-10 d-flex align-items-start p-3 mb-4">
                        <i class="fas fa-info-circle text-primary mt-1 mr-2"></i>
                        <small class="text-dark">Halaman yang disimpan akan otomatis aktif dan memiliki link permanen (Slug URL).</small>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary btn-lg rounded-pill font-weight-bold shadow-primary-light" id="submitBtn">
                            <span id="spinner-border" class="spinner-border spinner-border-sm d-none mr-2" role="status"></span>
                            <i class="fas fa-cloud-upload-alt mr-2" id="submitIcon"></i>
                            SIMPAN HALAMAN
                        </button>

                        <button type="button" onclick="resetPostForm()" class="btn btn-light btn-lg rounded-pill font-weight-bold text-muted border">
                            <i class="fas fa-sync-alt mr-2"></i> KOSONGKAN FORM
                        </button>

                        <a href="{{ route('pages.index') }}" class="btn btn-link text-danger mt-2 font-weight-bold">
                            <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

<style>
    /* Premium Primary/Blue Design System */
    .bg-gradient-primary { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; }
    .bg-light-primary { background: #eff6ff !important; }
    .text-primary { color: #2563eb !important; }
    .btn-primary { background: #3b82f6; color: #fff; border: none; }
    .btn-primary:hover { background: #2563eb; color: #fff; }
    .shadow-primary-light { box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-10 { border-radius: 10px; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 55px;
    }
    .input-group-premium i { font-size: 18px; margin-right: 15px; }
    .input-group-premium input { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    .input-group-premium:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    
    /* Premium Textarea / Summernote Wrapper */
    .premium-textarea-wrapper { border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden; }
    .premium-textarea-wrapper:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .note-editor.note-frame { border: none !important; margin-bottom: 0 !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    
    /* Utility */
    .gap-3 { gap: 1rem !important; }
</style>
@endsection

@include('includes.summernote')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let button = '#submitBtn';

    function submitForm(form) {
        let btn = $('#submitBtn');
        let icon = $('#submitIcon');
        let spinner = $('#spinner-border');

        btn.prop('disabled', true);
        icon.addClass('d-none');
        spinner.removeClass('d-none');

        Swal.fire({ title: 'Menyimpan Dokumen...', text: 'Mohon tunggu sebentar.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: $(form).attr('action'), method: $(form).attr('method'), data: new FormData(form),
            dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function(response) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message || 'Halaman berhasil dipublikasi', timer: 2000, showConfirmButton: false })
                .then(() => { window.location.href = response.redirect || '{{ route('pages.index') }}'; });
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                icon.removeClass('d-none');
                spinner.addClass('d-none');

                let msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.';
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg, timer: 3000 });

                if (xhr.status === 422) { loopErrors(xhr.responseJSON.errors); }
            }
        });
    }

    function resetPostForm() {
        Swal.fire({
            title: 'Kosongkan Form?', text: "Tulisan yang belum disimpan akan hilang.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#e3342f', cancelButtonColor: '#aaa',
            confirmButtonText: 'Iya, Kosongkan', cancelButtonText: 'Batal', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('post-form');
                form.reset();
                $('#body').summernote('reset');
                $('#post-form .is-invalid').removeClass('is-invalid');
                $('#post-form .invalid-feedback').remove();
            }
        });
    }
</script>
@endpush
