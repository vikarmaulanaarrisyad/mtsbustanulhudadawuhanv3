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

        .menu-item {
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background-color: #fff;
            margin-bottom: 6px;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <div class="row">
                    {{-- FORM TAMBAH MENU --}}
                    <div class="col-lg-3">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-primary text-white py-2">
                                <h5 class="mb-0"><i class="fas fa-cubes"></i> Tambah Menu</h5>
                            </div>
                            <div class="card-body">
                                <form id="form-menu" onsubmit="addCustomMenu(event)">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label>Judul Menu</label>
                                        <input type="text" name="menu_title" class="form-control form-control-sm"
                                            required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label>URL Menu</label>
                                        <input type="text" name="menu_url" class="form-control form-control-sm"
                                            placeholder="/url-anda">
                                    </div>

                                    <div class="form-group mb-2">
                                        <label>Target</label>
                                        <select name="menu_target" class="form-control form-control-sm">
                                            <option value="_self">Self</option>
                                            <option value="_blank">Blank</option>
                                            <option value="_parent">Parent</option>
                                            <option value="_top">Top</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label>Tipe Menu</label>
                                        <select name="menu_type" class="form-control form-control-sm">
                                            <option value="pages">Halaman</option>
                                            <option value="links">Link</option>
                                            <option value="modules">Modul</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-plus-circle"></i> Tambah Menu
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- STRUKTUR MENU --}}
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
    {{-- PAKAI VERSI INI, BUKAN YANG DARI ADMINLTE --}}
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

            console.log('Data to send:', data);

            $.ajax({
                url: "{{ route('menus.updateOrder') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    menu: data
                },
                success: res => toastr.success(res.message || 'Struktur menu disimpan'),
                error: err => {
                    console.error(err);
                    toastr.error('Gagal menyimpan urutan menu');
                }
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
