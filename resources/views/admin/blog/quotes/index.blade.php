@extends($layout)

@section('title', 'Kutipan & Kata Mutiara')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Jurnalistik</li>
    <li class="breadcrumb-item active">Kata Mutiara</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-cyan overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-quote-left mr-2 animate__animated animate__pulse animate__infinite"></i> 
                            Kutipan Inspiratif
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Bagikan kata mutiara, motivasi, atau kutipan tokoh inspiratif yang akan mempercantik halaman depan website Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-feather-alt fa-8x opacity-2 shadow-icon"></i>
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
                    <i class="fas fa-list-ul mr-2 text-cyan"></i> Arsip Kutipan
                </h4>
                <div class="d-flex" style="gap: 10px;">
                    <button id="deleteSelectedBtn" class="btn btn-danger rounded-pill font-weight-bold shadow-sm px-4" disabled>
                        <i class="fas fa-trash-alt mr-1"></i> HAPUS TERPILIH
                    </button>
                    <button onclick="addForm(`{{ route('quotes.store') }}`)" class="btn btn-cyan rounded-pill font-weight-bold shadow-cyan-light px-4">
                        <i class="fas fa-plus-circle mr-1"></i> TAMBAH KUTIPAN
                    </button>
                </div>
            </div>
            
            <div class="card-body p-4 bg-light-soft">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table" style="width:100%">
                        <thead class="bg-light-cyan text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">
                                    <div class="custom-control custom-checkbox ml-2">
                                        <input type="checkbox" class="custom-control-input" id="selectAll">
                                        <label class="custom-control-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th width="50px" class="text-center">NO</th>
                                <th>ISI KUTIPAN</th>
                                <th width="200px">TOKOH / SUMBER</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.blog.quotes.form')

<style>
    /* Premium Cyan Design System */
    .bg-gradient-cyan { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%) !important; }
    .bg-light-cyan { background: #f0f9ff; color: #0369a1; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-cyan { background: #0ea5e9; color: #fff; border: none; }
    .btn-cyan:hover { background: #0284c7; color: #fff; }
    .text-cyan { color: #0ea5e9; }
    .shadow-cyan-light { box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4); }

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
    #table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); background: #f0f9ff; }
    #table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; }
    #table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endsection

@include('includes.datatable')
@include('includes.summernote')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-quotes';
    let button = '#submitBtn';

    $(function() {
        table = $('#table').DataTable({
            processing: false, serverSide: true, autoWidth: false, responsive: true,
            language: { searchPlaceholder: "Cari kutipan...", search: "" },
            ajax: { url: '{{ route('quotes.data') }}' },
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
                { 
                    data: 'quote', orderable: false, searchable: false,
                    render: function(data) { return '<div class="text-dark" style="line-height:1.6; font-style:italic;"><i class="fas fa-quote-left text-cyan opacity-50 mr-2 text-sm"></i>' + data + '</div>'; }
                },
                { 
                    data: 'quote_by', orderable: false, searchable: false,
                    render: function(data) { return `<span class="badge badge-light border text-dark font-weight-bold px-2 py-1 shadow-sm"><i class="fas fa-feather-alt text-cyan mr-1"></i> ${data}</span>`; }
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

            if (selectedIds.length === 0) return Swal.fire('Oops!', 'Pilih kutipan terlebih dahulu.', 'warning');

            Swal.fire({
                title: 'Hapus Kutipan?',
                text: `Anda akan menghapus ${selectedIds.length} kata mutiara. Lanjutkan?`,
                icon: 'warning', showCancelButton: true, confirmButtonText: 'Iya, Hapus!', cancelButtonText: 'Batal',
                reverseButtons: true, confirmButtonColor: '#e3342f'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    
                    $.ajax({
                        url: '{{ route('quotes.deleteSelected') }}', type: 'POST',
                        data: { _token: '{{ csrf_token() }}', ids: selectedIds },
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 2000 })
                            .then(() => table.ajax.reload());
                        },
                        error: function(xhr) { Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error'); }
                    });
                }
            });
        });
    });

    function addForm(url, title = 'Kutipan Inspiratif Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title-text`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
        if($('.summernote').length) { $('.summernote').summernote('code', ''); }
    }

    function editForm(url, title = 'Edit Kutipan') {
        Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.get(url).done(response => {
            Swal.close();
            $(modal).modal('show');
            $(`${modal} .modal-title-text`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            loopForm(response.data);
            if($('.summernote').length) { $('.summernote').summernote('code', response.data.quote || ''); }
        }).fail(errors => {
            Swal.fire({ icon: 'error', title: 'Gagal', text: errors.responseJSON?.message || 'Tidak dapat memuat data.' });
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
                btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN KUTIPAN');
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Kutipan?',
            html: `Apakah Anda yakin ingin menghapus kata mutiara dari <strong>${name}</strong>?`,
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
