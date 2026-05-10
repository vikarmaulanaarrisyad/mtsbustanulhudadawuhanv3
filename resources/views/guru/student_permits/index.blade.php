@extends($layout)
@section('title', 'Manajemen Izin Siswa')

@section('content')
<div class="dashboard-wrapper pb-20">

    {{-- HEADER BANNER --}}
    <div class="header-banner bg-grad-orange pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-5">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center border border-white/30 shadow-xl">
                        <i class="fas fa-file-medical-alt text-white text-2xl"></i>
                    </div>
                    <div class="text-white">
                        <span class="bg-white/20 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-1 inline-block">Disiplin & Kehadiran</span>
                        <h1 class="text-2xl font-black leading-tight">Manajemen Izin Siswa</h1>
                        <p class="text-white/70 text-xs font-bold mt-1">
                            <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $teacher->name }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('guru.dashboard') }}" class="flex items-center space-x-2 bg-white/15 hover:bg-white/25 text-white text-xs font-black px-5 py-3 rounded-2xl border border-white/20 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="absolute right-[-50px] top-[-30px] w-56 h-56 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-20px] bottom-[-30px] w-40 h-40 bg-orange-400/10 rounded-full blur-2xl"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">

        {{-- FILTER & SUMMARY --}}
        <div class="row g-4 mb-6">
            <div class="col-md-3">
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden h-100 flex flex-col justify-center">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-3">
                        <i class="fas fa-filter"></i>
                    </div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Status Izin</label>
                    <select id="filter-status" class="form-control rounded-xl border-slate-200 font-bold text-sm bg-slate-50" style="height:46px" onchange="table.ajax.reload()">
                        <option value="">Semua Status</option>
                        <option value="pending" selected>Menunggu Persetujuan</option>
                        <option value="approved">Telah Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
            <div class="col-md-9">
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden h-100">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mr-4 shrink-0 shadow-sm">
                            <i class="fas fa-lightbulb text-xl"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-black text-slate-800 mb-1">Informasi Fitur (Khusus Wali Kelas)</h5>
                            <p class="text-xs font-bold text-slate-500 mb-0 leading-relaxed">
                                Fitur ini digunakan untuk meninjau pengajuan izin/sakit secara khusus dari <strong>siswa di kelas perwalian Anda</strong>. 
                                <strong class="text-emerald-600">Persetujuan (Approve)</strong> akan secara otomatis mencatat absensi siswa tersebut ke dalam sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATA TABLE CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center space-x-3 bg-slate-50/50">
                <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list-alt text-orange-600 text-sm"></i>
                </div>
                <div>
                    <h5 class="font-black text-slate-800 text-sm mb-0">Daftar Pengajuan Izin Siswa</h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0">Hanya menampilkan siswa dari kelas perwalian Anda (Wali Kelas)</p>
                </div>
            </div>

            <div class="table-responsive p-2">
                <table id="permits-table" class="table align-middle mb-0 w-100">
                    <thead style="background:#f8fafc;border-bottom:2px solid #e2e8f0">
                        <tr>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="50">NO</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">IDENTITAS SISWA</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="100">JENIS</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="150">TANGGAL IZIN</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="180">ALASAN</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="120">STATUS</th>
                            <th class="text-center px-4 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest" width="100">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables Content --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- MODAL AKSI IZIN -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-4">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl overflow-hidden">
            <div class="bg-grad-orange p-8 text-white text-center relative">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fas fa-clipboard-check text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-1">Tinjau Pengajuan Izin</h4>
                    <p id="modalStudentName" class="text-orange-100 text-[10px] font-black uppercase tracking-widest opacity-80">Nama Siswa</p>
                </div>
                <div class="absolute right-[-20px] top-[-20px] w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            </div>
            
            <div class="p-8 bg-white">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Alasan Pengajuan (<span id="modalPermitType"></span>)</span>
                    <p id="modalReason" class="text-sm font-bold text-slate-700 mb-0"></p>
                </div>

                <form id="formAction">
                    @csrf
                    <input type="hidden" id="modalPermitId">
                    <input type="hidden" name="status" id="modalStatusInput">
                    
                    <div class="mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Tambahkan Catatan Guru (Opsional)</label>
                        <textarea name="note" id="modalNote" rows="2" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cepat sembuh, ya! / Maaf, izin tidak valid..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="submitAction('rejected')" class="w-full bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-black py-4 rounded-2xl border border-rose-100 hover:border-rose-600 transition-all active:scale-95 uppercase tracking-widest text-[10px] shadow-sm">
                            <i class="fas fa-times mr-1"></i> Tolak Izin
                        </button>
                        <button type="button" onclick="submitAction('approved')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-200 transition-all active:scale-95 uppercase tracking-widest text-[10px]">
                            <i class="fas fa-check mr-1"></i> Setujui Izin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
