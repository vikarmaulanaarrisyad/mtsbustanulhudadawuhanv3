@extends('layouts.ppdb')

@section('title', 'Dashboard PPDB')

@section('content')


    @if(!$ppdbOpen && !$registrant)
        {{-- PPDB BELUM BUKA --}}
        <div class="ppdb-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Pendaftaran PPDB Belum Dibuka</h5>
                <p class="text-muted">Silakan tunggu informasi pembukaan pendaftaran dari sekolah.</p>
            </div>
        </div>


    @elseif($registrant || $ppdbOpen)
        {{-- PROGRESS STEPPER GLOBAL (HANYA UNTUK PENDAFTAR BELUM JADI SISWA) --}}
        <div class="stu-card mb-4 overflow-hidden border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="p-4" style="background: linear-gradient(135deg, #0b8c89 0%, #15b3af 100%);">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div>
                            <h5 class="mb-1 font-weight-bold">Halo, {{ $user->name }}!</h5>
                            <p class="mb-0 opacity-75 small"><i class="fas fa-map-marker-alt mr-1"></i> Selamat datang di portal pendaftaran madrasah digital.</p>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-light px-3 py-2 rounded-pill font-weight-bold" style="color: #0b8c89; font-size: 11px;">
                                @if($currentStep == 1) TAHAP 1: BIODATA @elseif($currentStep == 2) TAHAP 2: BERKAS @elseif($currentStep == 3) TAHAP 3: SELEKSI @else TAHAP 4: PENGUMUMAN @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white">
                    <div class="ppdb-main-stepper d-flex align-items-center justify-content-between">
                        <div class="step-item {{ $currentStep >= 1 ? 'active' : '' }}">
                            <div class="step-icon"><i class="fas fa-user-edit"></i></div>
                            <div class="step-label">Biodata</div>
                        </div>
                        <div class="step-line {{ $currentStep > 1 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 2 ? 'active' : '' }}">
                            <div class="step-icon"><i class="fas fa-file-upload"></i></div>
                            <div class="step-label">Berkas</div>
                        </div>
                        <div class="step-line {{ $currentStep > 2 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 3 ? 'active' : '' }}">
                            <div class="step-icon"><i class="fas fa-search"></i></div>
                            <div class="step-label">Seleksi</div>
                        </div>
                        <div class="step-line {{ $currentStep > 3 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 4 ? 'active' : '' }}">
                            <div class="step-icon"><i class="fas fa-award"></i></div>
                            <div class="step-label">Pengumuman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .ppdb-main-stepper .step-item { display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; flex: 1; }
            .ppdb-main-stepper .step-icon { width: 45px; height: 45px; border-radius: 14px; background: #f1f5f9; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 8px; transition: 0.4s; border: 2px solid #e2e8f0; }
            .ppdb-main-stepper .step-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
            .ppdb-main-stepper .step-line { flex: 1; height: 4px; background: #f1f5f9; margin-top: -30px; border-radius: 10px; position: relative; z-index: 1; }
            .ppdb-main-stepper .step-item.active .step-icon { background: #0b8c89; color: white; border-color: #0b8c89; box-shadow: 0 5px 15px rgba(11, 140, 137, 0.25); }
            .ppdb-main-stepper .step-item.active .step-label { color: #0b8c89; }
            .ppdb-main-stepper .step-line.active { background: #0b8c89; }
        </style>

        @if(!$registrant)
        {{-- PREMIUM HEADER FOR APPLICANTS --}}
        <div class="stu-new-header mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="stu-avatar-box mr-3">
                        <div class="stu-avatar-text">{{ substr($user->name, 0, 2) }}</div>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">CALON SISWA BARU</p>
                        <h4 class="text-white font-weight-bold mb-1">{{ $user->name }}</h4>
                        <div class="d-flex align-items-center">
                            <span class="stu-dot mr-1" style="background: #fbbf24;"></span>
                            <span class="text-white-50 font-weight-bold text-uppercase" style="font-size: 10px;">PROSES PENDAFTARAN</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form-main" class="d-inline">
                        @csrf
                        <div class="stu-header-icon bg-danger-soft" onclick="confirmLogout()">
                            <i class="fas fa-power-off"></i>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="stu-stat-new glass-card w-100 py-3">
                <div class="d-flex align-items-center">
                    <div class="stu-stat-icon-box bg-white-soft mr-3">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 10px;">ID PENDAFTARAN</p>
                        <h5 class="text-white font-weight-bold mb-0">BARU / {{ now()->year }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="stu-content-wrapper">
            {{-- PREMIUM BANNER SLIDER --}}
            <div class="stu-banner-wrapper">
                <div id="bannerCarouselApp" class="carousel slide stu-banner-card" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @php $totalSlides = $announcements->count(); @endphp
                        @for($i = 0; $i < ($totalSlides ?: 1); $i++)
                            <li data-target="#bannerCarouselApp" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                        @endfor
                    </ol>
                    <div class="carousel-inner">
                        @if($announcements->isNotEmpty())
                            @foreach($announcements as $idx => $ann)
                                <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                                    <div class="stu-banner-item" style="background: linear-gradient(135deg, {{ $idx % 2 == 0 ? '#065f46, #10b981' : '#1e3a8a, #3b82f6' }});">
                                        <h3 class="stu-banner-title">{{ $ann->title }}</h3>
                                        <p class="stu-banner-text">{{ strip_tags($ann->content) }}</p>
                                        <button class="stu-banner-btn" onclick='showAnnouncement(@json($ann))'>Lihat Detail</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="stu-banner-item">
                                    <h3 class="stu-banner-title">Selamat Datang di Madrasah Digital</h3>
                                    <p class="stu-banner-text">Silakan lengkapi pendaftaran Anda untuk bergabung bersama kami.</p>
                                    <button class="stu-banner-btn">Mulai Pendaftaran</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- FORM BIODATA --}}
            @include('ppdb.form-biodata', ['action' => route('ppdb.store_biodata'), 'method' => 'POST'])
        </div>

        {{-- PREMIUM BOTTOM NAVIGATION FOR APPLICANTS --}}
        <div class="stu-bottom-nav">
            <a href="{{ route('ppdb.dashboard') }}" class="nav-item active">
                <i class="fas fa-th-large"></i>
                <span>HOME</span>
            </a>
            <a href="#section-berkas" class="nav-item">
                <i class="fas fa-folder-open"></i>
                <span>BERKAS</span>
            </a>
            <div class="stu-fab" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </div>
            <a href="https://wa.me/628123456789" target="_blank" class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>BANTUAN</span>
            </a>
            <a href="javascript:void(0)" onclick="confirmLogout()" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>KELUAR</span>
            </a>
        </div>

    @else
        {{-- PREMIUM HEADER FOR REGISTERED APPLICANTS --}}
        <div class="stu-new-header mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <div class="stu-avatar-box mr-3">
                        <div class="stu-avatar-text">{{ substr($user->name, 0, 2) }}</div>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">PENDAFTAR TERVERIFIKASI</p>
                        <h4 class="text-white font-weight-bold mb-1">{{ $user->name }}</h4>
                        <div class="d-flex align-items-center">
                            <span class="stu-dot mr-1"></span>
                            <span class="text-white-50 font-weight-bold text-uppercase" style="font-size: 10px;">{{ $registrant->public_status_label }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form-main" class="d-inline">
                        @csrf
                        <div class="stu-header-icon bg-danger-soft" onclick="confirmLogout()">
                            <i class="fas fa-power-off"></i>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="stu-stat-new glass-card w-100 py-3">
                <div class="d-flex align-items-center">
                    <div class="stu-stat-icon-box bg-white-soft mr-3">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 10px;">NOMOR REGISTRASI</p>
                        <h5 class="text-white font-weight-bold mb-0">{{ $registrant->registration_number ?? 'MENGALOKASI...' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="stu-content-wrapper">
            {{-- PREMIUM BANNER SLIDER --}}
            <div class="stu-banner-wrapper">
                <div id="bannerCarouselReg" class="carousel slide stu-banner-card" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @php $totalSlides = $announcements->count(); @endphp
                        @for($i = 0; $i < ($totalSlides ?: 1); $i++)
                            <li data-target="#bannerCarouselReg" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                        @endfor
                    </ol>
                    <div class="carousel-inner">
                        @if($announcements->isNotEmpty())
                            @foreach($announcements as $idx => $ann)
                                <div class="carousel-item {{ $idx == 0 ? 'active' : '' }}">
                                    <div class="stu-banner-item" style="background: linear-gradient(135deg, {{ $idx % 2 == 0 ? '#065f46, #10b981' : '#1e3a8a, #3b82f6' }});">
                                        <h3 class="stu-banner-title">{{ $ann->title }}</h3>
                                        <p class="stu-banner-text">{{ strip_tags($ann->content) }}</p>
                                        <button class="stu-banner-btn" onclick='showAnnouncement(@json($ann))'>Lihat Detail</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="stu-banner-item">
                                    <h3 class="stu-banner-title">Selamat Datang di Madrasah Digital</h3>
                                    <p class="stu-banner-text">Pantau status pendaftaran Anda secara berkala di sini.</p>
                                    <button class="stu-banner-btn">Lihat Status</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STATUS PENDAFTARAN --}}
            @include('ppdb.status')
        </div>

        {{-- PREMIUM BOTTOM NAVIGATION FOR REGISTERED APPLICANTS --}}
        <div class="stu-bottom-nav">
            <a href="{{ route('ppdb.dashboard') }}" class="nav-item active">
                <i class="fas fa-th-large"></i>
                <span>HOME</span>
            </a>
            <a href="#section-berkas" class="nav-item">
                <i class="fas fa-folder-open"></i>
                <span>BERKAS</span>
            </a>
            <div class="stu-fab" onclick="window.location.reload()">
                <i class="fas fa-redo"></i>
            </div>
            <a href="https://wa.me/628123456789" target="_blank" class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>BANTUAN</span>
            </a>
            <a href="javascript:void(0)" onclick="confirmLogout()" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>KELUAR</span>
            </a>
        </div>
    @endif
    @endif


@push('css')
<style>
    /* ========== PREMIUM STUDENT DASHBOARD STYLES (TEACHER STYLE) ========== */

    /* New Premium Header */
    .stu-new-header {
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        margin: -20px -15px 30px -15px;
        padding: 40px 25px 100px 25px;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
        position: relative;
    }
    .stu-avatar-box {
        width: 60px; height: 60px;
        border-radius: 18px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        border: 2px solid rgba(255,255,255,0.3);
        overflow: hidden;
    }
    .stu-avatar-box img { width: 100%; height: 100%; object-fit: cover; }
    .stu-avatar-text { color: white; font-weight: 800; font-size: 1.5rem; }
    
    .stu-nip-box {
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.1);
        min-width: 100px;
    }
    .stu-dot {
        width: 6px; height: 6px;
        background: #34d399;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 10px #34d399;
    }
    .stu-header-icon {
        width: 45px; height: 45px;
        border-radius: 15px;
        background: rgba(255,255,255,0.15);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .stu-header-icon:hover { background: rgba(255,255,255,0.25); transform: translateY(-2px); }
    .bg-danger-soft { background: rgba(239, 68, 68, 0.2) !important; }

    /* New Stat Cards */
    .stu-stat-new {
        display: flex; align-items: center;
        padding: 20px;
        border-radius: 25px;
        gap: 15px;
        transition: transform 0.2s;
    }
    .stu-stat-new.white-card {
        background: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .stu-stat-new.glass-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .stu-stat-icon-box {
        width: 45px; height: 45px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
    }
    .bg-success-soft { background: #ecfdf5; color: #10b981; }
    .bg-white-soft { background: rgba(255,255,255,0.2); color: white; }

    /* Main Action Card */
    .stu-main-card {
        background: white;
        border-radius: 35px;
        padding: 30px;
        margin-top: -60px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        position: relative;
        z-index: 5;
    }
    .stu-action-icon {
        width: 60px; height: 60px;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
    }
    .stu-btn-absen {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 18px;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }
    .stu-btn-absen:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(16, 185, 129, 0.4); color: white; }

    /* Bottom Navigation Bar (Dark Style) */
    .stu-bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0;
        background: #1e293b;
        height: 85px;
        display: flex; justify-content: space-around; align-items: center;
        padding: 0 10px 20px 10px;
        z-index: 1000;
        border-top-left-radius: 35px;
        border-top-right-radius: 35px;
        box-shadow: 0 -5px 25px rgba(0,0,0,0.2);
    }
    .stu-bottom-nav .nav-item {
        display: flex; flex-direction: column; align-items: center;
        color: #94a3b8; text-decoration: none; font-size: 10px;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        transition: all 0.2s;
        flex: 1;
    }
    .stu-bottom-nav .nav-item.active { color: #10b981; }
    .stu-bottom-nav .nav-item i { font-size: 1.4rem; margin-bottom: 5px; transition: transform 0.2s; }
    .stu-bottom-nav .nav-item:hover i { transform: translateY(-3px); }
    
    .stu-fab {
        width: 68px; height: 68px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 2rem;
        margin-top: -65px;
        border: 7px solid #1e293b;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        transition: all 0.3s;
        cursor: pointer;
    }
    .stu-fab:hover { transform: scale(1.1) rotate(5deg); color: white; }

    /* Standard Cards Enhancement */
    .stu-card {
        background: white;
        border-radius: 25px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .stu-card-header {
        padding: 22px 25px;
        display: flex; align-items: center; gap: 15px;
    }
    .stu-card-icon {
        width: 45px; height: 45px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    .stu-card-title { font-weight: 800; font-size: 1.05rem; color: #1e293b; margin: 0; }
    .stu-card-sub { font-size: 0.8rem; color: #64748b; margin-top: 2px; }

    /* Quick Links Grid */
    .stu-quick-link {
        display: flex; flex-direction: column; align-items: center;
        text-decoration: none !important; transition: all 0.2s;
    }
    .stu-quick-link:hover { transform: translateY(-3px); }
    .stu-quick-link span { 
        font-size: 11px; font-weight: 700; color: #475569; 
        margin-top: 8px; text-transform: uppercase; letter-spacing: 0.3px;
    }
    .stu-quick-icon {
        width: 55px; height: 55px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    
    .bg-soft-purple { background: #f5f3ff; color: #8b5cf6; }
    .bg-soft-green { background: #f0fdf4; color: #10b981; }
    .bg-soft-emerald { background: #ecfdf5; color: #059669; }
    .bg-soft-blue { background: #eff6ff; color: #3b82f6; }
    .bg-soft-orange { background: #fff7ed; color: #f59e0b; }
    .bg-soft-indigo { background: #eef2ff; color: #6366f1; }
    .bg-soft-pink { background: #fdf2f8; color: #ec4899; }
    .bg-soft-red { background: #fef2f2; color: #ef4444; }

    .stu-badge-live {
        background: #ef4444; color: white;
        padding: 2px 8px; border-radius: 6px;
        font-size: 10px; font-weight: 800;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    body { background-color: #f8fafc; padding-bottom: 120px; }

    .container-fluid { max-width: 1200px; }

    /* Agenda/Schedule List */
    .stu-schedule-day { border-bottom: 1px solid #f1f5f9; }
    .stu-schedule-day:last-child { border-bottom: none; }
    .stu-schedule-day-header {
        display: flex; justify-content: space-between;
        align-items: center; padding: 18px 25px;
        cursor: pointer; transition: background 0.15s;
    }
    .stu-schedule-day-header:hover { background: #fafafa; }

    /* Attendance Premium UI */
    .stu-attend-card-inner {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .stu-clock-live {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -1px;
        margin-bottom: 5px;
    }
    .stu-btn-attend-main {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 18px;
        padding: 15px 25px;
        font-weight: 800;
        width: 100%;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        transition: all 0.3s;
    }
    .stu-btn-attend-main:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(16, 185, 129, 0.3); color: white; }
    
    .stu-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 15px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .status-success-light { background: #dcfce7; color: #166534; }
    .status-warning-light { background: #fef3c7; color: #92400e; }
    
    .stu-checkin-time {
        font-size: 1.2rem;
        font-weight: 800;
        color: #059669;
        margin-top: 10px;
    }

    /* Class Info Premium */
    .stu-teacher-card {
        background: white;
        border-radius: 20px;
        padding: 18px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border: 1px solid #eef2ff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.05);
        transition: all 0.2s;
    }
    .stu-teacher-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.1); }
    
    .stu-teacher-avatar {
        width: 50px; height: 50px;
        border-radius: 15px;
        background: #eff6ff;
        display: flex; align-items: center; justify-content: center;
        color: #3b82f6; font-size: 1.3rem;
        margin-right: 15px;
    }
    .stu-class-badge {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 6px 18px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
    }
    .stu-status-active {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .stu-dot-pulse {
        width: 6px; height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .stu-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .stu-info-item-small {
        background: #f8fafc;
        padding: 15px;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .stu-info-item-small:hover { background: #fff; border-color: #e2e8f0; }
    
    .stu-info-item-small label {
        display: block;
        font-size: 9px;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 800;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    .stu-info-item-small span {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
    }

    /* Announcement Banner Slider */
    .stu-banner-wrapper {
        margin: -100px 0 25px 0;
        position: relative;
        z-index: 20;
    }
    .stu-banner-card {
        border-radius: 30px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        height: 180px;
    }
    .stu-banner-item {
        height: 180px;
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        padding: 30px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        position: relative;
    }
    .stu-banner-item::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .stu-banner-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.2;
        max-width: 70%;
    }
    .stu-banner-text {
        font-size: 0.9rem;
        opacity: 0.9;
        max-width: 60%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .stu-banner-btn {
        margin-top: 15px;
        background: white;
        color: #059669;
        border: none;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        width: fit-content;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .carousel-indicators { bottom: 10px; }
    .carousel-indicators li {
        width: 8px; height: 8px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255,255,255,0.5);
        border: none;
    }
    .carousel-indicators .active { background-color: white; width: 20px; border-radius: 10px; }

    /* Full Screen Top Reset */
    .ppdb-navbar { display: none !important; }
    .ppdb-container { padding: 0 !important; max-width: 100% !important; margin: 0 !important; }
    body { background: #f8fafc; }

    @media (max-width: 768px) {
        .stu-new-header { border-radius: 0 0 40px 40px; margin-top: 0; padding-top: 40px; }
        .stu-main-card { margin-top: -70px; border-radius: 35px; }
        .stu-card-title { font-size: 0.95rem; }
    }

    @media (min-width: 769px) {
        .stu-new-header { border-radius: 0 0 50px 50px; margin-top: 0; padding-top: 50px; }
        .stu-main-card { margin-top: -80px; }
    }
    
    .stu-new-header {
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        padding-left: 25px;
        padding-right: 25px;
        padding-bottom: 120px;
        position: relative;
    }
    
    .stu-content-wrapper {
        padding-left: 20px;
        padding-right: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .bg-grad-green { background: linear-gradient(135deg, #065f46, #10b981); }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }
</style>
@endpush

@endsection



@push('scripts')
<script>
    function showAnnouncement(ann) {
        $('#announcementDetailTitle').text(ann.title);
        $('#announcementContent').html(ann.content);
        
        let date = new Date(ann.created_at);
        let options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        $('#announcementDate').text(date.toLocaleDateString('id-ID', options));
        
        $('#modalAnnouncement').modal('show');
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar Aplikasi?',
            text: 'Apakah Anda yakin ingin keluar dari akun ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt mr-1"></i> Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const logoutForm = document.getElementById('logout-form-main');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    // Fallback if form not found
                    const genericForm = document.createElement('form');
                    genericForm.method = 'POST';
                    genericForm.action = '{{ route("logout") }}';
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    genericForm.appendChild(csrfToken);
                    document.body.appendChild(genericForm);
                    genericForm.submit();
                }
            }
        });
    }

    $(document).ready(function() {
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });

        // Auto-resume logic: scroll to upload section if biodata is done but documents are pending
        @if(isset($currentStep) && $currentStep == 2)
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('#upload-section').offset()?.top - 100 || 500
                }, 1000);
            }, 500);
        @endif

        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
