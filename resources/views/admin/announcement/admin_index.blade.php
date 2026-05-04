@extends('layouts.app')

@section('title', 'Pusat Siaran Pengumuman')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Broadcasting</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-bullhorn mr-2 animate__animated animate__tada"></i> 
                            Pusat Siaran Informasi
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola dan siarkan pengumuman resmi Madrasah kepada Guru, Siswa, maupun seluruh civitas akademika secara real-time.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-satellite-dish fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-2 mb-md-0 font-weight-bold text-dark">
                    <i class="fas fa-list-alt mr-2 text-indigo"></i> Arsip Pengumuman
                </h4>
                <button onclick="addForm()" class="btn btn-indigo rounded-pill font-weight-bold shadow-indigo-light px-4 py-2">
                    <i class="fas fa-plus-circle mr-1"></i> BUAT PENGUMUMAN
                </button>
            </div>

            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="announcement-table" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3">NO</th>
                                <th>JUDUL SIARAN</th>
                                <th class="text-center">TARGET AUDIENS</th>
                                <th class="text-center">STATUS</th>
                                <th>TANGGAL TERBIT</th>
                                <th width="120px" class="text-center">AKSI TINDAKAN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.announcement.form')

<style>
    /* Premium Indigo Design System */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; }
    .bg-light-indigo { background: #e0e7ff; color: #4338ca; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-indigo { background: #4f46e5; color: #fff; border: none; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .text-indigo { color: #4f46e5; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }

    /* Dynamic Table Enhancements */
    #announcement-table { border-collapse: separate; border-spacing: 0 8px; }
    #announcement-table tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    #announcement-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #fefaff; }
    #announcement-table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #announcement-table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #4f46e5; }
    #announcement-table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }

    .badge-custom { padding: 8px 15px; font-weight: 700; border-radius: 8px; letter-spacing: 0.5px; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#announcement-table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari pengumuman...", search: "" },
            ajax: { url: '{{ route('announcements.data') }}' },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center'},
                {
                    data: 'title',
                    render: function(data) {
                        return '<div class="font-weight-bold text-dark text-lg mb-1">' + data + '</div>';
                    }
                },
                {
                    data: 'type', className: 'text-center',
                    render: function(data) {
                        let icon = 'fa-globe';
                        let color = 'primary';
                        if(data === 'Guru') { icon = 'fa-chalkboard-teacher'; color = 'info'; }
                        if(data === 'Siswa') { icon = 'fa-user-graduate'; color = 'success'; }
                        return `<span class="badge badge-light border border-${color} text-${color} badge-custom"><i class="fas ${icon} mr-1"></i> ${data}</span>`;
                    }
                },
                {
                    data: 'is_active', className: 'text-center',
                    render: function(data) {
                        if(data) return `<span class="badge bg-success text-white badge-custom shadow-sm"><i class="fas fa-check-circle mr-1"></i> Aktif</span>`;
                        return `<span class="badge bg-secondary text-white badge-custom"><i class="fas fa-power-off mr-1"></i> Nonaktif</span>`;
                    }
                },
                {
                    data: 'created_at',
                    render: function(data) {
                        return '<div class="text-muted font-weight-bold"><i class="far fa-calendar-alt mr-1"></i> ' + new Date(data).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) + '</div>';
                    }
                },
                {data: 'action', searchable: false, sortable: false, className: 'text-center'},
            ]
        });

        $(document).on('submit', '#modal-form form', function(e) {
            e.preventDefault();
            let id = $('#id').val();
            let url = id ? '{{ url('admin/announcements') }}/' + id : '{{ route('announcements.store') }}';
            let method = id ? 'PUT' : 'POST';
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIARKAN...');

            $.ajax({
                url: url, type: method, data: $(this).serialize(),
                success: function(response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Siaran Berhasil', text: response.message, timer: 2000, showConfirmButton: false });
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan sistem';
                    if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Gagal', html: message });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> SIMPAN SIARAN');
                }
            });
            return false;
        });
    });

    function addForm() {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title-text').text('Buat Siaran Baru');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', '{{ route('announcements.store') }}');
        $('#modal-form [name=_method]').val('post');
        $('#id').val('');
        // Trigger select2 if present in form.blade.php
        if($('.select2-premium').length) { $('.select2-premium').trigger('change'); }
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title-text').text('Edit Siaran Pengumuman');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');

        $.get(url).done(response => {
            const data = response.data;
            $('#id').val(data.id);
            $('#title').val(data.title);
            $('#type').val(data.type).trigger('change');
            $('#content').val(data.content);
            $('#is_active').val(data.is_active).trigger('change');
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat mengambil data siaran', 'error'));
    }

    function deleteData(url) {
        Swal.fire({
            title: 'Hapus Siaran?',
            text: 'Pengumuman ini akan ditarik dan tidak dapat dilihat lagi.',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#e3342f', confirmButtonText: 'Iya, Tarik Siaran!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'POST', data: { '_token': '{{ csrf_token() }}', '_method': 'delete' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 1500, showConfirmButton: false });
                }).fail(() => Swal.fire('Gagal', 'Tidak dapat menghapus data', 'error'));
            }
        });
    }
</script>
@endpush
