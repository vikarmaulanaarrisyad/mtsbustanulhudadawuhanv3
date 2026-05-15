@extends('layouts.app')

@include('includes.datatable')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative animate__animated animate__fadeIn" style="border-radius: 20px;">
            <div class="card-body p-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h1 class="font-weight-bold mb-1 display-4 text-white">
                            <i class="fas fa-tasks mr-3 animate__animated animate__backInLeft"></i> 
                            Master Kategori Kegiatan
                        </h1>
                        <p class="mb-0 opacity-8 lead font-weight-light text-white">
                            Pengaturan Kategori Kegiatan e-RKAM untuk klasifikasi anggaran dan realisasi program Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-sitemap fa-10x opacity-1 shadow-icon text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1" style="background: rgba(255,255,255,0.1);"></div>
            <div class="bg-circle-2" style="background: rgba(255,255,255,0.05);"></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: TOOLS & STATS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <!-- QUICK STATS -->
        <div class="card shadow-sm border-0 mb-4 premium-card bg-white overflow-hidden">
            <div class="card-body p-4">
                <h5 class="font-weight-bold mb-4 text-dark"><i class="fas fa-project-diagram mr-2 text-primary"></i> Statistik</h5>
                <div class="p-4 bg-soft-primary rounded-lg d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Total Kategori</p>
                        <h2 class="font-weight-bold mb-0 text-primary" id="count-activities">-</h2>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-circle shadow-sm" style="width: 50px; height: 50px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-stream fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- IMPORT TOOL -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-file-import mr-2 text-primary"></i> Import Kategori
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="alert alert-light border-dashed p-4 text-center mb-4" style="border: 2px dashed #007bff; border-radius: 15px; cursor: pointer;" onclick="importActivity()">
                    <i class="fas fa-folder-plus fa-3x text-muted mb-3"></i>
                    <p class="text-sm font-weight-bold mb-1">Klik untuk Import Excel</p>
                    <p class="text-xs text-muted mb-0">Format: kode, nama, kategori</p>
                </div>
                
                <div class="p-3 bg-light rounded-lg mb-4 border-left-primary-thick">
                    <p class="text-xs font-weight-bold mb-2 text-uppercase text-primary">Informasi:</p>
                    <p class="text-xs text-muted mb-0">
                        Kategori ini akan muncul pada saat pemilihan kegiatan di form pengeluaran BOS.
                    </p>
                </div>

                <a href="{{ route('admin.bos.activities.template') }}" class="btn btn-primary btn-block btn-premium py-2 font-weight-bold shadow-sm mb-2">
                    <i class="fas fa-download mr-2"></i> DOWNLOAD TEMPLATE
                </a>

                <button onclick="truncateData()" class="btn btn-outline-danger btn-block btn-premium py-2 font-weight-bold">
                    <i class="fas fa-trash-alt mr-2"></i> KOSONGKAN DATA
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-list mr-2 text-primary"></i> Daftar Kategori Kegiatan
                </h5>
                <button onclick="refreshTable()" class="btn btn-light btn-sm rounded-circle shadow-sm">
                    <i class="fas fa-sync-alt text-primary"></i>
                </button>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="table-activities" class="table table-hover custom-premium-table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>DETAIL KATEGORI KEGIATAN</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL IMPORT ACTIVITY -->
<div class="modal fade" id="modal-import-activity" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Import Kategori Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-import-activity" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <i class="fas fa-info-circle mr-2"></i> Format kolom: <b>kode, nama, kategori</b>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold uppercase text-muted">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control border-2" style="height: auto; padding: 10px; border-radius: 10px;" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold">UNGGAH & PROSES</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* PREMIUM THEME */
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #6610f2 100%) !important; }
    .premium-card { border-radius: 15px; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    
    .bg-circle-1, .bg-circle-2 { position: absolute; border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 400px; height: 400px; top: -150px; right: -100px; }
    .bg-circle-2 { width: 250px; height: 250px; bottom: -80px; left: 5%; }
    
    .bg-soft-primary { background: #e3f2fd; }
    .border-left-primary-thick { border-left: 4px solid #007bff !important; }
    
    .shadow-icon { filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2)); }
    .opacity-1 { opacity: 0.1; }
    
    /* Table Styling */
    .custom-premium-table { border-collapse: separate; border-spacing: 0 12px; }
    .custom-premium-table thead th { border: none; font-size: 0.75rem; text-transform: uppercase; color: #718096; padding: 15px; }
    .custom-premium-table tbody tr { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-radius: 12px; transition: all 0.2s; }
    .custom-premium-table tbody tr:hover { transform: scale(1.01); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .custom-premium-table td { border: none; padding: 20px 15px; vertical-align: middle; }
    .custom-premium-table td:first-child { border-radius: 12px 0 0 12px; }
    .custom-premium-table td:last-child { border-radius: 0 12px 12px 0; }

    .btn-premium { border-radius: 12px; transition: all 0.3s; }
</style>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let tableActivities;
    $(function() {
        tableActivities = $('#table-activities').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('admin.bos.activities.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold'},
                {data: 'name', name: 'name', render: (d, t, r) => `
                    <div class="d-flex align-items-center">
                        <div class="mr-3 p-3 bg-soft-primary rounded-lg text-primary font-weight-bold" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-th-list"></i>
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">${r.category}</div>
                            <div class="text-md font-weight-bold text-dark mb-1"><span class="badge badge-primary mr-2">${r.code}</span> ${d}</div>
                        </div>
                    </div>
                `},
            ],
            drawCallback: function(settings) {
                $('#count-activities').text(this.api().page.info().recordsTotal);
            }
        });
    });

    function refreshTable() { tableActivities.ajax.reload(); }

    function truncateData() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Seluruh data Kategori Kegiatan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.bos.activities.truncate') }}",
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        tableActivities.ajax.reload();
                        Swal.fire('Terhapus!', res.message, 'success');
                    }
                });
            }
        });
    }

    function importActivity() {
        $('#form-import-activity')[0].reset();
        $('#modal-import-activity').modal('show');
    }

    $('#form-import-activity').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading(); } });
        $.ajax({
            url: "{{ route('admin.bos.import_activity') }}",
            type: 'POST',
            data: formData,
            contentType: false, processData: false,
            success: function(res) {
                Swal.close();
                $('#modal-import-activity').modal('hide');
                tableActivities.ajax.reload();
                Swal.fire('Berhasil', res.message, 'success');
            }
        });
    });
</script>
@endpush
@endsection
