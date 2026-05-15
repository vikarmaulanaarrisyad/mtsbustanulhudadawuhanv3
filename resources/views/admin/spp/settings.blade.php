@extends($layout)

@section('title', 'Pengaturan SPP')
@section('subtitle', 'Keuangan')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-cog mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pengaturan Tarif SPP
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan besaran iuran bulanan (SPP) berdasarkan tingkat kelas dan tahun pelajaran secara spesifik.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-wallet fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT SIDEBAR: FORM -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-primary-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0" id="form-title">
                    <i class="fas fa-plus-circle text-primary mr-2"></i> Tambah Tarif Baru
                </h5>
            </div>
            <div class="card-body pt-0">
                <form id="form-setting">
                    @csrf
                    <input type="hidden" name="id" id="setting-id">
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-control rounded-pill border-2" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Tingkat Kelas</label>
                        <select name="class_level" id="class_level" class="form-control rounded-pill border-2" required>
                            <optgroup label="MI (Ibtidaiyah)">
                                @for($i=1; $i<=6; $i++)
                                    <option value="{{ $i }}">Kelas {{ $i }}</option>
                                @endfor
                            </optgroup>
                            <optgroup label="MTs (Tsanawiyah)">
                                @for($i=7; $i<=9; $i++)
                                    <option value="{{ $i }}">Kelas {{ $i }}</option>
                                @endfor
                            </optgroup>
                            <optgroup label="MA (Aliyah)">
                                @for($i=10; $i<=12; $i++)
                                    <option value="{{ $i }}">Kelas {{ $i }}</option>
                                @endfor
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Jumlah SPP (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-2" style="border-radius: 50px 0 0 50px;">Rp</span>
                            </div>
                            <input type="text" name="amount_display" id="amount_display" class="form-control border-2" style="border-radius: 0 50px 50px 0;" placeholder="Contoh: 150.000" required onkeyup="formatRupiah(this)">
                            <input type="hidden" name="amount" id="amount">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Keterangan (Opsional)</label>
                        <textarea name="description" id="description" class="form-control border-2" style="border-radius:15px;" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-block shadow-primary font-weight-bold py-2 btn-premium" id="btn-save">
                            <i class="fas fa-save mr-2"></i> SIMPAN TARIF
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-light btn-block font-weight-bold py-2 btn-premium d-none" id="btn-cancel">
                            BATAL
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- HELPER CARD -->
        <div class="card shadow-sm border-0 premium-card bg-light">
            <div class="card-body p-4">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-info-circle text-info mr-2"></i> Tips Pengaturan</h6>
                <p class="text-sm text-muted mb-0">
                    Pastikan tarif SPP sudah diatur sebelum melakukan <b>Generate Tagihan</b> bulanan. Sistem akan mengambil tarif yang sesuai dengan tingkat kelas siswa.
                </p>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Daftar Tarif SPP</h4>
                        <p class="text-muted text-sm mb-0">Riwayat pengaturan tarif iuran sekolah.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="table-settings" style="width:100%">
                        <thead class="bg-light text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">#</th>
                                <th>Tahun Pelajaran</th>
                                <th>Tingkat</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th width="100px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-primary-thick { border-left: 5px solid #4e73df !important; }

    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .shadow-primary { box-shadow: 0 4px 15px rgba(78,115,223,0.4); }

    #table-settings { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #table-settings thead th { 
        font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; color: #507b8f; 
        border: none; padding: 12px 15px;
    }
    #table-settings tbody tr { 
        background: #fff; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.03); 
        transition: all 0.2s ease;
    }
    #table-settings tbody tr:hover { transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    #table-settings td { 
        border: none; padding: 1.2rem 1rem; vertical-align: middle; 
        background: #fff;
    }
    #table-settings td:first-child { border-radius: 10px 0 0 10px; }
    #table-settings td:last-child { border-radius: 0 10px 10px 0; }

    /* Custom DataTables Styling */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 50px; padding: 8px 20px; border: 2px solid #f1f3f9;
        width: 250px !important; transition: all 0.3s;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.1); }
    .dataTables_wrapper .dataTables_length select { border-radius: 50px; padding: 5px 15px; border: 2px solid #f1f3f9; }
    .page-item.active .page-link { background-color: #4e73df; border-color: #4e73df; border-radius: 10px; }
    .page-link { border-radius: 10px; margin: 0 3px; border: none; color: #4e73df; font-weight: 600; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table = $('#table-settings').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.spp.settings.data') }}",
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari data...",
            lengthMenu: "_MENU_",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        },
        dom: "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
            {data: 'academic_year_name', name: 'academic_year_name'},
            {data: 'class_level', name: 'class_level', render: d => '<span class="badge badge-light px-3 py-2 border">Kelas ' + d + '</span>'},
            {data: 'amount', name: 'amount', className: 'font-weight-bold text-primary'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
        ]
    });

    $('#form-setting').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#btn-save');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...');

        $.post("{{ route('admin.spp.settings.store') }}", $(this).serialize(), function(res) {
            table.ajax.reload();
            resetForm();
            Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 1500, showConfirmButton: false });
        }).fail(err => {
            Swal.fire('Error', err.responseJSON.message || 'Terjadi kesalahan', 'error');
        }).always(() => {
            btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN TARIF');
        });
    });

    function editSetting(url) {
        $.get(url, function(res) {
            $('#setting-id').val(res.data.id);
            $('#academic_year_id').val(res.data.academic_year_id);
            $('#class_level').val(res.data.class_level);
            
            // Set amount and display
            $('#amount').val(res.data.amount);
            $('#amount_display').val(new Intl.NumberFormat('id-ID').format(res.data.amount));
            
            $('#description').val(res.data.description);
            
            $('#form-title').html('<i class="fas fa-edit text-warning mr-2"></i> Edit Tarif SPP');
            $('#btn-save').removeClass('btn-primary').addClass('btn-warning').html('<i class="fas fa-save mr-2"></i> UPDATE TARIF');
            $('#btn-cancel').removeClass('d-none');
            
            // Scroll to form
            $('html, body').animate({ scrollTop: $('#form-setting').offset().top - 100 }, 500);
        });
    }

    function resetForm() {
        $('#form-setting')[0].reset();
        $('#setting-id').val('');
        $('#amount').val('');
        $('#amount_display').val('');
        $('#form-title').html('<i class="fas fa-plus-circle text-primary mr-2"></i> Tambah Tarif Baru');
        $('#btn-save').removeClass('btn-warning').addClass('btn-primary').html('<i class="fas fa-save mr-2"></i> SIMPAN TARIF');
        $('#btn-cancel').addClass('d-none');
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
            $('#amount').val(value);
        } else {
            input.value = '';
            $('#amount').val('');
        }
    }

    function deleteSetting(url) {
        Swal.fire({
            title: 'Hapus Tarif?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(res) {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', res.message, 'success');
                    }
                });
            }
        });
    }
</script>
@endpush
