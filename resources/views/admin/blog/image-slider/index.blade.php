@extends($layout)

@section('title', 'Galeri Gambar Slider')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Front-End Madrasah</li>
    <li class="breadcrumb-item active">Gambar Slide Utama</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-violet overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-images mr-2 animate__animated animate__zoomIn"></i> 
                            Galeri Media & Slider Utama
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola gambar berjalan (slider) yang akan menyambut pengunjung pertama kali di halaman utama website Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-camera-retro fa-8x opacity-2 shadow-icon"></i>
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
                    <i class="far fa-image mr-2 text-violet"></i> Daftar Aset Media
                </h4>
                <div class="d-flex" style="gap: 10px;">
                    <button id="deleteSelectedBtn" class="btn btn-danger rounded-pill font-weight-bold shadow-sm px-4" disabled>
                        <i class="fas fa-trash-alt mr-1"></i> HAPUS TERPILIH
                    </button>
                    <button onclick="addForm(`{{ route('image-sliders.store') }}`)" class="btn btn-violet rounded-pill font-weight-bold shadow-violet-light px-4">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> UNGGAH GAMBAR
                    </button>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table" style="width:100%">
                        <thead class="bg-light-violet text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox ml-2">
                                        <input type="checkbox" class="custom-control-input" id="selectAll">
                                        <label class="custom-control-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th width="50px" class="text-center">NO</th>
                                <th width="200px" class="text-center">PREVIEW GAMBAR</th>
                                <th>CAPTION / KETERANGAN</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.blog.image-slider.form')

<style>
    /* Premium Violet Design System */
    .bg-gradient-violet { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%) !important; }
    .bg-light-violet { background: #f5f3ff; color: #6d28d9; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-violet { background: #8b5cf6; color: #fff; border: none; }
    .btn-violet:hover { background: #7c3aed; color: #fff; }
    .text-violet { color: #7c3aed; }
    .shadow-violet-light { box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }

    /* Dynamic Table Enhancements */
    #table { border-collapse: separate; border-spacing: 0 8px; }
    #table tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    #table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #f5f3ff; }
    #table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; }
    #table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
    
    /* Thumbnail Styling */
    #table img { border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); object-fit: cover; border: 2px solid #e2e8f0; }
    #table td:nth-child(4) { line-height: 1.6; }
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
            language: { searchPlaceholder: "Cari media...", search: "" },
            ajax: { url: '{{ route('image-sliders.data') }}' },
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
                { data: 'image', orderable: false, searchable: false, className: 'text-center py-3' },
                { 
                    data: 'caption', orderable: false, searchable: false,
                    render: function(data) { return '<div class="text-dark">' + (data || '<em class="text-muted">Tanpa Caption</em>') + '</div>'; }
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

            if (selectedIds.length === 0) return Swal.fire('Oops!', 'Pilih gambar terlebih dahulu.', 'warning');

            Swal.fire({
                title: 'Hapus Gambar?',
                text: `Anda akan menghapus ${selectedIds.length} gambar slider. Aset fisik gambar juga akan dihapus dari sistem.`,
                icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Buang!', cancelButtonText: 'Batal',
                reverseButtons: true, confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus Media...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    
                    $.ajax({
                        url: '{{ route('image-sliders.deleteSelected') }}', type: 'POST',
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

    function addForm(url, title = 'Unggah Gambar Slide') {
        $(modal).modal('show');
        $(`${modal} .modal-title-text`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
        if($('.summernote').length) { $('.summernote').summernote('code', ''); }
    }

    function editForm(url, title = 'Edit Gambar Slide') {
        Swal.fire({ title: "Memuat Aset...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.get(url).done(response => {
            Swal.close();
            $(modal).modal('show');
            $(`${modal} .modal-title-text`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            
            // Loop form values (except file input, which cannot be populated)
            $(`${modal} form [name="caption"]`).val(response.data.caption);
            if($('.summernote').length) { $('.summernote').summernote('code', response.data.caption || ''); }
            
        }).fail(errors => {
            Swal.fire({ icon: 'error', title: 'Gagal', text: errors.responseJSON?.message || 'Tidak dapat memuat aset media.' });
        });
    }

    function submitForm(originalForm) {
        let btn = $(button);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENGUNGGAH...');

        $.ajax({
            url: $(originalForm).attr('action'), type: $(originalForm).attr('method') || 'POST',
            data: new FormData(originalForm), dataType: 'JSON', contentType: false, processData: false,
            success: function(response) {
                $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 2000 })
                .then(() => table.ajax.reload());
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem';
                if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg, showConfirmButton: false, timer: 3000 });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-cloud-upload-alt mr-2"></i> SIMPAN GAMBAR');
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Aset Media?',
            html: `Apakah Anda yakin ingin menghapus gambar slider ini?`,
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
