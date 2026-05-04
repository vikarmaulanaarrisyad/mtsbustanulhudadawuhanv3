@extends($layout)

@section('title', 'Jadwal Pelajaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-cyan overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-th-list mr-2 animate__animated animate__fadeInLeft"></i> 
                            Manajemen Jadwal Pembelajaran
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Susun dan pantau jadwal mata pelajaran untuk seluruh kelas secara visual dan terorganisir.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-calendar-alt fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- FILTER CARD -->
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-filter mr-2 text-cyan"></i> Filter & Mode Tampilan
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran (Aktif)</label>
                            @php $activeYear = $academicYears->firstWhere('current_semester', 1); @endphp
                            <div class="input-group-premium bg-light">
                                <i class="fas fa-calendar-check text-cyan"></i>
                                <select id="filter_academic_year" class="form-control" disabled style="cursor: not-allowed;">
                                    @if($activeYear)
                                        <option value="{{ $activeYear->id }}" selected>{{ $activeYear->academic_year }} (Semester {{ $activeYear->semester_id == 1 ? 'Ganjil' : 'Genap' }})</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Pilih Kelas <span class="text-danger">*</span></label>
                            <div class="input-group-premium border-cyan shadow-sm">
                                <i class="fas fa-school text-cyan"></i>
                                <select id="filter_class" class="form-control select2" onchange="loadGrid()">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classGroups as $cg)
                                        <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>&nbsp;</label>
                            <button type="button" onclick="loadGrid()" class="btn btn-cyan btn-block font-weight-bold btn-premium shadow-cyan">
                                <i class="fas fa-sync-alt mr-2"></i> REFRESH JADWAL
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- INSTRUCTION VIEW --}}
        <div id="instruction-view" class="animate__animated animate__fadeIn">
            <div class="card shadow-sm border-0 rounded-20 py-5">
                <div class="text-center py-5">
                    <div class="avatar-xl mx-auto bg-light-soft rounded-circle d-flex align-items-center justify-content-center mb-4" style="width:120px;height:120px;">
                        <i class="fas fa-school fa-4x text-cyan-light"></i>
                    </div>
                    <h4 class="font-weight-bold text-dark">Pilih Kelas Terlebih Dahulu</h4>
                    <p class="text-muted mx-auto" style="max-width: 400px;">Silakan pilih kelas pada filter di atas untuk menampilkan dan mengelola jadwal pelajaran secara visual.</p>
                </div>
            </div>
        </div>

        {{-- GRID VIEW ONLY --}}
        <div id="grid-view-container" style="display: none;" class="animate__animated animate__fadeIn">
            <div class="card shadow-sm border-0 premium-card">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0 text-dark">
                        <i class="fas fa-th mr-2 text-cyan"></i> Grid Jadwal Pelajaran
                    </h5>
                    <div>
                        <button onclick="importForm()" class="btn btn-sm btn-soft-success font-weight-bold rounded-pill px-3">
                            <i class="fas fa-file-excel mr-1"></i> IMPORT EXCEL
                        </button>
                    </div>
                </div>
                
                <div class="grid-container py-3">
                    @php 
                        $days = [1=>'SENIN', 2=>'SELASA', 3=>'RABU', 4=>'KAMIS', 5=>'JUMAT', 6=>'SABTU', 7=>'MINGGU']; 
                    @endphp
                    @foreach($days as $id => $name)
                        <div class="grid-header {{ $id == 7 ? 'text-danger' : '' }} font-weight-bold">{{ $name }}</div>
                    @endforeach

                    @foreach($studyPeriods as $sp)
                        @foreach($days as $dayId => $dayName)
                            <div id="cell-{{ $dayId }}-{{ $sp->id }}" class="schedule-cell {{ $sp->is_break ? 'bg-break' : '' }}">
                                @if(!$sp->is_break)
                                    <button onclick="addFromGrid({{ $dayId }}, {{ $sp->id }})" class="btn-add-grid">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <div class="schedule-card break-card">
                                        <div class="card-top"><span>{{ $sp->period_number }}</span><span>{{ substr($sp->start_time, 0, 5) }}</span></div>
                                        <div class="subject-name text-center mt-2 font-weight-bold">ISTIRAHAT</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PREMIUM MODAL FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-gradient-cyan text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">Atur Jadwal Pelajaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div id="modal_info_header" class="alert alert-soft-cyan rounded-15 border-0 shadow-sm mb-4">
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <span class="d-block text-xs font-weight-bold text-muted uppercase">Hari</span>
                                <strong id="info_day" class="text-cyan text-lg">-</strong>
                            </div>
                            <div class="col-6">
                                <span class="d-block text-xs font-weight-bold text-muted uppercase">Waktu</span>
                                <strong id="info_time" class="text-cyan text-lg">-</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran</label>
                                    <div class="input-group-premium bg-light border-0">
                                        <i class="fas fa-calendar"></i>
                                        <input type="text" value="{{ $activeYear ? $activeYear->academic_year : '-' }}" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Kelas</label>
                                    <div class="input-group-premium bg-light border-0">
                                        <i class="fas fa-school"></i>
                                        <select id="display_class_group" class="form-control" disabled>
                                            @foreach($classGroups as $cg)
                                                <option value="{{ $cg->id }}">{{ $cg->class_group }} - {{ $cg->sub_class_group }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Mata Pelajaran <span class="text-danger">*</span></label>
                            <div class="input-group-premium shadow-sm">
                                <i class="fas fa-bookmark text-cyan"></i>
                                <select name="subject_id" class="form-control select2" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Guru Pengajar <span class="text-danger">*</span></label>
                            <div class="input-group-premium shadow-sm">
                                <i class="fas fa-user-tie text-cyan"></i>
                                <select name="teacher_id" class="form-control select2" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="day" id="modal_day">
                    <input type="hidden" name="study_period_id" id="modal_study_period">
                    <input type="hidden" name="class_group_id" id="modal_class_group">
                    <input type="hidden" name="academic_year_id" value="{{ $activeYear ? $activeYear->id : '' }}">
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-cyan rounded-pill px-5 font-weight-bold shadow-cyan-light text-white">
                        <i class="fas fa-save mr-2"></i> SIMPAN JADWAL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-cyan { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important; }
    .text-cyan { color: #0891b2 !important; }
    .btn-cyan { background: #0891b2; color: #fff; }
    .btn-cyan:hover { background: #0e7490; color: #fff; }
    .shadow-cyan { box-shadow: 0 4px 15px rgba(8,145,178,0.3); }
    .shadow-cyan-light { box-shadow: 0 4px 15px rgba(8,145,178,0.4); }
    .alert-soft-cyan { background: #ecfeff; border: 1px solid #cffafe; }
    .text-cyan-light { color: #a5f3fc; }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .bg-light-soft { background: #f8fafc; }

    /* GRID STYLING */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        background: #fdfdfd;
        padding: 15px;
        overflow-x: auto;
    }
    .grid-header {
        padding: 12px 5px;
        text-align: center;
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        border-radius: 8px;
    }
    .grid-header.text-danger { color: #ef4444 !important; background: #fef2f2; }

    .schedule-cell {
        min-height: 110px;
        background: #fff;
        border-radius: 12px;
        position: relative;
        transition: all 0.3s;
        border: 2px dashed #e2e8f0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    .btn-add-grid {
        width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;
        color: #cbd5e1; cursor: pointer; border: none; background: transparent; transition: all 0.2s;
    }
    .btn-add-grid:hover { color: #0891b2; background: #ecfeff; }

    .schedule-card {
        padding: 0; height: 100%; width: 100%; display: flex; flex-direction: column; position: relative; border-radius: 10px;
    }
    .card-top {
        display: flex; justify-content: space-between; padding: 4px 8px; font-size: 0.65rem;
        background: rgba(0,0,0,0.08); color: rgba(0,0,0,0.6); font-weight: 800;
    }
    .card-body-content { padding: 10px; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
    .subject-name { font-weight: 800; font-size: 0.7rem; text-transform: uppercase; color: #1e293b; line-height: 1.2; margin-bottom: 4px; }
    .teacher-name { font-size: 0.6rem; color: #64748b; font-weight: 600; max-width: 90%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .bg-break { background: #f1f5f9 !important; border: 1px solid #e2e8f0 !important; }
    .bg-break .card-top { background: #e2e8f0; }

    /* Palette Pastel WOW */
    .color-pink { background-color: #fce7f3; border: 2px solid #fbcfe8 !important; }
    .color-blue { background-color: #e0f2fe; border: 2px solid #bae6fd !important; }
    .color-yellow { background-color: #fef9c3; border: 2px solid #fef08a !important; }
    .color-purple { background-color: #f3e8ff; border: 2px solid #e9d5ff !important; }
    .color-mint { background-color: #dcfce7; border: 2px solid #bbf7d0 !important; }
    .color-orange { background-color: #ffedd5; border: 2px solid #fed7aa !important; }

    .schedule-actions-grid {
        position: absolute; top: 0; right: 0; left: 0; bottom: 0;
        background: rgba(8, 145, 178, 0.9); display: none; gap: 8px;
        align-items: center; justify-content: center; z-index: 5;
    }
    .schedule-cell:hover .schedule-actions-grid { display: flex; }
    
    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #0891b2; box-shadow: 0 0 15px rgba(8,145,178,0.1); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        if ($('#filter_class').val()) loadGrid();
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
            Swal.fire({ title: 'Memuat Jadwal...', didOpen: () => Swal.showLoading(), backdrop: 'rgba(0,0,0,0.1)' });
        }

        $.get('{{ route("class-schedules.matrix") }}', { class_group_id: classId, academic_year_id: ayId })
            .done(data => {
                if (!silent) Swal.close();
                
                // Reset cells
                $('.schedule-cell').each(function() {
                    if (!$(this).hasClass('bg-break')) {
                        let ids = $(this).attr('id').split('-');
                        $(this).html(`<button onclick="addFromGrid(${ids[1]}, ${ids[2]})" class="btn-add-grid"><i class="fas fa-plus"></i></button>`);
                    }
                });

                const colors = ['color-pink', 'color-blue', 'color-yellow', 'color-purple', 'color-mint', 'color-orange'];

                $.each(data, function(day, periods) {
                    $.each(periods, function(periodId, schedule) {
                        let item = schedule[0];
                        let colorClass = colors[item.subject_id % colors.length];
                        
                        let html = `
                            <div class="schedule-card ${colorClass}">
                                <div class="card-top">
                                    <span>${item.study_period.period_number}</span>
                                    <span>${item.study_period.start_time.substring(0,5)}</span>
                                </div>
                                <div class="card-body-content">
                                    <div class="subject-name">${item.subject.name}</div>
                                    <div class="teacher-name">${item.teacher.name}</div>
                                </div>
                                <div class="schedule-actions-grid animate__animated animate__fadeIn">
                                    <button onclick="editForm('${'{{ url("class-schedules") }}'}/${item.id}')" class="btn btn-sm btn-white rounded-circle shadow-sm" style="width:35px;height:35px;"><i class="fas fa-edit text-info"></i></button>
                                    <button onclick="deleteData('${'{{ url("class-schedules") }}'}/${item.id}', '${item.subject.name}')" class="btn btn-sm btn-white rounded-circle shadow-sm" style="width:35px;height:35px;"><i class="fas fa-trash text-danger"></i></button>
                                </div>
                            </div>
                        `;
                        $('#cell-' + day + '-' + periodId).html(html);
                    });
                });

                if (successMsg) Swal.fire({ icon: 'success', title: 'Berhasil', text: successMsg, timer: 1500, showConfirmButton: false });
            })
            .fail(() => {
                if (!silent) Swal.close();
                Swal.fire('Error', 'Gagal memuat jadwal', 'error');
            });
    }

    function addFromGrid(day, periodId) {
        let classId = $('#filter_class').val();
        addForm(`{{ route('class-schedules.store') }}`);
        const dayNames = {1:'SENIN', 2:'SELASA', 3:'RABU', 4:'KAMIS', 5:'JUMAT', 6:'SABTU', 7:'MINGGU'};
        $('#modal_day').val(day);
        $('#modal_study_period').val(periodId);
        $('#modal_class_group').val(classId);
        $('#display_class_group').val(classId);
        $('#info_day').text(dayNames[day]);
        $('#info_time').text('Jam ke-' + periodId);
    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Jadwal');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        $('#modal-form form')[0].reset();
        $('#modal-form .select2').val('').trigger('change');
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        $.post($(form).attr('action'), $(form).serialize())
            .done(res => { $('#modal-form').modal('hide'); loadGrid(true, res.message); })
            .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); let data = res.data;
            $('#modal-form').modal('show');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(data);
            const dayNames = {1:'SENIN', 2:'SELASA', 3:'RABU', 4:'KAMIS', 5:'JUMAT', 6:'SABTU', 7:'MINGGU'};
            $('#info_day').text(dayNames[data.day]);
            $('#info_time').text('Jam ke-' + data.study_period_id);
            $('#display_class_group').val(data.class_group_id);
            $('#modal-form .select2').trigger('change');
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Jadwal?', text: 'Yakin ingin menghapus mapel ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(res => { loadGrid(true, res.message); });
            }
        });
    }

    function importForm() { $('#modal-import').modal('show'); }
</script>
@endpush
