@extends($layout)

@section('title', 'Jalur Pendaftaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-route mr-2 animate__animated animate__fadeInLeft"></i> 
                            Kategori Jalur Penerimaan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola kategori pendaftaran siswa seperti Jalur Reguler, Prestasi, Afirmasi, atau KIP untuk pengelompokkan kuota.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-map-signs fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0 text-dark">
                        <i class="fas fa-tags mr-2 text-emerald"></i> Master Jalur Pendaftaran
                    </h5>
                    <button onclick="addForm(`{{ route('admission-types.store') }}`)" class="btn btn-emerald rounded-pill px-4 font-weight-bold shadow-emerald-light">
                        <i class="fas fa-plus-circle mr-1"></i> TAMBAH JALUR BARU
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-soft-emerald rounded-20 border-0 shadow-sm mb-4 p-3 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-emerald rounded-circle d-flex align-items-center justify-content-center mr-3 text-white">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="small">
                            Jalur pendaftaran ini akan muncul pada formulir pendaftaran online siswa. Pastikan penamaan jalur sudah sesuai dengan Juknis PPDB tahun berjalan.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="typeTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">NO</th>
                                <th width="30%">TAHUN PELAJARAN</th>
                                <th>NAMA JALUR PENDAFTARAN</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.admission.admission-types.form')

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-emerald-dark { background: linear-gradient(135deg, #065f46 0%, #064e3b 100%) !important; }
    .bg-shape-1 { position: absolute; width: 400px; height: 400px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-2 { position: absolute; width: 200px; height: 200px; background: rgba(16, 185, 129, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-light-emerald { background: #f0fdf4; color: #15803d; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .bg-soft-emerald { background: #f0fdf4; color: #065f46; border-left: 5px solid #10b981; }
    
    .btn-emerald { background: #10b981; color: #fff; }
    .btn-emerald:hover { background: #059669; color: #fff; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
    .text-emerald { color: #10b981; }

    /* Table Styling */
    #typeTable { border-collapse: separate; border-spacing: 0 10px; }
    #typeTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #typeTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #f6fff9; }
    #typeTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #typeTable td:first-child { border-radius: 12px 0 0 12px; }
    #typeTable td:last-child { border-radius: 0 12px 12px 0; }

    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#typeTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari jalur...", search: "" },
            ajax: { url: '{{ route('admission-types.data') }}' },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'academic_year', render: (data) => '<span class="badge badge-light border px-3 py-2 text-emerald font-weight-bold">' + data + '</span>' },
                { data: 'admission_type_name', render: (data) => '<span class="font-weight-bold text-dark h6 mb-0"><i class="fas fa-tag mr-2 text-emerald opacity-50"></i>' + data + '</span>' },
                { data: 'action', className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Tambah Jalur Pendaftaran') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Edit Jalur Pendaftaran') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            loopForm(res.data);
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data tidak ditemukan.' }); });
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(originalForm).attr('action'),
            type: 'POST',
            data: new FormData(originalForm),
            processData: false, contentType: false,
            success: function(res) {
                Swal.close();
                $(modal).modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close(); $(button).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan.' });
                if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Jalur?', text: 'Hapus ' + name + '? Data yang dihapus tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => Swal.showLoading() });
                $.ajax({ type: "DELETE", url: url, data: { _token: '{{ csrf_token() }}' }, success: (r) => { Swal.fire({ icon: 'success', title: 'Dihapus', text: r.message }); table.ajax.reload(); } });
            }
        });
    }
</script>
@endpush
