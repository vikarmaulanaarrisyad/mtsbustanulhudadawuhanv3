@extends($layout)

@section('title', 'Verifikasi Daftar Ulang')
@section('subtitle', 'Daftar Ulang Pendaftar')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">Verifikasi Daftar Ulang</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <select id="filter_phase" class="form-control form-control-sm" style="width:180px;">
                                <option value="">-- Gelombang --</option>
                                @foreach ($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                @endforeach
                            </select>
                            <select id="filter_type" class="form-control form-control-sm" style="width:160px;">
                                <option value="">-- Jalur --</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                @endforeach
                            </select>
                            <button onclick="applyFilter()" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
                            <button onclick="resetFilter()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync"></i> Reset</button>
                        </div>
                        <div class="d-flex flex-wrap mt-2 mt-md-0" style="gap:8px;">
                            <button onclick="bulkMove()" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-users mr-1"></i> Pindah Kolektif
                            </button>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="4%">NO</th>
                        <th>NO. PENDAFTARAN</th>
                        <th>NAMA LENGKAP</th>
                        <th>JK</th>
                        <th>ASAL SEKOLAH</th>
                        <th>GELOMBANG</th>
                        <th>TANGGAL KONFIRMASI</th>
                        <th>STATUS</th>
                        <th width="12%">AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.admission.ppdb.detail')
@endsection

@include('includes.datatable')

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route("ppdb.re_registration_data") }}',
                    data: function(d) {
                        d.phase_id = $('#filter_phase').val();
                        d.type_id = $('#filter_type').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'registration_number' },
                    { data: 'nama_lengkap' },
                    { data: 'jk_label', orderable: false, searchable: false },
                    { data: 'asal_sekolah', defaultContent: '-' },
                    { data: 'admission_phase.phase_name', name: 'admissionPhase.phase_name', defaultContent: '-' },
                    { data: 'confirmed_at_formatted', name: 'confirmed_at' },
                    { data: 'status_badge', orderable: false, searchable: false },
                    { 
                        data: null, 
                        orderable: false, 
                        searchable: false,
                        render: function(data, type, row) {
                            let btnVerify = '';
                            if (row.status === 'daftar_ulang') {
                                btnVerify = `
                                    <button onclick="verifyPayment(${row.id})" class="btn btn-sm btn-success shadow-sm" title="Verifikasi Pembayaran">
                                        <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                    </button>
                                `;
                            }
                            
                            let btnMove = '';
                            if (row.status === 'daftar_ulang_terverifikasi') {
                                btnMove = `
                                    <button onclick="moveIndividualToStudent(${row.id}, '${row.nama_lengkap}')" class="btn btn-sm btn-primary shadow-sm" title="Pindahkan ke Data Siswa">
                                        <i class="fas fa-user-check mr-1"></i> Pindahkan
                                    </button>
                                `;
                            }

                            return `
                                <div class="btn-group" style="gap:5px;">
                                    <button onclick="showDetail(${row.id})" class="btn btn-sm btn-info shadow-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${btnVerify}
                                    ${btnMove}
                                </div>
                            `;
                        }
                    },
                ]
            });
        });

        function applyFilter() { table.ajax.reload(); }
        function resetFilter() {
            $('#filter_phase, #filter_type').val('');
            table.ajax.reload();
        }

        function showDetail(id) {
            Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.get(`{{ url('/admission/ppdb') }}/${id}`)
                .done(response => {
                    Swal.close();
                    let d = response.data;
                    $('#det_reg_no').text(d.registration_number);
                    $('#det_nama').text(d.nama_lengkap);
                    $('#det_nisn').text(d.nisn || '-');
                    $('#det_nik').text(d.nik || '-');
                    $('#det_jk').text(d.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
                    $('#det_ttl').text((d.tempat_lahir || '-') + ', ' + (d.tanggal_lahir ? new Date(d.tanggal_lahir).toLocaleDateString('id-ID') : '-'));
                    $('#det_asal').text(d.asal_sekolah || '-');
                    $('#det_ayah').text(d.nama_ayah || '-');
                    $('#det_ibu').text(d.nama_ibu || '-');
                    $('#det_hp').text(d.no_hp_ortu || '-');
                    $('#det_alamat').text(d.alamat || '-');
                    $('#det_gelombang').text(d.admission_phase ? d.admission_phase.phase_name : '-');
                    $('#det_jalur').text(d.admission_type ? d.admission_type.admission_type_name : '-');
                    $('#det_verifier').text(d.verifier ? d.verifier.name : '-');
                    $('#det_verified_at').text(d.verified_at ? new Date(d.verified_at).toLocaleString('id-ID') : '-');
                    
                    // Stepper Logic
                    $('.step, .step-line').removeClass('active success danger');
                    $('#step_1').addClass('active');
                    
                    if (['berkas_lengkap', 'diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) {
                        $('#line_1, #step_2').addClass('active');
                    }
                    if (['diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) {
                        $('#line_2, #step_3, #line_3').addClass('active');
                        if (['diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status)) $('#step_4').addClass('success active');
                        if (d.status === 'ditolak') $('#step_4').addClass('danger active');
                    }
                    
                    // Handle Print & Move Button
                    // No print letter in this simplified view if not needed, or keep it
                    $('#btn-print-letter').addClass('d-none');

                    if (d.status === 'daftar_ulang_terverifikasi') {
                        $('#btn-move-student').removeClass('d-none').off('click').on('click', function() {
                            moveIndividualToStudent(d.id, d.nama_lengkap);
                        });
                    } else {
                        $('#btn-move-student').addClass('d-none');
                    }

                    // Handle Payment Proof Section
                    if (['daftar_ulang', 'daftar_ulang_terverifikasi'].includes(d.status) && d.payment_proof) {
                        $('#det_payment_section').removeClass('d-none');
                        $('#det_payment_img').attr('src', '/storage/' + d.payment_proof);
                        $('#det_payment_link').attr('href', '/storage/' + d.payment_proof);
                        $('#det_payment_date').text(d.confirmed_at_formatted || d.confirmed_at || '-');
                        
                        if (d.status === 'daftar_ulang_terverifikasi') {
                            $('#payment_status_verified').removeClass('d-none');
                            $('#payment_status_pending').addClass('d-none');
                        } else {
                            $('#payment_status_verified').addClass('d-none');
                            $('#payment_status_pending').removeClass('d-none');
                            $('#btn-verify-payment').off('click').on('click', function() {
                                verifyPayment(d.id);
                            });
                        }
                    } else {
                        $('#det_payment_section').addClass('d-none');
                    }

                    if (d.foto) {
                        $('#det_foto').attr('src', '/storage/' + d.foto).show();
                    } else {
                        $('#det_foto').hide();
                    }

                    // Documents
                    let docHtml = '';
                    if (d.documents && d.documents.length > 0) {
                        d.documents.forEach(doc => {
                            let verifiedClass = doc.is_verified ? 'success' : 'secondary';
                            let verifiedIcon = doc.is_verified ? 'check-circle' : 'clock';
                            let verifiedText = doc.is_verified ? 'Terverifikasi' : 'Belum';
                            
                            docHtml += `
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center p-2 border rounded shadow-xs bg-white">
                                    <div class="mr-3 text-info"><i class="fas fa-file-alt fa-2x"></i></div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="small font-weight-bold text-truncate" title="${doc.document_name}">${doc.document_name}</div>
                                        <div class="extra-small text-${verifiedClass}">
                                            <i class="fas fa-${verifiedIcon} mr-1"></i> ${verifiedText}
                                        </div>
                                    </div>
                                    <a href="/admission/ppdb/document/${doc.id}/download" class="btn btn-sm btn-light border ml-2" title="Unduh">
                                        <i class="fas fa-download text-primary"></i>
                                    </a>
                                </div>
                            </div>`;
                        });
                    } else {
                        docHtml = '<div class="col-12 text-center text-muted py-3">Belum ada berkas diupload</div>';
                    }
                    $('#det_docs_container').html(docHtml);

                    $('#modal-detail').modal('show');
                })
                .fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat detail.' }); });
        }

        function verifyPayment(id) {
            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: 'Apakah Anda yakin bukti pembayaran ini sudah valid?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        url: `{{ url('/admission/ppdb') }}/${id}/verify-re-registration`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message })
                                .then(() => { 
                                    showDetail(id); // Reload detail modal
                                    table.ajax.reload(); 
                                });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan server.' });
                        }
                    });
                }
            });
        }

        function moveIndividualToStudent(id, name) {
            Swal.fire({
                title: 'Pindahkan ke Data Siswa',
                text: `Apakah Anda yakin ingin memindahkan ${name} ke database Induk Siswa?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya, Pindahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memindahkan...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "POST",
                        url: `{{ url('/admission/ppdb/move-to-student') }}/${id}`,
                        data: { _token: '{{ csrf_token() }}' },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 3000 })
                                .then(() => { table.ajax.reload(); $('#modal-detail').modal('hide'); });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }

        function bulkMove() {
            let phaseId = $('#filter_phase').val();
            let typeId = $('#filter_type').val();

            if (!phaseId || !typeId) {
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'Perhatian', 
                    text: 'Silakan pilih Gelombang dan Jalur terlebih dahulu untuk melakukan pindah kolektif sesuai kategori.' 
                });
                return;
            }

            Swal.fire({
                title: 'Pindah Kolektif ke Data Siswa',
                text: 'Memindahkan semua pendaftar dengan status "Daftar Ulang Terverifikasi" pada filter terpilih ke database Induk Siswa. Data kelas bisa diatur kemudian di menu Siswa.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya, Pindahkan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Sedang Memproses...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "POST",
                        url: `{{ route('ppdb.bulk_move_to_student') }}`,
                        data: { 
                            _token: '{{ csrf_token() }}',
                            phase_id: phaseId,
                            type_id: typeId
                        },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Berhasil', 
                                text: response.message + (response.errors > 0 ? ` (${response.errors} data dilewati karena sudah ada)` : ''), 
                                showConfirmButton: true 
                            }).then(() => { table.ajax.reload(); });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }
    </script>
@endpush
