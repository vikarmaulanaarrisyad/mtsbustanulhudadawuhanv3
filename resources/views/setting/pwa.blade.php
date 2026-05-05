<form action="{{ route('setting.update', $setting->id) }}?pills=pwa" method="post">
    @csrf
    @method('put')

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="pwa_name">Nama Aplikasi PWA</label>
                <input type="text" name="pwa_name" id="pwa_name" class="form-control @error('pwa_name') is-invalid @enderror" value="{{ old('pwa_name') ?? $setting->pwa_name }}">
                @error('pwa_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Nama lengkap yang muncul saat instalasi.</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pwa_short_name">Nama Pendek (Short Name)</label>
                <input type="text" name="pwa_short_name" id="pwa_short_name" class="form-control @error('pwa_short_name') is-invalid @enderror" value="{{ old('pwa_short_name') ?? $setting->pwa_short_name }}">
                @error('pwa_short_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Nama yang muncul di bawah ikon di layar HP.</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="pwa_theme_color">Warna Tema (Theme Color)</label>
                <div class="input-group">
                    <input type="color" name="pwa_theme_color" id="pwa_theme_color" class="form-control" value="{{ old('pwa_theme_color') ?? $setting->pwa_theme_color }}" style="height: 45px; padding: 5px;">
                </div>
                @error('pwa_theme_color')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                <small class="text-muted">Warna bar navigasi browser/sistem.</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pwa_background_color">Warna Background (Splash Screen)</label>
                <div class="input-group">
                    <input type="color" name="pwa_background_color" id="pwa_background_color" class="form-control" value="{{ old('pwa_background_color') ?? $setting->pwa_background_color }}" style="height: 45px; padding: 5px;">
                </div>
                @error('pwa_background_color')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                <small class="text-muted">Warna latar belakang saat aplikasi dibuka.</small>
            </div>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm" style="border-radius: 15px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x mr-3"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Informasi Ikon</h6>
                <p class="mb-0 text-sm">Untuk mengubah ikon PWA, silakan ganti file <code>public/icons/icon-192x192.png</code> dan <code>public/icons/icon-512x512.png</code> secara manual untuk saat ini demi menjaga kualitas gambar.</p>
            </div>
        </div>
    </div>

    <div class="card-footer px-0">
        <button type="submit" class="btn btn-dark btn-lg px-5" style="border-radius: 12px; font-weight: 700;">
            <i class="fas fa-save mr-2"></i> Simpan Konfigurasi PWA
        </button>
    </div>
</form>
