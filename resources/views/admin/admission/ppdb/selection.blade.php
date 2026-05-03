@extends($layout)

@section('title', 'Proses Seleksi PPDB')
@section('subtitle', 'Seleksi & Perankingan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('ppdb.index') }}">PPDB</a></li>
    <li class="breadcrumb-item active">Proses Seleksi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Seleksi</h3>
                </x-slot>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gelombang</label>
                            <select id="filter_phase" class="form-control select2">
                                <option value="" disabled selected>-- Pilih Gelombang --</option>
                                @foreach ($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jalur Pendaftaran</label>
                            <select id="filter_type" class="form-control select2">
                                <option value="" disabled selected>-- Pilih Jalur --</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-group">
                            <button onclick="applyFilter()" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Tampilkan Peringkat</button>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-list-ol mr-1"></i> Daftar Peringkat (Status: Berkas Lengkap)</h3>
                        <div class="d-flex" style="gap:8px;">
                            <button onclick="confirmProcess()" class="btn btn-primary shadow-sm">
                                <i class="fas fa-check-double mr-1"></i> Proses Penetapan Lulus
                            </button>
                            <button onclick="confirmBulkMove()" class="btn btn-success shadow-sm">
                                <i class="fas fa-user-check mr-1"></i> Pindahkan ke Data Siswa
                            </button>
                        </div>
                    </div>
                </x-slot>
                
                <div class="alert alert-info shadow-sm">
                    <i class="fas fa-info-circle mr-2"></i> 
                    Daftar peringkat pendaftar berdasarkan Nilai Rapor atau Jarak. Gunakan tombol <strong>"Proses Penetapan Lulus"</strong> untuk mengubah status peringkat teratas secara otomatis.
                </div>

                <x-table id="table-selection">
                    <x-slot name="thead">
                        <th width="5%">RANK</th>
                        <th>NO. DAFTAR</th>
                        <th>NAMA LENGKAP</th>
                        <th>SKOR SELEKSI</th>
                        <th>GELOMBANG</th>
                        <th>JALUR</th>
                        <th>STATUS</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatable')

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#table-selection').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    url: '{{ route("ppdb.selection_data") }}',
                    data: function(d) {
                        d.phase_id = $('#filter_phase').val();
                        d.type_id = $('#filter_type').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'registration_number' },
                    { data: 'nama_lengkap' },
                    { data: 'selection_score' },
                    { data: 'admission_phase.phase_name' },
                    { data: 'admission_type.admission_type_name' },
                    { data: 'status_badge', orderable: false, searchable: false },
                ],
                order: [[3, 'desc']] // Sort by selection_score (column index 3)
            });
        });

        function applyFilter() { 
            if (!$('#filter_phase').val() || !$('#filter_type').val()) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Pilih Gelombang dan Jalur untuk melihat peringkat.'
                });
                return;
            }
            table.ajax.reload(); 
        }

        function confirmProcess() {
            let phaseId = $('#filter_phase').val();
            let typeId = $('#filter_type').val();

            if (!phaseId || !typeId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih Gelombang dan Jalur terlebih dahulu.'
                });
                return;
            }

            let phaseName = $('#filter_phase option:selected').text();
            let typeName = $('#filter_type option:selected').text();

            Swal.fire({
                title: 'Konfirmasi Seleksi',
                text: `Apakah Anda yakin ingin memproses penetapan LULUS untuk Gelombang: ${phaseName} dan Jalur: ${typeName}? Status siswa peringkat teratas akan berubah menjadi DITERIMA sesuai kuota yang tersedia.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Proses Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeProcess();
                }
            });
        }

        function executeProcess() {
            Swal.fire({ title: 'Memproses Seleksi...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: '{{ route("ppdb.process_selection") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    phase_id: $('#filter_phase').val(),
                    type_id: $('#filter_type').val()
                },
                success: function(response) {
                    Swal.close();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message })
                        .then(() => { table.ajax.reload(); });
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                }
            });
        }

        function confirmBulkMove() {
            let phaseId = $('#filter_phase').val();
            let typeId = $('#filter_type').val();

            if (!phaseId || !typeId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih Gelombang dan Jalur terlebih dahulu.'
                });
                return;
            }

            let phaseName = $('#filter_phase option:selected').text();
            let typeName = $('#filter_type option:selected').text();

            let classOptions = '';
            @foreach($classGroups as $cg)
                classOptions += `<option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>`;
            @endforeach

            Swal.fire({
                title: 'Pindahkan ke Data Siswa',
                html: `
                    <div class="text-left mb-3">
                        <p>Anda akan memindahkan semua siswa berstatus <b>DITERIMA</b> atau <b>DAFTAR ULANG</b> pada ${phaseName} - ${typeName} ke database Induk Siswa.</p>
                        <div class="form-group">
                            <label>Pilih Kelas Tujuan:</label>
                            <select id="swal_class_id" class="form-control">
                                <option value="">-- Pilih Kelas --</option>
                                ${classOptions}
                            </select>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const classId = Swal.getPopup().querySelector('#swal_class_id').value;
                    if (!classId) {
                        Swal.showValidationMessage(`Silakan pilih kelas tujuan terlebih dahulu`);
                    }
                    return { classId: classId };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeBulkMove(result.value.classId);
                }
            });
        }

        function executeBulkMove(classId) {
            Swal.fire({ title: 'Memproses Pemindahan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                url: '{{ route("ppdb.bulk_move_to_student") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    phase_id: $('#filter_phase').val(),
                    type_id: $('#filter_type').val(),
                    class_group_id: classId
                },
                success: function(response) {
                    Swal.close();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message });
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan!' });
                }
            });
        }
    </script>
@endpush
