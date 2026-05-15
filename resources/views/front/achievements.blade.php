@extends('layouts.front')

@section('title', 'Galeri Prestasi Siswa')

@push('css')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.4);
        --accent-color: #0b8c89;
        --accent-gradient: linear-gradient(135deg, #0b8c89 0%, #14b8a6 100%);
    }

    .achievement-hero {
        background: linear-gradient(135deg, #083344 0%, #0b8c89 100%);
        padding: 140px 0 100px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .achievement-hero::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -10%;
        width: 120%;
        height: 200px;
        background: #f8fafc;
        transform: rotate(-3deg);
        z-index: 1;
    }

    .hero-mesh {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: radial-gradient(at 0% 0%, hsla(177,88%,24%,1) 0, transparent 50%), 
                          radial-gradient(at 50% 0%, hsla(187,92%,30%,1) 0, transparent 50%), 
                          radial-gradient(at 100% 0%, hsla(177,88%,24%,1) 0, transparent 50%);
        opacity: 0.6;
    }

    .filter-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .filter-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    .achievement-card {
        border: 1px solid #f1f5f9;
        border-radius: 30px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .achievement-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 60px rgba(11, 140, 137, 0.15);
        border-color: rgba(11, 140, 137, 0.2);
    }

    .achievement-img-wrapper {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .achievement-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.2s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .achievement-card:hover .achievement-img-wrapper img {
        transform: scale(1.15);
    }

    .category-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        color: #0b8c89;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .achievement-body {
        padding: 28px;
        flex-grow: 1;
    }

    .medal-icon {
        width: 45px;
        height: 45px;
        background: var(--accent-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-bottom: 20px;
        box-shadow: 0 8px 20px rgba(11, 140, 137, 0.3);
    }

    .rank-text {
        color: #0b8c89;
        font-weight: 800;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
        display: block;
    }

    .student-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .achievement-title {
        color: #475569;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .achievement-footer {
        padding: 20px 28px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #f1f5f9;
    }

    .footer-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
    }

    .footer-item i {
        color: #0b8c89;
        font-size: 0.9rem;
    }

    .pagination .page-link {
        border-radius: 12px !important;
        margin: 0 5px;
        color: #0b8c89;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        font-weight: 700;
    }

    .pagination .active .page-link {
        background: var(--accent-gradient);
        color: white;
    }

    @media (max-width: 768px) {
        .achievement-hero { padding: 100px 0 80px; }
        .filter-card { padding: 20px; border-radius: 20px; }
        .achievement-card { border-radius: 24px; }
        .achievement-img-wrapper { height: 200px; }
    }

    /* Shimmer Effect for Loading (Optional) */
    .shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
</style>
@endpush

@section('content')
<div class="achievement-hero">
    <div class="hero-mesh"></div>
    <div class="container position-relative z-index-2">
        <span class="badge badge-pill badge-light text-primary font-weight-bold px-3 py-2 mb-3 animate__animated animate__fadeInDown">
            ✨ HALL OF FAME
        </span>
        <h1 class="display-3 font-weight-extrabold animate__animated animate__fadeInDown" style="letter-spacing: -2px;">
            Galeri <span class="text-info">Prestasi</span>
        </h1>
        <p class="lead opacity-8 mx-auto animate__animated animate__fadeInUp" style="max-width: 600px;">
            Menampilkan jejak prestasi dan dedikasi siswa-siswi terbaik kami di berbagai ajang kompetisi.
        </p>
    </div>
</div>

<div class="container pb-5">
    <div class="row filter-wrapper">
        <div class="col-lg-11 mx-auto">
            <div class="filter-card">
                <form action="{{ route('front.achievements') }}" method="GET" id="filterForm">
                    <div class="row align-items-center">
                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <h5 class="font-weight-extrabold mb-0 text-dark">
                                <i class="fas fa-search-plus mr-2 text-info"></i> Telusuri
                            </h5>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0 rounded-left-pill pl-3">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </span>
                                </div>
                                <select name="academic_year" class="form-control form-control-lg bg-light border-0 rounded-right-pill font-weight-bold text-dark px-2" onchange="this.form.submit()">
                                    <option value="">Tahun Pelajaran</option>
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}" {{ request('academic_year') == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-0 rounded-left-pill pl-3">
                                        <i class="fas fa-layer-group text-muted"></i>
                                    </span>
                                </div>
                                <select name="category" class="form-control form-control-lg bg-light border-0 rounded-right-pill font-weight-bold text-dark px-2" onchange="this.form.submit()">
                                    <option value="">Semua Bidang</option>
                                    <option value="Akademik" {{ request('category') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="Non-Akademik" {{ request('category') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                    <option value="Keagamaan" {{ request('category') == 'Keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                                    <option value="Olahraga" {{ request('category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                                </select>
                            </div>
                        </div>
                        @if(request('academic_year') || request('category'))
                        <div class="col-lg-1 mt-3 mt-lg-0">
                            <a href="{{ route('front.achievements') }}" class="btn btn-light btn-circle shadow-sm text-danger" title="Reset Filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5 pt-4">
        @forelse($achievements as $ach)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-5" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 150 }}">
                <div class="achievement-card">
                    <div class="achievement-img-wrapper">
                        <span class="category-badge">{{ $ach->category }}</span>
                        @if($ach->image)
                            <img src="{{ Storage::url($ach->image) }}" alt="{{ $ach->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1470" alt="Default">
                        @endif
                        <div class="img-overlay"></div>
                    </div>
                    <div class="achievement-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="medal-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <span class="rank-text">{{ $ach->rank }}</span>
                        </div>
                        <h4 class="student-name">{{ $ach->student->nama_lengkap ?? ($ach->student_name ?: '-') }}</h4>
                        <p class="achievement-title">{{ $ach->title }}</p>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $ach->event_name }}
                        </small>
                    </div>
                    <div class="achievement-footer">
                        <div class="footer-item">
                            <i class="fas fa-history"></i>
                            {{ $ach->academicYear->academic_year ?? $ach->year }}
                        </div>
                        <div class="footer-item">
                            <i class="fas fa-trophy"></i>
                            {{ $ach->level }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-30 shadow-sm border border-light">
                    <img src="https://illustrations.popsy.co/teal/falling.svg" alt="No data" style="width: 250px;" class="mb-4 opacity-50">
                    <h3 class="font-weight-extrabold text-dark">Data Tidak Ditemukan</h3>
                    <p class="text-muted mb-4">Sepertinya belum ada prestasi yang tercatat untuk kriteria ini.</p>
                    <a href="{{ route('front.achievements') }}" class="btn btn-info rounded-pill px-5 py-3 font-weight-bold">Tampilkan Semua</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5 pt-3">
        {{ $achievements->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true,
            easing: 'ease-out-back'
        });
    });
</script>
@endpush
