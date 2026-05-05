@extends($layout)

@section('title', 'Setting')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Setting</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-midnight overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-tools mr-2 animate__animated animate__fadeInLeft"></i> 
                            Konfigurasi Sistem
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Sesuaikan identitas sekolah, logo, dan pengaturan teknis aplikasi.
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
    <!-- LEFT SIDEBAR: TABS -->
    <div class="col-xl-3 col-lg-4">
        <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">Menu Pengaturan</h5>
            </div>
            <div class="card-body p-2">
                <div class="nav flex-column nav-pills premium-nav-vertical">
                    <a class="nav-link @if (request('pills') == '') active @endif mb-2 d-flex align-items-center"
                        href="{{ route('setting.index') }}">
                        <div class="nav-icon-box"><i class="fas fa-school"></i></div>
                        <span>Identitas Madrasah</span>
                    </a>
                    <a class="nav-link @if (request('pills') == 'logo') active @endif mb-2 d-flex align-items-center"
                        href="{{ route('setting.index') }}?pills=logo">
                        <div class="nav-icon-box"><i class="fas fa-image"></i></div>
                        <span>Logo & Branding</span>
                    </a>
                    <a class="nav-link @if (request('pills') == 'sosial-media') active @endif mb-2 d-flex align-items-center"
                        href="{{ route('setting.index') }}?pills=sosial-media">
                        <div class="nav-icon-box"><i class="fas fa-share-alt"></i></div>
                        <span>Media Sosial</span>
                    </a>
                    <a class="nav-link @if (request('pills') == 'payment') active @endif mb-2 d-flex align-items-center"
                        href="{{ route('setting.index') }}?pills=payment">
                        <div class="nav-icon-box"><i class="fas fa-credit-card"></i></div>
                        <span>Gateway Pembayaran</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-midnight text-white">
            <div class="card-body p-4">
                <div class="text-center">
                    <img src="{{ Storage::url($setting->path_image ?? '') }}" alt="Logo" class="img-fluid rounded shadow-sm bg-white p-2 mb-3" style="max-height: 100px;">
                    <h6 class="font-weight-bold mb-1">{{ $setting->company_name }}</h6>
                    <p class="text-xs opacity-7 mb-0">{{ $setting->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: FORMS -->
    <div class="col-xl-9 col-lg-8">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-0">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade @if (request('pills') == '') show active @endif">
                        @includeIf('setting.general')
                    </div>
                    <div class="tab-pane fade @if (request('pills') == 'logo') show active @endif">
                        @includeIf('setting.logo')
                    </div>
                    <div class="tab-pane fade @if (request('pills') == 'sosial-media') show active @endif">
                        @includeIf('setting.sosial_media')
                    </div>
                    <div class="tab-pane fade @if (request('pills') == 'payment') show active @endif">
                        @includeIf('setting.payment')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* PREMIUM COLORS - MIDNIGHT */
    .bg-gradient-midnight { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; }
    .bg-midnight { background: #1e293b !important; }
    .text-midnight { color: #1e293b !important; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Vertical Nav Pills Premium */
    .premium-nav-vertical .nav-link {
        border-radius: 12px;
        padding: 12px 15px;
        color: #64748b;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .premium-nav-vertical .nav-link:hover {
        background: #f8fafc;
        color: #1e293b;
        transform: translateX(5px);
    }
    .premium-nav-vertical .nav-link.active {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .nav-icon-box {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: #f1f5f9; margin-right: 12px; transition: all 0.3s;
    }
    .premium-nav-vertical .nav-link.active .nav-icon-box {
        background: #1e293b; color: #fff;
    }
    
    /* Overriding card in includes Refined */
    .tab-pane { padding: 25px; }
    .tab-pane .card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
    .tab-pane .card-header { display: none !important; }
    .tab-pane .card-body { padding: 0 !important; }
    .tab-pane .card-footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 0; margin-top: 20px; }
    
    .form-control { 
        border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 10px 15px; height: auto;
        font-weight: 500; color: #1e293b; font-size: 0.9rem;
    }
    .form-control:focus { border-color: #1e293b; box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1); }
    label { font-weight: 700; color: #475569; font-size: 0.8rem; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endpush

@includeIf('includes.summernote')
<x-toast />
