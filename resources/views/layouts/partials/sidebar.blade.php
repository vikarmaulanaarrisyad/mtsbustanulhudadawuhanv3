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

                {{-- ================= DASHBOARD & INFO ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Dashboard & Info</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard Utama</p>
                    </a>
                </li>

                @can('dashboard.admin')
                <li class="nav-item">
                    <a href="{{ route('admin.workflow') }}"
                        class="nav-link {{ request()->routeIs('admin.workflow') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt text-warning"></i>
                        <p>Peta Jalan Admin</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('announcements.admin') }}"
                        class="nav-link {{ request()->is('admin/manage-announcements*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn text-info"></i>
                        <p>Pengumuman Madrasah</p>
                    </a>
                </li>
                @endcan

                {{-- ================= MANAJEMEN KEPENDIDIKAN ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Manajemen Kependidikan</li>
                
                @canany(['teachers.view', 'dashboard.admin'])
                <li class="nav-item {{ request()->is('teachers*') || request()->is('admin/performance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('teachers*') || request()->is('admin/performance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Guru & Kepegawaian
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Guru & Staf</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('performance.index') }}" class="nav-link {{ request()->is('admin/performance') || request()->is('admin/performance/create*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penilaian Guru (PKG)</p>
                            </a>
                        </li>
                        @can('dashboard.admin')
                        <li class="nav-item">
                            <a href="{{ route('performance.indicators.manage') }}" class="nav-link {{ request()->is('admin/performance/indicators*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Indikator Penilaian</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <li class="nav-item {{ request()->is('academic/students*') || request()->is('alumni*') || request()->is('transfers*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('academic/students*') || request()->is('alumni*') || request()->is('transfers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Manajemen Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ request()->is('academic/students') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Siswa Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-status.index') }}" class="nav-link {{ request()->is('academic/students/status*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-placements.index') }}" class="nav-link {{ request()->is('academic/student-placements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Plotting Rombel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('alumni.index') }}" class="nav-link {{ request()->is('alumni*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Alumni</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transfers.index') }}" class="nav-link {{ request()->is('transfers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Mutasi & Pindah</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') || request()->is('subjects*') || request()->is('class-schedules*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('academic/class-groups*') || request()->is('academic/academic-years*') || request()->is('subjects*') || request()->is('class-schedules*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Kurikulum & Kelas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('class-groups.index') }}" class="nav-link {{ request()->is('academic/class-groups*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Kelas/Rombel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->is('academic/academic-years*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tahun Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->is('subjects*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mata Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('class-schedules.index') }}" class="nav-link {{ request()->is('class-schedules*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jadwal Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('study-periods.index') }}" class="nav-link {{ request()->is('study-periods*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jam Pelajaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.curriculum-targets.index') }}" class="nav-link {{ request()->is('admin/curriculum-targets*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Target Kurikulum</p>
                            </a>
                        </li>
                        <li class="nav-item border-top mt-1 pt-1">
                            <a href="{{ route('promotions.index') }}" class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Kenaikan Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('graduations.index') }}" class="nav-link {{ request()->is('graduations*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Kelulusan Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('academic/mutabaah-tahfidz*') || request()->is('admin/achievements*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('academic/mutabaah-tahfidz*') || request()->is('admin/achievements*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Pembiasaan & Prestasi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('mutabaah-tahfidz.index') }}" class="nav-link {{ request()->is('academic/mutabaah-tahfidz*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Mutabaah & Tahfidz</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.achievements.index') }}" class="nav-link {{ request()->is('admin/achievements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Prestasi Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= UJIAN & PENILAIAN (CBT) ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Ujian & Penilaian (CBT)</li>
                
                @can('dashboard.admin')
                <li class="nav-item {{ request()->is('cbt/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('cbt/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-laptop-code text-indigo"></i>
                        <p>
                            Manajemen CBT
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.cbt.bank.index') }}" class="nav-link {{ request()->is('cbt/bank*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bank Soal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cbt.exam.index') }}" class="nav-link {{ request()->is('cbt/exam*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jadwal Ujian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cbt.session-sync.index') }}" class="nav-link {{ request()->is('cbt/session-sync*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sinkron Sesi & Gel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cbt.ranking.index') }}" class="nav-link {{ request()->is('cbt/ranking*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Hasil & Peringkat</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="nav-item {{ request()->is('grades*') || request()->is('guru/cbt/grading*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('grades*') || request()->is('guru/cbt/grading*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Pengolahan Nilai
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('grade-settings.index') }}" class="nav-link {{ request()->routeIs('grade-settings.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Konfigurasi Mapel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-grades.raport') }}" class="nav-link {{ request()->routeIs('student-grades.raport') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Raport</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-grades.exam') }}" class="nav-link {{ request()->routeIs('student-grades.exam') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Ujian Manual</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('guru.cbt.grading.index') }}" class="nav-link {{ request()->is('guru/cbt/grading*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-indigo"></i>
                                <p>Koreksi Essay CBT</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- ================= ABSENSI & MONITORING ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Absensi & Monitoring</li>
                
                @can('dashboard.admin')
                <li class="nav-item">
                    <a href="{{ route('teacher.attendance.dashboard') }}"
                        class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-clock text-success"></i>
                        <p>Presensi Harian</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('attendance*') || request()->is('admin/teacher/permits*') || request()->is('admin/student-permits*') || request()->is('admin/teaching-journals*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('attendance*') || request()->is('admin/teacher/permits*') || request()->is('admin/student-permits*') || request()->is('admin/teaching-journals*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Manajemen Absensi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.attendance.live') }}" class="nav-link {{ request()->routeIs('admin.attendance.live') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Monitoring Live</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.face.registration') }}" class="nav-link {{ request()->is('teacher/face-registration*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrasi Wajah AI</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.permits.admin') }}" class="nav-link {{ request()->is('admin/teacher/permits*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Verifikasi Izin Guru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student.permits.admin') }}" class="nav-link {{ request()->is('admin/student-permits*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Verifikasi Izin Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.teaching-journals.index') }}" class="nav-link {{ request()->is('admin/teaching-journals*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jurnal Mengajar</p>
                            </a>
                        </li>
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
                @endcan

                {{-- ================= ADMINISTRASI & PERSURATAN ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Administrasi & Persuratan</li>
                
                <li class="nav-item">
                    <a href="{{ route('duty-letters.index') }}"
                        class="nav-link {{ request()->is('duty-letters*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase text-info"></i>
                        <p>Surat Tugas & SPPD</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-acceptances*') || request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('active-statements*') || request()->is('student-certificates*') || request()->is('student-acceptances*') || request()->is('outgoing-mails*') || request()->is('school-meetings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Layanan Surat
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('active-statements.index') }}" class="nav-link {{ request()->is('active-statements*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Keterangan Aktif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-acceptances.index') }}" class="nav-link {{ request()->is('admin/mail/student-acceptances*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Diterima</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('outgoing-mails.index') }}" class="nav-link {{ request()->is('outgoing-mails*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Surat Keluar Madrasah</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('school-meetings.index') }}" class="nav-link {{ request()->is('school-meetings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Undangan Rapat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-certificates.index') }}" class="nav-link {{ request()->is('student-certificates*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Arsip Suket Lainnya</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('mail-settings.index') }}" class="nav-link {{ request()->is('mail-settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-print"></i>
                        <p>Pengaturan Kop Surat</p>
                    </a>
                </li>

                {{-- ================= KEUANGAN & IURAN ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Keuangan & Iuran</li>
                
                @can('dashboard.admin')
                <li class="nav-item {{ request()->is('admin/spp*') || request()->is('admin/savings*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/spp*') || request()->is('admin/savings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet text-success"></i>
                        <p>
                            Keuangan Siswa (SPP)
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.settings') }}" class="nav-link {{ request()->is('admin/spp/settings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan Tarif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.billing') }}" class="nav-link {{ request()->is('admin/spp/billing*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-success"></i>
                                <p>Pembayaran Tagihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.savings.index') }}" class="nav-link {{ request()->is('admin/savings*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-info"></i>
                                <p>Tabungan Siswa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.spp.report') }}" class="nav-link {{ request()->is('admin/spp/report*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-primary"></i>
                                <p>Laporan Keuangan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('admin/bos*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/bos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-university"></i>
                        <p>
                            Dana BOS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.bos.index') }}" class="nav-link {{ request()->routeIs('admin.bos.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Buku Kas Umum</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.bos.rkam.index') }}" class="nav-link {{ request()->routeIs('admin.bos.rkam.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>RKAM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.bos.items.index') }}" class="nav-link {{ request()->routeIs('admin.bos.items.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Komponen Biaya</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('payrolls.index') }}" class="nav-link {{ request()->is('payrolls*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-check-alt text-warning"></i>
                        <p>Penggajian Guru</p>
                    </a>
                </li>
                @endcan

                {{-- ================= PENERIMAAN SISWA (PPDB) ================= --}}
                @canany(['ppdb.view', 'student-admissions.view'])
                <li class="nav-header uppercase tracking-wider opacity-70">Penerimaan Siswa Baru</li>
                <li class="nav-item {{ request()->is('ppdb*') || request()->is('admission*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('ppdb*') || request()->is('admission*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus text-primary"></i>
                        <p>
                            PPDB Online
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
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
                            <a href="{{ route('ppdb.scanner') }}" class="nav-link {{ request()->routeIs('ppdb.scanner') ? 'active' : '' }}">
                                <i class="fas fa-qrcode nav-icon text-warning"></i>
                                <p>Verifikasi Berkas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('ppdb.chat.inbox') }}" class="nav-link {{ request()->routeIs('ppdb.chat.*') ? 'active' : '' }}">
                                <i class="fas fa-comments nav-icon text-success"></i>
                                <p>Pesan / Chat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('student-admissions.index') }}" class="nav-link {{ request()->is('admission/student-admissions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengaturan Jalur</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                {{-- ================= MEDIA & PUBLIKASI ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Media & Publikasi</li>
                <li class="nav-item {{ request()->is('posts*') || request()->is('pages*') || request()->is('albums*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('posts*') || request()->is('pages*') || request()->is('albums*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-globe text-info"></i>
                        <p>
                            Website Madrasah
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Artikel & Berita</p>
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
                                <p>Galeri & Foto</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('menus.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manajemen Menu</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('faq.index') }}" class="nav-link {{ request()->is('admin/faq*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>FAQ (Tanya Jawab)</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @can('dashboard.admin')
                <li class="nav-item">
                    <a href="{{ route('admin.wa-gateway.index') }}" class="nav-link {{ request()->is('admin/wa-gateway*') ? 'active' : '' }}">
                        <i class="nav-icon fab fa-whatsapp text-success"></i>
                        <p>WA Gateway</p>
                    </a>
                </li>
                @endcan

                {{-- ================= ANALISIS & SISTEM ================= --}}
                <li class="nav-header uppercase tracking-wider opacity-70">Analisis & Sistem</li>
                
                @can('dashboard.admin')
                <li class="nav-item {{ request()->is('admin/analytics*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/analytics*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-warning"></i>
                        <p>
                            Statistik & Ranking
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->is('admin/analytics') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard Statistik</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics.ranking') }}" class="nav-link {{ request()->is('admin/analytics/ranking') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-warning"></i>
                                <p>Ranking Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="nav-item {{ request()->is('users*') || request()->is('role*') || request()->is('positions*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('users*') || request()->is('role*') || request()->is('positions*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Hak Akses & User
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
                                <p>Role & Permission</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('positions.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Master Jabatan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @can('setting.view')
                <li class="nav-item">
                    <a href="{{ route('admin.emis.index') }}" class="nav-link {{ request()->is('emis*') ? 'active bg-gradient-primary text-white' : '' }}">
                        <i class="nav-icon fas fa-sync-alt text-primary"></i>
                        <p>Sinkronisasi EMIS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('setting.index') }}" class="nav-link {{ request()->is('setting*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Pengaturan Aplikasi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backup.index') }}" class="nav-link {{ request()->routeIs('backup.index') ? 'active' : '' }}">
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
