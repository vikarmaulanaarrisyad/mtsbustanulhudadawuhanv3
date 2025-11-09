@extends('layouts.app')

@section('title', 'Manage Menu')

@section('subtitle', 'Manage Menu')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@push('css')
    <style>
        .sortable-menu,
        .nested-sortable {
            list-style-type: none;
            padding-left: 15px;
        }

        .sortable-menu>li {
            margin-bottom: 10px;
            /* Jarak antar menu utama */
            padding: 8px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .nested-sortable>li {
            margin-bottom: 8px;
            /* Jarak antar submenu dengan parent yang sama */
            padding: 6px;
            border-radius: 4px;
            /* Opsional: menandai submenu */
        }
    </style>
@endpush

@push('css')
    <style>
        .menu-card {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .menu-card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .menu-card .card-header {
            border-radius: 10px 10px 0 0 !important;
            cursor: pointer;
        }

        .menu-input-section .form-control {
            border-radius: 6px;
            box-shadow: none;
        }

        .menu-input-section .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
        }

        .menu-input-section label {
            font-weight: 500;
        }

        .menu-card button.btn {
            border-radius: 6px;
            font-weight: 600;
        }

        .sortable-menu>li {
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sortable-menu>li:hover {
            background: #f9f9fb;
        }

        .nested-sortable>li {
            background: #fafafa;
            border: 1px dashed #ddd;
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="menu-input-section">
                {{-- TAUTAN --}}
                <div class="card shadow-sm border-0 mb-3 menu-card">
                    <div
                        class="card-header bg-gradient-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-sm mb-0"><i class="fas fa-link mr-2"></i>TAUTAN</h3>
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="form-tautan" onsubmit="addCustomMenu(event, 'tautan')">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-muted small mb-1">Nama Menu</label>
                                <input type="text" name="menu_title" class="form-control form-control-sm"
                                    placeholder="Misal: Tentang Kami" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-muted small mb-1">URL</label>
                                <input type="url" name="menu_url" class="form-control form-control-sm"
                                    placeholder="https://example.com" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah ke Menu
                            </button>
                        </form>
                    </div>
                </div>

                {{-- HALAMAN --}}
                <div class="card shadow-sm border-0 mb-3 menu-card">
                    <div
                        class="card-header bg-gradient-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-sm mb-0"><i class="fas fa-file-alt mr-2"></i>HALAMAN</h3>
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="form-halaman" onsubmit="addCustomMenu(event, 'halaman')">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-muted small mb-1">Pilih Halaman</label>
                                <select name="menu_url" class="form-control form-control-sm" required>
                                    {{-- @foreach ($pages as $page)
                                <option value="{{ url($page->slug) }}">{{ $page->title }}</option>
                            @endforeach --}}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah ke Menu
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KATEGORI TULISAN --}}
                <div class="card shadow-sm border-0 mb-3 menu-card">
                    <div
                        class="card-header bg-gradient-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-sm mb-0"><i class="fas fa-tags mr-2"></i>KATEGORI TULISAN</h3>
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="form-kategori" onsubmit="addCustomMenu(event, 'kategori')">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-muted small mb-1">Pilih Kategori</label>
                                <select name="menu_url" class="form-control form-control-sm" required>
                                    {{-- @foreach ($categories as $cat)
                                <option value="{{ url('kategori/' . $cat->slug) }}">{{ $cat->name }}</option>
                            @endforeach --}}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah ke Menu
                            </button>
                        </form>
                    </div>
                </div>

                {{-- MODUL --}}
                <div class="card shadow-sm border-0 mb-3 menu-card">
                    <div
                        class="card-header bg-gradient-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-sm mb-0"><i class="fas fa-cubes mr-2"></i>MODUL</h3>
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="form-modul" onsubmit="addCustomMenu(event, 'modul')">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-muted small mb-1">Pilih Modul</label>
                                <select name="menu_url" class="form-control form-control-sm" required>
                                    <option value="/galeri">Galeri</option>
                                    <option value="/kontak">Kontak</option>
                                    <option value="/berita">Berita</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah ke Menu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <x-card>
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#struktur-menu" data-toggle="tab">Struktur Menu</a>
                            </li>
                        </ul>

                        <button onclick="addForm(`{{ route('menus.store') }}`)" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Tambah Data
                        </button>
                    </div>
                </x-slot>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content">
                            <div class="tab-pane active" id="struktur-menu">
                                <ul class="sortable-menu todo-list menu-list" data-widget="todo-list">
                                    @foreach ($menus->where('menu_parent_id', 0) as $menu)
                                        <li id="{{ $menu->id }}" data-id="{{ $menu->id }}">
                                            <span class="handle ui-sortable-handle">
                                                <i class="fas fa-ellipsis-v"></i>
                                                <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <span class="text">{{ $menu->menu_title }}</span>
                                            <div class="tools">
                                                <i onclick="editForm('{{ route('menus.show', $menu->id) }}')"
                                                    class="fas fa-edit text-primary"></i>
                                                <i onclick="deleteData('{{ route('menus.destroy', $menu->id) }}', '{{ $menu->menu_title }}')"
                                                    class="fas fa-trash-alt text-danger cursor-pointer"></i>

                                            </div>

                                            @if ($menus->where('menu_parent_id', $menu->id)->count() > 0)
                                                <ul class="nested-sortable">
                                                    @foreach ($menus->where('menu_parent_id', $menu->id) as $submenu)
                                                        <li id="{{ $submenu->id }}" data-id="{{ $submenu->id }}">
                                                            <span class="handle ui-sortable-handle">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </span>
                                                            <span class="text">{{ $submenu->menu_title }}</span>
                                                            <div class="tools">
                                                                <i onclick="editForm('{{ route('menus.show', $submenu->id) }}')"
                                                                    class="fas fa-edit text-primary"></i>
                                                                <i onclick="deleteData('{{ route('menus.destroy', $submenu->id) }}', '{{ $submenu->menu_title }}')"
                                                                    class="fas fa-trash-alt text-danger cursor-pointer"></i>

                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                {{--  <button id="saveMenuOrder" class="btn btn-primary mt-3">Simpan Perubahan</button>  --}}
                            </div>
                            <div class="tab-pane" id="timeline">
                                <!-- Konten Kelola Menu -->
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    @include('admin.menus.form')
@endsection

@push('scripts')
    <script src="{{ asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';


        function getMenuStructure() {
            let sortedData = [];

            $(".sortable-menu > li").each(function(index) {
                sortedData.push({
                    id: $(this).data('id'),
                    parent_id: 0,
                    position: index + 1
                });

                $(this).find("> ul > li").each(function(subIndex) {
                    sortedData.push({
                        id: $(this).data('id'),
                        parent_id: $(this).closest('ul').closest('li').data('id') || 0,
                        position: subIndex + 1
                    });
                });
            });

            return sortedData;
        }

        function sendMenuStructure(data) {
            $.ajax({
                url: "{{ route('menus.updateOrder') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    menu: data
                },
                success: function(response) {
                    toastr.success(response.message, "Berhasil!", {
                        timeOut: 2000
                    });
                },
                error: function() {
                    toastr.error("Terjadi kesalahan saat menyimpan!", "Gagal!", {
                        timeOut: 2000
                    });
                }
            });
        }

        $(function() {
            $(".sortable-menu, .nested-sortable").sortable({
                handle: ".handle",
                placeholder: "ui-state-highlight",
                connectWith: ".sortable-menu, .nested-sortable",
                update: function(event, ui) {
                    let menuData = getMenuStructure();
                    sendMenuStructure(menuData);
                }
            }).disableSelection();
        });

        function addForm(url, title = 'Form Data Kelas') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Data Kelas') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.get(url)
                .done(response => {
                    Swal.close(); // Tutup loading setelah sukses
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    Swal.close(); // Tutup loading jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                        showConfirmButton: true,
                    });

                    if (errors.status == 422) {
                        loopErrors(errors.responseJSON.errors);
                    }
                });
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);

            // Menampilkan Swal loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST', // Gunakan method dari form
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close(); // Tutup Swal Loading

                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            window.location.reload(); // Reload DataTables
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Tutup Swal Loading
                    $(button).prop('disabled', false);

                    let errorMessage = "Terjadi kesalahan!";
                    if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (xhr.status === 422) {
                        loopErrors(xhr.responseJSON.errors);
                    }
                }
            });
        }

        function deleteData(url, menuTitle) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: `Menu "${menuTitle}" akan dihapus secara permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    "content"),
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 3000
                            }).then(() => {
                                // Refresh halaman atau hapus elemen dari tampilan
                                window.location.reload();
                            });

                        })
                        .catch(error => {
                            Swal.fire("Error!", "Terjadi kesalahan saat menghapus data.", "error");
                        });
                }
            });
        }
    </script>
@endpush
