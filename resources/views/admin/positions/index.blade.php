@extends($layout)

@section('title', 'Master Jabatan / Tugas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Master Jabatan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-dark overflow-hidden position-relative" style="border-radius: 15px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-briefcase mr-2 animate__animated animate__fadeInLeft"></i> 
                            Master Jabatan & Tugas
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola struktur organisasi dan tanggung jawab staf di Madrasah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Jabatan</h4>
                        <p class="text-muted text-sm mb-0">Master data untuk klasifikasi tugas guru dan staf</p>
                    </div>
                    <div>
                        <button onclick="addForm(`{{ route('positions.store') }}`)" class="btn btn-primary shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH JABATAN
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="positionTable" style="width:100%">
                        <thead class="bg-light text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>KODE</th>
                                <th>NAMA JABATAN</th>
                                <th>DESKRIPSI</th>
                                <th class="text-center">SIGNER</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.positions.form')
@endsection

@push('css')
<style>
    .premium-card { border-radius: 15px; }
    .btn-premium { border-radius: 50px; transition: all 0.3s; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endpush

@push('scripts')
@include('includes.datatable')
<script>
    let table;

    $(function() {
        table = $('#positionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('positions.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'text-center'},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name', class: 'font-weight-bold text-dark'},
                {data: 'description', name: 'description'},
                {data: 'signer', name: 'signer', class: 'text-center'},
                {data: 'status', name: 'status', class: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ]
        });
    });

    function addForm(url, title = 'Tambah Jabatan Baru') {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text(title);
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=name]').focus();
    }

    function editForm(url, title = 'Edit Jabatan') {
        $.get(url)
            .done(response => {
                $('#modal-form').modal('show');
                $('#modal-form .modal-title').text(title);
                $('#modal-form form').attr('action', url);
                $('#modal-form [name=_method]').val('put');
                
                loopForm(response.data);
            })
            .fail(errors => {
                Swal.fire({icon: 'error', title: 'Oops...', text: 'Gagal mengambil data!'});
            });
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Jabatan?',
            text: "Anda yakin ingin menghapus " + name + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {'_method': 'delete', '_token': '{{ csrf_token() }}'})
                    .done(response => {
                        table.ajax.reload();
                        Swal.fire('Berhasil!', response.message, 'success');
                    })
                    .fail(errors => {
                        Swal.fire('Gagal!', 'Tidak dapat menghapus data.', 'error');
                    });
            }
        });
    }

    function submitForm(originalForm) {
        $.post({
            url: $(originalForm).attr('action'),
            data: new FormData(originalForm),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false
        })
        .done(response => {
            $('#modal-form').modal('hide');
            table.ajax.reload();
            Swal.fire({icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false});
        })
        .fail(errors => {
            if (errors.status == 422) {
                loopErrors(errors.responseJSON.errors);
            } else {
                Swal.fire({icon: 'error', title: 'Oops...', text: 'Terdapat kesalahan!'});
            }
        });
    }
</script>
@endpush
