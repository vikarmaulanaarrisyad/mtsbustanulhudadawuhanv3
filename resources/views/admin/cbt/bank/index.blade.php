@extends('layouts.app')
@section('title', 'Manajemen Bank Soal CBT')
@section('subtitle', 'Computer Based Test')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-database mr-2 animate__animated animate__fadeInLeft"></i> 
                            Bank Soal Madrasah Digital
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pusat penyimpanan dan pengelolaan butir soal ujian secara terstruktur berdasarkan Mata Pelajaran dan Tingkat Kelas.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-layer-group fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Bank Soal</p>
                        <h2 class="font-weight-bold mb-0 text-primary counter-value" id="stat_total">{{\App\Models\CbtBank::count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-archive text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #17a2b8 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Butir Soal</p>
                        <h2 class="font-weight-bold mb-0 text-info counter-value" id="stat_questions">{{\App\Models\CbtQuestion::count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-info rounded-circle p-3">
                        <i class="fas fa-list-ol text-info fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Mata Pelajaran Aktif</p>
                        <h2 class="font-weight-bold mb-0 text-success counter-value" id="stat_subjects">{{\App\Models\CbtBank::distinct('subject_id')->count()}}</h2>
                    </div>
                    <div class="icon-shape bg-soft-success rounded-circle p-3">
                        <i class="fas fa-book-open text-success fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FORM BANK SOAL -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-primary mb-0" id="formTitle">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Bank Soal
                </h5>
                <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="btnCancelEdit" onclick="resetForm()">Batal Edit</button>
            </div>
            <div class="card-body pt-0">
                <form id="bankForm">
                    @csrf
                    <input type="hidden" name="id" id="bank_id">
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">NAMA BANK SOAL</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Misal: Bank Soal PAI Kelas 7">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted">MATA PELAJARAN</label>
                        <select name="subject_id" id="subject_id" class="form-control select2" style="width: 100%" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach(\App\Models\Subject::all() as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted">TINGKAT KELAS</label>
                        <select name="class_level" id="class_level" class="form-control select2" style="width: 100%" required>
                            <option value="">-- Pilih Tingkat Kelas --</option>
                            <option value="7">Kelas 7</option>
                            <option value="8">Kelas 8</option>
                            <option value="9">Kelas 9</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block shadow-lg font-weight-bold py-2 btn-premium" id="btnSubmit">
                        <i class="fas fa-save mr-2"></i> SIMPAN BANK SOAL
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Bank Soal</h4>
                        <p class="text-muted text-sm mb-0">Kelola dan input butir soal ke dalam bank soal.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="bankTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Nama Bank Soal</th>
                                <th width="20%">Mata Pelajaran</th>
                                <th width="15%" class="text-center">Kelas</th>
                                <th width="15%" class="text-center">Jml Soal</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-primary-thick { border-left: 5px solid #007bff !important; }

    /* Table Styling */
    #bankTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #bankTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #bankTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    #bankTable td { border: none; padding: 1rem 0.75rem; vertical-align: middle; }
    #bankTable td:first-child { border-radius: 12px 0 0 12px; }
    #bankTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-info { background: #e0f7fa; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@push('scripts')
@include('includes.datatable')
@include('includes.select2')

<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'classic' });

        window.table = $('#bankTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari bank soal...", search: "" },
            ajax: '{{ route('admin.cbt.bank.data') }}',
            columns: [
                {data: 'id', render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }},
                {data: 'name', render: function(data, type, row) {
                    return `<strong class="text-primary" style="font-size:15px;">${data}</strong>`;
                }},
                {data: 'subject.name', defaultContent: '-', render: function(data) {
                    return `<span class="badge badge-light border border-secondary text-dark"><i class="fas fa-book mr-1"></i> ${data || '-'}</span>`;
                }},
                {data: 'class_level', className: 'text-center', render: function(data) {
                    return `<span class="badge badge-info px-3 py-1 rounded-pill">Kelas ${data || '-'}</span>`;
                }},
                {data: 'questions_count', className: 'text-center', render: function(data) {
                    return `<span class="font-weight-bold" style="font-size:18px;">${data || 0}</span> <span class="text-muted text-xs d-block">Soal</span>`;
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]
        });

        $('#bankForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#bank_id').val();
            let url = id ? `/admin/cbt/bank/${id}` : `{{ route('admin.cbt.bank.store') }}`;
            let type = id ? 'PUT' : 'POST';

            $('#btnSubmit').html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...').prop('disabled', true);

            $.ajax({
                url: url, type: type, data: $(this).serialize(),
                success: function(res) {
                    resetForm();
                    table.ajax.reload();
                    Swal.fire('Sukses', res.message, 'success');
                },
                error: function(err) {
                    Swal.fire('Gagal', 'Terjadi kesalahan pada sistem.', 'error');
                },
                complete: function() {
                    $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> SIMPAN BANK SOAL').prop('disabled', false);
                }
            });
        });
    });

    function resetForm() {
        $('#bankForm')[0].reset();
        $('#bank_id').val('');
        $('#subject_id').val('').trigger('change');
        $('#class_level').val('').trigger('change');
        $('#formTitle').html('<i class="fas fa-plus-circle mr-2"></i> Buat Bank Soal');
        $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> SIMPAN BANK SOAL').removeClass('btn-warning').addClass('btn-primary');
        $('#btnCancelEdit').addClass('d-none');
    }

    function editBank(id) {
        Swal.fire({ title: 'Memuat Data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        $.get(`/admin/cbt/bank/${id}/edit`, function(data) {
            $('#bank_id').val(data.id);
            $('#name').val(data.name);
            $('#subject_id').val(data.subject_id).trigger('change');
            $('#class_level').val(data.class_level).trigger('change');
            
            $('#formTitle').html('<i class="fas fa-edit mr-2"></i> Edit Bank Soal');
            $('#btnSubmit').html('<i class="fas fa-save mr-2"></i> UPDATE BANK SOAL').removeClass('btn-primary').addClass('btn-warning');
            $('#btnCancelEdit').removeClass('d-none');
            
            Swal.close();
        }).fail(function() {
            Swal.fire('Error', 'Gagal memuat data bank', 'error');
        });
    }

    function deleteBank(id) {
        Swal.fire({
            title: 'Hapus Bank Soal?', text: "Semua soal yang ada di dalamnya akan ikut terhapus permanen!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, hapus permanen!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/cbt/bank/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                    success: function(res) { window.table.ajax.reload(); Swal.fire('Terhapus!', res.message, 'success'); }
                });
            }
        });
    }
</script>
@endpush
