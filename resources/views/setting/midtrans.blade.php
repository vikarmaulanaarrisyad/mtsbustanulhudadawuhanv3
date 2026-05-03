<form action="{{ route('setting.update', $setting->id) }}?pills=midtrans" method="post">
    @csrf
    @method('put')
    
    <x-card>
        <x-slot name="header">
            <h5 class="card-title">Pengaturan Midtrans Payment Gateway</h5>
        </x-slot>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="midtrans_client_key">Client Key</label>
                    <input type="text" class="form-control @error('midtrans_client_key') is-invalid @enderror" 
                           id="midtrans_client_key" name="midtrans_client_key" 
                           value="{{ old('midtrans_client_key', $setting->midtrans_client_key) }}">
                    @error('midtrans_client_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="midtrans_server_key">Server Key</label>
                    <input type="text" class="form-control @error('midtrans_server_key') is-invalid @enderror" 
                           id="midtrans_server_key" name="midtrans_server_key" 
                           value="{{ old('midtrans_server_key', $setting->midtrans_server_key) }}">
                    @error('midtrans_server_key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="midtrans_is_production" name="midtrans_is_production" value="1" {{ old('midtrans_is_production', $setting->midtrans_is_production) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="midtrans_is_production">Gunakan Environment Production (Matikan untuk Sandbox)</label>
                </div>
                <small class="form-text text-muted mt-2">
                    Jika diaktifkan, sistem akan menggunakan API Production Midtrans. Pastikan Client Key dan Server Key sesuai dengan environment yang dipilih.
                </small>
            </div>
        </div>

        <x-slot name="footer">
            <button class="btn btn-primary">Simpan Pengaturan</button>
        </x-slot>
    </x-card>
</form>
