@extends('layouts.app')

@include('includes.datatable')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-dark overflow-hidden position-relative animate__animated animate__fadeIn" style="border-radius: 20px;">
            <div class="card-body p-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h1 class="font-weight-bold mb-1 display-4">
                            <i class="fas fa-layer-group mr-3 animate__animated animate__backInLeft"></i> 
                            Master Struktur RKAM
                        </h1>
                        <p class="mb-0 opacity-8 lead font-weight-light">
                            Kelola hierarki Standar Nasional Pendidikan (SNP), Kegiatan, dan Sub-Kegiatan sesuai standar e-RKAM Nasional.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-sitemap fa-10x opacity-1 shadow-icon text-white"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: TOOLS & STATS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <!-- QUICK STATS -->
        <div class="card shadow-sm border-0 mb-4 premium-card bg-white overflow-hidden">
            <div class="card-body p-4">
                <h5 class="font-weight-bold mb-4 text-dark"><i class="fas fa-chart-line mr-2 text-primary"></i> Ringkasan Data</h5>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-soft-primary rounded-lg text-center h-100">
                            <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Total SNP</p>
                            <h3 class="font-weight-bold mb-0 text-primary" id="count-snp">-</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-soft-info rounded-lg text-center h-100">
                            <p class="text-xs font-weight-bold text-uppercase text-muted mb-1">Sub-Kegiatan</p>
                            <h3 class="font-weight-bold mb-0 text-info" id="count-sub">-</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IMPORT TOOL -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-file-import mr-2 text-success"></i> Import Data RKAM
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="alert alert-light border-dashed p-4 text-center mb-4" style="border: 2px dashed #dee2e6; border-radius: 15px; cursor: pointer;" onclick="importRkam()">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                    <p class="text-sm font-weight-bold mb-1">Klik untuk Unggah Excel</p>
                    <p class="text-xs text-muted mb-0">Format: .xlsx, .xls, .csv</p>
                </div>
                
                <div class="p-3 bg-light rounded-lg mb-4">
                    <p class="text-xs font-weight-bold mb-2 text-uppercase text-muted">Instruksi:</p>
                    <ul class="text-xs text-muted pl-3 mb-0">
                        <li>Pastikan kolom sesuai template e-RKAM.</li>
                        <li>Sistem akan melakukan pembaharuan data jika kode sudah ada.</li>
                        <li>Gunakan template resmi untuk menghindari error.</li>
                    </ul>
                </div>

                <a href="{{ route('admin.bos.rkam.template') }}" class="btn btn-outline-primary btn-block btn-premium py-2 font-weight-bold mb-2">
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
                    <i class="fas fa-table mr-2 text-primary"></i> Daftar Struktur RKAM
                </h5>
                <button onclick="refreshTable()" class="btn btn-light btn-sm rounded-circle shadow-sm">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="table-rkam" class="table table-hover custom-premium-table" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>STRUKTUR RKAM (SNP - KEGIATAN - SUB)</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL IMPORT RKAM -->
<div class="modal fade" id="modal-import-rkam" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Import Master RKAM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-import-rkam" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <i class="fas fa-info-circle mr-2"></i> Format kolom: <b>kode_snp, snp, kode_kegiatan, nama_kegiatan, kode_sub_kegiatan, sub_kegiatan</b>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold uppercase text-muted">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control border-2" style="height: auto; padding: 10px; border-radius: 10px;" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-dark btn-block rounded-pill py-2 font-weight-bold">UNGGAH & PROSES</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* PREMIUM THEME OVERRIDES */
    .bg-gradient-dark { background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%) !important; }
    .premium-card { border-radius: 15px; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 400px; height: 400px; top: -150px; right: -100px; }
    .bg-circle-2 { width: 250px; height: 250px; bottom: -80px; left: 5%; }
    
    .bg-soft-primary { background: #ebf4ff; }
    .bg-soft-info { background: #e0f7fa; }
    .bg-soft-success { background: #f0fdf4; }
    
    .shadow-icon { filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3)); }
    .opacity-1 { opacity: 0.1; }
    
    /* Table Styling */
    .custom-premium-table { border-collapse: separate; border-spacing: 0 10px; }
    .custom-premium-table thead th { border: none; font-size: 0.75rem; text-transform: uppercase; color: #718096; padding: 15px; }
    .custom-premium-table tbody tr { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border-radius: 10px; transition: all 0.2s; }
    .custom-premium-table tbody tr:hover { transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .custom-premium-table td { border: none; padding: 20px 15px; vertical-align: middle; }
    .custom-premium-table td:first-child { border-radius: 10px 0 0 10px; }
    .custom-premium-table td:last-child { border-radius: 0 10px 10px 0; }

    .btn-premium { border-radius: 12px; transition: all 0.3s; }
    .btn-premium:hover { transform: scale(1.02); }
</style>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let tableRkam;
    $(function() {
        tableRkam = $('#table-rkam').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('admin.bos.rkam.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold'},
                {data: 'sub_kegiatan', name: 'sub_kegiatan', render: (d, t, r) => `
                    <div class="d-flex align-items-center">
                        <div class="mr-3 p-3 bg-soft-primary rounded-circle text-primary font-weight-bold" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center;">
                            ${r.kode_snp}
                        </div>
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">${r.snp}</div>
                            <div class="text-sm font-weight-bold text-dark mb-1"><i class="fas fa-arrow-right mr-1 text-xs opacity-5"></i> ${r.nama_kegiatan}</div>
                            <div class="text-md font-weight-bold text-primary"><span class="badge badge-primary mr-2">${r.kode_sub_kegiatan}</span> ${d}</div>
                        </div>
                    </div>
                `},
            ],
            drawCallback: function(settings) {
                let api = this.api();
                let recordsTotal = api.page.info().recordsTotal;
                $('#count-sub').text(recordsTotal);
                
                // Estimate SNP count from data if possible, or just use a dummy for now
                // Ideally this should come from a separate AJAX call
                $.get("{{ route('admin.bos.search_rkam') }}", {count_only: true}, function(res) {
                     // Assuming searchRkam might be updated or we use a separate call
                });
            }
        });
    });

    function refreshTable() {
        tableRkam.ajax.reload();
    }

    function truncateData() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Seluruh data Master RKAM akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.bos.rkam.truncate') }}",
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        tableRkam.ajax.reload();
                        Swal.fire('Terhapus!', res.message, 'success');
                    }
                });
            }
        });
    }

    function importRkam() {
        $('#form-import-rkam')[0].reset();
        $('#modal-import-rkam').modal('show');
    }

    $('#form-import-rkam').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        Swal.fire({
            title: 'Memproses Data...',
            text: 'Harap tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        $.ajax({
            url: "{{ route('admin.bos.import_rkam') }}",
            type: 'POST',
            data: formData,
            contentType: false, processData: false,
            success: function(res) {
                Swal.close();
                $('#modal-import-rkam').modal('hide');
                tableRkam.ajax.reload();
                Swal.fire('Berhasil', res.message, 'success');
            },
            error: function() {
                Swal.fire('Gagal', 'Terjadi kesalahan saat import', 'error');
            }
        });
    });
</script>
@endpush
@endsection
