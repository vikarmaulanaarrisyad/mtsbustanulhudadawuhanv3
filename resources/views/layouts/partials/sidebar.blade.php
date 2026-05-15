<aside class="main-sidebar elevation-4 sidebar-midnight-premium animate__animated animate__fadeInLeft">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link border-bottom-0 py-3">
        <div class="brand-logo-wrapper d-inline-block mr-2">
            <img src="{{ Storage::url($setting->path_image ?? '') }}" alt="Logo"
                class="brand-image img-circle elevation-3 bg-white" style="opacity: 1; padding: 2px;">
        </div>
        <span class="brand-text font-weight-bold text-white text-uppercase" style="letter-spacing: 1px;">
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
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.workflow') }}"
                        class="nav-link {{ request()->routeIs('admin.workflow') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt text-warning"></i>
                        <p>Peta Jalan Admin</p>
                    </a>
                </li>

                {{-- ================= DATA MASTER ================= --}}
                <li class="nav-header">MANAJEMEN DATA</li>

                <li class="nav-item {{ request()->is('teachers*') ? 'menu-open' : '' }}">
                    <a href="{{ route('teachers.index') }}"
                        class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Guru & Staf</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('admin/performance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/performance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>
                            Penilaian Kinerja (PKG)
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('performance.index') }}"
                                class="nav-link {{ request()->is('admin/performance') || request()->is('admin/performance/create*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penilaian Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('performance.indicators.manage') }}"
                                class="nav-link {{ request()->is('admin/performance/indicators*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Indikator PKG</p>
                            </a>
                        </li>
                    </ul>
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
                            <a href="{{ route('students.index') }}"
                                class="nav-link {{ request()->is('academic/students') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Siswa Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-status.index') }}"
                                class="nav-link {{ request()->is('academic/students/status*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('alumni.index') }}"
                                class="nav-link {{ request()->is('alumni*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Alumni</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfers.index') }}"
                                class="nav-link {{ request()->is('transfers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Mutasi & Pindah</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Akademik & Kelas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('class-groups.index') }}"
                                class="nav-link {{ request()->is('academic/class-groups*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-placements.index') }}"
                                class="nav-link {{ request()->is('academic/student-placements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penempatan Rombel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('academic-years.index') }}"
                                class="nav-link {{ request()->is('academic/academic-years*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tahun Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('study-periods.index') }}"
                                class="nav-link {{ request()->is('study-periods*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jam Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('promotions.index') }}"
                                class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kenaikan Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('class-transfers.index') }}"
                                class="nav-link {{ request()->is('class-transfers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mutasi Rombel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('graduations.index') }}"
                                class="nav-link {{ request()->is('graduations*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kelulusan Siswa</p>
                            </a>
                        </li>
                        @canany(['subjects.view', 'dashboard.admin'])
                            <li class="nav-item">
                                <a href="{{ route('subjects.index') }}"
                                    class="nav-link {{ request()->is('subjects*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mata Pelajaran</p>
                                </a>
                            </li>
                        @endcanany

                        @canany(['class-schedules.view', 'dashboard.admin'])
                            <li class="nav-item">
                                <a href="{{ route('class-schedules.index') }}"
                                    class="nav-link {{ request()->is('class-schedules*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jadwal Pelajaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.curriculum-targets.index') }}"
                                    class="nav-link {{ request()->is('admin/curriculum-targets*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Target Kurikulum</p>
                                </a>
                            </li>
                        @endcanany
                        <li class="nav-item">
                            <a href="{{ route('admin.achievements.index') }}"
                                class="nav-link {{ request()->is('admin/achievements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Prestasi Siswa</p>
                            </a>
                        </li>
                    </ul>
                    {{-- ================= PENGOLAHAN NILAI ================= --}}
                <li class="nav-header">PENGOLAHAN NILAI</li>
                <li class="nav-item {{ request()->is('grades*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('grades*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Nilai Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('grade-settings.index') }}"
                                class="nav-link {{ request()->routeIs('grade-settings.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Konfigurasi Mapel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-grades.raport') }}"
                                class="nav-link {{ request()->routeIs('student-grades.raport') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Input Nilai Raport</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-grades.exam') }}"
                                class="nav-link {{ request()->routeIs('student-grades.exam') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Input Nilai Ujian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= ABSENSI ================= --}}
                @can('dashboard.admin')
                    <li class="nav-header">ABSENSI & KEPEGAWAIAN</li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.attendance.dashboard') }}"
                            class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-clock"></i>
                            <p>Presensi Harian</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.permits.admin') }}"
                            class="nav-link {{ request()->is('admin/teacher/permits*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-check-double"></i>
                            <p>Verifikasi Izin Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payrolls.index') }}"
                            class="nav-link {{ request()->is('payrolls*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>Penggajian Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.permits.admin') }}"
                            class="nav-link {{ request()->is('admin/student-permits*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope-open-text"></i>
                            <p>Verifikasi Izin Siswa</p>
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
                                <a href="{{ route('admin.attendance.live') }}"
                                    class="nav-link {{ request()->routeIs('admin.attendance.live') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Presensi Guru (Live)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teacher.face.registration') }}"
                                    class="nav-link {{ request()->is('teacher/face-registration*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Registrasi Wajah AI</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attendance-reports.index') }}"
                                    class="nav-link {{ request()->is('attendance/reports*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Presensi Guru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.teaching-journals.index') }}"
                                    class="nav-link {{ request()->is('admin/teaching-journals*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Monitoring Jurnal KBM</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.curriculum-progress.index') }}"
                                    class="nav-link {{ request()->is('admin/curriculum-progress*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon text-emerald"></i>
                                    <p>Progress Kurikulum</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student-attendances.index') }}"
                                    class="nav-link {{ request()->is('student-attendances*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Presensi Siswa</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('holidays.index') }}"
                                    class="nav-link {{ request()->is('attendance/holidays*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hari Libur</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attendance-settings.index') }}"
                                    class="nav-link {{ request()->is('attendance/settings*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengaturan Absensi</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    @canany(['teacher-attendance.view', 'attendance-settings.view', 'dashboard.guru'])
                        <li class="nav-header">ABSENSI & KEPEGAWAIAN</li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.attendance.dashboard') }}"
                                class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-clock"></i>
                                <p>Presensi Harian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.permits.index') }}"
                                class="nav-link {{ request()->is('teacher/permits*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Izin Saya</p>
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
                                    <a href="{{ route('teacher.attendance.dashboard') }}"
                                        class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Presensi Harian</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('teacher.face.registration') }}"
                                        class="nav-link {{ request()->is('teacher/face-registration*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Registrasi Wajah AI</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('guru.journal.index') }}"
                                        class="nav-link {{ request()->is('guru/journal*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Jurnal KBM Saya</p>
                                    </a>
                                </li>
                                @can('teacher-attendance.view')
                                    <li class="nav-item">
                                        <a href="{{ route('attendance-reports.index') }}"
                                            class="nav-link {{ request()->is('attendance/reports*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Log Presensi Guru</p>
                                        </a>
                                    </li>
                                @endcan
                                @canany(['student-attendance.view', 'dashboard.guru'])
                                    <li class="nav-item">
                                        <a href="{{ route('student-attendances.index') }}"
                                            class="nav-link {{ request()->is('student-attendances*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Log Presensi Siswa</p>
                                        </a>
                                    </li>
                                @endcanany
                                <li class="nav-item">
                                    <a href="{{ route('holidays.index') }}"
                                        class="nav-link {{ request()->is('attendance/holidays*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hari Libur</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('guru.savings.index') }}"
                                        class="nav-link {{ request()->is('guru/savings*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tabungan Siswa</p>
                                    </a>
                                </li>
                                @can('attendance-settings.view')
                                    <li class="nav-item">
                                        <a href="{{ route('attendance-settings.index') }}"
                                            class="nav-link {{ request()->is('attendance/settings*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pengaturan Absensi</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endcan

                {{-- ================= PERSURATAN ================= --}}
                <li class="nav-header">LAYANAN PERSURATAN</li>

                <li class="nav-item {{ request()->is('duty-letters*') ? 'menu-open' : '' }}">
                    <a href="{{ route('duty-letters.index') }}"
                        class="nav-link {{ request()->is('duty-letters*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Surat Tugas & SPPD</p>
                    </a>
                </li>

                <li
                    class="nav-item {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-transfers*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-transfers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Administrasi Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('active-statements.index') }}"
                                class="nav-link {{ request()->is('active-statements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suket Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfers.index') }}"
                                class="nav-link {{ request()->is('transfers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Mutasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-acceptances.index') }}"
                                class="nav-link {{ request()->is('admin/mail/student-acceptances*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Diterima</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-certificates.index') }}"
                                class="nav-link {{ request()->is('student-certificates*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Arsip Suket (Lama)</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= KEUANGAN ================= --}}
                <li class="nav-header">KEUANGAN & IURAN</li>
                <li class="nav-item {{ request()->is('admin/spp*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('admin/spp*') ? 'active bg-gradient-info text-white' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            Keuangan SPP
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.settings') }}"
                                class="nav-link {{ request()->is('admin/spp/settings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan Tarif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.billing') }}"
                                class="nav-link {{ request()->is('admin/spp/billing*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Tagihan & Bayar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.savings.index') }}"
                                class="nav-link {{ request()->is('admin/savings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Tabungan Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.report') }}"
                                class="nav-link {{ request()->is('admin/spp/report*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Laporan SPP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item {{ request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Persuratan Umum
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('outgoing-mails.index') }}"
                                class="nav-link {{ request()->is('outgoing-mails*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Keluar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('school-meetings.index') }}"
                                class="nav-link {{ request()->is('school-meetings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Undangan Rapat</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('mail-settings.index') }}"
                        class="nav-link {{ request()->is('mail-settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-print"></i>
                        <p>Pengaturan Kop</p>
                    </a>
                </li>


                {{-- ================= PPDB ================= --}}
                @canany(['ppdb.view', 'student-admissions.view'])
                    <li class="nav-header">PENERIMAAN SISWA</li>
                    <li class="nav-item {{ request()->is('ppdb*') || request()->is('admission*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('ppdb*') || request()->is('admission*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>
                                PPDB Online
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('ppdb.view')
                                <li class="nav-item">
                                    <a href="{{ route('ppdb.admin_dashboard') }}"
                                        class="nav-link {{ request()->routeIs('ppdb.admin_dashboard') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard PPDB</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ppdb.index') }}"
                                        class="nav-link {{ request()->is('admission/ppdb') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Pendaftar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ppdb.re_registration') }}"
                                        class="nav-link {{ request()->is('admission/ppdb/re-registration*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Verifikasi Daftar Ulang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('ppdb.selection') }}"
                                        class="nav-link {{ request()->is('admission/ppdb/selection*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Proses Seleksi</p>
                                    </a>
                                </li>
                            @endcan
                            @can('student-admissions.view')
                                <li class="nav-item">
                                    <a href="{{ route('student-admissions.index') }}"
                                        class="nav-link {{ request()->is('admission/student-admissions*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengaturan PPDB</p>
                                    </a>
                                </li>
                            @endcan
                            @can('admission-phases.view')
                                <li class="nav-item">
                                    <a href="{{ route('admission-phases.index') }}"
                                        class="nav-link {{ request()->is('admission/admission-phases*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Gelombang PPDB</p>
                                    </a>
                                </li>
                            @endcan
                            @can('admission-types.view')
                                <li class="nav-item">
                                    <a href="{{ route('admission-types.index') }}"
                                        class="nav-link {{ request()->is('admission/admission-types*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Jalur PPDB</p>
                                    </a>
                                </li>
                            @endcan
                            @can('admission-quotas.view')
                                <li class="nav-item">
                                    <a href="{{ route('admission-quotas.index') }}"
                                        class="nav-link {{ request()->is('admission/admission-quotas*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kuota PPDB</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('ppdb.payment_items') }}"
                                    class="nav-link {{ request()->is('admission/ppdb/payment-items*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Master Biaya PPDB</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany


                {{-- ================= WEBSITE & PUBLIKASI ================= --}}
                <li class="nav-header">KONTEN WEBSITE</li>
                <li
                    class="nav-item {{ request()->is('posts*') || request()->is('categories*') || request()->is('pages*') ? 'menu-open' : '' }}">
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
                                <a href="{{ route('menus.index') }}"
                                    class="nav-link {{ request()->is('configuration/menus*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Manajemen Menu</p>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{ url('/prestasi') }}" target="_blank" class="nav-link text-warning">
                                <i class="fas fa-external-link-alt nav-icon"></i>
                                <p>Lihat Halaman Prestasi</p>
                            </a>
                        </li>
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

                @can('position.view')
                    <li class="nav-item">
                        <a href="{{ route('positions.index') }}"
                            class="nav-link {{ request()->is('positions*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>Master Jabatan</p>
                        </a>
                    </li>
                @endcan

                @can('dashboard.admin')
                    <li class="nav-item">
                        <a href="{{ route('announcements.admin') }}"
                            class="nav-link {{ request()->is('admin/manage-announcements*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>Pengumuman Madrasah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.wa-gateway.index') }}"
                            class="nav-link {{ request()->is('admin/wa-gateway*') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-whatsapp"></i>
                            <p>WA Gateway</p>
                        </a>
                    </li>
                @endcan

                @can('dashboard.admin')
                    <li class="nav-header">AKADEMIK & CBT</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.cbt.bank.index') }}"
                            class="nav-link {{ request()->is('cbt/bank*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>Bank Soal CBT</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.cbt.exam.index') }}"
                            class="nav-link {{ request()->is('cbt/exam*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-laptop-code"></i>
                            <p>Jadwal Ujian CBT</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.cbt.ranking.index') }}"
                            class="nav-link {{ request()->is('cbt/ranking*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Peringkat Ujian</p>
                        </a>
                    </li>

                    <li class="nav-header">KEUANGAN MADRASAH</li>
                    {{--  <li class="nav-item {{ request()->is('admin/spp*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/spp*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            Manajemen SPP
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.settings') }}" class="nav-link {{ request()->routeIs('admin.spp.settings') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan Tarif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.billing') }}" class="nav-link {{ request()->routeIs('admin.spp.billing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tagihan & Bayar</p>
                            </a>
                        </li>
                    </ul>
                </li>  --}}

                    <li class="nav-item {{ request()->is('admin/bos*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('admin/bos*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-university text-success"></i>
                            <p>
                                Dana BOS
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.bos.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.bos.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Buku Kas Umum</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bos.rkam.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.bos.rkam.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Master RKAM</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bos.items.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.bos.items.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Komponen Biaya</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bos.expense_types.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.bos.expense_types.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jenis Belanja</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.bos.activities.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.bos.activities.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kategori Kegiatan</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">LAPORAN & ANALISIS</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.analytics.index') }}"
                            class="nav-link {{ request()->is('admin/analytics') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Dashboard Statistik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.analytics.ranking') }}"
                            class="nav-link {{ request()->is('admin/analytics/ranking') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Ranking & Prestasi</p>
                        </a>
                    </li>

                    <li class="nav-header">PENGATURAN SISTEM</li>
                @endcan

                @can('setting.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.emis.index') }}"
                            class="nav-link {{ request()->is('emis*') ? 'active bg-gradient-primary text-white' : '' }}">
                            <i class="nav-icon fas fa-sync-alt text-primary"></i>
                            <p>Sinkronisasi EMIS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}"
                            class="nav-link {{ request()->is('setting*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Pengaturan Aplikasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backup.index') }}"
                            class="nav-link {{ request()->routeIs('backup.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>Backup & Restore</p>
                        </a>
                    </li>
                @endcan

                <li class="nav-header">AKSI</li>
                <li class="nav-item mt-auto">
                    <a href="#" class="nav-link text-danger"
                        onclick="document.querySelector('#form-logout').submit()">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Keluar Aplikasi</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* MIDNIGHT PREMIUM SIDEBAR */
    .sidebar-midnight-premium {
        background: #1a202c !important;
        /* Deep Midnight Blue-Grey */
        border-right: none;
    }

    .sidebar-midnight-premium .brand-link {
        background: transparent !important;
        padding-left: 20px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .brand-logo-wrapper {
        width: 33px;
        height: 33px;
        background: #fff;
        border-radius: 8px;
        padding: 2px;
        box-shadow: 0 4px 10px rgba(0, 255, 127, 0.3);
    }

    .sidebar-midnight-premium .user-panel {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        margin-top: 20px !important;
        padding-bottom: 20px !important;
    }

    .sidebar-midnight-premium .user-panel .info a {
        color: #e2e8f0 !important;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Menu Item Styling */
    .nav-sidebar .nav-item {
        margin-bottom: 5px;
        padding: 0 10px;
    }

    .nav-sidebar .nav-link {
        border-radius: 12px !important;
        color: #a0aec0 !important;
        padding: 10px 15px !important;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .nav-sidebar .nav-link i {
        font-size: 1.1rem;
        margin-right: 12px !important;
    }

    .nav-sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
        transform: translateX(5px);
    }

    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    }

    .nav-header {
        color: #4a5568 !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        letter-spacing: 1.5px;
        padding: 20px 0 10px 25px !important;
    }

    /* Treeview (Submenu) Styling */
    .nav-treeview {
        background: rgba(0, 0, 0, 0.15) !important;
        border-radius: 12px;
        margin-top: 5px;
        margin-bottom: 5px;
        padding: 5px 0;
    }

    .nav-treeview .nav-link {
        padding-left: 20px !important;
        font-size: 0.85rem;
    }

    .nav-treeview .nav-link i {
        font-size: 0.8rem;
    }

    .nav-treeview .nav-link.active {
        background: rgba(255, 255, 255, 0.08) !important;
        box-shadow: none !important;
        color: #2ecc71 !important;
        font-weight: bold;
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
</style>
