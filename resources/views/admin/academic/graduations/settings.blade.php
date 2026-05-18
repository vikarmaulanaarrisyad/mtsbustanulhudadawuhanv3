@extends($layout)

@section('title', 'Pengaturan Pengumuman Kelulusan')
@section('subtitle', 'Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-warning overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-dark">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-cog mr-2"></i> 
                            Pengaturan Pengumuman Kelulusan
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Atur tanggal rilis countdown pengumuman kelulusan dan pesan kustom untuk masing-masing jenjang.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-sliders-h fa-6x opacity-2 text-dark shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- FORM CONFIGURATION -->
<form action="{{ route('graduations.update-settings') }}" method="POST" class="animate__animated animate__fadeInUp">
    @csrf
    
    <div class="row">
        @foreach($settings as $setting)
            @php
                $levelName = '';
                $levelBadgeColor = '';
                if ($setting->level == 6) {
                    $levelName = 'MI (Tingkat 6)';
                    $levelBadgeColor = 'badge-success';
                } elseif ($setting->level == 9) {
                    $levelName = 'MTs (Tingkat 9)';
                    $levelBadgeColor = 'badge-info';
                } elseif ($setting->level == 12) {
                    $levelName = 'MA (Tingkat 12)';
                    $levelBadgeColor = 'badge-danger';
                }
            @endphp
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm border-0 premium-card h-100">
                    <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                        <span class="badge {{ $levelBadgeColor }} px-3 py-2 text-sm font-weight-bold rounded-pill">{{ $levelName }}</span>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" 
                                   name="settings[{{ $setting->level }}][is_active]" 
                                   class="custom-control-input" 
                                   id="switch_{{ $setting->level }}" 
                                   value="1" 
                                   {{ $setting->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="switch_{{ $setting->level }}">Aktif</label>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">TANGGAL & WAKTU PENGUMUMAN</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="far fa-clock text-primary"></i></span>
                                </div>
                                <input type="datetime-local" 
                                       name="settings[{{ $setting->level }}][announcement_date]" 
                                       class="form-control" 
                                       value="{{ $setting->announcement_date ? $setting->announcement_date->format('Y-m-d\TH:i') : '' }}"
                                       required>
                            </div>
                            <small class="text-muted">Gunakan zona waktu setempat (WIB/WITA/WIT).</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="text-xs font-weight-bold text-muted uppercase">PESAN KELULUSAN (LULUS)</label>
                            <textarea name="settings[{{ $setting->level }}][announcement_text]" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Pesan yang ditampilkan jika siswa lulus..." 
                                      required>{{ $setting->announcement_text }}</textarea>
                            <small class="text-muted">Akan ditampilkan pada halaman siswa berstatus lulus.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted uppercase">PESAN FALLBACK (BELUM LULUS)</label>
                            <textarea name="settings[{{ $setting->level }}][non_graduation_text]" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Pesan/instruksi jika siswa belum lulus..." 
                                      required>{{ $setting->non_graduation_text }}</textarea>
                            <small class="text-muted">Akan ditampilkan pada halaman siswa berstatus belum lulus.</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ACTION BUTTONS -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-end" style="gap: 15px;">
            <a href="{{ route('graduations.index') }}" class="btn btn-secondary rounded-pill px-4 font-weight-bold shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> KEMBALI
            </a>
            <button type="submit" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-lg btn-premium">
                <i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN
            </button>
        </div>
    </div>
</form>

<style>
    /* Premium Themes */
    .bg-gradient-warning { 
        background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%) !important; 
    }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.15)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.15); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-4px); box-shadow: 0 15px 40px rgba(0,0,0,0.08); }
    
    .btn-premium { border-radius: 10px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
    
    textarea { border-radius: 12px !important; resize: none; }
    .input-group-text { border-top-left-radius: 12px !important; border-bottom-left-radius: 12px !important; }
    .form-control { border-top-right-radius: 12px !important; border-bottom-right-radius: 12px !important; }
</style>
@endsection
