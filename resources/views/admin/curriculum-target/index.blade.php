@extends($layout)

@section('title', 'Target Kurikulum')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-bullseye mr-2 animate__animated animate__fadeInLeft"></i> 
                            Target Kurikulum
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan materi pembelajaran yang harus diselesaikan oleh guru dalam satu semester.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-tasks fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Filter Mata Pelajaran</label>
                            <select id="filter_subject" class="form-control select2bs4 rounded-pill border-2">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Filter Tahun Pelajaran</label>
                            <select id="filter_academic_year" class="form-control select2bs4 rounded-pill border-2">
                                <option value="">Semua Tahun</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}">{{ $ay->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Filter Semester</label>
                            <select id="filter_semester" class="form-control select2bs4 rounded-pill border-2">
                                <option value="">Semua Semester</option>
                                <option value="1">Ganjil</option>
                                <option value="2">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <button onclick="addForm(`{{ route('admin.curriculum-targets.store') }}`)" class="btn btn-indigo shadow-sm font-weight-bold px-4 btn-premium mt-3">
                            <i class="fas fa-plus-circle mr-1"></i> TAMBAH TARGET
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 font-weight-bold text-dark">Daftar Materi Kurikulum</h4>
                    <p class="text-muted text-sm mb-0">Rincian target penyampaian materi per mata pelajaran</p>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="targetTable" style="width:100%">
                        <thead class="bg-light-indigo text-uppercase">
                            <tr>
                                <th width="60px" class="text-center py-3">NO</th>
                                <th>MATA PELAJARAN</th>
                                <th>BAB</th>
                                <th>JUDUL MATERI</th>
                                <th width="100px">SMT</th>
                                <th width="120px" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PREMIUM MODAL FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-gradient-indigo text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">Form Target Kurikulum</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select name="subject_id" class="form-control select2bs4 rounded-pill px-3 border-2" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran <span class="text-danger">*</span></label>
                                    <select name="academic_year_id" class="form-control rounded-pill px-3 border-2" required>
                                        @foreach($academicYears as $ay)
                                            <option value="{{ $ay->id }}">{{ $ay->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Semester <span class="text-danger">*</span></label>
                                    <select name="semester" class="form-control rounded-pill px-3 border-2" required>
                                        <option value="1">Ganjil</option>
                                        <option value="2">Genap</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Nomor BAB</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-list-ol"></i>
                                        <input type="text" name="chapter_number" class="form-control" placeholder="Contoh: BAB 1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Judul Materi <span class="text-danger">*</span></label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-heading"></i>
                                        <input type="text" name="title" class="form-control" placeholder="Contoh: Pengenalan Aljabar" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">Deskripsi / Detail Materi</label>
                            <textarea name="description" class="form-control border-2 rounded-lg" rows="3" placeholder="Tambahkan rincian sub-materi jika ada..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN TARGET
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Design System - Indigo */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%) !important; }
    .bg-light-indigo { background: #eef2ff; color: #4338ca; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; }
    .btn-indigo { background: #6366f1; color: #fff; }
    .btn-indigo:hover { background: #4338ca; color: #fff; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(99,102,241,0.3); }

    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-20 { border-radius: 20px; }
    .bg-light-soft { background: #f8fafc; }

    /* Table Styling */
    #targetTable { border-collapse: separate; border-spacing: 0 10px; }
    #targetTable tbody tr { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.2s ease; border-radius: 10px; }
    #targetTable tbody tr:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: #fcfdfd; }
    #targetTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #targetTable td:first-child { border-radius: 10px 0 0 10px; font-weight: bold; color: #6366f1; }
    #targetTable td:last-child { border-radius: 0 10px 10px 0; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #6366f1; box-shadow: 0 0 15px rgba(99,102,241,0.1); }
</style>
@endsection

@include('includes.datatable')
@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    let table;
    $(function() {
        table = $('#targetTable').DataTable({
            processing: true, serverSide: true, autoWidth: false,
            language: { searchPlaceholder: "Cari materi...", search: "" },
            ajax: { 
                url: '{{ route("admin.curriculum-targets.data") }}',
                data: function(d) {
                    d.subject_id = $('#filter_subject').val();
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.semester = $('#filter_semester').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-weight-bold' },
                { 
                    data: 'subject_name',
                    render: (data, type, row) => '<span class="font-weight-bold text-dark">' + data + '</span>'
                },
                { 
                    data: 'chapter_number',
                    render: (data) => '<span class="badge badge-light border text-indigo font-weight-bold px-3 py-2">' + (data || '-') + '</span>'
                },
                { 
                    data: 'title',
                    render: (data) => '<span class="text-muted">' + data + '</span>'
                },
                { 
                    data: 'semester',
                    className: 'text-center'
                },
                { data: 'action', searchable: false, sortable: false, className: 'text-center' },
            ]
        });

        $('#filter_subject, #filter_academic_year, #filter_semester').on('change', function() {
            table.ajax.reload();
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Target Kurikulum');
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('POST');
        resetForm('#modal-form form');
        $('#modal-form [name=subject_id]').val($('#filter_subject').val()).trigger('change');
    }

    function editForm(url) {
        Swal.fire({ title: 'Memuat...', didOpen: () => Swal.showLoading() });
        $.get(url).done(res => {
            Swal.close();
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Target Kurikulum');
            $('#modal-form form').attr('action', url.replace('/show', ''));
            $('#modal-form [name=_method]').val('PUT');
            loopForm(res.data);
            $('#modal-form [name=subject_id]').trigger('change');
        });
    }

    function submitForm(form) {
        $('#submitBtn').prop('disabled', true);
        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });
        $.post($(form).attr('action'), $(form).serialize())
            .done(res => {
                Swal.close(); $('#modal-form').modal('hide');
                table.ajax.reload();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false });
                $('#submitBtn').prop('disabled', false);
            })
            .fail(xhr => {
                Swal.close(); $('#submitBtn').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Error' });
            });
    }

    function deleteData(url, name) {
        Swal.fire({ title: 'Hapus Target?', text: 'Yakin ingin menghapus target ' + name + '?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'DELETE', data: { _token: '{{ csrf_token() }}' } })
                .done(res => { table.ajax.reload(); Swal.fire({ icon: 'success', title: 'Dihapus', text: res.message }); });
            }
        });
    }
</script>
@endpush
