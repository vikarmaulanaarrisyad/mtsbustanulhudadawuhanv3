@extends($layout)

@section('title', 'Konfigurasi Mata Pelajaran Nilai')
@section('subtitle', 'Pengolahan Nilai')

@section('content')
<div class="row">
    <div class="col-md-8">
        <x-card>
            <x-slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-cog mr-1"></i> Konfigurasi Mata Pelajaran</h3>
                    <div class="d-flex align-items-center">
                        <select id="filter-level" class="form-control form-control-sm mr-2" style="width: 150px;">
                            <option value="">Semua Jenjang</option>
                            <option value="MI">MI</option>
                            <option value="MTs">MTs</option>
                            <option value="MA">MA</option>
                        </select>
                        <button onclick="addForm(`{{ route('grade-settings.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Tambah
                        </button>
                    </div>
                </div>
            </x-slot>

            <x-table>
                <x-slot name="thead">
                    <th width="5%">NO</th>
                    <th>JENJANG</th>
                    <th>TIPE NILAI</th>
                    <th>MATA PELAJARAN</th>
                    <th>URUTAN</th>
                    <th width="10%">AKSI</th>
                </x-slot>
            </x-table>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-percentage mr-1"></i> Bobot Penilaian Akhir</h3>
            </x-slot>
            
            <form id="weight-form" action="{{ route('grade-settings.update_weights') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Bobot Rata-rata Raport (%)</label>
                    <input type="number" name="weight_raport" class="form-control" value="{{ $setting->weight_raport ?? 60 }}" min="0" max="100">
                    <small class="text-muted">Biasanya 60%</small>
                </div>
                <div class="form-group">
                    <label>Bobot Ujian Madrasah (%)</label>
                    <input type="number" name="weight_exam" class="form-control" value="{{ $setting->weight_exam ?? 40 }}" min="0" max="100">
                    <small class="text-muted">Biasanya 40%</small>
                </div>
                <div class="alert alert-info">
                    Total bobot harus 100%.
                </div>
                <button type="submit" class="btn btn-primary btn-block">Simpan Bobot</button>
            </form>
        </x-card>
    </div>
</div>

<x-modal>
    <x-slot name="title">Form Konfigurasi Mapel Nilai</x-slot>

    @method('POST')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Jenjang <span class="text-danger">*</span></label>
                <select name="level" class="form-control" required>
                    <option value="MI">MI (Madrasah Ibtidaiyah)</option>
                    <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                    <option value="MA">MA (Madrasah Aliyah)</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tipe Nilai <span class="text-danger">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="raport">Nilai Raport</option>
                    <option value="ujian_madrasah">Ujian Madrasah</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Mata Pelajaran <span class="text-danger">*</span></label>
        <select name="subject_id" class="form-control select2" required style="width: 100%">
            <option value="">-- Pilih Mata Pelajaran --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Urutan Tampilan</label>
        <input type="number" name="order" class="form-control" placeholder="0" value="0">
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary" id="submitBtn">Simpan</button>
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
            ajax: { 
                url: '{{ route("grade-settings.data") }}',
                data: function(d) {
                    d.level = $('#filter-level').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false },
                { data: 'level' },
                { data: 'type_badge' },
                { data: 'subject_name' },
                { data: 'order' },
                { data: 'action', searchable: false, sortable: false },
            ]
        });

        $('#filter-level').on('change', function() {
            table.ajax.reload();
        });

        $('#weight-form').on('submit', function(e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serialize())
                .done(response => {
                    Swal.fire('Berhasil', response.message, 'success');
                })
                .fail(xhr => {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                });
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        $('.select2').val(null).trigger('change');
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

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Konfigurasi?',
            text: 'Apakah Anda yakin ingin menghapus konfigurasi mapel ' + name + '?',
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
