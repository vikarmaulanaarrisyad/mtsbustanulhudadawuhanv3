@extends('layouts.app')

@section('title', 'Surat Keterangan Siswa Aktif')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-file-signature mr-1"></i> Daftar Surat Keterangan Aktif</h3>
                    <div class="btn-group">
                        <button onclick="addForm(`{{ route('active-statements.store') }}`, 'individual')" class="btn btn-sm btn-primary">
                            <i class="fas fa-user mr-1"></i> Buat Surat Individu
                        </button>
                        <button onclick="addForm(`{{ route('active-statements.store') }}`, 'collective')" class="btn btn-sm btn-success">
                            <i class="fas fa-users mr-1"></i> Buat Surat Kolektif
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NO. SURAT</th>
                    <th>TGL SURAT</th>
                    <th>TIPE</th>
                    <th>SISWA</th>
                    <th>KEPERLUAN</th>
                    <th width="12%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

{{-- MODAL --}}
<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">Form Surat Keterangan Aktif</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Tipe Surat <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-control" onchange="toggleType()">
                    <option value="individual">Individu (1 Siswa)</option>
                    <option value="collective">Kolektif (Banyak Siswa)</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Pilih Siswa <span class="text-danger">*</span></label>
                <select name="student_ids[]" id="student_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->nis }})</option>
                    @endforeach
                </select>
                <small class="text-muted" id="student_hint">Pilih 1 siswa untuk tipe Individu.</small>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Surat <span class="text-danger">*</span></label>
                <input type="text" name="letter_number" class="form-control" placeholder="Contoh: 421.3/001/MTs-BH/V/2026">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="letter_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label>Keperluan <span class="text-danger">*</span></label>
        <input type="text" name="purpose" class="form-control" placeholder="Contoh: Pengurusan Tunjangan Anak / Paspor">
    </div>

    <hr>
    <h5>Informasi Penandatangan (Opsional)</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="signer_name" class="form-control" value="{{ $mailSetting->default_signer_name ?? '' }}" placeholder="Nama Kepala Madrasah">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="signer_position" class="form-control" value="{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}" placeholder="Kepala Madrasah">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="signer_nip" class="form-control" value="{{ $mailSetting->default_signer_nip ?? '' }}" placeholder="NIP Penandatangan">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Simpan
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
            ajax: { url: '{{ route("active-statements.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'letter_number' },
                { data: 'letter_date' },
                { data: 'type', render: (data) => data === 'individual' ? '<span class="badge badge-info">Individu</span>' : '<span class="badge badge-primary">Kolektif</span>' },
                { data: 'student_list' },
                { data: 'purpose' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });

        $('#student_ids').select2({ 
            theme: 'bootstrap4', 
            dropdownParent: $('#modal-form'),
            placeholder: '-- Pilih Siswa --'
        });
    });

    function toggleType() {
        let type = $('#type').val();
        if (type === 'individual') {
            $('#student_hint').text('Pilih 1 siswa untuk tipe Individu.');
            // Limit select2 to 1 if individual? 
            // Better to just let user select multiple but warn them, or handle in backend.
        } else {
            $('#student_hint').text('Anda bisa memilih lebih dari 1 siswa untuk tipe Kolektif.');
        }
    }

    function addForm(url, type = 'individual') {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Keterangan Aktif Baru (' + (type === 'individual' ? 'Individu' : 'Kolektif') + ')');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        $('#type').val(type);
        $('#student_ids').val(null).trigger('change');
        toggleType();
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Keterangan Aktif');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            
            loopForm(response.data);
            
            // If signer info is empty in the record, optionally keep it empty or fill with defaults
            // Usually for edit, we keep what's in the record.
            
            $('#student_ids').val(response.student_ids).trigger('change');
            toggleType();
        });
    }

    function submitForm(form) {
        let type = $('#type').val();
        let students = $('#student_ids').val();

        if (type === 'individual' && students.length > 1) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Tipe Individu hanya diperbolehkan memilih 1 siswa.' });
            return;
        }

        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                Swal.close();
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
            })
            .fail(xhr => {
                Swal.close();
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
