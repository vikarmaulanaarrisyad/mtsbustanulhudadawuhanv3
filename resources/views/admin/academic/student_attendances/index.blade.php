@extends($layout)

@section('title', 'Presensi Siswa')

@section('content')
@if(auth()->user()->hasRole('Guru'))
    <!-- Android Style Student Attendance -->
    <div class="bg-emerald-600 pt-10 pb-20 px-6 rounded-b-[3rem] shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="flex items-center space-x-4 mb-6 text-white relative">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold">Log Presensi Siswa</h1>
        </div>

        <!-- Date & Class Filter (Mobile Style) -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-calendar-alt text-emerald-200"></i>
                <span class="text-white font-bold text-sm">{{ date('d M Y') }}</span>
            </div>
            <button onclick="$('#filterModal').modal('show')" class="bg-white text-emerald-600 px-4 py-1.5 rounded-xl text-xs font-bold shadow-lg">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>
    </div>

    <div class="px-6 -mt-10 mb-20">
        <!-- Student List Cards -->
        <div id="studentCardsList" class="space-y-4">
            <!-- Data akan dimuat via AJAX -->
            <div class="text-center py-10 bg-white rounded-[2rem] shadow-sm border">
                <div class="animate-spin text-emerald-500 mb-3"><i class="fas fa-spinner fa-2x"></i></div>
                <p class="text-slate-400 text-sm font-medium">Memuat data presensi...</p>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Scanner -->
    <a href="{{ route('student-attendances.scanner') }}" class="fixed bottom-24 right-6 w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-2xl shadow-emerald-200 z-50 animate-bounce">
        <i class="fas fa-camera text-2xl"></i>
    </a>

    <!-- Filter Modal (Mobile) -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 rounded-[2.5rem]">
                <div class="modal-body p-6">
                    <h5 class="font-bold text-slate-800 mb-4">Filter Data</h5>
                    <div class="space-y-4">
                        <div>
                            <label class="text-slate-400 text-[10px] font-bold uppercase tracking-widest ml-1">Pilih Tanggal</label>
                            <input type="date" id="filter_date" class="w-full bg-slate-50 border-slate-100 rounded-2xl p-3 text-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="text-slate-400 text-[10px] font-bold uppercase tracking-widest ml-1">Pilih Kelas</label>
                            <select id="filter_class" class="w-full bg-slate-50 border-slate-100 rounded-2xl p-3 text-sm">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classGroups as $cg)
                                    <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button onclick="loadStudentCards()" class="w-full bg-emerald-500 text-white font-bold py-3 rounded-2xl" data-dismiss="modal">Terapkan Filter</button>
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

                <div class="row">
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

        function loadStudentCards() {
            const list = $('#studentCardsList');
            const date = $('#filter_date').val();
            const classId = $('#filter_class').val();

            list.html('<div class="text-center py-10"><div class="animate-spin text-emerald-500 mb-3"><i class="fas fa-spinner fa-2x"></i></div><p class="text-slate-400 text-sm">Memuat data...</p></div>');

            $.get('{{ route("student-attendances.data") }}', { date, class_group_id: classId })
                .done(response => {
                    if (response.data.length === 0) {
                        list.html('<div class="text-center py-12 bg-white rounded-[2rem] border-2 border-dashed border-slate-100"><i class="fas fa-folder-open fa-3x text-slate-100 mb-4"></i><p class="text-slate-400 font-bold">Tidak ada data presensi.</p></div>');
                        return;
                    }

                    let html = '';
                    response.data.forEach(item => {
                        html += `
                            <div class="bg-white rounded-[2rem] p-4 shadow-sm border border-slate-100 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-emerald-500 font-bold">
                                        ${item.student_name.charAt(0)}
                                    </div>
                                    <div>
                                        <h4 class="text-slate-800 font-bold text-sm mb-0">${item.student_name}</h4>
                                        <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">${item.class_name}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-slate-800 font-black text-xs mb-1">${item.time}</span>
                                    ${item.status_badge}
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
@endpush
