@extends('layouts.app')

@section('title', 'Surat Keluar')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-envelope-open-text mr-1"></i> Daftar Surat Keluar</h3>
                    <button onclick="addForm(`{{ route('outgoing-mails.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Buat Surat Baru
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NO. SURAT</th>
                    <th>TGL SURAT</th>
                    <th>PERIHAL</th>
                    <th>TUJUAN</th>
                    <th width="12%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

{{-- MODAL --}}
<x-modal size="modal-xl" data-backdrop="static">
    <x-slot name="title">Form Surat Keluar</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Surat <span class="text-danger">*</span></label>
                <input type="text" name="mail_number" class="form-control" placeholder="Contoh: 001/MTs-BH/V/2026">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" name="mail_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Perihal <span class="text-danger">*</span></label>
        <input type="text" name="mail_subject" class="form-control" placeholder="Contoh: Undangan Rapat Orang Tua">
    </div>
    <div class="form-group">
        <label>Tujuan / Penerima <span class="text-danger">*</span></label>
        <input type="text" name="mail_recipient" class="form-control" placeholder="Contoh: Bapak/Ibu Wali Murid Kelas IX">
    </div>
    <div class="form-group">
        <label>Isi Surat <span class="text-danger">*</span></label>
        <textarea id="mail_content" name="mail_content"></textarea>
    </div>
    <hr>
    <h5><i class="fas fa-file-signature mr-1"></i> Penandatangan</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="signer_name" class="form-control" value="{{ $mailSetting->default_signer_name ?? '' }}" placeholder="Nama Penandatangan">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="signer_position" class="form-control" value="{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}" placeholder="Jabatan">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="signer_nip" class="form-control" value="{{ $mailSetting->default_signer_nip ?? '' }}" placeholder="NIP">
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Simpan Surat
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')

@push('css')
<style>
    .ck-editor__editable_inline { min-height: 300px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    let table;
    let editor;

    $(function() {
        ClassicEditor.create(document.querySelector('#mail_content')).then(newEditor => {
            editor = newEditor;
        });

        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { url: '{{ route("outgoing-mails.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'mail_number' },
                { data: 'mail_date' },
                { data: 'mail_subject' },
                { data: 'mail_recipient' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Keluar Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        if(editor) editor.setData('');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Keluar');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
            if(editor) editor.setData(response.data.mail_content);
        });
    }

    function submitForm(form) {
        if(editor) document.querySelector('#mail_content').value = editor.getData();
        
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
