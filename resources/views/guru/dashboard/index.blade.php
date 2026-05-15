@extends($layout)

@section('title', 'Dashboard Guru')

@section('content')
<!-- PREMIUM DASHBOARD V3 - ULTRA MODERN -->
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <div class="profile-frame p-1 rounded-[2rem] bg-white/20 backdrop-blur-md">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-20 h-20 rounded-[1.8rem] object-cover shadow-2xl border-2 border-white/50">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=ffffff&color=6366f1&bold=true" class="w-20 h-20 rounded-[1.8rem] shadow-2xl border-2 border-white/50">
                        @endif
                    </div>
                    <div class="text-white">
                        <span class="bg-white/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2 inline-block">{{ $greeting }}</span>
                        <h1 class="text-3xl font-black tracking-tight leading-tight">{{ explode(' ', $teacher->name)[0] }} {{ explode(' ', $teacher->name)[1] ?? '' }}</h1>
                        <p class="text-white/70 text-xs font-bold mt-1"><i class="fas fa-id-badge mr-2"></i> {{ $teacher->nip ?? 'ID Belum Terdaftar' }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Status Anda</span>
                        <span class="text-xs font-black text-white bg-emerald-500/80 px-3 py-1 rounded-full border border-emerald-400">AKTIF</span>
                    </div>
                    <button onclick="document.querySelector('#form-logout-teacher').submit()" class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-3xl border border-white/10 text-white hover:bg-rose-500 transition-all flex items-center justify-center shadow-xl">
                        <i class="fas fa-power-off text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        
        @if($birthdayStudents->count() > 0)
        <!-- BIRTHDAY BANNER -->
        <div class="bg-gradient-to-r from-pink-500 to-rose-500 rounded-[2.5rem] p-6 mb-8 shadow-xl shadow-rose-200/50 flex items-center justify-between overflow-hidden relative group">
            <div class="flex items-center space-x-6 relative z-10">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl animate-bounce">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div class="text-white">
                    <h5 class="text-lg font-black mb-0">Ada yang Ulang Tahun!</h5>
                    <p class="text-white/80 text-xs font-bold">
                        @foreach($birthdayStudents as $bs)
                            {{ $bs->nama_lengkap }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                        hari ini merayakan ulang tahun.
                    </p>
                </div>
            </div>
            <div class="absolute right-[-20px] top-[-20px] w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
        </div>
        @endif
        
        <!-- KPI SECTION - VIBRANT GRID -->
        <div class="row g-4 mb-10">
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hadir</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $myAttendances->where('status', 'present')->count() }} <small class="text-[10px] text-slate-400">Hari</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jadwal</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ count($schedules) }} <small class="text-[10px] text-slate-400">Mapel</small></h3>
                </div>
            </div>
            @if($homeroomClass)
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tabungan</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0"><small class="text-[10px] text-slate-400">Rp</small> {{ number_format($totalClassSavings, 0, ',', '.') }}</h3>
                </div>
            </div>
            @endif
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Siswa</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $myStudents->count() }} <small class="text-[10px] text-slate-400">Orang</small></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-10">
            <!-- ATTENDANCE MAIN CARD -->
            <div class="col-lg-7">
                <div class="bg-white rounded-[3rem] p-1 shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden h-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h4 class="text-2xl font-black text-slate-800 mb-1">Presensi Digital</h4>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-2xl">
                                @if($todayAttendance)
                                    <span class="badge-status success"><i class="fas fa-check-circle mr-2"></i> TERCATAT</span>
                                @else
                                    <span class="badge-status warning"><i class="fas fa-clock mr-2"></i> BELUM ABSEN</span>
                                @endif
                            </div>
                        </div>

                        @if($onLeave)
                            <div class="leave-state py-12 text-center">
                                <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                                    <i class="fas fa-umbrella-beach text-4xl"></i>
                                </div>
                                <h5 class="text-xl font-black text-slate-800 mb-2">Sedang Cuti / Izin</h5>
                                <p class="text-sm text-slate-400 max-w-xs mx-auto">Sistem mencatat status izin Anda hari ini. Selamat beristirahat!</p>
                            </div>
                        @elseif(!$todayAttendance)
                            <div class="check-in-state py-8 text-center bg-slate-50/50 rounded-[2.5rem] border border-dashed border-slate-200">
                                <div class="w-24 h-24 bg-white text-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-2xl">
                                    <i class="fas fa-fingerprint text-4xl"></i>
                                </div>
                                <h5 class="text-xl font-black text-slate-800 mb-2">Siap Mengajar Hari Ini?</h5>
                                <form id="formCheckIn" action="{{ route('teacher.attendance.check-in') }}" method="POST">
                                    @csrf
                                    <a href="{{ route('teacher.attendance.dashboard') }}" class="btn-action primary mx-auto block text-center no-underline">
                                        {{ ($setting->enable_face_attendance ?? true) ? 'Mulai Presensi Wajah' : 'Mulai Presensi GPS' }} 
                                        <i class="fas fa-{{ ($setting->enable_face_attendance ?? true) ? 'camera' : 'location-arrow' }} ml-3"></i>
                                    </a>
                                </form>
                            </div>
                        @else
                            <div class="attendance-details">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100 flex items-center space-x-5">
                                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl text-emerald-500">
                                                <i class="fas fa-sign-in-alt text-2xl"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jam Masuk</span>
                                                <h4 class="text-3xl font-black text-slate-800 mb-0">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if(!$todayAttendance->check_out)
                                            <div class="p-6 bg-rose-50/50 rounded-[2rem] border border-rose-100 flex items-center space-x-5 relative overflow-hidden group cursor-pointer" onclick="submitAttendance('#formCheckOut')">
                                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                                                    <i class="fas fa-sign-out-alt text-2xl"></i>
                                                </div>
                                                <div class="relative z-10">
                                                    <span class="block text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Sesi Pulang</span>
                                                    <h4 class="text-sm font-black text-slate-800 mb-0 uppercase tracking-widest">
                                                        {{ ($setting->enable_face_attendance ?? true) ? 'PRESENSI WAJAH' : 'PRESENSI GPS' }} 
                                                        <i class="fas fa-chevron-right ml-1"></i>
                                                    </h4>
                                                </div>
                                                <a href="{{ route('teacher.attendance.dashboard') }}" class="absolute inset-0 z-20"></a>
                                                <form id="formCheckOut" action="{{ route('teacher.attendance.check-out') }}" method="POST" class="d-none">@csrf</form>
                                            </div>
                                        @else
                                            <div class="p-6 bg-indigo-50/50 rounded-[2rem] border border-indigo-100 flex items-center space-x-5">
                                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl text-indigo-500">
                                                    <i class="fas fa-check-double text-2xl"></i>
                                                </div>
                                                <div>
                                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jam Pulang</span>
                                                    <h4 class="text-3xl font-black text-slate-800 mb-0">{{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</h4>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-2xl flex items-center justify-between">
                                    <div>
                                        <h5 class="text-xs font-black uppercase tracking-widest text-indigo-400 mb-1">Status Kehadiran</h5>
                                        <p class="text-lg font-black mb-0">Laporan Hari Ini Aman & Terverifikasi</p>
                                    </div>
                                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center border border-white/10">
                                        <i class="fas fa-shield-alt text-indigo-300"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- QUICK ACCESS & TOOLS -->
            <div class="col-lg-5">
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 h-100">
                    <div class="flex items-center justify-between mb-8">
                        <h5 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Layanan Mandiri</h5>
                        <span class="w-10 h-1 bg-indigo-100 rounded-full"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('teacher.attendance.dashboard') }}" class="tool-btn bg-grad-indigo">
                            <div class="tool-icon"><i class="fas fa-{{ ($setting->enable_face_attendance ?? true) ? 'camera' : 'fingerprint' }}"></i></div>
                            <span class="tool-label">{{ ($setting->enable_face_attendance ?? true) ? 'Presensi Wajah' : 'Presensi GPS' }}</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        @if($setting->enable_face_attendance ?? true)
                        <a href="{{ route('teacher.face.registration') }}" class="tool-btn bg-grad-blue">
                            <div class="tool-icon"><i class="fas fa-user-shield"></i></div>
                            <span class="tool-label">Reg Wajah</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        @else
                        <a href="{{ route('teacher.attendance.manual') }}" class="tool-btn bg-grad-blue">
                            <div class="tool-icon"><i class="fas fa-map-marked-alt"></i></div>
                            <span class="tool-label">Peta Lokasi</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        @endif
                        <a href="{{ route('guru.schedule') }}" class="tool-btn bg-grad-green">
                            <div class="tool-icon"><i class="fas fa-calendar-alt"></i></div>
                            <span class="tool-label">Jadwal</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('guru.attendance.report') }}" class="tool-btn bg-grad-purple">
                            <div class="tool-icon"><i class="fas fa-chart-line"></i></div>
                            <span class="tool-label">Statistik</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('guru.journal.index') }}" class="tool-btn bg-grad-emerald relative">
                            <div class="tool-icon"><i class="fas fa-file-signature"></i></div>
                            <span class="tool-label">Jurnal KBM</span>
                            @if($pendingJournalsCount > 0)
                                <span class="absolute top-4 right-4 w-6 h-6 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center animate-bounce shadow-lg border-2 border-white">
                                    {{ $pendingJournalsCount }}
                                </span>
                            @endif
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('guru.grades.index') }}" class="tool-btn bg-grad-teal">
                            <div class="tool-icon"><i class="fas fa-pen-alt"></i></div>
                            <span class="tool-label">Input Nilai</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('guru.student-attendances.index') }}" class="tool-btn bg-grad-purple">
                            <div class="tool-icon"><i class="fas fa-clipboard-user"></i></div>
                            <span class="tool-label">Rekap Absensi</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('guru.student-permits.index') }}" class="tool-btn bg-grad-orange">
                            <div class="tool-icon"><i class="fas fa-file-medical-alt"></i></div>
                            <span class="tool-label">Izin Siswa</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        @if($homeroomClass)
                        <a href="{{ route('guru.savings.index') }}" class="tool-btn bg-grad-rose">
                            <div class="tool-icon"><i class="fas fa-piggy-bank"></i></div>
                            <span class="tool-label">Tabungan</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        @endif
                        <a href="javascript:void(0)" onclick="openPermitModal()" class="tool-btn bg-grad-red">
                            <div class="tool-icon"><i class="fas fa-paper-plane"></i></div>
                            <span class="tool-label">Izin/Cuti Saya</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                    </div>

                    <!-- RECENT PERMITS TIMELINE -->
                    <div class="mt-10">
                        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 px-2">Log Izin Terakhir</h6>
                        @forelse($myPermits->take(3) as $permit)
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-3 transition-all hover:bg-white hover:shadow-lg">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm mr-4">
                                    <i class="fas fa-history text-xs"></i>
                                </div>
                                <div class="flex-grow">
                                    <h6 class="text-xs font-black text-slate-800 mb-0">{{ $permit->type }}</h6>
                                    <span class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($permit->start_date)->format('d M Y') }}</span>
                                </div>
                                <span class="badge-status-small {{ $permit->status }}">{{ $permit->status }}</span>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0">Tidak ada riwayat</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- MANAJEMEN ANAK DIDIK (HOMEROOM SECTION) -->
        @if($homeroomClass && count($myStudents) > 0)
        <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 space-y-4 md:space-y-0">
                <div>
                    <h4 class="text-2xl font-black text-slate-800 mb-1">Manajemen Anak Didik</h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Wali Kelas {{ $homeroomClass->kelas_lengkap }} • {{ count($myStudents) }} Siswa Terdaftar</p>
                </div>
                <div class="relative group">
                    <input type="text" id="searchStudent" placeholder="Cari Siswa..." class="bg-slate-50 border-0 rounded-2xl py-3 pl-10 pr-4 text-xs font-bold text-slate-600 outline-none w-full md:w-64 focus:ring-2 focus:ring-indigo-500 transition-all shadow-inner" onkeyup="filterStudents()">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                </div>
            </div>

            <div class="max-h-[600px] overflow-y-auto pr-2 no-scrollbar" id="studentListContainer">
                <div class="row g-4" id="studentRows">
                    @foreach($myStudents as $s)
                        @php
                            $score = $s->behaviorLogs->sum(fn($l) => $l->type == 'positive' ? $l->points : -$l->points);
                            $statusColor = $score >= 100 ? 'emerald' : ($score >= 0 ? 'indigo' : 'rose');
                            $statusLabel = $score >= 100 ? 'SANGAT BAIK' : ($score >= 0 ? 'BAIK' : 'BUTUH PERHATIAN');
                        @endphp
                        <div class="col-md-6 student-row">
                            <div class="p-6 bg-white border border-slate-100 rounded-[2.5rem] flex items-center justify-between hover:shadow-2xl hover:border-indigo-100 transition-all group">
                                <div class="flex items-center space-x-5">
                                    <div class="relative">
                                        <div class="w-14 h-14 bg-{{ $statusColor }}-50 rounded-[1.2rem] flex items-center justify-center overflow-hidden border-2 border-white shadow-sm group-hover:scale-105 transition-transform">
                                            @if($s->profile && $s->profile->foto)
                                                <img src="{{ asset('storage/'.$s->profile->foto) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-user text-{{ $statusColor }}-500 text-xl"></i>
                                            @endif
                                        </div>
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-{{ $statusColor }}-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <div>
                                        <h6 class="student-name text-sm font-black text-slate-700 mb-1 leading-tight group-hover:text-indigo-600 transition-colors">{{ $s->nama_lengkap }}</h6>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">NISN. {{ $s->nisn ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right d-none d-sm-block">
                                        <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">SKOR AKHLAK</span>
                                        <span class="text-xs font-black text-{{ $statusColor }}-600 bg-{{ $statusColor }}-50 px-3 py-1 rounded-lg border border-{{ $statusColor }}-100">{{ $score }}</span>
                                    </div>
                                    <button onclick="openPointModal({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}')" class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-emerald-600 hover:scale-110 transition-all shadow-xl active:scale-95">
                                        <i class="fas fa-plus-circle text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- JADWAL TIMELINE -->
        <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-10">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h4 class="text-2xl font-black text-slate-800 mb-1">Jadwal Mengajar</h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</p>
                </div>
                <a href="{{ route('guru.schedule') }}" class="btn-view-all">Lihat Semua <i class="fas fa-chevron-right ml-2"></i></a>
            </div>

            <div class="row g-4">
                @forelse($schedules as $schedule)
                    <div class="col-md-4">
                        <div class="schedule-card group">
                            <div class="time-badge">{{ \Carbon\Carbon::parse($schedule->studyPeriod->start_time)->format('H:i') }}</div>
                            <div class="p-6 pt-10">
                                <h6 class="text-lg font-black text-slate-800 mb-2 leading-tight">{{ $schedule->subject->name ?? '-' }}</h6>
                                <div class="flex items-center space-x-3 text-slate-400">
                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fas fa-door-open mr-2 text-indigo-500"></i> {{ $schedule->classGroup->kelas_lengkap ?? '-' }}
                                    </div>
                                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fas fa-clock mr-2 text-indigo-500"></i> Jam {{ $schedule->studyPeriod->period_name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-12">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada jadwal hari ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- MODAL INPUT POIN (PREMIUM REDESIGN) -->
