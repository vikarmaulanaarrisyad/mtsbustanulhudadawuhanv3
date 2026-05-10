@extends($layout)

@section('title', 'Agenda')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Akademik</li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar: Countdown & Legend -->
        <div class="col-lg-3 order-lg-2 mb-4">
            <!-- Countdown Card -->
            <div id="countdown-card" class="premium-card bg-gradient-indigo text-white p-4 mb-4 shadow-lg overflow-hidden relative min-h-[200px] flex flex-col justify-center">
                <div class="bg-circle-1"></div>
                <div class="bg-circle-2"></div>
                <div class="relative z-10 text-center">
                    <p class="text-[10px] font-black uppercase tracking-[2px] opacity-70 mb-2">Agenda Terdekat</p>
                    <h3 id="next-event-title" class="text-lg font-black leading-tight mb-3">Memuat agenda...</h3>
                    <div class="flex justify-center space-x-3" id="timer-container">
                        <div class="timer-box">
                            <span id="days" class="text-2xl font-black block leading-none">00</span>
                            <span class="text-[8px] uppercase font-bold opacity-60">Hari</span>
                        </div>
                        <div class="timer-box">
                            <span id="hours" class="text-2xl font-black block leading-none">00</span>
                            <span class="text-[8px] uppercase font-bold opacity-60">Jam</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend Card -->
            <div class="premium-card bg-white p-4 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Kategori Agenda</p>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></div>
                        <span class="text-xs font-bold text-slate-700">Akademik</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                        <span class="text-xs font-bold text-slate-700">Non-Akademik</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></div>
                        <span class="text-xs font-bold text-slate-700">Hari Libur</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Calendar & List -->
        <div class="col-lg-9 order-lg-1">
            <x-card class="premium-card overflow-hidden">
                <x-slot name="header">
                    <div class="flex justify-between items-center w-full">
                        <div class="flex items-center space-x-2">
                            <button onclick="addForm(`{{ route('agenda.store') }}`)" class="btn btn-sm btn-indigo rounded-xl px-3 font-black text-xs">
                                <i class="fas fa-plus-circle mr-1"></i> TAMBAH
                            </button>
                            <button onclick="confirmImport()" type="button" class="btn btn-sm btn-soft-indigo rounded-xl px-3 font-black text-xs">
                                <i class="fas fa-file-excel mr-1"></i> IMPORT
                            </button>
                        </div>
                        <div class="nav-tabs-premium flex bg-slate-100 p-1 rounded-xl">
                            <button onclick="toggleView('calendar')" id="btn-calendar" class="view-tab active px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all">
                                <i class="fas fa-calendar-alt mr-1"></i> Kalender
                            </button>
                            <button onclick="toggleView('list')" id="btn-list" class="view-tab px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all">
                                <i class="fas fa-list mr-1"></i> Daftar
                            </button>
                        </div>
                    </div>
                </x-slot>

                <!-- Calendar View -->
                <div id="calendar-container" class="p-4">
                    <div id="calendar"></div>
                </div>

                <!-- List View (Hidden by default) -->
                <div id="list-container" class="p-4 hidden">
                    <x-table id="agenda-table">
                        <x-slot name="thead">
                            <th width="5%">NO</th>
                            <th>JUDUL AGENDA</th>
                            <th>MULAI</th>
                            <th>SELESAI</th>
                            <th>KATEGORI</th>
                            <th>STATUS</th>
                            <th width="10%">AKSI</th>
                        </x-slot>
                    </x-table>
                </div>
            </x-card>
        </div>
    </div>

    @include('admin.academic.agenda.form')
    @include('admin.academic.agenda.import-excel')
@endsection

