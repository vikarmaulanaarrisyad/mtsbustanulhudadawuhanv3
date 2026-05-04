@extends($layout)

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Akademik')

@section('content')
<div class="row">
    <div class="col-md-12">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter & Mode Tampilan</h3>
            </x-slot>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tahun Pelajaran (Aktif)</label>
                        @php $activeYear = $academicYears->firstWhere('current_semester', 1); @endphp
                        <select id="filter_academic_year" class="form-control" disabled>
                            @if($activeYear)
                                <option value="{{ $activeYear->id }}" selected>{{ $activeYear->academic_year }} (Semester {{ $activeYear->semester_id == 1 ? 'Ganjil' : 'Genap' }})</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kelas <span class="text-danger">*Pilih untuk tampilkan jadwal</span></label>
                        <select id="filter_class" class="form-control select2" onchange="loadGrid()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" onclick="loadGrid()" class="btn btn-info btn-block"><i class="fas fa-sync mr-1"></i> Refresh Jadwal</button>
                    </div>
                </div>
            </div>
        </x-card>

        {{-- INSTRUCTION VIEW --}}
        <div id="instruction-view">
            <x-card>
                <div class="text-center py-5">
                    <i class="fas fa-school fa-4x text-muted mb-3"></i>
                    <h4>Pilih Kelas Terlebih Dahulu</h4>
                    <p class="text-muted">Silakan pilih kelas pada filter di atas untuk menampilkan dan mengelola jadwal pelajaran.</p>
                </div>
            </x-card>
        </div>

        {{-- GRID VIEW ONLY --}}
        <div id="grid-view-container" style="display: none;">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-th mr-1"></i> Grid Jadwal Pelajaran</h3>
                </x-slot>
                
                <div class="grid-container">
                    @php 
                        $days = [1=>'SENIN', 2=>'SELASA', 3=>'RABU', 4=>'KAMIS', 5=>'JUMAT', 6=>'SABTU', 7=>'MINGGU']; 
                    @endphp
                    @foreach($days as $id => $name)
                        <div class="grid-header {{ $id == 7 ? 'text-danger' : '' }}">{{ $name }}</div>
                    @endforeach

                    @foreach($studyPeriods as $sp)
                        @foreach($days as $dayId => $dayName)
                            <div id="cell-{{ $dayId }}-{{ $sp->id }}" class="schedule-cell {{ $sp->is_break ? 'bg-break' : '' }}">
                                @if(!$sp->is_break)
                                    <button onclick="addFromGrid({{ $dayId }}, {{ $sp->id }})" class="btn-add-grid">
                                        <i class="fas fa-plus fa-lg"></i>
                                    </button>
                                @else
                                    <div class="schedule-card">
                                        <div class="card-top"><span>{{ $sp->period_number }}</span><span>{{ substr($sp->start_time, 0, 5) }}</span></div>
                                        <div class="subject-name">ISTIRAHAT</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Jadwal Pelajaran</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Tahun Pelajaran (Aktif) <span class="text-danger">*</span></label>
                @php $activeYear = $academicYears->firstWhere('current_semester', 1); @endphp
                <select id="display_academic_year" class="form-control" disabled>
                    @if($activeYear)
                        <option value="{{ $activeYear->id }}" selected>{{ $activeYear->academic_year }} (Semester {{ $activeYear->semester_id == 1 ? 'Ganjil' : 'Genap' }})</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Kelas <span class="text-danger">*</span></label>
                <select id="display_class_group" class="form-control" disabled>
                    @foreach($classGroups as $cg)
                        <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Mata Pelajaran <span class="text-danger">*</span></label>
        <select name="subject_id" class="form-control select2" required>
            <option value="">-- Pilih Mapel --</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Guru Pengajar <span class="text-danger">*</span></label>
        <select name="teacher_id" class="form-control select2" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="modal_info_header" class="alert alert-info py-2 mb-3">
        <div class="d-flex justify-content-between mb-1">
            <span><i class="fas fa-calendar-day mr-1"></i> <strong id="info_day">Senin</strong></span>
            <span><i class="fas fa-clock mr-1"></i> <strong id="info_time">Jam ke-1</strong></span>
        </div>
        <div class="border-top border-info pt-1 mt-1 text-center small">
            @php $activeYear = $academicYears->firstWhere('current_semester', 1); @endphp
            <i class="fas fa-calendar-check mr-1"></i> Tahun Pelajaran Aktif: <strong>{{ $activeYear ? $activeYear->academic_year . ' (Semester ' . ($activeYear->semester_id == 1 ? 'Ganjil' : 'Genap') . ')' : 'Tidak ada' }}</strong>
        </div>
    </div>

    <input type="hidden" name="day" id="modal_day">
    <input type="hidden" name="study_period_id" id="modal_study_period">
    <input type="hidden" name="class_group_id" id="modal_class_group">
    <input type="hidden" name="academic_year_id" value="{{ $activeYear ? $activeYear->id : '' }}">

    <x-slot name="footer">
        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('class-schedules.import_excel') }}" method="POST" enctype="multipart/form-data" onsubmit="submitImport(event)">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Jadwal Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-info">
                        <small>Gunakan header Excel: <b>mata_pelajaran, nama_guru, kelas, tahun_pelajaran, hari, jam_mulai, jam_selesai</b></small>
                        <br>
                        <a href="{{ route('class-schedules.download_template') }}" class="badge badge-light mt-2"><i class="fas fa-download"></i> Download Template Excel</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnImport">Mulai Import</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('css')
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 100px repeat(7, 1fr);
        gap: 8px;
        background: #fff;
        padding: 10px;
        overflow-x: auto;
    }
    .grid-header {
        padding: 15px 5px;
        text-align: center;
        font-weight: 500;
        text-transform: uppercase;
        color: #999;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }
    .grid-header.text-danger { color: #dc3545 !important; }

    .grid-period-info {
        display: none; /* Hide the leftmost column as it's integrated into cards in the screenshot */
    }
    
    /* Adjust grid columns since we're hiding the first one */
    .grid-container { grid-template-columns: repeat(7, 1fr); }

    .schedule-cell {
        min-height: 100px;
        background: #fdfdfd;
        border-radius: 2px;
        position: relative;
        transition: all 0.3s;
        border: 1px solid #eee;
        display: flex;
        flex-direction: column;
        margin-bottom: 5px;
    }
    
    .btn-add-grid {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #eee;
        cursor: pointer;
        border: none;
        background: transparent;
    }
    .btn-add-grid:hover { color: #ccc; }

    .schedule-card {
        padding: 0;
        height: 100%;
        width: 100%;
        border-radius: 2px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .card-top {
        display: flex;
        justify-content: space-between;
        padding: 2px 8px;
        font-size: 0.7rem;
        background: rgba(0,0,0,0.15);
        color: #fff;
        font-weight: bold;
    }
    .card-top span:first-child { 
        background: rgba(0,0,0,0.2); 
        margin-left: -8px; 
        padding: 2px 8px;
    }

    .card-body-content {
        padding: 8px;
        flex-grow: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .subject-info { 
        display: flex; 
        flex-direction: column; 
        justify-content: flex-start; 
        max-width: 65%; 
    }
    .subject-name { 
        font-weight: 500; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        color: #444;
        line-height: 1.1;
        margin-bottom: 2px;
    }
    .teacher-name {
        font-size: 0.65rem;
        color: #666;
        font-style: italic;
    }
    
    .teacher-avatar {
        width: 50px;
        height: 60px;
        border-radius: 2px;
        object-fit: cover;
        background: #eee;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .bg-break { background: #eeeeee !important; border: 1px solid #ddd !important; }
    .bg-break .card-top { background: #cccccc; color: #666; }
    .bg-break .subject-name { 
        font-size: 0.75rem; 
        font-weight: bold; 
        color: #777; 
        width: 100%; 
        max-width: 100%;
        text-align: left;
        margin-top: 5px;
    }

    /* Palette exactly from screenshot */
    .color-pink { background-color: #fbc2cc; }
    .color-blue { background-color: #b9e3f1; }
    .color-yellow { background-color: #f9f2b8; }
    .color-purple { background-color: #e5bff1; }
    .color-mint { background-color: #bbf3d3; }
    .color-orange { background-color: #fbc6b0; }
    .color-tan { background-color: #d8c3b0; }

    .schedule-actions-grid {
        position: absolute;
        bottom: 2px;
        left: 2px;
        display: none;
        gap: 2px;
    }
    .schedule-card:hover .schedule-actions-grid { display: flex; }
    
    .bg-warning-light { background-color: #fff9e6 !important; }
    .bg-disabled { background-color: #f4f6f9 !important; pointer-events: none; }
    .bg-warning { background-color: #ffc107 !important; color: #333 !important; }
</style>
@endpush

@push('scripts')
<script>
    $(function() {
        // Automatically load grid if a class is already selected (e.g. from session)
        if ($('#filter_class').val()) {
            loadGrid();
        }

        // Prevent default form submission and use AJAX to avoid page reloads
        $('#modal-form form').on('submit', function(e) {
            e.preventDefault();
            submitForm(this);
        });
    });

    function loadGrid(silent = false, successMsg = null) {
        let classId = $('#filter_class').val();
        let ayId = $('#filter_academic_year').val();

        if (!classId) {
            $('#grid-view-container').hide();
            $('#instruction-view').show();
            return;
        }

        $('#instruction-view').hide();
        $('#grid-view-container').show();

        if (!silent) {
            Swal.fire({ 
                title: 'Memuat Jadwal...', 
                didOpen: () => Swal.showLoading(), 
                allowOutsideClick: false,
                backdrop: 'rgba(0,0,0,0.1)'
            });
        }

        $.get('{{ route("class-schedules.matrix") }}', { class_group_id: classId, academic_year_id: ayId })
            .done(data => {
                if (!silent) Swal.close();
                
                // Reset cells
                $('.schedule-cell').each(function() {
                    if (!$(this).hasClass('bg-break')) {
                        let ids = $(this).attr('id').split('-');
                        $(this).html(`
                            <button onclick="addFromGrid(${ids[1]}, ${ids[2]})" class="btn-add-grid">
                                <i class="fas fa-plus fa-lg"></i>
                            </button>
                        `);
                    }
                });

                // Palette exactly from screenshot
                const colors = ['color-pink', 'color-blue', 'color-yellow', 'color-purple', 'color-mint', 'color-orange', 'color-tan'];

                // Fill with data
                $.each(data, function(day, periods) {
                    $.each(periods, function(periodId, schedule) {
                        let item = schedule[0];
                        let colorClass = colors[item.subject_id % colors.length];
                        let timeRange = $('#modal_study_period option[value="'+periodId+'"]').data('time') || '';
                        let startTime = timeRange.split(' - ')[0] || '';
                        let endTime = timeRange.split(' - ')[1] || '';
                        
                        let photo = item.teacher.profile_photo_path 
                            ? '{{ Storage::url("") }}' + item.teacher.profile_photo_path 
                            : '{{ asset("AdminLTE/dist/img/user1-128x128.jpg") }}';

                        let html = `
                            <div class="schedule-card ${colorClass}">
                                <div class="card-top">
                                    <span>${item.study_period.period_number}</span>
                                    <span>${startTime.substring(0, 5)} - ${endTime.substring(0, 5)}</span>
                                </div>
                                <div class="card-body-content">
                                    <div class="subject-info">
                                        <div class="subject-name">${item.subject.name}</div>
                                        <div class="teacher-name">${item.teacher.name}</div>
                                    </div>
                                    <img src="${photo}" class="teacher-avatar" alt="Photo">
                                </div>
                                <div class="schedule-actions-grid">
                                    <button onclick="editForm('${'{{ url("class-schedules") }}'}/${item.id}')" class="btn btn-xs btn-info shadow-sm p-0 px-1"><i class="fas fa-edit"></i></button>
                                    <button onclick="deleteData('${'{{ url("class-schedules") }}'}/${item.id}', '${item.subject.name}')" class="btn btn-xs btn-danger shadow-sm p-0 px-1"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                        $('#cell-' + day + '-' + periodId).html(html);
                    });
                });

                if (successMsg) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: successMsg, timer: 1500, showConfirmButton: false });
                }
            })
            .fail(() => {
                if (!silent) Swal.close();
                Swal.fire('Error', 'Gagal memuat jadwal', 'error');
            });
    }

    function addFromGrid(day, periodId) {
        let classId = $('#filter_class').val();
        
        addForm(`{{ route('class-schedules.store') }}`);
        
        // Map day number to name
        const dayNames = {1:'SENIN', 2:'SELASA', 3:'RABU', 4:'KAMIS', 5:'JUMAT', 6:'SABTU', 7:'MINGGU'};
        
        // Get period info
        let periodText = 'Jam ke-' + periodId;
        
        // Pre-fill hidden
        $('#modal_day').val(day);
        $('#modal_study_period').val(periodId);
        $('#modal_class_group').val(classId);

        // Update read-only display
        $('#display_class_group').val(classId);

        // Update info labels
        $('#info_day').text(dayNames[day]);
        $('#info_time').text(periodText);
    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Jadwal Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        
        // Manually reset only the inputs inside the modal so we don't accidentally clear the global filter
        $('#modal-form form')[0].reset();
        $('#modal-form select[name="subject_id"]').val('').trigger('change');
        $('#modal-form select[name="teacher_id"]').val('').trigger('change');
    }

    function importForm() {
        $('#modal-import').modal('show');
    }

    function submitImport(e) {
        e.preventDefault();
        let form = e.target;
        let formData = new FormData(form);
        $('#btnImport').prop('disabled', true).text('Sedang Import...');
        
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#modal-import').modal('hide');
                loadGrid(true, response.message);
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            },
            complete: function() {
                $('#btnImport').prop('disabled', false).text('Mulai Import');
            }
        });
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            setTimeout(() => {
                Swal.close();
                let data = response.data;
                $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Jadwal Pelajaran');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            
            loopForm(data);

            const dayNames = {1:'SENIN', 2:'SELASA', 3:'RABU', 4:'KAMIS', 5:'JUMAT', 6:'SABTU', 7:'MINGGU'};
            $('#info_day').text(dayNames[data.day]);
            $('#info_time').text('Jam ke-' + data.study_period_id);
            $('#display_class_group').val(data.class_group_id);
            
            $('#modal-form .select2').trigger('change');
            }, 300); // Wait for Swal animation to finish
        }).fail(() => {
            Swal.close();
            Swal.fire('Error', 'Gagal memuat data', 'error');
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                loadGrid(true, response.message);
            })
            .fail(xhr => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: 'Apakah Anda yakin ingin menghapus jadwal ' + name + '?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    loadGrid(true, response.message);
                });
            }
        });
    }
</script>
@endpush
