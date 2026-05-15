@extends('layouts.ppdb')

@section('title', 'Dashboard PPDB')

@section('content')


    @if(!$ppdbOpen && !$registrant)
        {{-- PPDB BELUM BUKA (PREMIUM MOBILE VIEW) --}}
        <div class="ppdb-closed-wrapper d-flex flex-column align-items-center justify-content-center px-4" style="min-height: 80vh;">
            <div class="animate-bounce-slow mb-5">
                <div class="icon-container-premium">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
            </div>
            
            <div class="glass-card p-6 text-center shadow-2xl border-0 animate-fade-in" style="border-radius: 2.5rem; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); max-width: 400px;">
                <h4 class="font-weight-black text-slate-800 mb-3">Pendaftaran Belum Dibuka</h4>
                <p class="text-slate-500 font-medium mb-6">Sabar ya! Saat ini pendaftaran santri baru belum dibuka. Silakan pantau terus informasi di sosial media kami atau hubungi admin.</p>
                
                <div class="d-flex flex-column gap-3 w-100">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->phone ?? '628123456789') }}" target="_blank" class="btn-premium-action py-4 text-white font-bold no-underline">
                        <i class="fab fa-whatsapp mr-2"></i> TANYA ADMIN
                    </a>
                    <button onclick="window.location.reload()" class="btn-premium-outline py-3 text-indigo-600 font-bold bg-transparent">
                        <i class="fas fa-sync-alt mr-2"></i> CEK BERKALA
                    </button>
                </div>
            </div>
        </div>

        <style>
            .icon-container-premium {
                width: 120px; height: 120px;
                background: linear-gradient(135deg, #6366f1, #4338ca);
                border-radius: 35px;
                display: flex; align-items: center; justify-content: center;
                font-size: 3.5rem;
                box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
                position: relative;
            }
            .icon-container-premium::after {
                content: '';
                position: absolute;
                inset: -5px;
                border: 2px solid #6366f1;
                border-radius: 40px;
                opacity: 0.3;
                animation: pulse-border 2s infinite;
            }
            @keyframes pulse-border {
                0% { transform: scale(1); opacity: 0.3; }
                50% { transform: scale(1.1); opacity: 0; }
                100% { transform: scale(1); opacity: 0.3; }
            }
            .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
            @keyframes bounce-slow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-15px); }
            }
            .btn-premium-action {
                background: linear-gradient(135deg, #6366f1, #4338ca);
                border-radius: 1.2rem;
                display: block;
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
                border: none;
            }
            .btn-premium-action:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4); color: white; }
            .btn-premium-outline {
                border: 2px solid #e2e8f0;
                border-radius: 1.2rem;
                transition: all 0.2s;
            }
            .btn-premium-outline:hover { border-color: #6366f1; background: #f5f3ff; }
            
            .font-weight-black { font-weight: 900; }
            .badge-light-indigo { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); }
            .object-cover { object-fit: cover; }
            .btn-soft-indigo { background: rgba(99, 102, 241, 0.1); color: #4f46e5; border: none; }
            .btn-soft-indigo:hover { background: rgba(99, 102, 241, 0.2); }
            .btn-outline-indigo { border: 2px solid #e2e8f0; color: #4f46e5; }
            .btn-outline-indigo:hover { border-color: #6366f1; background: #f5f3ff; }
            .truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        </style>


    @elseif($registrant)
        {{-- PREMIUM UNIFIED DASHBOARD HERO --}}
        <div class="stu-new-header mb-8">
            {{-- Top Row: Avatar & Greeting --}}
            <div class="d-flex justify-content-between align-items-center mb-8">
                <div class="d-flex align-items-center">
                    <div class="stu-avatar-box mr-4">
                        <div class="stu-avatar-text">{{ substr($user->name, 0, 2) }}</div>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 10px; letter-spacing: 2px;">SELAMAT DATANG</p>
                        <h3 class="text-white font-weight-black mb-1">{{ $user->name }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="stu-dot mr-2" style="background: #10b981; box-shadow: 0 0 12px #10b981;"></span>
                            <span class="text-white-50 font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                                PENDAFTAR TERVERIFIKASI
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stu-header-icon" onclick="confirmLogout()">
                    <i class="fas fa-power-off"></i>
                </div>
            </div>

            {{-- Middle Row: Status Card --}}
            <div class="glass-card p-5 mb-8" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); border-radius: 2rem;">
                <div class="d-flex align-items-center">
                    <div class="mr-4" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-id-card text-white font-size-lg"></i>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold small">NOMOR REGISTRASI</p>
                        <h4 class="text-white font-weight-black mb-0" style="letter-spacing: 1px;">
                            {{ $registrant->registration_number ?? 'MENGALOKASI...' }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Bottom Row: Premium Stepper --}}
            <div class="stu-stepper-container">
                <div class="ppdb-main-stepper d-flex align-items-center justify-content-between">
                    <div class="step-item {{ $currentStep >= 1 ? 'active' : '' }}">
                        <div class="step-icon"><i class="fas fa-user-edit"></i></div>
                        <div class="step-label">Biodata</div>
                    </div>
                    <div class="step-line {{ $currentStep > 1 ? 'active' : '' }}"></div>
                    <div class="step-item {{ $currentStep >= 2 ? 'active' : '' }}">
                        <div class="step-icon"><i class="fas fa-file-upload"></i></div>
                        <div class="step-label">Berkas</div>
                    </div>
                    <div class="step-line {{ $currentStep > 2 ? 'active' : '' }}"></div>
                    <div class="step-item {{ $currentStep >= 3 ? 'active' : '' }}">
                        <div class="step-icon"><i class="fas fa-search"></i></div>
                        <div class="step-label">Seleksi</div>
                    </div>
                    <div class="step-line {{ $currentStep > 3 ? 'active' : '' }}"></div>
                    <div class="step-item {{ $currentStep >= 4 ? 'active' : '' }}">
                        <div class="step-icon"><i class="fas fa-award"></i></div>
                        <div class="step-label">Lulus</div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .ppdb-main-stepper .step-item { display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; flex: 1; }
            .ppdb-main-stepper .step-icon { width: 42px; height: 42px; border-radius: 12px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: 8px; transition: 0.4s; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(5px); }
            .ppdb-main-stepper .step-label { font-size: 10px; font-weight: 800; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.5px; }
            .ppdb-main-stepper .step-line { flex: 1; height: 2px; background: rgba(255,255,255,0.1); margin-top: -30px; border-radius: 10px; position: relative; z-index: 1; }
            .ppdb-main-stepper .step-item.active .step-icon { background: white; color: #6366f1; border-color: white; box-shadow: 0 8px 20px rgba(0,0,0,0.15); transform: scale(1.1); }
            .ppdb-main-stepper .step-item.active .step-label { color: white; font-weight: 900; }
            .ppdb-main-stepper .step-line.active { background: white; opacity: 0.5; }
        </style>

        <div class="stu-content-wrapper">
            {{-- BANNER & QUICK LINKS --}}
            <div class="stu-banner-wrapper">
                <div id="bannerCarouselApp" class="carousel slide stu-banner-card shadow-2xl" data-ride="carousel" style="border-radius: 2.5rem;">
                    <div class="carousel-inner h-100">
                        @if($announcements->isNotEmpty())
                            @foreach($announcements as $idx => $ann)
                                <div class="carousel-item h-100 {{ $idx == 0 ? 'active' : '' }}">
                                    <div class="stu-banner-item h-100 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, {{ $idx % 2 == 0 ? '#4338ca, #6366f1' : '#1e1b4b, #3730a3' }});">
                                        <h3 class="stu-banner-title font-bold">{{ $ann->title }}</h3>
                                        <p class="stu-banner-text opacity-90">{{ strip_tags($ann->content) }}</p>
                                        <button class="stu-banner-btn hover-glow mt-4" onclick='showAnnouncement(@json($ann))'>Lihat Detail</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item h-100 active">
                                <div class="stu-banner-item h-100 d-flex flex-column justify-content-center bg-grad-indigo">
                                    <h3 class="stu-banner-title font-bold">Status Pendaftaran</h3>
                                    <p class="stu-banner-text opacity-90">Silakan pantau status dan lengkapi berkas pendaftaran Anda.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- QUICK LINKS --}}
            <div class="row mb-8 mt-4 px-2">
                <div class="col-3 px-1">
                    <a href="{{ route('front.achievements') }}" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-orange">
                            <i class="fas fa-award text-amber-500"></i>
                        </div>
                        <span class="text-xs font-bold mt-2 text-slate-600">Prestasi</span>
                    </a>
                </div>
                <div class="col-3 px-1">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->phone ?? '628123456789') }}" target="_blank" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-green">
                            <i class="fab fa-whatsapp text-emerald-500"></i>
                        </div>
                        <span class="text-xs font-bold mt-2 text-slate-600">Bantuan</span>
                    </a>
                </div>
                <div class="col-3 px-1">
                    <a href="#upload-section" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-indigo">
                            <i class="fas fa-file-invoice text-indigo-500"></i>
                        </div>
                        <span class="text-xs font-bold mt-2 text-slate-600">Berkas</span>
                    </a>
                </div>
                <div class="col-3 px-1">
                    <a href="javascript:void(0)" onclick="confirmLogout()" class="stu-quick-link">
                        <div class="stu-quick-icon bg-soft-red">
                            <i class="fas fa-sign-out-alt text-rose-500"></i>
                        </div>
                        <span class="text-xs font-bold mt-2 text-slate-600">Keluar</span>
                    </a>
                </div>
            </div>

            {{-- PREMIUM DIGITAL IDENTITY CARD --}}
            <div class="stu-profile-card glass-card mb-8 overflow-hidden border-0 shadow-2xl" style="border-radius: 2.5rem; background: #fff; position: relative;">
                {{-- Decorative Elements --}}
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(99, 102, 241, 0.05); border-radius: 50%;"></div>
                
                <div class="p-6">
                    <div class="d-flex align-items-start justify-content-between mb-6">
                        <div class="d-flex align-items-center">
                            <div class="stu-id-avatar mr-4 shadow-lg" style="width: 85px; height: 85px; border-radius: 22px; border: 4px solid #fff; overflow: hidden; background: #f8fafc;">
                                @if($registrant->foto)
                                    <img src="{{ asset('storage/' . $registrant->foto) }}" class="w-100 h-100 object-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-grad-indigo text-white font-weight-black" style="font-size: 1.8rem;">
                                        {{ substr($registrant->nama_lengkap, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-weight-black mb-1 text-slate-800" style="letter-spacing: -0.8px; font-size: 1.4rem;">{{ $registrant->nama_lengkap }}</h3>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge badge-soft-indigo px-3 py-1 rounded-pill small font-weight-bold mr-2">
                                        <i class="fas fa-fingerprint mr-1"></i> {{ $registrant->nisn ?? 'TANPA NISN' }}
                                    </span>
                                    <span class="badge badge-soft-emerald px-3 py-1 rounded-pill small font-weight-bold">
                                        <i class="fas fa-check-circle mr-1"></i> DATA VALID
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-gutters mb-6 p-4 rounded-3xl" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                        <div class="col-6 pr-2 mb-4 border-right">
                            <label class="text-slate-400 small font-bold text-uppercase mb-1" style="font-size: 9px; letter-spacing: 1px;">Asal Sekolah</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-school text-indigo-400 mr-2 small"></i>
                                <span class="font-weight-bold text-slate-700 truncate" style="font-size: 13px;">{{ $registrant->asal_sekolah }}</span>
                            </div>
                        </div>
                        <div class="col-6 pl-4 mb-4">
                            <label class="text-slate-400 small font-bold text-uppercase mb-1" style="font-size: 9px; letter-spacing: 1px;">Jalur Masuk</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-route text-indigo-400 mr-2 small"></i>
                                <span class="font-weight-bold text-slate-700" style="font-size: 13px;">{{ $registrant->admissionType->admission_type_name ?? 'Reguler' }}</span>
                            </div>
                        </div>
                        <div class="col-12"><div class="border-top my-2" style="border-style: dashed !important; opacity: 0.5;"></div></div>
                        <div class="col-6 pr-2 mt-2 border-right">
                            <label class="text-slate-400 small font-bold text-uppercase mb-1" style="font-size: 9px; letter-spacing: 1px;">Nama Wali</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-shield text-indigo-400 mr-2 small"></i>
                                <span class="font-weight-bold text-slate-700 truncate" style="font-size: 13px;">{{ $registrant->nama_ayah ?? $registrant->nama_ibu ?? '---' }}</span>
                            </div>
                        </div>
                        <div class="col-6 pl-4 mt-2">
                            <label class="text-slate-400 small font-bold text-uppercase mb-1" style="font-size: 9px; letter-spacing: 1px;">Kontak WA</label>
                            <div class="d-flex align-items-center">
                                <i class="fab fa-whatsapp text-emerald-500 mr-2 small"></i>
                                <span class="font-weight-bold text-slate-700" style="font-size: 13px;">{{ $registrant->no_hp_ortu }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-4 bg-grad-indigo text-white shadow-lg" style="border-radius: 1.5rem;">
                        <div>
                            <p class="mb-0 font-weight-bold small opacity-80">GELOMBANG</p>
                            <h5 class="mb-0 font-weight-black">{{ $registrant->admissionPhase->phase_name ?? 'I' }}</h5>
                        </div>
                        <div class="text-center px-4 border-left border-right border-white-50">
                            <p class="mb-0 font-weight-bold small opacity-80">TH. AJARAN</p>
                            <h5 class="mb-0 font-weight-black">{{ $academicYear->academic_year ?? date('Y').'/'.(date('Y')+1) }}</h5>
                        </div>
                        <div class="text-right">
                            <p class="mb-0 font-weight-bold small opacity-80">TGL DAFTAR</p>
                            <h5 class="mb-0 font-weight-black">{{ $registrant->created_at->format('d/m/Y') }}</h5>
                        </div>
                    </div>
                    <div class="row mt-6">
                        <div class="col-6 pr-2">
                            <a href="{{ route('ppdb.print_registration') }}" class="btn btn-outline-indigo btn-block py-3 rounded-xl font-weight-bold shadow-sm" style="font-size: 11px;">
                                <i class="fas fa-print mr-2"></i> CETAK KARTU
                            </a>
                        </div>
                        <div class="col-6 pl-2">
                            <button class="btn btn-soft-indigo btn-block py-3 rounded-xl font-weight-bold shadow-sm" style="font-size: 11px;" data-toggle="collapse" data-target="#editBiodataCollapse">
                                <i class="fas fa-edit mr-2"></i> EDIT PROFIL
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATUS & BERKAS --}}
            {{-- LIVE COUNTDOWN (NEW FEATURE) --}}
            @php
                $announcementDate = $registrant->admissionPhase->announcement_date ?? null;
                $isFuture = $announcementDate ? $announcementDate->isFuture() : false;
            @endphp

            @if($isFuture && !in_array($registrant->status, ['diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi']))
                <div class="glass-card mb-8 p-6 text-center shadow-2xl border-0 overflow-hidden" style="border-radius: 2.5rem; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white;">
                    <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1; font-size: 5rem;"><i class="fas fa-clock"></i></div>
                    
                    <h6 class="font-weight-black mb-4 text-indigo-200" style="letter-spacing: 1px; font-size: 11px;">PENGUMUMAN HASIL SELEKSI</h6>
                    
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <div class="countdown-unit mx-2">
                            <div class="unit-box shadow-lg" id="days">00</div>
                            <span class="unit-label">Hari</span>
                        </div>
                        <div class="countdown-unit mx-2">
                            <div class="unit-box shadow-lg" id="hours">00</div>
                            <span class="unit-label">Jam</span>
                        </div>
                        <div class="countdown-unit mx-2">
                            <div class="unit-box shadow-lg" id="minutes">00</div>
                            <span class="unit-label">Menit</span>
                        </div>
                        <div class="countdown-unit mx-2">
                            <div class="unit-box shadow-lg" id="seconds">00</div>
                            <span class="unit-label">Detik</span>
                        </div>
                    </div>
                    
                    <p class="small mb-0 text-indigo-300 font-weight-bold">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ $announcementDate->format('d F Y') }}
                    </p>
                </div>

                @push('scripts')
                <script>
                    function updateCountdown() {
                        const targetDate = new Date("{{ $announcementDate->format('Y-m-d H:i:s') }}").getTime();
                        const now = new Date().getTime();
                        const distance = targetDate - now;

                        if (distance < 0) {
                            clearInterval(countdownInterval);
                            window.location.reload();
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        document.getElementById("days").innerText = days.toString().padStart(2, '0');
                        document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
                        document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
                        document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');
                    }

                    const countdownInterval = setInterval(updateCountdown, 1000);
                    updateCountdown();
                </script>
                @endpush
            @endif

            @include('ppdb.status')
            
            {{-- OPTIONAL: BUTTON TO SHOW BIODATA AGAIN FOR EDITING --}}
            <div class="text-center mt-8 mb-8">
                <button class="btn btn-light rounded-pill px-6 font-weight-bold text-muted shadow-sm" type="button" data-toggle="collapse" data-target="#editBiodataCollapse">
                    <i class="fas fa-user-edit mr-2"></i> EDIT BIODATA
                </button>
                <div class="collapse mt-6 text-left" id="editBiodataCollapse">
                    @include('ppdb.form-biodata', ['action' => route('ppdb.update_biodata'), 'method' => 'PUT'])
                </div>
            </div>
        </div>

    @elseif($ppdbOpen)
        {{-- PREMIUM HEADER FOR NEW APPLICANTS --}}
        <div class="stu-new-header mb-8">
            <div class="d-flex justify-content-between align-items-center mb-8">
                <div class="d-flex align-items-center">
                    <div class="stu-avatar-box mr-4">
                        <div class="stu-avatar-text">{{ substr($user->name, 0, 2) }}</div>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold" style="font-size: 10px; letter-spacing: 2px;">SELAMAT DATANG</p>
                        <h3 class="text-white font-weight-black mb-1">{{ $user->name }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="stu-dot mr-2" style="background: #fbbf24;"></span>
                            <span class="text-white-50 font-weight-bold" style="font-size: 10px;">CALON SISWA BARU</span>
                        </div>
                    </div>
                </div>
                <div class="stu-header-icon" onclick="confirmLogout()">
                    <i class="fas fa-power-off"></i>
                </div>
            </div>

            <div class="glass-card p-5 mb-8" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); border-radius: 2rem;">
                <div class="d-flex align-items-center">
                    <div class="mr-4" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit text-white font-size-lg"></i>
                    </div>
                    <div>
                        <p class="text-white-50 mb-0 font-weight-bold small">TAHAP 1</p>
                        <h4 class="text-white font-weight-black mb-0">ISI BIODATA</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="stu-content-wrapper">
             {{-- WELCOME BANNER --}}
             <div class="stu-banner-wrapper">
                <div class="stu-banner-card bg-grad-indigo p-8 text-white d-flex align-items-center" style="border-radius: 2.5rem; min-height: 200px; position: relative; overflow: hidden;">
                    <div style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.1; transform: rotate(-15deg);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="position-relative z-index-2">
                        <h2 class="font-weight-black mb-3" style="letter-spacing: -1px;">Mulai Masa Depanmu!</h2>
                        <p class="opacity-90 mb-4" style="max-width: 80%; line-height: 1.6;">Silakan lengkapi formulir biodata di bawah ini untuk memulai proses pendaftaran santri baru.</p>
                        <div class="d-flex align-items-center">
                            <div class="bg-white px-4 py-2 rounded-pill text-indigo-600 font-weight-bold shadow-sm" style="font-size: 12px;">
                                <i class="fas fa-clock mr-2"></i> Estimasi 5 Menit
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TWIBBON GENERATOR (NEW FEATURE - VIRAL) --}}
            <div class="glass-card mb-8 p-6 shadow-xl border-0 overflow-hidden" style="border-radius: 2rem; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="font-weight-black mb-1" style="letter-spacing: 0.5px;">BANGGA JADI SISWA</h6>
                        <p class="small mb-3 text-indigo-100" style="line-height: 1.3;">Ayo pakai Twibbon resmi Madrasah dan bagikan ke media sosial!</p>
                        <button type="button" class="btn btn-white btn-sm px-4 rounded-pill font-weight-bold shadow-sm" onclick="openTwibbonModal()">
                            <i class="fas fa-camera-retro mr-2"></i> BUAT TWIBBON
                        </button>
                    </div>
                    <div class="col-4 text-center">
                        <div class="twibbon-preview-mini shadow-lg">
                            <img src="{{ asset('assets/img/ppdb/twibbon-frame.png') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            {{-- REGISTRATION MILESTONE --}}
            <div class="glass-card mb-8 p-6 shadow-xl border-0 overflow-hidden" style="border-radius: 2rem; background: #fff;">
                <h6 class="font-weight-black text-indigo-900 mb-6" style="letter-spacing: 0.5px;">ALUR PENDAFTARAN</h6>
                <div class="milestone-track d-flex justify-content-between">
                    <div class="milestone-item active">
                        <div class="milestone-icon bg-indigo-600 text-white"><i class="fas fa-edit"></i></div>
                        <span class="milestone-label">BIODATA</span>
                    </div>
                    <div class="milestone-line"></div>
                    <div class="milestone-item">
                        <div class="milestone-icon"><i class="fas fa-file-upload"></i></div>
                        <span class="milestone-label">BERKAS</span>
                    </div>
                    <div class="milestone-line"></div>
                    <div class="milestone-item">
                        <div class="milestone-icon"><i class="fas fa-user-check"></i></div>
                        <span class="milestone-label">VERIFIKASI</span>
                    </div>
                    <div class="milestone-line"></div>
                    <div class="milestone-item">
                        <div class="milestone-icon"><i class="fas fa-bullhorn"></i></div>
                        <span class="milestone-label">HASIL</span>
                    </div>
                </div>
            </div>

            {{-- SUPPORT & FAQ (NEW FEATURE) --}}
            <div class="row mb-8">
                <div class="col-6 pr-2">
                    <div class="glass-card p-5 h-100 shadow-lg border-0" style="border-radius: 2rem; background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);">
                        <div class="mb-3 text-pink-500 font-size-lg"><i class="fas fa-question-circle"></i></div>
                        <h6 class="font-weight-black text-slate-800 mb-1" style="font-size: 13px;">PANDUAN</h6>
                        <p class="text-slate-500 small mb-0" style="font-size: 10px;">Lihat FAQ & cara daftar yang benar.</p>
                    </div>
                </div>
                <div class="col-6 pl-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->phone ?? '628123456789') }}" target="_blank" style="text-decoration: none;">
                        <div class="glass-card p-5 h-100 shadow-lg border-0" style="border-radius: 2rem; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
                            <div class="mb-3 text-emerald-500 font-size-lg"><i class="fab fa-whatsapp"></i></div>
                            <h6 class="font-weight-black text-slate-800 mb-1" style="font-size: 13px;">CS PPDB</h6>
                            <p class="text-slate-500 small mb-0" style="font-size: 10px;">Chat panitia jika ada kendala.</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- FORM --}}
            @include('ppdb.form-biodata', ['action' => route('ppdb.store_biodata'), 'method' => 'POST'])
        </div>
    @endif

    {{-- TWIBBON MODAL --}}
    <div class="modal fade" id="twibbonModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-2xl" style="border-radius: 2rem; background: #f8fafc;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="modal-title font-weight-black text-slate-800">TWIBBON GENERATOR</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="twibbon-canvas-container shadow-xl mb-4 mx-auto" style="width: 300px; height: 300px; border-radius: 20px; overflow: hidden; background: #fff; position: relative;">
                        <canvas id="twibbonCanvas" width="1000" height="1000" style="width: 100%; height: 100%;"></canvas>
                    </div>
                    
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="twibbonPhoto" accept="image/*">
                        <label class="custom-file-label text-left rounded-pill" for="twibbonPhoto">Ganti Foto...</label>
                    </div>

                    <button type="button" class="btn btn-indigo-600 btn-block py-3 rounded-pill font-weight-bold shadow-lg" id="downloadTwibbon" style="background: #4f46e5; color: white; border: none;">
                        <i class="fas fa-download mr-2"></i> DOWNLOAD TWIBBON
                    </button>
                    <p class="small text-slate-400 mt-3 mb-0">Bagikan ke Status WhatsApp atau Instagram!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="stu-bottom-nav">
        <a href="{{ route('ppdb.dashboard') }}" class="nav-item {{ request()->routeIs('ppdb.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home-alt"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('front.achievements') }}" class="nav-item">
            <i class="fas fa-award"></i>
            <span>Prestasi</span>
        </a>
        
        <div class="stu-fab-container">
            <div class="stu-fab" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </div>
        </div>

        <a href="{{ $registrant ? '#upload-section' : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $setting->phone ?? '628123456789') }}" class="nav-item">
            <i class="fas {{ $registrant ? 'fa-folder-open' : 'fa-headset' }}"></i>
            <span>{{ $registrant ? 'Berkas' : 'Bantuan' }}</span>
        </a>
        <a href="javascript:void(0)" onclick="confirmLogout()" class="nav-item">
            <i class="fas fa-power-off"></i>
            <span>Keluar</span>
        </a>
    </div>


