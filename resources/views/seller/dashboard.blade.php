<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Command Center - Madrasah Digital</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- JQuery & Bootstrap JS for Modals -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CSS Bootstrap 4 Core (Isolated) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
            min-height: 100vh;
            background-image: radial-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
        }
        
        .glow-card {
            background: rgba(17, 24, 30, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        
        .glow-card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            box-shadow: 0 15px 40px rgba(139, 92, 246, 0.1);
        }
        
        .navbar-dev {
            background: rgba(17, 24, 30, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 30px;
        }
        
        .dev-badge {
            font-family: 'Fira Code', monospace;
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .metric-badge {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        
        .table-dev {
            color: #d1d5db;
        }
        
        .table-dev th {
            border-color: rgba(255, 255, 255, 0.05) !important;
            color: #9ca3af;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-dev td {
            border-color: rgba(255, 255, 255, 0.03) !important;
            font-size: 13px;
            vertical-align: middle;
        }
        
        /* Larger Custom Switch */
        .custom-switch-dev .custom-control-label::before {
            height: 1.5rem !important;
            width: 2.75rem !important;
            border-radius: 1rem !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        .custom-switch-dev .custom-control-label::after {
            width: calc(1.5rem - 4px) !important;
            height: calc(1.5rem - 4px) !important;
            border-radius: 50% !important;
            background-color: #9ca3af !important;
        }
        .custom-switch-dev .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(1.25rem) !important;
            background-color: #ffffff !important;
        }
        .custom-switch-dev .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #8b5cf6 !important;
            border-color: #8b5cf6 !important;
        }
        
        .btn-dev-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border: none; color: #fff;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .btn-dev-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
            color: #fff;
        }
        
        .btn-outline-dev {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: transparent;
            color: #d1d5db;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-dev:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
            border-color: rgba(255,255,255,0.2);
        }
        
        .console-font {
            font-family: 'Fira Code', monospace;
        }
        
        .swal2-popup {
            background: #111827 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 20px !important;
            color: #f3f4f6 !important;
        }
    </style>
</head>
<body>

    <!-- NAV BAR -->
    <nav class="navbar navbar-dev justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="mr-3 bg-primary rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%) !important;">
                <i class="fas fa-terminal text-white"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold tracking-tight">Madrasah Digital</h6>
                <span class="dev-badge"><i class="fas fa-user-shield mr-1"></i> LICENSOR ADMIN</span>
            </div>
        </div>
        
        <div class="d-flex align-items-center">
            <span class="mr-4 text-xs text-muted d-none d-md-inline console-font">Server Time: {{ date('Y-m-d H:i:s') }} UTC</span>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-dev mr-3">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard Klien
            </a>
            <form action="{{ url('seller/logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger px-3 rounded-pill">
                    <i class="fas fa-power-off mr-1"></i> Log Out
                </button>
            </form>
        </div>
    </nav>

    <!-- CONTENT CONTAINER -->
    <div class="container-fluid px-md-5 mb-5">
        
        <!-- WELCOME BANNER -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glow-card p-4 d-flex justify-content-between align-items-center flex-wrap" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);">
                    <div class="mb-3 mb-md-0">
                        <h4 class="font-weight-bold text-white mb-1">Developer & Seller Command Center</h4>
                        <p class="text-muted text-sm mb-0">Kelola dan aktifkan lisensi modul premium berbayar secara mandiri untuk instance sekolah ini.</p>
                    </div>
                    <div>
                        <button onclick="openSimulationModal()" class="btn btn-dev-gradient">
                            <i class="fas fa-money-bill-wave mr-2"></i> Simulasikan Pembayaran Klien
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(count($expiringSoon) > 0)
        <!-- EXPIRING SOON ALERT -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glow-card p-4" style="border: 1px solid rgba(239, 68, 68, 0.4); background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(220, 38, 38, 0.05) 100%);">
                    <div class="d-flex align-items-center mb-3">
                        <div class="metric-badge mr-3" style="background: rgba(239, 68, 68, 0.2); color: #ef4444; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 12px; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);">
                            <i class="fas fa-exclamation-triangle fa-lg animate-pulse"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-white mb-0" style="text-shadow: 0 0 10px rgba(239, 68, 68, 0.3);">⚠️ Perhatian: Masa Aktif Lisensi Segera Habis</h5>
                            <p class="text-muted text-sm mb-0">Beberapa lisensi modul premium sekolah klien akan segera kadaluarsa. Silakan perpanjang durasi lisensi di bawah ini.</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless text-white mb-0" style="background: rgba(0,0,0,0.15); border-radius: 10px;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 11px; color: #9ca3af; text-transform: uppercase;">
                                    <th class="pl-4 py-3">Nama Modul Premium</th>
                                    <th class="py-3">Tanggal Kadaluarsa</th>
                                    <th class="py-3">Sisa Hari</th>
                                    <th class="py-3 text-right pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiringSoon as $item)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px;">
                                    <td class="font-weight-bold pl-4 py-3 text-white">
                                        <i class="fas fa-cube mr-2 text-danger"></i> {{ $item['name'] }}
                                    </td>
                                    <td class="py-3 text-light">
                                        <i class="far fa-calendar-alt mr-2 text-muted"></i> {{ $item['expires_at'] }}
                                    </td>
                                    <td class="py-3">
                                        @if($item['days_left'] <= 0)
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); box-shadow: 0 0 8px rgba(239, 68, 68, 0.3);">
                                                <i class="fas fa-times-circle mr-1"></i> Telah Kadaluarsa
                                            </span>
                                        @elseif($item['days_left'] <= 7)
                                            <span class="badge animate-pulse" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); box-shadow: 0 0 8px rgba(245, 158, 11, 0.3);">
                                                <i class="fas fa-hourglass-half mr-1"></i> {{ $item['days_left'] }} Hari Lagi
                                            </span>
                                        @else
                                            <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.4);">
                                                <i class="fas fa-clock mr-1"></i> {{ $item['days_left'] }} Hari
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right pr-4">
                                        <button onclick="scrollToManualPayment('{{ $item['key'] }}')" class="btn btn-xs btn-outline-danger" style="border-radius: 6px; font-size: 11px;">
                                            <i class="fas fa-sync-alt mr-1"></i> Perpanjang Lisensi
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- STATS / METRICS -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="glow-card p-4 d-flex align-items-center">
                    <div class="metric-badge bg-soft-primary mr-3" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-school fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-muted d-block uppercase font-weight-bold">Klien Terhubung</span>
                        <h6 class="font-weight-bold mb-0 text-white">{{ $stats['school_name'] }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="glow-card p-4 d-flex align-items-center">
                    <div class="metric-badge bg-soft-info mr-3" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-muted d-block uppercase font-weight-bold">Lokasi Server</span>
                        <h6 class="font-weight-bold mb-0 text-white">{{ $stats['city'] }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="glow-card p-4 d-flex align-items-center">
                    <div class="metric-badge bg-soft-success mr-3" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-muted d-block uppercase font-weight-bold">Total Siswa Terdaftar</span>
                        <h6 class="font-weight-bold mb-0 text-white">{{ $stats['total_students'] }} Siswa</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="glow-card p-4 d-flex align-items-center">
                    <div class="metric-badge bg-soft-warning mr-3" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-muted d-block uppercase font-weight-bold">Total Guru Aktif</span>
                        <h6 class="font-weight-bold mb-0 text-white">{{ $stats['total_teachers'] }} Guru</h6>
                    </div>
                </div>
            </div>
        </div>

        @php
            $activeCount = 0;
            if($setting->is_workflow_pro_active) $activeCount++;
            if($setting->is_announcements_pro_active) $activeCount++;
            if($setting->is_teachers_pro_active) $activeCount++;
            if($setting->is_students_pro_active) $activeCount++;
            if($setting->is_curriculum_pro_active) $activeCount++;
            if($setting->is_achievements_pro_active) $activeCount++;
            if($setting->is_cbt_pro_active) $activeCount++;
            if($setting->is_grades_pro_active) $activeCount++;
            if($setting->is_attendance_pro_active) $activeCount++;
            if($setting->is_mail_pro_active) $activeCount++;
            if($setting->is_savings_pro_active) $activeCount++;
            if($setting->is_bos_pro_active) $activeCount++;
            if($setting->is_ppdb_pro_active) $activeCount++;
            if($setting->is_website_pro_active) $activeCount++;
            if($setting->is_wa_gateway_pro_active) $activeCount++;
            if($setting->is_users_pro_active) $activeCount++;
            if($setting->is_system_pro_active) $activeCount++;
            
            $packageName = "Bronze (Trial)";
            $packageColor = "badge-danger";
            if ($activeCount == 17) {
                $packageName = "Platinum Premium Suite (All Open)";
                $packageColor = "badge-primary";
            } elseif ($activeCount >= 12) {
                $packageName = "Gold Premium Suite";
                $packageColor = "badge-warning";
            } elseif ($activeCount >= 1) {
                $packageName = "Silver Premium Suite";
                $packageColor = "badge-success";
            }
        @endphp

        <!-- LICENSE HEALTH PROGRESS & DEVELOPER QUICK ACTIONS -->
        <div class="row mb-4">
            <!-- LICENSE HEALTH PROGRESS & PACKAGE LEVEL -->
            <div class="col-xl-6 mb-4 mb-xl-0">
                <div class="glow-card p-4 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(59, 130, 246, 0.03) 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-heartbeat text-success mr-2"></i> Kesehatan Lisensi Klien</h5>
                        <span class="badge {{ $packageColor }} text-xs px-3 py-2 rounded-pill font-weight-bold">{{ $packageName }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center my-4 justify-content-around">
                        <div class="text-center">
                            <span class="text-xs text-muted d-block uppercase mb-1">Modul Terkunci</span>
                            <h3 class="font-weight-bold text-warning mb-0">{{ 17 - $activeCount }} <span class="text-xs text-muted">/ 17</span></h3>
                        </div>
                        <div class="px-3" style="border-left: 1px solid rgba(255,255,255,0.08); height: 50px;"></div>
                        <div class="text-center">
                            <span class="text-xs text-muted d-block uppercase mb-1">Modul Aktif</span>
                            <h3 class="font-weight-bold text-success mb-0">{{ $activeCount }} <span class="text-xs text-muted">/ 17</span></h3>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="d-flex justify-content-between text-xs font-weight-bold text-muted mb-2">
                            <span>PERSENTASE AKTIVASI FITUR</span>
                            <span class="text-white">{{ round(($activeCount / 17) * 100) }}%</span>
                        </div>
                        <div class="progress rounded-pill bg-dark border border-secondary" style="height: 12px; border-color: rgba(255,255,255,0.08) !important;">
                            <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: {{ ($activeCount / 17) * 100 }}%;" aria-valuenow="{{ ($activeCount / 17) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DEVELOPER QUICK ACTIONS -->
            <div class="col-xl-6">
                <div class="glow-card p-4 h-100" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.03) 0%, rgba(239, 68, 68, 0.03) 100%);">
                    <h5 class="font-weight-bold text-white mb-3"><i class="fas fa-bolt text-warning mr-2"></i> Panel Aksi Cepat Pemilik</h5>
                    <p class="text-muted text-xs mb-4">Gunakan shortcut aksi cepat di bawah ini untuk mengaktifkan seluruh modul premium secara massal atau mereset ulang riwayat transaksi klien.</p>
                    
                    <div class="d-flex flex-wrap" style="gap: 12px;">
                        <button onclick="runQuickAction('unlock_all')" class="btn btn-outline-success font-weight-bold px-3 py-2.5 flex-fill" style="border-radius: 12px; border-width: 2px;">
                            <i class="fas fa-unlock-alt mr-1"></i> Buka Semua Modul
                        </button>
                        <button onclick="runQuickAction('lock_all')" class="btn btn-outline-warning font-weight-bold px-3 py-2.5 flex-fill" style="border-radius: 12px; border-width: 2px;">
                            <i class="fas fa-lock mr-1"></i> Kunci Semua Modul
                        </button>
                        <button onclick="runQuickAction('clear_logs')" class="btn btn-outline-danger font-weight-bold px-3 py-2.5 flex-fill" style="border-radius: 12px; border-width: 2px;">
                            <i class="fas fa-trash-alt mr-1"></i> Bersihkan Log Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- REMOTE DIAGNOSTICS & SYSTEM HEALTH PANEL -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glow-card p-4" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(139, 92, 246, 0.02) 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary flex-wrap">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h5 class="font-weight-bold text-white mb-0"><i class="fas fa-server text-info mr-2"></i> Remote Diagnostics & System Health</h5>
                            <span class="badge badge-info text-xs ml-3" id="db_status_badge"><i class="fas fa-sync fa-spin mr-1"></i> DB: Checking...</span>
                        </div>
                        <div>
                            <button onclick="refreshDiagnostics()" class="btn btn-sm btn-outline-dev mr-2" id="btn-refresh-diag">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh Real-time
                            </button>
                            <button onclick="triggerRemoteBackup()" class="btn btn-sm btn-dev-gradient" id="btn-trigger-backup">
                                <i class="fas fa-file-archive mr-1"></i> Minta Backup Database Jarak Jauh
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Disk Health Bar -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="p-3 border rounded border-secondary bg-dark-50 h-100" style="background: rgba(0,0,0,0.2);">
                                <div class="d-flex justify-content-between align-items-center text-xs font-weight-bold text-muted mb-2">
                                    <span>PENYIMPANAN DISK SERVER</span>
                                    <span class="text-white console-font" id="disk_percent_text">--%</span>
                                </div>
                                <div class="progress rounded-pill bg-dark border border-secondary mb-3" style="height: 10px; border-color: rgba(255,255,255,0.08) !important;">
                                    <div class="progress-bar rounded-pill bg-info" id="disk_progress_bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between text-xs text-muted">
                                    <span>Digunakan: <strong class="text-white" id="disk_used_text">-- GB</strong></span>
                                    <span>Tersedia: <strong class="text-white" id="disk_free_text">-- GB</strong></span>
                                    <span>Total: <strong class="text-white" id="disk_total_text">-- GB</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- RAM Health Bar -->
                        <div class="col-md-6">
                            <div class="p-3 border rounded border-secondary bg-dark-50 h-100" style="background: rgba(0,0,0,0.2);">
                                <div class="d-flex justify-content-between align-items-center text-xs font-weight-bold text-muted mb-2">
                                    <span>PENGGUNAAN MEMORI (RAM) SERVER</span>
                                    <span class="text-white console-font" id="ram_percent_text">--%</span>
                                </div>
                                <div class="progress rounded-pill bg-dark border border-secondary mb-3" style="height: 10px; border-color: rgba(255,255,255,0.08) !important;">
                                    <div class="progress-bar rounded-pill bg-success" id="ram_progress_bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between text-xs text-muted">
                                    <span>Aktif: <strong class="text-white" id="ram_used_text">-- GB</strong></span>
                                    <span>Tersedia: <strong class="text-white" id="ram_free_text">-- GB</strong></span>
                                    <span>Total: <strong class="text-white" id="ram_total_text">-- GB</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Host Environment Specs Grid -->
                    <div class="row mt-4">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <div class="p-2.5 border rounded border-secondary text-center bg-dark-50" style="background: rgba(0,0,0,0.2);">
                                <span class="text-[9px] text-muted font-weight-bold uppercase d-block">OS Platform</span>
                                <span class="font-weight-bold text-white text-xs console-font" id="os_platform_text">--</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <div class="p-2.5 border rounded border-secondary text-center bg-dark-50" style="background: rgba(0,0,0,0.2);">
                                <span class="text-[9px] text-muted font-weight-bold uppercase d-block">PHP Version</span>
                                <span class="font-weight-bold text-white text-xs console-font" id="php_version_text">--</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <div class="p-2.5 border rounded border-secondary text-center bg-dark-50" style="background: rgba(0,0,0,0.2);">
                                <span class="text-[9px] text-muted font-weight-bold uppercase d-block">Laravel Engine</span>
                                <span class="font-weight-bold text-white text-xs console-font" id="laravel_version_text">--</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2.5 border rounded border-secondary text-center bg-dark-50" style="background: rgba(0,0,0,0.2);">
                                <span class="text-[9px] text-muted font-weight-bold uppercase d-block">DB Version</span>
                                <span class="font-weight-bold text-white text-xs console-font" id="db_version_text">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            <!-- LICENSE CONTROL PANEL -->
            <div class="col-xl-6 mb-4">
                <div class="glow-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-key text-warning mr-2"></i> Kontrol Lisensi Modul</h5>
                        <span class="badge badge-info console-font text-xs">Live Database Connection</span>
                    </div>

                    <div style="max-height: 520px; overflow-y: auto; padding-right: 5px;">
                        
                        <!-- MODULE 1: WORKFLOW -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-warning mr-3 mt-1" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-map-marked-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Peta Jalan Admin (Workflow)</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Modul alur kerja tata kelola madrasah kronologis semester 1 & semester 2.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_workflow_pro_active" onchange="toggleLicense('workflow')" {{ $setting->is_workflow_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_workflow_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_workflow_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_workflow">
                                    {{ $setting->is_workflow_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 2: ANNOUNCEMENTS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-info mr-3 mt-1" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                                    <i class="fas fa-bullhorn fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Pengumuman Madrasah</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Portal pengumuman resmi madrasah untuk warga madrasah.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_announcements_pro_active" onchange="toggleLicense('announcements')" {{ $setting->is_announcements_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_announcements_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_announcements_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_announcements">
                                    {{ $setting->is_announcements_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 3: TEACHERS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-primary mr-3 mt-1" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Guru & Kepegawaian (PKG)</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Penilaian kinerja guru (PKG), jurnal, serta rekap kehadiran guru lengkap.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_teachers_pro_active" onchange="toggleLicense('teachers')" {{ $setting->is_teachers_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_teachers_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_teachers_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_teachers">
                                    {{ $setting->is_teachers_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 4: STUDENTS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-success mr-3 mt-1" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-user-graduate fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Manajemen Siswa</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Manajemen siswa aktif, alumni, rombel, mutasi, dan statistik kesiswaan.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_students_pro_active" onchange="toggleLicense('students')" {{ $setting->is_students_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_students_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_students_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_students">
                                    {{ $setting->is_students_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 5: CURRICULUM -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-warning mr-3 mt-1" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-school fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Kurikulum & Jadwal Kelas</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Manajemen kurikulum, mata pelajaran, jam, rombel, dan plotting jadwal mengajar.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_curriculum_pro_active" onchange="toggleLicense('curriculum')" {{ $setting->is_curriculum_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_curriculum_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_curriculum_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_curriculum">
                                    {{ $setting->is_curriculum_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 6: ACHIEVEMENTS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-primary mr-3 mt-1" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <i class="fas fa-star fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Pembiasaan & Prestasi</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Mutabaah tahfidz mingguan, hafalan Al-Qur'an, sertifikat prestasi akademik/non-akademik.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_achievements_pro_active" onchange="toggleLicense('achievements')" {{ $setting->is_achievements_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_achievements_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_achievements_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_achievements">
                                    {{ $setting->is_achievements_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 7: CBT -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-info mr-3 mt-1" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                                    <i class="fas fa-laptop-code fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Ujian & Penilaian (CBT)</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Bank soal CBT, monitor real-time, import template, cetak kartu & berita acara.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_cbt_pro_active" onchange="toggleLicense('cbt')" {{ $setting->is_cbt_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_cbt_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_cbt_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_cbt">
                                    {{ $setting->is_cbt_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 8: GRADES -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-success mr-3 mt-1" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-file-invoice fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Pengolahan Nilai & Rapor</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Penilaian raport K13/Merdeka, nilai ujian manual, rekap semester, dan pencetakan raport.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_grades_pro_active" onchange="toggleLicense('grades')" {{ $setting->is_grades_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_grades_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_grades_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_grades">
                                    {{ $setting->is_grades_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 9: ATTENDANCE -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-warning mr-3 mt-1" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-clipboard-list fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Absensi & Monitoring Wajah AI</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Registrasi wajah, live monitoring absen masuk/pulang, rekap bulanan, dan verifikasi izin.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_attendance_pro_active" onchange="toggleLicense('attendance')" {{ $setting->is_attendance_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_attendance_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_attendance_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_attendance">
                                    {{ $setting->is_attendance_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 10: MAIL -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-danger mr-3 mt-1" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                    <i class="fas fa-envelope fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Layanan Surat & SPPD</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Surat tugas dinas, pembuatan SPPD otomatis, arsip surat masuk/keluar, dan kop surat.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_mail_pro_active" onchange="toggleLicense('mail')" {{ $setting->is_mail_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_mail_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_mail_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_mail">
                                    {{ $setting->is_mail_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 11: SAVINGS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-success mr-3 mt-1" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fas fa-wallet fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Keuangan SPP & Tabungan Siswa</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Manajemen tabungan siswa, tagihan SPP bulanan, slip setoran tunai Rupiah.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_savings_pro_active" onchange="toggleLicense('savings')" {{ $setting->is_savings_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_savings_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_savings_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_savings">
                                    {{ $setting->is_savings_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 12: BOS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-primary mr-3 mt-1" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <i class="fas fa-university fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Dana BOS & Payroll Guru</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Buku kas umum BOS, perencanaan RKAM, komponen biaya, penggajian guru & staf.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_bos_pro_active" onchange="toggleLicense('bos')" {{ $setting->is_bos_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_bos_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_bos_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_bos">
                                    {{ $setting->is_bos_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 13: PPDB -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-warning mr-3 mt-1" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-user-plus fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">PPDB Online</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Penerimaan peserta didik baru, registrasi, kuota & integrasi Midtrans.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_ppdb_pro_active" onchange="toggleLicense('ppdb')" {{ $setting->is_ppdb_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_ppdb_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_ppdb_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_ppdb">
                                    {{ $setting->is_ppdb_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 14: WEBSITE -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-danger mr-3 mt-1" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                    <i class="fas fa-globe fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Website Madrasah</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Halaman depan portal madrasah, posting berita, agenda, pengumuman & galeri.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_website_pro_active" onchange="toggleLicense('website')" {{ $setting->is_website_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_website_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_website_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_website">
                                    {{ $setting->is_website_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 15: WA GATEWAY -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-success mr-3 mt-1" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <i class="fab fa-whatsapp fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">WhatsApp Gateway</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Broadcast pesan otomatis, notifikasi tagihan SPP, sinkron nomor wali murid.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_wa_gateway_pro_active" onchange="toggleLicense('wa_gateway')" {{ $setting->is_wa_gateway_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_wa_gateway_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_wa_gateway_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_wa_gateway">
                                    {{ $setting->is_wa_gateway_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 16: USERS -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-info mr-3 mt-1" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                                    <i class="fas fa-user-shield fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Hak Akses & User Management</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Role & permission detail, pembatasan akses guru/staf, dan jabatan madrasah.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_users_pro_active" onchange="toggleLicense('users')" {{ $setting->is_users_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_users_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_users_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_users">
                                    {{ $setting->is_users_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                        <!-- MODULE 17: SYSTEM -->
                        <div class="p-3 border rounded border-secondary mb-3 d-flex align-items-center justify-content-between bg-dark-50">
                            <div class="d-flex align-items-start">
                                <div class="rounded-circle p-2 bg-soft-warning mr-3 mt-1" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <i class="fas fa-cogs fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-white">Analisis, EMIS & Pengaturan</h6>
                                    <p class="text-muted text-xs mb-0" style="max-width: 320px;">Dashboard statistik visual, sinkronisasi EMIS Kemenag, backup & restore DB.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="custom-control custom-switch custom-switch-dev">
                                    <input type="checkbox" class="custom-control-input" id="is_system_pro_active" onchange="toggleLicense('system')" {{ $setting->is_system_pro_active ? 'checked' : '' }}>
                                    <label class="custom-control-label cursor-pointer" for="is_system_pro_active"></label>
                                </div>
                                <span class="text-[9px] font-weight-bold uppercase d-block mt-1 {{ $setting->is_system_pro_active ? 'text-success' : 'text-warning' }}" id="statusText_system">
                                    {{ $setting->is_system_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- TRANSACTION LOGS -->
            <div class="col-xl-6 mb-4">
                <div class="glow-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-history text-info mr-2"></i> Log Transaksi Lisensi Klien</h5>
                        <span class="badge badge-success text-xs px-3 rounded-pill font-weight-bold">{{ $transactions->count() }} Payments Recorded</span>
                    </div>

                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-dev table-borderless">
                            <thead>
                                <tr>
                                    <th>No Invoice</th>
                                    <th>Nama Modul</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="transactionTableBody">
                                @forelse($transactions as $tx)
                                <tr>
                                    <td class="console-font text-white">{{ $tx->invoice_no }}</td>
                                    <td>{{ $tx->module_name }}</td>
                                    <td class="text-success font-weight-bold">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-success text-[10px] rounded px-2 py-1 font-weight-bold">
                                            <i class="fas fa-check mr-1"></i> {{ $tx->status }}
                                        </span>
                                    </td>
                                    <td class="text-xs text-muted">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 d-block opacity-4"></i>
                                        Belum ada transaksi pembayaran lisensi dari klien.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- NEW ROW FOR MANUAL ACTIVATION REQUESTS QUEUE -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glow-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary flex-wrap">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h5 class="font-weight-bold text-white mb-0"><i class="fas fa-file-invoice-dollar text-warning mr-2"></i> 📥 Permintaan Aktivasi Modul Klien (Manual Verification)</h5>
                            <span class="badge badge-warning text-xs ml-3 font-weight-bold" id="pending_requests_count">{{ $pendingTransactions->count() }} Menunggu Persetujuan</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped mb-0" style="border-radius: 12px; overflow: hidden; background-color: #0c101d;">
                            <thead>
                                <tr class="bg-dark-900 border-secondary">
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">No Invoice</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Modul Premium</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Masa Aktif</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Nominal Bayar</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Metode</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Bukti Transfer</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider text-right">Aksi Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingTransactions as $ptx)
                                <tr id="pending-row-{{ $ptx->id }}">
                                    <td class="console-font text-white font-weight-bold">{{ $ptx->invoice_no }}</td>
                                    <td>{{ $ptx->module_name }}</td>
                                    <td>
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                            {{ $ptx->duration == '30' ? 'Bulanan (30 Hari)' : ($ptx->duration == '365' ? 'Tahunan (365 Hari)' : 'Selamanya (Lifetime)') }}
                                        </span>
                                    </td>
                                    <td class="text-success font-weight-bold">Rp {{ number_format($ptx->amount, 0, ',', '.') }}</td>
                                    <td class="text-xs text-muted">{{ $ptx->payment_method }}</td>
                                    <td>
                                        <a href="{{ asset($ptx->transfer_proof) }}" target="_blank" class="btn btn-xs btn-outline-info rounded px-2">
                                            <i class="fas fa-image mr-1"></i> Lihat Gambar
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <button onclick="approveRequest({{ $ptx->id }}, '{{ $ptx->module_name }}')" class="btn btn-xs btn-success font-weight-bold px-2 py-1 mr-1" style="border-radius: 6px;">
                                            <i class="fas fa-check-circle mr-1"></i> Setujui
                                        </button>
                                        <button onclick="rejectRequest({{ $ptx->id }}, '{{ $ptx->module_name }}')" class="btn btn-xs btn-danger font-weight-bold px-2 py-1" style="border-radius: 6px;">
                                            <i class="fas fa-times-circle mr-1"></i> Tolak
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-double fa-3x mb-3 d-block opacity-4 text-success"></i>
                                        Tidak ada permintaan aktivasi modul klien yang tertunda. Semua bersih!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW ROW FOR DYNAMIC PRICING PANEL & OFFLINE PAYMENTS -->
        <div class="row mt-4">
            
            <!-- LEFT COLUMN: PRICING CONFIGURATION -->
            <div class="col-xl-6 mb-4">
                <div class="glow-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-tags text-warning mr-2"></i> Pengaturan Harga Modul Premium</h5>
                        <span class="badge badge-warning console-font text-xs">Simpan ke DB</span>
                    </div>

                    <form id="priceSettingsForm">
                        @csrf
                        <div class="row" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Peta Jalan (Workflow)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="workflow_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->workflow_price ?? 99000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Pengumuman Madrasah</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="announcements_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->announcements_price ?? 49000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Guru & Kepegawaian (PKG)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="teachers_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->teachers_price ?? 99000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Manajemen Siswa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="students_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->students_price ?? 99000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Kurikulum & Kelas</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="curriculum_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->curriculum_price ?? 119000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Pembiasaan & Prestasi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="achievements_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->achievements_price ?? 79000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Ujian & Penilaian (CBT)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="cbt_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->cbt_price ?? 149000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Pengolahan Nilai & Rapor</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="grades_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->grades_price ?? 129000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Absensi & Monitoring Wajah AI</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="attendance_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->attendance_price ?? 149000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Layanan Surat & SPPD</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="mail_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->mail_price ?? 89000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Keuangan & Tabungan Siswa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="savings_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->savings_price ?? 129000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Dana BOS & Payroll Guru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="bos_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->bos_price ?? 139000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">PPDB Online</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="ppdb_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->ppdb_price ?? 99000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Website Madrasah</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="website_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->website_price ?? 79000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">WhatsApp Gateway</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="wa_gateway_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->wa_gateway_price ?? 199000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Hak Akses & User</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="users_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->users_price ?? 69000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Analisis & Sistem</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                        </div>
                                        <input type="number" name="system_price" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->system_price ?? 149000 }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2 text-right">
                            <button type="submit" class="btn btn-dev-gradient btn-block py-3 font-weight-bold">
                                <i class="fas fa-save mr-2"></i> Simpan Tarif Harga Lisensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: OFFLINE PAYMENT RECORD -->
            <div class="col-xl-6 mb-4">
                <div class="glow-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-file-invoice-dollar text-success mr-2"></i> Pencatatan Pembayaran Offline</h5>
                        <span class="badge badge-success console-font text-xs">Offline Manual Record</span>
                    </div>

                    <form id="manualPaymentForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Pilih Modul Premium</label>
                            <select name="module" id="manual_module_select" class="form-control text-white border-secondary" style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;" onchange="updateManualAmount()">
                                <option value="workflow" data-price="{{ $setting->workflow_price ?? 99000 }}">Peta Jalan Admin</option>
                                <option value="announcements" data-price="{{ $setting->announcements_price ?? 49000 }}">Pengumuman Madrasah</option>
                                <option value="teachers" data-price="{{ $setting->teachers_price ?? 99000 }}">Guru & Kepegawaian (PKG)</option>
                                <option value="students" data-price="{{ $setting->students_price ?? 99000 }}">Manajemen Siswa</option>
                                <option value="curriculum" data-price="{{ $setting->curriculum_price ?? 119000 }}">Kurikulum & Kelas</option>
                                <option value="achievements" data-price="{{ $setting->achievements_price ?? 79000 }}">Pembiasaan & Prestasi</option>
                                <option value="cbt" data-price="{{ $setting->cbt_price ?? 149000 }}">Ujian & Penilaian CBT</option>
                                <option value="grades" data-price="{{ $setting->grades_price ?? 129000 }}">Pengolahan Nilai & Rapor</option>
                                <option value="attendance" data-price="{{ $setting->attendance_price ?? 149000 }}">Absensi & Monitoring Wajah AI</option>
                                <option value="mail" data-price="{{ $setting->mail_price ?? 89000 }}">Layanan Surat & SPPD</option>
                                <option value="savings" data-price="{{ $setting->savings_price ?? 129000 }}">Keuangan & Tabungan Siswa</option>
                                <option value="bos" data-price="{{ $setting->bos_price ?? 139000 }}">Dana BOS & Payroll</option>
                                <option value="ppdb" data-price="{{ $setting->ppdb_price ?? 99000 }}">PPDB Online</option>
                                <option value="website" data-price="{{ $setting->website_price ?? 79000 }}">Website Madrasah</option>
                                <option value="wa_gateway" data-price="{{ $setting->wa_gateway_price ?? 199000 }}">WhatsApp Gateway</option>
                                <option value="users" data-price="{{ $setting->users_price ?? 69000 }}">Hak Akses & User Management</option>
                                <option value="system" data-price="{{ $setting->system_price ?? 149000 }}">Analisis, EMIS & Sistem</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Nominal Pembayaran (Rp)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-dark border-secondary text-white font-weight-bold" style="border-color: rgba(255,255,255,0.08);">Rp</span>
                                </div>
                                <input type="number" name="amount" id="manual_amount_input" class="form-control text-white border-secondary" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 0 10px 10px 0;" value="{{ $setting->workflow_price ?? 99000 }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Masa Aktif / Paket Langganan</label>
                            <select name="duration" class="form-control text-white border-secondary" style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;">
                                <option value="30">Bulanan (30 Hari Masa Aktif)</option>
                                <option value="365">Tahunan (365 Hari Masa Aktif)</option>
                                <option value="lifetime" selected>Selamanya (Lifetime / Tanpa Batas)</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Kode Kupon / Voucher (Opsional)</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="manual_coupon_code" class="form-control text-white border-secondary" placeholder="Contoh: MADRASAHMERDEKA" style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px 0 0 10px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-info px-3" onclick="validateManualCoupon()" style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.08);">Terapkan</button>
                                </div>
                            </div>
                            <small id="manual_coupon_info" class="form-text mt-1 text-xs d-none"></small>
                            <input type="hidden" id="applied_manual_coupon" value="">
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Metode / Catatan Pembayaran</label>
                            <input type="text" name="payment_method" class="form-control text-white border-secondary" placeholder="Contoh: Transfer Bank BCA Manual, Tunai Cash" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;">
                        </div>

                        <button type="submit" class="btn btn-dev-gradient btn-block py-3 font-weight-bold">
                            <i class="fas fa-check-circle mr-2"></i> Aktifkan & Catat Pembayaran Offline
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- NEW ROW FOR OWNER BANK & QRIS SETTINGS -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glow-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-university text-warning mr-2"></i> 🏦 Pengaturan Rekening & QRIS Pembayaran Pemilik</h5>
                        <span class="badge badge-warning console-font text-xs">Penyesuaian Gateway Manual</span>
                    </div>

                    <form id="bankSettingsForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="form-group mb-3">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Nama Bank / Metode Transfer</label>
                                    <input type="text" name="owner_bank_name" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->owner_bank_name ?? 'BANK TRANSFER BCA' }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;">
                                    <small class="text-muted text-[10px]">Contoh: BANK TRANSFER BCA, MANDIRI TRANSFER, dll.</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Nomor Rekening</label>
                                    <input type="text" name="owner_bank_account" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->owner_bank_account ?? '8392-1209-9021' }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Nama Pemilik Rekening (Atas Nama)</label>
                                    <input type="text" name="owner_bank_holder" class="form-control text-white border-secondary bg-dark-50" value="{{ $setting->owner_bank_holder ?? 'PT MARDIK DIGITAL INDONESIA' }}" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px;">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3 text-center border-left border-secondary d-flex flex-column align-items-center justify-content-center" style="border-color: rgba(255,255,255,0.08) !important;">
                                <div class="mb-3">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2 d-block">Preview QRIS Saat Ini</label>
                                    <div class="d-inline-block p-2 bg-white border rounded-15 shadow-sm">
                                        <img id="qris_preview_img" src="{{ $setting->owner_qris_path ?? 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https://mtsbustanulhuda.sch.id/payment' }}" alt="QRIS Preview" class="img-fluid" style="width: 140px; height: 140px; object-fit: contain; border-radius: 8px;">
                                    </div>
                                </div>
                                <div class="form-group w-75">
                                    <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Unggah File QRIS Baru (Opsional)</label>
                                    <div class="custom-file bg-dark">
                                        <input type="file" name="owner_qris_file" class="custom-file-input" id="owner_qris_file" onchange="previewQRIS(this)">
                                        <label class="custom-file-label text-left text-muted bg-dark" for="owner_qris_file" id="qris_file_label" style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">Pilih gambar QRIS...</label>
                                    </div>
                                    <small class="text-muted text-[10px] d-block mt-2">Format: JPG, PNG (Max 2MB). Kosongkan jika tidak ingin mengubah QRIS.</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-right border-top border-secondary pt-3 mt-2" style="border-color: rgba(255,255,255,0.08) !important;">
                            <button type="submit" class="btn btn-dev-gradient px-5 py-3 font-weight-bold" style="border-radius: 12px;">
                                <i class="fas fa-save mr-2"></i> Simpan Rekening & QRIS Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- NEW ROW FOR COUPON MANAGEMENT SYSTEM -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glow-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                        <h5 class="font-weight-bold text-white"><i class="fas fa-ticket-alt text-info mr-2"></i> 🎫 Manajemen Kupon & Voucher Diskon</h5>
                        <button type="button" class="btn btn-info font-weight-bold px-3 py-2" data-toggle="modal" data-target="#modal-create-coupon" style="border-radius: 10px;">
                            <i class="fas fa-plus mr-1"></i> Buat Kupon Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover table-striped mb-0" style="border-radius: 12px; overflow: hidden; background-color: #0c101d;">
                            <thead>
                                <tr class="bg-dark-900 border-secondary">
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Kode Kupon</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Tipe Potongan</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Nilai Diskon</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Masa Berlaku</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Dipakai</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider">Status</th>
                                    <th class="border-secondary text-muted uppercase text-[10px] tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr id="coupon-row-{{ $coupon->id }}">
                                    <td class="console-font text-white font-weight-bold">
                                        <span class="px-2 py-1 rounded bg-dark border border-secondary text-info">{{ $coupon->code }}</span>
                                    </td>
                                    <td>
                                        @if($coupon->discount_type === 'percentage')
                                        <span class="badge badge-info px-2 py-1 font-weight-bold">Persentase (%)</span>
                                        @else
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold">Fixed (Nominal)</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        @if($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                        @else
                                        Rp {{ number_format($coupon->discount_value, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="text-xs text-muted">
                                        @if($coupon->expires_at)
                                        {{ $coupon->expires_at->format('d M Y') }}
                                        @else
                                        <span class="text-success font-weight-bold">Lifetime (Selamanya)</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-white">{{ $coupon->used_count }}x</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="switch-coupon-{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }} onchange="toggleCouponStatus({{ $coupon->id }})">
                                            <label class="custom-control-label text-xs text-muted" for="switch-coupon-{{ $coupon->id }}">
                                                <span class="status-label-{{ $coupon->id }} {{ $coupon->is_active ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $coupon->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded px-2" onclick="deleteCoupon({{ $coupon->id }})" title="Hapus Kupon">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-ticket-alt fa-3x mb-3 d-block opacity-4 text-info"></i>
                                        Belum ada kupon promosi yang dibuat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CREATE COUPON MODAL -->
    <div class="modal fade" id="modal-create-coupon" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-white border-secondary" style="border-radius: 20px; background-color: #0d121f; border: 1px solid rgba(255,255,255,0.08);">
                <div class="modal-header border-0 pt-4 px-4 pb-0 bg-transparent">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-ticket-alt text-info mr-2"></i> Buat Kupon Promosi Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form id="createCouponForm" onsubmit="submitCreateCoupon(event)">
                    @csrf
                    <div class="modal-body p-4 bg-transparent">
                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Kode Kupon (Uppercase, Unik)</label>
                            <input type="text" name="code" class="form-control text-white border-secondary bg-dark-50" placeholder="Contoh: MADRASAH20" required style="background-color: #171d2b; text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Tipe Potongan</label>
                            <select name="discount_type" class="form-control text-white border-secondary" required style="background-color: #171d2b; border-color: rgba(255,255,255,0.08);">
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed">Fixed Nominal (Rp)</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Nilai Potongan (Diskon)</label>
                            <input type="number" name="discount_value" class="form-control text-white border-secondary bg-dark-50" placeholder="Masukkan angka saja. Contoh: 20 untuk 20%, atau 149000 untuk Rp 149.000" required style="background-color: #171d2b;">
                        </div>

                        <div class="form-group mb-0">
                            <label class="text-[10px] text-muted font-weight-bold uppercase mb-2">Masa Berlaku Kupon (Opsional)</label>
                            <input type="date" name="expires_at" class="form-control text-white border-secondary bg-dark-50" style="background-color: #171d2b;">
                            <small class="form-text text-muted text-xs">Kosongkan jika ingin kupon berlaku selamanya.</small>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 p-4 pt-0 justify-content-center bg-transparent">
                        <button type="submit" class="btn btn-dev-gradient btn-block py-3 font-weight-bold">
                            <i class="fas fa-plus mr-1"></i> SIMPAN KODE KUPON PROMO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PAYMENT SIMULATION MODAL -->
    <div class="modal fade" id="modal-simulation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-white border-secondary" style="border-radius: 20px; background-color: #0d121f; border: 1px solid rgba(255,255,255,0.08);">
                <div class="modal-header border-0 pt-4 px-4 pb-0 bg-transparent">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-wallet text-primary mr-2"></i> Simulasikan Pembayaran Klien</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-transparent">
                    <p class="text-muted text-xs mb-3">Pilih modul berbayar di bawah ini untuk menyimulasikan pelunasan tagihan dari sekolah klien. Simulasi ini akan mencatat pembayaran sukses di log developer dan membuka modul tersebut di aplikasi klien.</p>
                    
                    <div class="form-group mb-3">
                        <label class="text-[10px] text-muted font-weight-bold tracking-wider uppercase mb-2">Pilih Modul Premium</label>
                        <select class="form-control text-white border-secondary" id="sim_module_select" style="background-color: #171d2b; border-radius: 10px;">
                            <option value="workflow">Peta Jalan Admin (Rp {{ number_format($setting->workflow_price ?? 99000, 0, ',', '.') }})</option>
                            <option value="announcements">Pengumuman Madrasah (Rp {{ number_format($setting->announcements_price ?? 49000, 0, ',', '.') }})</option>
                            <option value="teachers">Guru & Kepegawaian (Rp {{ number_format($setting->teachers_price ?? 99000, 0, ',', '.') }})</option>
                            <option value="students">Manajemen Siswa (Rp {{ number_format($setting->students_price ?? 99000, 0, ',', '.') }})</option>
                            <option value="curriculum">Kurikulum & Kelas (Rp {{ number_format($setting->curriculum_price ?? 119000, 0, ',', '.') }})</option>
                            <option value="achievements">Pembiasaan & Prestasi (Rp {{ number_format($setting->achievements_price ?? 79000, 0, ',', '.') }})</option>
                            <option value="cbt">Ujian & Penilaian CBT (Rp {{ number_format($setting->cbt_price ?? 149000, 0, ',', '.') }})</option>
                            <option value="grades">Pengolahan Nilai & Rapor (Rp {{ number_format($setting->grades_price ?? 129000, 0, ',', '.') }})</option>
                            <option value="attendance">Absensi & Monitoring Wajah AI (Rp {{ number_format($setting->attendance_price ?? 149000, 0, ',', '.') }})</option>
                            <option value="mail">Layanan Surat & SPPD (Rp {{ number_format($setting->mail_price ?? 89000, 0, ',', '.') }})</option>
                            <option value="savings">Keuangan & Tabungan Siswa (Rp {{ number_format($setting->savings_price ?? 129000, 0, ',', '.') }})</option>
                            <option value="bos">Dana BOS & Payroll (Rp {{ number_format($setting->bos_price ?? 139000, 0, ',', '.') }})</option>
                            <option value="ppdb">PPDB Online (Rp {{ number_format($setting->ppdb_price ?? 99000, 0, ',', '.') }})</option>
                            <option value="website">Website Madrasah (Rp {{ number_format($setting->website_price ?? 79000, 0, ',', '.') }})</option>
                            <option value="wa_gateway">WhatsApp Gateway (Rp {{ number_format($setting->wa_gateway_price ?? 199000, 0, ',', '.') }})</option>
                            <option value="users">Hak Akses & User Management (Rp {{ number_format($setting->users_price ?? 69000, 0, ',', '.') }})</option>
                            <option value="system">Analisis, EMIS & Sistem (Rp {{ number_format($setting->system_price ?? 149000, 0, ',', '.') }})</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-[10px] text-muted font-weight-bold tracking-wider uppercase mb-2">Pilih Masa Aktif (Durasi)</label>
                        <select class="form-control text-white border-secondary" id="sim_duration_select" style="background-color: #171d2b; border-radius: 10px;">
                            <option value="30">Bulanan (30 Hari)</option>
                            <option value="365">Tahunan (365 Hari)</option>
                            <option value="lifetime" selected>Lifetime (Selamanya)</option>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-[10px] text-muted font-weight-bold tracking-wider uppercase mb-2">Kode Kupon / Voucher (Opsional)</label>
                        <div class="input-group">
                            <input type="text" id="sim_coupon_code" class="form-control text-white border-secondary bg-dark-50" placeholder="Contoh: CBTGRATIS" style="background-color: #171d2b; border-color: rgba(255,255,255,0.08); border-radius: 10px 0 0 10px;">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info px-3" onclick="validateSimCoupon()" style="border-radius: 0 10px 10px 0; border-color: rgba(255,255,255,0.08);">Terapkan</button>
                            </div>
                        </div>
                        <small id="sim_coupon_info" class="form-text mt-1 text-xs d-none"></small>
                        <input type="hidden" id="applied_sim_coupon" value="">
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0 justify-content-center bg-transparent">
                    <button type="button" onclick="submitSimulation()" class="btn btn-dev-gradient btn-block py-3 font-weight-bold">
                        <i class="fas fa-paper-plane mr-2"></i> KIRIM SIMULASI PEMBAYARAN SUKSES
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLicense(moduleName) {
            $.post('{{ route("seller.toggle_license") }}', {
                _token: '{{ csrf_token() }}',
                module: moduleName
            })
            .done(res => {
                const switchBtn = document.getElementById('is_' + moduleName + '_pro_active');
                const statusText = document.getElementById('statusText_' + moduleName);
                
                switchBtn.checked = res.is_active;
                
                if (res.is_active) {
                    statusText.innerText = 'AKTIF / TERBUKA';
                    statusText.className = 'text-[9px] font-weight-bold uppercase d-block mt-1 text-success';
                } else {
                    statusText.innerText = 'TERKUNCI / BERBAYAR';
                    statusText.className = 'text-[9px] font-weight-bold uppercase d-block mt-1 text-warning';
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            })
            .fail(xhr => {
                Swal.fire('Gagal', 'Terjadi kesalahan sistem saat merubah status lisensi.', 'error');
            });
        }

        function openSimulationModal() {
            $('#modal-simulation').modal('show');
        }

        function submitSimulation() {
            const moduleName = document.getElementById('sim_module_select').value;
            const moduleText = document.getElementById('sim_module_select').options[document.getElementById('sim_module_select').selectedIndex].text;
            const durationVal = document.getElementById('sim_duration_select').value;
            const couponVal = document.getElementById('applied_sim_coupon').value;
            
            $('#modal-simulation').modal('hide');
            
            Swal.fire({
                title: 'Kirim Simulasi?',
                text: "Sistem akan mencatat pembayaran " + moduleText + " sukses dan membuka modul tersebut seketika.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Kirim Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('{{ route("seller.simulate_payment") }}', {
                        _token: '{{ csrf_token() }}',
                        module: moduleName,
                        duration: durationVal,
                        coupon_code: couponVal
                    })
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Sukses!',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', 'Gagal memproses simulasi pembayaran.', 'error');
                    });
                }
            });
        }

        function scrollToManualPayment(moduleKey) {
            const selectEl = document.getElementById('manual_module_select');
            if (selectEl) {
                selectEl.value = moduleKey;
                updateManualAmount();
                
                // Select 30 days active by default when extending expiring module
                const durationSelect = document.querySelector('select[name="duration"]');
                if (durationSelect) {
                    durationSelect.value = '30';
                }
                
                // Scroll smoothly to form
                const formEl = document.getElementById('manualPaymentForm');
                if (formEl) {
                    formEl.scrollIntoView({ behavior: 'smooth' });
                    // Highlight the select
                    selectEl.style.boxShadow = '0 0 20px rgba(239, 68, 68, 0.6)';
                    selectEl.style.borderColor = '#ef4444';
                    setTimeout(() => {
                        selectEl.style.boxShadow = '';
                        selectEl.style.borderColor = 'rgba(255,255,255,0.08)';
                    }, 2500);
                }
            }
        }

        $('#priceSettingsForm').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
            
            $.post('{{ route("seller.update_prices") }}', $(this).serialize())
            .done(res => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                });
            })
            .fail(xhr => {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem saat memperbarui harga.', 'error');
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Simpan Harga Lisensi');
            });
        });

        function previewQRIS(input) {
            const file = input.files[0];
            if (file) {
                $('#qris_file_label').text(file.name);
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#qris_preview_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                $('#qris_file_label').text('Pilih gambar QRIS...');
            }
        }

        $('#bankSettingsForm').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
            
            const formData = new FormData(this);
            
            $.ajax({
                url: '{{ route("seller.update_bank_settings") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem saat memperbarui rekening.', 'error');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Simpan Rekening & QRIS Pembayaran');
                }
            });
        });

        function updateManualAmount() {
            const selectEl = document.getElementById('manual_module_select');
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const price = selectedOpt.getAttribute('data-price');
            document.getElementById('manual_amount_input').value = price;
            
            // Reset manual coupon applied state when selecting another module
            document.getElementById('manual_coupon_code').value = '';
            document.getElementById('applied_manual_coupon').value = '';
            const infoEl = document.getElementById('manual_coupon_info');
            infoEl.classList.add('d-none');
            infoEl.innerText = '';
        }

        $('#manualPaymentForm').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
            
            $.post('{{ route("seller.record_manual_payment") }}', $(this).serialize())
            .done(res => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                });
            })
            .fail(xhr => {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem saat menyimpan pembayaran.', 'error');
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> Aktifkan & Catat Pembayaran Offline');
            });
        });

        function runQuickAction(actionName) {
            let actionText = "";
            let confirmBtnColor = "#10b981";
            
            if (actionName === 'unlock_all') {
                actionText = "mengaktifkan semua lisensi modul premium seketika";
            } else if (actionName === 'lock_all') {
                actionText = "mengunci kembali semua lisensi modul premium";
                confirmBtnColor = "#f59e0b";
            } else if (actionName === 'clear_logs') {
                actionText = "menghapus bersih seluruh riwayat log pembayaran lisensi";
                confirmBtnColor = "#ef4444";
            }
            
            Swal.fire({
                title: 'Konfirmasi Aksi Cepat',
                text: "Apakah Anda yakin ingin " + actionText + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#374151',
                confirmButtonText: 'Ya, Jalankan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.post('{{ route("seller.quick_action") }}', {
                        _token: '{{ csrf_token() }}',
                        action: actionName
                    })
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem saat menjalankan aksi cepat.', 'error');
                    });
                }
            });
        }

        // REMOTE SYSTEM DIAGNOSTICS & SECURE DATABASE BACKUP ACTIONS
        function refreshDiagnostics() {
            const btn = $('#btn-refresh-diag');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Syncing...');

            $.post('{{ route("seller.remote_diagnostics") }}', {
                _token: '{{ csrf_token() }}'
            })
            .done(res => {
                if (res.success) {
                    const data = res.data;
                    
                    // DB status badge
                    const dbBadge = $('#db_status_badge');
                    if (data.db_status === 'ACTIVE') {
                        dbBadge.removeClass().addClass('badge badge-success text-xs ml-3').html('<i class="fas fa-check-circle mr-1"></i> DB: Active');
                    } else {
                        dbBadge.removeClass().addClass('badge badge-danger text-xs ml-3').html('<i class="fas fa-times-circle mr-1"></i> DB: Disconnected');
                    }

                    // Host Specs
                    $('#os_platform_text').text(data.os_platform);
                    $('#php_version_text').text('v' + data.php_version);
                    $('#laravel_version_text').text('v' + data.laravel_version);
                    $('#db_version_text').text(data.db_version);

                    // Disk Stats
                    $('#disk_percent_text').text(data.disk_percent + '%');
                    $('#disk_progress_bar').css('width', data.disk_percent + '%').attr('aria-valuenow', data.disk_percent);
                    $('#disk_used_text').text(data.disk_used);
                    $('#disk_free_text').text(data.disk_free);
                    $('#disk_total_text').text(data.disk_total);

                    // RAM Stats
                    $('#ram_percent_text').text(data.ram_percent + '%');
                    $('#ram_progress_bar').css('width', data.ram_percent + '%').attr('aria-valuenow', data.ram_percent);
                    $('#ram_used_text').text(data.ram_used);
                    $('#ram_free_text').text(data.ram_free);
                    $('#ram_total_text').text(data.ram_total);
                }
            })
            .fail(xhr => {
                Swal.fire('Gagal', 'Terjadi kesalahan sistem saat mengambil data diagnostik.', 'error');
            })
            .always(() => {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Refresh Real-time');
            });
        }

        function triggerRemoteBackup() {
            Swal.fire({
                title: 'Minta Backup Database?',
                text: "Sistem klien akan memproses dump seluruh skema tabel & baris data menjadi file SQL aman secara instan.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Proses Backup',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Dump Database...',
                        text: 'Membaca skema tabel & mengekspor data klien...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post('{{ route("seller.remote_backup") }}', {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(res => {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Backup Berhasil!',
                                text: res.message + ' Mengunduh file otomatis sekarang...',
                                showConfirmButton: false,
                                timer: 2500
                            }).then(() => {
                                // Trigger secure file download
                                window.location.href = res.download_url;
                            });
                        } else {
                            Swal.fire('Gagal', res.message || 'Gagal membuat dump database.', 'error');
                        }
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal memproses backup database klien.', 'error');
                    });
                }
            });
        }

        // COUPON & DISCOUNT MANAGEMENT JAVASCRIPT ENGINE
        function submitCreateCoupon(e) {
            e.preventDefault();
            const form = $('#createCouponForm');
            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.post('{{ route("seller.coupon.create") }}', form.serialize())
            .done(res => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.reload();
                });
            })
            .fail(xhr => {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal menyimpan kupon baru.', 'error');
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> SIMPAN KODE KUPON PROMO');
            });
        }

        function toggleCouponStatus(id) {
            $.post('{{ url("/seller/coupons") }}/' + id + '/toggle', {
                _token: '{{ csrf_token() }}'
            })
            .done(res => {
                const switchBtn = document.getElementById('switch-coupon-' + id);
                const label = document.querySelector('.status-label-' + id);
                
                switchBtn.checked = res.is_active;
                if (res.is_active) {
                    label.innerText = 'AKTIF';
                    label.className = 'status-label-' + id + ' text-success font-weight-bold';
                } else {
                    label.innerText = 'NONAKTIF';
                    label.className = 'status-label-' + id + ' text-danger font-weight-bold';
                }

                // Show mini toast instead of disruptive modal
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: res.message
                });
            })
            .fail(xhr => {
                Swal.fire('Gagal', 'Terjadi kesalahan sistem saat merubah status kupon.', 'error');
            });
        }

        function deleteCoupon(id) {
            Swal.fire({
                title: 'Hapus Kupon?',
                text: "Kupon promosi ini akan dihapus selamanya dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("/seller/coupons") }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    })
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            $('#coupon-row-' + id).fadeOut(500, function() {
                                $(this).remove();
                            });
                        });
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', 'Terjadi kesalahan sistem saat menghapus kupon.', 'error');
                    });
                }
            });
        }

        function validateManualCoupon() {
            const couponInput = document.getElementById('manual_coupon_code');
            const code = couponInput.value.trim().toUpperCase();
            const moduleSelect = document.getElementById('manual_module_select');
            const selectedOpt = moduleSelect.options[moduleSelect.selectedIndex];
            const originalPrice = parseFloat(selectedOpt.getAttribute('data-price') || 0);
            
            const infoEl = document.getElementById('manual_coupon_info');
            const appliedInput = document.getElementById('applied_manual_coupon');

            if (!code) {
                infoEl.classList.remove('d-none', 'text-success');
                infoEl.classList.add('text-warning');
                infoEl.innerText = 'Silakan masukkan kode kupon terlebih dahulu.';
                return;
            }

            infoEl.classList.remove('d-none', 'text-success', 'text-warning', 'text-danger');
            infoEl.classList.add('text-muted');
            infoEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memvalidasi kupon...';

            $.post('{{ route("seller.coupon.validate") }}', {
                _token: '{{ csrf_token() }}',
                code: code,
                price: originalPrice
            })
            .done(res => {
                infoEl.className = 'form-text mt-1 text-xs text-success font-weight-bold';
                infoEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> ' + res.message;
                appliedInput.value = res.code;

                // Visually update the price display: strikethrough original and glow discounted
                const amountInput = document.getElementById('manual_amount_input');
                amountInput.value = res.final_price;
                
                // Add aesthetic text inside coupon info
                infoEl.innerHTML += ' (Harga Akhir: Rp ' + Number(res.final_price).toLocaleString('id-ID') + ')';
            })
            .fail(xhr => {
                infoEl.className = 'form-text mt-1 text-xs text-danger font-weight-bold';
                infoEl.innerHTML = '<i class="fas fa-times-circle mr-1"></i> ' + (xhr.responseJSON?.message || 'Kode kupon tidak valid.');
                appliedInput.value = '';
                
                // Reset amount to original price
                document.getElementById('manual_amount_input').value = originalPrice;
            });
        }

        function validateSimCoupon() {
            const couponInput = document.getElementById('sim_coupon_code');
            const code = couponInput.value.trim().toUpperCase();
            
            // Map module value to select pricing
            const moduleSelect = document.getElementById('sim_module_select');
            const val = moduleSelect.value;
            
            // Fetch price from form input price configuration dynamically
            let originalPrice = 0;
            const priceInput = document.querySelector('input[name="' + val + '_price"]');
            if (priceInput) {
                originalPrice = parseFloat(priceInput.value);
            } else {
                originalPrice = 99000; // default safe fallback
            }

            const infoEl = document.getElementById('sim_coupon_info');
            const appliedInput = document.getElementById('applied_sim_coupon');

            if (!code) {
                infoEl.classList.remove('d-none', 'text-success');
                infoEl.classList.add('text-warning');
                infoEl.innerText = 'Silakan masukkan kode kupon terlebih dahulu.';
                return;
            }

            infoEl.classList.remove('d-none', 'text-success', 'text-warning', 'text-danger');
            infoEl.classList.add('text-muted');
            infoEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memvalidasi kupon...';

            $.post('{{ route("seller.coupon.validate") }}', {
                _token: '{{ csrf_token() }}',
                code: code,
                price: originalPrice
            })
            .done(res => {
                infoEl.className = 'form-text mt-1 text-xs text-success font-weight-bold';
                infoEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> ' + res.message;
                appliedInput.value = res.code;
            })
            .fail(xhr => {
                infoEl.className = 'form-text mt-1 text-xs text-danger font-weight-bold';
                infoEl.innerHTML = '<i class="fas fa-times-circle mr-1"></i> ' + (xhr.responseJSON?.message || 'Kode kupon tidak valid.');
                appliedInput.value = '';
            });
        }

        function approveRequest(id, moduleName) {
            Swal.fire({
                title: 'Setujui Aktivasi?',
                text: 'Apakah Anda yakin ingin menyetujui aktivasi modul "' + moduleName + '" dan mengaktifkan lisensinya di klien?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Menyetujui pembayaran & mengaktifkan modul...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post('/seller/transactions/' + id + '/approve', {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Disetujui!',
                            text: res.message,
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal memproses persetujuan.', 'error');
                    });
                }
            });
        }

        function rejectRequest(id, moduleName) {
            Swal.fire({
                title: 'Tolak Aktivasi?',
                text: 'Apakah Anda yakin ingin menolak pengajuan aktivasi modul "' + moduleName + '"? Klien akan diminta mengunggah bukti baru.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Menolak pengajuan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post('/seller/transactions/' + id + '/reject', {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(res => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Ditolak!',
                            text: res.message,
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .fail(xhr => {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal memproses penolakan.', 'error');
                    });
                }
            });
        }

        // Auto initialization
        $(document).ready(function() {
            refreshDiagnostics();
        });
    </script>

</body>
</html>
