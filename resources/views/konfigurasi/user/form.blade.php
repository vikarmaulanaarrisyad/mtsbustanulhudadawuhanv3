<x-modal data-backdrop="static" data-keyboard="false" size="modal-xl">
    <x-slot name="title">
        <span class="text-white"><i class="fas fa-user-shield mr-2"></i> Konfigurasi Akun & Hak Akses</span>
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-md-7">
            {{-- INFORMASI DASAR --}}
            <div class="card border-0 shadow-sm premium-modal-card mb-4">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h3 class="card-title text-sm font-weight-bold text-slate"><i class="fas fa-id-card mr-2 text-primary"></i> IDENTITAS PENGGUNA</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="premium-label">NAMA LENGKAP</label>
                                <div class="input-group input-group-alternative shadow-none">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-user text-xs"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-0 bg-light" name="name" id="name" placeholder="Nama lengkap user..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="premium-label">USERNAME</label>
                                <div class="input-group input-group-alternative shadow-none">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-at text-xs"></i></span>
                                    </div>
                                    <input type="text" class="form-control border-0 bg-light" name="username" id="username" placeholder="Username login..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="premium-label">EMAIL AKTIF</label>
                                <div class="input-group input-group-alternative shadow-none">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-xs"></i></span>
                                    </div>
                                    <input type="email" class="form-control border-0 bg-light" name="email" id="email" placeholder="example@mail.com" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="passwordRow">
                            <div class="form-group mb-3">
                                <label class="premium-label">PASSWORD</label>
                                <div class="input-group input-group-alternative shadow-none">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-xs"></i></span>
                                    </div>
                                    <input type="password" class="form-control border-0 bg-light" name="password" id="password" placeholder="Min. 6 karakter" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="premium-label">ROLE UTAMA (HAK AKSES DASAR)</label>
                        <select id="roles" class="form-control select2-premium" name="roles" style="width: 100%"></select>
                    </div>
                </div>
            </div>

            {{-- AKSES MENU GRANULAR --}}
            <div class="card border-0 shadow-sm premium-modal-card">
                <div class="card-header py-3 bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-sm font-weight-bold text-slate"><i class="fas fa-tasks mr-2 text-warning"></i> MANAJEMEN IZIN PER MODUL</h3>
                    <div class="custom-control custom-checkbox select-all-wrapper">
                        <input class="custom-control-input" type="checkbox" id="checkAllPermissions">
                        <label for="checkAllPermissions" class="custom-control-label text-[10px] font-weight-bold text-muted uppercase">Pilih Semua</label>
                    </div>
                </div>
                <div class="card-body pt-0 p-0 overflow-auto" style="max-height: 500px;">
                    <table class="table table-sm table-hover mb-0 permission-granular-table">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="pl-4 py-2 border-0" width="30%"><span class="text-[10px] font-weight-bold text-muted uppercase letter-spacing-1">MODUL / MENU</span></th>
                                <th class="text-center py-2 border-0"><span class="text-[10px] font-weight-bold text-muted uppercase">VIEW</span></th>
                                <th class="text-center py-2 border-0"><span class="text-[10px] font-weight-bold text-muted uppercase">ADD</span></th>
                                <th class="text-center py-2 border-0"><span class="text-[10px] font-weight-bold text-muted uppercase">EDIT</span></th>
                                <th class="text-center py-2 border-0"><span class="text-[10px] font-weight-bold text-muted uppercase">DEL</span></th>
                                <th class="text-center py-2 border-0"><span class="text-[10px] font-weight-bold text-muted uppercase">VERIF</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- DASHBOARD SPECIAL --}}
                            <tr class="bg-light-soft">
                                <td class="pl-4 font-weight-bold text-xs"><i class="fas fa-tachometer-alt mr-2 text-primary"></i> Dashboard Access</td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="p_dash_admin" value="dashboard.admin">
                                        <label class="custom-control-label" for="p_dash_admin"></label>
                                    </div>
                                    <small class="d-block text-[8px] mt-n1 text-muted">Adm</small>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-primary">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="p_dash_guru" value="dashboard.guru">
                                        <label class="custom-control-label" for="p_dash_guru"></label>
                                    </div>
                                    <small class="d-block text-[8px] mt-n1 text-muted">Guru</small>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-info">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="p_dash_siswa" value="dashboard.siswa">
                                        <label class="custom-control-label" for="p_dash_siswa"></label>
                                    </div>
                                    <small class="d-block text-[8px] mt-n1 text-muted">Sis</small>
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-warning">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="p_dash_ppdb" value="dashboard.ppdb">
                                        <label class="custom-control-label" for="p_dash_ppdb"></label>
                                    </div>
                                    <small class="d-block text-[8px] mt-n1 text-muted">PPDB</small>
                                </td>
                                <td class="text-center opacity-0">-</td>
                            </tr>

                            @php
                                $granularModules = [
                                    ['name' => 'PPDB Online', 'prefix' => 'ppdb', 'icon' => 'fa-user-plus', 'verify' => true],
                                    ['name' => 'Data Guru & Staf', 'prefix' => 'teacher', 'icon' => 'fa-users'],
                                    ['name' => 'Data Siswa', 'prefix' => 'student', 'icon' => 'fa-user-graduate'],
                                    ['name' => 'Data Alumni', 'prefix' => 'alumni', 'icon' => 'fa-user-tag'],
                                    ['name' => 'Manajemen Kelas', 'prefix' => 'class-group', 'icon' => 'fa-school'],
                                    ['name' => 'Mata Pelajaran', 'prefix' => 'subjects', 'icon' => 'fa-book'],
                                    ['name' => 'Jadwal Pelajaran', 'prefix' => 'class-schedules', 'icon' => 'fa-calendar-alt'],
                                    ['name' => 'Nilai Siswa', 'prefix' => 'grades', 'icon' => 'fa-file-invoice'],
                                    ['name' => 'Presensi Guru', 'prefix' => 'teacher-attendance', 'icon' => 'fa-user-clock'],
                                    ['name' => 'Presensi Siswa', 'prefix' => 'student-attendance', 'icon' => 'fa-clock'],
                                    ['name' => 'Layanan Surat', 'prefix' => 'mail', 'icon' => 'fa-envelope-open-text'],
                                    ['name' => 'Berita & Konten', 'prefix' => 'posts', 'icon' => 'fa-newspaper'],
                                    ['name' => 'Pengaturan App', 'prefix' => 'setting', 'icon' => 'fa-cogs'],
                                    ['name' => 'Manajemen User', 'prefix' => 'user', 'icon' => 'fa-user-cog'],
                                    ['name' => 'Role & Izin', 'prefix' => 'role', 'icon' => 'fa-user-lock'],
                                ];
                            @endphp

                            @foreach($granularModules as $mod)
                            <tr>
                                <td class="pl-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-sm mr-2 text-center text-muted opacity-6"><i class="fas {{ $mod['icon'] }} text-xs"></i></div>
                                        <span class="text-xs font-weight-600 text-slate">{{ $mod['name'] }}</span>
                                    </div>
                                </td>
                                {{-- VIEW --}}
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="perm_{{ $mod['prefix'] }}_view" value="{{ $mod['prefix'] }}.view">
                                        <label class="custom-control-label" for="perm_{{ $mod['prefix'] }}_view"></label>
                                    </div>
                                </td>
                                {{-- CREATE --}}
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-primary">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="perm_{{ $mod['prefix'] }}_create" value="{{ $mod['prefix'] }}.create">
                                        <label class="custom-control-label" for="perm_{{ $mod['prefix'] }}_create"></label>
                                    </div>
                                </td>
                                {{-- EDIT --}}
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-info">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="perm_{{ $mod['prefix'] }}_edit" value="{{ $mod['prefix'] }}.edit">
                                        <label class="custom-control-label" for="perm_{{ $mod['prefix'] }}_edit"></label>
                                    </div>
                                </td>
                                {{-- DELETE --}}
                                <td class="text-center">
                                    <div class="custom-control custom-switch custom-switch-on-danger">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="perm_{{ $mod['prefix'] }}_delete" value="{{ $mod['prefix'] }}.delete">
                                        <label class="custom-control-label" for="perm_{{ $mod['prefix'] }}_delete"></label>
                                    </div>
                                </td>
                                {{-- VERIFY --}}
                                <td class="text-center">
                                    @if(isset($mod['verify']) && $mod['verify'])
                                    <div class="custom-control custom-switch custom-switch-on-warning">
                                        <input type="checkbox" class="custom-control-input menu-permission-check" id="perm_{{ $mod['prefix'] }}_verify" value="{{ $mod['prefix'] }}.verify">
                                        <label class="custom-control-label" for="perm_{{ $mod['prefix'] }}_verify"></label>
                                    </div>
                                    @else
                                    <span class="text-muted opacity-2">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm premium-modal-card h-100 mb-0 bg-slate-dark text-white overflow-hidden">
                <div class="card-header py-3 border-bottom-0 bg-transparent d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-sm font-weight-bold text-white"><i class="fas fa-list-check mr-2 text-gold"></i> SUMMARY IZIN</h3>
                    <span class="badge badge-gold badge-pill px-3 font-weight-bold text-[9px]"><span id="checkedCountDisplay">0</span> SELECTED</span>
                </div>
                <div class="card-body pt-0 d-flex flex-column">
                    <div class="form-group mb-4">
                        <label class="text-[10px] font-weight-bold uppercase opacity-6 mb-2 letter-spacing-1">Advanced Filter (Izin Spesifik)</label>
                        <select id="permission_ids" class="form-control select2-midnight" name="permission_ids[]" multiple="multiple" style="width: 100%">
                            @foreach($permissionGroups as $group)
                                <optgroup label="{{ strtoupper($group->name) }}">
                                    @foreach($group->permissions as $permission)
                                        <option value="{{ $permission->id }}" data-name="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="permission-status-box mt-auto p-4 rounded-xl text-center" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1)">
                        <div class="mb-3">
                            <i class="fas fa-shield-virus fa-4x text-gold opacity-3"></i>
                        </div>
                        <h6 class="text-sm font-weight-bold mb-2">Security Override</h6>
                        <p class="text-[11px] opacity-6 mb-0">Toggle di sisi kiri adalah pintasan cepat untuk izin utama. Izin yang tidak terdaftar di tabel dapat dicari secara manual melalui box filter di atas.</p>
                    </div>

                    <div class="mt-4 alert border-0 py-3 rounded-lg" style="background: rgba(234, 179, 8, 0.1); color: #eab308">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-2 fa-lg"></i>
                            <div class="text-[10px] font-weight-bold uppercase letter-spacing-1">Important Note</div>
                        </div>
                        <p class="text-[11px] opacity-8 mb-0">Perubahan hak akses akan berdampak langsung setelah user melakukan refresh halaman atau login ulang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="d-flex justify-content-between w-100 align-items-center">
            <p class="mb-0 text-[10px] text-muted font-weight-bold uppercase letter-spacing-1">
                <i class="fas fa-info-circle mr-1"></i> Mode Konfigurasi Administrator
            </p>
            <div>
                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary rounded-pill px-4 font-weight-bold text-xs uppercase letter-spacing-1 mr-2">
                    <i class="fas fa-times mr-2"></i> Batal
                </button>
                <button type="button" onclick="submitForm(this.form)" class="btn btn-midnight rounded-pill px-5 shadow-lg font-weight-bold text-xs uppercase letter-spacing-1" id="submitBtn">
                    <span id="spinner-border" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true" style="display: none;"></span>
                    <i class="fas fa-check-circle mr-2"></i> Update User Access
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        /* MIDNIGHT MODAL PREMIUM STYLE */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .modal-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: none; padding: 1.5rem 2rem; }
        .modal-body { background: #f8fafc; padding: 1.5rem 2rem; }
        .modal-footer { background: #fff; border-top: 1px solid #f1f5f9; padding: 1.2rem 2rem; }
        .close { color: #fff; opacity: 0.8; text-shadow: none; }

        .premium-modal-card { border-radius: 15px; overflow: hidden; }
        .premium-label { font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 1px; margin-bottom: 8px; display: block; }
        .bg-slate-dark { background: #0f172a !important; }
        .text-gold { color: #eab308 !important; }
        .badge-gold { background: #eab308; color: #0f172a; }
        .text-slate { color: #1e293b !important; }
        
        .section-title-premium { border-left: 3px solid #eab308; }
        .letter-spacing-1 { letter-spacing: 1px; }
        .text-[10px] { font-size: 10px; }
        .text-[8px] { font-size: 8px; }
        .text-[11px] { font-size: 11px; }
        .text-[9px] { font-size: 9px; }
        .font-weight-600 { font-weight: 600; }

        .bg-light-soft { background: #f8fafc; }
        
        /* TABLE STYLING */
        .permission-granular-table thead th { border-bottom: 2px solid #f1f5f9; background: #fff; z-index: 10; }
        .permission-granular-table td { vertical-align: middle; border-top: 1px solid #f1f5f9; }
        .permission-granular-table tr:hover { background-color: #f1f5f9; }

        /* SWITCH SIZING */
        .custom-switch .custom-control-label::before { height: 1.2rem; width: 2.1rem; border-radius: 1rem; }
        .custom-switch .custom-control-label::after { width: calc(1.2rem - 4px); height: calc(1.2rem - 4px); border-radius: 1rem; }
        .custom-switch .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(0.9rem); }

        /* BUTTONS */
        .btn-midnight { background: #0f172a; color: #fff; border: none; }
        .btn-midnight:hover { background: #1e293b; color: #fff; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2); }

        /* SELECT2 MIDNIGHT */
        .select2-midnight + .select2-container--bootstrap4 .select2-selection {
            background-color: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #fff !important;
            border-radius: 12px;
            padding: 5px;
        }
        .select2-midnight + .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background-color: #334155 !important;
            border: none !important;
            color: #fff !important;
            border-radius: 6px;
            font-size: 11px;
            padding: 2px 10px;
        }

        .select-all-wrapper {
            background: #f8fafc;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .icon-sm { width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 6px; }
    </style>
</x-modal>
