@extends('layouts.app')

@section('title', 'Master Biaya PPDB')
@section('subtitle', 'Rincian Biaya Daftar Ulang')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">Master Biaya</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-sky overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-money-bill-wave mr-2 animate__animated animate__fadeInLeft"></i> 
                            Master Biaya PPDB
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola komponen biaya pendaftaran dan daftar ulang siswa baru secara terpusat.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-coins fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT SIDEBAR: FILTERS -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter mr-2 text-sky"></i> Filter Data
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-4">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran</label>
                    <div class="input-group-premium bg-light-soft">
                        <i class="fas fa-calendar-alt text-sky"></i>
                        <select id="filter_year" class="form-control font-weight-bold">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $activeYear && $activeYear->id == $year->id ? 'selected' : '' }}>
                                    Tahun Pelajaran {{ $year->academic_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <button type="button" onclick="refreshTable()" class="btn btn-sky btn-block shadow-sm font-weight-bold py-2 mb-3 text-white">
                    <i class="fas fa-sync-alt mr-2"></i> REFRESH DATA
                </button>
                <hr>
                <button onclick="addForm()" class="btn btn-outline-sky btn-block rounded-pill font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> TAMBAH KOMPONEN BIAYA
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-sky text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box mr-3">
                        <i class="fas fa-info-circle fa-2x opacity-5"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Penting</h6>
                        <p class="text-xs mb-0 opacity-8 text-white text-justify">Biaya yang diatur di sini akan muncul otomatis pada invoice pendaftar sesuai Tahun Pelajaran yang aktif.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: TABLE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Rincian Komponen Biaya</h4>
                <p class="text-muted text-sm mb-0">Daftar biaya pendaftaran dan operasional PPDB</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="costTable" style="width:100%">
                        <thead class="bg-light-sky text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>KOMPONEN BIAYA</th>
                                <th>NOMINAL</th>
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

{{-- MODAL --}}
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form action="" method="post">
            @csrf
            @method('post')
            <div class="modal-content border-0">
                <div class="modal-header bg-sky text-white px-4 py-4" style="border-radius: 24px 24px 0 0;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i> <span id="modal-title-text">Tambah Komponen Biaya</span>
                    </h5>
                    <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4 bg-slate-50">
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nama Item Biaya <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white shadow-sm">
                            <i class="fas fa-tag text-sky"></i>
                            <input type="text" name="item_name" class="form-control font-weight-bold" placeholder="Contoh: Seragam Olahraga" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nominal Biaya (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white shadow-sm">
                            <i class="fas fa-money-bill text-sky"></i>
                            <input type="text" id="amount_mask" class="form-control font-weight-bold" placeholder="0" required>
                            <input type="hidden" name="amount" id="amount_real">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tahun Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white shadow-sm">
                            <i class="fas fa-calendar-check text-sky"></i>
                            <select name="academic_year_id" class="form-control font-weight-bold" required>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase ml-1">Keterangan Tambahan</label>
                        <textarea name="description" class="form-control border-0 bg-white shadow-sm font-weight-bold p-3" rows="2" placeholder="Informasi tambahan..." style="border-radius:12px;"></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch premium-switch">
                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active_check" value="1" checked>
                            <label class="custom-control-label font-weight-bold text-dark" for="is_active_check">Aktifkan Item Ini</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 p-4" style="border-radius: 0 0 24px 24px;">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold text-muted" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sky rounded-pill px-5 font-weight-bold shadow-sky-light text-white" id="submitBtn">
                        <i class="fas fa-save mr-1"></i> SIMPAN DATA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('includes.datatable')

@push('css')
<style>
    /* Premium Themes & Layout */
    .bg-gradient-sky { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%) !important; }
    .bg-sky { background: #0ea5e9 !important; }
    .text-sky { color: #0ea5e9 !important; }
    .btn-sky { background: #0ea5e9; color: #fff; border: none; }
    .btn-sky:hover { background: #0284c7; color: #fff; transform: translateY(-2px); }
    .btn-outline-sky { border: 2px solid #0ea5e9; color: #0ea5e9; background: transparent; }
    .btn-outline-sky:hover { background: #0ea5e9; color: #fff; }
    .bg-light-sky { background: #f0f9ff; color: #0369a1; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .shadow-sky-light { box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3); }

    .btn-soft-primary { background: #e0f2fe; color: #0ea5e9; border: none; }
    .btn-soft-primary:hover { background: #bae6fd; color: #0369a1; }
    .btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
    .btn-soft-danger:hover { background: #fecaca; color: #991b1b; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .bg-slate-50 { background: #f8fafc; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%;
    }
    .input-group-premium:focus-within { border-color: #0ea5e9; box-shadow: 0 0 10px rgba(14, 165, 233, 0.1); }
    .input-group-premium:focus-within i { color: #0ea5e9; }

    /* Table Styles Refined */
    #costTable { border-collapse: separate; border-spacing: 0 8px; padding: 0 20px 20px 20px; }
    #costTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    #costTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fff; }
    #costTable td { border: none; padding: 1rem 1.25rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #costTable td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #0ea5e9; }
    #costTable td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid #f1f5f9; }

    /* DataTables Controls Refined */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter { padding: 15px 25px; margin-bottom: 0; }
    .dataTables_wrapper .dataTables_info { padding: 15px 25px; }
    .dataTables_wrapper .dataTables_paginate { padding: 10px 25px; }

    /* Switch Styling */
    .premium-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #0ea5e9; border-color: #0ea5e9; }

    /* Modal Styles */
    .modal-content { border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
</style>
@endpush

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('#costTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari komponen biaya...", search: "" },
            ajax: {
                url: '{{ route("ppdb.payment_items_data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_year').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center font-weight-bold'},
                {
                    data: 'item_name',
                    render: function(data, type, row) {
                        return `
                            <div class="font-weight-bold text-dark mb-0">${data}</div>
                            <div class="text-[10px] text-muted uppercase font-weight-bold">${row.description || '-'}</div>
                        `;
                    }
                },
                {
                    data: 'amount',
                    render: function(data) {
                        return `<span class="badge badge-light-sky px-3 py-2 rounded-pill font-weight-bold text-dark border shadow-sm">${data}</span>`;
                    }
                },
                {
                    data: 'is_active',
                    className: 'text-center',
                    render: function(data) {
                        return data 
                            ? '<span class="badge badge-pill badge-success px-3 shadow-sm"><i class="fas fa-check-circle mr-1 text-xs"></i> AKTIF</span>' 
                            : '<span class="badge badge-pill badge-danger px-3 shadow-sm"><i class="fas fa-times-circle mr-1 text-xs"></i> NON-AKTIF</span>';
                    }
                },
                {
                    data: 'action', 
                    searchable: false, sortable: false, className: 'text-center',
                    render: function(data) {
                        // Action content is generated from controller
                        return data;
                    }
                }
            ]
        });

        $('#filter_year').change(function() {
            refreshTable();
        });

        $('#modal-form form').on('submit', function(e) {
            e.preventDefault();
            let rawAmount = $('#amount_mask').val().replace(/\./g, '');
            $('#amount_real').val(rawAmount);
            
            $('#submitBtn').prop('disabled', true);
            Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.close();
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 2000, showConfirmButton: false });
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });

        $('#amount_mask').on('keyup', function() {
            $(this).val(formatRupiah($(this).val()));
        });
    });

    function refreshTable() {
        let btn = $('.btn-sky');
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> REFRESHING...');
        table.ajax.reload(function() {
            btn.html(originalHtml);
        });
    }

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    }

    function addForm() {
        $('#modal-form').modal('show');
        $('#modal-title-text').text('Tambah Komponen Biaya');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', '{{ route("ppdb.payment_items_store") }}');
        $('#modal-form [name=_method]').val('post');
    }

    function editData(id) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(`{{ url('/admin/admission/ppdb/payment-items') }}/${id}`)
            .done(response => {
                Swal.close();
                let d = response.data;
                $('#modal-form').modal('show');
                $('#modal-title-text').text('Edit Komponen Biaya');
                $('#modal-form form').attr('action', `{{ url('/admin/admission/ppdb/payment-items') }}/${id}`);
                $('#modal-form [name=_method]').val('put');
                
                $('#modal-form [name=item_name]').val(d.item_name);
                $('#amount_mask').val(Math.floor(d.amount)).trigger('keyup');
                $('#modal-form [name=academic_year_id]').val(d.academic_year_id);
                $('#modal-form [name=description]').val(d.description);
                $('#modal-form [name=is_active]').prop('checked', d.is_active);
            });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/admin/admission/ppdb/payment-items') }}/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 2000, showConfirmButton: false });
                    }
                });
            }
        });
    }
</script>
@endpush
