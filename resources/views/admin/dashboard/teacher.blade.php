@extends($layout)

@section('title', 'Dashboard Guru')

@section('content')
<!-- Premium Dashboard Guru - Nature Edition v4.8 -->
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
                    <h1 class="text-2xl font-black text-white leading-tight">Halo, {{ explode(' ', $teacher->name)[0] }}!</h1>
                    <div class="flex items-center mt-1 space-x-2">
                        <span class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest opacity-80">NIP. {{ $teacher->nip ?? '-' }}</span>
                        <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                        <span class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest opacity-80">{{ $teacher->specialty ?? 'Tenaga Pengajar' }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('teacher.announcements') }}" class="relative p-3 bg-white/10 rounded-2xl border border-white/20 text-white">
                <i class="fas fa-bell text-lg"></i>
                @if($unreadAnnouncementsCount > 0)
                    <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 rounded-full border-2 border-emerald-700 text-[8px] flex items-center justify-center font-bold">{{ $unreadAnnouncementsCount }}</span>
                @endif
            </a>
        </div>

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

        @if(!$todayAttendance)
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

    <!-- Quick Services Grid (New Informative Section) -->
    <div class="mb-10">
        <h3 class="text-slate-800 font-black text-sm uppercase tracking-widest mb-6 px-2">Layanan Akademik</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('student-attendances.scanner') }}" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-3 transition-all active:scale-95">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-indigo-100">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <span class="block text-slate-800 font-black text-xs leading-tight">Scanner QR<br>Siswa</span>
                </div>
            </a>
            <a href="{{ route('student-grades.raport') }}" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-3 transition-all active:scale-95">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-amber-100">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <span class="block text-slate-800 font-black text-xs leading-tight">Input Nilai<br>Raport</span>
                </div>
            </a>
            <a href="{{ route('teacher.schedule') }}" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-3 transition-all active:scale-95">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-emerald-100">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <span class="block text-slate-800 font-black text-xs leading-tight">Jadwal<br>Mengajar</span>
                </div>
            </a>
            <a href="{{ route('teacher.announcements') }}" class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-3 transition-all active:scale-95">
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-sky-100">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <span class="block text-slate-800 font-black text-xs leading-tight">Informasi<br>Sekolah</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Agenda Timeline -->
    <div class="flex items-center justify-between mb-6 px-2">
        <h3 class="text-slate-800 font-black text-sm uppercase tracking-widest">Jadwal Mengajar Hari Ini</h3>
        <span class="text-emerald-600 font-bold text-[10px] bg-emerald-50 px-3 py-1 rounded-full">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</span>
    </div>

    <div class="space-y-4">
        @forelse($schedules as $schedule)
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex items-center space-x-5 transition-all active:scale-98">
                <div class="text-center bg-slate-50 p-3 rounded-2xl border border-slate-100 min-w-[75px] shadow-inner">
                    <span class="block text-emerald-600 font-black text-base">{{ \Carbon\Carbon::parse($schedule->studyPeriod->start_time)->format('H:i') }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">WIB</span>
                </div>
                <div class="flex-grow">
                    <h4 class="text-slate-800 font-black text-base mb-1 leading-tight">{{ $schedule->subject->name ?? '-' }}</h4>
                    <div class="flex items-center space-x-2">
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-3 py-1 rounded-lg uppercase tracking-tighter">Kelas {{ $schedule->classGroup->kelas_lengkap ?? '-' }}</span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Jam Ke-{{ $schedule->studyPeriod->period_name ?? '-' }}</span>
                    </div>
                </div>
                <div class="text-slate-200">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <i class="fas fa-mug-hot text-2xl text-slate-200"></i>
                </div>
                <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Tidak ada jadwal mengajar hari ini</p>
            </div>
        @endforelse
    </div>

    <!-- Homeroom Identity Banner -->
    @if($homeroomClass)
    <div class="mt-12 bg-gradient-to-br from-emerald-800 to-emerald-950 rounded-[2.5rem] p-8 shadow-2xl shadow-emerald-900/40 relative overflow-hidden border border-white/5">
        <!-- Decoration -->
        <div class="absolute top-[-30px] right-[-30px] w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-5">
                <div class="w-16 h-16 bg-emerald-500/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center text-emerald-400 border border-emerald-500/30 shadow-inner">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <div>
                    <span class="text-emerald-400 text-[10px] font-black uppercase tracking-widest opacity-80">Wali Kelas Aktif</span>
                    <h4 class="text-white font-black text-2xl leading-tight">Kelas {{ $homeroomClass->kelas_lengkap }}</h4>
                    <div class="flex items-center mt-2 space-x-2">
                        <span class="bg-white/10 text-emerald-100 text-[8px] font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-white/5">Semester Ganjil</span>
                        <span class="bg-white/10 text-emerald-100 text-[8px] font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-white/5">TA 2023/2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
