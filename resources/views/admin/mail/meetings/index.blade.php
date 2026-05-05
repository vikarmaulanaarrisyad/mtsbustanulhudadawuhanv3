@extends($layout)

@section('title', 'Surat Undangan Rapat')
@section('subtitle', 'Persuratan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-calendar-check mr-2 animate__animated animate__fadeInLeft"></i> 
                            Undangan Rapat Sekolah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola jadwal pertemuan dan penerbitan surat undangan rapat secara efisien.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-users-cog fa-8x opacity-2 shadow-icon"></i>
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
                    <i class="fas fa-filter mr-2 text-indigo"></i> Filter Rapat
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="form-group mb-3">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Cari Perihal / Tempat</label>
                    <div class="input-group-premium bg-light-soft">
                        <i class="fas fa-search text-indigo"></i>
                        <input type="text" id="filter_subject" class="form-control font-weight-bold" placeholder="Ketik agenda/tempat...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Mulai</label>
                            <div class="input-group-premium bg-light-soft">
                                <input type="date" id="filter_start_date" class="form-control" value="{{ date('Y-m-01') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Selesai</label>
                            <div class="input-group-premium bg-light-soft">
                                <input type="date" id="filter_end_date" class="form-control" value="{{ date('Y-m-t') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="refreshTable()" class="btn btn-indigo btn-block shadow-sm font-weight-bold py-2 mb-3 text-white">
                    <i class="fas fa-search mr-2"></i> TAMPILKAN DATA
                </button>
                <hr>
                <button onclick="addForm(`{{ route('school-meetings.store') }}`)" class="btn btn-outline-indigo btn-block rounded-pill font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> BUAT UNDANGAN BARU
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-indigo text-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-box mr-3">
                        <i class="fas fa-clock fa-2x opacity-5"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Manajemen Waktu</h6>
                        <p class="text-xs mb-0 opacity-8 text-white text-justify">Pastikan input waktu dan tempat rapat sudah akurat untuk kenyamanan para undangan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: TABLE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-1 font-weight-bold text-dark">Riwayat Undangan Rapat</h4>
                <p class="text-muted text-sm mb-0">Daftar seluruh undangan rapat yang telah diterbitkan</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="meetingTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>DETAIL RAPAT</th>
                                <th>TUJUAN</th>
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
        <i class="fas fa-calendar-plus mr-2 text-indigo"></i> Form Surat Undangan Rapat
    </x-slot>

    <form id="formMeeting">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-md-7 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nomor Surat <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white shadow-sm">
                    <i class="fas fa-hashtag text-indigo"></i>
                    <input type="text" name="meeting_number" id="meeting_number" class="form-control font-weight-bold" placeholder="005/UND/MTs-BH/V/2026">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-link text-indigo font-weight-bold p-0 mr-2" onclick="generateNumber('SchoolMeeting', 'UND', '#meeting_number', 'meeting_number')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tanggal Surat <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white shadow-sm">
                    <i class="fas fa-calendar-day text-indigo"></i>
                    <input type="date" name="mail_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Perihal <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white shadow-sm">
                    <i class="fas fa-info-circle text-indigo"></i>
                    <input type="text" name="meeting_subject" class="form-control font-weight-bold" placeholder="Contoh: Undangan Rapat Wali Murid Kelas VII">
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tujuan Penerima <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white shadow-sm">
                    <i class="fas fa-user-tag text-indigo"></i>
                    <input type="text" name="recipient_description" class="form-control font-weight-bold" placeholder="Contoh: Bapak/Ibu Wali Murid / Dewan Guru">
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 mb-4 shadow-sm">
            <h6 class="text-dark font-weight-bold mb-3 d-flex align-items-center">
                <span class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center mr-2 text-indigo">
                    <i class="fas fa-clock text-xs"></i>
                </span>
                Waktu & Tempat Pelaksanaan
            </h6>
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="text-[10px] font-black text-muted uppercase">Tanggal Rapat <span class="text-danger">*</span></label>
                    <div class="input-group-premium bg-white border-0 shadow-sm">
                        <input type="date" name="meeting_date" class="form-control font-weight-bold border-0">
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="text-[10px] font-black text-muted uppercase">Jam <span class="text-danger">*</span></label>
                    <div class="input-group-premium bg-white border-0 shadow-sm">
                        <input type="time" name="meeting_time" class="form-control font-weight-bold border-0 text-center">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="text-[10px] font-black text-muted uppercase">Tempat <span class="text-danger">*</span></label>
                    <div class="input-group-premium bg-white border-0 shadow-sm">
                        <input type="text" name="meeting_place" class="form-control font-weight-bold border-0" placeholder="Aula Madrasah">
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <label class="text-[10px] font-black text-muted uppercase">Agenda Rapat <span class="text-danger">*</span></label>
                    <textarea name="meeting_agenda" class="form-control border-0 bg-white shadow-sm font-weight-bold p-3" rows="2" placeholder="Pembahasan Persiapan Ujian..." style="border-radius:12px;"></textarea>
                </div>
            </div>
        </div>

        <div class="p-4 bg-light-indigo rounded-2xl border border-dashed border-indigo-200">
            <h6 class="text-dark font-weight-bold mb-3 d-flex align-items-center">
                <span class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center mr-2 text-indigo">
                    <i class="fas fa-signature text-xs"></i>
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
    </form>

    <x-slot name="footer">
        <button type="button" data-dismiss="modal" class="btn btn-light rounded-pill px-4 font-weight-bold text-muted mr-2">
            BATAL
        </button>
        <button type="button" onclick="submitForm('#formMeeting')" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light text-white" id="submitBtn">
            <i class="fas fa-save mr-1"></i> SIMPAN & CETAK
        </button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')

@push('css')
<style>
    /* Premium Themes & Layout */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .bg-indigo { background: #4e73df !important; }
    .text-indigo { color: #4e73df !important; }
    .btn-indigo { background: #4e73df; color: #fff; border: none; }
    .btn-indigo:hover { background: #2e59d9; color: #fff; transform: translateY(-2px); }
    .btn-outline-indigo { border: 2px solid #4e73df; color: #4e73df; background: transparent; }
    .btn-outline-indigo:hover { background: #4e73df; color: #fff; }
    .bg-light-indigo { background: #f0f3ff; color: #4e73df; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3); }

    .btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
    .btn-soft-info:hover { background: #bae6fd; color: #075985; }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
    .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }
    .btn-soft-danger { background: #fee2e2; color: #b91c1c; border: none; }
    .btn-soft-danger:hover { background: #fecaca; color: #991b1b; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-xl { border-radius: 1rem !important; }
    .rounded-2xl { border-radius: 1.5rem !important; }
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
    .input-group-premium:focus-within { border-color: #4e73df; box-shadow: 0 0 10px rgba(78, 115, 223, 0.1); }
    .input-group-premium:focus-within i { color: #4e73df; }

    /* Table Styles */
    #meetingTable { border-collapse: separate; border-spacing: 0 8px; padding: 0 15px; }
    #meetingTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
    #meetingTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fbfcff; }
    #meetingTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #meetingTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #4e73df; }
    #meetingTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }

    /* Modal Styles */
    .modal-content { border-radius: 24px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
</style>
@endpush

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('#meetingTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari undangan...", search: "" },
            ajax: { 
                url: '{{ route("school-meetings.data") }}',
                data: function(d) {
                    d.meeting_subject = $('#filter_subject').val();
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center font-weight-bold' },
                { 
                    data: 'meeting_number',
                    render: function(data, type, row) {
                        return `
                            <div class="mb-1"><span class="badge badge-light border border-indigo px-2 py-1 text-indigo shadow-sm font-weight-bold text-xs">${data}</span></div>
                            <div class="font-weight-bold text-dark mb-1">${row.meeting_subject}</div>
                            <div class="text-[10px] text-muted"><i class="fas fa-map-marker-alt mr-1 text-danger"></i> ${row.meeting_place}</div>
                        `;
                    }
                },
                { 
                    data: 'recipient_description',
                    render: function(data, type, row) {
                        return `
                            <div class="font-weight-bold text-sm text-dark mb-1"><i class="fas fa-user-tag text-indigo mr-2"></i>${data}</div>
                            <div class="text-[10px] text-muted font-weight-bold uppercase"><i class="far fa-clock mr-1"></i> ${row.meeting_date} @ ${row.meeting_time}</div>
                        `;
                    }
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });
    });

    function refreshTable() {
        let btn = $('.btn-indigo');
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> LOADING...');
        table.ajax.reload(function() {
            btn.html(originalHtml);
        });
    }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Undangan Rapat Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');

        // Re-fill defaults
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Undangan Rapat');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
        });
    }

    function submitForm(formId) {
        let form = $(formId);
        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

        $.post(form.attr('action'), form.serialize())
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
            title: 'Hapus Undangan?',
            text: 'Apakah Anda yakin ingin menghapus undangan nomor ' + number + '?',
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
@endpush
