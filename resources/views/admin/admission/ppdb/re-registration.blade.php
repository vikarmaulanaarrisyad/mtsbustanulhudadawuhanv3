@extends($layout)

@section('title', 'Verifikasi Daftar Ulang')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-gold-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-file-invoice-dollar mr-2 animate__animated animate__fadeInLeft"></i> 
                            Verifikasi & Validasi Daftar Ulang
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Lakukan validasi bukti pembayaran dan aktivasi status siswa baru ke database Induk Madrasah.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-check-double fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-gold-1"></div>
            <div class="bg-shape-gold-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <!-- FILTERS -->
                    <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                        <div class="filter-group shadow-xs">
                            <select id="filter_phase" class="form-control rounded-pill px-3 border-2 text-sm" style="min-width:180px;">
                                <option value="">-- Gelombang --</option>
                                @foreach ($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group shadow-xs">
                            <select id="filter_type" class="form-control rounded-pill px-3 border-2 text-sm" style="min-width:160px;">
                                <option value="">-- Jalur --</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button onclick="applyFilter()" class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-indigo-light">
                            <i class="fas fa-filter mr-1"></i> FILTER
                        </button>
                        <button onclick="resetFilter()" class="btn btn-light rounded-pill px-3 border">
                            <i class="fas fa-sync mr-1"></i> RESET
                        </button>
                    </div>

                    <!-- ACTIONS -->
                    <div class="d-flex flex-wrap mt-2 mt-md-0" style="gap:10px;">
                        <button onclick="bulkMove()" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-primary">
                            <i class="fas fa-users-cog mr-2"></i> PINDAH KOLEKTIF KE DATA INDUK
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-soft-indigo rounded-20 border-0 shadow-sm mb-4 p-3 animate__animated animate__fadeIn">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-indigo rounded-circle d-flex align-items-center justify-content-center mr-3 text-white">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="small">
                            Hanya pendaftar yang telah <strong>Lulus Seleksi</strong> dan melakukan <strong>Konfirmasi Pembayaran</strong> yang akan muncul di daftar ini.
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="reRegTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3">NO</th>
                                <th>NO. PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th class="text-center">JK</th>
                                <th>ASAL SEKOLAH</th>
                                <th>GELOMBANG</th>
                                <th>KONFIRMASI PADA</th>
                                <th class="text-center">STATUS</th>
                                <th width="150px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.admission.ppdb.detail')

<style>
    /* PREMIUM UI STYLES */
    .bg-gradient-gold-dark { background: linear-gradient(135deg, #b45309 0%, #1e1b4b 100%) !important; }
    .bg-shape-gold-1 { position: absolute; width: 400px; height: 400px; background: rgba(251, 191, 36, 0.1); border-radius: 50%; top: -150px; right: -100px; }
    .bg-shape-gold-2 { position: absolute; width: 200px; height: 200px; background: rgba(251, 191, 36, 0.05); border-radius: 50%; bottom: -50px; left: 10%; }
    
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; }
    .alert-soft-indigo { background: #fcfaff; color: #4338ca; border-left: 5px solid #4f46e5; }
    
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2); }

    /* Table Styling */
    #reRegTable { border-collapse: separate; border-spacing: 0 10px; }
    #reRegTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 12px; }
    #reRegTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); background: #fcfaff; }
    #reRegTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #reRegTable td:first-child { border-radius: 12px 0 0 12px; }
    #reRegTable td:last-child { border-radius: 0 12px 12px 0; }

    .avatar-sm { width: 32px; height: 32px; }
    .shadow-xs { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
</style>
@endsection

@include('includes.datatable')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#reRegTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari pendaftar...", search: "" },
            ajax: {
                url: '{{ route("ppdb.re_registration_data") }}',
                data: (d) => { d.phase_id = $('#filter_phase').val(); d.type_id = $('#filter_type').val(); }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { data: 'registration_number', render: (data) => '<span class="badge badge-light border px-3 py-2 text-indigo font-weight-bold">' + data + '</span>' },
                { 
                    data: 'nama_lengkap',
                    render: (data) => '<span class="font-weight-bold text-dark h6 mb-0">' + data + '</span>'
                },
                { data: 'jk_label', className: 'text-center' },
                { data: 'asal_sekolah', defaultContent: '-' },
                { data: 'admission_phase.phase_name', render: (data) => '<span class="small font-weight-bold text-muted">' + (data || '-') + '</span>' },
                { 
                    data: 'confirmed_at_formatted', 
                    render: (data) => '<span class="small text-muted font-weight-bold"><i class="fas fa-clock mr-1"></i> ' + (data || '-') + '</span>' 
                },
                { data: 'status_badge', className: 'text-center' },
                { 
                    data: null, 
                    orderable: false, searchable: false, className: 'text-center',
                    render: function(data, type, row) {
                        let btnVerify = '';
                        if (row.status === 'daftar_ulang') {
                            btnVerify = `<button onclick="verifyPayment(${row.id})" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm ml-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> VALIDASI</button>`;
                        }
                        
                        let btnMove = '';
                        if (row.status === 'daftar_ulang_terverifikasi') {
                            btnMove = `<button onclick="moveIndividualToStudent(${row.id}, '${row.nama_lengkap}')" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm ml-1 font-weight-bold"><i class="fas fa-user-check mr-1"></i> PINDAHKAN</button>`;
                        }

                        return `<div class="d-flex justify-content-center">
                                    <button onclick="showDetail(${row.id})" class="btn btn-sm btn-soft-indigo rounded-circle shadow-xs" title="Detail" style="width:32px;height:32px;padding:0;"><i class="fas fa-eye"></i></button>
                                    ${btnVerify} ${btnMove}
                                </div>`;
                    }
                },
            ]
        });
    });

    function applyFilter() { table.ajax.reload(); }
    function resetFilter() { $('#filter_phase, #filter_type').val(''); table.ajax.reload(); }

    function showDetail(id) {
        Swal.fire({ title: "Memuat Profil...", didOpen: () => Swal.showLoading() });
        $.get(`{{ url('/admission/ppdb') }}/${id}`).done(res => {
            Swal.close(); let d = res.data;
            // Map data to the modernized detail modal (which we updated previously)
            $('#det_reg_no').text(d.registration_number); $('#det_nama').text(d.nama_lengkap);
            $('#det_nisn').text(d.nisn || '-'); $('#det_nik').text(d.nik || '-');
            $('#det_jk').text(d.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $('#det_ttl').text((d.tempat_lahir || '-') + ', ' + (d.tanggal_lahir ? new Date(d.tanggal_lahir).toLocaleDateString('id-ID') : '-'));
            $('#det_asal').text(d.asal_sekolah || '-'); $('#det_ayah').text(d.nama_ayah || '-');
            $('#det_ibu').text(d.nama_ibu || '-'); $('#det_hp').text(d.no_hp_ortu || '-');
            $('#det_alamat').text(d.alamat || '-');
            $('#det_gelombang').text(d.admission_phase ? d.admission_phase.phase_name : '-');
            $('#det_jalur').text(d.admission_type ? d.admission_type.admission_type_name : '-');
            $('#det_verifier').text(d.verifier ? d.verifier.name : '-');
            $('#det_verified_at').text(d.verified_at ? new Date(d.verified_at).toLocaleString('id-ID') : '-');
            
            // Stepper Logic
            $('.step-item, .step-connector').removeClass('active success danger');
            $('#step_1').addClass('active');
            if (['berkas_lengkap', 'diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) { $('#line_1, #step_2').addClass('active'); }
            if (['diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) {
                $('#line_2, #step_3, #line_3').addClass('active');
                if (['diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) $('#step_4').addClass('success active');
                if (d.status === 'ditolak') $('#step_4').addClass('danger active');
            }

            // Handle Move Button
            if (d.status === 'daftar_ulang_terverifikasi') {
                $('#btn-move-student').removeClass('d-none').off('click').on('click', () => moveIndividualToStudent(d.id, d.nama_lengkap));
            } else { $('#btn-move-student').addClass('d-none'); }

            // Handle Payment Proof
            if (['daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status) && d.payment_proof) {
                $('#det_payment_section').removeClass('d-none');
                $('#det_payment_img').attr('src', '/storage/' + d.payment_proof);
                $('#det_payment_link').attr('href', '/storage/' + d.payment_proof);
                $('#det_payment_date').text(d.confirmed_at_formatted || d.confirmed_at || '-');
                if (d.status === 'daftar_ulang_terverifikasi') { $('#payment_status_verified').removeClass('d-none'); $('#payment_status_pending').addClass('d-none'); }
                else { 
                    $('#payment_status_verified').addClass('d-none'); $('#payment_status_pending').removeClass('d-none'); 
                    $('#btn-verify-payment').off('click').on('click', () => verifyPayment(d.id));
                }
            } else { $('#det_payment_section').addClass('d-none'); }

            if (d.foto) $('#det_foto').attr('src', '/storage/' + d.foto).show(); else $('#det_foto').hide();

            // Documents
            let docHtml = '';
            if (d.documents && d.documents.length > 0) {
                d.documents.forEach(doc => {
                    docHtml += `<div class="col-md-6 mb-3"><div class="d-flex align-items-center p-3 border rounded-15 shadow-xs bg-white"><div class="mr-3 text-indigo"><i class="fas fa-file-alt fa-2x"></i></div><div class="flex-grow-1 overflow-hidden"><div class="small font-weight-bold text-truncate">${doc.document_name}</div><div class="text-xs text-${doc.is_verified ? 'success' : 'muted'}"><i class="fas fa-${doc.is_verified ? 'check-circle' : 'clock'} mr-1"></i> ${doc.is_verified ? 'Terverifikasi' : 'Belum'}</div></div><a href="/admission/ppdb/document/${doc.id}/download" class="btn btn-sm btn-light rounded-circle shadow-xs"><i class="fas fa-download text-indigo"></i></a></div></div>`;
                });
            } else { docHtml = '<div class="col-12 text-center text-muted py-4">Berkas digital belum diunggah</div>'; }
            $('#det_docs_container').html(docHtml);

            $('#modal-detail').modal('show');
        });
    }

    function verifyPayment(id) {
        Swal.fire({ title: 'Validasi Pembayaran', text: 'Konfirmasi bahwa bukti pembayaran ini sudah sah?', icon: 'question', showCancelButton: true, confirmButtonColor: '#10b981', confirmButtonText: 'Iya, Validkan!' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Memvalidasi...', didOpen: () => Swal.showLoading() });
                $.post(`{{ url('/admission/ppdb') }}/${id}/verify-re-registration`, { _token: '{{ csrf_token() }}' })
                .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message }); showDetail(id); table.ajax.reload(); })
                .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Error' }); });
            }
        });
    }

    function moveIndividualToStudent(id, name) {
        Swal.fire({ title: 'Pindahkan ke Data Induk', text: `Pindahkan ${name} ke database Induk Siswa?`, icon: 'question', showCancelButton: true, confirmButtonColor: '#4f46e5', confirmButtonText: 'Iya, Pindahkan!' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading() });
                $.post(`{{ url('/admission/ppdb/move-to-student') }}/${id}`, { _token: '{{ csrf_token() }}' })
                .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message }); table.ajax.reload(); $('#modal-detail').modal('hide'); })
                .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); });
            }
        });
    }

    function bulkMove() {
        let phaseId = $('#filter_phase').val(); let typeId = $('#filter_type').val();
        if (!phaseId || !typeId) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih parameter filter terlebih dahulu.' }); return; }

        Swal.fire({ title: 'Pindah Kolektif', text: 'Pindahkan SEMUA pendaftar yang sudah Terverifikasi Daftar Ulang ke database Induk Siswa?', icon: 'info', showCancelButton: true, confirmButtonColor: '#4f46e5', confirmButtonText: 'Iya, Pindahkan Kolektif!' })
        .then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Sedang Memproses...', didOpen: () => Swal.showLoading() });
                $.post(`{{ route('ppdb.bulk_move_to_student') }}`, { _token: '{{ csrf_token() }}', phase_id: phaseId, type_id: typeId })
                .done(res => { Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message }); table.ajax.reload(); })
                .fail(xhr => { Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' }); });
            }
        });
    }
</script>
@endpush