@push('css')
<style>
    /* ========== PREMIUM STUDENT DASHBOARD STYLES (TEACHER STYLE) ========== */

    /* New Premium Header */
    /* New Premium Header (Indigo) */
    .stu-new-header {
        background: linear-gradient(135deg, #3730a3 0%, #6366f1 100%);
        margin: -25px -20px 30px -20px;
        padding: 60px 30px 140px 30px;
        border-bottom-left-radius: 4rem;
        border-bottom-right-radius: 4rem;
        box-shadow: 0 25px 50px rgba(79, 70, 229, 0.25);
        position: relative;
        overflow: hidden;
    }
    .stu-new-header::before {
        content: ''; position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .stu-avatar-box {
        width: 65px; height: 65px;
        border-radius: 20px;
        background: rgba(255,255,255,0.15);
        display: flex; align-items: center; justify-content: center;
        border: 2px solid rgba(255,255,255,0.25);
        overflow: hidden;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .stu-avatar-box img { width: 100%; height: 100%; object-fit: cover; }
    .stu-avatar-text { color: white; font-weight: 900; font-size: 1.6rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    
    .stu-nip-box {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 4px 12px;
    }
    .stu-dot {
        width: 8px; height: 8px;
        background: #34d399;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 12px #34d399;
    }
    .stu-header-icon {
        width: 45px; height: 45px;
        border-radius: 15px;
        background: rgba(255,255,255,0.15);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .stu-header-icon:hover { background: rgba(255,255,255,0.25); transform: translateY(-2px); }
    .bg-danger-soft { background: rgba(239, 68, 68, 0.2) !important; }

    /* New Stat Cards */
    .stu-stat-new {
        display: flex; align-items: center;
        padding: 20px;
        border-radius: 25px;
        gap: 15px;
        transition: transform 0.2s;
    }
    .stu-stat-new.white-card {
        background: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .stu-stat-new.glass-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .stu-stat-icon-box {
        width: 45px; height: 45px;
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
    }
    .bg-success-soft { background: #ecfdf5; color: #10b981; }
    .bg-white-soft { background: rgba(255,255,255,0.2); color: white; }

    /* Main Action Card */
    .stu-main-card {
        background: white;
        border-radius: 35px;
        padding: 30px;
        margin-top: -60px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        position: relative;
        z-index: 5;
    }
    .stu-action-icon {
        width: 60px; height: 60px;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
    }
    .stu-btn-absen {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 18px;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }
    .stu-btn-absen:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(16, 185, 129, 0.4); color: white; }

    /* Bottom Navigation Bar (Android Style) */
    .stu-bottom-nav {
        position: fixed; bottom: 0; left: 0; right: 0;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        height: 75px;
        display: flex; justify-content: space-around; align-items: center;
        padding: 0 10px 15px 10px;
        z-index: 1000;
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.05);
    }
    .stu-bottom-nav .nav-item {
        display: flex; flex-direction: column; align-items: center;
        color: #94a3b8; text-decoration: none; font-size: 11px;
        font-weight: 800; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex: 1;
    }
    .stu-bottom-nav .nav-item.active { color: #6366f1; transform: translateY(-2px); }
    .stu-bottom-nav .nav-item i { font-size: 1.4rem; margin-bottom: 4px; }
    
    .stu-fab-container { position: relative; width: 65px; height: 65px; flex-shrink: 0; }
    .stu-fab {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #6366f1, #4338ca);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.6rem;
        position: absolute; top: -30px; left: 2px;
        border: 5px solid #fff;
        box-shadow: 0 12px 25px rgba(99, 102, 241, 0.4);
        cursor: pointer;
        z-index: 1001;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .stu-fab:hover { transform: scale(1.1) rotate(90deg); box-shadow: 0 15px 35px rgba(99, 102, 241, 0.5); }
    .stu-fab::after {
        content: ''; position: absolute; inset: -5px; border-radius: 50%;
        border: 2px solid #6366f1; opacity: 0;
        animation: fab-pulse 2s infinite;
    }
    @keyframes fab-pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.5); opacity: 0; }
    }
    
    body { padding-bottom: 110px !important; }

    /* Quick Links Grid */
    .stu-quick-link {
        display: flex; flex-direction: column; align-items: center;
        text-decoration: none !important; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .stu-quick-link:hover { transform: translateY(-5px); }
    .stu-quick-link:hover .stu-quick-icon { box-shadow: 0 12px 25px rgba(0,0,0,0.08); transform: scale(1.05); }
    .stu-quick-link span { 
        font-size: 12px; font-weight: 800; color: #334155; 
        margin-top: 10px; letter-spacing: 0.2px;
    }
    .stu-quick-icon {
        width: 65px; height: 65px;
        border-radius: 22px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 8px 15px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .bg-soft-purple { background: #f5f3ff; color: #8b5cf6; }
    .bg-soft-green { background: #f0fdf4; color: #10b981; }
    .bg-soft-emerald { background: #ecfdf5; color: #059669; }
    .bg-soft-blue { background: #eff6ff; color: #3b82f6; }
    .bg-soft-orange { background: #fff7ed; color: #f59e0b; }
    .bg-soft-indigo { background: #eef2ff; color: #6366f1; }
    .bg-soft-pink { background: #fdf2f8; color: #ec4899; }
    .bg-soft-red { background: #fef2f2; color: #ef4444; }

    .stu-badge-live {
        background: #ef4444; color: white;
        padding: 2px 8px; border-radius: 6px;
        font-size: 10px; font-weight: 800;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    body { background-color: #f8fafc; padding-bottom: 120px; }

    .container-fluid { max-width: 1200px; }

    /* Agenda/Schedule List */
    .stu-schedule-day { border-bottom: 1px solid #f1f5f9; }
    .stu-schedule-day:last-child { border-bottom: none; }
    .stu-schedule-day-header {
        display: flex; justify-content: space-between;
        align-items: center; padding: 18px 25px;
        cursor: pointer; transition: background 0.15s;
    }
    .stu-schedule-day-header:hover { background: #fafafa; }

    /* Attendance Premium UI */
    .stu-attend-card-inner {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .stu-clock-live {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -1px;
        margin-bottom: 5px;
    }
    .stu-btn-attend-main {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 18px;
        padding: 15px 25px;
        font-weight: 800;
        width: 100%;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        transition: all 0.3s;
    }
    .stu-btn-attend-main:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(16, 185, 129, 0.3); color: white; }
    
    .stu-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 15px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .status-success-light { background: #dcfce7; color: #166534; }
    .status-warning-light { background: #fef3c7; color: #92400e; }
    
    .stu-checkin-time {
        font-size: 1.2rem;
        font-weight: 800;
        color: #059669;
        margin-top: 10px;
    }

    /* Class Info Premium */
    .stu-teacher-card {
        background: white;
        border-radius: 20px;
        padding: 18px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border: 1px solid #eef2ff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.05);
        transition: all 0.2s;
    }
    .stu-teacher-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.1); }
    
    .stu-teacher-avatar {
        width: 50px; height: 50px;
        border-radius: 15px;
        background: #eff6ff;
        display: flex; align-items: center; justify-content: center;
        color: #3b82f6; font-size: 1.3rem;
        margin-right: 15px;
    }
    .stu-class-badge {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 6px 18px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
    }
    .stu-status-active {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .stu-dot-pulse {
        width: 6px; height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .stu-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .stu-info-item-small {
        background: #f8fafc;
        padding: 15px;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .stu-info-item-small:hover { background: #fff; border-color: #e2e8f0; }
    
    .stu-info-item-small label {
        display: block;
        font-size: 9px;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 800;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    .stu-info-item-small span {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
    }

    /* Announcement Banner Slider */
    .stu-banner-wrapper {
        margin: -100px 0 25px 0;
        position: relative;
        z-index: 20;
    }
    .stu-banner-card {
        border-radius: 30px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        height: 180px;
    }
    .stu-banner-item {
        height: 180px;
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
        padding: 30px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
        position: relative;
    }
    .stu-banner-item::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .stu-banner-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1.2;
        max-width: 70%;
    }
    .stu-banner-text {
        font-size: 0.9rem;
        opacity: 0.9;
        max-width: 60%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .stu-banner-btn {
        margin-top: 15px;
        background: white;
        color: #059669;
        border: none;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        width: fit-content;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .carousel-indicators { bottom: 10px; }
    .carousel-indicators li {
        width: 8px; height: 8px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255,255,255,0.5);
        border: none;
    }
    .carousel-indicators .active { background-color: white; width: 20px; border-radius: 10px; }

    /* Full Screen Top Reset */
    .ppdb-navbar { display: none !important; }
    .ppdb-container { padding: 0 !important; max-width: 100% !important; margin: 0 !important; }
    body { background: #f8fafc; }

    @media (max-width: 768px) {
        .glass-nav, footer { display: none !important; }
        .pt-24 { padding-top: 0 !important; }
        .stu-new-header { border-radius: 0 0 35px 35px; margin-top: 0; padding-top: 30px; padding-bottom: 110px; }
        .stu-content-wrapper { padding-left: 12px; padding-right: 12px; }
        .stu-bottom-nav { height: 65px; padding-bottom: 15px; }
        .stu-fab { width: 52px; height: 52px; top: -30px; font-size: 1.4rem; }
    }

    @media (min-width: 769px) {
        .stu-new-header { border-radius: 0 0 50px 50px; margin-top: 0; padding-top: 50px; }
        .stu-main-card { margin-top: -80px; }
    }
    
    .stu-new-header {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        padding-left: 30px;
        padding-right: 30px;
        padding-bottom: 140px;
        position: relative;
    }

    /* Milestone Timeline */
    .milestone-track { position: relative; padding: 10px 0; }
    .milestone-item { display: flex; flex-direction: column; align-items: center; z-index: 2; flex: 1; }
    .milestone-icon { 
        width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; 
        display: flex; align-items: center; justify-content: center; font-size: 12px;
        border: 2px solid #e2e8f0; transition: all 0.3s;
    }
    .milestone-item.active .milestone-icon { background: #6366f1; color: white; border-color: #4338ca; box-shadow: 0 5px 15px rgba(99,102,241,0.3); }
    .milestone-label { font-size: 8px; font-weight: 900; margin-top: 8px; color: #94a3b8; letter-spacing: 0.5px; }
    .milestone-item.active .milestone-label { color: #4338ca; }
    .milestone-line { flex-grow: 1; height: 2px; background: #f1f5f9; align-self: flex-start; margin-top: 18px; margin-left: -15px; margin-right: -15px; }
    
    /* Countdown Styles */
    .countdown-unit { display: flex; flex-direction: column; align-items: center; }
    .unit-box { 
        width: 55px; height: 55px; background: rgba(255,255,255,0.1); 
        border: 1px solid rgba(255,255,255,0.2); border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; font-weight: 900; color: white;
        backdrop-filter: blur(5px);
    }
    .unit-label { font-size: 8px; font-weight: 700; text-transform: uppercase; margin-top: 8px; color: #a5b4fc; letter-spacing: 1px; }

    @media (max-width: 768px) {
        .stu-new-header { border-radius: 0 0 40px 40px; margin-top: -25px; padding-top: 40px; }
        .stu-content-wrapper { padding-left: 15px; padding-right: 15px; }
        .stu-bottom-nav { border-radius: 25px 25px 0 0; height: 75px; }
        .stu-fab { width: 60px; height: 60px; margin-top: -65px; font-size: 1.8rem; border-width: 5px; }
    }

    /* Twibbon Styles */
    .twibbon-preview-mini {
        width: 70px; height: 70px; border-radius: 12px; overflow: hidden; border: 2px solid rgba(255,255,255,0.3);
        transform: rotate(5deg); transition: all 0.3s;
    }
    .twibbon-preview-mini:hover { transform: rotate(0deg) scale(1.1); }
    .twibbon-canvas-container { border: 4px solid #fff; background: #eee; }

    /* Standardized Android Theme */
    .bg-grad-indigo { background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); }
    .glass-card { background: rgba(255, 255, 255, 0.8) !important; backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4) !important; }
    
    /* Center the closed state */
    .ppdb-closed-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
    }
</style>
@endpush

@endsection



@push('scripts')
<script>
    function showAnnouncement(ann) {
        $('#announcementDetailTitle').text(ann.title);
        $('#announcementContent').html(ann.content);
        
        let date = new Date(ann.created_at);
        let options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        $('#announcementDate').text(date.toLocaleDateString('id-ID', options));
        
        $('#modalAnnouncement').modal('show');
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar Sesi?',
            text: "Anda akan keluar dari portal pendaftaran.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#ffffff',
            customClass: {
                popup: 'premium-rounded-modal',
                title: 'font-weight-black',
                confirmButton: 'btn-premium-action px-4 py-2',
                cancelButton: 'btn-premium-outline px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }

    $(document).ready(function() {
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });

        // Auto-resume logic: scroll to upload section if biodata is done but documents are pending
        @if(isset($currentStep) && $currentStep == 2)
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('#upload-section').offset()?.top - 100 || 500
                }, 1000);
            }, 500);
        @endif

        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
    // TWIBBON GENERATOR LOGIC
    const canvas = document.getElementById('twibbonCanvas');
    const ctx = canvas.getContext('2d');
    const frameImg = new Image();
    const userImg = new Image();
    frameImg.src = "{{ asset('assets/img/ppdb/twibbon-frame.png') }}";

    function openTwibbonModal() {
        $('#twibbonModal').modal('show');
        @if($registrant->foto)
            userImg.src = "{{ Storage::url($registrant->foto) }}";
            userImg.onload = () => drawTwibbon();
        @else
            frameImg.onload = () => drawTwibbon();
        @endif
        drawTwibbon();
    }

    function drawTwibbon() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // 1. Draw User Photo (Background)
        if (userImg.src) {
            let scale = Math.max(canvas.width / userImg.width, canvas.height / userImg.height);
            let x = (canvas.width / 2) - (userImg.width / 2) * scale;
            let y = (canvas.height / 2) - (userImg.height / 2) * scale;
            ctx.drawImage(userImg, x, y, userImg.width * scale, userImg.height * scale);
        } else {
            ctx.fillStyle = "#f1f5f9";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }

        // 2. Draw Frame (Foreground)
        ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
    }

    $('#twibbonPhoto').on('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(event) {
            userImg.src = event.target.result;
            userImg.onload = () => drawTwibbon();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    $('#downloadTwibbon').on('click', function() {
        const link = document.createElement('a');
        link.download = 'Twibbon-PPDB-BustanulHuda.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil diunduh!',
            text: 'Silakan bagikan ke media sosial Anda.',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endpush

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
