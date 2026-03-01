<aside class="main-sidebar elevation-4 sidebar-light-success">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-success">
        <img src="{{ Storage::url($setting->path_image ?? '') }}" alt="Logo"
            class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
        <span class="brand-text font-weight-light text-sm">
            {{ $setting->company_name }}
        </span>
    </a>

    <div class="sidebar">

        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!empty(auth()->user()->path_image) && Storage::disk('public')->exists(auth()->user()->path_image))
                    <img src="{{ Storage::url(auth()->user()->path_image) }}" class="img-circle elevation-2"
                        style="width:35px;height:35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" class="img-circle elevation-2"
                        style="width:35px;height:35px;">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block" data-toggle="tooltip" title="Edit Profil">
                    {{ auth()->user()->name }}
                    <i class="fas fa-pencil-alt ml-2 text-sm text-primary"></i>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview"
                role="menu" data-accordion="false">

                {{-- ================= DASHBOARD ================= --}}
                @can('dashboard.view')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endcan


                {{-- ================= WEBSITE & PUBLIKASI ================= --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Website & Publikasi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-header">Struktur Website</li>

                        <li class="nav-item">
                            <a href="{{ route('menus.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Menu Website</p>
                            </a>
                        </li>

                        <li class="nav-header">Konten Statis</li>

                        <li class="nav-item">
                            <a href="{{ route('pages.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Halaman</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('opening_speech.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sambutan Kepala Sekolah</p>
                            </a>
                        </li>

                        <li class="nav-header">Artikel & Berita</li>

                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Artikel / Berita</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori Artikel</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('tags.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tag Artikel</p>
                            </a>
                        </li>

                        <li class="nav-header">Tampilan & Elemen</li>

                        <li class="nav-item">
                            <a href="{{ route('image-sliders.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Slider</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('quotes.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kutipan</p>
                            </a>
                        </li>

                        <li class="nav-header">Media</li>

                        <li class="nav-item">
                            <a href="{{ route('albums.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Album Foto</p>
                            </a>
                        </li>

                    </ul>
                </li>


                {{-- ================= AKADEMIK & SISWA ================= --}}
                @canany(['academic-year.view', 'class.view'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                Akademik & Siswa
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-header">Data Akademik</li>

                            <li class="nav-item">
                                <a href="{{ route('academic-years.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tahun Pelajaran</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('class-groups.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kelas</p>
                                </a>
                            </li>

                            <li class="nav-header">Data Master Siswa</li>

                            <li class="nav-item">
                                <a href="{{ route('student-status.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Status Siswa</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('educations.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendidikan Orang Tua</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('monthly-incomes.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Penghasilan Orang Tua</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('residences.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tempat Tinggal</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('transportations.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Transportasi</p>
                                </a>
                            </li>

                            <li class="nav-header">Operasional</li>

                            <li class="nav-item">
                                <a href="{{ route('students.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Siswa</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('agenda.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Agenda Sekolah</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcanany


                {{-- ================= PPDB ================= --}}
                @php
                    $ppdb = \App\Models\AcademicYear::where('current_semester', 1)
                        ->where('admission_semester', 1)
                        ->value('academic_year');
                @endphp

                @if ($ppdb)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>
                                PPDB {{ $ppdb }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-header">Pengaturan</li>

                            <li class="nav-item">
                                <a href="{{ route('admission-phases.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gelombang</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admission-types.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jalur</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admission-quotas.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kuota</p>
                                </a>
                            </li>

                            <li class="nav-header">Proses</li>

                            <li class="nav-item">
                                <a href="{{ route('student-admissions.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Pendaftar</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif


                {{-- ================= MANAJEMEN SISTEM ================= --}}
                @canany(['user.view', 'role.view', 'permission.view'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Manajemen Sistem
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('role.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Role</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permission</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcanany


                {{-- ================= PENGATURAN APLIKASI ================= --}}
                @can('setting.view')
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Pengaturan Aplikasi</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
