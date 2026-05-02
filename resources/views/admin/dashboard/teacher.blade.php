@extends($layout)

@section('title', 'Dashboard Guru')

@section('content')
<!-- Page Container -->
<div class="relative min-h-screen bg-slate-50 pb-32">
    
    <!-- Indigo Header Section -->
    <div class="bg-indigo-600 pt-10 pb-20 px-6 rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
        <!-- Decoration Circles -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-indigo-400/20 rounded-full blur-2xl"></div>
        
        <!-- Header Info -->
        <div class="flex items-center justify-between relative z-10 mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-lg shadow-indigo-700/50">
                    <span class="text-white font-black text-xl">{{ substr($teacher->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-[0.2em] mb-0.5 opacity-80">Selamat Datang,</p>
                    <h1 class="text-white text-lg font-black leading-tight tracking-tight">{{ $teacher->name }}</h1>
                </div>
            </div>
            <a href="{{ route('teacher.announcements') }}" class="w-11 h-11 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 text-white relative shadow-sm active:scale-90 transition-all">
                <i class="fas fa-bell text-sm"></i>
                @if($unreadAnnouncementsCount > 0)
                    <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 border-2 border-indigo-600 rounded-full animate-pulse"></span>
                @endif
            </a>
        </div>

        <!-- Floating Quick Stats -->
        <div class="grid grid-cols-2 gap-4 relative z-10">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-4 flex items-center space-x-3 shadow-lg">
                <div class="w-9 h-9 bg-indigo-500/30 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-calendar-check text-xs"></i>
                </div>
                <div>
                    <p class="text-indigo-100 text-[8px] font-black uppercase tracking-wider leading-none mb-1">Hadir</p>
                    <p class="text-white font-black text-sm leading-none">{{ $myAttendances->where('status', 'present')->count() }} Hari</p>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-4 flex items-center space-x-3 shadow-lg">
                <div class="w-9 h-9 bg-amber-500/30 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-clock text-xs"></i>
                </div>
                <div>
                    <p class="text-indigo-100 text-[8px] font-black uppercase tracking-wider leading-none mb-1">Lambat</p>
                    <p class="text-white font-black text-sm leading-none">{{ $myAttendances->where('status', 'late')->count() }} Hari</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Body -->
    <div class="px-6 -mt-8 relative z-20">
        <!-- Attendance Action Card -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 p-6 mb-8 border border-slate-50">
            <div class="flex justify-between items-center mb-6 px-1">
                <div>
                    <h3 class="text-slate-800 font-black text-base mb-0 tracking-tight">Presensi Harian</h3>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100/50">
                    <i class="fas fa-fingerprint text-xl"></i>
                </div>
            </div>

            <div class="w-full">
                @if(!$todayAttendance)
                    <form id="formCheckIn" action="{{ route('teacher.attendance.check-in') }}" method="POST">
                        @csrf
                        <button type="button" onclick="submitAttendance('#formCheckIn', 'Selamat bertugas! Absen Masuk berhasil.')" class="w-full bg-gradient-to-br from-indigo-600 to-indigo-700 text-white rounded-3xl py-4 px-6 flex items-center justify-between shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all">
                            <div class="flex items-center">
                                <div class="bg-white/20 w-11 h-11 rounded-2xl flex items-center justify-center mr-4">
                                    <i class="fas fa-sign-in-alt text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <span class="block font-black text-base leading-none mb-1">Mulai Kerja</span>
                                    <span class="text-indigo-100 text-[9px] font-bold uppercase tracking-[0.1em]">Absen Masuk Sekarang</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-indigo-300 text-xs"></i>
                        </button>
                    </form>
                @elseif($todayAttendance && !$todayAttendance->check_out)
                    <div class="flex space-x-3">
                        <div class="flex-1 bg-slate-50 rounded-3xl p-4 flex flex-col items-center justify-center border border-slate-100 shadow-inner">
                            <span class="text-slate-400 text-[8px] font-black uppercase tracking-widest mb-1 opacity-60">Jam Masuk</span>
                            <span class="text-xl font-black text-emerald-500 tracking-tighter">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</span>
                        </div>
                        <form id="formCheckOut" action="{{ route('teacher.attendance.check-out') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="button" onclick="submitAttendance('#formCheckOut', 'Kerja bagus hari ini! Absen Pulang berhasil.')" class="w-full h-full bg-rose-500 hover:bg-rose-600 text-white rounded-3xl flex flex-col items-center justify-center shadow-lg shadow-rose-100 active:scale-[0.98] transition-all py-4">
                                <i class="fas fa-sign-out-alt mb-1 text-sm"></i>
                                <span class="font-black text-[11px] uppercase tracking-widest">Selesai</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4 bg-emerald-50 border border-emerald-100 rounded-[2rem] p-5 shadow-sm">
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                            <i class="fas fa-check-double text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-emerald-900 font-black text-[11px] mb-0.5 uppercase tracking-wider">Presensi Lengkap</h4>
                            <p class="text-emerald-600 text-[10px] font-bold leading-tight">Terima kasih atas dedikasi Anda hari ini!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Menu Tiles -->
        <div class="grid grid-cols-4 gap-4 mb-10 px-2">
            @php
                $quickMenus = [
                    ['icon' => 'fa-calendar-alt', 'label' => 'Jadwal', 'color' => 'bg-indigo-50 text-indigo-500', 'route' => 'teacher.schedule'],
                    ['icon' => 'fa-bullhorn', 'label' => 'Info', 'color' => 'bg-rose-50 text-rose-500', 'route' => 'teacher.announcements'],
                    ['icon' => 'fa-file-invoice', 'label' => 'Log', 'color' => 'bg-sky-50 text-sky-500', 'route' => 'teacher.attendance.dashboard'],
                    ['icon' => 'fa-user-circle', 'label' => 'Profil', 'color' => 'bg-purple-50 text-purple-500', 'route' => 'profile.show'],
                ];
            @endphp
            @foreach($quickMenus as $menu)
                <a href="{{ route($menu['route']) }}" class="flex flex-col items-center group">
                    <div class="w-14 h-14 {{ $menu['color'] }} rounded-[1.5rem] flex items-center justify-center mb-2 shadow-sm group-active:scale-90 transition-all border border-white">
                        <i class="fas {{ $menu['icon'] }} text-lg"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- My Homeroom Class (Simplified) -->
        @if($homeroomClass)
        <div class="mb-10">
            <div class="flex justify-between items-center mb-4 px-2">
                <h3 class="text-slate-800 font-black text-base tracking-tight">Wali Kelas {{ $homeroomClass->kelas_lengkap }}</h3>
                <button onclick="$('#studentListModal').modal('show')" class="text-indigo-600 text-[9px] font-black uppercase tracking-[0.15em] bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">Detail Siswa</button>
            </div>
            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-100/50 p-6 border border-slate-50 relative overflow-hidden">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest leading-none mb-1">Total Anak Didik</p>
                        <h4 class="text-slate-800 font-black text-xl leading-none">{{ $myStudents->count() }} Siswa</h4>
                    </div>
                </div>

                <div class="flex space-x-3 overflow-x-auto pb-1 no-scrollbar">
                    @foreach($myStudents->take(10) as $student)
                    <div class="flex-shrink-0 text-center w-12 group">
                        <div class="w-11 h-11 bg-slate-50 border border-slate-100 rounded-2xl mx-auto mb-2 flex items-center justify-center text-emerald-500 font-black text-base group-active:bg-emerald-500 group-active:text-white transition-all shadow-sm">
                            {{ substr($student->nama_lengkap, 0, 1) }}
                        </div>
                        <p class="text-[8px] text-slate-500 font-bold truncate leading-none uppercase">{{ explode(' ', $student->nama_lengkap)[0] }}</p>
                    </div>
                    @endforeach
                    @if($myStudents->count() > 10)
                    <button onclick="$('#studentListModal').modal('show')" class="flex-shrink-0 w-11 h-11 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm">
                        <span class="text-[9px] font-black">+{{ $myStudents->count() - 10 }}</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Academic Schedule Timeline -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-5 px-2">
                <div>
                    <h3 class="text-slate-800 font-black text-base leading-none mb-1 tracking-tight">Agenda Hari Ini</h3>
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest">{{ $schedules->count() }} Mata Pelajaran</p>
                </div>
                <a href="{{ route('teacher.schedule') }}" class="text-indigo-600 font-black text-[9px] uppercase tracking-wider bg-white border border-slate-100 px-3 py-1.5 rounded-full shadow-sm">Lihat Semua</a>
            </div>

            <div class="space-y-4">
                @forelse($schedules as $schedule)
                    <div onclick="showClassStudents({{ $schedule->class_group_id }})" class="bg-white rounded-[2rem] p-5 shadow-sm border border-slate-50 flex items-center justify-between group hover:shadow-md active:scale-[0.97] transition-all cursor-pointer">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex flex-col items-center justify-center border border-slate-100 group-hover:bg-indigo-600 group-hover:border-indigo-600 transition-all duration-300">
                                <span class="text-indigo-600 font-black text-[11px] leading-none group-hover:text-white">{{ $schedule->studyPeriod->start_time ?? '--:--' }}</span>
                                <div class="w-4 h-0.5 bg-slate-200 group-hover:bg-indigo-400 my-1.5"></div>
                                <span class="text-slate-400 text-[8px] font-black uppercase group-hover:text-indigo-200">Selesai</span>
                            </div>
                            <div>
                                <h4 class="text-slate-800 font-black text-sm mb-1.5 leading-tight">{{ $schedule->subject->name ?? '-' }}</h4>
                                <div class="flex items-center">
                                    <div class="px-2 py-0.5 bg-indigo-50 text-indigo-500 rounded-md text-[8px] font-black uppercase tracking-widest border border-indigo-100">
                                        Kelas {{ $schedule->classGroup->kelas_lengkap ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-9 h-9 bg-slate-50 rounded-xl flex items-center justify-center text-slate-300 group-hover:text-indigo-500">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 shadow-inner opacity-60">
                        <i class="fas fa-mug-hot text-2xl text-slate-200 mb-3 block"></i>
                        <p class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em]">Tidak Ada Jadwal</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- All Modals (Placed at bottom to avoid layout shift) -->
@if($homeroomClass)
<div class="modal fade" id="studentListModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable px-4" role="document">
        <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="modal-header border-0 p-6 pb-2 flex justify-between items-center bg-white">
                <h5 class="font-black text-slate-800 text-lg">Siswa {{ $homeroomClass->kelas_lengkap }}</h5>
                <button type="button" class="w-9 h-9 bg-slate-50 rounded-full flex items-center justify-center text-slate-400" data-dismiss="modal">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="modal-body p-6 pt-2 bg-white">
                <div class="space-y-3">
                    @foreach($myStudents as $student)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 shadow-sm">
                        <div class="flex items-center space-x-4">
                            <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center text-emerald-500 font-black shadow-sm border border-slate-100">
                                {{ substr($student->nama_lengkap, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-slate-800 mb-0 leading-tight">{{ $student->nama_lengkap }}</h5>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $student->nis ?? 'NIS --' }}</p>
                            </div>
                        </div>
                        <a href="tel:{{ $student->phone ?? '#' }}" class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm active:scale-90 transition-all">
                            <i class="fas fa-phone-alt text-xs"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="classStudentModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable px-4" role="document">
        <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="modal-header border-0 p-6 pb-2 flex justify-between items-center bg-white">
                <h5 class="font-black text-slate-800 text-lg" id="classTitle">Daftar Siswa</h5>
                <button type="button" class="w-9 h-9 bg-slate-50 rounded-full flex items-center justify-center text-slate-400" data-dismiss="modal">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="modal-body p-6 pt-2 bg-white" id="classStudentList">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function submitAttendance(formId, successMessage) {
        const form = $(formId);
        const url = form.attr('action');
        const data = form.serialize();

        Swal.fire({
            title: 'Konfirmasi',
            text: "Lanjutkan presensi sekarang?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-5 py-3 font-bold',
                cancelButton: 'rounded-xl px-5 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                $.post(url, data)
                    .done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
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
                        Swal.fire({ icon: 'error', title: 'Gagal', text: message, customClass: { popup: 'rounded-[2rem]' } });
                    });
            }
        });
    }

    function showClassStudents(classId) {
        Swal.fire({
            title: 'Memuat data...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        $.get('{{ url("admin/class-students") }}/' + classId)
            .done(response => {
                Swal.close();
                $('#classTitle').text('Siswa ' + response.class);
                let html = '';
                response.students.forEach(student => {
                    html += `
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 mb-3 shadow-sm">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-500 font-black shadow-sm border border-slate-100">
                                    ${student.nama_lengkap.charAt(0)}
                                </div>
                                <div>
                                    <h5 class="text-sm font-black text-slate-800 mb-0 leading-tight">${student.nama_lengkap}</h5>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">${student.nis || 'NIS --'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                if (response.students.length === 0) {
                    html = '<div class="text-center py-10"><p class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Belum ada siswa.</p></div>';
                }

                $('#classStudentList').html(html);
                $('#classStudentModal').modal('show');
            })
            .fail(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data siswa', customClass: { popup: 'rounded-[2rem]' } });
            });
    }
</script>
@endpush
@endsection
