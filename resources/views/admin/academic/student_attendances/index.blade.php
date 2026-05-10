@extends($layout)

@section('title', 'Log Presensi Siswa')

@section('content')
@if(auth()->user()->hasRole('Guru'))
    <!-- Premium Mobile Header -->
    <div class="bg-emerald-600 pt-12 pb-24 px-6 rounded-b-[3.5rem] shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl"></div>
        
        <div class="flex items-center justify-between relative z-10 mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/30 active:scale-90 transition-all">
                    <i class="fas fa-chevron-left text-sm"></i>
                </a>
                <div>
                    <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest opacity-80">Monitoring</p>
                    <h1 class="text-white text-xl font-black leading-tight">Presensi Siswa</h1>
                </div>
            </div>
            <button onclick="$('#filterModal').modal('show')" class="w-11 h-11 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30 text-white shadow-sm active:scale-90 transition-all">
                <i class="fas fa-filter text-sm"></i>
            </button>
        </div>

        <!-- Filter Info Card -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-4 flex items-center justify-between relative z-10 shadow-lg">
            <div class="flex items-center space-x-3 text-white">
                <i class="fas fa-calendar-day text-emerald-300"></i>
                <span class="font-black text-xs uppercase tracking-widest" id="display_date">{{ date('d M Y') }}</span>
            </div>
            <div class="h-4 w-px bg-white/20"></div>
            <div class="flex items-center space-x-3 text-white">
                <i class="fas fa-users text-emerald-300"></i>
                <span class="font-black text-[10px] uppercase tracking-widest truncate max-w-[100px]" id="display_class">Semua Kelas</span>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-10 mb-32 relative z-20">
        <!-- Student List Container -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 p-6 border border-slate-50 min-h-[500px]">
            <div id="studentCardsList" class="space-y-4">
                <!-- Loading State -->
                <div class="text-center py-20">
                    <div class="animate-spin text-emerald-500 mb-4 inline-block"><i class="fas fa-circle-notch fa-2x"></i></div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Sinkronisasi Data...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Scanner -->
    <a href="{{ route('student-attendances.scanner') }}" class="fixed bottom-32 right-6 w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-2xl shadow-emerald-200 z-50 hover:bg-emerald-600 active:scale-90 transition-all border-4 border-white">
        <i class="fas fa-qrcode text-2xl"></i>
    </a>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-4" role="document">
            <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden">
                <div class="modal-header border-0 p-6 pb-2 flex justify-between items-center">
                    <h5 class="font-black text-slate-800">Filter Pencarian</h5>
                    <button type="button" class="w-9 h-9 bg-slate-50 rounded-full flex items-center justify-center text-slate-400" data-dismiss="modal">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
                <div class="modal-body p-6 pt-2">
                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Tanggal Absensi</label>
                            <input type="date" id="filter_date" class="w-full bg-slate-50 border-slate-100 rounded-2xl p-4 text-sm font-bold" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Pilih Kelas</label>
                            <select id="filter_class" class="w-full bg-slate-50 border-slate-100 rounded-2xl p-4 text-sm font-bold">
                                <option value="">Semua Kelas</option>
                                @foreach($classGroups as $cg)
                                    <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button onclick="applyFilters()" class="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-100 active:scale-[0.98] transition-all uppercase text-xs tracking-widest" data-dismiss="modal">
                            Tampilkan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- PREMIUM HEADER BANNER -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative" style="z-index: 1;">
                    <div class="row align-items-center">
                        <div class="col-md-8 text-white">
                            <h2 class="font-weight-bold mb-1">
                                <i class="fas fa-id-badge mr-2 animate__animated animate__fadeInLeft"></i> 
                                Log Presensi Siswa
                            </h2>
                            <p class="mb-0 opacity-8 text-lg font-weight-light">
                                Monitoring kehadiran siswa secara real-time, cetak kartu QR, dan kelola laporan kedisiplinan.
                            </p>
                        </div>
                        <div class="col-md-4 text-right d-none d-md-block">
                            <i class="fas fa-user-check fa-8x opacity-2 shadow-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-circle-1"></div>
                <div class="bg-circle-2"></div>
            </div>
        </div>
    </div>

    <div class="row animate__animated animate__fadeInUp">
        <div class="col-12">
            <!-- PREMIUM FILTER PANEL -->
            <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
                <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald mr-3" style="width:40px;height:40px;">
                            <i class="fas fa-filter"></i>
                        </div>
                        <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Parameter Presensi</h5>
                    </div>
                    <div class="d-flex" style="gap: 8px;">
                        <a href="{{ route('student-attendances.scanner') }}" class="btn btn-soft-emerald btn-sm font-weight-bold rounded-pill px-3">
                            <i class="fas fa-camera mr-1"></i> SCANNER
                        </a>
                        <button onclick="printCards()" class="btn btn-soft-info btn-sm font-weight-bold rounded-pill px-3">
                            <i class="fas fa-address-card mr-1"></i> KARTU QR
                        </button>
                    </div>
                </div>
                <div class="card-body p-4 bg-light-soft">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tanggal</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-calendar-day"></i>
                                <input type="date" id="admin_filter_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Pilih Kelas</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-users text-emerald"></i>
                                <select id="admin_filter_class" class="form-control select2-no-search border-0">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classGroups as $cg)
                                        <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran</label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-graduation-cap"></i>
                                <select id="admin_filter_academic_year" class="form-control select2-no-search border-0">
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex" style="gap: 10px;">
                                <button onclick="refreshTable()" class="btn btn-emerald flex-fill rounded-pill font-weight-bold shadow-emerald-light">
                                    <i class="fas fa-search mr-1"></i> TAMPILKAN
                                </button>
                                <button onclick="pdfDownload()" class="btn btn-danger rounded-pill px-3 font-weight-bold shadow-sm" title="Cetak PDF Harian">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button onclick="$('#monthlyModal').modal('show')" class="btn btn-soft-danger rounded-pill px-3 font-weight-bold shadow-sm" title="Cetak Rekap Bulanan">
                                    <i class="fas fa-calendar-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Rekap Bulanan (PREMIUM VERSION) -->
            <div class="modal fade" id="monthlyModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered px-4" role="document">
                    <div class="modal-content border-0 rounded-[2.5rem] shadow-2xl overflow-hidden bg-white/95 backdrop-blur-xl">
                        <!-- Header Banner inside Modal -->
                        <div class="bg-gradient-rose p-8 relative overflow-hidden">
                            <div class="bg-circle-1 opacity-2"></div>
                            <div class="bg-circle-2 opacity-1"></div>
                            <div class="relative z-10 flex justify-between items-center text-white">
                                <div>
                                    <h3 class="font-black mb-1 flex items-center">
                                        <i class="fas fa-file-invoice mr-3 animate__animated animate__fadeInLeft"></i> 
                                        Rekapitulasi Bulanan
                                    </h3>
                                    <p class="text-xs font-bold opacity-80 uppercase tracking-[2px]">Generator Laporan Matriks Kehadiran Siswa</p>
                                </div>
                                <button type="button" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-2xl flex items-center justify-center text-white transition-all shadow-sm" data-dismiss="modal">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="row no-gutters">
                                <!-- LEFT COLUMN: INFO & PREVIEW -->
                                <div class="col-md-5 bg-slate-50/50 p-8 border-right border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Pratinjau Format</p>
                                    
                                    <div class="space-y-4">
                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500">
                                                <i class="fas fa-th"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Format Matriks</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Tabel 31 Kolom Hari</p>
                                            </div>
                                        </div>

                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                                                <i class="fas fa-calculator"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Auto-Sum</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Total H, S, I, A, T per Siswa</p>
                                            </div>
                                        </div>

                                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500">
                                                <i class="fas fa-print"></i>
                                            </div>
                                            <div>
                                                <h6 class="text-xs font-black text-slate-700 mb-0">Siap Cetak</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase">Orientasi Landscape A4</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-8 border-top border-slate-100">
                                        <div class="flex items-center space-x-3 text-rose-600 mb-2">
                                            <i class="fas fa-shield-alt text-sm"></i>
                                            <span class="text-[10px] font-black uppercase tracking-wider">Validasi Data</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 leading-relaxed">
                                            Laporan akan di-generate berdasarkan data absensi yang sudah diverifikasi oleh sistem. Pastikan filter kelas di halaman utama sudah sesuai sebelum melanjutkan.
                                        </p>
                                    </div>
                                </div>

                                <!-- RIGHT COLUMN: FORM -->
                                <div class="col-md-7 p-8">
                                    <form id="monthlyForm" action="{{ route('student-attendances.monthly') }}" method="GET" target="_blank">
                                        <input type="hidden" name="class_group_id" id="monthly_class_id">
                                        
                                        <div class="space-y-6">
                                            <div>
                                                <h5 class="text-sm font-black text-slate-800 mb-1">Konfigurasi Periode</h5>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tentukan bulan dan tahun laporan</p>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Pilih Bulan</label>
                                                <div class="relative">
                                                    <select name="month" class="w-full appearance-none bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-rose-500 focus:bg-white outline-none transition-all">
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
                                                <label class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Pilih Tahun</label>
                                                <div class="relative">
                                                    <select name="year" class="w-full appearance-none bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-700 focus:border-rose-500 focus:bg-white outline-none transition-all">
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
                                                <button type="button" onclick="submitMonthly('pdf')" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-rose-200 active:scale-[0.98] transition-all flex items-center justify-center space-x-3 group">
                                                    <span class="uppercase text-xs tracking-widest">GENERATE PDF</span>
                                                    <i class="fas fa-file-pdf transition-transform group-hover:rotate-12"></i>
                                                </button>
                                                
                                                <button type="button" onclick="submitMonthly('excel')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-200 active:scale-[0.98] transition-all flex items-center justify-center space-x-3 group">
                                                    <span class="uppercase text-xs tracking-widest">EXPORT EXCEL</span>
                                                    <i class="fas fa-file-excel transition-transform group-hover:rotate-12"></i>
                                                </button>
                                                
                                                <p class="text-center mt-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Mendukung Format PDF & EXCEL XLSX</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN DATA TABLE -->
            <div class="card shadow-sm border-0 premium-card">
                <div class="card-header bg-white py-4 border-bottom">
                    <h4 class="mb-1 font-weight-bold text-dark">Daftar Kehadiran Siswa</h4>
                    <p class="text-muted text-sm mb-0">Detail rekaman waktu presensi harian siswa per kelas</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="adminAttendanceTable" style="width:100%">
                            <thead class="bg-light-emerald text-uppercase">
                                <tr>
                                    <th width="50px" class="text-center py-3">NO</th>
                                    <th width="120px">NIS</th>
                                    <th>NAMA SISWA</th>
                                    <th>KELAS</th>
                                    <th class="text-center">WAKTU</th>
                                    <th class="text-center">STATUS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    @if(auth()->user()->hasRole('Guru'))
        $(function() {
            loadStudentCards();
        });

        function applyFilters() {
            const dateStr = $('#filter_date').val();
            const classText = $('#filter_class option:selected').text();
            
            // Format date for display
            const dateObj = new Date(dateStr);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            $('#display_date').text(dateObj.toLocaleDateString('id-ID', options));
            $('#display_class').text(classText);
            
            loadStudentCards();
        }

        function loadStudentCards() {
            const list = $('#studentCardsList');
            const date = $('#filter_date').val();
            const classId = $('#filter_class').val();

            list.html('<div class="text-center py-20"><div class="animate-spin text-emerald-500 mb-4 inline-block"><i class="fas fa-circle-notch fa-2x"></i></div><p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Memuat Data...</p></div>');

            $.get('{{ route("student-attendances.data") }}', { date, class_group_id: classId })
                .done(response => {
                    if (response.data.length === 0) {
                        list.html('<div class="text-center py-20"><div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-slate-200"><i class="fas fa-folder-open text-3xl text-slate-200"></i></div><p class="text-slate-400 font-black text-[10px] uppercase tracking-widest">Tidak ada data ditemukan</p></div>');
                        return;
                    }

                    let html = '';
                    response.data.forEach(item => {
                        html += `
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 group active:scale-[0.98] transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-500 font-black shadow-sm border border-slate-200">
                                        ${item.student_name.charAt(0)}
                                    </div>
                                    <div>
                                        <h4 class="text-slate-800 font-black text-sm mb-1 leading-tight">${item.student_name}</h4>
                                        <div class="flex items-center">
                                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">${item.class_name}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-end text-slate-800 font-black text-xs mb-1">
                                        <i class="far fa-clock mr-1 text-[10px] text-emerald-500"></i>
                                        ${item.time}
                                    </div>
                                    <div class="scale-75 origin-right">
                                        ${item.status_badge}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    list.html(html);
                });
        }
    @else
        // Admin JS Logic
        let table;
        $(function() {
            $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });

            table = $('#adminAttendanceTable').DataTable({
                processing: true, serverSide: true, autoWidth: false,
                language: { searchPlaceholder: "Cari siswa...", search: "" },
                ajax: { 
                    url: '{{ route("student-attendances.data") }}',
                    data: function(d) {
                        d.class_group_id = $('#admin_filter_class').val();
                        d.academic_year_id = $('#admin_filter_academic_year').val();
                        d.date = $('#admin_filter_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                    { 
                        data: 'nis',
                        render: function(data) {
                            return '<code class="text-xs font-weight-bold">' + data + '</code>';
                        }
                    },
                    { 
                        data: 'student_name',
                        render: function(data) {
                            return '<span class="font-weight-bold text-dark">' + data + '</span>';
                        }
                    },
                    { data: 'class_name' },
                    { 
                        data: 'time',
                        className: 'text-center',
                        render: function(data) {
                            return '<span class="badge badge-light border px-2 py-1 shadow-sm"><i class="far fa-clock mr-1 text-emerald"></i> ' + data + '</span>';
                        }
                    },
                    { data: 'status_badge', className: 'text-center' },
                ]
            });
        });

        function refreshTable() { 
            let btn = $('.btn-emerald');
            let originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> LOADING...');
            table.ajax.reload(function() {
                btn.html(originalHtml);
            }); 
        }
        
        function pdfDownload() {
            let date = $('#admin_filter_date').val();
            let classId = $('#admin_filter_class').val();
            let url = '{{ route("student-attendances.pdf") }}?date=' + date + '&class_group_id=' + classId;
            window.open(url, '_blank');
        }

        function printCards() {
            let classId = $('#admin_filter_class').val();
            let url = '{{ route("student-attendances.cards") }}';
            if (classId) url += '?class_group_id=' + classId;
            window.open(url, '_blank');
        }

        function submitMonthly(type = 'pdf') {
            const classId = $('#admin_filter_class').val();
            if (!classId) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Silakan pilih kelas terlebih dahulu!' });
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
    @endif
</script>
<style>
    .btn-soft-danger { background: #fee2e2; color: #dc2626; border: none; }
    .btn-soft-danger:hover { background: #fecaca; color: #b91c1c; }
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-600 { color: #059669; }
    .bg-amber-100 { background-color: #fef3c7; }
    .text-amber-600 { color: #d97706; }
    .bg-rose-100 { background-color: #fee2e2; }
    .text-rose-600 { color: #dc2626; }

    /* Premium Design System */
    .bg-gradient-emerald { background: linear-gradient(135deg, #059669 0%, #047857 100%) !important; }
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
    .bg-gradient-rose { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important; }
    
    .bg-light-emerald { background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-emerald { background: #059669; color: #fff; border: none; }
    .btn-emerald:hover { background: #047857; color: #fff; }
    .text-emerald { color: #059669; }
    .bg-soft-emerald { background: #d1fae5; color: #059669; }
    .btn-soft-emerald { background: #d1fae5; color: #059669; border: none; }
    .btn-soft-emerald:hover { background: #a7f3d0; color: #047857; }
    .btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
    .btn-soft-info:hover { background: #bae6fd; color: #075985; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .opacity-1 { opacity: 0.1; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%;
    }
    .input-group-premium:focus-within { border-color: #059669; box-shadow: 0 0 10px rgba(5, 150, 105, 0.1); }
    .input-group-premium:focus-within i { color: #059669; }

    /* Select2 Tweaks inside input group */
    .select2-container--default .select2-selection--single { border: none !important; background: transparent !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0; font-weight: 600; color: #334155; line-height: normal; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }

    /* Table Enhancements */
    #adminAttendanceTable { border-collapse: separate; border-spacing: 0 8px; }
    #adminAttendanceTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #adminAttendanceTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fffa; }
    #adminAttendanceTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #adminAttendanceTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #059669; }
    #adminAttendanceTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endpush
