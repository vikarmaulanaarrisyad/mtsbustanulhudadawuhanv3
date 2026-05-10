@extends('layouts.ppdb')

@section('title', 'Dashboard Siswa')

@section('content')
<!-- PREMIUM STUDENT DASHBOARD - ULTRA MODERN (SAME AS TEACHER) -->
<div class="dashboard-wrapper pb-20">
    @php
        $now = \Carbon\Carbon::now();
        $currentTime = $now->format('H:i:s');
        $todaySchedule = $todaySchedules;
        
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
        $greeting = 'Selamat Pagi';
        if($hour >= 11) $greeting = 'Selamat Siang';
        if($hour >= 15) $greeting = 'Selamat Sore';
        if($hour >= 19) $greeting = 'Selamat Malam';

        $totalH = $attendanceStats['H'];
        $totalAll = $totalH + $attendanceStats['I'] + $attendanceStats['S'] + $attendanceStats['A'];
        $attPercentage = $totalAll > 0 ? round(($totalH / $totalAll) * 100) : 100;
        
        $attColor = 'emerald';
        if($attPercentage < 80) $attColor = 'rose';
        elseif($attPercentage < 90) $attColor = 'amber';
    @endphp

    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6 md:space-x-8">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2.2rem] blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                        <div class="profile-frame relative p-1 rounded-[2.2rem] bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">
                            @if($student->profile && $student->profile->foto)
                                <img src="{{ asset('storage/' . $student->profile->foto) }}" class="w-24 h-24 md:w-28 md:h-28 rounded-[2rem] object-cover border-2 border-white/50">
                            @else
                                <div class="w-24 h-24 md:w-28 md:h-28 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[2rem] flex items-center justify-center border-2 border-white/50 shadow-2xl backdrop-blur-md">
                                    <span class="text-4xl font-black text-white">{{ substr($student->nama_lengkap, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-white text-center md:text-left">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-3">
                            <span class="bg-indigo-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg">{{ $greeting }}</span>
                            <span class="bg-emerald-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg">Siswa Aktif</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none mb-3">{{ $student->nama_lengkap }}</h1>
                        <div class="flex items-center justify-center md:justify-start space-x-4 text-white/70 text-[10px] md:text-xs font-black uppercase tracking-widest">
                            <span class="flex items-center"><i class="fas fa-id-card mr-2 text-indigo-400"></i> {{ $student->nisn }}</span>
                            <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                            <span class="flex items-center"><i class="fas fa-layer-group mr-2 text-indigo-400"></i> {{ $student->classGroup->group_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="hidden lg:flex flex-col items-end bg-white/5 backdrop-blur-md px-6 py-3 rounded-[2rem] border border-white/10 shadow-2xl">
                        <span class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Tahun Pelajaran</span>
                        <span class="text-sm font-black text-white">{{ $student->academicYear->year ?? '-' }}</span>
                    </div>
                    <button onclick="confirmLogout()" class="w-14 h-14 md:w-16 md:h-16 bg-white/10 backdrop-blur-md rounded-[1.8rem] border border-white/10 text-white hover:bg-rose-500 hover:border-rose-400 transition-all flex items-center justify-center shadow-2xl active:scale-90 group">
                        <i class="fas fa-power-off text-xl group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        
        <!-- KPI SECTION - VIBRANT GRID -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 mb-12">
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(99,102,241,0.2)]">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Hadir</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $attendanceStats['H'] }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">Hari</small></h3>
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fas fa-calendar-check fa-4x"></i>
                    </div>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(16,185,129,0.2)]">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mata Pelajaran</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $todaySchedule->count() }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">Aktif</small></h3>
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fas fa-book-open fa-4x"></i>
                    </div>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.2)]">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-medal text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Poin Karakter</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $netPoints }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">PTS</small></h3>
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fas fa-medal fa-4x"></i>
                    </div>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(244,63,94,0.2)]">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Persentase Absen</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $attPercentage }}<small class="text-lg text-slate-300 font-black ml-1">%</small></h3>
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                        <i class="fas fa-chart-pie fa-4x"></i>
                    </div>
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
                                <h4 class="text-2xl font-black text-slate-800 mb-1">Presensi Mandiri</h4>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-2xl">
                                @if($hasCheckedInToday)
                                    <span class="badge-status success"><i class="fas fa-check-circle mr-2"></i> TERCATAT</span>
                                @else
                                    <span class="badge-status warning"><i class="fas fa-clock mr-2"></i> BELUM ABSEN</span>
                                @endif
                            </div>
                        </div>

                        @if(!$hasCheckedInToday)
                            <div class="check-in-state py-8 text-center bg-slate-50/50 rounded-[2.5rem] border border-dashed border-slate-200">
                                <div class="w-24 h-24 bg-white text-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-2xl">
                                    <i class="fas fa-fingerprint text-4xl"></i>
                                </div>
                                <h5 class="text-xl font-black text-slate-800 mb-2">Sudah Hadir Hari Ini?</h5>
                                @if($isWorkDay && $isCheckInTime && !$isHoliday)
                                    <button onclick="submitAttendance()" class="btn-action primary mx-auto block text-center no-underline border-0">
                                        ABSEN MASUK SEKARANG <i class="fas fa-arrow-right ml-3"></i>
                                    </button>
                                @else
                                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 max-w-sm mx-auto flex items-center gap-3">
                                        <i class="fas fa-info-circle text-amber-500"></i>
                                        <p class="text-[10px] text-amber-700 font-bold uppercase tracking-widest mb-0">{{ $attendanceMessage }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="attendance-details">
                                <div class="row g-4 mb-4">
                                    <div class="col-md-12 text-center">
                                        <div class="p-8 bg-emerald-50 rounded-[3rem] border border-emerald-100 flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-xl text-emerald-500 mb-4">
                                                <i class="fas fa-check-circle text-3xl"></i>
                                            </div>
                                            <span class="block text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-2">Presensi Berhasil</span>
                                            <h4 class="text-4xl font-black text-slate-800 mb-0">{{ $todayAttendance ? substr($todayAttendance->time, 0, 5) : '--:--' }} <small class="text-sm">WIB</small></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-2xl flex items-center justify-between">
                                    <div>
                                        <h5 class="text-xs font-black uppercase tracking-widest text-indigo-400 mb-1">Sistem Kehadiran</h5>
                                        <p class="text-lg font-black mb-0">Kehadiran Anda Telah Terverifikasi</p>
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
                        <h5 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Layanan Akademik</h5>
                        <span class="w-10 h-1 bg-indigo-100 rounded-full"></span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{ route('student.cbt.dashboard') }}" class="tool-btn bg-grad-indigo">
                            <div class="tool-icon"><i class="fas fa-laptop-code"></i></div>
                            <span class="tool-label">Portal CBT</span>
                        </a>
                        <a href="{{ route('siswa.cbt_results') }}" class="tool-btn bg-grad-blue">
                            <div class="tool-icon"><i class="fas fa-poll"></i></div>
                            <span class="tool-label">Hasil Ujian</span>
                        </a>
                        <a href="{{ route('siswa.raport') }}" class="tool-btn bg-grad-rose">
                            <div class="tool-icon"><i class="fas fa-graduation-cap"></i></div>
                            <span class="tool-label">Nilai & Rapor</span>
                        </a>
                        <a href="{{ route('siswa.schedule') }}" class="tool-btn bg-grad-emerald">
                            <div class="tool-icon"><i class="fas fa-calendar-alt"></i></div>
                            <span class="tool-label">Jadwal</span>
                        </a>
                        <a href="{{ route('siswa.permits') }}" class="tool-btn bg-grad-purple">
                            <div class="tool-icon"><i class="fas fa-history"></i></div>
                            <span class="tool-label">Riwayat Izin</span>
                        </a>
                        <a href="{{ route('students.card', $student->id) }}" target="_blank" class="tool-btn bg-grad-orange">
                            <div class="tool-icon"><i class="fas fa-id-card"></i></div>
                            <span class="tool-label">Kartu Siswa</span>
                        </a>
                        
                        <!-- Actions with Modals -->
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalMutabaah" class="tool-btn bg-slate-800">
                            <div class="tool-icon"><i class="fas fa-tasks text-indigo-400"></i></div>
                            <span class="tool-label">Jurnal Ibadah</span>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalTahfidz" class="tool-btn bg-slate-800">
                            <div class="tool-icon"><i class="fas fa-quran text-emerald-400"></i></div>
                            <span class="tool-label">Tahfidz</span>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuanIzin" class="tool-btn bg-slate-800">
                            <div class="tool-icon"><i class="fas fa-paper-plane text-rose-400"></i></div>
                            <span class="tool-label">Ajukan Izin</span>
                        </a>
                    </div>

                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-6 px-2">
                            <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0">Mading Digital</h6>
                            <a href="{{ route('siswa.announcements') }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">Lihat Semua</a>
                        </div>
                        @forelse($announcements->take(2) as $ann)
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-3 transition-all hover:bg-white hover:shadow-lg cursor-pointer" onclick='showAnnouncement(@json($ann))'>
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm mr-4">
                                    <i class="fas fa-bullhorn text-xs"></i>
                                </div>
                                <div class="flex-grow overflow-hidden">
                                    <h6 class="text-xs font-black text-slate-800 mb-0 truncate">{{ $ann->title }}</h6>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $ann->created_at->diffForHumans() }}</span>
                                </div>
                                <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0">Belum ada pengumuman</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- JADWAL TIMELINE -->
        <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-slate-200/50 border border-slate-50 mb-10">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h4 class="text-2xl font-black text-slate-800 mb-1">Jadwal Pelajaran</h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</p>
                </div>
                <div class="bg-indigo-50 p-2 rounded-xl text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                    {{ $todaySchedule->count() }} Mapel
                </div>
            </div>

            <div class="row g-4">
                @forelse($todaySchedule as $sch)
                    <div class="col-md-4">
                        <div class="schedule-card group">
                            <div class="time-badge">{{ substr($sch->start_time, 0, 5) }}</div>
                            <div class="p-6 pt-10">
                                <h6 class="text-lg font-black text-slate-800 mb-2 leading-tight">{{ $sch->subject->subject_name ?? '-' }}</h6>
                                <div class="flex items-center space-x-3 text-slate-400">
                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fas fa-user-tie mr-2 text-indigo-500"></i> {{ $sch->teacher->name ?? '-' }}
                                    </div>
                                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                    <div class="flex items-center text-[10px] font-bold uppercase tracking-widest">
                                        <i class="fas fa-clock mr-2 text-indigo-500"></i> {{ substr($sch->end_time, 0, 5) }}
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

