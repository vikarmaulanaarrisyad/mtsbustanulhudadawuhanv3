@extends($layout)

@section('title', 'Jam Pelajaran')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-clock mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pengaturan Slot Jam Pelajaran
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan urutan jam, durasi pembelajaran, dan waktu istirahat dalam satu hari sekolah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-stopwatch fa-8x opacity-2 shadow-icon"></i>
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
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Slot Waktu</h4>
                        <p class="text-muted text-sm mb-0">Konfigurasi durasi dan jeda istirahat harian</p>
                    </div>
                    <button onclick="addForm(`{{ route('study-periods.store') }}`)" class="btn btn-indigo shadow-sm font-weight-bold px-4 btn-premium">
                        <i class="fas fa-plus-circle mr-1"></i> TAMBAH JAM
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="periodTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="80px" class="text-center py-3">NO</th>
                                <th>JAM KE-</th>
                                <th>WAKTU MULAI</th>
                                <th>WAKTU SELESAI</th>
                                <th class="text-center">TIPE</th>
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
                <div class="modal-header bg-gradient-indigo text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">Konfigurasi Waktu</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Urutan Jam Ke-</label>
                            <div class="input-group-premium">
                                <i class="fas fa-list-ol"></i>
                                <input type="number" name="period_number" class="form-control" placeholder="Contoh: 1" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Jam Mulai</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Jam Selesai</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tipe Slot</label>
                            <select name="is_break" class="form-control rounded-pill px-3 border-2">
                                <option value="0">📖 Jam Pelajaran</option>
                                <option value="1">☕ Istirahat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN SLOT
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2 0%, #4b0082 100%) !important; }
    .bg-light-indigo { background: #f4f0fa; color: #6610f2; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-indigo { background: #6610f2; color: #fff; }
    .btn-indigo:hover { background: #520dc2; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(102,162,242,0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-soft { background: #f8f9fc; }

    /* Table Styling */
    #periodTable { border-collapse: separate; border-spacing: 0 10px; }
    #periodTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #periodTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #periodTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #periodTable td:first-child { border-radius: 10px 0 0 10px; font-weight: bold; color: #6610f2; }
    #periodTable td:last-child { border-radius: 0 10px 10px 0; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e1e8ef; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #adb5bd; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #2d4154; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #6610f2; box-shadow: 0 0 15px rgba(102,16,242,0.1); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        table = $('#periodTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari jam...", search: "" },
            ajax: { url: '{{ route("study-periods.data") }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'period_number',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo font-weight-bold" style="width:35px;height:35px;background:#f0eaff;">' + data + '</div><span class="font-weight-bold text-dark h6 mb-0">Jam Ke-' + data + '</span></div>';
                    }
                },
                { data: 'start_time', render: (data) => '<span class="badge badge-light border px-3"><i class="far fa-clock mr-1 text-primary"></i> ' + data + '</span>' },
                { data: 'end_time', render: (data) => '<span class="badge badge-light border px-3"><i class="far fa-clock mr-1 text-danger"></i> ' + data + '</span>' },
                { data: 'is_break_label', className: 'text-center' },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Jam Pelajaran');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Jam Pelajaran');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
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
                toastr.success(response.message);
            })
            .fail(xhr => {
                Swal.close();
                $('#submitBtn').prop('disabled', false);
                toastr.error(xhr.responseJSON?.message || 'Gagal menyimpan data.');
            });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Jam Ke-' + name + '?', text: "Data yang dihapus tidak bisa dikembalikan!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.post(url, { '_method': 'DELETE', '_token': '{{ csrf_token() }}' })
                    .done(response => { table.ajax.reload(); toastr.success(response.message); })
                    .fail(xhr => toastr.error(xhr.responseJSON?.message || 'Gagal menghapus data.'));
            }
        });
    }
</script>
@endpush
