@extends($layout)

@section('title', 'Studio Album Foto')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Media & Galeri</li>
    <li class="breadcrumb-item active">Manajemen Album</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-studio overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <span class="badge badge-light text-dark mb-2 font-weight-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.8rem;">
                            <i class="fas fa-camera mr-1"></i> STUDIO MEDIA CENTER
                        </span>
                        <h2 class="font-weight-bold mb-1 mt-2">
                            <i class="fas fa-images mr-2 animate__animated animate__pulse animate__infinite"></i> 
                            Koleksi Album Foto
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pusat pengelolaan dokumentasi visual dan kegiatan madrasah. Buat album baru untuk mengelompokkan galeri foto.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-photo-video fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-2 mb-md-0 font-weight-bold text-dark">
                    <i class="fas fa-book-open mr-2 text-studio"></i> Daftar Album Utama
                </h4>
                <div class="d-flex" style="gap: 10px;">
                    <button id="deleteSelectedBtn" class="btn btn-danger rounded-pill font-weight-bold shadow-sm px-4" disabled>
                        <i class="fas fa-trash-alt mr-1"></i> HAPUS TERPILIH
                    </button>
                    <button onclick="addForm(`{{ route('albums.store') }}`)" class="btn btn-studio rounded-pill font-weight-bold shadow-studio-light px-4">
                        <i class="fas fa-folder-plus mr-1"></i> BUAT ALBUM BARU
                    </button>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table" style="width:100%">
                        <thead class="bg-light-studio text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox ml-2">
                                        <input type="checkbox" class="custom-control-input" id="selectAll">
                                        <label class="custom-control-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th width="50px" class="text-center">NO</th>
                                <th width="180px" class="text-center">SAMPUL ALBUM</th>
                                <th width="250px">JUDUL ALBUM</th>
                                <th>KETERANGAN / DESKRIPSI</th>
                                <th width="120px" class="text-center">AKSI TINDAKAN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.media.album.form')

