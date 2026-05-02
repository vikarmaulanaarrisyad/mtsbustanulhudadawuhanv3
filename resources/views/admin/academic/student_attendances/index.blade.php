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
    <!-- Standar Admin Table View -->
    <div class="row">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Presensi</h3>
                        <div>
                            <a href="{{ route('student-attendances.scanner') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-camera mr-1"></i> Buka Scanner
                            </a>
                            <button onclick="printCards()" class="btn btn-sm btn-info">
                                <i class="fas fa-address-card mr-1"></i> Cetak Kartu QR
                            </button>
                            <button onclick="pdfDownload()" class="btn btn-sm btn-danger">
                                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                            </button>
                        </div>
                    </div>
                </x-slot>

                <div class="row p-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" id="admin_filter_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select id="admin_filter_class" class="form-control select2">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classGroups as $cg)
                                    <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tahun Pelajaran</label>
                            <select id="admin_filter_academic_year" class="form-control select2">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button onclick="refreshTable()" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tampilkan</button>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Log Presensi Siswa</h3>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th>NIS</th>
                        <th>NAMA SISWA</th>
                        <th>KELAS</th>
                        <th>JAM</th>
                        <th>STATUS</th>
                    </x-slot>
                </x-table>
            </x-card>
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
            table = $('.table').DataTable({
                processing: true, serverSide: true, autoWidth: false,
                ajax: { 
                    url: '{{ route("student-attendances.data") }}',
                    data: function(d) {
                        d.class_group_id = $('#admin_filter_class').val();
                        d.academic_year_id = $('#admin_filter_academic_year').val();
                        d.date = $('#admin_filter_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', searchable: false, sortable: false },
                    { data: 'nis' },
                    { data: 'student_name' },
                    { data: 'class_name' },
                    { data: 'time' },
                    { data: 'status_badge' },
                ]
            });
        });
        function refreshTable() { table.ajax.reload(); }
        
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
    @endif
</script>
<style>
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-600 { color: #059669; }
    .bg-amber-100 { background-color: #fef3c7; }
    .text-amber-600 { color: #d97706; }
    .bg-rose-100 { background-color: #fee2e2; }
    .text-rose-600 { color: #dc2626; }
</style>
@endpush
