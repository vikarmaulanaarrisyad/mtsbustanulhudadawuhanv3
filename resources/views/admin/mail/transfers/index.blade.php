@extends($layout)

@section('title', 'Surat Mutasi Siswa')
@section('subtitle', 'Persuratan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-emerald overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-truck-moving mr-2 animate__animated animate__fadeInLeft"></i> 
                            Surat Mutasi Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola administrasi kepindahan siswa, penerbitan surat mutasi, dan pengarsipan data mutasi keluar.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-exchange-alt fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <div class="col-12">
        <!-- PREMIUM FILTER PANEL -->
        <div class="card shadow-sm border-0 premium-card mb-4 bg-white">
            <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald mr-3" style="width:40px;height:40px;">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Pencarian Mutasi</h5>
                </div>
                <button onclick="addForm(`{{ route('student-transfers.store') }}`)" class="btn btn-emerald rounded-pill px-4 font-weight-bold shadow-emerald-light">
                    <i class="fas fa-plus-circle mr-1"></i> BUAT SURAT MUTASI
                </button>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Siswa</label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-user-graduate"></i>
                            <select id="filter_student_id" class="form-control select2-no-search border-0">
                                <option value="">-- Seluruh Siswa --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Periode Awal</label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" id="filter_start_date" class="form-control font-weight-bold" value="{{ date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Periode Akhir</label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-calendar-check"></i>
                            <input type="date" id="filter_end_date" class="form-control font-weight-bold" value="{{ date('Y-m-t') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" onclick="refreshTable()" class="btn btn-emerald btn-block rounded-pill font-weight-bold shadow-emerald-light">
                            <i class="fas fa-search mr-1"></i> TAMPILKAN
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN DATA TABLE -->
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Riwayat Mutasi Siswa</h4>
                <p class="text-muted text-sm mb-0">Daftar rekaman kepindahan siswa ke sekolah lain</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="transferTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>NOMOR SURAT</th>
                                <th>TGL PINDAH</th>
                                <th>NAMA SISWA</th>
                                <th>SEKOLAH TUJUAN</th>
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
<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">
        <i class="fas fa-exchange-alt mr-2 text-emerald"></i> Form Surat Mutasi Siswa
    </x-slot>

    @method('POST')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="p-3 bg-light-soft rounded-xl border">
                <label class="text-xs font-black text-muted uppercase tracking-widest mb-2 d-block">
                    <i class="fas fa-user-graduate mr-1"></i> Pilih Siswa <span class="text-danger">*</span>
                </label>
                <select name="student_id" class="form-control select2" style="width: 100%;">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nisn }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nomor Surat <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-hashtag"></i>
                <input type="text" name="transfer_number" id="transfer_number" class="form-control font-weight-bold" placeholder="001/MUT/MTs-BH/V/2026">
                <div class="input-group-append">
                    <button type="button" class="btn btn-link text-emerald font-weight-bold p-0 mr-2" onclick="generateNumber('StudentTransfer', 'MUT', '#transfer_number', 'transfer_number')">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tanggal Pindah <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-calendar-alt text-emerald"></i>
                <input type="date" name="transfer_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
            </div>
        </div>

        <div class="col-12 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Sekolah Tujuan <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-school text-info"></i>
                <input type="text" name="destination_school" class="form-control font-weight-bold" placeholder="Contoh: SMP Negeri 1 Situbondo">
            </div>
        </div>

        <div class="col-12 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Alasan Pindah</label>
            <div class="input-group-premium bg-white align-items-start py-2" style="height: auto;">
                <i class="fas fa-comment-alt mt-1"></i>
                <textarea name="reason" class="form-control font-weight-bold" rows="2" placeholder="Contoh: Mengikuti orang tua pindah domisili" style="resize: none;"></textarea>
            </div>
        </div>

        <div class="col-12">
            <div class="mt-3 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <h6 class="text-dark font-weight-bold mb-3 d-flex align-items-center">
                    <span class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center mr-2 text-emerald">
                        <i class="fas fa-file-signature text-xs"></i>
                    </span>
                    Informasi Penandatangan
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label class="text-[10px] font-black text-muted uppercase">Nama</label>
                            <input type="text" name="signer_name" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_name ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label class="text-[10px] font-black text-muted uppercase">Jabatan</label>
                            <input type="text" name="signer_position" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label class="text-[10px] font-black text-muted uppercase">NIP</label>
                            <input type="text" name="signer_nip" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_nip ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" data-dismiss="modal" class="btn btn-light rounded-pill px-4 font-weight-bold text-muted mr-2">
            BATAL
        </button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-emerald rounded-pill px-5 font-weight-bold shadow-emerald-light" id="submitBtn">
            <i class="fas fa-save mr-1"></i> SIMPAN & CETAK
        </button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;

    $(function() {
        $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });

        table = $('#transferTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari data mutasi...", search: "" },
            ajax: { 
                url: '{{ route("student-transfers.data") }}',
                data: function(d) {
                    d.student_id = $('#filter_student_id').val();
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                { 
                    data: 'transfer_number',
                    render: function(data) {
                        return '<span class="badge badge-light border border-emerald px-2 py-1 text-emerald shadow-sm font-weight-bold">' + data + '</span>';
                    }
                },
                { 
                    data: 'transfer_date',
                    render: function(data) {
                        return '<div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-alt mr-1"></i> ' + data + '</div>';
                    }
                },
                { 
                    data: 'student_name',
                    render: function(data) {
                        return '<span class="font-weight-bold text-dark text-sm">' + data + '</span>';
                    }
                },
                { 
                    data: 'destination_school',
                    render: function(data) {
                        return '<div class="text-xs text-muted font-weight-bold"><i class="fas fa-school text-info mr-1"></i> ' + data + '</div>';
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#modal-form') });
    });

    function refreshTable() {
        let btn = $('.btn-emerald');
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> LOADING...');
        table.ajax.reload(function() {
            btn.html(originalHtml);
        });
    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Mutasi Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        $('.select2').val('').trigger('change');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Mutasi');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
            $('.select2').val(response.data.student_id).trigger('change');
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            })
            .fail(xhr => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, number) {
        Swal.fire({
            title: 'Hapus Surat Mutasi?',
            text: 'Apakah Anda yakin ingin menghapus surat nomor ' + number + '?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message });
                });
            }
        });
    }
</script>
<style>
    /* Premium Design System */
    .bg-gradient-emerald { background: linear-gradient(135deg, #059669 0%, #047857 100%) !important; }
    .bg-light-emerald { background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-emerald { background: #059669; color: #fff; border: none; }
    .btn-emerald:hover { background: #047857; color: #fff; }
    .text-emerald { color: #059669; }
    .bg-soft-emerald { background: #d1fae5; color: #059669; }
    .btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
    .btn-soft-info:hover { background: #bae6fd; color: #075985; }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
    .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }
    .btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
    .btn-soft-danger:hover { background: #fecaca; color: #991b1b; }
    .border-emerald { border-color: #059669 !important; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .rounded-xl { border-radius: 1rem !important; }
    .rounded-2xl { border-radius: 1.5rem !important; }

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
    .input-group-premium:focus-within { border-color: #059669; box-shadow: 0 0 10px rgba(5, 150, 105, 0.1); }
    .input-group-premium:focus-within i { color: #059669; }

    /* Modal Premium Styling */
    .modal-content { border-radius: 24px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.5rem 2rem; }
    .modal-title { font-weight: 800; color: #1e293b; letter-spacing: -0.025em; }
    .modal-body { padding: 2rem; }
    .modal-footer { border-top: 1px solid #f1f5f9; padding: 1.5rem 2rem; background: #f8fafc; border-radius: 0 0 24px 24px; }

    /* Table Enhancements */
    #transferTable { border-collapse: separate; border-spacing: 0 8px; }
    #transferTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #transferTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fffa; }
    #transferTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #transferTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #059669; }
    #transferTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
    
    .bg-slate-50 { background: #f8fafc; }
</style>
@endpush
