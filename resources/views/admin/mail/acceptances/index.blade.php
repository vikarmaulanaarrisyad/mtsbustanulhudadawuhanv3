@extends($layout)

@section('title', 'Surat Keterangan Diterima')
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
                            <i class="fas fa-user-check mr-2 animate__animated animate__fadeInLeft"></i> 
                            Surat Keterangan Diterima
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Penerbitan surat keterangan bagi siswa baru yang telah dinyatakan diterima di Madrasah ini.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-id-card-alt fa-8x opacity-2 shadow-icon"></i>
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
                    <h5 class="card-title font-weight-bold mb-0 text-dark">Filter Pencarian Surat</h5>
                </div>
                <button onclick="addForm()" class="btn btn-emerald rounded-pill px-4 font-weight-bold shadow-emerald-light">
                    <i class="fas fa-plus-circle mr-1"></i> BUAT SURAT BARU
                </button>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Siswa</label>
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
                <h4 class="mb-1 font-weight-bold text-dark">Daftar Surat Keterangan Diterima</h4>
                <p class="text-muted text-sm mb-0">Daftar riwayat penerbitan surat keterangan diterima bagi siswa baru</p>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="acceptanceTable" style="width:100%">
                        <thead class="bg-light-emerald text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>NOMOR SURAT</th>
                                <th>TANGGAL</th>
                                <th>NAMA SISWA</th>
                                <th>SEKOLAH ASAL</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    @include('admin.mail.acceptances.form')
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
    <script>
        let table;

        $(function() {
            $('.select2-no-search').select2({ minimumResultsForSearch: -1, width: '100%' });

            table = $('#acceptanceTable').DataTable({
                processing: true, serverSide: true, autoWidth: false,
                language: { searchPlaceholder: "Cari surat...", search: "" },
                ajax: { 
                    url: '{{ route("student-acceptances.data") }}',
                    data: function(d) {
                        d.student_id = $('#filter_student_id').val();
                        d.start_date = $('#filter_start_date').val();
                        d.end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                    { 
                        data: 'acceptance_number',
                        render: function(data) {
                            return '<span class="badge badge-light border border-emerald px-2 py-1 text-emerald shadow-sm font-weight-bold">' + data + '</span>';
                        }
                    },
                    { 
                        data: 'acceptance_date',
                        render: function(data) {
                            return '<div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-alt mr-1"></i> ' + data + '</div>';
                        }
                    },
                    { 
                        data: 'student_name',
                        render: function(data) {
                            return '<span class="font-weight-bold text-dark text-sm">' + data + '</span>';
                        }
                    },
                    { 
                        data: 'origin_school',
                        render: function(data) {
                            return '<div class="text-xs text-muted font-weight-bold"><i class="fas fa-school text-info mr-1"></i> ' + data + '</div>';
                        }
                    },
                    { data: 'action', searchable: false, sortable: false, className: 'text-center' },
                ]
            });

            $('#formAcceptance').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    let id = $('#id').val();
                    let url = id ? "{{ url('admin/mail/student-acceptances') }}/" + id : "{{ route('student-acceptances.store') }}";
                    let method = id ? 'PUT' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            Swal.fire({icon: 'success', title: 'Berhasil', text: response.message});
                        },
                        error: function(xhr) {
                            Swal.fire({icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan'});
                        }
                    });
                    return false;
                }
            });
        });

        function addForm() {
            $('#modal-form').modal('show');
            $('.modal-title').text('Tambah Surat Diterima');
            $('#formAcceptance')[0].reset();
            $('#id').val('');
            $('#student_id').val('').trigger('change');
            $('#acceptance_number').val('');
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('.modal-title').text('Edit Surat Diterima');
            $('#formAcceptance')[0].reset();

            $.get(url).done(response => {
                let data = response.data;
                $('#id').val(data.id);
                $('#student_id').val(data.student_id).trigger('change');
                $('#acceptance_number').val(data.acceptance_number);
                $('#acceptance_date').val(data.acceptance_date);
                $('#origin_school').val(data.origin_school);
                $('#origin_class').val(data.origin_class);
                $('#signer_name').val(data.signer_name);
                $('#signer_position').val(data.signer_position);
                $('#signer_nip').val(data.signer_nip);
            });
        }

        function deleteData(url, number) {
            Swal.fire({
                title: 'Hapus Surat?',
                text: 'Apakah Anda yakin ingin menghapus surat nomor ' + number + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        '_token': '{{ csrf_token() }}',
                        '_method': 'DELETE'
                    }).done(response => {
                        table.ajax.reload();
                        Swal.fire({icon: 'success', title: 'Dihapus', text: response.message});
                    });
                }
            });
        }
        function refreshTable() {
            let btn = $('.btn-emerald');
            let originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> LOADING...');
            table.ajax.reload(function() {
                btn.html(originalHtml);
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
        .btn-soft-info { background: #e0f2fe; color: #0369a1; border: none; }
        .btn-soft-info:hover { background: #bae6fd; color: #075985; }
        .btn-soft-primary { background: #e0e7ff; color: #4338ca; border: none; }
        .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }
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

        /* Table Enhancements */
        #acceptanceTable { border-collapse: separate; border-spacing: 0 8px; }
        #acceptanceTable tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; }
        #acceptanceTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fffa; }
        #acceptanceTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
        #acceptanceTable td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #059669; }
        #acceptanceTable td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
    </style>
@endpush
