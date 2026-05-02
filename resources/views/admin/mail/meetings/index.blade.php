@extends('layouts.app')

@section('title', 'Surat Undangan Rapat')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Daftar Undangan Rapat</h3>
                    <button onclick="addForm(`{{ route('school-meetings.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Buat Undangan Baru
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NO. SURAT</th>
                    <th>TGL RAPAT</th>
                    <th>PERIHAL</th>
                    <th>TUJUAN</th>
                    <th width="12%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

{{-- MODAL --}}
<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">Form Surat Undangan</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Surat <span class="text-danger">*</span></label>
                <input type="text" name="meeting_number" class="form-control" placeholder="Contoh: 005/MTs-BH/V/2026">
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
        <input type="text" name="meeting_subject" class="form-control" placeholder="Contoh: Undangan Rapat Wali Murid Kelas VII">
    </div>
    <div class="form-group">
        <label>Tujuan Penerima <span class="text-danger">*</span></label>
        <input type="text" name="recipient_description" class="form-control" placeholder="Contoh: Bapak/Ibu Wali Murid / Dewan Guru">
    </div>

    <hr>
    <h5><i class="fas fa-clock mr-1"></i> Detail Waktu & Tempat</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Tanggal Rapat <span class="text-danger">*</span></label>
                <input type="date" name="meeting_date" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Waktu (Jam) <span class="text-danger">*</span></label>
                <input type="time" name="meeting_time" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Tempat <span class="text-danger">*</span></label>
                <input type="text" name="meeting_place" class="form-control" placeholder="Contoh: Aula Madrasah">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Agenda Rapat <span class="text-danger">*</span></label>
        <textarea name="meeting_agenda" class="form-control" rows="2" placeholder="Contoh: Pembahasan Persiapan Ujian Semester"></textarea>
    </div>

    <hr>
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
            <i class="fas fa-save mr-1"></i> Simpan & Cetak
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
    </x-slot>
</x-modal>
@endsection

@include('includes.datatable')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            ajax: { url: '{{ route("school-meetings.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'meeting_number' },
                { data: 'meeting_date' },
                { data: 'meeting_subject' },
                { data: 'recipient_description' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Undangan Rapat Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');

        // Re-fill defaults after reset
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Undangan Rapat');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
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
            title: 'Hapus Undangan?',
            text: 'Apakah Anda yakin ingin menghapus undangan nomor ' + number + '?',
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
