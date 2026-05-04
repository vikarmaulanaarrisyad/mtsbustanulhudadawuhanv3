@extends($layout)

@section('title', 'Konfigurasi Mata Pelajaran Nilai')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-slate overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-cogs mr-2 animate__animated animate__fadeInLeft text-teal"></i> 
                            Konfigurasi Mesin Penilaian
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur struktur mata pelajaran yang muncul pada raport dan tentukan rasio bobot perhitungan nilai akhir kelulusan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tools fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT PANEL: SUBJECT CONFIGURATION -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-2 mb-md-0 font-weight-bold text-dark">
                    <i class="fas fa-list-ol mr-2 text-teal"></i> Struktur Mapel Penilaian
                </h4>
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <div class="input-group-premium bg-light" style="width: 160px; height: 38px;">
                        <i class="fas fa-filter text-muted"></i>
                        <select id="filter-level" class="form-control border-0 bg-transparent font-weight-bold text-sm">
                            <option value="">Semua Jenjang</option>
                            <option value="MI">MI</option>
                            <option value="MTs">MTs</option>
                            <option value="MA">MA</option>
                        </select>
                    </div>
                    <button onclick="addForm(`{{ route('grade-settings.store') }}`)" class="btn btn-teal rounded-pill font-weight-bold shadow-sm px-4" style="height: 38px;">
                        <i class="fas fa-plus-circle mr-1"></i> TAMBAH
                    </button>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="setting-table" style="width:100%">
                        <thead class="bg-light-slate text-uppercase">
                            <tr>
                                <th width="50px" class="text-center py-3">NO</th>
                                <th>JENJANG</th>
                                <th>TIPE NILAI</th>
                                <th>MATA PELAJARAN</th>
                                <th class="text-center">URUTAN</th>
                                <th width="100px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: WEIGHT CONFIGURATION -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 premium-card bg-gradient-dark text-white mb-4 position-relative overflow-hidden">
            <div class="card-header bg-transparent py-4 border-bottom border-secondary position-relative" style="z-index:2;">
                <h4 class="mb-0 font-weight-bold text-white">
                    <i class="fas fa-balance-scale mr-2 text-teal"></i> Bobot Nilai Kelulusan
                </h4>
            </div>
            
            <div class="card-body p-4 position-relative" style="z-index:2;">
                <form id="weight-form" action="{{ route('grade-settings.update_weights') }}" method="POST">
                    @csrf
                    
                    <div class="weight-box bg-white-10 rounded-15 p-3 mb-3 border border-secondary">
                        <label class="text-xs font-weight-bold text-teal uppercase mb-2"><i class="fas fa-book-reader mr-1"></i> Rata-rata Raport</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="weight_raport" class="form-control form-control-lg bg-transparent text-white font-weight-bold border-0 p-0" value="{{ $setting->weight_raport ?? 60 }}" min="0" max="100" style="font-size: 2.5rem; height: auto; width: 80px;">
                            <span class="text-white-50 font-weight-bold text-xl">%</span>
                        </div>
                        <small class="text-muted d-block mt-1">Rekomendasi sistem: 60%</small>
                    </div>

                    <div class="weight-box bg-white-10 rounded-15 p-3 mb-4 border border-secondary">
                        <label class="text-xs font-weight-bold text-fuchsia uppercase mb-2"><i class="fas fa-award mr-1"></i> Ujian Madrasah</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="weight_exam" class="form-control form-control-lg bg-transparent text-white font-weight-bold border-0 p-0" value="{{ $setting->weight_exam ?? 40 }}" min="0" max="100" style="font-size: 2.5rem; height: auto; width: 80px;">
                            <span class="text-white-50 font-weight-bold text-xl">%</span>
                        </div>
                        <small class="text-muted d-block mt-1">Rekomendasi sistem: 40%</small>
                    </div>

                    <div class="alert bg-white-5 border-0 rounded-10 text-white-50 small mb-4">
                        <i class="fas fa-info-circle text-teal mr-1"></i> Total akumulasi kedua bobot harus tepat <strong>100%</strong> untuk kalkulasi SKL.
                    </div>

                    <button type="submit" class="btn btn-teal btn-block rounded-pill font-weight-bold shadow-teal py-3 text-lg">
                        <i class="fas fa-sync-alt mr-2"></i> PERBARUI BOBOT
                    </button>
                </form>
            </div>
            <i class="fas fa-percent position-absolute text-white" style="opacity: 0.03; font-size: 15rem; bottom: -20px; right: -20px; z-index:1;"></i>
        </div>
    </div>
</div>

