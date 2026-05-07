@extends('layouts.ppdb')

@section('title', 'Dashboard Siswa')

@section('content')
<!-- PREMIUM STUDENT DASHBOARD - ULTRA MODERN (SAME AS TEACHER) -->
<div class="dashboard-wrapper pb-20">
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
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <div class="profile-frame p-1 rounded-[2rem] bg-white/20 backdrop-blur-md">
                        @if($student->profile && $student->profile->foto)
                            <img src="{{ asset('storage/' . $student->profile->foto) }}" class="w-20 h-20 rounded-[1.8rem] object-cover shadow-2xl border-2 border-white/50">
                        @else
                            <div class="w-20 h-20 bg-white/20 rounded-[1.8rem] flex items-center justify-center border-2 border-white/50 shadow-2xl backdrop-blur-md">
                                <span class="text-3xl font-black text-white">{{ substr($student->nama_lengkap, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-white text-center md:text-left">
                        <span class="bg-white/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2 inline-block">{{ $greeting }}</span>
                        <h1 class="text-3xl font-black tracking-tight leading-tight">{{ $student->nama_lengkap }}</h1>
                        <p class="text-white/70 text-xs font-bold mt-1"><i class="fas fa-id-card mr-2"></i> {{ $student->nisn }} • {{ $student->classGroup->group_name ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Status</span>
                        <span class="text-xs font-black text-white bg-indigo-500/80 px-3 py-1 rounded-full border border-indigo-400">SISWA AKTIF</span>
                    </div>
                    <button onclick="confirmLogout()" class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-3xl border border-white/10 text-white hover:bg-rose-500 transition-all flex items-center justify-center shadow-xl">
                        <i class="fas fa-power-off text-lg"></i>
                    </button>
                    <form id="logout-form-dashboard" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        
        <!-- KPI SECTION - VIBRANT GRID -->
        <div class="row g-4 mb-10">
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hadir</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $attendanceStats['H'] }} <small class="text-[10px] text-slate-400">Hari</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jadwal</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $todaySchedule->count() }} <small class="text-[10px] text-slate-400">Mapel</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-medal"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Poin Akhlak</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $netPoints }} <small class="text-[10px] text-slate-400">PTS</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Presensi</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $attPercentage }} <small class="text-[10px] text-slate-400">%</small></h3>
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

                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('student.cbt.dashboard') }}" class="tool-btn bg-grad-indigo">
                            <div class="tool-icon"><i class="fas fa-laptop-code"></i></div>
                            <span class="tool-label">Portal CBT</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalMutabaah" class="tool-btn bg-grad-blue">
                            <div class="tool-icon"><i class="fas fa-tasks"></i></div>
                            <span class="tool-label">Ibadah Harian</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalTahfidz" class="tool-btn bg-grad-green">
                            <div class="tool-icon"><i class="fas fa-quran"></i></div>
                            <span class="tool-label">Tahfidz</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuanIzin" class="tool-btn bg-grad-purple">
                            <div class="tool-icon"><i class="fas fa-envelope-open-text"></i></div>
                            <span class="tool-label">Izin/Sakit</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="{{ route('students.card', $student->id) }}" target="_blank" class="tool-btn bg-grad-emerald">
                            <div class="tool-icon"><i class="fas fa-id-card"></i></div>
                            <span class="tool-label">Kartu Siswa</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPoin" class="tool-btn bg-grad-orange">
                            <div class="tool-icon"><i class="fas fa-medal"></i></div>
                            <span class="tool-label">Riwayat Poin</span>
                            <i class="fas fa-chevron-right tool-arrow"></i>
                        </a>
                    </div>

                    <!-- MADING DIGITAL MINI -->
                    <div class="mt-10">
                        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 px-2">Mading Digital</h6>
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