<style>
    /* Premium Studio/Dark Design System */
    .bg-gradient-studio { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; }
    .bg-light-studio { background: #f1f5f9; color: #334155; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-studio { background: #1e293b; color: #fff; border: none; }
    .btn-studio:hover { background: #0f172a; color: #fff; }
    .text-studio { color: #334155; }
    .shadow-studio-light { box-shadow: 0 4px 15px rgba(30, 41, 59, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.5)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.03); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }

    /* Dynamic Table Enhancements */
    #table { border-collapse: separate; border-spacing: 0 8px; }
    #table tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    #table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #f1f5f9; }
    #table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; }
    #table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
    
    /* Thumbnail Styling */
    #table img { border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); object-fit: cover; border: 3px solid #fff; }
</style>
@endsection

@include('includes.datatable')
@include('includes.summernote')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#table').DataTable({
            processing: false, serverSide: true, autoWidth: false, responsive: true,
            language: { searchPlaceholder: "Cari album...", search: "" },
            ajax: { url: '{{ route('albums.data') }}' },
            columns: [
                {
                    data: 'selectAll', name: 'selectAll', orderable: false, searchable: false, className: 'text-center',
                    render: function(data, type, row) {
                        return `<div class="custom-control custom-checkbox ml-2">
                                    <input type="checkbox" class="custom-control-input row-checkbox" id="chk_${row.id}" data-id="${row.id}">
                                    <label class="custom-control-label" for="chk_${row.id}"></label>
                                </div>`;
                    }
                },
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold text-muted' },
                { data: 'album_cover', orderable: false, searchable: false, className: 'text-center py-3' },
                { 
                    data: 'album_title', orderable: false, searchable: false,
                    render: function(data) { return '<div class="font-weight-bold text-dark text-md">' + data + '</div>'; }
                },
                { 
                    data: 'album_description', orderable: false, searchable: false,
                    render: function(data) { return '<div class="text-muted">' + (data || '<em class="opacity-50">Tanpa Deskripsi</em>') + '</div>'; }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            drawCallback: function() {
                $('#selectAll').prop('checked', false);
                $('#deleteSelectedBtn').prop('disabled', true);
            }
        });

        $('#selectAll').on('click', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            $('#deleteSelectedBtn').prop('disabled', $('.row-checkbox:checked').length === 0);
        });

        $(document).on('click', '.row-checkbox', function() {
            $('#selectAll').prop('checked', $('.row-checkbox:checked').length === $('.row-checkbox').length);
        });

        $(document).on('change', '.row-checkbox', function() {
            $('#deleteSelectedBtn').prop('disabled', $('.row-checkbox:checked').length === 0);
        });

        $('#deleteSelectedBtn').on('click', function() {
            const selectedIds = $('.row-checkbox:checked').map(function() { return $(this).data('id'); }).get();

            if (selectedIds.length === 0) return Swal.fire('Oops!', 'Pilih album terlebih dahulu.', 'warning');

            Swal.fire({
                title: 'Hapus Album?',
                text: `Anda akan menghapus ${selectedIds.length} album. PERINGATAN: Menghapus album berpotensi menghapus seluruh foto di dalamnya. Lanjutkan?`,
                icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Musnahkan!', cancelButtonText: 'Batal',
                reverseButtons: true, confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus Direktori...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    
                    $.ajax({
                        url: '{{ route('albums.deleteSelected') }}', type: 'POST',
                        data: { _token: '{{ csrf_token() }}', ids: selectedIds },
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, showConfirmButton: false, timer: 2000 })
                            .then(() => table.ajax.reload());
                        },
                        error: function(xhr) { Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error'); }
                    });
                }
            });
        });
    });

    function addForm(url, title = 'Buat Album Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title-text`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
        if($('.summernote').length) { $('.summernote').summernote('code', ''); }
        // Reset Dropzone UI
        $('#file-name-display').hide();
        $('.file-drop-area').css({'borderColor': '#cbd5e1', 'backgroundColor': '#fff'});
    }

    function editForm(url, title = 'Edit Data Album') {
        Swal.fire({ title: "Memuat Data...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.get(url).done(response => {
            Swal.close();
            $(modal).modal('show');
            $(`${modal} .modal-title-text`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            
            // Loop form values 
            $(`${modal} form [name="album_title"]`).val(response.data.album_title);
            if($('.summernote').length) { $('.summernote').summernote('code', response.data.album_description || ''); }
            
            // Reset Dropzone UI
            $('#file-name-display').html('<i class="fas fa-info-circle mr-1"></i> Biarkan kosong jika tidak ingin mengubah sampul').show();
            $('.file-drop-area').css({'borderColor': '#cbd5e1', 'backgroundColor': '#f8fafc'});

        }).fail(errors => {
            Swal.fire({ icon: 'error', title: 'Gagal', text: errors.responseJSON?.message || 'Tidak dapat memuat metadata album.' });
        });
    }

    function submitForm(originalForm) {
        let btn = $(button);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...');

        $.ajax({
            url: $(originalForm).attr('action'), type: $(originalForm).attr('method') || 'POST',
            data: new FormData(originalForm), dataType: 'JSON', contentType: false, processData: false,
            success: function(response) {
                $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Tersimpan', text: response.message, showConfirmButton: false, timer: 2000 })
                .then(() => table.ajax.reload());
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem';
                if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg, showConfirmButton: false, timer: 3000 });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN ALBUM');
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Album?',
            html: `Apakah Anda yakin ingin menghapus album <strong>${name}</strong> beserta seluruh isinya?`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#e3342f', confirmButtonText: 'Iya, Hapus!',
            cancelButtonText: 'Batal', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                $.ajax({
                    type: "DELETE", url: url, dataType: "json", data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, showConfirmButton: false, timer: 2000 })
                        .then(() => table.ajax.reload());
                    },
                    error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' }); }
                });
            }
        });
    }
</script>
@endpush
