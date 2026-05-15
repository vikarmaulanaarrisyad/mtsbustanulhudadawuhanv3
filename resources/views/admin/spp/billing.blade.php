@extends($layout)

@section('title', 'Tagihan & Pembayaran SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-success overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> Tagihan & Pembayaran SPP
                        </h2>
                        <p class="mb-0 opacity-8 text-lg">Kelola tagihan bulanan siswa dan catat pembayaran masuk secara real-time.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-white btn-premium px-4 shadow-sm" data-toggle="modal" data-target="#modal-generate">
                            <i class="fas fa-magic mr-1"></i> GENERATE TAGIHAN
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="card shadow-sm border-0 premium-card mb-4">
    <div class="card-body p-3">
        <form id="form-filter">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Kelas</label>
                        <select name="class_group_id" class="form-control rounded-pill border-2 filter-input">
                            <option value="">Semua Kelas</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}">{{ $cg->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Bulan</label>
                        <select name="month" class="form-control rounded-pill border-2 filter-input">
                            <option value="">Semua Bulan</option>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Tahun</label>
                        <select name="year" class="form-control rounded-pill border-2 filter-input">
                            @for($y=date('Y'); $y>=date('Y')-2; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Status</label>
                        <select name="status" class="form-control rounded-pill border-2 filter-input">
                            <option value="">Semua Status</option>
                            <option value="Unpaid">Belum Lunas</option>
                            <option value="Partial">Sebagian</option>
                            <option value="Paid">Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success btn-block rounded-pill font-weight-bold shadow-sm" onclick="table.ajax.reload()">
                        <i class="fas fa-search mr-1"></i> CARI DATA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="table-billing">
                        <thead>
                            <tr class="bg-light">
                                <th width="30">#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Periode</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Generate -->
<div class="modal fade" id="modal-generate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Generate Tagihan Otomatis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-generate">
                @csrf
                <div class="modal-body p-4 text-center">
                    <i class="fas fa-magic fa-4x text-success mb-4 opacity-5"></i>
                    <p class="text-muted mb-4">Sistem akan membuat tagihan SPP untuk <b>seluruh siswa aktif</b> berdasarkan tarif yang telah diatur.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-left">
                                <label class="text-xs font-weight-bold">Tahun Pelajaran</label>
                                <select name="academic_year_id" class="form-control rounded-pill border-2">
                                    @foreach($academicYears as $ay)
                                        <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left">
                                <label class="text-xs font-weight-bold">Bulan</label>
                                <select name="month" class="form-control rounded-pill border-2">
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left">
                                <label class="text-xs font-weight-bold">Tahun</label>
                                <input type="number" name="year" class="form-control rounded-pill border-2" value="{{ date('Y') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-success btn-block rounded-pill py-3 font-weight-bold shadow-success">
                        MULAI GENERATE SEKARANG
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pay -->
<div class="modal fade" id="modal-pay" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Input Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-pay">
                @csrf
                <input type="hidden" name="spp_billing_id" id="pay-billing-id">
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-xl mb-4 d-flex justify-content-between align-items-center">
                        <span class="text-sm font-weight-bold text-muted">SISA TAGIHAN</span>
                        <h4 class="mb-0 font-weight-bold text-danger" id="pay-remaining-text">Rp 0</h4>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Jumlah Bayar (Rp)</label>
                        <input type="text" id="pay-amount-display" class="form-control rounded-pill border-2 form-control-lg font-weight-bold" placeholder="0" required onkeyup="formatRupiah(this)">
                        <input type="hidden" name="amount" id="pay-amount">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Bayar</label>
                                <input type="date" name="payment_date" class="form-control rounded-pill border-2" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Metode</label>
                                <select name="payment_method" class="form-control rounded-pill border-2">
                                    <option value="Cash">Tunai / Cash</option>
                                    <option value="Transfer">Transfer Bank</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Catatan</label>
                        <input type="text" name="notes" class="form-control rounded-pill border-2" placeholder="Contoh: Lunas, Cicilan 1, dll">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold shadow-primary">
                        KONFIRMASI PEMBAYARAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Bill -->
<div class="modal fade" id="modal-edit-bill" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Edit Nominal Tagihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-bill">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-bill-id">
                <div class="modal-body p-4">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nominal Tagihan (Rp)</label>
                        <input type="number" name="amount" id="edit-bill-amount" class="form-control rounded-pill border-2 form-control-lg font-weight-bold" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-warning btn-block rounded-pill py-3 font-weight-bold shadow-warning">
                        UPDATE TAGIHAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal History -->
<div class="modal fade" id="modal-history" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title font-weight-bold">Riwayat Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tgl Bayar</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Penerima</th>
                                <th>No Kwitansi</th>
                            </tr>
                        </thead>
                        <tbody id="history-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
    .btn-white { background: #fff; color: #1cc88a; font-weight: bold; border-radius: 50px; }
    .bg-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -100px; right: -50px; z-index: 0; }
    .premium-card { border-radius: 20px; }
    .rounded-xl { border-radius: 15px; }
    .shadow-success { box-shadow: 0 4px 15px rgba(28,200,138,0.4); }
    .shadow-primary { box-shadow: 0 4px 15px rgba(78,115,223,0.4); }

    #table-billing { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #table-billing thead th { 
        font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; color: #507b8f; 
        border: none; padding: 12px 15px;
    }
    #table-billing tbody tr { 
        background: #fff; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.03); 
        transition: all 0.2s ease;
    }
    #table-billing tbody tr:hover { transform: scale(1.005); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    #table-billing td { 
        border: none; padding: 1.2rem 1rem; vertical-align: middle; 
        background: #fff;
    }
    #table-billing td:first-child { border-radius: 10px 0 0 10px; }
    #table-billing td:last-child { border-radius: 0 10px 10px 0; }

    /* Custom DataTables Styling */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 50px; padding: 8px 20px; border: 2px solid #f1f3f9;
        width: 250px !important; transition: all 0.3s;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #1cc88a; box-shadow: 0 0 0 0.2rem rgba(28,200,138,0.1); }
    .dataTables_wrapper .dataTables_length select { border-radius: 50px; padding: 5px 15px; border: 2px solid #f1f3f9; }
    .page-item.active .page-link { background-color: #1cc88a; border-color: #1cc88a; border-radius: 10px; }
    .page-link { border-radius: 10px; margin: 0 3px; border: none; color: #1cc88a; font-weight: 600; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<script>
    let table = $('#table-billing').DataTable({
        processing: true,
        serverSide: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari nama siswa...",
            lengthMenu: "_MENU_",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ tagihan",
            paginate: {
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        },
        dom: "<'row mb-3'<'col-md-6'l><'col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
        ajax: {
            url: "{{ route('admin.spp.billing.data') }}",
            data: function(d) {
                d.class_group_id = $('select[name=class_group_id]').val();
                d.month = $('select[name=month]').val();
                d.year = $('select[name=year]').val();
                d.status = $('select[name=status]').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'student_name', name: 'student_name'},
            {data: 'class_name', name: 'class_name'},
            {data: 'month', name: 'month'},
            {data: 'amount', name: 'amount'},
            {data: 'paid', name: 'paid'},
            {data: 'status_badge', name: 'status_badge'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#form-generate').on('submit', function(e) {
        e.preventDefault();
        let btn = $(this).find('button[type=submit]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> GENERATING...');
        
        $.post("{{ route('admin.spp.billing.generate') }}", $(this).serialize(), function(res) {
            $('#modal-generate').modal('hide');
            table.ajax.reload();
            Swal.fire('Berhasil', res.message, 'success');
        }).fail(err => {
            Swal.fire('Error', err.responseJSON.message || 'Gagal generate', 'error');
        }).always(() => {
            btn.prop('disabled', false).text('MULAI GENERATE SEKARANG');
        });
    });

    function payModal(id, remaining) {
        $('#pay-billing-id').val(id);
        $('#pay-amount').val(remaining);
        $('#pay-amount-display').val(new Intl.NumberFormat('id-ID').format(remaining));
        $('#pay-remaining-text').text('Rp ' + new Intl.NumberFormat('id-ID').format(remaining));
        $('#modal-pay').modal('show');
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
            $('#pay-amount').val(value);
        } else {
            input.value = '';
            $('#pay-amount').val('');
        }
    }

    $('#form-pay').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('admin.spp.payment.store') }}", $(this).serialize(), function(res) {
            $('#modal-pay').modal('hide');
            table.ajax.reload();
            Swal.fire('Berhasil', res.message, 'success');
        }).fail(err => {
            Swal.fire('Error', err.responseJSON.message || 'Gagal simpan', 'error');
        });
    });

    function editBill(id, amount) {
        $('#edit-bill-id').val(id);
        $('#edit-bill-amount').val(amount);
        $('#modal-edit-bill').modal('show');
    }

    $('#form-edit-bill').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit-bill-id').val();
        $.ajax({
            url: "{{ url('admin/spp/billing') }}/" + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#modal-edit-bill').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil', res.message, 'success');
            },
            error: function(err) {
                Swal.fire('Error', err.responseJSON.message || 'Gagal update', 'error');
            }
        });
    });

    function viewHistory(id) {
        $.get("{{ url('admin/spp/payment-history') }}/" + id, function(res) {
            let html = '';
            res.data.forEach(p => {
                html += `
                    <tr>
                        <td>${p.payment_date}</td>
                        <td>Rp ${new Intl.NumberFormat('id-ID').format(p.amount)}</td>
                        <td>${p.payment_method}</td>
                        <td>${p.receiver.name}</td>
                        <td><small class="font-weight-bold">${p.receipt_number}</small></td>
                    </tr>
                `;
            });
            if (res.data.length == 0) html = '<tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pembayaran</td></tr>';
            $('#history-body').html(html);
            $('#modal-history').modal('show');
        });
    }
</script>
@endpush
