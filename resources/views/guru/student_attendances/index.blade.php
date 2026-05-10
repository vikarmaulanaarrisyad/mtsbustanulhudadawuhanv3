@extends($layout)
@section('title', 'Rekap Kehadiran Siswa')

@section('content')
<div class="dashboard-wrapper pb-20">

    {{-- HEADER BANNER --}}
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center border border-white/30 shadow-xl">
                        <i class="fas fa-clipboard-user text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <span class="bg-white/20 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block">Monitoring Siswa</span>
                        <h1 class="text-2xl font-black leading-tight">Rekap Kehadiran</h1>
                        <p class="text-white/70 text-xs font-bold mt-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $teacher->name }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('guru.student-attendances.summary') }}" class="flex items-center space-x-2 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-black px-5 py-3 rounded-2xl shadow-lg transition-all">
                        <i class="fas fa-chart-bar mr-2"></i> Statistik Per Siswa
                    </a>
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2 bg-white/15 hover:bg-white/25 text-white text-xs font-black px-5 py-3 rounded-2xl border border-white/20 transition-all shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-30px] w-56 h-56 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-20px] bottom-[-30px] w-40 h-40 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">

        {{-- FILTER CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 mb-6 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center space-x-3 bg-slate-50/50">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-indigo-600 text-sm"></i>
                </div>
                <div>
                    <h5 class="font-black text-slate-800 text-sm mb-0">Filter Data Kehadiran</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0">Pilih kelas yang Anda ampu dan tanggal rekap</p>
                </div>
            </div>
            <div class="p-6">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Pilih Kelas</label>
                        <select id="sel-class" class="form-control rounded-xl border-slate-200 font-bold text-sm" style="height:46px">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($myClasses as $cls)
                                <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->kelas_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Pilih Tanggal</label>
                        <input type="date" id="sel-date" class="form-control rounded-xl border-slate-200 font-bold text-sm" style="height:46px" value="{{ $selectedDate }}">
                    </div>
                    <div class="col-md-3">
                        <button onclick="loadAttendance()" id="btn-load" class="w-100 btn font-black text-sm rounded-xl px-4 shadow-lg shadow-indigo-200" style="height:46px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border:none">
                            <i class="fas fa-search mr-2"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA TABLE CARD --}}
        <div id="data-card" class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden {{ $selectedClassId ? '' : 'd-none' }}">
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600 text-sm"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-slate-800 text-sm mb-0">Daftar Kehadiran Siswa</h5>
                        <p class="text-[10px] text-slate-400 font-bold mb-0">
                            Kelas: <strong class="text-indigo-600" id="lbl-class">{{ $selectedClass?->kelas_lengkap ?? '-' }}</strong> | 
                            Tanggal: <strong class="text-indigo-600" id="lbl-date">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</strong>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="$('#monthlyModal').modal('show')" class="btn btn-sm btn-soft-rose border-0 font-black text-xs rounded-xl px-4 py-2 text-rose-600 hover:bg-rose-100 transition-all">
                        <i class="fas fa-calendar-alt mr-1"></i> Rekap Bulanan
                    </button>
                    <button onclick="window.print()" class="btn btn-sm btn-light border font-black text-xs rounded-xl px-4 py-2 text-slate-600 hover:bg-slate-100 transition-all">
                        <i class="fas fa-print text-indigo-600 mr-1"></i> Cetak PDF
                    </button>
                </div>
            </div>

            <!-- Modal Rekap Bulanan (PREMIUM VERSION) -->
            <div class="modal fade" id="monthlyModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered px-4" role="document">
                    <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden bg-white/95 backdrop-blur-xl">
                        <!-- Header Banner -->
                        <div class="bg-gradient-indigo p-8 relative overflow-hidden">
                            <div class="bg-circle-1 opacity-2"></div>
                            <div class="bg-circle-2 opacity-1"></div>
                            <div class="relative z-10 flex justify-between items-center text-white">
                                <div>
                                    <h3 class="font-black mb-1 flex items-center">
                                        <i class="fas fa-file-contract mr-3 animate__animated animate__fadeInLeft"></i> 
                                        Rekap Absensi Bulanan
                                    </h3>
                                    <p class="text-xs font-bold opacity-80 uppercase tracking-[2px]">Laporan Matriks Kehadiran Per Kelas</p>
                                </div>
                                <button type="button" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-2xl flex items-center justify-center text-white transition-all shadow-sm" data-dismiss="modal">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="row no-gutters">
                                <!-- LEFT COLUMN: TIPS & INFO -->
                                <div class="col-md-5 bg-slate-50/50 p-8 border-right border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Informasi Dokumen</p>
                                    
                                    <div class="space-y-4">
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-map"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Matriks 31 Hari</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Tampilan Per Tanggal</p>
                                            </div>
                                        </div>

                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Rekapitulasi Total</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Jumlah H, S, I, A, T</p>
                                            </div>
                                        </div>

                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                                                <i class="fas fa-signature"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Legalitas</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Kolom Tanda Tangan Wali</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-8 border-top border-slate-100">
                                        <div class="flex items-center space-x-3 text-indigo-600 mb-2">
                                            <i class="fas fa-lightbulb text-sm"></i>
                                            <span class="text-[10px] font-black uppercase tracking-wider">Tips Guru</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 leading-relaxed">
                                            Gunakan laporan ini untuk pengisian buku absen manual atau pelaporan bulanan ke bagian kesiswaan. Format otomatis menyesuaikan jumlah hari dalam bulan terpilih.
                                        </p>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN: FORM -->
                                <div class="col-md-7 p-8">
                                    <form id="monthlyForm" action="{{ route('student-attendances.monthly') }}" method="GET" target="_blank">
                                        <input type="hidden" name="class_group_id" id="monthly_class_id">
                                        
                                        <div class="space-y-6">
                                            <div>
                                                <h5 class="text-sm font-black text-slate-800 mb-1">Periode Laporan</h5>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih bulan dan tahun ajaran</p>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Bulan Laporan</label>
                                                <div class="relative">
                                                    <select name="month" class="w-full appearance-none bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                                        @for($m=1; $m<=12; $m++)
                                                            <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <i class="fas fa-chevron-down text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Tahun Pelajaran</label>
                                                <div class="relative">
                                                    <select name="year" class="w-full appearance-none bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:bg-white outline-none transition-all">
                                                        @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                                            <option value="{{ $y }}">{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <i class="fas fa-chevron-down text-xs"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-4 space-y-3">
                                                <button type="button" onclick="submitMonthly('pdf')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all flex items-center justify-center space-x-3 group">
                                                    <span class="uppercase text-xs tracking-widest text-white">GENERATE PDF</span>
                                                    <i class="fas fa-file-pdf transition-transform group-hover:scale-110"></i>
                                                </button>
                                                
                                                <button type="button" onclick="submitMonthly('excel')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-100 active:scale-[0.98] transition-all flex items-center justify-center space-x-3 group">
                                                    <span class="uppercase text-xs tracking-widest text-white">EXPORT EXCEL</span>
                                                    <i class="fas fa-file-excel transition-transform group-hover:scale-110"></i>
                                                </button>
                                                
                                                <p class="text-center mt-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Otomatis diunduh dalam format PDF & EXCEL</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="attendance-table" class="table align-middle mb-0 w-100">
                    <thead style="background:#f8fafc;border-bottom:2px solid #e2e8f0">
                        <tr>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="50">NO</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">IDENTITAS SISWA</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="150">WAKTU SCAN</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="120">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables Content --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- EMPTY STATE --}}
        <div id="empty-state" class="{{ $selectedClassId ? 'd-none' : '' }} text-center py-16">
            <div class="w-24 h-24 bg-indigo-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-fingerprint text-indigo-400 text-4xl"></i>
            </div>
            <h5 class="font-black text-slate-700 mb-2">Belum Ada Kelas Terpilih</h5>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">Silakan pilih kelas dan tanggal pada panel filter di atas untuk melihat rekap kehadiran siswa Anda.</p>
        </div>

    </div>
</div>

<style>
body { background:#f8fafc; font-family:'Outfit',sans-serif; }
.bg-grad-indigo { background:linear-gradient(135deg,#1e1b4b 0%,#312e81 100%); }
.bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
.bg-gradient-rose { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important; }
.opacity-8 { opacity: 0.8; }
.opacity-2 { opacity: 0.2; }
.opacity-1 { opacity: 0.1; }
.bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
.bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
.bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
.header-banner { padding-top:40px; padding-bottom:80px; }

/* Table overrides */
#attendance-table tbody tr { border-bottom:1px solid #f1f5f9; transition:all 0.2s ease; }
#attendance-table tbody tr:hover { background:#f8fafc; transform:translateY(-1px); }
#attendance-table td { padding:1rem; vertical-align:middle; }

/* DataTables styling */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #6366f1 !important; color: white !important; border: none; border-radius: 8px; font-weight: bold;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px; border: none; margin: 0 2px;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e2e8f0; border-radius: 12px; padding: 6px 15px; outline: none;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

@media print {
    body * { visibility: hidden; }
    #data-card, #data-card * { visibility: visible; }
    #data-card { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; }
    .dataTables_filter, .dataTables_paginate, .dataTables_info, .dataTables_length { display: none !important; }
}
@media(max-width:768px){
    .header-banner { padding-top:30px; padding-bottom:70px; }
}
</style>
@endsection

@push('scripts')
<script>
let table;

$(function() {
    @if($selectedClassId)
        initDataTable();
    @endif
});

function loadAttendance() {
    const classId = $('#sel-class').val();
    const date = $('#sel-date').val();

    if (!classId) {
        Swal.fire({ icon:'warning', title:'Perhatian', text:'Pilih Kelas terlebih dahulu!', customClass:{popup:'rounded-[2rem]'} });
        return;
    }

    const className = $('#sel-class option:selected').text().trim();
    // Format date simple
    const dateObj = new Date(date);
    const dateStr = dateObj.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});

    $('#lbl-class').text(className);
    $('#lbl-date').text(dateStr);
    
    $('#empty-state').addClass('d-none');
    $('#data-card').removeClass('d-none');

    initDataTable(classId, date);
}

function initDataTable(classId = null, date = null) {
    if (table) {
        table.destroy();
    }

    const cid = classId || $('#sel-class').val();
    const dt = date || $('#sel-date').val();

    table = $('#attendance-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("guru.student-attendances.data") }}',
            data: { class_group_id: cid, date: dt }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-sm font-black text-slate-400'},
            {data: 'student_name', name: 'student.nama_lengkap'},
            {data: 'time', name: 'time', className: 'text-center font-black text-slate-700'},
            {data: 'status_badge', name: 'status', className: 'text-center'}
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin fa-2x text-indigo-500"></i><br><span class="text-xs font-bold text-slate-500">Memuat Data...</span>',
            zeroRecords: '<div class="py-10"><i class="fas fa-folder-open text-slate-200 fa-3x mb-3"></i><p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada data kehadiran pada tanggal ini</p></div>',
            info: '<span class="text-xs font-bold text-slate-500">Menampilkan _START_ sampai _END_ dari _TOTAL_ data</span>',
            infoEmpty: '<span class="text-xs font-bold text-slate-500">Menampilkan 0 data</span>',
            search: '<span class="text-xs font-bold text-slate-500 mr-2">Cari:</span>',
            paginate: {
                previous: '<i class="fas fa-chevron-left text-xs"></i>',
                next: '<i class="fas fa-chevron-right text-xs"></i>'
            }
        },
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"text-sm font-bold text-slate-600"l><"mt-3 md:mt-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>'
    });
}

function submitMonthly(type = 'pdf') {
    const classId = $('#sel-class').val();
    if (!classId) {
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Silakan pilih kelas terlebih dahulu!', customClass:{popup:'rounded-[2rem]'} });
        return;
    }
    
    const form = $('#monthlyForm');
    if (type === 'excel') {
        form.attr('action', '{{ route("student-attendances.monthly_excel") }}');
    } else {
        form.attr('action', '{{ route("student-attendances.monthly") }}');
    }
    
    $('#monthly_class_id').val(classId);
    form.submit();
    $('#monthlyModal').modal('hide');
}
</script>
<style>
.btn-soft-rose { background: #fff1f2; color: #e11d48; }
.btn-soft-rose:hover { background: #ffe4e6; color: #be123c; }
</style>
@endpush
