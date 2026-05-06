@extends($layout)

@section('title', 'Dashboard Guru')

@section('content')
<!-- Premium Dashboard Guru - Nature Edition v5.1 -->
<div class="bg-emerald-700 pt-12 pb-24 px-6 rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
    <!-- Aurora Background Effects -->
    <div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-30px] left-[-30px] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>

    <div class="relative z-10">
        <div class="flex justify-between items-start mb-8">
            <div class="flex items-center space-x-4">
                <div class="p-1 bg-white/20 rounded-2xl backdrop-blur-md border border-white/30 shadow-lg">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=10b981&color=fff&bold=true" class="w-14 h-14 rounded-xl shadow-inner">
                </div>
                <div>
                    <span class="text-emerald-200 text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-1 block">Selamat Datang Kembali</span>
                    <h1 class="text-2xl font-black text-white leading-tight drop-shadow-sm">{{ $teacher->name }}</h1>
                    <div class="flex items-center mt-2 space-x-2">
                        <div class="px-2 py-0.5 bg-white/10 rounded-md border border-white/10">
                            <span class="text-emerald-100 text-[9px] font-bold uppercase tracking-wider opacity-90">NIP. {{ $teacher->nip ?? '-' }}</span>
                        </div>
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-emerald-100 text-[9px] font-bold uppercase tracking-wider opacity-80">{{ $teacher->specialty ?? 'Tenaga Pengajar' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.announcements') }}" class="relative w-12 h-12 bg-white/10 rounded-2xl border border-white/20 text-white flex items-center justify-center transition-all active:scale-90 hover:bg-white/20">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadAnnouncementsCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full border-2 border-emerald-700 text-[9px] flex items-center justify-center font-black animate-bounce">{{ $unreadAnnouncementsCount }}</span>
                    @endif
                </a>
                <button onclick="document.querySelector('#form-logout-teacher').submit()" class="w-12 h-12 bg-rose-500/20 rounded-2xl border border-rose-500/30 text-rose-100 transition-all active:scale-90 hover:bg-rose-500/40 flex items-center justify-center shadow-lg shadow-rose-900/20">
                    <i class="fas fa-power-off text-lg"></i>
                </button>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="post" id="form-logout-teacher" class="hidden">
            @csrf
        </form>

        <!-- KPI Stats (Informative Pills) -->
        <div class="flex space-x-3 overflow-x-auto pb-4 no-scrollbar">
            <div class="flex-shrink-0 bg-white px-6 py-4 rounded-[1.5rem] shadow-xl flex items-center space-x-3 border border-emerald-50">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg shadow-inner">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest">Hadir 7 Hari</span>
                    <span class="text-emerald-600 font-black text-base">{{ $myAttendances->where('status', 'present')->count() }} Hari</span>
                </div>
            </div>
            <div class="flex-shrink-0 bg-white/10 backdrop-blur-md px-6 py-4 rounded-[1.5rem] flex items-center space-x-3 border border-white/10">
                <div class="w-10 h-10 bg-white/10 text-white rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <span class="block text-[8px] font-black text-emerald-100 uppercase tracking-widest">Beban Mengajar</span>
                    <span class="text-white font-black text-base">{{ $totalSchedules }} Mapel</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="px-6 -mt-12 mb-32 relative z-20">
    
    <!-- Attendance Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-emerald-900/10 mb-8 border border-emerald-50/50">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-5">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-[1.5rem] flex items-center justify-center text-2xl shadow-inner border border-emerald-100">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div>
                    <h3 class="text-slate-800 font-black text-lg">Presensi Harian</h3>
                    <p class="text-slate-400 text-xs font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            @if($todayAttendance)
                <div class="px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100">
                    <span class="text-emerald-600 font-black text-xs">AKTIF</span>
                </div>
            @endif
        </div>

        @if($onLeave)
            <div class="bg-amber-50 text-amber-700 py-6 px-8 rounded-3xl border-2 border-dashed border-amber-200 flex flex-col items-center text-center space-y-3">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm text-amber-500 mb-2">
                    <i class="fas fa-umbrella-beach text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-black text-lg uppercase tracking-tight">Status: Sedang Izin</h4>
                    <p class="text-xs font-bold text-amber-600/80 max-w-[250px]">Anda tercatat sedang mengambil izin/cuti hari ini. Selamat beristirahat!</p>
                </div>
            </div>
        @elseif(!$todayAttendance)
            <form id="formCheckIn" action="{{ route('teacher.attendance.check-in') }}" method="POST">
                @csrf
                <button type="button" onclick="submitAttendance('#formCheckIn')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-[1.5rem] shadow-xl shadow-emerald-200 transition-all active:scale-95 flex items-center justify-center space-x-3">
                    <span>ABSEN MASUK</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>
        @elseif($todayAttendance && !$todayAttendance->check_out)
            <div class="bg-slate-50 rounded-[1.5rem] p-5 flex items-center justify-between border border-slate-100">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm text-emerald-600 border border-slate-50">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">JAM MASUK</span>
                        <span class="text-xl font-black text-slate-800">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                    </div>
                </div>
                <form id="formCheckOut" action="{{ route('teacher.attendance.check-out') }}" method="POST" class="m-0">
                    @csrf
                    <button type="button" onclick="submitAttendance('#formCheckOut')" class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-rose-200 transition-all active:scale-95 uppercase text-[10px]">
                        Pulang
                    </button>
                </form>
            </div>
        @else
            <div class="bg-emerald-50 text-emerald-700 py-4 px-6 rounded-2xl flex items-center justify-center space-x-3 border border-emerald-100">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-black text-sm uppercase">Presensi Selesai</span>
            </div>
        @endif
    </div>

    <!-- Premium Banner Slider: Unified Experience -->
    @if($announcements->isNotEmpty())
    <div class="mb-10 px-1">
        <div id="teacherBannerCarousel" class="carousel slide stu-banner-card shadow-2xl shadow-emerald-900/20" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach($announcements as $index => $ann)
                    <li data-target="#teacherBannerCarousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
            <div class="carousel-inner">
                @foreach($announcements as $index => $ann)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="stu-banner-item" style="background: linear-gradient(135deg, {{ $index % 2 == 0 ? '#065f46, #10b981' : '#1e3a8a, #3b82f6' }});">
                            <div class="relative z-10">
                                <span class="bg-white/20 backdrop-blur-md text-white text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-3 inline-block">Informasi Terbaru</span>
                                <h3 class="stu-banner-title text-white font-black leading-tight">{{ $ann->title }}</h3>
                                <p class="stu-banner-text text-white/80 text-xs mt-2 line-clamp-2 max-w-[80%]">{{ strip_tags($ann->content) }}</p>
                                <a href="{{ route('teacher.announcements') }}" class="stu-banner-btn mt-6 inline-block bg-white text-emerald-700 px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider shadow-lg hover:bg-emerald-50 transition-all">
                                    Baca Selengkapnya
                                </a>
                            </div>
                            <!-- Background Decoration -->
                            <div class="absolute right-[-20px] bottom-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Unified Quick Actions (Student Dashboard Style) -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-50 mb-10">
        <div class="flex items-center justify-between mb-8 px-2">
            <h6 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">LAYANAN MANDIRI GURU</h6>
            <span class="badge badge-light text-muted px-3 py-2" style="border-radius: 8px; font-size: 9px; font-weight: 800;">LENGKAP</span>
        </div>
        
        <div class="grid grid-cols-4 gap-4 text-center">
            {{-- Scanner QR --}}
            <div class="mb-4">
                <a href="{{ route('student-attendances.scanner') }}" class="stu-quick-link">
                    <div class="stu-quick-icon bg-soft-indigo"><i class="fas fa-qrcode"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Scanner</span>
                </a>
            </div>

            {{-- Pengajuan Izin --}}
            <div class="mb-4">
                <a href="javascript:void(0)" onclick="openPermitModal()" class="stu-quick-link">
                    <div class="stu-quick-icon bg-soft-orange"><i class="fas fa-paper-plane"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Izin</span>
                </a>
            </div>

            {{-- Jadwal --}}
            <div class="mb-4">
                <a href="{{ route('guru.schedule') }}" class="stu-quick-link">
                    <div class="stu-quick-icon bg-soft-purple"><i class="fas fa-calendar-alt"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Jadwal</span>
                </a>
            </div>

            {{-- Informasi --}}
            <div class="mb-4">
                <a href="{{ route('teacher.announcements') }}" class="stu-quick-link">
                    <div class="stu-quick-icon bg-soft-blue"><i class="fas fa-bullhorn"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Info</span>
                </a>
            </div>

            {{-- Laporan --}}
            <div class="mb-4">
                <a href="#" class="stu-quick-link opacity-50">
                    <div class="stu-quick-icon bg-soft-pink"><i class="fas fa-file-invoice"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Laporan</span>
                </a>
            </div>

            {{-- Kurikulum --}}
            <div class="mb-4">
                <a href="#" class="stu-quick-link opacity-50">
                    <div class="stu-quick-icon bg-soft-emerald"><i class="fas fa-book"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Materi</span>
                </a>
            </div>

            {{-- Sertifikat --}}
            <div class="mb-4">
                <a href="#" class="stu-quick-link opacity-50">
                    <div class="stu-quick-icon bg-soft-indigo"><i class="fas fa-certificate"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Piagam</span>
                </a>
            </div>

            {{-- Profil --}}
            <div class="mb-4">
                <a href="#" class="stu-quick-link">
                    <div class="stu-quick-icon bg-soft-emerald"><i class="fas fa-user-circle"></i></div>
                    <span class="block text-[10px] font-black text-slate-600 mt-3 uppercase tracking-tighter">Profil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Riwayat Izin: Student-Style Timeline -->
    @if($myPermits->count() > 0)
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6 px-2">
            <h6 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">RIWAYAT IZIN TERAKHIR</h6>
            <a href="{{ route('teacher.permits.index') }}" class="badge badge-light text-muted px-3 py-2" style="border-radius: 8px; font-size: 9px; font-weight: 800;">LIHAT SEMUA</a>
        </div>
        
        <div class="max-h-[380px] overflow-y-auto pr-2 no-scrollbar" id="permitList">
            <div class="px-1">
                @foreach($myPermits as $permit)
                    @php
                        $statusColor = [
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'pending'  => 'warning'
                        ][$permit->status] ?? 'secondary';
                        
                        $statusIcon = [
                            'approved' => 'fa-check',
                            'rejected' => 'fa-times',
                            'pending'  => 'fa-clock'
                        ][$permit->status] ?? 'fa-info';
                    @endphp
                    <div class="d-flex align-items-start mb-4 position-relative permit-item" data-status="{{ $permit->status }}">
                        <div class="mr-3 mt-1" style="z-index: 2;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-{{ $statusColor }} text-white" style="width: 28px; height: 28px; font-size: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <i class="fas {{ $statusIcon }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 p-3 bg-white" style="border-radius: 18px; border: 1px solid #f1f5f9; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 text-sm font-black text-slate-700 leading-tight">{{ $permit->type }}</h6>
                                <span class="badge {{ $permit->status == 'approved' ? 'badge-success-soft text-success' : ($permit->status == 'rejected' ? 'badge-danger-soft text-danger' : 'badge-warning-soft text-warning') }} px-2 py-1" style="font-size: 8px; border-radius: 6px;">
                                    @if($permit->status == 'approved') DISETUJUI @elseif($permit->status == 'rejected') DITOLAK @else PENDING @endif
                                </span>
                            </div>
                            <div class="d-flex align-items-center text-muted" style="font-size: 10px; font-weight: 700;">
                                <span class="mr-2"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($permit->start_date)->translatedFormat('d M Y') }}</span>
                                @if($permit->description)
                                    <span class="truncate"><i class="fas fa-info-circle mr-1"></i> {{ Str::limit($permit->description, 20) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div id="noPermitsFound" class="hidden text-center py-5 bg-white border-dashed border-2 border-slate-100 rounded-[2rem]">
                    <div class="mb-3 opacity-20"><i class="fas fa-search fa-2x"></i></div>
                    <p class="text-xs text-slate-400 font-black uppercase tracking-widest px-4">Tidak ada data izin ditemukan.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Jadwal Mengajar: Student-Style Timeline -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-6 px-2">
            <h6 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0">JADWAL HARI INI</h6>
            <span class="badge badge-success px-3 py-2" style="border-radius: 8px; font-size: 9px; font-weight: 800;">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</span>
        </div>

        <div class="max-h-[450px] overflow-y-auto pr-2 no-scrollbar">
            <div class="px-1">
                @forelse($schedules as $schedule)
                    <div class="d-flex align-items-start mb-4 position-relative">
                        <div class="mr-3 mt-1" style="z-index: 2;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-emerald-600 text-white" style="width: 28px; height: 28px; font-size: 10px; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 p-3 bg-white" style="border-radius: 18px; border: 1px solid #f1f5f9; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 text-sm font-black text-slate-700 leading-tight">{{ $schedule->subject->name ?? '-' }}</h6>
                                <span class="font-black text-emerald-600" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($schedule->studyPeriod->start_time)->format('H:i') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center text-muted" style="font-size: 10px; font-weight: 700;">
                                <span class="mr-3"><i class="fas fa-door-open mr-1"></i> Kelas {{ $schedule->classGroup->kelas_lengkap ?? '-' }}</span>
                                <span><i class="fas fa-list-ol mr-1"></i> Jam Ke-{{ $schedule->studyPeriod->period_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white border-dashed border-2 border-slate-100 rounded-[2rem]">
                        <div class="mb-3 opacity-20"><i class="fas fa-mug-hot fa-3x"></i></div>
                        <p class="text-xs text-slate-400 font-black uppercase tracking-widest px-4">Tidak ada jadwal mengajar hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    <!-- Unified Student Management (Student Dashboard Style) -->
    @if($homeroomClass && count($myStudents) > 0)
    <div class="stu-card mb-10 mt-4 shadow-sm border border-slate-50">
        <div class="stu-card-header bg-white">
            <div class="stu-card-icon bg-soft-green">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="flex-grow">
                <h6 class="stu-card-title uppercase tracking-tight">MANAJEMEN ANAK DIDIK</h6>
                <p class="stu-card-sub">Wali Kelas {{ $homeroomClass->kelas_lengkap }} • {{ count($myStudents) }} Siswa</p>
            </div>
            <div class="relative group">
                <input type="text" id="searchStudent" placeholder="Cari..." class="bg-slate-50 border-0 rounded-xl py-2 pl-8 pr-3 text-[10px] font-bold text-slate-600 outline-none w-32 focus:w-48 transition-all" onkeyup="filterStudents()">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-[9px]"></i>
            </div>
        </div>
        
        <div class="p-6">
            <div class="max-h-[600px] overflow-y-auto pr-2 no-scrollbar" id="studentListContainer">
                <div class="space-y-3" id="studentRows">
                    @foreach($myStudents as $s)
                        @php
                            $score = $s->behaviorLogs->sum(fn($l) => $l->type == 'positive' ? $l->points : -$l->points);
                            $statusColor = $score >= 100 ? 'green' : ($score >= 0 ? 'blue' : 'red');
                            $statusText = $score >= 100 ? 'SANGAT BAIK' : ($score >= 0 ? 'BAIK' : 'PERLU BIMBINGAN');
                        @endphp
                        <div class="student-row stu-teacher-card bg-white hover:bg-slate-50 transition-all border border-slate-100 mb-3 px-5 py-4 rounded-[1.5rem] flex items-center justify-between shadow-none">
                            <div class="flex items-center space-x-4">
                                <div class="stu-teacher-avatar bg-soft-{{ $statusColor }} rounded-xl flex items-center justify-center overflow-hidden w-12 h-12">
                                    @if($s->profile && $s->profile->foto)
                                        <img src="{{ asset('storage/'.$s->profile->foto) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-lg"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="student-name text-sm font-black text-slate-700 leading-tight mb-1">{{ $s->nama_lengkap }}</h6>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NISN. {{ $s->nisn ?? '-' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="text-right mr-3">
                                    <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">SKOR</span>
                                    <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor == 'blue' ? 'primary' : ($statusColor == 'green' ? 'success' : 'danger') }} px-3 py-1.5 rounded-lg text-[10px] font-black">{{ $score }}</span>
                                </div>
                                <button onclick="openPointModal({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}')" class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-emerald-600 transition-all shadow-lg active:scale-90">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50/50 p-6 px-8 border-t border-slate-50 flex items-center justify-center space-x-6">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Target Akhlak Mulia</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bimbingan Rutin</span>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- MODAL INPUT POIN (PREMIUM REDESIGN) -->
<div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-4">
        <div class="modal-content rounded-[3rem] border-0 shadow-2xl overflow-hidden">
            <div class="bg-grad-green p-10 text-white relative text-center overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-[-20px] left-[-20px] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-[-10px] right-[-10px] w-32 h-32 bg-emerald-400/20 rounded-full blur-xl"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-medal text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-black mb-1">Penilaian Karakter</h4>
                    <p id="studentNameDisplay" class="text-emerald-100 text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Memuat Nama Siswa...</p>
                </div>
            </div>
            <div class="p-10 bg-white">
                <form id="formPoint">
                    @csrf
                    <input type="hidden" name="student_id" id="modalStudentId">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Jenis Catatan</label>
                                <div class="flex p-1 bg-slate-50 rounded-2xl border border-slate-100">
                                    <button type="button" onclick="selectPointType('positive', this)" class="flex-1 py-3 px-2 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all point-type-btn bg-emerald-600 text-white shadow-lg active-type" data-type="positive">Kebaikan</button>
                                    <button type="button" onclick="selectPointType('negative', this)" class="flex-1 py-3 px-2 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all point-type-btn text-slate-400" data-type="negative">Pelanggaran</button>
                                    <input type="hidden" name="type" id="inputPointType" value="positive">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Bobot Poin</label>
                                <div class="relative">
                                    <input type="number" name="points" value="10" min="1" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-emerald-500 focus:bg-white rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none transition-all pr-12">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 font-black text-[10px]">PTS</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Kategori Aktivitas</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="selectCategory('Kedisiplinan', this)" class="cat-btn py-3 px-4 rounded-xl border border-slate-100 text-[10px] font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all text-left flex items-center justify-between">
                                    Kedisiplinan <i class="fas fa-check-circle opacity-0 transition-all text-[8px]"></i>
                                </button>
                                <button type="button" onclick="selectCategory('Kebersihan', this)" class="cat-btn py-3 px-4 rounded-xl border border-slate-100 text-[10px] font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all text-left flex items-center justify-between">
                                    Kebersihan <i class="fas fa-check-circle opacity-0 transition-all text-[8px]"></i>
                                </button>
                                <button type="button" onclick="selectCategory('Sosial', this)" class="cat-btn py-3 px-4 rounded-xl border border-slate-100 text-[10px] font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all text-left flex items-center justify-between">
                                    Sosial <i class="fas fa-check-circle opacity-0 transition-all text-[8px]"></i>
                                </button>
                                <button type="button" onclick="selectCategory('Ibadah', this)" class="cat-btn py-3 px-4 rounded-xl border border-slate-100 text-[10px] font-bold text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-all text-left flex items-center justify-between">
                                    Ibadah <i class="fas fa-check-circle opacity-0 transition-all text-[8px]"></i>
                                </button>
                                <input type="hidden" name="category" id="inputCategory" value="Kedisiplinan">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Deskripsi Aktivitas</label>
                            <textarea name="description" rows="3" required class="w-full bg-slate-50 border-2 border-slate-50 focus:border-emerald-500 focus:bg-white rounded-2xl p-5 text-sm font-bold text-slate-700 outline-none transition-all" placeholder="Tuliskan detail perilaku siswa..."></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-2 border-slate-50 focus:border-emerald-500 focus:bg-white rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none transition-all">
                        </div>
                        
                        <div class="pt-6">
                            <button type="button" onclick="submitPoint()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-6 rounded-[1.5rem] shadow-2xl shadow-emerald-200 transition-all active:scale-95 uppercase tracking-widest text-xs flex items-center justify-center space-x-3">
                                <span>Simpan Penilaian</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                            <button type="button" data-dismiss="modal" class="w-full mt-4 text-slate-300 font-bold text-[9px] uppercase tracking-widest py-2 transition-all hover:text-slate-500">
                                Tutup Jendela
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PENGAJUAN IZIN (PREMIUM) -->
<div class="modal fade" id="permitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-4">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl overflow-hidden">
            <div class="bg-emerald-600 p-8 text-white relative text-center">
                <div class="relative z-10">
                    <h4 class="text-xl font-black mb-1">Pengajuan Izin</h4>
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Kirim ke Kepala Madrasah</p>
                </div>
                <div class="absolute top-[-20px] right-[-20px] w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="p-8 bg-white">
                <form id="formPermit" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Jenis Izin</label>
                            <select name="type" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="Izin">Izin (Kepentingan Keluarga/Lainnya)</option>
                                <option value="Sakit">Sakit (Butuh Istirahat/Berobat)</option>
                                <option value="Cuti">Cuti Tahunan/Besar</option>
                                <option value="Perjalanan Dinas">Perjalanan Dinas / Tugas Luar</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Alasan / Keperluan</label>
                            <textarea name="reason" rows="3" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Tuliskan alasan pengajuan Anda..."></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Mulai Tanggal</label>
                                <input type="date" name="start_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Sampai (Opsional)</label>
                                <input type="date" name="end_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Lampiran (Opsional)</label>
                            <div class="relative w-full bg-slate-50 rounded-2xl p-4 border-2 border-dashed border-slate-200 text-center transition-all hover:border-emerald-500 group">
                                <input type="file" name="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                <div class="relative z-10 text-slate-400 group-hover:text-emerald-600">
                                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">Klik atau Tarik File Disini</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <button type="button" onclick="submitPermit()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-100 transition-all active:scale-95 uppercase tracking-widest text-xs">
                                Kirim Pengajuan
                            </button>
                            <button type="button" data-dismiss="modal" class="w-full mt-3 text-slate-400 font-bold text-[10px] uppercase tracking-widest py-2">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    function filterPermits(status, btn) {
        // Toggle Active Button
        $('.permit-filter-btn').removeClass('bg-emerald-600 text-white shadow-lg active-filter')
                              .addClass('bg-white text-slate-400 border border-slate-100 shadow-sm');
        $(btn).removeClass('bg-white text-slate-400 border border-slate-100 shadow-sm')
              .addClass('bg-emerald-600 text-white shadow-lg active-filter');

        // Filter Items
        let visibleCount = 0;
        $('.permit-item').each(function() {
            if (status === 'all' || $(this).data('status') === status) {
                $(this).removeClass('hidden').addClass('animate__fadeIn');
                visibleCount++;
            } else {
                $(this).addClass('hidden').removeClass('animate__fadeIn');
            }
        });

        // Show/Hide Empty State
        if (visibleCount === 0) {
            $('#noPermitsFound').removeClass('hidden');
        } else {
            $('#noPermitsFound').addClass('hidden');
        }
    }

    function openPermitModal() {
        $('#permitModal').modal('show');
    }

    function submitPermit() {
        const form = document.getElementById('formPermit');
        const formData = new FormData(form);

        Swal.fire({
            title: 'KIRIM PENGAJUAN',
            text: "Kirim pengajuan izin ini ke Kepala Madrasah?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, KIRIM SEKARANG',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                
                $.ajax({
                    url: '{{ route("teacher.permits.store") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, showConfirmButton: false, timer: 2000 }).then(() => { 
                            $('#permitModal').modal('hide');
                            window.location.reload(); 
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengirim pengajuan.' });
                    }
                });
            }
        });
    }

    function submitAttendance(formId) {
        const form = $(formId);
        Swal.fire({
            title: 'VERIFIKASI',
            text: "Konfirmasi kehadiran Anda?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, KONFIRMASI',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                $.post(form.attr('action'), form.serialize())
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, showConfirmButton: false, timer: 2000 }).then(() => { window.location.reload(); });
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    });
            }
        });
    }

    function openPointModal(id, name) {
        $('#modalStudentId').val(id);
        $('#studentNameDisplay').text(name);
        $('#pointModal').modal('show');
    }

    function filterStudents() {
        const input = document.getElementById('searchStudent');
        const filter = input.value.toLowerCase();
        const rows = document.getElementsByClassName('student-row');

        for (let i = 0; i < rows.length; i++) {
            const name = rows[i].getElementsByClassName('student-name')[0].innerText.toLowerCase();
            if (name.includes(filter)) {
                rows[i].style.display = "";
                rows[i].classList.add('flex'); // Maintain flex layout
            } else {
                rows[i].style.display = "none";
                rows[i].classList.remove('flex');
            }
        }
    }

    function selectPointType(type, btn) {
        $('.point-type-btn').removeClass('bg-emerald-600 text-white shadow-lg active-type').addClass('text-slate-400');
        $(btn).addClass('bg-emerald-600 text-white shadow-lg active-type').removeClass('text-slate-400');
        $('#inputPointType').val(type);
    }

    function selectCategory(cat, btn) {
        $('.cat-btn').removeClass('bg-emerald-50 text-emerald-600 border-emerald-200');
        $('.cat-btn i').addClass('opacity-0');
        $(btn).addClass('bg-emerald-50 text-emerald-600 border-emerald-200');
        $(btn).find('i').removeClass('opacity-0');
        $('#inputCategory').val(cat);
    }

    function submitPoint() {
        const form = $('#formPoint');
        
        Swal.fire({
            title: 'KONFIRMASI',
            text: "Simpan catatan poin untuk siswa ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, SIMPAN',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                
                $.ajax({
                    url: '{{ route("behavior-logs.store") }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, showConfirmButton: false, timer: 2000 }).then(() => { 
                            $('#pointModal').modal('hide');
                            form[0].reset();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                    }
                });
            }
        });
    }
