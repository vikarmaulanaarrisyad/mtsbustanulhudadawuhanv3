@extends($layout)

@section('title', 'Studio Konten Artikel')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Jurnalistik</li>
    <li class="breadcrumb-item active">Artikel Berita</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-rose overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-pen-nib mr-2 animate__animated animate__swing animate__infinite" style="animation-duration: 3s;"></i> 
                            Studio Jurnalistik & Berita
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tulis, edit, dan kelola publikasi berita terbaru untuk menyajikan informasi terkini kepada publik.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-newspaper fa-8x opacity-2 shadow-icon"></i>
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
                    <i class="fas fa-list-alt mr-2 text-rose"></i> Arsip Publikasi
                </h4>
                <div class="d-flex" style="gap: 10px;">
                    <button id="deleteSelectedBtn" class="btn btn-danger rounded-pill font-weight-bold shadow-sm px-4" disabled>
                        <i class="fas fa-trash-alt mr-1"></i> HAPUS TERPILIH
                    </button>
                    <a href="{{ route('posts.create') }}" class="btn btn-rose rounded-pill font-weight-bold shadow-rose-light px-4">
                        <i class="fas fa-feather-alt mr-1"></i> TULIS ARTIKEL BARU
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table" style="width:100%">
                        <thead class="bg-light-rose text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox ml-2">
                                        <input type="checkbox" class="custom-control-input" id="selectAll">
                                        <label class="custom-control-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th width="50px" class="text-center">NO</th>
                                <th width="120px" class="text-center">SAMPUL</th>
                                <th>JUDUL PUBLIKASI</th>
                                <th width="150px">TANGGAL</th>
                                <th width="150px">PENULIS</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Rose Design System */
    .bg-gradient-rose { background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important; }
    .bg-light-rose { background: #ffe4e6; color: #e11d48; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-rose { background: #e11d48; color: #fff; border: none; }
    .btn-rose:hover { background: #be123c; color: #fff; }
    .text-rose { color: #e11d48; }
    .shadow-rose-light { box-shadow: 0 4px 15px rgba(225, 29, 72, 0.4); }

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
    #table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #fff1f2; }
    #table td { border: none; padding: 1rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; }
    #table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }

    /* Thumbnail Styling override */
    #table img { border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); object-fit: cover; }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let button = '#submitBtn';

    $(function() {
        table = $('#table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            pageLength: 30,
            language: { searchPlaceholder: "Cari artikel...", search: "" },
            lengthMenu: [[10, 30, 50, 100], [10, 30, 50, 100]],
            ajax: { url: '{{ route('posts.data') }}' },
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
                { data: 'post_image', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'post_title',
                    render: function(data) { return '<div class="font-weight-bold text-dark text-md">' + data + '</div>'; }
                },
                { 
                    data: 'created_at',
                    render: function(data) { return '<span class="text-muted font-weight-bold"><i class="far fa-calendar-alt mr-1"></i> ' + data + '</span>'; }
                },
                { 
                    data: 'user', orderable: false, searchable: false,
                    render: function(data) { return '<span class="badge badge-light border text-dark font-weight-bold px-2 py-1 shadow-sm"><i class="fas fa-user-edit text-rose mr-1"></i> ' + data + '</span>'; }
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

            if (selectedIds.length === 0) return Swal.fire('Oops!', 'Tidak ada artikel yang dipilih.', 'warning');

            Swal.fire({
                title: 'Hapus Artikel Massal?',
                text: `Anda akan menghapus ${selectedIds.length} artikel yang telah diterbitkan. Tindakan ini permanen.`,
                icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Buang!', cancelButtonText: 'Batal',
                reverseButtons: true, confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus Publikasi...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    
                    $.ajax({
                        url: '{{ route('posts.deleteSelected') }}', type: 'POST',
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

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Artikel?',
            html: `Apakah Anda yakin ingin menghapus artikel <strong>${name}</strong>?`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#e3342f', confirmButtonText: 'Iya, Hapus!',
            cancelButtonText: 'Batal', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                $.ajax({
                    type: "DELETE", url: url, dataType: "json", data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 2000 })
                        .then(() => table.ajax.reload());
                    },
                    error: function(xhr) { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' }); }
                });
            }
        });
    }
</script>
@endpush