@push('css')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
    <style>
        /* Premium UI Elements */
        .premium-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .btn-indigo { background: #6366f1; color: white; border: none; }
        .btn-indigo:hover { background: #4f46e5; color: white; }
        .btn-soft-indigo { background: #e0e7ff; color: #4338ca; border: none; }
        .btn-soft-indigo:hover { background: #c7d2fe; }
        
        .nav-tabs-premium .view-tab.active { background: white; color: #4338ca; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .nav-tabs-premium .view-tab:not(.active) { color: #64748b; }
        
        .timer-box { background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); padding: 10px 15px; border-radius: 12px; min-width: 65px; border: 1px solid rgba(255,255,255,0.1); }
        
        .bg-circle-1 { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -50px; right: -50px; }
        .bg-circle-2 { position: absolute; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; bottom: -20px; left: 10%; }
        
        /* FullCalendar Custom Styling */
        #calendar { font-family: inherit; }
        .fc .fc-toolbar-title { font-size: 1.1rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
        .fc .fc-button-primary { background: #f1f5f9; border: none; color: #475569; font-weight: 800; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; padding: 8px 12px; border-radius: 8px !important; }
        .fc .fc-button-primary:hover { background: #e2e8f0; color: #1e293b; }
        .fc .fc-button-active { background: #4338ca !important; color: white !important; }
        .fc th { padding: 10px 0 !important; background: #f8fafc; font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; border: none !important; }
        .fc td { border: 1px solid #f1f5f9 !important; }
        .fc-event { border-radius: 6px !important; border: none !important; padding: 2px 5px !important; font-size: 10px !important; font-weight: 700 !important; }
        .fc-day-today { background: #f5f3ff !important; }
        
        .hidden { display: none !important; }
    </style>
@endpush

@include('includes.datatable')

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        let table;
        let calendar;
        let modal = '#modal-form';
        let importExcel = '#importExcelModal';
        let button = '#submitBtn';

        $(document).ready(function() {
            initDataTable();
            initCalendar();
            updateCountdown();
        });

        function toggleView(view) {
            $('.view-tab').removeClass('active');
            if (view === 'calendar') {
                $('#btn-calendar').addClass('active');
                $('#calendar-container').removeClass('hidden');
                $('#list-container').addClass('hidden');
                calendar.render();
            } else {
                $('#btn-list').addClass('active');
                $('#calendar-container').addClass('hidden');
                $('#list-container').removeClass('hidden');
                table.columns.adjust().draw();
            }
        }

        function initDataTable() {
            table = $('#agenda-table').DataTable({
                processing: false,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: { url: '{{ route('agenda.data') }}' },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'category' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false },
                ]
            });
        }

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                locale: 'id',
                events: '{{ route('agenda.events') }}',
                eventClick: function(info) {
                    const id = info.event.id;
                    if (id.startsWith('agenda_')) {
                        const actualId = id.replace('agenda_', '');
                        editForm(`{{ url('admin/academic/agenda') }}/${actualId}`);
                    }
                },
                height: 'auto'
            });
            calendar.render();
        }

        function updateCountdown() {
            $.get('{{ route('agenda.events') }}', function(events) {
                const now = new Date();
                const upcoming = events
                    .filter(e => new Date(e.start) > now)
                    .sort((a, b) => new Date(a.start) - new Date(b.start));

                if (upcoming.length > 0) {
                    const next = upcoming[0];
                    $('#next-event-title').text(next.title);
                    
                    const targetDate = new Date(next.start).getTime();
                    
                    const x = setInterval(function() {
                        const nowTime = new Date().getTime();
                        const distance = targetDate - nowTime;

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

                        $('#days').text(days.toString().padStart(2, '0'));
                        $('#hours').text(hours.toString().padStart(2, '0'));

                        if (distance < 0) {
                            clearInterval(x);
                            updateCountdown();
                        }
                    }, 1000);
                } else {
                    $('#next-event-title').text('Tidak ada agenda terdekat');
                    $('#timer-container').hide();
                }
            });
        }

        function addForm(url, title = 'Form Agenda') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Agenda') {
            Swal.fire({ title: "Memuat...", text: "Mohon tunggu sebentar...", allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.get(url).done(response => {
                Swal.close();
                $(modal).modal('show');
                $(`${modal} .modal-title`).text(title);
                $(`${modal} form`).attr('action', url);
                $(`${modal} [name=_method]`).val('put');
                resetForm(`${modal} form`);
                loopForm(response.data);
            }).fail(errors => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Oops! Gagal', text: 'Terjadi kesalahan saat memuat data.' });
            });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            Swal.fire({ title: 'Mohon Tunggu...', text: 'Sedang memproses data', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: 'POST',
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    Swal.close();
                    $(modal).modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 2000 }).then(() => {
                        $(button).prop('disabled', false);
                        table.ajax.reload();
                        calendar.refetchEvents();
                        updateCountdown();
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    $(button).prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
                }
            });
        }

        function deleteData(url, name) {
            Swal.fire({
                title: 'Hapus Agenda?',
                text: `Anda yakin ingin menghapus "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 2000, showConfirmButton: false });
                            table.ajax.reload();
                            calendar.refetchEvents();
                            updateCountdown();
                        }
                    });
                }
            });
        }

        function confirmImport() { $(importExcel).modal('show'); }
    </script>
@endpush
