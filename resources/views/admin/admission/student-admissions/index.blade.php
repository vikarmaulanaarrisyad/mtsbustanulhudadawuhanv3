@extends($layout)

@section('title', 'Pengaturan Penerimaan Peserta Didik Baru')
@section('subtitle', 'Pengaturan PPDB')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-cog mr-2 animate__animated animate__rotateIn"></i> 
                            Konfigurasi Periode PPDB
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur rentang waktu pendaftaran, jadwal pengumuman, dan nomor surat resmi untuk periode penerimaan siswa.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tools fa-7x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

<!-- DYNAMIC STATUS OVERVIEW -->
<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm premium-card h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo rounded-xl d-flex align-items-center justify-content-center mr-3 shadow-sm border border-indigo-100">
                        <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-0">Tahun Aktif</p>
                        <h4 class="font-weight-bold text-dark mb-0">PPDB {{ $activeYear ?? '---' }}</h4>
                    </div>
                </div>
                <div class="progress progress-xs mb-0 bg-indigo-50" style="height: 4px;">
                    <div class="progress-bar bg-indigo" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-3 mt-md-0">
        <div class="card border-0 shadow-sm premium-card h-100 bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="w-12 h-12 {{ ($isActive ?? false) ? 'bg-emerald-50 text-emerald' : 'bg-rose-50 text-danger' }} rounded-xl d-flex align-items-center justify-content-center mr-3 shadow-sm border {{ ($isActive ?? false) ? 'border-emerald-100' : 'border-rose-100' }}">
                        <i class="fas {{ ($isActive ?? false) ? 'fa-lock-open' : 'fa-lock' }} fa-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-0">Status Sistem</p>
                        <h4 class="font-weight-bold {{ ($isActive ?? false) ? 'text-emerald' : 'text-danger' }} mb-0">
                            {{ ($isActive ?? false) ? 'PENDAFTARAN BUKA' : 'PENDAFTARAN TUTUP' }}
                        </h4>
                    </div>
                </div>
                <div class="progress progress-xs mb-0 {{ ($isActive ?? false) ? 'bg-emerald-50' : 'bg-rose-50' }}" style="height: 4px;">
                    <div class="progress-bar {{ ($isActive ?? false) ? 'bg-emerald' : 'bg-danger' }}" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-3 mt-md-0">
        <div class="card border-0 shadow-sm premium-card h-100 bg-white">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <p class="text-[9px] font-black text-muted uppercase tracking-widest mb-2">Panduan Cepat</p>
                <div class="d-flex justify-content-around">
                    <div class="text-center">
                        <span class="d-block text-xs font-weight-bold text-indigo"><i class="fas fa-circle mr-1 text-[8px]"></i> Edit</span>
                        <small class="text-muted">Ubah Jadwal</small>
                    </div>
                    <div class="text-center">
                        <span class="d-block text-xs font-weight-bold text-success"><i class="fas fa-circle mr-1 text-[8px]"></i> Cetak</span>
                        <small class="text-muted">Laporan BA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-history mr-2 text-indigo"></i> Log Master Periode PPDB
                </h5>
                @if ($studentAdmissions == 0)
                    <button id="btn-tambah-data" onclick="addForm(`{{ route('student-admissions.store') }}`)"
                        class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-indigo-light animate__animated animate__pulse animate__infinite">
                        <i class="fas fa-rocket mr-2"></i> INISIASI PERIODE BARU
                    </button>
                @endif
            </div>

            <div class="card-body p-4">
                <div class="alert alert-soft-indigo rounded-20 border-0 shadow-sm mb-4 p-3 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-indigo rounded-circle d-flex align-items-center justify-content-center mr-3 text-white">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="small">
                            Pastikan rentang tanggal pendaftaran tidak bentrok dengan jadwal pengumuman. Nomor BA dan SK akan digunakan pada cetak dokumen kolektif.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="admissionTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="5%" class="text-center py-3">NO</th>
                                <th><i class="fas fa-calendar-alt mr-1"></i> MULAI PPDB</th>
                                <th><i class="fas fa-calendar-check mr-1"></i> SELESAI PPDB</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">TAHUN</th>
                                <th><i class="fas fa-bullhorn mr-1"></i> PENGUMUMAN</th>
                                <th>NO. BA / SK</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.admission.student-admissions.form')

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4338ca 0%, #1e1b4b 100%) !important; }
    .bg-shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; top: -100px; right: -50px; }
    .bg-shape-2 { position: absolute; width: 150px; height: 150px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; bottom: -30px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px; }
    .alert-soft-indigo { background: #fcfaff; color: #4338ca; border-left: 5px solid #4f46e5; }
    
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }

    /* Table Styling */
    #admissionTable { border-collapse: separate; border-spacing: 0 10px; }
    #admissionTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #admissionTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #admissionTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #admissionTable td:first-child { border-radius: 12px 0 0 12px; }
    #admissionTable td:last-child { border-radius: 0 12px 12px 0; }

    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }
</style>
@endsection

@include('includes.datatable')
@include('includes.datepicker')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#admissionTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari periode...", search: "" },
            ajax: { url: '{{ route('student-admissions.data') }}' },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'admission_start_date', render: (data) => '<span class="font-weight-bold text-dark"><i class="far fa-calendar-alt mr-2 text-indigo"></i>' + data + '</span>' },
                { data: 'admission_end_date', render: (data) => '<span class="font-weight-bold text-dark"><i class="far fa-calendar-check mr-2 text-danger"></i>' + data + '</span>' },
                { data: 'admission_status', className: 'text-center' },
                { data: 'admission_year', className: 'text-center font-weight-bold' },
                { 
                    data: 'announcement_start_date', 
                    render: (data, type, row) => '<div class="small font-weight-bold text-indigo"><i class="fas fa-bullhorn mr-1"></i> ' + data + '</div><div class="small text-muted">s/d ' + row.announcement_end_date + '</div>'
                },
                { 
                    data: 'ba_letter_number', 
                    render: (data, type, row) => '<div class="text-xs font-weight-bold text-muted">BA: ' + (data || '---') + '</div><div class="text-xs font-weight-bold text-muted">SK: ' + (row.sk_letter_number || '---') + '</div>'
                },
                { data: 'action', className: 'text-center' },
            ]
        });
    });

    function addForm(url, title = 'Inisiasi Periode PPDB Baru') {
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} form`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} form`);
    }

    function editForm(url, title = 'Konfigurasi Periode PPDB') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close(); $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} form`);
            
            // Format dates for native HTML5 date inputs (must be YYYY-MM-DD)
            const dates = ['admission_start_date', 'admission_end_date', 'announcement_start_date', 'announcement_end_date'];
            dates.forEach(field => {
                if(res.data[field]) {
                    res.data[field] = moment(res.data[field]).format('YYYY-MM-DD');
                }
            });

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
</script>

<script>
    $(function() {
        // Fix Datepicker Initialization for Modal
        // Initialize other components if any
    });
</script>

<style>
    /* Fix Datepicker Z-Index inside Modal */
    .bootstrap-datetimepicker-widget { z-index: 9999 !important; }
</style>
@endpush
