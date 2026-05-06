@extends($layout)

@section('title', 'Data Guru & Staf')

@section('breadcrumb')
    <li class="breadcrumb-item active">Guru & Staf</li>
@endsection

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-tie mr-2 animate__animated animate__fadeInLeft"></i> 
                            Direktori Guru & Tenaga Kependidikan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Kelola profil profesional, kualifikasi, dan data kepegawaian tenaga pendidik Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chalkboard-teacher fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATS SUMMARY CARDS -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="border-radius: 12px; border-left: 5px solid #2d3436!important">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-3 rounded-circle mr-3">
                        <i class="fas fa-users fa-lg text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small uppercase font-weight-bold">Total Staf</h6>
                        <h3 class="font-weight-bold mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="border-radius: 12px; border-left: 5px solid #0984e3!important">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-blue-soft p-3 rounded-circle mr-3" style="background: #e1f5fe;">
                        <i class="fas fa-male fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small uppercase font-weight-bold">Laki-Laki</h6>
                        <h3 class="font-weight-bold mb-0 text-primary">{{ $stats['laki'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="border-radius: 12px; border-left: 5px solid #e84393!important">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-pink-soft p-3 rounded-circle mr-3" style="background: #fce4ec;">
                        <i class="fas fa-female fa-lg text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small uppercase font-weight-bold">Perempuan</h6>
                        <h3 class="font-weight-bold mb-0 text-danger">{{ $stats['perempuan'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="border-radius: 12px; border-left: 5px solid #6c5ce7!important">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-purple-soft p-3 rounded-circle mr-3" style="background: #f3e5f5;">
                        <i class="fas fa-user-shield fa-lg text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small uppercase font-weight-bold">TU & Staff</h6>
                        <h3 class="font-weight-bold mb-0 text-info">{{ $stats['tu'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KEPALA MADRASAH QUICK INFO -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fffbeb; border: 1px solid #fde68a;">
            <div class="card-body py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-lg mr-3">
                        <img src="{{ $stats['kepala_madrasah'] && $stats['kepala_madrasah']->user && $stats['kepala_madrasah']->user->profile_photo_url ? $stats['kepala_madrasah']->user->profile_photo_url : 'https://ui-avatars.com/api/?name=Kepala+Madrasah&background=d97706&color=fff' }}" 
                             class="rounded-circle" width="50" height="50" style="object-fit: cover; border: 2px solid #d97706;">
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bold text-dark">Kepala Madrasah (Official Signer)</h6>
                        <p class="mb-0 text-sm text-dark opacity-8">
                            @if($stats['kepala_madrasah'])
                                <span class="font-weight-bold">{{ $stats['kepala_madrasah']->name }}</span> 
                                <span class="mx-1">|</span> NIP. {{ $stats['kepala_madrasah']->nip ?? '-' }}
                            @else
                                <span class="text-danger font-italic">Belum ada guru dengan jabatan 'Kepala Madrasah'</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="badge badge-warning px-3 py-2 text-xs font-weight-bold" style="border-radius: 50px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                        <i class="fas fa-check-circle mr-1"></i> TTD AKTIF
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Data Guru</h4>
                        <p class="text-muted text-sm mb-0">Manajemen seluruh staf pengajar Madrasah</p>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="gap:.5rem!important">
                        <a href="{{ route('teachers.download_template') }}" class="btn btn-outline-secondary shadow-sm font-weight-bold px-3 btn-premium">
                            <i class="fas fa-file-excel mr-1"></i> Template
                        </a>
                        <button onclick="$(&#39;#importModal&#39;).modal(&#39;show&#39;)" class="btn btn-success shadow-sm font-weight-bold px-3 btn-premium">
                            <i class="fas fa-file-import mr-1"></i> Import Excel
                        </button>
                        <button onclick="addForm(`{{ route('teachers.store') }}`)" class="btn btn-info shadow-sm font-weight-bold px-4 btn-premium">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH GURU
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="teacherTable" style="width:100%">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>Nama Lengkap</th>
                                <th>NIP / Identitas</th>
                                <th>Jabatan / Tugas</th>
                                <th>Pangkat / Golongan</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Table Styling */
    #teacherTable { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #teacherTable tbody tr { background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #teacherTable tbody tr:hover { transform: scale(1.005); box-shadow: 0 6px 15px rgba(0,0,0,0.06); background: #f8fbff; }
    #teacherTable td { border: none; padding: 1.5rem 0.75rem; vertical-align: middle; }
    #teacherTable td:first-child { border-radius: 12px 0 0 12px; font-weight: bold; color: #17a2b8; }
    #teacherTable td:last-child { border-radius: 0 12px 12px 0; }

    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>

@include('admin.teachers.form')

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('teachers.import_excel') }}" method="post" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-header bg-gradient-success text-white border-0 py-3" style="background: linear-gradient(135deg, #28a745, #1a7432)!important">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-file-import mr-2"></i> Import Data Guru dari Excel
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-10 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Gunakan file Excel sesuai format template. 
                        <a href="{{ route('teachers.download_template') }}" class="font-weight-bold">
                            <i class="fas fa-download mr-1"></i> Download Template
                        </a>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-muted text-xs uppercase">Pilih File Excel (.xlsx / .xls)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="importFile">Pilih file...</label>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 rounded-10 mb-0 small">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Catatan:</strong> Data guru yang sudah ada tidak akan ditimpa. Kolom <code>email</code> dan <code>username</code> opsional — jika tidak diisi, akun login tidak dibuat.
                    </div>
                </div>
                <div class="modal-footer border-0 p-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" id="importBtn" class="btn btn-success rounded-pill px-5 font-weight-bold">
                        <i class="fas fa-upload mr-2"></i> Import Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    let modal = '#modal-form';
    let button = '#submitBtn';

    $(function() {
        table = $('#teacherTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari guru...", search: "" },
            ajax: { url: '{{ route("teachers.data") }}' },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { 
                    data: 'name',
                    render: function(data) {
                        return '<div class="d-flex align-items-center"><div class="avatar-sm mr-3 bg-soft-info rounded-circle d-flex align-items-center justify-content-center text-info font-weight-bold shadow-xs" style="width:45px;height:45px;background:#e0f7fa;">' + data.charAt(0) + '</div><span class="font-weight-bold text-dark h6 mb-0">' + data + '</span></div>';
                    }
                },
                { data: 'nip', defaultContent: '-' },
                { 
                    data: 'position', 
                    render: function(data, type, row) {
                        let res = '<div class="font-weight-bold text-dark">' + (data || '-') + '</div>';
                        if (row.additional_duty) {
                            res += '<div class="badge badge-info mt-1 font-weight-normal" style="font-size: 10px; border-radius: 4px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">' + row.additional_duty + '</div>';
                        }
                        return res;
                    }
                },
                { 
                    data: 'rank',
                    render: function(data) {
                        return data ? '<span class="badge badge-light border px-3 py-2 rounded-pill shadow-xs">' + data + '</span>' : '-';
                    }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });

        // Intercept form submit via jQuery delegation (reliable across all browsers)
        $(document).on('submit', '#teacherForm', function(e) {
            e.preventDefault();
            // Strip Rupiah formatting sebelum submit
            let rawSalary = $('#base_salary').val().replace(/\./g, '');
            $('#base_salary').val(rawSalary);
            submitForm(this);
        });

        // Auto-format Rupiah saat mengetik di field gaji
        $(document).on('input', '#base_salary', function() {
            let val = $(this).val().replace(/[^\d]/g, '');
            if (val !== '') {
                $(this).val(formatRibuan(val));
            }
        });
    });

    function addForm(url, title = 'Tambah Guru Baru') {
        // Reset tab to first
        $('#teacherTab a:first').tab('show');
        $(modal).modal('show');
        $(`${modal} .modal-title`).text(title);
        $(`${modal} #teacherForm`).attr('action', url);
        $(`${modal} [name=_method]`).val('post');
        resetForm(`${modal} #teacherForm`);
    }

    function editForm(url, title = 'Edit Data Guru') {
        Swal.fire({ title: "Memuat...", didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close();
            // Reset tab to first
            $('#teacherTab a:first').tab('show');
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} #teacherForm`).attr('action', url);
            $(`${modal} [name=_method]`).val('put');
            resetForm(`${modal} #teacherForm`);
            loopForm(res.data);
            
            // Explicitly set select values and trigger change
            $(`${modal} [name=gender]`).val(res.data.gender).trigger('change');
            $(`${modal} [name=employment_status]`).val(res.data.employment_status).trigger('change');
            $(`${modal} [name=position]`).val(res.data.position).trigger('change');
            $(`${modal} [name=additional_duty]`).val(res.data.additional_duty).trigger('change');
            $(`${modal} [name=certification_status]`).val(res.data.certification_status).trigger('change');
            $(`${modal} [name=education]`).val(res.data.education).trigger('change');

            // Format salary setelah data di-load
            let salary = res.data.base_salary;
            if (salary) {
                $('#base_salary').val(formatRibuan(String(salary)));
            }
        }).fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data guru' }); });
    }

    // Helper: Format angka ke format ribuan Indonesia (titik sebagai pemisah)
    function formatRibuan(str) {
        let num = String(str).replace(/[^\d]/g, '');
        return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function submitForm(originalForm) {
        $(button).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
        Swal.fire({ title: 'Menyimpan Data...', didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(originalForm).attr('action'), type: 'POST',
            data: new FormData(originalForm), dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function(res) {
                Swal.close();
                $(modal).modal('hide');
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil Disimpan!', 
                    text: res.message, 
                    timer: 2500, 
                    showConfirmButton: false,
                    timerProgressBar: true
                });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close();
                let msg = 'Terjadi kesalahan pada server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errs = xhr.responseJSON.errors;
                    msg = Object.values(errs).map(e => e[0]).join('<br>');
                }
                Swal.fire({ icon: 'error', title: 'Gagal!', html: msg });
            },
            complete: function() {
                $(button).prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN DATA');
            }
        });
    }

    function deleteData(url, name) {
        Swal.fire({ 
            title: 'Hapus Guru?', 
            text: 'Yakin ingin menghapus ' + name + '?', 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', didOpen: () => Swal.showLoading() });
                $.ajax({ 
                    type: "DELETE", url: url, 
                    success: (res) => { 
                        table.ajax.reload(); 
                        Swal.fire({ icon: 'success', title: 'Dihapus!', text: res.message, timer: 2000, showConfirmButton: false }); 
                    },
                    error: () => { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menghapus data.' }); }
                });
            }
        });
    }

    // Handle Import Form
    $(document).on('submit', '#importForm', function(e) {
        e.preventDefault();
        $('#importBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Mengimport...');
        Swal.fire({ title: 'Mengimport Data...', text: 'Mohon tunggu, proses ini mungkin memerlukan beberapa detik.', didOpen: () => Swal.showLoading() });
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                Swal.close();
                $('#importModal').modal('hide');
                $('#importForm')[0].reset();
                $('.custom-file-label').text('Pilih file...');
                Swal.fire({ icon: 'success', title: 'Import Berhasil!', text: res.message, timer: 3000, showConfirmButton: false, timerProgressBar: true });
                table.ajax.reload();
            },
            error: function(xhr) {
                Swal.close();
                let msg = xhr.responseJSON?.message || 'Terjadi kesalahan saat import.';
                Swal.fire({ icon: 'error', title: 'Import Gagal!', html: msg });
            },
            complete: function() {
                $('#importBtn').prop('disabled', false).html('<i class="fas fa-upload mr-2"></i> Import Sekarang');
            }
        });
    });
</script>
@endpush
