@extends($layout)

@section('title', 'Kuota Penerimaan PPDB')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-cyan-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-users-cog mr-2 animate__animated animate__fadeInLeft"></i> 
                            Alokasi & Kuota Pendaftar
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan batas kapasitas penerimaan siswa baru untuk setiap jalur dan gelombang pendaftaran secara presisi.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chart-bar fa-8x opacity-1 shadow-icon"></i>
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
                        <i class="fas fa-th-list mr-2 text-cyan"></i> Master Alokasi Kuota
                    </h5>
                    <button onclick="addForm(`{{ route('admission-quotas.store') }}`)" class="btn btn-cyan rounded-pill px-4 font-weight-bold shadow-cyan-light text-white">
                        <i class="fas fa-plus-circle mr-1"></i> TETAPKAN KUOTA BARU
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-soft-cyan rounded-20 border-0 shadow-sm mb-4 p-3 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-cyan rounded-circle d-flex align-items-center justify-content-center mr-3 text-white">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="small">
                            Pengaturan kuota ini akan membatasi jumlah siswa yang dapat dinyatakan <strong>Diterima</strong> pada proses seleksi otomatis.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="quotaTable" style="width:100%">
                        <thead class="bg-light-cyan text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3">NO</th>
                                <th>TAHUN PELAJARAN</th>
                                <th>GELOMBANG</th>
                                <th>JALUR PENDAFTARAN</th>
                                <th class="text-center">KUOTA</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="quotaBody">
                            {{-- Data via DataTables --}}
                        </tbody>
                        <tfoot class="bg-light-soft border-top-2">
                            <tr>
                                <th colspan="4" class="text-right py-3 pr-4 font-weight-bold text-dark">TOTAL KAPASITAS PENERIMAAN:</th>
                                <th class="text-center py-3">
                                    <span class="badge badge-cyan px-4 py-2 rounded-pill font-weight-bold shadow-sm h5 mb-0 text-white" id="total-quota">0</span>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.admission.admission-quotas.form')

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-cyan-dark { background: linear-gradient(135deg, #0891b2 0%, #164e63 100%) !important; }
    .bg-shape-1 { position: absolute; width: 400px; height: 400px; background: rgba(6, 182, 212, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-2 { position: absolute; width: 200px; height: 200px; background: rgba(6, 182, 212, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-light-cyan { background: #ecfeff; color: #0e7490; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .bg-soft-cyan { background: #ecfeff; color: #0891b2; border-left: 5px solid #06b6d4; }
    
    .btn-cyan { background: #06b6d4; color: #fff; }
    .btn-cyan:hover { background: #0891b2; color: #fff; }
    .shadow-cyan-light { box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3); }
    .text-cyan { color: #06b6d4; }
    .badge-cyan { background: #06b6d4; color: #fff; }

    /* Table Styling */
    #quotaTable { border-collapse: separate; border-spacing: 0 10px; }
    #quotaTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #quotaTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #f0faff; }
    #quotaTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #quotaTable td:first-child { border-radius: 12px 0 0 12px; }
    #quotaTable td:last-child { border-radius: 0 12px 12px 0; }

    .border-top-2 { border-top: 3px double #e2e8f0; }
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
        table = $('#quotaTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari kuota...", search: "" },
            ajax: { url: '{{ route('admission-quotas.data') }}' },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'academic_year', render: (data) => '<span class="badge badge-light border px-3 py-2 text-cyan font-weight-bold">' + data + '</span>' },
                { data: 'admission_phase', render: (data) => '<span class="font-weight-bold text-dark"><i class="fas fa-layer-group mr-2 text-cyan opacity-50"></i>' + data + '</span>' },
                { data: 'admission_types', render: (data) => '<span class="small font-weight-bold text-muted"><i class="fas fa-route mr-1"></i> ' + data + '</span>' },
                { 
                    data: 'quota', 
                    className: 'text-center',
                    render: (data) => '<span class="badge badge-soft-cyan px-4 py-2 rounded-pill font-weight-bold" style="font-size:0.9rem;">' + data + ' Siswa</span>'
                },
                { data: 'action', className: 'text-center' },
            ],
            footerCallback: function(row, data, start, end, display) {
                let api = this.api();
                let total = api.column(4, { page: 'current' }).data().reduce((a, b) => {
                    let x = typeof a === 'string' ? parseInt(a.replace(/[^\d]/g, '')) || 0 : a;
                    let y = typeof b === 'string' ? parseInt(b.replace(/[^\d]/g, '')) || 0 : b;
                    return x + y;
                }, 0);
                $('#total-quota').html(total.toLocaleString('id-ID'));
            }
        });
    });

    function addForm(url, title = 'Tetapkan Kuota Pendaftaran Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Ubah Alokasi Kuota') {
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
        Swal.fire({ title: 'Hapus Kuota?', text: 'Hapus alokasi ' + name + '? Tindakan ini tidak dapat dibatalkan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => Swal.showLoading() });
                $.ajax({ type: "DELETE", url: url, data: { _token: '{{ csrf_token() }}' }, success: (r) => { Swal.fire({ icon: 'success', title: 'Dihapus', text: r.message }); table.ajax.reload(); } });
            }
        });
    }
</script>
@endpush
