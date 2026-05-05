@extends('layouts.app')

@section('title', 'Verifikasi Izin Siswa')
@section('subtitle', 'Absensi & Kepegawaian')

@push('css')
<style>
    /* Premium Themes & Effects */
    .bg-gradient-primary-premium { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important; }
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

    /* Table Styling */
    #permitsTable { border-collapse: separate; border-spacing: 0 12px; }
    #permitsTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #permitsTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.005);
    }
    #permitsTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #permitsTable td:first-child { border-radius: 12px 0 0 12px; }
    #permitsTable td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-primary { background: #f0f4f8; color: #3b82f6; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-danger { background: #ffebee; }
    
    .btn-icon {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary-premium overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-envelope-open-text mr-2 animate__animated animate__fadeInLeft"></i> 
                            Verifikasi Izin Siswa
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola persetujuan pengajuan izin dan sakit siswa secara digital terintegrasi dengan absensi harian.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-clipboard-check fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

            @php
                $pending = \App\Models\StudentPermit::where('status', 'pending')->count();
                $approved = \App\Models\StudentPermit::where('status', 'approved')->count();
                $rejected = \App\Models\StudentPermit::where('status', 'rejected')->count();
            @endphp

            <!-- STATISTICS WIDGETS (GLASSMORPHISM STYLE) -->
            <div class="row mb-4 animate__animated animate__fadeInUp">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Menunggu Verifikasi</p>
                                    <h2 class="font-weight-bold mb-0 text-warning counter-value" id="stat_pending">{{ $pending }}</h2>
                                </div>
                                <div class="icon-shape bg-soft-warning rounded-circle p-3">
                                    <i class="fas fa-clock text-warning fa-lg"></i>
                                </div>
                            </div>
                            <div class="progress progress-xs mt-3 bg-light">
                                <div class="progress-bar bg-warning" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Disetujui</p>
                                    <h2 class="font-weight-bold mb-0 text-success counter-value" id="stat_approved">{{ $approved }}</h2>
                                </div>
                                <div class="icon-shape bg-soft-success rounded-circle p-3">
                                    <i class="fas fa-check text-success fa-lg"></i>
                                </div>
                            </div>
                            <div class="progress progress-xs mt-3 bg-light">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Ditolak</p>
                                    <h2 class="font-weight-bold mb-0 text-danger counter-value" id="stat_rejected">{{ $rejected }}</h2>
                                </div>
                                <div class="icon-shape bg-soft-danger rounded-circle p-3">
                                    <i class="fas fa-times text-danger fa-lg"></i>
                                </div>
                            </div>
                            <div class="progress progress-xs mt-3 bg-light">
                                <div class="progress-bar bg-danger" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAIN DATA TABLE -->
            <div class="row animate__animated animate__fadeInUp">
                <div class="col-12">
                    <div class="card shadow-sm border-0 premium-card">
                        <div class="card-header bg-white py-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 font-weight-bold text-dark"><i class="fas fa-list text-primary mr-2"></i> Daftar Pengajuan Izin</h4>
                                    <p class="text-muted text-sm mb-0">Kelola dan verifikasi pengajuan dari siswa.</p>
                                </div>
                                <div class="card-tools w-25">
                                    <select id="statusFilter" class="form-control" style="border-radius: 8px;">
                                        <option value="">Semua Status</option>
                                        <option value="pending" selected>Menunggu Verifikasi</option>
                                        <option value="approved">Disetujui</option>
                                        <option value="rejected">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table id="permitsTable" class="table table-hover align-middle mb-0" style="width:100%">
                                    <thead class="bg-light-primary text-uppercase">
                                        <tr>
                                            <th width="5%" class="text-center py-3">No</th>
                                            <th class="py-3">Nama Siswa</th>
                                            <th class="py-3">Kelas</th>
                                            <th class="py-3">Jenis</th>
                                            <th class="py-3">Rentang Tanggal</th>
                                            <th class="py-3">Alasan</th>
                                            <th class="text-center py-3">Status</th>
                                            <th width="12%" class="text-center py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>

<!-- Modal Approval -->
<div class="modal fade" id="modalApproval" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-white" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); border: none;">
                <h5 class="modal-title font-weight-bold text-white" id="modalTitle">Konfirmasi Izin</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formApproval">
                @csrf
                <input type="hidden" name="permit_id" id="permit_id">
                <input type="hidden" name="status" id="permit_status">
                <div class="modal-body p-4">
                    <p id="confirmMessage" class="mb-3"></p>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-sm">Catatan (Opsional)</label>
                        <textarea name="note" id="permit_note" rows="3" class="form-control" style="border-radius: 10px;" placeholder="Beri catatan untuk siswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border: none;">
                    <button type="button" class="btn btn-secondary" style="border-radius: 10px;" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitApproval" style="border-radius: 10px; border: none;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('includes.datatable')

@push('scripts')
<script>
    let table;
    $(document).ready(function() {
        $('#modalApproval').appendTo("body");
        
        table = $('#permitsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('student.permits.data') }}",
                data: function(d) {
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'student_name', name: 'student.nama_lengkap' },
                { data: 'class_name', name: 'student.classGroup.name' },
                { data: 'type', name: 'type' },
                { data: 'date_range', name: 'start_date' },
                { data: 'reason', name: 'reason' },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        $('#statusFilter').change(function() {
            table.ajax.reload();
        });

        $('#formApproval').submit(function(e) {
            e.preventDefault();
            let id = $('#permit_id').val();
            let status = $('#permit_status').val();
            let note = $('#permit_note').val();

            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: `/admin/student-permits/${id}/approve`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    note: note
                },
                success: function(response) {
                    $('#modalApproval').modal('hide');
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        table.ajax.reload();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                    }
                },
                error: function(xhr) {
                    $('#modalApproval').modal('hide');
                    let msg = 'Terjadi kesalahan.';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        });
    });

    function approvePermit(id, status) {
        $('#permit_id').val(id);
        $('#permit_status').val(status);
        $('#permit_note').val('');
        
        if (status === 'approved') {
            $('#modalTitle').html('<i class="fas fa-check-circle mr-2"></i>Setujui Izin');
            $('.modal-header').css('background', 'linear-gradient(135deg, #10b981, #059669)');
            $('#btnSubmitApproval').css('background', 'linear-gradient(135deg, #10b981, #059669)');
            $('#confirmMessage').html('Apakah Anda yakin ingin <b>menyetujui</b> izin ini? Absensi akan otomatis tercatat sesuai rentang tanggal.');
        } else {
            $('#modalTitle').html('<i class="fas fa-times-circle mr-2"></i>Tolak Izin');
            $('.modal-header').css('background', 'linear-gradient(135deg, #ef4444, #dc2626)');
            $('#btnSubmitApproval').css('background', 'linear-gradient(135deg, #ef4444, #dc2626)');
            $('#confirmMessage').html('Apakah Anda yakin ingin <b>menolak</b> izin ini?');
        }

        $('#modalApproval').modal('show');
    }
</script>
@endpush
