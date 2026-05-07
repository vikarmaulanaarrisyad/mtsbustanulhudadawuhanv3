<div class="animate__animated animate__fadeIn">
    <div class="d-flex align-items-center mb-4">
        <div class="icon-shape bg-soft-primary mr-3" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #4f46e5;">
            <i class="fas fa-robot"></i>
        </div>
        <div>
            <h4 class="font-weight-bold text-dark mb-0">Integrasi AI (Artificial Intelligence)</h4>
            <p class="text-muted mb-0">Konfigurasi kunci API untuk fitur kecerdasan buatan dalam aplikasi.</p>
        </div>
    </div>

    <form action="{{ route('setting.update', $setting->id) }}?pills=ai" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="pills" value="ai">

        <div class="card border shadow-none rounded-xl mb-4">
            <div class="card-body p-4">
                <div class="alert alert-soft-primary border-0 rounded-xl mb-4 p-3 d-flex align-items-start">
                    <i class="fas fa-lightbulb mr-3 mt-1 text-primary fa-lg"></i>
                    <div class="text-sm">
                        <h6 class="font-weight-bold mb-1">Tentang Google Gemini AI</h6>
                        <p class="mb-0">Aplikasi ini menggunakan <b>Google Gemini 1.5 Flash</b> untuk fitur Question Generator. Anda bisa mendapatkan API Key secara gratis melalui <a href="https://aistudio.google.com/app/apikey" target="_blank" class="font-weight-bold text-primary">Google AI Studio</a>.</p>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label-premium">GEMINI API KEY</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-key text-muted"></i></span>
                        </div>
                        <input type="password" name="gemini_api_key" id="gemini_api_key" class="form-control border-left-0" value="{{ $setting->gemini_api_key }}" placeholder="Masukkan kunci API Gemini Anda...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary border-left-0" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-shield-alt mr-1"></i> API Key disimpan secara aman di database sekolah.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-footer bg-transparent border-0 p-0 text-right">
            <button type="submit" class="btn btn-primary px-5 py-3 rounded-xl font-weight-bold shadow-lg hover-lift">
                <i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN AI
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('gemini_api_key');
    const icon = document.getElementById('toggleIcon');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<style>
.bg-soft-primary { background: #eef2ff !important; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; transition: all 0.2s; }
.rounded-xl { border-radius: 12px !important; }
</style>
