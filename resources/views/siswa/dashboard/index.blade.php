@extends('layouts.ppdb')

@section('title', 'Dashboard Siswa')

@section('content')
    {{-- ========== DASHBOARD AKADEMIK SISWA - PREMIUM ========== --}}
    @php
        $now = \Carbon\Carbon::now();
        $currentDay = $now->translatedFormat('l');
        $currentTime = $now->format('H:i:s');
        $todaySchedule = $schedules[$currentDay] ?? collect();
        
        $ongoingSubject = null;
        $nextSubject = null;
        
        if($todaySchedule->isNotEmpty()) {
            foreach($todaySchedule as $sch) {
                if($currentTime >= $sch->start_time && $currentTime <= $sch->end_time) {
                    $ongoingSubject = $sch;
                    break;
                } elseif ($currentTime < $sch->start_time && !$nextSubject) {
                    $nextSubject = $sch;
                }
            }
        }
        
        $hour = $now->format('H');
        if ($hour < 11) {
            $greeting = 'Selamat Pagi';
            $greetingIcon = 'fa-sun text-warning';
        } elseif ($hour < 15) {
            $greeting = 'Selamat Siang';
            $greetingIcon = 'fa-cloud-sun text-primary';
        } elseif ($hour < 18) {
            $greeting = 'Selamat Sore';
            $greetingIcon = 'fa-cloud-sun text-info';
        } else {
            $greeting = 'Selamat Malam';
            $greetingIcon = 'fa-moon text-indigo';
        }

        $totalH = $attendanceStats['H'];
        $totalAll = $totalH + $attendanceStats['I'] + $attendanceStats['S'] + $attendanceStats['A'];
        $attPercentage = $totalAll > 0 ? round(($totalH / $totalAll) * 100) : 100;
        
        $attColor = 'success';
        if($attPercentage < 80) $attColor = 'danger';
        elseif($attPercentage < 90) $attColor = 'warning';
    @endphp

    {{-- PREMIUM HEADER --}}
    <div class="stu-new-header mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <div class="stu-avatar-box mr-3">
                    @if($student->profile && $student->profile->foto)
                        <img src="{{ asset('storage/' . $student->profile->foto) }}" alt="Foto">
                    @else
                        <div class="stu-avatar-text">{{ substr($student->nama_lengkap, 0, 2) }}</div>
                    @endif
                </div>
                <div>
                    <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">SELAMAT DATANG KEMBALI</p>
                    <h4 class="text-white font-weight-bold mb-1">{{ $student->nama_lengkap }}</h4>
                    <div class="d-flex align-items-center">
                        <div class="stu-nip-box px-2 py-1 mr-2">
                            <span class="text-white-50 d-block" style="font-size: 9px;">NISN</span>
                            <span class="text-white font-weight-bold" style="font-size: 11px;">{{ $student->nisn }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="stu-dot mr-1"></span>
                            <span class="text-white-50 font-weight-bold text-uppercase" style="font-size: 10px;">SISWA AKTIF</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="stu-header-icon mr-2 position-relative">
                    <i class="fas fa-bell"></i>
                    <span class="badge badge-danger badge-pill position-absolute" style="top: -5px; right: -5px; font-size: 9px;">2</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" id="logout-form-main" class="d-inline">
                    @csrf
                    <div class="stu-header-icon bg-danger-soft" onclick="confirmLogout()">
                        <i class="fas fa-power-off"></i>
                    </div>
                </form>
            </div>
        </div>

        {{-- TOP STATS CARDS --}}
        <div class="row">
            <div class="col-6">
                <div class="stu-stat-new white-card">
                    <div class="stu-stat-icon-box bg-success-soft">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-muted mb-0 font-weight-bold" style="font-size: 10px;">HADIR BULAN INI</p>
                        <h5 class="font-weight-bold text-success mb-0">{{ $attendanceStats['H'] }} Hari</h5>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="stu-stat-new glass-card">
                    <div class="stu-stat-icon-box bg-white-soft">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 10px;">JADWAL HARI INI</p>
                        <h5 class="text-white font-weight-bold mb-0">{{ $todaySchedule->count() }} Mapel</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN ACTION CARD (ABSENSI) --}}
    <div class="stu-main-card mb-4 shadow-lg">
        <div class="d-flex align-items-center mb-4">
            <div class="stu-action-icon bg-success-soft mr-3">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div>
                <h5 class="font-weight-bold mb-0 text-dark">Presensi Harian</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>

        @if(!$hasCheckedInToday)
            @if($isWorkDay && $isCheckInTime && !$isHoliday)
                <button type="button" onclick="submitAttendance()" class="btn btn-block stu-btn-absen">
                    ABSEN MASUK <i class="fas fa-arrow-right ml-2"></i>
                </button>
            @else
                <div class="alert alert-warning border-0 text-center py-3" style="border-radius: 15px;">
                    <i class="fas fa-info-circle mr-2"></i> {{ $attendanceMessage }}
                </div>
            @endif
        @else
            <div class="alert alert-success border-0 text-center py-3" style="border-radius: 15px;">
                <i class="fas fa-check-circle mr-2"></i> Anda sudah absen hari ini
            </div>
        @endif
    </div>

    {{-- CBT EXAM PORTAL BANNER --}}
    <div class="stu-main-card mb-4 shadow-lg p-0 overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);">
        <div class="p-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="stu-action-icon bg-white text-indigo-600 mr-3">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div>
                    <h5 class="font-weight-bold mb-0 text-white">Portal Ujian CBT</h5>
                    <p class="text-white-50 mb-0" style="font-size: 13px;">Ujian Berbasis Komputer</p>
                </div>
            </div>
            <a href="{{ route('student.cbt.dashboard') }}" class="btn btn-light btn-sm font-weight-bold" style="border-radius: 12px; padding: 8px 15px;">
                Masuk <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <div class="stu-content-wrapper">
        {{-- LAYANAN AKADEMIK GRID --}}
        <div class="stu-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 mt-3 px-4">
                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px; letter-spacing: 0.5px;">LAYANAN AKADEMIK</h6>
                <span class="badge badge-light text-muted px-3 py-2" style="border-radius: 8px;">Lihat Semua</span>
            </div>
            <div class="row no-gutters text-center" style="background: white; border-radius: 25px; padding: 20px 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
                <div class="col-3 mb-3">
                    <a href="#section-jadwal" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-purple"><i class="fas fa-calendar-alt"></i></div>
                        <span>Jadwal</span>
                    </a>
                </div>
                <div class="col-3 mb-3">
                    <a href="javascript:void(0)" onclick="$('#modalMutabaah').modal('show')" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-green"><i class="fas fa-tasks"></i></div>
                        <span>Ibadah</span>
                    </a>
                </div>
                <div class="col-3 mb-3">
                    <a href="javascript:void(0)" onclick="$('#modalTahfidz').modal('show')" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-emerald"><i class="fas fa-quran"></i></div>
                        <span>Tahfidz</span>
                    </a>
                </div>
                <div class="col-3 mb-3">
                    <a href="javascript:void(0)" onclick="$('#modalPengajuanIzin').modal('show')" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-blue"><i class="fas fa-envelope-open-text"></i></div>
                        <span>Izin</span>
                    </a>
                </div>
                <div class="col-3">
                    <a href="{{ route('students.card', $student->id) }}" target="_blank" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-orange"><i class="fas fa-id-card"></i></div>
                        <span>Kartu</span>
                    </a>
                </div>
                <div class="col-3">
                    <a href="#section-mading" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-indigo"><i class="fas fa-bullhorn"></i></div>
                        <span>Mading</span>
                    </a>
                </div>
                <div class="col-3">
                    <a href="#section-agenda" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-pink"><i class="fas fa-calendar-day"></i></div>
                        <span>Agenda</span>
                    </a>
                </div>
                <div class="col-3">
                    <a href="javascript:void(0)" onclick="$('#modalPoin').modal('show')" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-gold"><i class="fas fa-star"></i></div>
                        <span>Poin</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- PREMIUM BANNER SLIDER --}}
        <div class="stu-banner-wrapper" style="margin-top: 20px;">
            <div id="bannerCarouselStudent" class="carousel slide stu-banner-card" data-ride="carousel">
                <ol class="carousel-indicators">
                    @php $totalSlides = $announcements->count() + (($student && $isHoliday) ? 1 : 0); @endphp
                    @for($i = 0; $i < ($totalSlides ?: 1); $i++)
                        <li data-target="#bannerCarouselStudent" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                    @endfor
                </ol>
                <div class="carousel-inner">
                    @if($student && $isHoliday)
                        <div class="carousel-item active">
                            <div class="stu-banner-item" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
                                <h3 class="stu-banner-title">Hari Libur Sekolah</h3>
                                <p class="stu-banner-text">Hari ini adalah hari libur atau bukan hari efektif. Selamat beristirahat dan tetap jaga kesehatan!</p>
                                <button class="stu-banner-btn" style="color: #ef4444;">Informasi Libur</button>
                            </div>
                        </div>
                    @endif

                    @if($announcements->isNotEmpty())
                        @foreach($announcements as $idx => $ann)
                            <div class="carousel-item {{ (!($student && $isHoliday) && $idx == 0) ? 'active' : '' }}">
                                <div class="stu-banner-item" style="background: linear-gradient(135deg, {{ $idx % 2 == 0 ? '#065f46, #10b981' : '#1e3a8a, #3b82f6' }});">
                                    <h3 class="stu-banner-title">{{ $ann->title }}</h3>
                                    <p class="stu-banner-text">{{ strip_tags($ann->content) }}</p>
                                    <button class="stu-banner-btn" onclick='showAnnouncement(@json($ann))'>Lihat Detail</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5 mb-4">
                {{-- STATUS CURRENT --}}
                <div class="stu-card mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                    <div class="stu-card-body p-4">
                        @if($isHoliday)
                            <div class="p-3 bg-light text-center" style="border-radius: 12px; border: 1px dashed #cbd5e1;">
                                <p class="text-sm text-muted mb-0 font-weight-bold">Status Pelajaran Libur</p>
                            </div>
                        @else
                            @if($ongoingSubject)
                                <div class="p-3 bg-white shadow-sm" style="border-radius: 12px; border-left: 4px solid #10b981;">
                                    <p class="text-xs text-muted mb-1 text-uppercase font-weight-bold">Sedang Berlangsung</p>
                                    <h6 class="font-weight-bold text-success mb-1">{{ $ongoingSubject->subject->subject_name ?? 'Pelajaran' }}</h6>
                                    <p class="text-sm mb-0"><i class="far fa-clock mr-1"></i> {{ substr($ongoingSubject->start_time,0,5) }} - {{ substr($ongoingSubject->end_time,0,5) }} &bull; <i class="fas fa-user-tie mx-1"></i> {{ $ongoingSubject->teacher->name ?? '-' }}</p>
                                </div>
                            @elseif($nextSubject)
                                <div class="p-3 bg-white shadow-sm" style="border-radius: 12px; border-left: 4px solid #3b82f6;">
                                    <p class="text-xs text-muted mb-1 text-uppercase font-weight-bold">Pelajaran Selanjutnya</p>
                                    <h6 class="font-weight-bold text-primary mb-1">{{ $nextSubject->subject->subject_name ?? 'Pelajaran' }}</h6>
                                    <p class="text-sm mb-0"><i class="far fa-clock mr-1"></i> Mulai jam {{ substr($nextSubject->start_time,0,5) }} &bull; <i class="fas fa-user-tie mx-1"></i> {{ $nextSubject->teacher->name ?? '-' }}</p>
                                </div>
                            @elseif($todaySchedule->isNotEmpty())
                                <div class="p-3 bg-white shadow-sm" style="border-radius: 12px; border-left: 4px solid #64748b;">
                                    <p class="text-xs text-muted mb-1 text-uppercase font-weight-bold">Informasi</p>
                                    <h6 class="font-weight-bold text-secondary mb-0">Pelajaran hari ini telah selesai.</h6>
                                </div>
                            @else
                                <div class="p-3 bg-white shadow-sm" style="border-radius: 12px; border-left: 4px solid #f59e0b;">
                                    <p class="text-xs text-muted mb-1 text-uppercase font-weight-bold">Informasi</p>
                                    <h6 class="font-weight-bold text-warning mb-0">Tidak ada jadwal pelajaran hari ini.</h6>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- ABSENSI CARD --}}
                <div class="stu-card mb-4">
                    <div class="stu-card-header">
                        <div class="stu-card-icon bg-grad-green"><i class="fas fa-fingerprint"></i></div>
                        <div>
                            <h6 class="stu-card-title">Presensi Kehadiran</h6>
                            <p class="stu-card-sub">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div class="stu-card-body">
                        <div class="stu-attend-card-inner">
                            <div id="liveClock" class="stu-clock-live">00:00:00</div>
                            
                            @if(!$hasCheckedInToday)
                                @if($isWorkDay && $isCheckInTime && !$isHoliday)
                                    <div class="stu-status-badge status-warning-light mb-3">
                                        <i class="fas fa-clock mr-2"></i> Waktu Absensi Dibuka
                                    </div>
                                    <button type="button" onclick="submitAttendance()" class="stu-btn-attend-main">
                                        <i class="fas fa-hand-point-up mr-2"></i> ABSEN SEKARANG
                                    </button>
                                @else
                                    <div class="stu-status-badge bg-light text-muted mb-3">
                                        <i class="fas fa-lock mr-2"></i> Belum Waktunya Absen
                                    </div>
                                    <p class="text-sm text-muted mb-0">{{ $attendanceMessage }}</p>
                                @endif
                            @else
                                <div class="stu-status-badge status-success-light mb-2">
                                    <i class="fas fa-check-circle mr-2"></i> Berhasil Absen
                                </div>
                                <p class="text-xs text-muted mb-1">Anda tercatat hadir pada jam:</p>
                                <div class="stu-checkin-time">
                                    <i class="fas fa-history mr-1"></i> {{ $todayAttendance ? substr($todayAttendance->time, 0, 5) : '--:--' }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between text-xs mb-2">
                                <span class="text-muted font-weight-bold">Rasio Kehadiran Semester Ini</span>
                                <span class="font-weight-bold text-{{ $attColor }}">{{ $attPercentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 4px; background: #f1f5f9;">
                                <div class="progress-bar bg-{{ $attColor }}" role="progressbar" style="width: {{ $attPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- POIN KARAKTER --}}
                <div class="stu-card mb-4 overflow-hidden" style="background: linear-gradient(to bottom, #ffffff, #f0fdf4);">
                    <div class="stu-card-header border-0 pb-0">
                        <div class="stu-card-icon" style="background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 10px 20px rgba(16,185,129,0.2);">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <h6 class="stu-card-title">Poin Karakter & Akhlak</h6>
                            <p class="stu-card-sub">Pencapaian adab dan perilaku terpuji</p>
                        </div>
                    </div>
                    <div class="stu-card-body pt-4">
                        <div class="text-center mb-4 p-4" style="background: white; border-radius: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; position: relative;">
                            <div class="position-absolute" style="top: 10px; right: 15px;">
                                <span class="badge {{ $netPoints >= 100 ? 'badge-success' : ($netPoints >= 0 ? 'badge-info' : 'badge-danger') }} px-3 py-1" style="border-radius: 20px; font-size: 9px; letter-spacing: 0.5px;">
                                    {{ $netPoints >= 100 ? 'SANGAT BAIK' : ($netPoints >= 0 ? 'BAIK' : 'PERLU BIMBINGAN') }}
                                </span>
                            </div>
                            
                            <h2 class="font-weight-bold mb-0 {{ $netPoints >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 3.5rem; letter-spacing: -2px;">{{ $netPoints }}</h2>
                            <p class="text-xs text-muted font-weight-bold text-uppercase mb-3" style="letter-spacing: 2px;">SKOR AKHLAK SAAT INI</p>
                        </div>
                    </div>
                </div>

                {{-- INFO KELAS --}}
                <div class="stu-card mb-4" id="section-profil">
                    <div class="stu-card-header">
                        <div class="stu-card-icon bg-grad-blue"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div>
                            <h6 class="stu-card-title">Informasi Kelas</h6>
                            <p class="stu-card-sub">Semester Ganjil 2024/2025</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="stu-class-badge mb-0">{{ $student->classGroup->group_name ?? '-' }}</h2>
                            <div class="stu-status-active">
                                <span class="stu-dot-pulse"></span>
                                AKTIF
                            </div>
                        </div>

                        <div class="stu-teacher-card">
                            <div class="stu-teacher-avatar">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <p class="text-xs text-primary mb-0 font-weight-bold" style="letter-spacing: 0.5px; opacity: 0.8;">WALI KELAS</p>
                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 15px;">{{ $student->classGroup->homeroomTeacher->name ?? 'Belum ditentukan' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-4">
                {{-- MADING --}}
                <div class="stu-card mb-4 border-0 shadow-sm" id="section-mading" style="background: #fff; border-radius: 20px;">
                    <div class="stu-card-header bg-transparent border-0 pb-0">
                        <div class="stu-card-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h6 class="stu-card-title">Mading Digital</h6>
                            <p class="stu-card-sub">Informasi & Pengumuman Sekolah</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-3">
                        @if($announcements->isEmpty())
                            <div class="py-4 text-center">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                                <p class="text-muted text-sm mb-0">Belum ada pengumuman terbaru saat ini.</p>
                            </div>
                        @else
                            @foreach($announcements as $ann)
                                <div class="mading-item mb-3 p-3 shadow-sm" style="border-radius: 16px; background: #f8fafc; border-left: 5px solid #f59e0b !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="font-weight-bold text-dark mb-0">{{ $ann->title }}</h6>
                                        <span class="badge badge-light text-muted" style="font-size: 10px;">{{ $ann->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-muted text-sm mb-2">
                                        {!! Str::limit(strip_tags($ann->content), 100) !!}
                                    </div>
                                    <button class="btn btn-sm p-0 font-weight-bold text-primary" onclick='showAnnouncement(@json($ann))'>Baca Selengkapnya</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- TAHFIDZ --}}
                <div class="stu-card mb-4 border-0 shadow-sm" style="background: #fff; border-radius: 20px;">
                    <div class="stu-card-header bg-transparent border-0 pb-0">
                        <div class="stu-card-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <div>
                            <h6 class="stu-card-title">Tracker Tahfidz</h6>
                            <p class="stu-card-sub">Perkembangan Hafalan Al-Qur'an</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-3">
                        @if($tahfidzLogs->isEmpty())
                            <div class="py-4 text-center">
                                <i class="fas fa-book-open fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                                <p class="text-muted text-sm mb-0">Belum ada catatan hafalan terdaftar.</p>
                            </div>
                        @else
                            @foreach($tahfidzLogs->take(5) as $log)
                                <div class="d-flex align-items-center mb-3 p-2" style="border-bottom: 1px solid #f1f5f9;">
                                    <div class="mr-3 text-center" style="width: 45px; height: 45px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                        <span class="font-weight-bold text-success">{{ $log->grade }}</span>
                                    </div>
                                    <div style="flex: 1;">
                                        <h6 class="mb-0 font-weight-bold text-dark">{{ $log->surah_name }}</h6>
                                        <p class="mb-0 text-xs text-muted">Ayat {{ $log->verse_range }} &bull; Juz {{ $log->juz }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-muted d-block">{{ $log->date->format('d/m/y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- JADWAL --}}
                <div class="stu-card" id="section-jadwal">
                    <div class="stu-card-header">
                        <div class="stu-card-icon bg-grad-purple"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <h6 class="stu-card-title">Jadwal Pelajaran</h6>
                            <p class="stu-card-sub">Semua hari dalam seminggu</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-0">
                        @if($schedules->isEmpty())
                            <div class="text-center py-5">
                                <p class="text-muted">Jadwal pelajaran belum tersedia</p>
                            </div>
                        @else
                            <div class="accordion" id="scheduleAccordion">
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach($days as $day)
                                    @if(isset($schedules[$day]))
                                        <div class="stu-schedule-day">
                                            <div class="stu-schedule-day-header" data-toggle="collapse" data-target="#collapse{{ $day }}">
                                                <span class="font-weight-bold">{{ $day }}</span>
                                                <span class="badge badge-light">{{ $schedules[$day]->count() }} Mapel</span>
                                            </div>
                                            <div id="collapse{{ $day }}" class="collapse {{ $day === $currentDay ? 'show' : '' }}">
                                                <table class="table table-sm mb-0">
                                                    <tbody>
                                                        @foreach($schedules[$day] as $sch)
                                                            <tr>
                                                                <td class="pl-4 py-2" style="width: 100px;">
                                                                    <span class="text-primary font-weight-bold">{{ substr($sch->start_time, 0, 5) }}</span>
                                                                </td>
                                                                <td class="py-2">
                                                                    <span class="font-weight-bold d-block">{{ $sch->subject->subject_name ?? '-' }}</span>
                                                                    <span class="text-xs text-muted">{{ $sch->teacher->name ?? '-' }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM NAVIGATION --}}
    <div class="stu-bottom-nav">
        <a href="{{ route('siswa.dashboard') }}" class="nav-item active">
            <i class="fas fa-th-large"></i>
            <span>HOME</span>
        </a>
        <a href="#section-jadwal" class="nav-item">
            <i class="fas fa-calendar-alt"></i>
            <span>JADWAL</span>
        </a>
        <div class="stu-fab" onclick="submitAttendance()">
            <i class="fas fa-fingerprint"></i>
        </div>
        <a href="javascript:void(0)" onclick="$('#modalPengajuanIzin').modal('show')" class="nav-item">
            <i class="fas fa-paper-plane"></i>
            <span>IZIN</span>
        </a>
        <a href="#section-profil" class="nav-item">
            <i class="fas fa-user-circle"></i>
            <span>PROFIL</span>
        </a>
    </div>

    {{-- MODALS --}}
    @include('siswa.dashboard.modals')

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function updateClock() {
            let now = new Date();
            let h = String(now.getHours()).padStart(2, '0');
            let m = String(now.getMinutes()).padStart(2, '0');
            let s = String(now.getSeconds()).padStart(2, '0');
            $('#liveClock').text(h + ':' + m + ':' + s);
        }
        setInterval(updateClock, 1000);
        updateClock();

        window.submitAttendance = function() {
            Swal.fire({
                title: 'Konfirmasi Absen',
                text: "Apakah Anda ingin melakukan absensi sekarang?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ya, Absen!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.post('{{ route("siswa.store_attendance") }}', { _token: '{{ csrf_token() }}' })
                        .done(response => {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message }).then(() => location.reload());
                        })
                        .fail(xhr => {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                        });
                }
            });
        }
    });

    function showAnnouncement(ann) {
        Swal.fire({
            title: ann.title,
            html: `<div class="text-left" style="font-size: 14px;">${ann.content}</div>`,
            confirmButtonText: 'Tutup',
            customClass: { popup: 'rounded-xl' }
        });
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar Aplikasi?',
            text: "Anda akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) $('#logout-form-main').submit();
        });
    }
</script>
@endpush
