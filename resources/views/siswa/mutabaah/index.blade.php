@extends('layouts.app')

@section('title', 'Ibadah Saya')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0 font-weight-bold"><i class="fas fa-pray mr-2 text-success"></i> Ibadah Saya</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Ibadah Saya</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- KPI CARDS -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm" style="border-radius:15px;border-left:5px solid #059669 !important;">
                    <div class="card-body p-3 text-center">
                        <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Shalat Bulan Ini</p>
                        <h2 class="font-weight-bold text-success mb-0">{{ $percentage }}%</h2>
                        <small class="text-muted">{{ $totalShalat }}/{{ $maxShalat }} waktu</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm" style="border-radius:15px;border-left:5px solid #3b82f6 !important;">
                    <div class="card-body p-3 text-center">
                        <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Hari Tercatat</p>
                        <h2 class="font-weight-bold text-primary mb-0">{{ $totalDays }}</h2>
                        <small class="text-muted">hari mutabaah</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm" style="border-radius:15px;border-left:5px solid #7c3aed !important;">
                    <div class="card-body p-3 text-center">
                        <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Surat Dihafal</p>
                        <h2 class="font-weight-bold mb-0" style="color:#7c3aed;">{{ $totalSurah }}</h2>
                        <small class="text-muted">surat (ziyadah)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm" style="border-radius:15px;border-left:5px solid #f59e0b !important;">
                    <div class="card-body p-3 text-center">
                        <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Hari Ini</p>
                        @if($todayLog)
                            @php $todayScore = $todayLog->shubuh + $todayLog->zhuhur + $todayLog->ashar + $todayLog->maghrib + $todayLog->isya + $todayLog->dhuha + $todayLog->tahajud; @endphp
                            <h2 class="font-weight-bold text-warning mb-0">{{ $todayScore }}/7</h2>
                        @else
                            <h2 class="font-weight-bold text-muted mb-0">-</h2>
                        @endif
                        <small class="text-muted">ibadah tercatat</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- KALENDER 30 HARI -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-calendar-alt mr-2 text-success"></i> Kalender Ibadah (30 Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap" style="gap:6px;">
                            @foreach($calendarDays as $day)
                                @php
                                    $pct = $day['max'] > 0 ? ($day['score'] / $day['max']) * 100 : 0;
                                    $bg = $day['score'] == 0 ? '#f1f5f9' : ($pct >= 70 ? '#dcfce7' : ($pct >= 40 ? '#fef9c3' : '#fee2e2'));
                                    $textColor = $day['score'] == 0 ? '#94a3b8' : ($pct >= 70 ? '#166534' : ($pct >= 40 ? '#854d0e' : '#991b1b'));
                                    $border = $day['is_today'] ? 'border:2px solid #059669;' : 'border:1px solid #e2e8f0;';
                                @endphp
                                <div style="width:52px;text-align:center;padding:8px 4px;border-radius:12px;background:{{ $bg }};{{ $border }}" title="{{ $day['date'] }}: {{ $day['score'] }}/{{ $day['max'] }}">
                                    <div style="font-size:0.6rem;font-weight:700;color:{{ $textColor }};opacity:0.6;">{{ $day['label'] }}</div>
                                    <div style="font-size:1rem;font-weight:900;color:{{ $textColor }};">{{ $day['day'] }}</div>
                                    <div style="font-size:0.6rem;font-weight:800;color:{{ $textColor }};">{{ $day['score'] }}/{{ $day['max'] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex align-items-center" style="gap:15px;font-size:0.7rem;">
                            <span><span style="display:inline-block;width:14px;height:14px;background:#dcfce7;border-radius:4px;border:1px solid #bbf7d0;"></span> Baik (≥70%)</span>
                            <span><span style="display:inline-block;width:14px;height:14px;background:#fef9c3;border-radius:4px;border:1px solid #fde68a;"></span> Cukup (40-69%)</span>
                            <span><span style="display:inline-block;width:14px;height:14px;background:#fee2e2;border-radius:4px;border:1px solid #fecaca;"></span> Kurang (<40%)</span>
                            <span><span style="display:inline-block;width:14px;height:14px;background:#f1f5f9;border-radius:4px;border:1px solid #e2e8f0;"></span> Belum Diisi</span>
                        </div>
                    </div>
                </div>

                <!-- DETAIL HARI INI -->
                @if($todayLog)
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-check-double mr-2 text-success"></i> Detail Ibadah Hari Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap" style="gap:10px;">
                            @foreach(['Shubuh'=>$todayLog->shubuh,'Zhuhur'=>$todayLog->zhuhur,'Ashar'=>$todayLog->ashar,'Maghrib'=>$todayLog->maghrib,'Isya'=>$todayLog->isya,'Dhuha'=>$todayLog->dhuha,'Tahajud'=>$todayLog->tahajud] as $name => $val)
                            <div style="padding:12px 20px;border-radius:14px;font-weight:800;font-size:0.85rem;background:{{ $val ? '#dcfce7' : '#f1f5f9' }};color:{{ $val ? '#166534' : '#94a3b8' }};">
                                <i class="fas {{ $val ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i> {{ $name }}
                            </div>
                            @endforeach
                        </div>
                        @if($todayLog->puasa)
                        <div class="mt-3"><span class="badge badge-info px-3 py-2"><i class="fas fa-moon mr-1"></i> Puasa: {{ $todayLog->puasa }}</span></div>
                        @endif
                        @if($todayLog->tadarus)
                        <div class="mt-2"><span class="badge badge-success px-3 py-2"><i class="fas fa-book-open mr-1"></i> Tadarus: {{ $todayLog->tadarus }}</span></div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- PROGRESS TAHFIDZ -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-book-quran mr-2" style="color:#7c3aed;"></i> Riwayat Tahfidz</h5>
                    </div>
                    <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                        @forelse($tahfidzLogs as $log)
                            <div class="d-flex align-items-center px-4 py-3 border-bottom" style="gap:12px;">
                                <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:900;font-size:0.85rem;
                                    background:{{ $log->type === 'ziyadah' ? '#dcfce7' : '#e0f2fe' }};
                                    color:{{ $log->type === 'ziyadah' ? '#166534' : '#0369a1' }};">
                                    {{ $log->grade }}
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div class="font-weight-bold text-dark text-sm" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $log->surah_name }}</div>
                                    <div class="text-muted" style="font-size:0.7rem;">
                                        {{ $log->verse_range ? 'Ayat '.$log->verse_range : '' }}
                                        {{ $log->juz ? '• Juz '.$log->juz : '' }}
                                        • {{ ucfirst($log->type) }}
                                    </div>
                                </div>
                                <div class="text-right" style="flex-shrink:0;">
                                    <div class="font-weight-bold text-sm" style="color:#7c3aed;">{{ $log->tajwid_score }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;">{{ \Carbon\Carbon::parse($log->date)->format('d M') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-book fa-2x mb-2 d-block" style="opacity:0.3;"></i>
                                <p class="mb-0">Belum ada riwayat tahfidz</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
