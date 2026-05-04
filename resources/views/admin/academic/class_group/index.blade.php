@extends($layout)

@section('title', 'Data Rombongan Belajar')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-school mr-2 animate__animated animate__fadeInLeft"></i> 
                            Data Rombongan Belajar
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola data kelas, wali kelas, dan tingkat pendidikan secara sistematis.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-door-open fa-8x opacity-2 shadow-icon"></i>
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
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Rombel</h4>
                        <p class="text-muted text-sm mb-0">Kelola dan pantau seluruh kelas yang aktif</p>
                    </div>
                    <div class="d-flex align-items-center flex-wrap mt-2 mt-md-0">
                        @can('class-group.create')
                            <button onclick="addForm(`{{ route('class-groups.store') }}`)" class="btn btn-info shadow-sm font-weight-bold px-3 mr-2 btn-premium">
                                <i class="fas fa-plus-circle mr-1"></i> TAMBAH KELAS
                            </button>
                            <button onclick="syncClasses()" class="btn btn-success shadow-sm font-weight-bold px-3 mr-3 btn-premium" id="btnSync">
                                <i class="fas fa-sync-alt mr-1"></i> SINKRON GENAP
                            </button>
                        @endcan
                        <div class="input-group" style="width: 250px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-filter text-muted text-xs"></i></span>
                            </div>
                            <select id="filter_academic_year_id" class="form-control select2 custom-select-premium border-left-0" onchange="refreshTable()">
                                <option value="">Semua TA</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $ay->current_semester ? 'selected' : '' }}>{{ $ay->academic_year }} - {{ $ay->semester->semester_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="classTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Tahun Pelajaran</th>
                                <th>Nama Kelas</th>
                                <th>Sub Kelas</th>
                                <th>Tingkat</th>
                                <th>Wali Kelas</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Premium Styles inherited from main design */
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }
    
    #classTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #classTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #classTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); background: #f8fbff; }
    #classTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #classTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #17a2b8; }
    #classTable td:last-child { border-radius: 0 12px 12px 0; }
    
    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>

@include('admin.academic.class_group.form')
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#classTable').DataTable({
            processing: true, serverSide: true, autoWidth: false, responsive: true,
            ajax: {
                url: '{{ route('class-groups.data') }}',
                data: function(d) { d.academic_year_id = $('#filter_academic_year_id').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'ta_semester' },
                { 
                    data: 'class_group',
                    render: function(data) {
                        return '<span class="font-weight-bold text-dark h6 mb-0">' + data + '</span>';
                    }
                },
                { data: 'sub_class_group' },
                { 
                    data: 'class_level',
                    render: function(data) {
                        return '<span class="badge badge-info px-3 py-2 rounded-pill shadow-xs">Tingkat ' + data + '</span>';
                    }
                },
                { 
                    data: 'wali_kelas',
                    render: function(data) {
                        return data ? '<div class="d-flex align-items-center"><i class="fas fa-user-tie mr-2 text-muted"></i>' + data + '</div>' : '<span class="text-muted italic text-xs">Belum ditentukan</span>';
                    }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });

    function refreshTable() { table.ajax.reload(); }

    function syncClasses() {
        let targetAyId = $('#filter_academic_year_id').val();
        if (!targetAyId) {
            Swal.fire({ icon: 'warning', title: 'Filter TA', text: 'Pilih Tahun Pelajaran tujuan di filter TA terlebih dahulu.' });
            return;
        }

        Swal.fire({
            title: 'Sinkronisasi Kelas',
            text: 'Salin data kelas dari Ganjil ke Genap pada tahun terpilih?',
            icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Ya, Sinkronkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btnSync').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');
                $.post('{{ route("class-groups.sync") }}', { _token: '{{ csrf_token() }}', target_academic_year_id: targetAyId })
                    .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message }); table.ajax.reload(); })
                    .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); })
                    .always(() => { $('#btnSync').prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> SINKRON GENAP'); });
            }
        });
    }

    function addForm(url, title = 'Tambah Rombongan Belajar') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Rombongan Belajar') {
        Swal.fire({ title: "Memuat...", didOpen: () => { Swal.showLoading(); } });
        $.get(url).done(res => {
            Swal.close();
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            loopForm(res.data);
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data.' }); });
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true);
        Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading(); } });
        $.ajax({
            url: $(originalForm).attr('action'),
            type: 'POST',
            data: new FormData(originalForm),
            dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function(res) {
                Swal.close();
                $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close(); $(button).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Kelas?',
            text: 'Yakin ingin menghapus kelas ' + name + '? Data tidak dapat dikembalikan.',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => { Swal.showLoading(); } });
                $.ajax({
                    type: "DELETE", url: url, dataType: "json",
                    success: function(res) {
                        Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message, timer: 2000, showConfirmButton: false });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
                    }
                });
            }
        });
    }
</script>
@endpush
