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
                    <div class="col-lg-3">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-primary text-white py-2">
                                <h5 class="mb-0"><i class="fas fa-cubes"></i> Tambah Menu</h5>
                            </div>

                            <div class="card-body">

                                <!-- NAV PILIHAN FORM -->
                                <ul class="nav nav-tabs mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#formHalaman">Halaman</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#formLink">Link</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#formModul">Modul</a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    <!-- FORM HALAMAN -->
                                    <div class="tab-pane fade show active" id="formHalaman">
                                        <form onsubmit="addCustomMenu(event)">
                                            @csrf
                                            <input type="hidden" name="menu_type" value="pages">

                                            <div class="form-group mb-2">
                                                <label>Judul Menu</label>
                                                <input type="text" name="menu_title" class="form-control form-control-sm"
                                                    required>
                                            </div>

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

                                    <!-- FORM LINK -->
                                    <div class="tab-pane fade" id="formLink">
                                        <form onsubmit="addCustomMenu(event)">
                                            @csrf
                                            <input type="hidden" name="menu_type" value="links">

                                            <div class="form-group mb-2">
                                                <label>Judul Menu</label>
                                                <input type="text" name="menu_title" class="form-control form-control-sm"
                                                    required>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label>URL</label>
                                                <input type="text" name="menu_url" class="form-control form-control-sm"
                                                    placeholder="https://example.com" required>
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

                                    <!-- FORM MODUL -->
                                    <div class="tab-pane fade" id="formModul">
                                        <form onsubmit="addCustomMenu(event)">
                                            @csrf
                                            <input type="hidden" name="menu_type" value="modules">

                                            <div class="form-group mb-2">
                                                <label>Judul Menu</label>
                                                <input type="text" name="menu_title" class="form-control form-control-sm"
                                                    required>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label>Pilih Modul</label>
                                                <select name="menu_url" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>Pilih Modul</option>
                                                    @foreach ($modules as $m)
                                                        <option value="/{{ $m->slug }}">{{ $m->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button class="btn btn-sm btn-primary w-100">Tambah</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STRUKTUR MENU -->
                    <div class="col-lg-9">
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
    </script>
@endpush