body { background:#f8fafc; font-family:'Outfit',sans-serif; }
.bg-grad-orange { background:linear-gradient(135deg,#ea580c 0%,#c2410c 100%); }
.header-banner { padding-top:40px; padding-bottom:80px; }

/* Table overrides */
#permits-table tbody tr { border-bottom:1px solid #f1f5f9; transition:all 0.2s ease; }
#permits-table tbody tr:hover { background:#fff7ed; transform:translateY(-1px); }
#permits-table td { padding:1rem; vertical-align:middle; }

/* DataTables styling */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #ea580c !important; color: white !important; border: none; border-radius: 8px; font-weight: bold;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px; border: none; margin: 0 2px;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e2e8f0; border-radius: 12px; padding: 6px 15px; outline: none;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #ea580c; box-shadow: 0 0 0 3px rgba(234,88,12,0.1);
}

.btn-orange { background: #ea580c; border: none; }
.btn-orange:hover { background: #c2410c; }

@media(max-width:768px){
    .header-banner { padding-top:30px; padding-bottom:70px; }
}
</style>
@endsection

@push('scripts')
<script>
let table;

$(function() {
    initDataTable();
});

function initDataTable() {
    table = $('#permits-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("guru.student-permits.data") }}',
            data: function(d) {
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-sm font-black text-slate-400'},
            {data: 'student_info', name: 'student.nama_lengkap'},
            {data: 'permit_type', name: 'type', className: 'text-center'},
            {data: 'date_range', name: 'start_date', className: 'text-center font-bold text-slate-600 text-xs'},
            {data: 'reason', name: 'reason', className: 'text-xs font-bold text-slate-500'},
            {data: 'status_badge', name: 'status', className: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
        ],
        language: {
            processing: '<i class="fas fa-spinner fa-spin fa-2x text-orange-500"></i><br><span class="text-xs font-bold text-slate-500">Memuat Data...</span>',
            zeroRecords: '<div class="py-10"><i class="fas fa-envelope-open-text text-slate-200 fa-3x mb-3"></i><p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada pengajuan izin ditemukan</p></div>',
            info: '<span class="text-xs font-bold text-slate-500">Menampilkan _START_ sampai _END_ dari _TOTAL_ izin</span>',
            infoEmpty: '<span class="text-xs font-bold text-slate-500">0 data</span>',
            search: '<span class="text-xs font-bold text-slate-500 mr-2">Cari Nama:</span>',
            paginate: {
                previous: '<i class="fas fa-chevron-left text-xs"></i>',
                next: '<i class="fas fa-chevron-right text-xs"></i>'
            }
        },
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"text-sm font-bold text-slate-600"l><"mt-3 md:mt-0"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>'
    });
}

function openActionModal(id, name, type, reason) {
    $('#modalPermitId').val(id);
    $('#modalStudentName').text(name);
    $('#modalPermitType').text(type);
    $('#modalReason').text(reason);
    $('#modalNote').val('');
    $('#actionModal').modal('show');
}

function submitAction(status) {
    const id = $('#modalPermitId').val();
    $('#modalStatusInput').val(status);
    
    const statusText = status === 'approved' ? 'Menyetujui' : 'Menolak';
    const color = status === 'approved' ? '#10b981' : '#f43f5e';

    Swal.fire({
        title: 'Konfirmasi',
        text: `Anda yakin ingin ${statusText} izin ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: color,
        confirmButtonText: `Ya, ${statusText}`,
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2.5rem]' }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            
            $.post(`{{ url('guru/student-permits') }}/${id}/approve`, $('#formAction').serialize())
                .done(response => {
                    $('#actionModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-[2.5rem]' } });
                })
                .fail(xhr => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem', customClass: { popup: 'rounded-[2.5rem]' } });
                });
        }
    });
}
</script>
@endpush
