<form action="{{ route('setting.update', $setting->id) }}?pills=payment" method="post">
    @csrf
    @method('put')
    
    <x-card>
        <x-slot name="header">
            <h5 class="card-title">Pengaturan Pembayaran & Midtrans</h5>
        </x-slot>

        <div class="row">
            <div class="col-lg-12">
                <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-university mr-1"></i> Pengaturan Transfer Bank Manual</h6>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="bank_name">Nama Bank <small class="text-muted">(Cth: BCA, BNI, BRI)</small></label>
                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                           id="bank_name" name="bank_name" 
                           value="{{ old('bank_name', $setting->bank_name) }}">
                    @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="bank_account_number">Nomor Rekening</label>
                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                           id="bank_account_number" name="bank_account_number" 
                           value="{{ old('bank_account_number', $setting->bank_account_number) }}">
                    @error('bank_account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="bank_account_name">Atas Nama</label>
                    <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                           id="bank_account_name" name="bank_account_name" 
                           value="{{ old('bank_account_name', $setting->bank_account_name) }}">
                    @error('bank_account_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">
                <h6 class="font-weight-bold text-warning border-bottom pb-2 mb-3"><i class="fas fa-credit-card mr-1"></i> Pengaturan Midtrans Payment Gateway</h6>
            </div>
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
