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

    @elseif($registrant && $registrant->status == 'sudah_masuk_siswa')
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

        {{-- PREMIUM HEADER (TEACHER STYLE) --}}
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
                    <form action="{{ route('logout') }}" method="POST" id="form-logout-siswa-header" class="d-inline">
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

        <div class="stu-content-wrapper">
            {{-- LAYANAN AKADEMIK GRID --}}
            <div class="stu-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
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

            {{-- PREMIUM BANNER SLIDER (STUDENT POSITION) --}}
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
                        @else
                            <div class="carousel-item active">
                                <div class="stu-banner-item">
                                    <h3 class="stu-banner-title">Selamat Datang di Madrasah Digital</h3>
                                    <p class="stu-banner-text">Pantau progres akademik dan ibadah Anda secara real-time di sini.</p>
                                    <button class="stu-banner-btn">Mulai Eksplorasi</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>



        <div class="row">
            {{-- LEFT COLUMN --}}
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

                {{-- POIN KARAKTER STATUS CARD --}}
                <div class="stu-card mb-4">
                    <div class="stu-card-header border-0 pb-0">
                        <div class="stu-card-icon bg-grad-orange"><i class="fas fa-heart"></i></div>
                        <div>
                            <h6 class="stu-card-title">Poin Karakter & Akhlak</h6>
                            <p class="stu-card-sub">Monitoring perilaku dan kebaikan siswa</p>
                        </div>
                    </div>
                    <div class="stu-card-body pt-3">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <div class="p-3 text-center" style="background: #f8fafc; border-radius: 20px; border: 1px solid #f1f5f9;">
                                    <h2 class="font-weight-bold mb-0 {{ $netPoints >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 2.5rem;">{{ $netPoints }}</h2>
                                    <p class="text-xs text-muted font-weight-bold text-uppercase mb-0">Total Poin</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2 d-flex justify-content-between align-items-center p-2 px-3" style="background: #ecfdf5; border-radius: 12px;">
                                    <span class="text-xs text-success font-weight-bold">KEBAIKAN</span>
                                    <span class="badge badge-success">+{{ $totalPositivePoints }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-2 px-3" style="background: #fef2f2; border-radius: 12px;">
                                    <span class="text-xs text-danger font-weight-bold">PELANGGARAN</span>
                                    <span class="badge badge-danger">-{{ $totalNegativePoints }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($behaviorLogs->isNotEmpty())
                            <div class="mt-4">
                                <p class="text-xs font-weight-bold text-muted text-uppercase mb-3">Catatan Terakhir</p>
                                @foreach($behaviorLogs as $log)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="mr-3 {{ $log->type == 'positive' ? 'text-success' : 'text-danger' }}" style="width: 30px; text-align: center;">
                                            <i class="fas {{ $log->type == 'positive' ? 'fa-plus-circle' : 'fa-minus-circle' }} fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $log->description }}</h6>
                                            <p class="text-xs text-muted mb-0">{{ $log->date->translatedFormat('d M Y') }} &bull; Oleh {{ $log->teacher->name ?? 'Guru' }}</p>
                                        </div>
                                        <div class="font-weight-bold {{ $log->type == 'positive' ? 'text-success' : 'text-danger' }}">
                                            {{ $log->type == 'positive' ? '+' : '-' }}{{ $log->points }}
                                        </div>
                                    </div>
                                @endforeach
                                <button class="btn btn-block btn-light text-primary font-weight-bold text-xs mt-2" style="border-radius: 12px;" onclick="$('#modalPoin').modal('show')">LIHAT SEMUA RIWAYAT</button>
                            </div>
                        @else
                            <div class="mt-4 p-3 text-center bg-light" style="border-radius: 15px; border: 1px dashed #cbd5e1;">
                                <p class="text-xs text-muted mb-0">Belum ada catatan poin karakter.</p>
                            </div>
                        @endif
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

                        <div class="stu-info-grid">
                            <div class="stu-info-item-small">
                                <label>NISN SISWA</label>
                                <span>{{ $student->nisn ?? '-' }}</span>
                            </div>
                            <div class="stu-info-item-small">
                                <label>NOMOR INDUK</label>
                                <span>{{ $student->nis ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QUICK ACTIONS --}}
                <div class="stu-card mb-4">
                    <div class="stu-card-body p-3">
                        <button type="button" class="btn btn-block stu-btn-action mb-2" data-toggle="modal" data-target="#modalPengajuanIzin">
                            <i class="fas fa-envelope-open-text mr-2 text-primary"></i> Pengajuan Izin / Sakit
                        </button>
                        <a href="{{ route('students.card', $student->id) }}" target="_blank" class="btn btn-block stu-btn-action mb-2">
                            <i class="fas fa-id-card mr-2 text-info"></i> Cetak Kartu Siswa
                        </a>
                    </div>
                </div>

                {{-- RIWAYAT IZIN --}}
                <div class="stu-card mb-4">
                    <div class="stu-card-header">
                        <div class="stu-card-icon bg-grad-blue"><i class="fas fa-history"></i></div>
                        <div>
                            <h6 class="stu-card-title">Riwayat Izin / Sakit</h6>
                            <p class="stu-card-sub">Pengajuan terbaru</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-0" style="max-height: 220px; overflow-y: auto;">
                        @if($myPermits->isEmpty())
                            <div class="p-4 text-center">
                                <p class="text-muted text-sm mb-0">Belum ada riwayat pengajuan</p>
                            </div>
                        @else
                            @foreach($myPermits as $permit)
                                <div class="stu-info-row px-3 py-2">
                                    <div style="flex: 1;">
                                        <span class="font-weight-bold text-dark" style="font-size: 13px;">{{ $permit->type }}</span>
                                        <p class="text-xs text-muted mb-0">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($permit->start_date)->format('d M') }} 
                                            {{ $permit->end_date ? '- '.\Carbon\Carbon::parse($permit->end_date)->format('d M') : '' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @if($permit->status == 'pending')
                                            <span class="badge badge-warning text-white shadow-sm" style="font-size: 10px; border-radius: 6px;">Menunggu</span>
                                        @elseif($permit->status == 'approved')
                                            <span class="badge badge-success text-white shadow-sm" style="font-size: 10px; border-radius: 6px;">Disetujui</span>
                                        @else
                                            <span class="badge badge-danger text-white shadow-sm" style="font-size: 10px; border-radius: 6px;">Ditolak</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- AGENDA --}}
                <div class="stu-card" id="section-agenda">
                    <div class="stu-card-header">
                        <div class="stu-card-icon bg-grad-orange"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <h6 class="stu-card-title">Agenda Terdekat</h6>
                            <p class="stu-card-sub">Kegiatan sekolah mendatang</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-0">
                        @if($agendas->isEmpty())
                            <div class="p-4 text-center">
                                <i class="fas fa-calendar-day fa-2x text-muted mb-2 d-block" style="opacity:.3"></i>
                                <p class="text-muted text-sm mb-0">Belum ada agenda terdekat</p>
                            </div>
                        @else
                            @foreach($agendas as $agenda)
                                <div class="stu-agenda-item">
                                    <div class="stu-agenda-date">
                                        <span class="stu-agenda-day">{{ \Carbon\Carbon::parse($agenda->start_date)->format('d') }}</span>
                                        <span class="stu-agenda-month">{{ \Carbon\Carbon::parse($agenda->start_date)->translatedFormat('M') }}</span>
                                    </div>
                                    <div class="stu-agenda-info">
                                        <h6 class="mb-0 font-weight-bold text-sm">{{ $agenda->title }}</h6>
                                        <p class="text-muted text-xs mb-0 text-truncate" style="max-width:200px">{{ $agenda->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-md-7 mb-4">
                {{-- MADING DIGITAL / PENGUMUMAN --}}
                <div class="stu-card mb-4 border-0 shadow-sm" style="background: #fff; border-radius: 20px;">
                    <div class="stu-card-header bg-transparent border-0 pb-0">
                        <div class="stu-card-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h6 class="stu-card-title" style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">Mading Digital</h6>
                            <p class="stu-card-sub" style="font-size: 0.8rem; color: #64748b;">Informasi & Pengumuman Sekolah</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-3">
                        @if($announcements->isEmpty())
                            <div class="py-4 text-center">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                                <p class="text-muted text-sm mb-0">Belum ada pengumuman terbaru saat ini.</p>
                            </div>
                        @else
                            <div class="mading-wrapper" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                @foreach($announcements as $ann)
                                    <div class="mading-item mb-3 p-3 shadow-sm border-0 animate__animated animate__fadeIn" 
                                         style="border-radius: 16px; background: #f8fafc; border-left: 5px solid #f59e0b !important; transition: transform 0.2s;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="font-weight-bold text-dark mb-0" style="font-size: 14px; line-height: 1.4;">{{ $ann->title }}</h6>
                                            <span class="badge badge-light text-muted" style="font-size: 10px; font-weight: 600;">{{ $ann->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-muted text-sm mb-2" style="font-size: 13px; line-height: 1.5; color: #475569;">
                                            {!! Str::limit(strip_tags($ann->content), 100) !!}
                                        </div>
                                        <button class="btn btn-sm p-0 font-weight-bold text-primary" style="font-size: 12px;" 
                                                onclick='showAnnouncement(@json($ann))'>
                                            Baca Selengkapnya <i class="fas fa-arrow-right ml-1" style="font-size: 10px;"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TAHFIDZ TRACKER --}}
                <div class="stu-card mb-4 border-0 shadow-sm" style="background: #fff; border-radius: 20px;">
                    <div class="stu-card-header bg-transparent border-0 pb-0">
                        <div class="stu-card-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                            <i class="fas fa-quran"></i>
                        </div>
                        <div>
                            <h6 class="stu-card-title" style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">Tracker Tahfidz</h6>
                            <p class="stu-card-sub" style="font-size: 0.8rem; color: #64748b;">Perkembangan Hafalan Al-Qur'an</p>
                        </div>
                    </div>
                    <div class="stu-card-body p-3">
                        @if($tahfidzLogs->isEmpty())
                            <div class="py-4 text-center">
                                <i class="fas fa-book-open fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                                <p class="text-muted text-sm mb-0">Belum ada catatan hafalan terdaftar.</p>
                            </div>
                        @else
                            @php
                                $latestLog = $tahfidzLogs->first();
                                // Dummy progress for Juz 30
                                $progress = 75; 
                            @endphp
                            <div class="tahfidz-progress mb-4 p-3" style="background: #f0fdf4; border-radius: 16px; border: 1px solid #dcfce7;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="font-weight-bold text-success" style="font-size: 13px;">Target Juz 30</span>
                                    <span class="badge badge-success" style="border-radius: 6px;">{{ $progress }}% Selesai</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 5px; background: #dcfce7;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="text-xs text-muted mt-2 mb-0">
                                    <i class="fas fa-info-circle mr-1"></i> 
                                    Setoran terakhir: <b>{{ $latestLog->surah_name }} ({{ $latestLog->verse_range }})</b> pada {{ $latestLog->date->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            <h6 class="font-weight-bold mb-3" style="font-size: 13px; color: #475569;"><i class="fas fa-history mr-2"></i> Riwayat Setoran</h6>
                            <div class="tahfidz-list" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                @foreach($tahfidzLogs as $log)
                                    <div class="d-flex align-items-center mb-3 p-2" style="border-bottom: 1px solid #f1f5f9; background: #fff; transition: background 0.2s;">
                                        <div class="mr-3 text-center" style="width: 45px; height: 45px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                            <span class="font-weight-bold text-success" style="font-size: 16px;">{{ $log->grade }}</span>
                                        </div>
                                        <div style="flex: 1;">
                                            <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 14px;">{{ $log->surah_name }}</h6>
                                            <p class="mb-0 text-xs text-muted">Ayat {{ $log->verse_range }} &bull; Juz {{ $log->juz }} &bull; <span class="text-capitalize">{{ $log->type }}</span></p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-muted d-block">{{ $log->date->format('d/m/y') }}</span>
                                            <span class="badge badge-light text-primary" style="font-size: 10px;">Tajwid: {{ $log->tajwid_score }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- JADWAL PELAJARAN --}}
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
                                <i class="fas fa-book-open fa-3x text-muted mb-3 d-block" style="opacity:.2"></i>
                                <p class="text-muted font-weight-bold">Jadwal pelajaran belum tersedia</p>
                            </div>
                        @else
                            <div class="accordion" id="scheduleAccordion">
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    $dayColors = ['#10b981','#3b82f6','#8b5cf6','#f59e0b','#ef4444','#06b6d4','#64748b'];
                                @endphp
                                @foreach($days as $idx => $day)
                                    @if(isset($schedules[$day]))
                                        <div class="stu-schedule-day">
                                            <div class="stu-schedule-day-header" data-toggle="collapse" data-target="#collapse{{ $day }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="stu-day-dot" style="background:{{ $dayColors[$idx] }}"></span>
                                                    <span class="font-weight-bold">{{ $day }}</span>
                                                </div>
                                                <span class="badge" style="background:{{ $dayColors[$idx] }}15; color:{{ $dayColors[$idx] }}; font-weight:700; padding:5px 12px; border-radius:8px; font-size:11px;">{{ $schedules[$day]->count() }} Mapel</span>
                                            </div>
                                            <div id="collapse{{ $day }}" class="collapse {{ $day === \Carbon\Carbon::now()->translatedFormat('l') ? 'show' : '' }}">
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0">
                                                        <thead>
                                                            <tr class="stu-table-head">
                                                                <th style="padding-left:20px">Jam</th>
                                                                <th>Mata Pelajaran</th>
                                                                <th>Guru</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($schedules[$day] as $sch)
                                                                <tr>
                                                                    <td style="padding-left:20px" class="py-2">
                                                                        <span class="font-weight-bold text-primary" style="font-size:13px">{{ $sch->start_time }} - {{ $sch->end_time }}</span>
                                                                    </td>
                                                                    <td class="py-2">
                                                                        <span class="font-weight-bold" style="font-size:13px">{{ $sch->subject->subject_name ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="py-2 text-muted" style="font-size:12px">{{ $sch->teacher->name ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
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

    {{-- BOTTOM NAVIGATION (PREMIUM DARK) --}}
        <div class="stu-bottom-nav">
            <a href="{{ route('ppdb.dashboard') }}" class="nav-item active">
                <i class="fas fa-th-large"></i>
                <span>HOME</span>
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>JADWAL</span>
            </a>
            <div class="stu-fab" onclick="submitAttendance()">
                <i class="fas fa-fingerprint"></i>
            </div>
            <a href="#" data-toggle="modal" data-target="#modalPengajuanIzin" class="nav-item">
                <i class="fas fa-history"></i>
                <span>IZIN</span>
            </a>
            <a href="#section-profil" class="nav-item">
                <i class="fas fa-user-circle"></i>
                <span>PROFIL</span>
            </a>
        </div>

        {{-- MODAL POIN KARAKTER --}}
        <div class="modal fade" id="modalPoin" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content stu-modal">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-star text-warning mr-2"></i> Riwayat Poin Karakter</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="stu-info-item-small mb-4 text-center py-4 bg-grad-green">
                            <label class="text-white-50">Total Poin Akhlak</label>
                            <h2 class="text-white font-weight-bold mb-0" style="font-size: 3rem;">{{ $netPoints }}</h2>
                        </div>

                        @if($behaviorLogs->isNotEmpty())
                            @foreach($behaviorLogs as $log)
                                <div class="p-3 mb-3 border" style="border-radius: 16px; transition: all 0.2s;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge {{ $log->type == 'positive' ? 'badge-success' : 'badge-danger' }} px-3 py-2" style="border-radius: 8px;">
                                            {{ $log->type == 'positive' ? 'KEBAIKAN' : 'PELANGGARAN' }}
                                        </span>
                                        <span class="font-weight-bold {{ $log->type == 'positive' ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                                            {{ $log->type == 'positive' ? '+' : '-' }}{{ $log->points }}
                                        </span>
                                    </div>
                                    <h6 class="font-weight-bold mb-1">{{ $log->description }}</h6>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                        <span><i class="far fa-calendar-alt mr-1"></i> {{ $log->date->translatedFormat('d F Y') }}</span>
                                        <span><i class="fas fa-user-tie mr-1"></i> {{ $log->teacher->name ?? 'Sistem' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada riwayat poin.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-block" style="border-radius: 12px;" data-dismiss="modal">TUTUP</button>
                    </div>
                </div>
            </div>
        </div>

    @elseif(!$registrant)
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
                    <form action="{{ route('logout') }}" method="POST" id="form-logout-applicant" class="d-inline">
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
                    <form action="{{ route('logout') }}" method="POST" id="form-logout-registered" class="d-inline">
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

    @if($registrant && $registrant->status == 'sudah_masuk_siswa')
        {{-- MODAL PENGAJUAN IZIN --}}
        <div class="modal fade" id="modalPengajuanIzin" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-envelope-open-text mr-2"></i> Pengajuan Izin / Sakit</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formPengajuanIzin" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-sm">Jenis Pengajuan <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" style="border-radius: 10px;" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin (Keperluan Keluarga, dll)</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-sm">Mulai Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" class="form-control" style="border-radius: 10px;" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-sm">Sampai Tanggal</label>
                                        <input type="date" name="end_date" class="form-control" style="border-radius: 10px;">
                                        <small class="text-muted">Kosongkan jika hanya 1 hari</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-sm">Alasan / Keterangan <span class="text-danger">*</span></label>
                                <textarea name="reason" rows="3" class="form-control" style="border-radius: 10px;" required placeholder="Jelaskan alasan izin..."></textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-sm">Lampiran (Opsional)</label>
                                <div class="custom-file">
                                    <input type="file" name="attachment" class="custom-file-input" id="customFile" accept="image/*">
                                    <label class="custom-file-label" for="customFile" style="border-radius: 10px;">Pilih foto surat/keterangan</label>
                                </div>
                                <small class="text-muted">Format: JPG/PNG, Maks: 5MB. Contoh: Foto surat dokter.</small>
                            </div>
                        </div>
                        <div class="modal-footer bg-light" style="border: none;">
                            <button type="button" class="btn btn-secondary" style="border-radius: 10px;" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL PENGUMUMAN --}}
        <div class="modal fade" id="modalAnnouncement" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border: none; padding: 20px 25px;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-bullhorn mr-2"></i> Detail Pengumuman</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                            <div class="mr-3" style="width: 45px; height: 45px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold" id="announcementAuthor">Admin Madrasah</h6>
                                <p class="mb-0 text-muted text-xs" id="announcementDate"></p>
                            </div>
                        </div>
                        <h4 class="font-weight-bold mb-3" id="announcementDetailTitle" style="color: #1e293b;"></h4>
                        <div id="announcementContent" class="text-dark" style="line-height: 1.8; font-size: 1.05rem;">
                            <!-- Content injected via JS -->
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3" style="border: none;">
                        <button type="button" class="btn btn-secondary px-4" style="border-radius: 12px; font-weight: 600;" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL MUTABA'AH YAUMIYAH --}}
        <div class="modal fade" id="modalMutabaah" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 20px 25px;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-tasks mr-2"></i> Jurnal Ibadah Harian</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formMutabaahModal">
                        @csrf
                        <div class="modal-body p-4">
                            <p class="text-muted text-sm mb-4">Silakan centang ibadah yang telah Anda laksanakan hari ini.</p>
                            <div class="row">
                                @php
                                    $prayers_m = [
                                        'shubuh' => 'Shubuh',
                                        'zhuhur' => 'Zhuhur',
                                        'ashar' => 'Ashar',
                                        'maghrib' => 'Maghrib',
                                        'isya' => 'Isya',
                                        'dhuha' => 'Dhuha',
                                        'tahajud' => 'Tahajud'
                                    ];
                                @endphp
                                @foreach($prayers_m as $key => $label)
                                    <div class="col-6 mb-3">
                                        <div class="custom-control custom-checkbox custom-checkbox-lg p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                            <input type="checkbox" name="{{ $key }}" class="custom-control-input" id="modalcheck{{ $key }}" {{ ($todayMutabaah && $todayMutabaah->$key) ? 'checked' : '' }} value="1">
                                            <label class="custom-control-label font-weight-bold text-dark" for="modalcheck{{ $key }}" style="cursor: pointer;">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group mb-0 mt-2">
                                <label class="font-weight-bold text-sm">Tadarus / Catatan Ibadah</label>
                                <input type="text" name="tadarus" class="form-control" style="border-radius: 12px;" placeholder="Misal: Surah Al-Kahfi ayat 1-10" value="{{ $todayMutabaah->tadarus ?? '' }}">
                            </div>
                        </div>
                        <div class="modal-footer bg-light" style="border: none;">
                            <button type="button" class="btn btn-secondary" style="border-radius: 12px;" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success px-4" style="border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); border: none; font-weight: 700;">Simpan Jurnal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL TAHFIDZ HISTORY --}}
        <div class="modal fade" id="modalTahfidz" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" style="border-radius: 25px; border: none; overflow: hidden;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #059669, #047857); color: white; border: none; padding: 20px 25px;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-quran mr-2"></i> Riwayat Hafalan (Tahfidz)</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        @if($tahfidzLogs->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-3x text-muted mb-3 d-block" style="opacity:.1"></i>
                                <p class="text-muted">Belum ada catatan hafalan terdaftar.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Surah</th>
                                            <th>Ayat</th>
                                            <th>Juz</th>
                                            <th>Jenis</th>
                                            <th>Grade</th>
                                            <th>Tajwid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tahfidzLogs as $log)
                                            <tr>
                                                <td>{{ $log->date->format('d/m/Y') }}</td>
                                                <td class="font-weight-bold">{{ $log->surah_name }}</td>
                                                <td>{{ $log->verse_range }}</td>
                                                <td>{{ $log->juz }}</td>
                                                <td><span class="badge badge-pill badge-light">{{ ucfirst($log->type) }}</span></td>
                                                <td><span class="font-weight-bold text-success">{{ $log->grade }}</span></td>
                                                <td>{{ $log->tajwid_score }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
                document.getElementById('form-logout-siswa').submit();
            }
        });
    }

    function submitAttendance() {
        Swal.fire({
            title: 'Konfirmasi Kehadiran',
            text: 'Apakah Anda ingin melakukan absensi hadir untuk hari ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            confirmButtonText: 'Ya, Absen Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.post('{{ route("ppdb.store_attendance") }}', { _token: '{{ csrf_token() }}' })
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message })
                            .then(() => location.reload());
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    });
            }
        });
    }

    $(document).ready(function() {
        // Live Clock for Attendance
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            $('#liveClock').text(`${hours}:${minutes}:${seconds}`);
        }
        setInterval(updateClock, 1000);
        updateClock();

        $('#formPengajuanIzin').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            
            $.ajax({
                url: '{{ route("ppdb.store_permit") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message })
                            .then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                    }
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan saat memproses data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        });

        $('#formMutabaah, #formMutabaahModal').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            
            $.post('{{ route("ppdb.store_mutabaah") }}', formData)
                .done(response => {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                    }
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                });
        });

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

        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
