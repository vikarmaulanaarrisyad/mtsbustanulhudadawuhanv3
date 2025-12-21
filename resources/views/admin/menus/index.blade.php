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
                                    <li class="nav-item"><a class="nav-link" href="#halaman"
                                            data-toggle="tab">Halaman</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#kategori" data-toggle="tab">Kategori</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#modul" data-toggle="tab">Modul</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tautan">
                                        The European languages are members of the same family. Their separate existence is a
                                        myth.
                                        For science, music, sport, etc, Europe uses the same vocabulary. The languages only
                                        differ
                                        in their grammar, their pronunciation and their most common words. Everyone realizes
                                        why a
                                        new common language would be desirable: one could refuse to pay expensive
                                        translators. To
                                        achieve this, it would be necessary to have uniform grammar, pronunciation and more
                                        common
                                        words. If several languages coalesce, the grammar of the resulting language is more
                                        simple
                                        and regular than that of the individual languages.
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

                                            {{--  <div class="form-group mb-2">
                                                <label>Judul Menu</label>
                                                <input type="text" name="menu_title" class="form-control form-control-sm"
                                                    required>
                                            </div>  --}}

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
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                        Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
                                        when an unknown printer took a galley of type and scrambled it to make a type
                                        specimen book.
                                        It has survived not only five centuries, but also the leap into electronic
                                        typesetting,
                                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                                        Letraset
                                        sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                                        software
                                        like Aldus PageMaker including versions of Lorem Ipsum.
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
    </script>
@endpush
