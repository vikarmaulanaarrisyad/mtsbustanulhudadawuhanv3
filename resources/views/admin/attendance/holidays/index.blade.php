@extends($layout)

@section('title', 'Hari Libur Nasional')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-rose overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-calendar-times mr-2 animate__animated animate__fadeInLeft"></i> 
                            Kalender Hari Libur
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur tanggal merah dan hari libur nasional untuk menyesuaikan sistem presensi otomatis Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-umbrella-beach fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="mr-4">
                            <h4 class="mb-1 font-weight-bold text-dark">Daftar Hari Libur</h4>
                            <p class="text-muted text-sm mb-0">Sistem presensi akan dinonaktifkan pada tanggal berikut</p>
                        </div>
                        <!-- FILTER YEAR -->
                        <div class="ml-md-4 mt-3 mt-md-0">
                            <select id="filter_year" class="form-control select2 shadow-sm border-0" style="min-width: 150px; height: 45px; border-radius: 12px;">
                                <option value="">Semua Tahun</option>
                                @for($i = date('Y') + 1; $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>Tahun {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap mt-3 mt-md-0" style="gap: 10px;">
                        <button onclick="syncHolidays()" class="btn btn-outline-rose shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-sync-alt mr-1"></i> SYNC LIBUR NASIONAL
                        </button>
                        <button onclick="addForm(`{{ route('holidays.store') }}`)" class="btn btn-rose shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH HARI LIBUR
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="holidayTable" style="width:100%">
                        <thead class="bg-light-rose text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3">NO</th>
                                <th width="200px">TANGGAL LIBUR</th>
                                <th>KETERANGAN / NAMA LIBUR</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PREMIUM MODAL FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-gradient-rose text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-calendar-plus mr-2"></i> Form Hari Libur
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Libur <span class="text-danger">*</span></label>
                            <div class="input-group-premium shadow-sm border-rose-light">
                                <i class="fas fa-calendar-day text-rose"></i>
                                <input type="date" name="holiday_date" class="form-control text-dark font-weight-bold" required>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Keterangan / Nama Libur <span class="text-danger">*</span></label>
                            <div class="input-group-premium shadow-sm">
                                <i class="fas fa-tag"></i>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Idul Fitri 1447 H" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-soft-rose border-0 mt-4 rounded-15 shadow-xs mb-0">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 bg-rose text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px; flex-shrink:0;">
                                <i class="fas fa-info text-xs"></i>
                            </div>
                            <span class="small font-weight-bold text-rose-dark">Presensi pada tanggal ini akan ditandai otomatis sebagai Libur Nasional.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-rose rounded-pill px-5 font-weight-bold shadow-rose-light text-white">
                        <i class="fas fa-save mr-2"></i> SIMPAN LIBUR
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-rose { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important; }
    .bg-light-rose { background: #fff1f2; color: #e11d48; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-rose { background: #e11d48; color: #fff; border: none; }
    .btn-rose:hover { background: #be123c; color: #fff; }
    .text-rose { color: #e11d48; }
    .text-rose-dark { color: #881337; }
    .bg-rose { background: #e11d48; }
    .alert-soft-rose { background: #ffe4e6; }
    .shadow-rose-light { box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4); }
    .border-rose-light:focus-within { border-color: #e11d48 !important; }

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

    /* Table Styling */
    #holidayTable { border-collapse: separate; border-spacing: 0 10px; }
    #holidayTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #holidayTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fffbfa; }
    #holidayTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #holidayTable td:first-child { border-radius: 10px 0 0 10px; font-weight: bold; color: #e11d48; }
    #holidayTable td:last-child { border-radius: 0 10px 10px 0; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #e11d48; box-shadow: 0 0 15px rgba(225, 29, 72, 0.1); }
    .input-group-premium:focus-within i { color: #e11d48; }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#holidayTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari libur...", search: "" },
            ajax: { 
                url: '{{ route("holidays.data") }}',
                data: function(d) {
                    d.year = $('#filter_year').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center font-weight-bold' },
                { 
                    data: 'holiday_date',
                    render: function(data) {
                        return '<span class="badge badge-light border border-danger px-3 py-2 text-danger font-weight-bold shadow-sm"><i class="fas fa-calendar-day mr-1"></i> ' + data + '</span>';
                    }
                },
                { 
                    data: 'name',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 rounded-circle d-flex align-items-center justify-content-center text-rose font-weight-bold" style="width:35px;height:35px;background:#ffe4e6;"><i class="fas fa-umbrella-beach"></i></div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#filter_year').on('change', function() {
            table.ajax.reload();
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').html('<i class="fas fa-calendar-plus mr-2"></i> Tambah Hari Libur');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').html('<i class="fas fa-edit mr-2"></i> Edit Hari Libur');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
        });
    }

    function syncHolidays() {
        Swal.fire({
            title: 'Sinkronisasi Libur?',
            text: 'Sistem akan mengambil data hari libur nasional dari API resmi pemerintah untuk tahun ' + new Date().getFullYear(),
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Ya, Sinkronkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Mensinkronkan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.post('{{ route("holidays.sync") }}', { _token: '{{ csrf_token() }}' })
                    .done(response => {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan saat sinkronisasi' });
                    });
            }
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                Swal.close();
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 2000, showConfirmButton: false });
            })
            .fail(xhr => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Libur?',
            text: 'Yakin ingin menghapus ' + name + '?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', confirmButtonText: 'Iya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 1500, showConfirmButton: false });
                });
            }
        });
    }
</script>
@endpush