<!-- PREMIUM MODAL -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content border-0 shadow-lg-premium rounded-20">
            <form action="" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                
                <div class="modal-header bg-gradient-slate text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-cog mr-2 text-teal"></i> Form Konfigurasi Mapel
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">Jenjang <span class="text-danger">*</span></label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-layer-group"></i>
                                <select name="level" class="form-control border-0" required>
                                    <option value="MI">MI (Madrasah Ibtidaiyah)</option>
                                    <option value="MTs">MTs (Madrasah Tsanawiyah)</option>
                                    <option value="MA">MA (Madrasah Aliyah)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">Tipe Penilaian <span class="text-danger">*</span></label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-tags"></i>
                                <select name="type" class="form-control border-0" required>
                                    <option value="raport">Nilai Raport</option>
                                    <option value="ujian_madrasah">Ujian Madrasah</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">Mata Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-book"></i>
                            <select name="subject_id" class="form-control select2 border-0" required style="width: 100%">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Urutan Tampilan pada Dokumen</label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-sort-numeric-down"></i>
                            <input type="number" name="order" class="form-control font-weight-bold text-lg" placeholder="0" value="0">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" class="btn btn-teal rounded-pill px-5 font-weight-bold shadow-teal text-white" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Premium Slate/Teal Design System */
    .bg-gradient-slate { background: linear-gradient(135deg, #334155 0%, #0f172a 100%) !important; }
    .bg-gradient-dark { background: linear-gradient(135deg, #1e293b 0%, #020617 100%) !important; }
    .bg-light-slate { background: #f8fafc; color: #475569; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    
    .btn-teal { background: #0d9488; color: #fff; border: none; }
    .btn-teal:hover { background: #0f766e; color: #fff; }
    .text-teal { color: #14b8a6; }
    .text-fuchsia { color: #d946ef; }
    .bg-white-10 { background: rgba(255,255,255,0.05); }
    .bg-white-5 { background: rgba(255,255,255,0.02); }
    .shadow-teal { box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.4)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .rounded-10 { border-radius: 10px; }
    .bg-light-soft { background: #f1f5f9; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Weight Inputs override */
    .weight-box input[type="number"]:focus { outline: none; border-bottom: 2px solid #14b8a6 !important; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 45px;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%; height: 100%; font-weight: 600;
    }
    .input-group-premium:focus-within { border-color: #0d9488; box-shadow: 0 0 10px rgba(13, 148, 136, 0.1); }
    .input-group-premium:focus-within i { color: #0d9488; }

    /* Table Enhancements */
    #setting-table { border-collapse: separate; border-spacing: 0 8px; }
    #setting-table tbody tr { background: #fff; transition: all 0.2s ease; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    #setting-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #f8fafc; }
    #setting-table td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
    #setting-table td:first-child { border-radius: 10px 0 0 10px; border-left: 1px solid #f1f5f9; font-weight: bold; color: #475569; }
    #setting-table td:last-child { border-radius: 0 10px 10px 0; border-right: 1px solid #f1f5f9; }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;

    $(function() {
        table = $('#setting-table').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari mapel...", search: "" },
            ajax: { 
                url: '{{ route("grade-settings.data") }}',
                data: function(d) {
                    d.level = $('#filter-level').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', searchable: false, sortable: false, className: 'text-center' },
                { 
                    data: 'level',
                    render: function(data) { return `<span class="badge badge-light border px-3 py-2 text-dark font-weight-bold shadow-sm">${data}</span>`; }
                },
                { data: 'type_badge' },
                { 
                    data: 'subject_name',
                    render: function(data) { return `<span class="font-weight-bold text-dark">${data}</span>`; }
                },
                { data: 'order', className: 'text-center font-weight-bold text-teal text-lg' },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#filter-level').on('change', function() {
            table.ajax.reload();
        });

        $('#weight-form').on('submit', function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...').prop('disabled', true);
            
            $.post($(this).attr('action'), $(this).serialize())
                .done(response => {
                    Swal.fire({ icon: 'success', title: 'Bobot Disimpan', text: response.message, timer: 2000, showConfirmButton: false });
                })
                .fail(xhr => {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Pastikan total bobot bernilai 100%', 'error');
                })
                .always(() => {
                    btn.html('<i class="fas fa-sync-alt mr-2"></i> PERBARUI BOBOT').prop('disabled', false);
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
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MEMPROSES...');
        $.post($(form).attr('action'), $(form).serialize())
            .done(response => {
                $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500, showConfirmButton: false });
            })
            .fail(xhr => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem' });
            })
            .always(() => $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN'));
    }

    function deleteData(url, name) {
        Swal.fire({
            title: 'Hapus Konfigurasi?',
            text: 'Yakin ingin menghapus mapel ' + name + ' dari daftar penilaian?',
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#e3342f', confirmButtonText: 'Iya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(response => {
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Terhapus', text: response.message, timer: 1500, showConfirmButton: false });
                });
            }
        });
    }
</script>
@endpush
