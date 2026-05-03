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
                @if (!empty(auth()->user()->profile_photo_path) && Storage::disk('public')->exists(auth()->user()->profile_photo_path))
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="img-circle elevation-2"
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
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- ================= DATA MASTER ================= --}}
                <li class="nav-header">MANAJEMEN DATA</li>
                
                <li class="nav-item {{ request()->is('teachers*') ? 'menu-open' : '' }}">
                    <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Guru & Staf</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('academic/students*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('academic/students*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Data Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ request()->is('academic/students') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Siswa Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-status.index') }}" class="nav-link {{ request()->is('academic/students/status*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Akademik & Kelas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('class-groups.index') }}" class="nav-link {{ request()->is('academic/class-groups*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-placements.index') }}" class="nav-link {{ request()->is('academic/student-placements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penempatan Rombel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->is('academic/academic-years*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tahun Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('study-periods.index') }}" class="nav-link {{ request()->is('study-periods*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jam Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('promotions.index') }}" class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kenaikan Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('graduations.index') }}" class="nav-link {{ request()->is('graduations*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kelulusan Siswa</p>
                            </a>
                        </li>
                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']) || auth()->user()->can('subjects.view'))
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->is('subjects*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mata Pelajaran</p>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']) || auth()->user()->can('class-schedules.view'))
                        <li class="nav-item">
                            <a href="{{ route('class-schedules.index') }}" class="nav-link {{ request()->is('class-schedules*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jadwal Pelajaran</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- ================= ABSENSI ================= --}}
                @if(auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                <li class="nav-header">ABSENSI & KEPEGAWAIAN</li>
                <li class="nav-item">
                    <a href="{{ route('teacher.attendance.dashboard') }}" class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock"></i>
                        <p>Presensi Harian</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('attendance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Manajemen Absensi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('attendance-reports.index') }}" class="nav-link {{ request()->is('attendance/reports*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Presensi Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-attendances.index') }}" class="nav-link {{ request()->is('student-attendances*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Presensi Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('holidays.index') }}" class="nav-link {{ request()->is('attendance/holidays*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hari Libur</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance-settings.index') }}" class="nav-link {{ request()->is('attendance/settings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan Absensi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @else
                    @canany(['teacher-attendance.view', 'attendance-settings.view'])
                    <li class="nav-header">ABSENSI & KEPEGAWAIAN</li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.attendance.dashboard') }}" class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-clock"></i>
                            <p>Presensi Harian</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('attendance*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Manajemen Absensi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('teacher-attendance.view')
                            <li class="nav-item">
                                <a href="{{ route('attendance-reports.index') }}" class="nav-link {{ request()->is('attendance/reports*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Presensi Guru</p>
                                </a>
                            </li>
                            @endcan
                            @if(auth()->user()->can('student-attendance.view') || auth()->user()->hasRole('Guru'))
                            <li class="nav-item">
                                <a href="{{ route('student-attendances.index') }}" class="nav-link {{ request()->is('student-attendances*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Presensi Siswa</p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('holidays.index') }}" class="nav-link {{ request()->is('attendance/holidays*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hari Libur</p>
                                </a>
                            </li>
                            @can('attendance-settings.view')
                            <li class="nav-item">
                                <a href="{{ route('attendance-settings.index') }}" class="nav-link {{ request()->is('attendance/settings*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengaturan Absensi</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                @endif

                {{-- ================= PERSURATAN ================= --}}
                <li class="nav-header">LAYANAN PERSURATAN</li>

                <li class="nav-item {{ request()->is('duty-letters*') ? 'menu-open' : '' }}">
                    <a href="{{ route('duty-letters.index') }}" class="nav-link {{ request()->is('duty-letters*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Surat Tugas & SPPD</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-transfers*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-transfers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Administrasi Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('active-statements.index') }}" class="nav-link {{ request()->is('active-statements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suket Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-transfers.index') }}" class="nav-link {{ request()->is('student-transfers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Mutasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-acceptances.index') }}" class="nav-link {{ request()->is('admin/mail/student-acceptances*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Diterima</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-certificates.index') }}" class="nav-link {{ request()->is('student-certificates*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Arsip Suket (Lama)</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Persuratan Umum
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('outgoing-mails.index') }}" class="nav-link {{ request()->is('outgoing-mails*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Keluar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('school-meetings.index') }}" class="nav-link {{ request()->is('school-meetings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Undangan Rapat</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('mail-settings.index') }}" class="nav-link {{ request()->is('mail-settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-print"></i>
                        <p>Pengaturan Kop</p>
                    </a>
                </li>


                {{-- ================= PPDB ================= --}}
                @canany(['ppdb.view', 'student-admissions.view'])
                <li class="nav-header">PENERIMAAN SISWA</li>
                <li class="nav-item {{ request()->is('ppdb*') || request()->is('admission*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('ppdb*') || request()->is('admission*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>
                            PPDB Online
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('ppdb.view')
                        <li class="nav-item">
                            <a href="{{ route('ppdb.admin_dashboard') }}" class="nav-link {{ request()->routeIs('ppdb.admin_dashboard') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard PPDB</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ppdb.index') }}" class="nav-link {{ request()->is('admission/ppdb') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pendaftar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ppdb.re_registration') }}" class="nav-link {{ request()->is('admission/ppdb/re-registration*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Verifikasi Daftar Ulang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ppdb.selection') }}" class="nav-link {{ request()->is('admission/ppdb/selection*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Proses Seleksi</p>
                            </a>
                        </li>
                        @endcan
                        @can('student-admissions.view')
                        <li class="nav-item">
                            <a href="{{ route('student-admissions.index') }}" class="nav-link {{ request()->is('admission/student-admissions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan PPDB</p>
                            </a>
                        </li>
                        @endcan
                        @can('admission-phases.view')
                        <li class="nav-item">
                            <a href="{{ route('admission-phases.index') }}" class="nav-link {{ request()->is('admission/admission-phases*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gelombang PPDB</p>
                            </a>
                        </li>
                        @endcan
                        @can('admission-types.view')
                        <li class="nav-item">
                            <a href="{{ route('admission-types.index') }}" class="nav-link {{ request()->is('admission/admission-types*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jalur PPDB</p>
                            </a>
                        </li>
                        @endcan
                        @can('admission-quotas.view')
                        <li class="nav-item">
                            <a href="{{ route('admission-quotas.index') }}" class="nav-link {{ request()->is('admission/admission-quotas*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kuota PPDB</p>
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{ route('ppdb.payment_items') }}" class="nav-link {{ request()->is('admission/ppdb/payment-items*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Master Biaya PPDB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany


                {{-- ================= WEBSITE & PUBLIKASI ================= --}}
                <li class="nav-header">KONTEN WEBSITE</li>
                <li class="nav-item {{ request()->is('posts*') || request()->is('categories*') || request()->is('pages*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Website & Berita
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Artikel/Berita</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pages.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Halaman Statis</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('albums.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Galeri Foto</p>
                            </a>
                        </li>
                        @can('menus.view')
                        <li class="nav-item">
                            <a href="{{ route('menus.index') }}" class="nav-link {{ request()->is('configuration/menus*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manajemen Menu</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>


                {{-- ================= SISTEM ================= --}}
                <li class="nav-header">SISTEM & PENGATURAN</li>
                
                @canany(['user.view', 'role.view'])
                <li class="nav-item {{ request()->is('users*') || request()->is('role*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Manajemen User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Role & Hak Akses</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                <li class="nav-item">
                    <a href="{{ route('announcements.admin') }}" class="nav-link {{ request()->is('admin/manage-announcements*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Pengumuman Madrasah</p>
                    </a>
                </li>
                @endif

                @can('setting.view')
                <li class="nav-item">
                    <a href="{{ route('setting.index') }}" class="nav-link {{ request()->is('setting*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Pengaturan Aplikasi</p>
                    </a>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
