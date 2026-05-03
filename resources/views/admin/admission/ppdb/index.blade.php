@extends($layout)

@section('title', 'Pendaftar PPDB')
@section('subtitle', 'Data Pendaftar')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    {{-- STATISTIK --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); color: white;">
                <div class="inner">
                    <h3 class="font-weight-bold">{{ $stats['total'] }}</h3>
                    <p class="mb-0 opacity-75">Total Pendaftar</p>
                </div>
                <div class="icon" style="color: rgba(255,255,255,0.3);"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                <div class="inner">
                    <h3 class="font-weight-bold">{{ $stats['pending'] }}</h3>
                    <p class="mb-0 opacity-75">Menunggu Verifikasi</p>
                </div>
                <div class="icon" style="color: rgba(255,255,255,0.3);"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <div class="inner">
                    <h3 class="font-weight-bold">{{ $stats['diterima'] }}</h3>
                    <p class="mb-0 opacity-75">Diterima / Lulus</p>
                </div>
                <div class="icon" style="color: rgba(255,255,255,0.3);"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                <div class="inner">
                    <h3 class="font-weight-bold">{{ $stats['ditolak'] }}</h3>
                    <p class="mb-0 opacity-75">Ditolak / Tidak Lulus</p>
                </div>
                <div class="icon" style="color: rgba(255,255,255,0.3);"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="row">
        <div class="col-md-8">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Tren Pendaftaran (30 Hari Terakhir)</h3>
                </x-slot>
                <div class="chart">
                    <canvas id="regTrendChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Distribusi Jalur</h3>
                </x-slot>
                <div class="chart">
                    <canvas id="typeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    {{-- TABLE --}}
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
                            <select id="filter_status" class="form-control form-control-sm" style="width:180px;">
                                <option value="">-- Status --</option>
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="berkas_lengkap">Berkas Lengkap</option>
                                <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <button onclick="applyFilter()" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filter</button>
                            <button onclick="resetFilter()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync"></i> Reset</button>
                        </div>
                        <div class="d-flex flex-wrap mt-2 mt-md-0" style="gap:8px;">
                            <div class="dropdown" id="bulk_actions" style="display:none;">
                                <button class="btn btn-sm btn-outline-success dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-tasks mr-1"></i> Aksi Kolektif (<span id="selected_count">0</span>)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="bulkUpdateStatus('diterima')">
                                        <i class="fas fa-check-circle mr-2 text-success"></i> Set DITERIMA
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="bulkUpdateStatus('berkas_lengkap')">
                                        <i class="fas fa-file-invoice mr-2 text-info"></i> Set BERKAS LENGKAP
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="bulkUpdateStatus('ditolak')">
                                        <i class="fas fa-times-circle mr-2 text-danger"></i> Set DITOLAK
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-info dropdown-toggle shadow-sm" type="button" data-toggle="dropdown">
                                    <i class="fas fa-print mr-1"></i> Cetak Kolektif
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item" href="{{ route('ppdb.print_berita_acara') }}" target="_blank">
                                        <i class="fas fa-file-alt mr-2 text-info"></i> Berita Acara Penerimaan
                                    </a>
                                    <a class="dropdown-item" href="{{ route('ppdb.print_collective_sk') }}" target="_blank">
                                        <i class="fas fa-users mr-2 text-success"></i> SK Kolektif (Daftar Lulus)
                                    </a>
                                </div>
                            </div>
                            @if ($admission)
                                <a href="{{ route('ppdb.selection') }}" class="btn btn-sm btn-outline-success shadow-sm">
                                    <i class="fas fa-tasks mr-1"></i> Proses Seleksi
                                </a>
                                <button onclick="addForm(`{{ route('ppdb.store') }}`)" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Pendaftar
                                </button>
                            @endif
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="4%"><input type="checkbox" id="select_all"></th>
                        <th width="4%">NO</th>
                        <th>NO. PENDAFTARAN</th>
                        <th>NAMA LENGKAP</th>
                        <th>JK</th>
                        <th>ASAL SEKOLAH</th>
                        <th>GELOMBANG</th>
                        <th>JALUR</th>
                        <th>STATUS</th>
                        <th width="12%">AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @if ($admission)
        @include('admin.admission.ppdb.form')
    @endif
    @include('admin.admission.ppdb.detail')
    @include('admin.admission.ppdb.verify')
@endsection

@include('includes.datatable')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        $(function() {
            // Trend Chart
            const trendCtx = document.getElementById('regTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendData->pluck('date')) !!},
                    datasets: [{
                        label: 'Pendaftar',
                        data: {!! json_encode($trendData->pluck('total')) !!},
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // Type Chart
            const typeCtx = document.getElementById('typeChart').getContext('2d');
            new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($typeDistribution->pluck('label')) !!},
                    datasets: [{
                        data: {!! json_encode($typeDistribution->pluck('value')) !!},
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route("ppdb.data") }}',
                    data: function(d) {
                        d.phase_id = $('#filter_phase').val();
                        d.type_id = $('#filter_type').val();
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [
                    { data: 'select_checkbox', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'registration_number' },
                    { data: 'nama_lengkap' },
                    { data: 'jk_label', orderable: false, searchable: false },
                    { data: 'asal_sekolah', defaultContent: '-' },
                    { data: 'gelombang', orderable: false, searchable: false },
                    { data: 'jalur', orderable: false, searchable: false },
                    { data: 'status_badge', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false },
                ]
            });

            // Select All Checkbox
            $('#select_all').on('click', function() {
                $('.select-row').prop('checked', this.checked);
                toggleBulkActions();
            });

            $(document).on('change', '.select-row', function() {
                if ($('.select-row:checked').length == $('.select-row').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
                toggleBulkActions();
            });
        });

        function toggleBulkActions() {
            let count = $('.select-row:checked').length;
            if (count > 0) {
                $('#bulk_actions').show();
                $('#selected_count').text(count);
            } else {
                $('#bulk_actions').hide();
            }
        }

        function bulkUpdateStatus(status) {
            let ids = [];
            $('.select-row:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) return;

            Swal.fire({
                title: 'Konfirmasi Masal',
                text: `Apakah Anda yakin ingin mengubah status ${ids.length} pendaftar yang dipilih menjadi ${status.toUpperCase()}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya, Perbarui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "POST",
                        url: `{{ route('ppdb.bulk_update_status') }}`,
                        data: { 
                            _token: '{{ csrf_token() }}',
                            ids: ids,
                            status: status
                        },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 3000 })
                                .then(() => { 
                                    $('#select_all').prop('checked', false);
                                    table.ajax.reload(); 
                                    toggleBulkActions();
                                });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }

        function applyFilter() { table.ajax.reload(); }
        function resetFilter() {
            $('#filter_phase, #filter_type, #filter_status').val('');
            table.ajax.reload();
        }

        function addForm(url, title = 'Tambah Pendaftar PPDB') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);
            $(`${modal} .nav-link:first`).tab('show');
        }

        function editForm(url, title = 'Edit Data Pendaftar') {
            Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.get(url)
                .done(response => {
                    Swal.close();
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');
                    resetForm(`${modal} form`);
                    loopForm(response.data);
                    $(`${modal} .nav-link:first`).tab('show');
                })
                .fail(errors => {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: errors.responseJSON?.message || 'Gagal memuat data.' });
                });
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
                    
                    if (['berkas_lengkap', 'diterima', 'ditolak'].includes(d.status)) {
                        $('#line_1, #step_2').addClass('active');
                    }
                    if (['diterima', 'ditolak'].includes(d.status)) {
                        $('#line_2, #step_3, #line_3').addClass('active');
                        if (d.status === 'diterima') $('#step_4').addClass('success active');
                        if (d.status === 'ditolak') $('#step_4').addClass('danger active');
                    }
                    
                    // Handle Print & Move Button
                    if (['diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi', 'ditolak'].includes(d.status)) {
                        $('#btn-print-letter').attr('href', `{{ url('/admission/ppdb') }}/${d.id}/print-letter`).removeClass('d-none');
                    } else {
                        $('#btn-print-letter').addClass('d-none');
                    }

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

        function openVerify(id) {
            Swal.fire({ title: "Memuat...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            $.get(`{{ url('/admission/ppdb') }}/${id}`)
                .done(response => {
                    Swal.close();
                    let d = response.data;
                    let vm = '#modal-verify';

                    $(`${vm} form`).attr('action', `{{ url('/admission/ppdb') }}/${d.id}/verify`);
                    $('#verify_nama').text(d.nama_lengkap);
                    $('#verify_reg_no').text(d.registration_number);
                    $(`${vm} [name=status]`).val(d.status);
                    $(`${vm} [name=catatan_verifikasi]`).val(d.catatan_verifikasi || '');
                    $(`${vm} [name=average_score]`).val(d.average_score || '');
                    $(`${vm} [name=distance_km]`).val(d.distance_km || '');

                    // Documents
                    let tbody = '';
                    if (d.documents && d.documents.length > 0) {
                        d.documents.forEach(doc => {
                            tbody += `<tr>
                                <td>${doc.document_name}</td>
                                <td class="text-center">
                                    <a href="/admission/ppdb/document/${doc.id}/download" target="_blank" class="btn btn-xs btn-outline-info"><i class="fas fa-eye"></i> Lihat</a>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="doc_verified[${doc.id}]" value="1" ${doc.is_verified ? 'checked' : ''}>
                                </td>
                                <td>
                                    <input type="text" name="doc_notes[${doc.id}]" class="form-control form-control-sm" value="${doc.verification_note || ''}" placeholder="Catatan...">
                                </td>
                            </tr>`;
                        });
                    } else {
                        tbody = '<tr><td colspan="4" class="text-center text-muted">Belum ada berkas diupload</td></tr>';
                    }
                    $('#verify_docs_tbody').html(tbody);

                    $(vm).modal('show');
                })
                .fail(() => { Swal.close(); Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data.' }); });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            Swal.fire({ title: 'Mohon Tunggu...', text: 'Sedang memproses data', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST',
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close();
                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 3000 })
                            .then(() => { $(button).prop('disabled', false); table.ajax.reload(); location.reload(); });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    $(button).prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!', timer: 3000 });
                    if (xhr.status === 422) loopErrors(xhr.responseJSON.errors);
                }
            });
        }

        function submitVerify(form) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'JSON',
                success: function(response) {
                    Swal.close();
                    $('#modal-verify').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, showConfirmButton: false, timer: 3000 })
                        .then(() => { table.ajax.reload(); location.reload(); });
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                }
            });
        }

        function deleteData(url, name) {
            Swal.fire({
                title: 'Hapus Data!',
                text: 'Apakah Anda yakin ingin menghapus pendaftar ' + name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 3000 })
                                .then(() => { table.ajax.reload(); location.reload(); });
                        },
                        error: function(xhr) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }

        function moveToStudent(id, name) {
            let classOptions = '';
            @foreach($classGroups as $cg)
                classOptions += `<option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>`;
            @endforeach

            Swal.fire({
                title: 'Pindahkan ke Data Siswa',
                html: `
                    <div class="text-left mb-3">
                        <p>Apakah Anda yakin ingin memindahkan <b>${name}</b> ke database Induk Siswa?</p>
                        <div class="form-group">
                            <label>Pilih Kelas Tujuan:</label>
                            <select id="swal_indiv_class_id" class="form-control">
                                <option value="">-- Pilih Kelas --</option>
                                ${classOptions}
                            </select>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya, Pindahkan!',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const classId = Swal.getPopup().querySelector('#swal_indiv_class_id').value;
                    if (!classId) {
                        Swal.showValidationMessage(`Silakan pilih kelas tujuan`);
                    }
                    return { classId: classId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memindahkan...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        type: "POST",
                        url: `{{ url('/admission/ppdb/move-to-student') }}/${id}`,
                        data: { 
                            _token: '{{ csrf_token() }}',
                            class_group_id: result.value.classId
                        },
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
                title: 'Konfirmasi Pindah',
                text: `Apakah Anda yakin ingin memindahkan ${name} ke database Induk Siswa?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    $.ajax({
                        url: `{{ url('/admission/ppdb/move-to-student') }}/${id}`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            Swal.close();
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message })
                                .then(() => { $('#modal-detail').modal('hide'); table.ajax.reload(); });
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                        }
                    });
                }
            });
        }
    </script>
@endpush