@push('modals')
    @include('siswa.dashboard.modals')
@endpush

<!-- STYLES (MATCHING TEACHER) -->
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

    /* Gradients (Handled by Global Layout) */

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

    /* Badges */
    .badge-status { padding: 8px 16px; border-radius: 12px; font-size: 9px; font-weight: 900; letter-spacing: 1px; display: inline-flex; align-items: center; }
    .badge-status.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .badge-status.warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

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
    $(document).ready(function() {
        window.submitAttendance = function() {
            Swal.fire({
                title: 'KONFIRMASI PRESENSI',
                text: "Apakah Anda ingin melakukan absensi sekarang?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'YA, ABSEN!',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-[2.5rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.post('{{ route("siswa.store_attendance") }}', { _token: '{{ csrf_token() }}' })
                        .done(response => {
                            Swal.fire({ icon: 'success', title: 'BERHASIL', text: response.message, customClass: { popup: 'rounded-[2.5rem]' } }).then(() => location.reload());
                        })
                        .fail(xhr => {
                            Swal.fire({ icon: 'error', title: 'GAGAL', text: xhr.responseJSON?.message || 'Terjadi kesalahan', customClass: { popup: 'rounded-[2.5rem]' } });
                        });
                }
            });
        }
    });

    function showAnnouncement(ann) {
        Swal.fire({
            title: ann.title,
            html: `<div class="text-left text-sm leading-relaxed text-slate-600 font-medium">${ann.content}</div>`,
            confirmButtonText: 'TUTUP',
            confirmButtonColor: '#4F46E5',
            customClass: { popup: 'rounded-[2.5rem]', title: 'font-black tracking-tight' }
        });
    }

    function confirmLogout() {
        Swal.fire({
            title: 'KELUAR APLIKASI?',
            text: "Pastikan Anda sudah menyelesaikan semua kegiatan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            confirmButtonText: 'YA, KELUAR',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[2.5rem]' }
        }).then((result) => {
            if (result.isConfirmed) $('#logout-form-dashboard').submit();
        });
    }
</script>
@endpush
