@extends($layout)

@section('title', 'Smart Madrasah')

@section('content')
<div class="min-h-screen bg-slate-50 pb-10">
    <!-- Premium Header Area -->
    <div class="relative overflow-hidden bg-indigo-600 pt-12 pb-24 px-6 rounded-b-[3.5rem] shadow-2xl">
        <!-- Abstract Background Shapes -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 bottom-0 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>

        <div class="relative flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=fff&color=4f46e5" class="w-14 h-14 rounded-2xl border-2 border-white/50 shadow-inner object-cover">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 border-2 border-indigo-600 rounded-full"></div>
                </div>
                <div class="text-white">
                    <h2 class="text-xl font-bold tracking-tight leading-tight">{{ $teacher->name }}</h2>
                    <p class="text-indigo-100 text-[10px] uppercase font-black tracking-widest opacity-80">{{ $teacher->position ?? 'Guru Madrasah' }}</p>
                </div>
            </div>
            <button class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/10">
                <i class="far fa-bell text-lg"></i>
            </button>
        </div>

        <!-- Floating Quick Stats -->
        <div class="relative grid grid-cols-2 gap-4">
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/30 flex items-center justify-center text-white">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black text-white leading-none">{{ $schedules->count() }}</span>
                    <small class="text-indigo-100 text-[10px] font-bold uppercase opacity-80">Kelas Hari Ini</small>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/30 flex items-center justify-center text-white">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                <div>
                    <span class="block text-2xl font-black text-white leading-none">{{ $totalSchedules }}</span>
                    <small class="text-indigo-100 text-[10px] font-bold uppercase opacity-80">Jam / Minggu</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section Area -->
    <div class="px-6 -mt-10">
        <!-- Attendance Card (Primary Focus) -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-100/40 p-6 mb-8 border border-slate-50 relative overflow-hidden group">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-slate-800 font-bold text-lg mb-0">Presensi Kehadiran</h3>
                    <p class="text-slate-400 text-xs font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                </div>
                <div class="text-indigo-600 bg-indigo-50 w-10 h-10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-fingerprint text-xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @if(!$todayAttendance)
                    <form id="formCheckIn" action="{{ route('teacher.attendance.check-in') }}" method="POST">
                        @csrf
                        <button type="button" onclick="submitAttendance('#formCheckIn', 'Check-in berhasil!')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl py-4 px-6 flex items-center justify-between shadow-lg shadow-indigo-200 active:scale-95 transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="bg-white/20 w-10 h-10 rounded-xl flex items-center justify-center mr-3 group-hover:rotate-12 transition-transform">
                                    <i class="fas fa-sign-in-alt text-lg"></i>
                                </div>
                                <span class="font-bold tracking-wide">Absen Masuk Sekarang</span>
                            </div>
                            <i class="fas fa-chevron-right text-indigo-300"></i>
                        </button>
                    </form>
                @elseif($todayAttendance && !$todayAttendance->check_out)
                    <div class="flex items-stretch space-x-3 h-20">
                        <div class="flex-1 bg-slate-50 rounded-2xl p-3 flex flex-col items-center justify-center border border-slate-100 shadow-inner">
                            <small class="text-slate-400 text-[9px] uppercase font-black tracking-widest mb-1">Jam Masuk</small>
                            <span class="text-lg font-black text-emerald-500">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                        </div>
                        <form id="formCheckOut" action="{{ route('teacher.attendance.check-out') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="button" onclick="submitAttendance('#formCheckOut', 'Selamat istirahat, check-out berhasil!')" class="w-full h-full bg-rose-500 hover:bg-rose-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200 active:scale-95 transition-all">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span class="font-bold">Pulang</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-3xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                <i class="fas fa-check-double text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-emerald-800 font-bold text-sm">Selesai Hari Ini</h4>
                                <p class="text-emerald-600/70 text-[10px] font-bold uppercase tracking-wider">Terima kasih atas dedikasinya</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-emerald-700 font-black text-sm">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Secondary Menu Grid -->
        <h3 class="text-slate-800 font-bold text-sm mb-4 px-1">Akses Cepat</h3>
        <div class="grid grid-cols-3 gap-6 mb-10">
            @php
                $menus = [
                    ['icon' => 'fa-users', 'label' => 'Absensi Siswa', 'color' => 'bg-emerald-50 text-emerald-500', 'route' => 'student-attendances.index'],
                    ['icon' => 'fa-calendar-alt', 'label' => 'Jadwal Saya', 'color' => 'bg-indigo-50 text-indigo-500', 'route' => 'teacher.schedule'],
                    ['icon' => 'fa-file-invoice', 'label' => 'Riwayat Absen', 'color' => 'bg-sky-50 text-sky-500', 'route' => 'teacher.attendance.dashboard'],
                ];
            @endphp
            @foreach($menus as $menu)
                <a href="{{ route($menu['route']) }}" class="flex flex-col items-center group">
                    <div class="w-16 h-16 {{ $menu['color'] }} rounded-[1.5rem] flex items-center justify-center mb-2 shadow-sm border border-white group-active:scale-90 transition-all duration-200">
                        <i class="fas {{ $menu['icon'] }} text-2xl"></i>
                    </div>
                    <span class="text-slate-600 text-[11px] font-bold tracking-tight text-center leading-tight">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- Schedule List Area -->
        <div class="flex justify-between items-end mb-5 px-1">
            <div>
                <h3 class="text-slate-800 font-bold text-lg leading-none mb-1">Agenda Hari Ini</h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $schedules->count() }} Kelas Mengajar</p>
            </div>
            <a href="{{ route('teacher.schedule') }}" class="text-indigo-600 font-black text-[10px] uppercase tracking-wider bg-indigo-50 px-3 py-1.5 rounded-full no-underline">Lihat Semua</a>
        </div>

        <div class="space-y-4">
            @forelse($schedules as $schedule)
                <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex flex-col items-center justify-center border border-slate-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                <span class="text-indigo-600 font-black text-xs leading-none">{{ $schedule->studyPeriod->start_time ?? '--:--' }}</span>
                                <div class="w-4 h-0.5 bg-slate-200 my-1"></div>
                                <span class="text-slate-400 text-[8px] font-black uppercase">{{ $schedule->studyPeriod->period_name ?? '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-slate-800 font-bold text-sm mb-1 leading-tight">{{ $schedule->subject->name ?? '-' }}</h4>
                            <div class="flex items-center space-x-3 text-slate-400">
                                <div class="flex items-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mr-1.5"></div>
                                    <span class="text-[10px] font-bold">Kelas {{ $schedule->classGroup->class_group ?? '-' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="far fa-clock mr-1 text-[10px]"></i>
                                    <span class="text-[10px] font-medium">{{ $schedule->studyPeriod->end_time ?? '--:--' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('student-attendances.index') }}" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-90">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @empty
                <div class="text-center py-12 px-6 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 shadow-inner">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-white">
                        <i class="fas fa-mug-hot text-slate-200 text-3xl"></i>
                    </div>
                    <h4 class="text-slate-500 font-bold text-sm mb-1">Waktunya Santai!</h4>
                    <p class="text-slate-300 text-[10px] font-bold uppercase tracking-widest">Tidak ada jadwal hari ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Premium Animations & Smoothness */
    .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    }
    
    .group:active {
        transform: scale(0.98);
    }

    .btn:active {
        transform: scale(0.95);
    }
    
    /* Smooth transition for hover effects */
    * {
        transition: all 0.3s ease-in-out;
    }
    
    /* Custom Card Style */
    .rounded-2xl { border-radius: 1.25rem; }
    .rounded-3xl { border-radius: 1.75rem; }
</style>
@push('scripts')
<script>
    function submitAttendance(formId, successMessage) {
        const form = $(formId);
        const url = form.attr('action');
        const data = form.serialize();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin melakukan presensi sekarang?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Absen Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.post(url, data)
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: successMessage,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .fail(xhr => {
                        let message = 'Terjadi kesalahan sistem';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: message
                        });
                    });
            }
        });
    }
</script>
@endpush
@endsection
