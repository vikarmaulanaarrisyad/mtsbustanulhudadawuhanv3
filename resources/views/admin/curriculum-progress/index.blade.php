@extends($layout)

@section('title', 'Progress Kurikulum')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-chart-line mr-2 animate__animated animate__fadeInLeft"></i> 
                            Monitoring Progress Kurikulum
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau sejauh mana penyampaian materi pelajaran di setiap kelas dibandingkan dengan target yang ditetapkan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chart-pie fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="row mb-4">
    <div class="col-12">
        <form action="{{ route('admin.curriculum-progress.index') }}" method="GET">
            <div class="card shadow-sm border-0 premium-card">
                <div class="card-body p-3">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran</label>
                                <select name="academic_year_id" class="form-control rounded-pill border-2">
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Semester</label>
                                <select name="semester" class="form-control rounded-pill border-2">
                                    <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Ganjil</option>
                                    <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-emerald btn-block shadow-sm font-weight-bold px-4 btn-premium">
                                <i class="fas fa-sync-alt mr-1"></i> UPDATE TAMPILAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- PROGRESS CARDS -->
<div class="row">
    @forelse($progressData as $subject)
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 premium-card h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="avatar-sm mr-3 bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald font-weight-bold" style="width:40px;height:40px;background:#ecfdf5;color:#10b981;">
                        {{ substr($subject['subject_name'], 0, 1) }}
                    </div>
                    <h5 class="mb-0 font-weight-bold text-dark">{{ $subject['subject_name'] }}</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($subject['classes'] as $class)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold text-muted text-sm">{{ $class['class_name'] }}</span>
                                <span class="badge {{ $class['percentage'] >= 100 ? 'badge-success' : ($class['percentage'] > 50 ? 'badge-info' : 'badge-warning') }} rounded-pill px-3">
                                    {{ $class['completed'] }} / {{ $class['total'] }} Materi
                                </span>
                            </div>
                            <div class="progress rounded-pill shadow-inner" style="height: 12px; background: #f1f5f9;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-emerald" 
                                     role="progressbar" 
                                     style="width: {{ $class['percentage'] }}%; border-radius: 50px;" 
                                     aria-valuenow="{{ $class['percentage'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="text-right mt-1">
                                <small class="font-weight-bold {{ $class['percentage'] >= 100 ? 'text-success' : 'text-primary' }}">
                                    {{ $class['percentage'] }}% Selesai
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty-state">
                <i class="fas fa-folder-open fa-5x text-muted mb-4 opacity-3"></i>
                <h4 class="font-weight-bold text-muted">Belum Ada Data Progress</h4>
                <p class="text-muted">Pastikan Target Kurikulum sudah diisi dan Guru sudah mengisi Jurnal KBM.</p>
                <a href="{{ route('admin.curriculum-targets.index') }}" class="btn btn-emerald rounded-pill px-5 mt-3 shadow-emerald-light">
                    ISI TARGET SEKARANG
                </a>
            </div>
        </div>
    @endforelse
</div>

<style>
    /* Premium Design System */
    .bg-gradient-emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .btn-emerald { background: #10b981; color: #fff; border-radius: 50px; }
    .btn-emerald:hover { background: #059669; color: #fff; transform: translateY(-2px); }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(16,185,129,0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-3 { opacity: 0.3; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 20px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important; }
    
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    
    .progress-bar-animated { animation: progress-bar-stripes 1s linear infinite; }
</style>
@endsection
