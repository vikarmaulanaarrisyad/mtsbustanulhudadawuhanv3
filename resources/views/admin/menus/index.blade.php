@extends($layout)

@section('title', 'Kelola Menu')
@section('subtitle', 'Manajemen Struktur Menu')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-sitemap mr-2 animate__animated animate__fadeInLeft"></i> 
                            Struktur Navigasi
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur struktur menu website utama, sub-menu, dan tautan eksternal.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-network-wired fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- LEFT SIDEBAR: MENU BUILDER -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-plus-circle mr-2 text-indigo"></i> Tambah Menu
                </h5>
            </div>
            <div class="card-body pt-0">
                <ul class="nav nav-pills premium-pills mb-4 bg-light rounded-pill p-1" id="menuTabs" role="tablist">
                    <li class="nav-item flex-fill">
                        <a class="nav-link active text-center rounded-pill" data-toggle="tab" href="#tautan">Tautan</a>
                    </li>
                    <li class="nav-item flex-fill">
                        <a class="nav-link text-center rounded-pill" data-toggle="tab" href="#halaman">Halaman</a>
                    </li>
                    <li class="nav-item flex-fill">
                        <a class="nav-link text-center rounded-pill" data-toggle="tab" href="#kategori">Kategori</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tautan">
                        @include('admin.menus.form-tautan')
                    </div>
                    <div class="tab-pane fade" id="halaman">
                        @include('admin.menus.form-halaman')
                    </div>
                    <div class="tab-pane fade" id="kategori">
                        @include('admin.menus.form-kategori')
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 premium-card bg-indigo text-white">
            <div class="card-body p-4">
                <div class="d-flex">
                    <div class="mr-3"><i class="fas fa-lightbulb fa-2x opacity-5"></i></div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Tips Navigasi</h6>
                        <p class="text-xs mb-0 opacity-8 text-justify">Gunakan fitur drag & drop di sebelah kanan untuk menyusun urutan menu dan membuat sub-menu (geser ke kanan).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: MENU STRUCTURE -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">Struktur Menu Aktif</h4>
                        <p class="text-muted text-sm mb-0">Klik ikon pensil untuk mengedit atau sampah untuk menghapus</p>
                    </div>
                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                        <button onclick="resetMenu()" class="btn btn-danger btn-sm px-3">
                            <i class="fas fa-undo mr-1"></i> RESET
                        </button>
                        <button onclick="refreshMenuList()" class="btn btn-indigo btn-sm px-3 text-white">
                            <i class="fas fa-sync-alt mr-1"></i> REFRESH
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body bg-slate-50 p-4">
                <ul class="sortable-menu" id="menuList">
                    @include('admin.menus.menu-list', ['menus' => $menus])
                </ul>
                
                @if($menus->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-sitemap fa-4x text-light mb-3"></i>
                    <h5 class="text-muted">Belum ada menu yang dibuat</h5>
                    <p class="text-xs text-muted">Gunakan panel kiri untuk mulai membangun navigasi</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* PREMIUM COLORS - INDIGO PURPLE */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%) !important; }
    .bg-indigo { background: #6366f1 !important; }
    .text-indigo { color: #6366f1 !important; }
    .btn-indigo { background: #6366f1; color: #fff; border: none; }
    .btn-indigo:hover { background: #4f46e5; color: #fff; transform: translateY(-2px); }
    .bg-light-indigo { background: #eef2ff; color: #4338ca; }

    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-slate-50 { background: #f8fafc; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Premium Pills */
    .premium-pills .nav-link { 
        color: #64748b; font-weight: 700; font-size: 0.8rem; padding: 10px; border: none !important;
    }
    .premium-pills .nav-link.active { 
        background: #fff !important; color: #6366f1 !important; box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
    }

    /* Sortable Menu Styling */
    .sortable-menu { list-style: none; padding-left: 0; }
    .sortable-menu ul { list-style: none; padding-left: 30px; margin-top: 10px; border-left: 2px dashed #e2e8f0; }
    
    .sortable-menu li { margin-bottom: 12px; }
    .sortable-menu li > span { 
        display: flex; align-items: center; background: #fff; padding: 12px 18px; 
        border-radius: 15px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .sortable-menu li > span:hover { border-color: #6366f1; transform: translateX(5px); }
    
    .handle { cursor: grab; color: #94a3b8; margin-right: 15px; font-size: 1.2rem; }
    .handle:active { cursor: grabbing; }
    
    .menu-title-text { font-weight: 700; color: #1e293b; font-size: 0.95rem; }
    .menu-url-text { font-size: 0.75rem; color: #94a3b8; font-family: monospace; background: #f8fafc; padding: 2px 8px; border-radius: 4px; margin-left: 10px; }
    
    .tools { margin-left: auto; display: flex; gap: 8px; }
    .btn-tool { 
        width: 32px; height: 32px; border-radius: 8px; display: flex; 
        align-items: center; justify-content: center; border: none; transition: all 0.2s;
    }
    .btn-tool-edit { background: #e0f2fe; color: #0ea5e9; }
    .btn-tool-delete { background: #fee2e2; color: #ef4444; }
    .btn-tool:hover { transform: scale(1.1); }

    .sortable-placeholder { 
        background: #f1f5f9; border: 2px dashed #cbd5e1; border-radius: 15px; margin-bottom: 12px; height: 60px; 
    }
</style>
@endpush
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

        function refreshMenuList(silent = false) {

            if (!silent) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memuat menu...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            fetch(`{{ route('menus.index') }}`)
                .then(res => res.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, "text/html");
                    const newList = doc.querySelector("#menuList");
                    $("#menuList").html(newList.innerHTML);
                    initSortableMenu();

                    if (!silent) {
                        Swal.close(); // Tutup loading
                    }
                })
                .catch(() => {
                    if (!silent) {
                        Swal.close();
                    }
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
                            refreshMenuList(true);
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
