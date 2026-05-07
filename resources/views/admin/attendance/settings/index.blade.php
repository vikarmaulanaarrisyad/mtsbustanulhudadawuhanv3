@extends($layout)

@section('title', 'Konfigurasi Sistem Presensi')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-amber overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-user-clock mr-2 animate__animated animate__fadeInLeft"></i> 
                            Pengaturan Batas Waktu Presensi
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Tentukan toleransi jam masuk, batas akhir pulang, serta hari kerja operasional Madrasah secara presisi.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-cogs fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<div class="row animate__animated animate__fadeInUp">
    <!-- MAIN CONFIGURATION FORM -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 premium-card mb-4">
            <div class="card-header bg-white py-4 border-bottom">
                <h4 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-sliders-h mr-2 text-amber"></i> Panel Kontrol Waktu
                </h4>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <form id="settingForm">
                    @csrf
                    <input type="hidden" name="id" value="{{ $settingAttendace->id }}">
                    
                    {{-- WAKTU MASUK --}}
                    <div class="card border-0 shadow-sm rounded-15 mb-4">
                        <div class="card-body p-4 border-left-amber">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3"><i class="fas fa-sign-in-alt mr-2 text-success"></i> Konfigurasi Jam Masuk (Datang)</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Dibuka Mulai Pukul</label>
                                        <div class="input-group-premium border-success-light">
                                            <i class="far fa-clock text-success"></i>
                                            <input type="time" name="check_in_start" class="form-control text-lg font-weight-bold" value="{{ $settingAttendace->check_in_start }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Batas Akhir (Tutup)</label>
                                        <div class="input-group-premium border-danger-light">
                                            <i class="far fa-times-circle text-danger"></i>
                                            <input type="time" name="check_in_end" class="form-control text-lg font-weight-bold" value="{{ $settingAttendace->check_in_end }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- WAKTU PULANG --}}
                    <div class="card border-0 shadow-sm rounded-15 mb-4">
                        <div class="card-body p-4 border-left-indigo">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3"><i class="fas fa-sign-out-alt mr-2 text-indigo"></i> Konfigurasi Jam Pulang (Kembali)</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Dibuka Mulai Pukul</label>
                                        <div class="input-group-premium border-indigo-light">
                                            <i class="far fa-clock text-indigo"></i>
                                            <input type="time" name="check_out_start" class="form-control text-lg font-weight-bold" value="{{ $settingAttendace->check_out_start }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Batas Akhir (Tutup)</label>
                                        <div class="input-group-premium border-danger-light">
                                            <i class="far fa-times-circle text-danger"></i>
                                            <input type="time" name="check_out_end" class="form-control text-lg font-weight-bold" value="{{ $settingAttendace->check_out_end }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- HARI KERJA --}}
                    <div class="card border-0 shadow-sm rounded-15 mb-4">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Hari Kerja Efektif</h6>
                            <div class="d-flex flex-wrap" style="gap: 15px;">
                                @php
                                    $days = [1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu', 0=>'Minggu'];
                                    $workDays = (array) ($settingAttendace->work_days ?? []);
                                @endphp
                                @foreach ($days as $key => $day)
                                    @php $isChecked = in_array((string) $key, array_map('strval', $workDays)); @endphp
                                    <div class="custom-day-checkbox">
                                        <input type="checkbox" name="work_days[]" id="day_{{ $key }}" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                        <label for="day_{{ $key }}" class="shadow-sm">
                                            <i class="fas {{ $isChecked ? 'fa-check-circle' : 'fa-circle' }} mr-1 icon-check"></i> {{ $day }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- LOKASI & RADIUS --}}
                    <div class="card border-0 shadow-sm rounded-15 mb-4">
                        <div class="card-body p-4 border-left-success">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3"><i class="fas fa-map-marker-alt mr-2 text-success"></i> Konfigurasi Lokasi & Radius</h6>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Latitude</label>
                                        <input type="text" name="latitude" id="latitude" class="form-control font-weight-bold" value="{{ $settingAttendace->latitude }}" placeholder="-6.12345678">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Longitude</label>
                                        <input type="text" name="longitude" id="longitude" class="form-control font-weight-bold" value="{{ $settingAttendace->longitude }}" placeholder="106.12345678">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Radius (m)</label>
                                        <input type="number" name="radius" class="form-control font-weight-bold" value="{{ $settingAttendace->radius ?? 100 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info border-0 rounded-10 py-2 px-3 mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <small>Admin dapat mengatur koordinat kantor dan batas jarak maksimal (dalam meter) guru boleh melakukan absen.</small>
                                <button type="button" onclick="getCurrentLocation()" class="btn btn-sm btn-info ml-auto shadow-sm rounded-pill">
                                    <i class="fas fa-location-arrow mr-1"></i> Lokasi Saya
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- KEAMANAN AI --}}
                    <div class="card border-0 shadow-sm rounded-15 mb-4">
                        <div class="card-body p-4 border-left-rose">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3"><i class="fas fa-user-shield mr-2 text-rose"></i> Fitur Keamanan Biometrik</h6>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-15 border border-dashed">
                                <div class="pr-4">
                                    <h6 class="font-weight-bold mb-1">Wajib Registrasi & Verifikasi Wajah AI</h6>
                                    <p class="text-xs text-muted mb-0">Jika aktif, guru wajib menscan wajah untuk absen. Jika non-aktif, guru hanya perlu validasi lokasi (GPS).</p>
                                </div>
                                <div class="custom-control custom-switch custom-switch-lg">
                                    <input type="hidden" name="enable_face_attendance" value="0">
                                    <input type="checkbox" class="custom-control-input" id="enable_face_attendance" name="enable_face_attendance" value="1" {{ ($settingAttendace->enable_face_attendance ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="enable_face_attendance"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" onclick="submitSetting()" class="btn btn-amber rounded-pill px-5 py-2 font-weight-bold shadow-amber text-dark" id="btnSave">
                            <i class="fas fa-save mr-2"></i> SIMPAN KONFIGURASI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SUMMARY & INFO SIDEBAR -->
    <div class="col-md-4">
        {{-- SUMMARY CARD --}}
        <div class="card shadow-sm border-0 premium-card mb-4 bg-gradient-dark text-white">
            <div class="card-header bg-transparent py-3 border-bottom border-secondary">
                <h5 class="card-title font-weight-bold mb-0 text-white">
                    <i class="fas fa-clipboard-check mr-2 text-amber"></i> Ringkasan Aktif
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="summary-item mb-4">
                    <small class="text-uppercase opacity-70 d-block mb-1">Durasi Presensi Masuk</small>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-white-20 rounded p-2 mr-3 text-success"><i class="fas fa-sign-in-alt text-lg"></i></div>
                        <h5 class="font-weight-bold mb-0">{{ substr($settingAttendace->check_in_start, 0, 5) }} - {{ substr($settingAttendace->check_in_end, 0, 5) }}</h5>
                    </div>
                </div>
                
                <div class="summary-item mb-4">
                    <small class="text-uppercase opacity-70 d-block mb-1">Durasi Presensi Pulang</small>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-white-20 rounded p-2 mr-3 text-info"><i class="fas fa-sign-out-alt text-lg"></i></div>
                        <h5 class="font-weight-bold mb-0">{{ substr($settingAttendace->check_out_start, 0, 5) }} - {{ substr($settingAttendace->check_out_end, 0, 5) }}</h5>
                    </div>
                </div>

                <div class="summary-item border-top border-secondary pt-3">
                    <small class="text-uppercase opacity-70 d-block mb-2">Hari Aktif Presensi</small>
                    <div class="d-flex flex-wrap" style="gap: 5px;">
                        @php
                            $activeDays = [];
                            foreach ($workDays as $d) { if (isset($days[$d])) $activeDays[] = $days[$d]; }
                        @endphp
                        @foreach($activeDays as $ad)
                            <span class="badge bg-white-20 text-white px-2 py-1 rounded">{{ $ad }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO ALERT --}}
        <div class="alert alert-soft-amber border-0 rounded-15 shadow-sm p-4">
            <div class="d-flex mb-2">
                <i class="fas fa-lightbulb text-amber text-xl mr-3 mt-1"></i>
                <h6 class="font-weight-bold text-dark mb-0">Petunjuk Penting</h6>
            </div>
            <p class="small text-muted mb-0 ml-4 pl-3" style="line-height: 1.6;">
                Mesin presensi (QR Scanner) akan secara otomatis <strong>menolak</strong> pemindaian yang dilakukan di luar rentang waktu masuk dan pulang yang telah ditetapkan di atas. Pastikan jam server sesuai dengan jam lokal.
            </p>
        </div>
    </div>
</div>

<style>
    /* Premium Design System */
    .bg-gradient-amber { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
    .bg-gradient-dark { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; }
    .btn-amber { background: #f59e0b; border: none; }
    .btn-amber:hover { background: #d97706; }
    .text-amber { color: #f59e0b; }
    .bg-white-20 { background: rgba(255,255,255,0.1); }
    .shadow-amber { box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4); }
    
    .opacity-8 { opacity: 0.8; }
    .opacity-70 { opacity: 0.7; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    .premium-card { border-radius: 15px; overflow: hidden; }
    .rounded-15 { border-radius: 15px; }
    .bg-light-soft { background: #f8fafc; }
    .alert-soft-amber { background: #fffbeb; }

    .border-left-amber { border-left: 4px solid #f59e0b; }
    .border-left-indigo { border-left: 4px solid #6366f1; }
    .text-indigo { color: #6366f1; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #334155; width: 100%;
    }
    .border-success-light:focus-within { border-color: #10b981 !important; box-shadow: 0 0 10px rgba(16,185,129,0.1); }
    .border-danger-light:focus-within { border-color: #ef4444 !important; box-shadow: 0 0 10px rgba(239,68,68,0.1); }
    .border-indigo-light:focus-within { border-color: #6366f1 !important; box-shadow: 0 0 10px rgba(99,102,241,0.1); }

    /* Custom Checkbox Pills */
    .custom-day-checkbox { position: relative; }
    .custom-day-checkbox input { display: none; }
    .custom-day-checkbox label {
        display: inline-flex; align-items: center; padding: 8px 16px;
        background: #fff; border: 2px solid #e2e8f0; border-radius: 50rem;
        cursor: pointer; font-weight: 700; color: #64748b; font-size: 0.85rem;
        transition: all 0.2s ease; margin-bottom: 0;
    }
    .custom-day-checkbox input:checked + label {
        background: #f0f9ff; border-color: #0ea5e9; color: #0ea5e9;
    }
    .custom-day-checkbox input:checked + label .icon-check { color: #0ea5e9; }

    /* Custom Switch Large */
    .custom-switch-lg .custom-control-label::before { height: 2rem; width: 3.5rem; border-radius: 2rem; }
    .custom-switch-lg .custom-control-label::after { width: calc(2rem - 4px); height: calc(2rem - 4px); border-radius: 2rem; }
    .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(1.5rem); }
    .custom-switch-lg .custom-control-label { padding-top: 0.5rem; padding-left: 2rem; }
    .border-left-rose { border-left: 4px solid #f43f5e; }
    .text-rose { color: #f43f5e; }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    // Interaktivitas checkbox icon
    $('.custom-day-checkbox input').change(function() {
        let icon = $(this).next('label').find('.icon-check');
        if(this.checked) { icon.removeClass('fa-circle').addClass('fa-check-circle'); }
        else { icon.removeClass('fa-check-circle').addClass('fa-circle'); }
    });

    function getCurrentLocation() {
        if (navigator.geolocation) {
            Swal.fire({ title: 'Mencari Lokasi...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            navigator.geolocation.getCurrentPosition(function(position) {
                $('#latitude').val(position.coords.latitude);
                $('#longitude').val(position.coords.longitude);
                Swal.close();
                Swal.fire({ icon: 'success', title: 'Lokasi Ditemukan', text: 'Koordinat telah diperbarui.', timer: 1500, showConfirmButton: false });
            }, function(error) {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pastikan GPS aktif dan izin lokasi diberikan.' });
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Browser tidak mendukung geolokasi.' });
        }
    }

    function submitSetting() {
        let btn = $('#btnSave');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...');
        
        $.ajax({
            url: '{{ route('attendance-settings.update') }}',
            type: 'POST',
            data: $('#settingForm').serialize(),
            success: function(response) {
                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 2000, showConfirmButton: false })
                .then(() => location.reload()); // Reload to update summary sidebar
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> SIMPAN KONFIGURASI');
            }
        });
    }
</script>
@endpush
