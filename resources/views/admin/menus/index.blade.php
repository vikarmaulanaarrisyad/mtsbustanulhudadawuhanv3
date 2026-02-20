<!-- FULL CODE MENU BUILDER YANG SUDAH DIPISAH: HALAMAN, LINK, MODUL -->

@extends('layouts.app')

@section('title', 'Kelola Menu')
@section('subtitle', 'Manajemen Struktur Menu')

@push('css')
    <style>
        .sortable-menu {
            list-style: none;
            padding-left: 0;
            position: relative;
            min-height: 20px;
        }

        .sortable-menu li {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
            position: relative;
        }

        .sortable-menu li:hover {
            background: #f8f9fa;
            transform: translateX(2px);
        }

        .sortable-placeholder {
            height: 38px !important;
            background: rgba(0, 123, 255, 0.12);
            border: 2px dashed #007bff;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .handle {
            cursor: move;
            margin-right: 8px;
        }

        .tools i {
            cursor: pointer;
            margin-left: 8px;
        }

        .tools i:hover {
            transform: scale(1.15);
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <div class="row">

                    <!-- FORM TAMBAH MENU -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex p-0">
                                <h4 class="card-title p-3">Tambah Menu</h4>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tautan"
                                            data-toggle="tab">Tautan</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#halaman" data-toggle="tab">Halaman</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#kategori" data-toggle="tab">Kategori</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#modul" data-toggle="tab">Modul</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tautan">

                                        <p>Masih Tahap Developer</p>

                                    </div>
                                    <div class="tab-pane " id="halaman">
                                        <form onsubmit="addCustomMenu(event)">
                                            @csrf
                                            <input type="hidden" name="menu_type" value="pages">
                                            <div class="form-group mb-2">
                                                <label>Pilih Halaman</label>
                                                <select name="menu_url" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>Pilih Halaman</option>
                                                    @foreach ($pages as $page)
                                                        <option value="{{ $page->slug }}">{{ $page->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label>Target</label>
                                                <select name="menu_target" class="form-control form-control-sm">
                                                    <option value="_self">Self</option>
                                                    <option value="_blank">Blank</option>
                                                </select>
                                            </div>

                                            <button class="btn btn-sm btn-primary w-100">Tambah</button>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="kategori">
                                        <form onsubmit="addCustomMenu(event)">
                                            @csrf
                                            <input type="hidden" name="menu_type" value="links">
                                            <div class="form-group mb-2">
                                                <label>Pilih Salah Satu</label>
                                                <select name="menu_url" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>Pilih Halaman</option>
                                                    @foreach ($category as $c)
                                                        <option value="{{ $c->category_slug }}">{{ $c->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label>Target</label>
                                                <select name="menu_target" class="form-control form-control-sm">
                                                    <option value="_self">Self</option>
                                                    <option value="_blank">Blank</option>
                                                </select>
                                            </div>

                                            <button class="btn btn-sm btn-primary w-100">Tambah</button>
                                        </form>
                                    </div>

                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="modul">
                                        <form id="formMenu" action="{{ route('menus.store') }}" method="POST">
                                            <input type="hidden" name="menu_type" value="modules">
                                            @csrf

                                            <div class="row">

                                                <!-- Parent Menu -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>Parent Menu</label>
                                                        <select class="form-control" name="menu_parent_id">
                                                            <option value="0">Menu Utama</option>
                                                            @foreach ($menus as $menu)
                                                                @if ($menu->menu_parent_id == 0)
                                                                    <option value="{{ $menu->id }}">
                                                                        {{ $menu->menu_title }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Nama Menu -->
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label>Nama Menu <span class="text-danger">*</span></label>
                                                        <input type="text" name="menu_title" class="form-control"
                                                            required>
                                                    </div>
                                                </div>

                                                <!-- URL Menu -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>URL Menu</label>
                                                        <input type="text" name="menu_url" class="form-control"
                                                            placeholder="/about-us">
                                                    </div>
                                                </div>

                                                <!-- Target -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>Target Menu</label>
                                                        <select class="form-control" name="menu_target">
                                                            <option value="_self">Self</option>
                                                            <option value="_blank">Blank</option>
                                                            <option value="_parent">Parent</option>
                                                            <option value="_top">Top</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Posisi -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>Posisi Menu</label>
                                                        <input type="number" name="menu_position" class="form-control"
                                                            value="0">
                                                    </div>
                                                </div>

                                                <!-- Status -->
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label>Status</label>
                                                        <select class="form-control" name="menu_status">
                                                            <option value="1">Aktif</option>
                                                            <option value="0">Non Aktif</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Button -->
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Simpan Menu
                                                    </button>
                                                    <button type="reset" class="btn btn-secondary">
                                                        Reset
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                    </div>


                    <!-- STRUKTUR MENU -->
                    <div class="col-lg-6">
                        <x-card>
                            <x-slot name="header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-list-ul"></i> Struktur Menu</h5>
                                    <button onclick="refreshMenuList()" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-sync"></i> Refresh
                                    </button>
                                </div>
                            </x-slot>

                            <ul class="sortable-menu" id="menuList">
                                @include('admin.menus.menu-list', ['menus' => $menus])
                            </ul>
                        </x-card>
                    </div>

                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ilikenwf/nestedSortable/jquery.mjs.nestedSortable.js"></script>

    <script>
        $(function() {
            initSortableMenu();
        });

        function initSortableMenu() {
            $('#menuList').nestedSortable({
                handle: '.handle',
                items: 'li',
                toleranceElement: '> span',
                placeholder: 'sortable-placeholder',
                listType: 'ul',
                forcePlaceholderSize: true,
                helper: 'clone',
                opacity: 0.8,
                tolerance: 'pointer',
                revert: 250,
                maxLevels: 5,
                relocate: saveMenuOrder
            });
        }

        function saveMenuOrder() {
            const serialized = $('#menuList').nestedSortable('toArray', {
                startDepthCount: 0
            });

            const data = serialized
                .filter(i => i.item_id || i.id)
                .map((item, index) => ({
                    id: item.item_id || item.id,
                    parent_id: item.parent_id || 0,
                    position: index + 1
                }));

            $.ajax({
                url: "{{ route('menus.updateOrder') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    menu: data
                },
                success: res => toastr.success(res.message || 'Struktur menu disimpan'),
                error: err => toastr.error('Gagal menyimpan urutan menu')
            });
        }

        function refreshMenuList() {
            fetch(`{{ route('menus.index') }}`)
                .then(res => res.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, "text/html");
                    const newList = doc.querySelector("#menuList");
                    $("#menuList").html(newList.innerHTML);
                    initSortableMenu();
                })
                .catch(() => toastr.error("Gagal memuat ulang menu"));
        }

        function addCustomMenu(e) {
            e.preventDefault();

            const form = e.target;
            const data = new FormData(form);

            Swal.fire({
                title: 'Menyimpan...',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            fetch(`{{ route('menus.store') }}`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: data
                })
                .then(res => res.json())
                .then(res => {
                    Swal.close();
                    if (res.menu) {
                        toastr.success(res.message);
                        form.reset();
                        refreshMenuList();
                    } else {
                        Swal.fire('Gagal', res.message || 'Kesalahan tidak diketahui', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Tidak dapat mengirim data ke server', 'error'));
        }

        function deleteData(url, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            });

            swalWithBootstrapButtons.fire({
                title: 'Delete Data!',
                text: 'Apakah Anda yakin ingin menghapus ' + name +
                    ' ? Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Swal loading sebelum menghapus
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                refreshMenuList(); // Reload DataTables setelah penghapusan
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: xhr.responseJSON ? xhr.responseJSON.message :
                                    'Terjadi kesalahan!',
                                showConfirmButton: true,
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel jika terjadi error
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {

            // Setup CSRF token global
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Submit Form
            $('#formMenu').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: "json",
                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message ?? 'Data berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        form.trigger("reset");
                        refreshMenuList();
                        // Jika pakai DataTables
                        if (typeof table !== 'undefined') {
                            refreshMenuList();
                        }

                    },
                    error: function(xhr) {

                        let errors = xhr.responseJSON?.errors;
                        let errorMessage = "Terjadi kesalahan!";

                        if (errors) {
                            errorMessage = Object.values(errors)
                                .map(err => err[0])
                                .join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: errorMessage
                        });
                    }
                });

            });

        });
    </script>
@endpush
