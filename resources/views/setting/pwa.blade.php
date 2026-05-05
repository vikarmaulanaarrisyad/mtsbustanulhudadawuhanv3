{{-- ═══════════════════════════════════════════════════════ --}}
{{--  BAGIAN 1: Upload Ikon PWA (Form Terpisah)              --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<form action="{{ route('pwa.upload-icon') }}" method="post" enctype="multipart/form-data">
    @csrf

    <x-card>
        {{-- Header --}}
        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:12px!important;">
            <div class="mr-3" style="width:48px;height:48px;border-radius:12px;overflow:hidden;flex-shrink:0;box-shadow:0 4px 10px rgba(16,185,129,.3);">
                <img src="{{ ($setting->pwa_icon ? $setting->pwa_icon : '/icons/icon-192x192.png') }}" alt="PWA Icon" id="pwa-icon-preview" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold" style="color:#065f46;">Ikon Aplikasi PWA</h6>
                <small style="color:#059669;">Unggah ikon yang akan tampil di layar utama HP dan splash screen.</small>
            </div>
        </div>

        {{-- Upload area --}}
        <div class="form-group">
            <label>Upload Ikon Baru (PNG / JPG)</label>
            <div class="d-flex align-items-start" style="gap:20px; flex-wrap:wrap;">
                {{-- Preview --}}
                <div style="text-align:center;">
                    <div style="width:120px;height:120px;border-radius:22px;overflow:hidden;border:2px dashed #a7f3d0;display:flex;align-items:center;justify-content:center;background:#f9fafb;">
                        <img src="{{ ($setting->pwa_icon ? $setting->pwa_icon : '/icons/icon-192x192.png') }}"
                            alt="Preview" id="icon-preview-large"
                            style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <small class="text-muted d-block mt-1">Preview</small>
                </div>

                {{-- Input --}}
                <div style="flex:1;">
                    <div class="custom-file mb-2">
                        <input type="file" class="custom-file-input @error('pwa_icon') is-invalid @enderror"
                            id="pwa_icon" name="pwa_icon" accept="image/png,image/jpeg">
                        <label class="custom-file-label" for="pwa_icon">Pilih file ikon...</label>
                    </div>
                    @error('pwa_icon')
                        <span class="text-danger d-block" style="font-size:.875em;">{{ $message }}</span>
                    @enderror
                    <div class="mt-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:.8rem;color:#64748b;">
                        <i class="fas fa-info-circle text-primary mr-1"></i>
                        <strong>Rekomendasi:</strong> Gambar persegi (1:1), minimal <strong>512×512px</strong>, format PNG dengan latar transparan atau putih.<br>
                        Sistem akan otomatis membuat 3 ukuran: <code>192px</code>, <code>512px</code>, dan <code>maskable 192px</code>.
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:700;">
                <i class="fas fa-upload mr-2"></i> Upload & Terapkan Ikon
            </button>
        </x-slot>
    </x-card>
</form>

<div class="mt-4"></div>

{{-- ═══════════════════════════════════════════════════════ --}}
{{--  BAGIAN 2: Konfigurasi Nama & Warna PWA                 --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<form action="{{ route('setting.update', $setting->id) }}?pills=pwa" method="post">
    @csrf
    @method('put')

    <x-card>
        {{-- Nama Aplikasi --}}
        <h6 class="font-weight-bold mb-3" style="color:#374151;border-bottom:2px solid #ecfdf5;padding-bottom:10px;">
            <i class="fas fa-mobile-alt mr-2 text-success"></i> Identitas & Tampilan Aplikasi
        </h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pwa_name">Nama Aplikasi PWA</label>
                    <input type="text" name="pwa_name" id="pwa_name"
                        class="form-control @error('pwa_name') is-invalid @enderror"
                        value="{{ old('pwa_name') ?? $setting->pwa_name }}"
                        placeholder="Misal: MTs Bustanul Huda Digital">
                    @error('pwa_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    <small class="text-muted">Nama lengkap saat instalasi.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pwa_short_name">Nama Pendek</label>
                    <input type="text" name="pwa_short_name" id="pwa_short_name"
                        class="form-control @error('pwa_short_name') is-invalid @enderror"
                        value="{{ old('pwa_short_name') ?? $setting->pwa_short_name }}"
                        placeholder="Misal: Madrasah" maxlength="50">
                    @error('pwa_short_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    <small class="text-muted">Tampil di bawah ikon HP (maks. 50 karakter).</small>
                </div>
            </div>
        </div>

        {{-- Warna --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Warna Tema (Theme Color)</label>
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <input type="color" name="pwa_theme_color" id="pwa_theme_color"
                            value="{{ old('pwa_theme_color') ?? ($setting->pwa_theme_color ?? '#10b981') }}"
                            style="width:50px;height:42px;padding:4px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                        <div style="flex:1;padding:10px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-weight:700;font-family:monospace;font-size:.9rem;color:#1e293b;" id="theme_hex_display">
                            {{ old('pwa_theme_color') ?? ($setting->pwa_theme_color ?? '#10b981') }}
                        </div>
                    </div>
                    <small class="text-muted">Warna bar navigasi browser / status bar sistem.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Warna Background Splash Screen</label>
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <input type="color" name="pwa_background_color" id="pwa_background_color"
                            value="{{ old('pwa_background_color') ?? ($setting->pwa_background_color ?? '#ffffff') }}"
                            style="width:50px;height:42px;padding:4px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;">
                        <div style="flex:1;padding:10px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-weight:700;font-family:monospace;font-size:.9rem;color:#1e293b;" id="bg_hex_display">
                            {{ old('pwa_background_color') ?? ($setting->pwa_background_color ?? '#ffffff') }}
                        </div>
                    </div>
                    <small class="text-muted">Warna loading screen pertama kali dibuka.</small>
                </div>
            </div>
        </div>

        {{-- Versi --}}
        <div class="d-flex align-items-center p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
            <i class="fas fa-code-branch mr-2" style="color:#16a34a;"></i>
            <div>
                <small class="font-weight-bold" style="color:#15803d;">Versi PWA Saat Ini:</small>
                <strong style="color:#166534;margin-left:8px;font-family:monospace;">v{{ $setting->pwa_version ?? '1.0.0' }}</strong>
                <small class="text-muted ml-2">— versi akan otomatis bertambah saat Anda menyimpan, memicu notifikasi update ke semua pengguna.</small>
            </div>
        </div>

        <x-slot name="footer">
            <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:700;">
                <i class="fas fa-save mr-2"></i> Simpan & Publikasikan Update
            </button>
        </x-slot>
    </x-card>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Color picker live update
    const themeInput = document.getElementById('pwa_theme_color');
    const themeDisplay = document.getElementById('theme_hex_display');
    const bgInput = document.getElementById('pwa_background_color');
    const bgDisplay = document.getElementById('bg_hex_display');

    if (themeInput) themeInput.addEventListener('input', () => { themeDisplay.textContent = themeInput.value; });
    if (bgInput) bgInput.addEventListener('input', () => { bgDisplay.textContent = bgInput.value; });

    // Icon preview before upload
    const iconInput = document.getElementById('pwa_icon');
    if (iconInput) {
        iconInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('icon-preview-large').src = e.target.result;
                    document.getElementById('pwa-icon-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
                document.querySelector('.custom-file-label').textContent = file.name;
            }
        });
    }
});
</script>
