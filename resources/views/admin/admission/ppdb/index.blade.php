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
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Pendaftar</p>
                </div>
                <div class="icon"><i class="fas fa-user-plus"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['diterima'] }}</h3>
                    <p>Diterima</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['ditolak'] }}</h3>
                    <p>Ditolak</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
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
                            @if ($admission)
                                <button onclick="addForm(`{{ route('ppdb.store') }}`)" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus-circle"></i> Tambah Pendaftar
                                </button>
                            @endif
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
        });

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
                    $('#det_catatan').text(d.catatan_verifikasi || '-');
                    $('#det_verifier').text(d.verifier ? d.verifier.name : '-');
                    $('#det_verified_at').text(d.verified_at ? new Date(d.verified_at).toLocaleString('id-ID') : '-');

                    if (d.foto) {
                        $('#det_foto').attr('src', '/storage/' + d.foto).show();
                    } else {
                        $('#det_foto').hide();
                    }

                    // Documents
                    let docHtml = '';
                    if (d.documents && d.documents.length > 0) {
                        d.documents.forEach(doc => {
                            let verified = doc.is_verified
                                ? '<span class="badge badge-success"><i class="fas fa-check"></i> Terverifikasi</span>'
                                : '<span class="badge badge-secondary"><i class="fas fa-clock"></i> Belum</span>';
                            docHtml += `<tr>
                                <td>${doc.document_name}</td>
                                <td>${verified}</td>
                                <td><a href="/admission/ppdb/document/${doc.id}/download" class="btn btn-xs btn-outline-primary"><i class="fas fa-download"></i></a></td>
                            </tr>`;
                        });
                    } else {
                        docHtml = '<tr><td colspan="3" class="text-center text-muted">Belum ada berkas</td></tr>';
                    }
                    $('#det_docs_tbody').html(docHtml);

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
    </script>
@endpush
