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
                <div class="form-group mb-4">
                    <label class="form-label-premium">PROVIDER AI AKTIF</label>
                    <div class="d-flex align-items-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="provider_gemini" name="ai_provider" value="gemini" class="custom-control-input" {{ $setting->ai_provider == 'gemini' ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="provider_gemini">Google Gemini</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="provider_groq" name="ai_provider" value="groq" class="custom-control-input" {{ $setting->ai_provider == 'groq' ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="provider_groq">Groq (Ultra Fast)</label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- GEMINI SETTINGS -->
                <div id="gemini_settings" style="{{ $setting->ai_provider != 'gemini' ? 'display:none;' : '' }}">
                    <div class="alert alert-soft-primary border-0 rounded-xl mb-4 p-3 d-flex align-items-start">
                        <i class="fas fa-magic mr-3 mt-1 text-primary fa-lg"></i>
                        <div class="text-sm">
                            <h6 class="font-weight-bold mb-1">Konfigurasi Google Gemini</h6>
                            <p class="mb-0">Dapatkan API Key di <a href="https://aistudio.google.com/app/apikey" target="_blank" class="font-weight-bold text-primary">Google AI Studio</a>.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">MODEL GEMINI</label>
                                <select name="gemini_model" class="form-control rounded-xl">
                                    <option value="gemini-1.5-flash" {{ $setting->gemini_model == 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (Cepat)</option>
                                    <option value="gemini-1.5-pro" {{ $setting->gemini_model == 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Smarter)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">GEMINI API KEY</label>
                                <input type="password" name="gemini_api_key" class="form-control rounded-xl" value="{{ $setting->gemini_api_key }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GROQ SETTINGS -->
                <div id="groq_settings" style="{{ $setting->ai_provider != 'groq' ? 'display:none;' : '' }}">
                    <div class="alert alert-soft-success border-0 rounded-xl mb-4 p-3 d-flex align-items-start">
                        <i class="fas fa-bolt mr-3 mt-1 text-success fa-lg"></i>
                        <div class="text-sm">
                            <h6 class="font-weight-bold mb-1">Konfigurasi Groq (Ultra Fast)</h6>
                            <p class="mb-0">Dapatkan API Key di <a href="https://console.groq.com/" target="_blank" class="font-weight-bold text-success">Groq Cloud</a>.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">MODEL GROQ</label>
                                <select name="groq_model" class="form-control rounded-xl">
                                    <option value="llama-3.1-8b-instant" {{ $setting->groq_model == 'llama-3.1-8b-instant' ? 'selected' : '' }}>Llama 3.1 8B (Ultra Fast)</option>
                                    <option value="llama-3.3-70b-versatile" {{ $setting->groq_model == 'llama-3.3-70b-versatile' ? 'selected' : '' }}>Llama 3.3 70B (Smart & Versatile)</option>
                                    <option value="gemma2-9b-it" {{ $setting->groq_model == 'gemma2-9b-it' ? 'selected' : '' }}>Gemma 2 9B (Efficient)</option>
                                    <option value="mixtral-8x7b-32768" {{ $setting->groq_model == 'mixtral-8x7b-32768' ? 'selected' : '' }}>Mixtral 8x7B (Complex Reasoning)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label-premium">GROQ API KEY</label>
                                <input type="password" name="groq_api_key" class="form-control rounded-xl" value="{{ $setting->groq_api_key }}">
                            </div>
                        </div>
                    </div>
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
document.querySelectorAll('input[name="ai_provider"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'gemini') {
            document.getElementById('gemini_settings').style.display = 'block';
            document.getElementById('groq_settings').style.display = 'none';
        } else {
            document.getElementById('gemini_settings').style.display = 'none';
            document.getElementById('groq_settings').style.display = 'block';
        }
    });
});
</script>

<style>
.bg-soft-primary { background: #eef2ff !important; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; transition: all 0.2s; }
.rounded-xl { border-radius: 12px !important; }
</style>
