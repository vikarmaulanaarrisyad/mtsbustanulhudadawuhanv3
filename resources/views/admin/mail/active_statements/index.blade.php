@extends($layout)

@section('title', 'Surat Keterangan Siswa Aktif')
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
                            <i class="fas fa-file-signature mr-2 animate__animated animate__fadeInLeft"></i> 
                            Surat Keterangan Aktif
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Penerbitan surat keterangan siswa aktif belajar, baik secara individu maupun kolektif per kelas.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-user-graduate fa-8x opacity-2 shadow-icon"></i>
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
                    <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Parameter Surat</h5>
                </div>
                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                    <button onclick="addForm(`{{ route('active-statements.store') }}`, 'individual')" class="btn btn-soft-primary font-weight-bold px-3">
                        <i class="fas fa-user mr-1"></i> INDIVIDU
                    </button>
                    <button onclick="addForm(`{{ route('active-statements.store') }}`, 'collective')" class="btn btn-soft-emerald font-weight-bold px-3">
                        <i class="fas fa-users mr-1"></i> KOLEKTIF
                    </button>
                </div>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Cari Siswa</label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-search text-emerald"></i>
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
                <h4 class="mb-1 font-weight-bold text-dark">Riwayat Surat Keterangan Aktif</h4>
                <p class="text-muted text-sm mb-0">Detail rekaman surat keterangan aktif siswa yang pernah diterbitkan</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="activeStatementTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>NOMOR SURAT</th>
                                <th>TANGGAL</th>
                                <th>TIPE</th>
                                <th>NAMA SISWA</th>
                                <th>KEPERLUAN</th>
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
        <i class="fas fa-file-invoice mr-2 text-emerald"></i> Form Surat Keterangan Aktif
    </x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tipe Surat <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-layer-group text-info"></i>
                <select name="type" id="type" class="form-control font-weight-bold border-0" onchange="toggleType()">
                    <option value="individual">Individu (1 Siswa)</option>
                    <option value="collective">Kolektif (Banyak Siswa)</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Pilih Siswa <span class="text-danger">*</span></label>
            <div class="p-2 bg-light-soft rounded-xl border">
                <select name="student_ids[]" id="student_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nis }})</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1 font-italic" id="student_hint">Pilih 1 siswa untuk tipe Individu.</small>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nomor Surat <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-hashtag"></i>
                <input type="text" name="letter_number" id="letter_number" class="form-control font-weight-bold" placeholder="001/S-AKT/MTs-BH/V/2026">
                <div class="input-group-append">
                    <button type="button" class="btn btn-link text-emerald font-weight-bold p-0 mr-2" onclick="generateNumber('StudentActiveStatement', 'S-AKT', '#letter_number')">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tanggal Surat <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-calendar-day text-emerald"></i>
                <input type="date" name="letter_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        
        <div class="col-12 mb-4">
            <label class="text-xs font-weight-bold text-muted uppercase ml-1">Keperluan <span class="text-danger">*</span></label>
            <div class="input-group-premium bg-white">
                <i class="fas fa-info-circle text-muted"></i>
                <input type="text" name="purpose" class="form-control font-weight-bold" placeholder="Contoh: Pengurusan Tunjangan Anak / Paspor">
            </div>
        </div>

        <div class="col-12">
            <div class="p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
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
            <i class="fas fa-save mr-1"></i> SIMPAN SURAT
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

        table = $('#activeStatementTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari surat...", search: "" },
            ajax: { 
                url: '{{ route("active-statements.data") }}',
                data: function(d) {
                    d.student_id = $('#filter_student_id').val();
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                { 
                    data: 'letter_number',
                    render: function(data) {
                        return '<span class="badge badge-light border border-emerald px-2 py-1 text-emerald shadow-sm font-weight-bold">' + data + '</span>';
                    }
                },
                { 
                    data: 'letter_date',
                    render: function(data) {
                        return '<div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-alt mr-1"></i> ' + data + '</div>';
                    }
                },
                { 
                    data: 'type', 
                    className: 'text-center',
                    render: (data) => data === 'individual' ? '<span class="badge badge-soft-primary px-2">Individu</span>' : '<span class="badge badge-soft-emerald px-2">Kolektif</span>' 
                },
                { 
                    data: 'student_list',
                    render: function(data) {
                        return '<span class="font-weight-bold text-dark text-sm">' + data + '</span>';
                    }
                },
                { 
                    data: 'purpose',
                    render: function(data) {
                        return '<div class="text-xs text-muted font-weight-bold">' + data + '</div>';
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#student_ids').select2({ 
            theme: 'bootstrap4', 
            dropdownParent: $('#modal-form'),
            placeholder: '-- Pilih Siswa --'
        });
    });

    function refreshTable() {
        let btn = $('.btn-emerald');
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> LOADING...');
        table.ajax.reload(function() {
            btn.html(originalHtml);
        });
    }

    function toggleType() {
        let type = $('#type').val();
        if (type === 'individual') {
            $('#student_hint').text('Pilih 1 siswa untuk tipe Individu.');
            // Limit select2 to 1 if individual? 
            // Better to just let user select multiple but warn them, or handle in backend.
        } else {
            $('#student_hint').text('Anda bisa memilih lebih dari 1 siswa untuk tipe Kolektif.');
        }
    }

    function addForm(url, type = 'individual') {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Keterangan Aktif Baru (' + (type === 'individual' ? 'Individu' : 'Kolektif') + ')');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        $('#type').val(type);
        $('#student_ids').val(null).trigger('change');
        toggleType();
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Keterangan Aktif');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            
            loopForm(response.data);
            
            // If signer info is empty in the record, optionally keep it empty or fill with defaults
            // Usually for edit, we keep what's in the record.
            
            $('#student_ids').val(response.student_ids).trigger('change');
            toggleType();
        });
    }

    function submitForm(form) {
        let type = $('#type').val();
        let students = $('#student_ids').val();

        if (type === 'individual' && students.length > 1) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Tipe Individu hanya diperbolehkan memilih 1 siswa.' });
            return;
        }

        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                Swal.close();
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            })
            .fail(xhr => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, number) {
        Swal.fire({
            title: 'Hapus Surat?',
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
    .badge-soft-emerald { background: #d1fae5; color: #059669; }
    .badge-soft-primary { background: #e0e7ff; color: #4338ca; }
    .btn-soft-emerald { background: #d1fae5; color: #059669; border: none; }
    .btn-soft-emerald:hover { background: #a7f3d0; color: #047857; }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
    .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }
    .btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
    .btn-soft-info:hover { background: #bae6fd; color: #075985; }
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
    #activeStatementTable { border-collapse: separate; border-spacing: 0 8px; }
    #activeStatementTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #activeStatementTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fffa; }
    #activeStatementTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #activeStatementTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #059669; }
    #activeStatementTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
    
    .bg-slate-50 { background: #f8fafc; }
</style>
@endpush
