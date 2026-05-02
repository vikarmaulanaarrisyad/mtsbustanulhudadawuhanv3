@extends($layout)

@section('title', 'Surat Keterangan Siswa Aktif')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-file-signature mr-1"></i> Daftar Surat Keterangan</h3>
                    <button onclick="addForm(`{{ route('student-certificates.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Buat Surat Baru
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NO. SURAT</th>
                    <th>TGL SURAT</th>
                    <th>NAMA SISWA</th>
                    <th>KEPERLUAN</th>
                    <th width="12%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

{{-- MODAL --}}
<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">Form Surat Keterangan</x-slot>

    @method('POST')
    <div class="form-group">
        <label>Pilih Siswa <span class="text-danger">*</span></label>
        <select name="student_id" class="form-control select2" style="width: 100%;">
            <option value="">-- Pilih Siswa --</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nisn }})</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Surat <span class="text-danger">*</span></label>
                <input type="text" name="certificate_number" class="form-control" placeholder="Contoh: 421.3/001/MTs-BH/V/2026">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="certificate_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Keperluan <span class="text-danger">*</span></label>
        <input type="text" name="purpose" class="form-control" placeholder="Contoh: Pengurusan Tunjangan Anak / Paspor">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Penandatangan (Nama)</label>
                <input type="text" name="signer_name" class="form-control" value="{{ $mailSetting->default_signer_name ?? '' }}" placeholder="KEPALA MADRASAH">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>NIP Penandatangan</label>
                <input type="text" name="signer_nip" class="form-control" value="{{ $mailSetting->default_signer_nip ?? '' }}" placeholder="Contoh: 1980...">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Simpan & Cetak
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { url: '{{ route("student-certificates.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'certificate_number' },
                { data: 'certificate_date' },
                { data: 'student_name' },
                { data: 'purpose' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });

        $('.select2').select2({ theme: 'bootstrap4', dropdownParent: $('#modal-form') });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Keterangan Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        $('.select2').val('').trigger('change');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Keterangan');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
            $('.select2').val(response.data.student_id).trigger('change');
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            })
            .fail(xhr => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            })
            .always(() => $('#submitBtn').prop('disabled', false));
    }

    function deleteData(url, number) {
        Swal.fire({
            title: 'Hapus Surat?',
            text: 'Apakah Anda yakin ingin menghapus surat nomor ' + number + '?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message });
                });
            }
        });
    }
</script>
@endpush
