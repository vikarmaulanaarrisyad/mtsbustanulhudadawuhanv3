@extends($layout)

@section('title', 'Mata Pelajaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-book-open mr-2 animate__animated animate__fadeInLeft"></i> 
                            Direktori Mata Pelajaran
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola seluruh kurikulum, kategori mapel, dan kode referensi akademik Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-graduation-cap fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Kurikulum</h4>
                        <p class="text-muted text-sm mb-0">Total mata pelajaran yang terdaftar dalam sistem</p>
                    </div>
                    <div class="d-flex align-items-center mt-2 mt-md-0">
                        <button onclick="importForm()" class="btn btn-success shadow-sm font-weight-bold px-4 btn-premium mr-2">
                            <i class="fas fa-file-excel mr-1"></i> IMPORT EXCEL
                        </button>
                        <button onclick="addForm(`{{ route('subjects.store') }}`)" class="btn btn-emerald shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH MAPEL
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="subjectTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">NO</th>
                                <th>KODE MAPEL</th>
                                <th>NAMA MATA PELAJARAN</th>
                                <th>KATEGORI</th>
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
                <div class="modal-header bg-gradient-emerald text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">Form Mata Pelajaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                            <div class="input-group-premium">
                                <i class="fas fa-bookmark"></i>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Bahasa Indonesia" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Kode Mapel</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-id-card-alt"></i>
                                        <input type="text" name="code" class="form-control" placeholder="Bindo-01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Kategori</label>
                                    <select name="category" class="form-control rounded-pill px-3 border-2">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Kelompok A (Wajib)">Kelompok A (Wajib)</option>
                                        <option value="Kelompok B (Wajib)">Kelompok B (Wajib)</option>
                                        <option value="Keagamaan">Keagamaan</option>
                                        <option value="Muatan Lokal">Muatan Lokal</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-emerald rounded-pill px-5 font-weight-bold shadow-emerald-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN MAPEL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-emerald { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .bg-light-emerald { background: #ecfdf5; color: #10b981; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-emerald { background: #10b981; color: #fff; }
    .btn-emerald:hover { background: #059669; color: #fff; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(16,185,129,0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-soft { background: #f8fafc; }

    /* Table Styling */
    #subjectTable { border-collapse: separate; border-spacing: 0 10px; }
    #subjectTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #subjectTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fcfdfd; }
    #subjectTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #subjectTable td:first-child { border-radius: 10px 0 0 10px; font-weight: bold; color: #10b981; }
    #subjectTable td:last-child { border-radius: 0 10px 10px 0; }

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
    .input-group-premium:focus-within { border-color: #10b981; box-shadow: 0 0 15px rgba(16,185,129,0.1); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        table = $('#subjectTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari mata pelajaran...", search: "" },
            ajax: { url: '{{ route("subjects.data") }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { 
                    data: 'code',
                    render: (data) => '<span class="badge badge-light border text-primary font-weight-bold px-3 py-2">' + (data || '-') + '</span>'
                },
                { 
                    data: 'name',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald font-weight-bold" style="width:35px;height:35px;background:#e1f9f1;color:#059669;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'category_badge' },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Mata Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function importForm() { $('#modal-import').modal('show'); }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Mata Pelajaran');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(res.data);
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        $.post($(form).attr('action'), $(form).serialize())
            .done(res => {
                Swal.close(); $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false });
            })
            .fail(xhr => {
                Swal.close(); $('#submitBtn').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
            });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Mata Pelajaran?', text: 'Yakin ingin menghapus mapel ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(res => { table.ajax.reload(); Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); });
            }
        });
    }
</script>
@endpush
