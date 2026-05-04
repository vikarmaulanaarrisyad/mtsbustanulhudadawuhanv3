@extends($layout)

@section('title', 'Halaman Statis Sekolah')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Website Utama</li>
    <li class="breadcrumb-item active">Halaman Statis</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-file-alt mr-2 animate__animated animate__pulse animate__infinite"></i> 
                            Halaman Resmi Madrasah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola halaman statis dan informatif seperti Profil Singkat, Sejarah, Fasilitas, dan Visi Misi secara terpusat.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-sitemap fa-8x opacity-2 shadow-icon"></i>
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
                    <i class="fas fa-list-ul mr-2 text-primary"></i> Daftar Halaman
                </h4>
                <div class="d-flex" style="gap: 10px;">
                    <a href="{{ route('pages.create') }}" class="btn btn-primary rounded-pill font-weight-bold shadow-primary-light px-4">
                        <i class="fas fa-plus-circle mr-1"></i> BUAT HALAMAN BARU
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table" style="width:100%">
                        <thead class="bg-light-primary text-uppercase">
                            <tr>
                                <th width="50px" class="text-center">NO</th>
                                <th>JUDUL HALAMAN DOKUMEN</th>
                                <th>URL SLUG</th>
                                <th width="120px" class="text-center">AKSI TINDAKAN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Primary/Blue Design System */
    .bg-gradient-primary { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; }
    .bg-light-primary { background: #eff6ff; color: #1d4ed8; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-primary { background: #3b82f6; color: #fff; border: none; }
    .btn-primary:hover { background: #2563eb; color: #fff; }
    .text-primary { color: #2563eb !important; }
    .shadow-primary-light { box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); }

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
    #table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #eff6ff; }
    #table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; }
    #table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#table').DataTable({
            processing: false, serverSide: true, autoWidth: false, responsive: true,
            pageLength: 30, lengthMenu: [[10, 30, 50, 100], [10, 30, 50, 100]],
            language: { searchPlaceholder: "Cari halaman...", search: "" },
            ajax: { url: '{{ route('pages.data') }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold text-muted' },
                { 
                    data: 'title', orderable: false, searchable: false,
                    render: function(data) { return '<div class="font-weight-bold text-dark text-md"><i class="far fa-file-alt text-primary mr-2 opacity-50"></i>' + data + '</div>'; }
                },
                { 
                    data: 'slug', orderable: false, searchable: false,
                    render: function(data) { return `<span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-link mr-1"></i> ${data}</span>`; }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
        });
    });

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Halaman?',
            html: `Apakah Anda yakin ingin menghapus halaman <strong>${name}</strong>? Data yang dihapus tidak dapat dikembalikan.`,
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
