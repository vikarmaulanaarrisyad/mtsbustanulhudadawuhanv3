@extends('layouts.front')

@section('title', 'Galeri Prestasi Siswa')

@push('css')
<style>
    .achievement-hero {
        background: linear-gradient(135deg, #0b8c89 0%, #14b8a6 100%);
        padding: 120px 0 80px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .achievement-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(-30deg);
    }
    .achievement-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .achievement-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 50px rgba(11, 140, 137, 0.15);
    }
    .achievement-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .achievement-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .achievement-card:hover .achievement-img-wrapper img {
        transform: scale(1.1);
    }
    .category-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(11, 140, 137, 0.9);
        backdrop-filter: blur(10px);
        color: white;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        z-index: 2;
    }
    .achievement-body {
        padding: 25px;
    }
    .rank-tag {
        display: inline-block;
        color: #0b8c89;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    .student-name {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    .event-name {
        color: #64748b;
        font-size: 0.85rem;
        line-height: 1.5;
    }
    .achievement-footer {
        padding: 15px 25px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .year-pill {
        background: white;
        color: #64748b;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }
    .level-pill {
        color: #0b8c89;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .filter-wrapper {
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: none;
    }
</style>
@endpush

@section('content')
<div class="achievement-hero">
    <div class="container">
        <h1 class="display-3 font-weight-bold animate__animated animate__fadeInDown">Galeri Prestasi</h1>
        <p class="lead opacity-8 animate__animated animate__fadeInUp animate__delay-1s">Membangun kebanggaan melalui pencapaian luar biasa siswa-siswi terbaik kami.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row filter-wrapper">
        <div class="col-lg-10 mx-auto">
            <div class="card filter-card">
                <form action="{{ route('front.achievements') }}" method="GET">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-filter mr-2 text-success"></i> Temukan Inspirasi</h5>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <select name="academic_year" class="form-control rounded-pill border-light bg-light px-4" onchange="this.form.submit()">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ request('academic_year') == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-control rounded-pill border-light bg-light px-4" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <option value="Akademik" {{ request('category') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="Non-Akademik" {{ request('category') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        @forelse($achievements as $ach)
            <div class="col-lg-4 col-md-6 mb-5" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="achievement-card">
                    <div class="achievement-img-wrapper">
                        <span class="category-badge">{{ $ach->category }}</span>
                        @if($ach->image)
                            <img src="{{ Storage::url($ach->image) }}" alt="{{ $ach->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1578574515318-47ad71ef539c?q=80&w=1470" alt="Default Achievement">
                        @endif
                    </div>
                    <div class="achievement-body">
                        <span class="rank-tag"><i class="fas fa-medal mr-1"></i> {{ $ach->rank }}</span>
                        <h4 class="student-name">{{ $ach->student->nama_lengkap ?? ($ach->student_name ?: '-') }}</h4>
                        <p class="event-name font-weight-bold text-dark mb-1">{{ $ach->title }}</p>
                        <p class="event-name mb-0">{{ $ach->event_name }}</p>
                    </div>
                    <div class="achievement-footer">
                        <span class="year-pill">TA {{ $ach->academicYear->academic_year ?? $ach->year }}</span>
                        <span class="level-pill"><i class="fas fa-globe-asia mr-1"></i> {{ $ach->level }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-light rounded-xl">
                    <i class="fas fa-trophy fa-4x text-muted mb-4 opacity-30"></i>
                    <h3 class="text-muted">Belum ada data prestasi yang ditemukan.</h3>
                    <p class="text-muted">Coba ubah filter pencarian Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $achievements->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
