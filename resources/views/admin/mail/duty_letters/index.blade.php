@extends('layouts.app')

@section('title', 'Surat Tugas & SPPD')
@section('subtitle', 'Persuratan')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-briefcase mr-1"></i> Daftar Surat Tugas</h3>
                    <button onclick="addForm(`{{ route('duty-letters.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Buat Surat Tugas
                    </button>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>NO. SURAT</th>
                    <th>TGL SURAT</th>
                    <th>GURU/STAF</th>
                    <th>TUJUAN</th>
                    <th>TGL BERANGKAT</th>
                    <th width="15%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
</div>

<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">Form Surat Tugas</x-slot>

    @method('POST')
    <div class="form-group">
        <label>Pilih Guru/Staf <span class="text-danger">*</span></label>
        <select name="teacher_ids[]" id="teacher_ids" class="form-control select2" multiple style="width: 100%;">
            @foreach($teachers as $t)
                <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->nip ?? '-' }})</option>
            @endforeach
        </select>
        <small class="text-muted">Bisa memilih lebih dari satu guru</small>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Surat <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" name="letter_number" id="letter_number" class="form-control" placeholder="Contoh: 094/001/MTs-BH/V/2026">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-primary" onclick="generateNumber('DutyLetter', 'ST', '#letter_number')">
                            <i class="fas fa-sync-alt mr-1"></i> Generate
                        </button>
                    </div>
                </div>
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
        <label>Maksud Tugas / Perihal <span class="text-danger">*</span></label>
        <textarea name="purpose" class="form-control" rows="2" placeholder="Contoh: Mengikuti Bimbingan Teknis Kurikulum Merdeka"></textarea>
    </div>

    <div class="form-group">
        <label>Tempat Tujuan <span class="text-danger">*</span></label>
        <input type="text" name="destination" class="form-control" placeholder="Contoh: Aula Kemenag Kab. Situbondo">
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Berangkat <span class="text-danger">*</span></label>
                <input type="date" name="departure_date" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="return_date" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Alat Transportasi</label>
                <input type="text" name="transportation" class="form-control" placeholder="Contoh: Kendaraan Umum / Dinas">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Sumber Anggaran</label>
                <input type="text" name="budget_source" class="form-control" placeholder="Contoh: Dana BOS">
            </div>
        </div>
    </div>

    <hr>
    <h5><i class="fas fa-file-signature mr-1"></i> Penandatangan</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="signer_name" class="form-control" value="{{ $mailSetting->default_signer_name ?? '' }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="signer_position" class="form-control" value="{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="signer_nip" class="form-control" value="{{ $mailSetting->default_signer_nip ?? '' }}">
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
            ajax: { url: '{{ route("duty-letters.data") }}' },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'letter_number' },
                { data: 'letter_date' },
                { data: 'teacher_list' },
                { data: 'destination' },
                { data: 'departure_date' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });

        $('#teacher_ids').select2({ theme: 'bootstrap4', dropdownParent: $('#modal-form') });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Buat Surat Tugas Baru');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        
        // Re-fill defaults
        $('#modal-form [name=signer_name]').val(`{{ $mailSetting->default_signer_name ?? '' }}`);
        $('#modal-form [name=signer_position]').val(`{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}`);
        $('#modal-form [name=signer_nip]').val(`{{ $mailSetting->default_signer_nip ?? '' }}`);
        
        $('#teacher_ids').val(null).trigger('change');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(response => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Surat Tugas');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(response.data);
            $('#teacher_ids').val(response.teacher_ids).trigger('change');
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
