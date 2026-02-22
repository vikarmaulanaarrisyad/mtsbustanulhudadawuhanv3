@extends('layouts.app')

@section('title', 'Kelola Menu')
@section('subtitle', 'Manajemen Struktur Menu')

@push('css')
    <style>
        .content-wrapper {
            background: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f1f1;
            border-radius: 16px 16px 0 0 !important;
            padding: 18px 20px;
        }

        .card-title {
            font-weight: 600;
            font-size: 15px;
            margin: 0;
        }

        .nav-pills {
            gap: 6px;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            font-weight: 500;
            font-size: 13px;
            padding: 6px 14px;
            background: #f4f6f9;
            color: #6c757d;
            transition: all .25s ease;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            padding: 10px 14px;
            transition: all .2s ease;
        }

        .form-control:focus {
            border-color: #3c8dbc;
            box-shadow: 0 0 0 3px rgba(60, 141, 188, .15);
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 7px 16px;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3c8dbc, #00c0ef);
            border: none;
        }

        .btn-primary:hover {
            opacity: .9;
        }

        .btn-light {
            background: #f4f6f9;
            border: 1px solid #e5e7eb;
        }

        .sortable-menu {
            list-style: none;
            padding-left: 0;
        }

        .sortable-menu li {
            background: #ffffff;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 10px;
            border: 1px solid #f1f1f1;
            transition: all .25s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
        }

        .sortable-menu li:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, .08);
        }

        .sortable-placeholder {
            height: 50px !important;
            background: rgba(60, 141, 188, .08);
            border: 2px dashed #3c8dbc;
            border-radius: 14px;
        }

        .handle {
            cursor: grab;
            margin-right: 10px;
            color: #adb5bd;
        }

        .handle:hover {
            color: #3c8dbc;
        }

        .tools i {
            cursor: pointer;
            margin-left: 12px;
            transition: .2s;
            color: #6c757d;
        }

        .tools .fa-trash:hover {
            color: #dc3545;
        }

        .tools .fa-edit:hover {
            color: #17a2b8;
        }

        .menu-info {
            background: #ffffff;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            border: 1px solid #f1f1f1;
            margin-bottom: 12px;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex p-0">
                                <h4 class="card-title p-3">Tambah Menu</h4>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item"><a class="nav-link active" href="#tautan"
                                            data-toggle="tab">Tautan</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#halaman" data-toggle="tab">Halaman</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#kategori" data-toggle="tab">Kategori
                                            Tulisan</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#modul" data-toggle="tab">Modul</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- TAB TAUTAN -->
                                    <div class="tab-pane active" id="tautan">
                                        @include('admin.menus.form-tautan')
                                    </div>

                                    <div class="tab-pane" id="kategori">
                                        @include('admin.menus.form-kategori')
                                    </div>

                                    <!-- TAB HALAMAN -->
                                    <div class="tab-pane" id="halaman">
                                        @include('admin.menus.form-halaman')
                                    </div>

                                    <!-- TAB MODUL (dipersingkat tampilan premium) -->
                                    <div class="tab-pane" id="modul">
                                        @include('admin.menus.form-modul')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <x-card>
                            <x-slot name="header">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div>
                                        <h5 class="card-title">
                                            <i class="fas fa-sitemap text-info mr-2"></i>
                                            Struktur Menu Website
                                        </h5>
                                    </div>
                                    <button onclick="resetMenu()" class="btn btn-danger btn-sm">
                                        <i class="fas fa-undo"></i> Reset Menu
                                    </button>
                                    <button onclick="refreshMenuList()" class="btn btn-light btn-sm">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </x-slot>

                            <div class="menu-info">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Tarik menu untuk membuat sub-menu hingga 2 level.
                            </div>

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

            // Tampilkan loading
            Swal.fire({
                title: 'Memuat menu...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`{{ route('menus.index') }}`)
                .then(res => res.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, "text/html");
                    const newList = doc.querySelector("#menuList");
                    $("#menuList").html(newList.innerHTML);
                    initSortableMenu();

                    Swal.close(); // Tutup loading
                })
                .catch(() => {
                    Swal.close();
                    toastr.error("Gagal memuat ulang menu");
                });
        }

        function resetMenu() {

            Swal.fire({
                title: 'Reset Menu?',
                text: "Semua susunan menu akan dikembalikan ke default!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Mereset menu...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`{{ route('menus.reset') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(response => {

                            if (response.success) {
                                refreshMenuList(); // reload daftar menu
                                toastr.success("Menu berhasil direset");
                            } else {
                                toastr.error("Gagal reset menu");
                            }

                        })
                        .catch(() => {
                            toastr.error("Terjadi kesalahan");
                        })
                        .finally(() => {
                            Swal.close();
                        });
                }
            });
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

            // Gunakan class, bukan ID
            $(document).on('submit', '.formMenu', function(e) {
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

                        if (typeof refreshMenuList === "function") {
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
