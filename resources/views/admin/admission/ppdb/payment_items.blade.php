@extends('layouts.app')

@section('title', 'Master Biaya PPDB')
@section('subtitle', 'Rincian Biaya Daftar Ulang')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">PPDB</li>
    <li class="breadcrumb-item active">Master Biaya</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex" style="gap:8px;">
                            <select id="filter_year" class="form-control form-control-sm" style="width:200px;">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $activeYear && $activeYear->id == $year->id ? 'selected' : '' }}>
                                        Tahun Ajaran {{ $year->academic_year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button onclick="addForm()" class="btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Item Biaya
                        </button>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">NO</th>
                        <th>ITEM BIAYA</th>
                        <th>NOMINAL</th>
                        <th>TAHUN AJARAN</th>
                        <th>STATUS</th>
                        <th>KETERANGAN</th>
                        <th width="10%">AKSI</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    {{-- MODAL FORM --}}
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="form-horizontal">
                @csrf
                @method('post')
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Tambah Item Biaya</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Item Biaya</label>
                            <input type="text" name="item_name" class="form-control" placeholder="Contoh: Seragam Olahraga" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nominal (Rp)</label>
                            <input type="text" id="amount_mask" class="form-control" placeholder="0" required>
                            <input type="hidden" name="amount" id="amount_real">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tahun Ajaran</label>
                            <select name="academic_year_id" class="form-control" required>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Keterangan (Opsional)</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Informasi tambahan..."></textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">Aktifkan Item Ini</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('includes.datatable')

@push('scripts')
<script>
    let table;

    $(function() {
        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("ppdb.payment_items_data") }}',
                data: function(d) {
                    d.academic_year_id = $('#filter_year').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'item_name'},
                {data: 'amount'},
                {data: 'academic_year.academic_year'},
                {
                    data: 'is_active',
                    render: function(data) {
                        return data ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non-Aktif</span>';
                    }
                },
                {data: 'description', defaultContent: '-'},
                {data: 'action', searchable: false, sortable: false}
            ]
        });

        $('#filter_year').change(function() {
            table.ajax.reload();
        });

        $('#modal-form form').on('submit', function(e) {
            e.preventDefault();
            
            // Bersihkan format titik sebelum kirim
            let rawAmount = $('#amount_mask').val().replace(/\./g, '');
            $('#amount_real').val(rawAmount);
            
            $('#submitBtn').prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 2000 });
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });

        // Event listener untuk format rupiah
        $('#amount_mask').on('keyup', function() {
            $(this).val(formatRupiah($(this).val()));
        });
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function addForm() {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Item Biaya');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', '{{ route("ppdb.payment_items_store") }}');
        $('#modal-form [name=_method]').val('post');
    }

    function editData(id) {
        $.get(`{{ url('/admin/admission/ppdb/payment-items') }}/${id}`)
            .done(response => {
                let d = response.data;
                $('#modal-form').modal('show');
                $('#modal-form .modal-title').text('Edit Item Biaya');
                $('#modal-form form').attr('action', `{{ url('/admin/admission/ppdb/payment-items') }}/${id}`);
                $('#modal-form [name=_method]').val('put');
                
                $('#modal-form [name=item_name]').val(d.item_name);
                // Gunakan Math.floor untuk membuang desimal .00 agar tidak jadi jutaan
                $('#amount_mask').val(Math.floor(d.amount)).trigger('keyup');
                $('#modal-form [name=academic_year_id]').val(d.academic_year_id);
                $('#modal-form [name=description]').val(d.description);
                $('#modal-form [name=is_active]').prop('checked', d.is_active);
            });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/admin/admission/ppdb/payment-items') }}/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, showConfirmButton: false, timer: 2000 });
                    }
                });
            }
        });
    }
</script>
@endpush
