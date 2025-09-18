<aside class="main-sidebar elevation-4 sidebar-light-success">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-success">
        <img src="{{ Storage::url($setting->path_image ?? '') }}" alt="Logo"
            class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $setting->company_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!empty(auth()->user()->path_image) && Storage::disk('public')->exists(auth()->user()->path_image))
                    <img src="{{ Storage::url(auth()->user()->path_image) }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block" data-toggle="tooltip" data-placement="top"
                    title="Edit Profil">
                    {{ auth()->user()->name }}
                    <i class="fas fa-pencil-alt ml-2 text-sm text-primary"></i>
                </a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-header">MENU</li>

                @can('dashboard.view')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Blog
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('image-sliders.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gambar Slide</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pesan Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tautan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Halaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Tulisan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="{{ route('posts.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Semu Tulisan</p>
                                    </a>
                                </li>
                                {{--  <li class="nav-item">
                                    <a href="{{ route('posts.create') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Tambah Baru</p>
                                    </a>
                                </li>  --}}
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Kategori Tulisan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tags.index') }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Tags</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Komentar</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('quotes.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kutipan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('opening_speech.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sambutan Kepala Sekolah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subcriber</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Media
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>File</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori File</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('albums.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Album Foto</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Video</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @canany(['academic-year.view', 'class.view', 'subject.view'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Akademik
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('academic-year.view')
                                <li class="nav-item">
                                    <a href="{{ route('academic-years.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tahun Pelajaran</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('class-groups.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kelas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transportations.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mode Transportasi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('monthly-incomes.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Penghasilan Bulanan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('educations.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendidikan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student-status.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Status Peserta Didik</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('residences.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tempat Tinggal</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Peserta Didik</p>
                                </a>
                            </li>
                            <!-- Sub-menu lain seperti Class, Subject -->
                        </ul>
                    </li>
                @endcanany

                @php
                    $ppdb = \App\Models\AcademicYear::where('current_semester', 1)
                        ->where('admission_semester', 1)
                        ->pluck('academic_year')
                        ->first();
                @endphp

                @if ($ppdb)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-signature"></i>
                            <p>
                                PPDB {{ $ppdb }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{ route('student-admissions.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengaturan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admission-phases.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gelombang Pendaftaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admission-types.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jalur Pendaftaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admission-quotas.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kuota Penerimaan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Calon Peserta Didik Baru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Proses Seleksi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendaftar Diterima</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendaftar Tidak Diterima</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Ujian / Exam
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Data Ujian -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Ujian</p>
                            </a>
                        </li>
                        <!-- Tambah Ujian Baru -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buat Ujian Baru</p>
                            </a>
                        </li>
                        <!-- Kategori / Mata Pelajaran -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori / Mata Pelajaran</p>
                            </a>
                        </li>
                        <!-- Hasil Ujian -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hasil Ujian</p>
                            </a>
                        </li>
                        <!-- Soal Ujian -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bank Soal</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @canany(['user.view', 'role.view', 'permission.view', 'permission-group.view'])
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Konfigurasi
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Menu</p>
                                </a>
                            </li>
                            @can('user.view')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>User</p>
                                    </a>
                                </li>
                            @endcan

                            @can('role.view')
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Role</p>
                                    </a>
                                </li>
                            @endcan

                            @can('permission.view')
                                <li class="nav-item">
                                    <a href="{{ route('permission.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Permission</p>
                                    </a>
                                </li>
                            @endcan

                            @can('permission-group.view')
                                <li class="nav-item">
                                    <a href="{{ route('permissiongroups.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Group Permission</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('setting.view')
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
