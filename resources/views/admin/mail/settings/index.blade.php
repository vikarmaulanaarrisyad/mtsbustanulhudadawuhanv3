@extends($layout)

@section('title', 'Pengaturan Kop Surat')
@section('subtitle', 'Persuratan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-slate overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-print mr-2 animate__animated animate__fadeInLeft"></i> 
                            Konfigurasi Kop Surat
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Sesuaikan identitas lembaga dan format header untuk seluruh dokumen resmi Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-cogs fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT SIDEBAR: LOGO & PREVIEW -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 premium-card mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-image mr-2 text-slate"></i> Logo Madrasah
                </h5>
            </div>
            <div class="card-body pt-0 text-center">
                <div class="logo-preview-container mb-4">
                    @if ($mailSetting->logo)
                        <img src="{{ Storage::url($mailSetting->logo) }}" id="previewLogo" class="img-fluid rounded-xl shadow-sm border p-3 bg-white" style="max-height: 180px; width: auto;">
                    @else
                        <div class="bg-slate-50 py-5 rounded-xl border border-dashed border-slate-300">
                            <i class="fas fa-cloud-upload-alt fa-3x text-slate opacity-2 mb-2"></i>
                            <p class="text-xs font-weight-bold text-muted mb-0">BELUM ADA LOGO</p>
                        </div>
                    @endif
                </div>
                
                <button type="button" class="btn btn-slate btn-block rounded-pill font-weight-bold mb-2 shadow-slate-light" onclick="$('#logoInput').click()">
                    <i class="fas fa-upload mr-2"></i> UPLOAD LOGO BARU
                </button>
                <p class="text-[10px] text-muted uppercase font-weight-bold mb-4">Maksimal 2MB (PNG/JPG)</p>
                
                <hr class="opacity-5">
                
                <div class="alert bg-light-slate border-0 text-left rounded-xl p-3 mt-4">
                    <div class="d-flex">
                        <i class="fas fa-info-circle mr-3 mt-1 text-slate"></i>
                        <p class="text-xs mb-0 text-dark opacity-8">
                            Logo ini akan muncul di sisi kiri <b>Kop Surat</b> pada setiap dokumen cetak (PDF).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: FORM -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Detail Identitas Lembaga</h4>
                <p class="text-muted text-sm mb-0">Lengkapi data berikut untuk ditampilkan pada header surat</p>
            </div>
            <div class="card-body p-4">
                <form id="formSetting" onsubmit="event.preventDefault(); updateSetting(this);">
                    @csrf
                    <input type="file" name="logo" id="logoInput" style="display:none;" onchange="previewImage(this)">

                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nama Sekolah / Lembaga <span class="text-danger">*</span></label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-university text-slate"></i>
                                <input type="text" name="school_name" class="form-control font-weight-bold" value="{{ $mailSetting->school_name }}" placeholder="Contoh: MTs Bustanul Huda">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Singkatan</label>
                            <div class="input-group-premium bg-white shadow-sm text-center">
                                <input type="text" name="school_code" class="form-control font-weight-bold text-center" value="{{ $mailSetting->school_code }}" placeholder="MTs-BH">
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Sub-Header (Instansi Pembina)</label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-landmark text-slate"></i>
                                <input type="text" name="sub_header" class="form-control font-weight-bold" value="{{ $mailSetting->sub_header }}" placeholder="Yayasan / Kementerian / Dinas">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Alamat Lengkap</label>
                            <div class="input-group-premium bg-white shadow-sm py-2" style="height: auto;">
                                <i class="fas fa-map-marker-alt text-slate mt-1"></i>
                                <textarea name="address" class="form-control font-weight-bold" rows="2" placeholder="Jl. Raya Situbondo No. 123...">{{ $mailSetting->address }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nomor Telepon</label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-phone text-slate"></i>
                                <input type="text" name="phone" class="form-control font-weight-bold" value="{{ $mailSetting->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Email Resmi</label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-envelope text-slate"></i>
                                <input type="email" name="email" class="form-control font-weight-bold" value="{{ $mailSetting->email }}">
                            </div>
                        </div>
                        
                        <div class="col-12 mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Website</label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-globe text-slate"></i>
                                <input type="text" name="website" class="form-control font-weight-bold" value="{{ $mailSetting->website }}">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200 mb-4">
                        <h6 class="text-dark font-weight-bold mb-3 d-flex align-items-center">
                            <span class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center mr-2 text-slate">
                                <i class="fas fa-signature text-xs"></i>
                            </span>
                            Penandatangan Default (Otomatis Terisi)
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="text-[10px] font-black text-muted uppercase">Nama</label>
                                <input type="text" name="default_signer_name" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_name }}" placeholder="Budi Santoso, S.Pd">
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="text-[10px] font-black text-muted uppercase">Jabatan</label>
                                <input type="text" name="default_signer_position" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_position }}" placeholder="Kepala Madrasah">
                            </div>
                            <div class="col-md-4">
                                <label class="text-[10px] font-black text-muted uppercase">NIP</label>
                                <input type="text" name="default_signer_nip" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_nip }}" placeholder="19800101...">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Gaya Garis Pembatas</label>
                            <div class="input-group-premium bg-white shadow-sm">
                                <i class="fas fa-grip-lines text-slate"></i>
                                <select name="header_line_style" class="form-control font-weight-bold">
                                    <option value="none" {{ $mailSetting->header_line_style == 'none' ? 'selected' : '' }}>Tanpa Garis</option>
                                    <option value="solid" {{ $mailSetting->header_line_style == 'solid' ? 'selected' : '' }}>Garis Tunggal</option>
                                    <option value="double" {{ $mailSetting->header_line_style == 'double' ? 'selected' : '' }}>Garis Ganda (Premium)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-slate rounded-pill px-5 py-2 font-weight-bold shadow-slate-light" id="btnSave">
                                <i class="fas fa-save mr-1"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Layout */
    .bg-gradient-slate { background: linear-gradient(135deg, #475569 0%, #1e293b 100%) !important; }
    .bg-slate { background: #475569 !important; }
    .text-slate { color: #475569 !important; }
    .btn-slate { background: #1e293b; color: #fff; border: none; }
    .btn-slate:hover { background: #0f172a; color: #fff; transform: translateY(-2px); }
    .bg-light-slate { background: #f1f5f9; color: #475569; }
    .shadow-slate-light { box-shadow: 0 4px 15px rgba(30, 41, 59, 0.3); }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-xl { border-radius: 1rem !important; }
    .rounded-2xl { border-radius: 1.5rem !important; }
    .bg-slate-50 { background: #f8fafc; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select, .input-group-premium textarea { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%;
    }
    .input-group-premium:focus-within { border-color: #475569; box-shadow: 0 0 10px rgba(71, 85, 105, 0.1); }
    .input-group-premium:focus-within i { color: #475569; }
</style>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewLogo').attr('src', e.target.result).show();
                if ($('#previewLogo').length == 0) {
                    $('.logo-preview-container').html(`<img src="${e.target.result}" id="previewLogo" class="img-fluid rounded-xl shadow-sm border p-3 bg-white" style="max-height: 180px; width: auto;">`);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateSetting(form) {
        Swal.fire({
            title: 'Menyimpan Perubahan...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('mail-settings.update') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Diperbaharui',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server'
                });
            }
        });
    }
</script>
@endpush