<div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-4">
        <div class="modal-content rounded-[3rem] border-0 shadow-2xl overflow-hidden">
            <div class="bg-grad-indigo p-10 text-white relative text-center overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-medal text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-black mb-1">Penilaian Karakter</h4>
                    <p id="studentNameDisplay" class="text-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Nama Siswa</p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            </div>
            <div class="p-10 bg-white">
                <form id="formPoint">
                    @csrf
                    <input type="hidden" name="student_id" id="modalStudentId">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Jenis Catatan</label>
                                <div class="flex p-1 bg-slate-50 rounded-2xl border border-slate-100">
                                    <button type="button" onclick="selectPointType('positive', this)" class="flex-1 py-3 px-2 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all point-type-btn bg-emerald-600 text-white shadow-lg" data-type="positive">Kebaikan</button>
                                    <button type="button" onclick="selectPointType('negative', this)" class="flex-1 py-3 px-2 rounded-xl text-[9px] font-black uppercase tracking-tighter transition-all point-type-btn text-slate-400" data-type="negative">Pelanggaran</button>
                                    <input type="hidden" name="type" id="inputPointType" value="positive">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Bobot Poin</label>
                                <input type="number" name="points" value="10" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Kategori</label>
                            <select name="category" id="inputCategory" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="Kedisiplinan">Kedisiplinan</option>
                                <option value="Kebersihan">Kebersihan</option>
                                <option value="Ibadah">Ibadah</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Deskripsi Catatan</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Tuliskan detail kejadian..."></textarea>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tanggal Kejadian</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-black text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="pt-6">
                            <button type="button" onclick="submitPoint()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-[1.5rem] shadow-2xl shadow-indigo-100 transition-all active:scale-95 uppercase tracking-widest text-xs">Simpan Penilaian</button>
                            <button type="button" data-dismiss="modal" class="w-full mt-3 text-slate-400 font-bold text-[10px] uppercase tracking-widest py-2">Tutup Jendela</button>
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
            <div class="bg-grad-orange p-8 text-white text-center relative">
                <div class="relative z-10">
                    <h4 class="text-xl font-black mb-1">Pengajuan Izin</h4>
                    <p class="text-orange-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Layanan Guru Madrasah</p>
                </div>
                <i class="fas fa-paper-plane absolute right-[-10px] top-[-10px] text-white/10 fa-5x"></i>
            </div>
            <div class="p-8 bg-white">
                <form id="formPermit" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Jenis Izin</label>
                            <select name="type" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none">
                                <option value="Izin">Izin (Keperluan Mendesak)</option>
                                <option value="Sakit">Sakit (Butuh Istirahat)</option>
                                <option value="Cuti">Cuti Tahunan</option>
                                <option value="Dinas">Dinas Luar / Tugas</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Alasan Keperluan</label>
                            <textarea name="reason" rows="3" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="Jelaskan alasan pengajuan Anda..."></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Mulai Tanggal</label>
                                <input type="date" name="start_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Sampai (Opsional)</label>
                                <input type="date" name="end_date" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-orange-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Lampiran (Foto/PDF)</label>
                            <input type="file" name="attachment" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-xs font-bold text-slate-400">
                        </div>
                        <div class="pt-4">
                            <button type="button" onclick="submitPermit()" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-orange-100 transition-all active:scale-95 uppercase tracking-widest text-xs">Kirim Pengajuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="form-logout-teacher" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<!-- STYLES -->