</script>
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .hidden { display: none !important; }
    .bg-grad-green { background: linear-gradient(135deg, #065f46, #10b981); }
    .bg-success-soft { background-color: rgba(16, 185, 129, 0.1); }
    .bg-danger-soft { background-color: rgba(239, 68, 68, 0.1); }

    /* Quick Links Grid Sync */
    .stu-quick-link {
        display: flex; flex-direction: column; align-items: center;
        text-decoration: none !important; transition: all 0.2s;
    }
    .stu-quick-link:hover { transform: translateY(-3px); }
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

    /* Banner Slider Styling */
    .stu-banner-card {
        border-radius: 2.5rem;
        overflow: hidden;
        border: none;
        height: 200px;
    }
    .stu-banner-item {
        height: 200px;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
    }
    .stu-banner-title {
        font-size: 1.25rem;
        max-width: 80%;
    }
    .carousel-indicators { bottom: 20px; }
    .carousel-indicators li {
        width: 6px; height: 6px;
        border-radius: 50%;
        margin: 0 4px;
        background-color: rgba(255,255,255,0.4);
        border: none;
    }
    .carousel-indicators .active { background-color: white; width: 16px; border-radius: 10px; }
    
    /* Soft Badge Styles */
    .badge-success-soft { background: #dcfce7; color: #166534; }
    .badge-danger-soft { background: #fef2f2; color: #991b1b; }
    .badge-warning-soft { background: #fef3c7; color: #92400e; }

    /* Student Dashboard Layout Sync */
    .stu-card {
        background: white;
        border-radius: 2.5rem;
        border: 1px solid #f1f5f9;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .stu-card-header {
        padding: 25px 30px;
        display: flex; align-items: center; gap: 15px;
        border-bottom: 1px solid #f8fafc;
    }
    .stu-card-icon {
        width: 50px; height: 50px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
    }
    .stu-card-title { font-weight: 900; font-size: 1rem; color: #1e293b; margin: 0; letter-spacing: -0.5px; }
    .stu-card-sub { font-size: 0.75rem; color: #94a3b8; font-weight: 700; margin-top: 2px; }

    .stu-teacher-card {
        background: white;
        border-radius: 20px;
        padding: 15px;
        display: flex;
        align-items: center;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .stu-teacher-avatar {
        width: 48px; height: 48px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        overflow: hidden;
    }
    
    .bg-soft-blue { background: #eff6ff; color: #3b82f6; }
    .bg-soft-green { background: #f0fdf4; color: #10b981; }
    .bg-soft-red { background: #fef2f2; color: #ef4444; }
    .bg-soft-indigo { background: #eef2ff; color: #6366f1; }
    .bg-soft-orange { background: #fff7ed; color: #f59e0b; }
    .bg-soft-emerald { background: #ecfdf5; color: #059669; }
    .bg-soft-pink { background: #fdf2f8; color: #ec4899; }
    .bg-soft-purple { background: #f5f3ff; color: #8b5cf6; }
</style>
@endpush
@endsection