<style>
    :root {
        --p-indigo: #6366f1;
        --p-blue: #3b82f6;
        --p-emerald: #10b981;
        --p-rose: #f43f5e;
        --p-amber: #f59e0b;
        --p-slate-800: #1e293b;
    }

    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }

    /* Gradients */
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
    .bg-grad-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .bg-grad-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .bg-grad-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-grad-emerald { background: linear-gradient(135deg, #059669 0%, #064e3b 100%); }
    .bg-grad-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-grad-teal   { background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); }
    .bg-grad-rose   { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); }

    /* Buttons */
    .btn-action.primary {
        background: var(--p-indigo);
        color: white;
        padding: 18px 40px;
        border-radius: 22px;
        font-weight: 900;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        box-shadow: 0 15px 30px rgba(99,102,241,0.3);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-action.primary:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 20px 40px rgba(99,102,241,0.4); }

    .btn-view-all {
        font-size: 10px; font-weight: 900; color: var(--p-indigo); text-transform: uppercase;
        letter-spacing: 1px; background: #eef2ff; padding: 10px 20px; border-radius: 14px;
        transition: all 0.2s;
    }
    .btn-view-all:hover { background: var(--p-indigo); color: white; }

    /* Badges */
    .badge-status { padding: 8px 16px; border-radius: 12px; font-size: 9px; font-weight: 900; letter-spacing: 1px; display: inline-flex; align-items: center; }
    .badge-status.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-status.warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

    .badge-status-small { font-size: 8px; font-weight: 900; text-transform: uppercase; padding: 4px 8px; border-radius: 6px; }
    .badge-status-small.approved { background: #dcfce7; color: #166534; }
    .badge-status-small.pending { background: #fef3c7; color: #92400e; }
    .badge-status-small.rejected { background: #fee2e2; color: #991b1b; }

    /* Tool Buttons */
    .tool-btn {
        padding: 24px; border-radius: 2.2rem; display: flex; flex-direction: column;
        align-items: center; position: relative; transition: all 0.3s; overflow: hidden;
        text-decoration: none !important; border: none; box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .tool-btn:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .tool-icon { 
        width: 48px; height: 48px; background: rgba(255,255,255,0.2); 
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.2rem; margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.1);
    }
    .tool-label { color: white; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
    .tool-arrow { position: absolute; right: 15px; top: 15px; color: rgba(255,255,255,0.3); font-size: 10px; }

    /* Schedule Card */
    .schedule-card {
        background: white; border-radius: 2.2rem; border: 1px solid #f1f5f9;
        transition: all 0.3s; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .schedule-card:hover { transform: translateY(-5px); border-color: var(--p-indigo); box-shadow: 0 15px 40px rgba(99,102,241,0.1); }
    .time-badge {
        position: absolute; top: -15px; left: 30px; background: var(--p-indigo);
        color: white; font-weight: 900; font-size: 12px; padding: 8px 20px;
        border-radius: 12px; box-shadow: 0 8px 20px rgba(99,102,241,0.3);
    }

    /* Scrollbar */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    @media (max-width: 768px) {
        .header-banner { padding-top: 40px; padding-bottom: 80px; }
        .btn-action.primary { width: 100%; padding: 15px 20px; font-size: 12px; }
    }
</style>
@endsection

@push('scripts')
<script>
    // Attendance Submission
    function submitAttendance(formId) {
        const form = $(formId);
        Swal.fire({
            title: 'KONFIRMASI',
            text: "Kirim data presensi sekarang?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'YA, KIRIM',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2.5rem]' }
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

    // Permit/Leave Logic
    function openPermitModal() { $('#permitModal').modal('show'); }
    function submitPermit() {
        const form = document.getElementById('formPermit');
        const formData = new FormData(form);
        Swal.fire({ title: 'Kirim Pengajuan?', icon: 'question', showCancelButton: true, confirmButtonColor: '#f59e0b', confirmButtonText: 'YA, KIRIM', customClass: { popup: 'rounded-[2.5rem]' } })
            .then(res => {
                if(res.isConfirmed) {
                    Swal.fire({ title: 'Mengirim...', didOpen: () => { Swal.showLoading() } });
                    $.ajax({ url: '{{ route("teacher.permits.store") }}', method: 'POST', data: formData, processData: false, contentType: false,
                        success: (r) => { Swal.fire({ icon: 'success', title: 'BERHASIL', text: r.message, timer: 2000, showConfirmButton: false }).then(() => window.location.reload()); },
                        error: (e) => { Swal.fire({ icon: 'error', title: 'GAGAL', text: e.responseJSON?.message || 'Gagal mengirim' }); }
                    });
                }
            });
    }

    // Student Point Management
    function openPointModal(id, name) {
        $('#modalStudentId').val(id);
        $('#studentNameDisplay').text(name);
        $('#pointModal').modal('show');
    }
    function selectPointType(type, btn) {
        $('.point-type-btn').removeClass('bg-emerald-600 text-white shadow-lg').addClass('text-slate-400');
        $(btn).addClass('bg-emerald-600 text-white shadow-lg').removeClass('text-slate-400');
        $('#inputPointType').val(type);
    }
    function submitPoint() {
        const form = $('#formPoint');
        Swal.fire({ title: 'Simpan Penilaian?', icon: 'question', showCancelButton: true, confirmButtonColor: '#6366f1', confirmButtonText: 'YA, SIMPAN', customClass: { popup: 'rounded-[2.5rem]' } })
            .then(res => {
                if(res.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
                    $.ajax({ url: '{{ route("guru.behavior-logs.store") }}', method: 'POST', data: form.serialize(),
                        success: (r) => { Swal.fire({ icon: 'success', title: 'BERHASIL', text: r.message, timer: 2000, showConfirmButton: false }).then(() => window.location.reload()); },
                        error: (e) => { Swal.fire({ icon: 'error', title: 'GAGAL', text: e.responseJSON?.message || 'Gagal menyimpan' }); }
                    });
                }
            });
    }

    // Filters
    function filterStudents() {
        const val = $('#searchStudent').val().toLowerCase();
        $('.student-row').each(function() {
            const name = $(this).find('.student-name').text().toLowerCase();
            $(this).toggle(name.includes(val));
        });
    }
</script>
@endpush
