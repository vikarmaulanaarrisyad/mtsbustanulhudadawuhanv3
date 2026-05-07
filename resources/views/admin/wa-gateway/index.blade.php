@extends('layouts.app')

@section('title', 'WA Gateway Center')

@section('content')
<div class="container-fluid py-4">
    <!-- PREMIUM HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-gradient-success overflow-hidden position-relative" style="border-radius: 20px;">
                <div class="card-body p-4 position-relative" style="z-index: 1;">
                    <div class="row align-items-center">
                        <div class="col-md-8 text-white">
                            <h2 class="font-weight-bold mb-1"><i class="fab fa-whatsapp mr-2"></i> WA Gateway Center</h2>
                            <p class="mb-0 opacity-8">Kirim notifikasi, pengumuman, dan pesan massal langsung ke nomor WhatsApp Guru & Siswa.</p>
                        </div>
                        <div class="col-md-4 text-right d-none d-md-block">
                            <i class="fab fa-whatsapp fa-8x opacity-2 shadow-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-circle-1"></div>
                <div class="bg-circle-2"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT: SETTINGS & COMPOSER -->
        <div class="col-lg-8 animate__animated animate__fadeInLeft">
            <!-- API CONFIGURATION -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex align-items-center">
                    <i class="fas fa-key text-success mr-2"></i>
                    <h5 class="card-title font-weight-bold mb-0">Konfigurasi API Gateway</h5>
                    <button class="btn btn-sm btn-light ml-auto rounded-pill" type="button" data-toggle="collapse" data-target="#apiCollapse">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
                <div id="apiCollapse" class="collapse">
                    <div class="card-body bg-light-soft">
                        <form action="{{ route('admin.wa-gateway.update_settings') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label class="text-xs font-weight-bold text-muted uppercase">API URL</label>
                                    <input type="text" name="wa_api_url" class="form-control rounded-pill border-2" value="{{ $setting->wa_api_url }}" placeholder="https://api.fonnte.com/send">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-xs font-weight-bold text-muted uppercase">API Token</label>
                                    <input type="password" name="wa_api_token" class="form-control rounded-pill border-2" value="{{ $setting->wa_api_token }}" placeholder="Enter Token">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Device ID / Sender</label>
                                    <input type="text" name="wa_api_sender" class="form-control rounded-pill border-2" value="{{ $setting->wa_api_sender }}" placeholder="Optional">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold shadow-sm">SIMPAN KONFIGURASI</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MESSAGE COMPOSER -->
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-4 border-bottom">
                    <h4 class="mb-0 font-weight-bold text-dark">Kirim Pesan Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form id="waForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase mb-2 d-block">Target Penerima</label>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    <input type="radio" class="btn-check" name="target_type" id="target_guru" value="guru" checked autocomplete="off">
                                    <label class="btn btn-outline-success rounded-pill px-4 py-2 font-weight-bold" for="target_guru">
                                        <i class="fas fa-user-tie mr-2"></i> SEMUA GURU
                                    </label>

                                    <input type="radio" class="btn-check" name="target_type" id="target_siswa" value="siswa" autocomplete="off">
                                    <label class="btn btn-outline-success rounded-pill px-4 py-2 font-weight-bold" for="target_siswa">
                                        <i class="fas fa-user-graduate mr-2"></i> SEMUA SISWA
                                    </label>

                                    <input type="radio" class="btn-check" name="target_type" id="target_all" value="all" autocomplete="off">
                                    <label class="btn btn-outline-success rounded-pill px-4 py-2 font-weight-bold" for="target_all">
                                        <i class="fas fa-users mr-2"></i> SEMUA
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase mb-2 d-block">Contoh Pesan Cepat</label>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3 font-weight-bold border" onclick="setMessage('Pemberitahuan: Bapak/Ibu Guru dimohon segera mengisi Jurnal KBM untuk hari ini. Terima kasih.')">Tagih Jurnal</button>
                                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3 font-weight-bold border" onclick="setMessage('Info Madrasah: Besok hari Libur Nasional. Kegiatan Belajar Mengajar diliburkan dan masuk kembali pada lusa.')">Info Libur</button>
                                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3 font-weight-bold border" onclick="setMessage('Penting: Rapat Dewan Guru akan dilaksanakan hari ini pukul 13.00 WIB di Aula. Harap hadir tepat waktu.')">Rapat Guru</button>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase mb-2 d-block">Isi Pesan</label>
                                <div class="position-relative">
                                    <textarea name="message" id="waMessage" rows="8" class="form-control border-2" style="border-radius: 15px; padding: 20px;" placeholder="Tuliskan pesan Anda di sini..."></textarea>
                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                        <small class="text-muted font-weight-bold">Gunakan <span class="badge badge-light">*Tebal*</span>, <span class="badge badge-light">_Miring_</span>, atau <span class="badge badge-light">~Coret~</span></small>
                                        <small id="charCount" class="text-muted font-weight-bold">0 Karakter</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="button" id="btnSend" onclick="sendBroadcast()" class="btn btn-success btn-lg btn-block rounded-pill font-weight-bold py-3 shadow-success-light">
                                    <i class="fas fa-paper-plane mr-2"></i> KIRIM PESAN MASSAL
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT: PHONE PREVIEW -->
        <div class="col-lg-4 animate__animated animate__fadeInRight">
            <div class="card shadow-sm border-0 overflow-hidden sticky-top" style="border-radius: 20px; top: 20px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <i class="fas fa-mobile-alt text-muted mr-2"></i>
                    <h6 class="mb-0 font-weight-bold text-muted uppercase">Preview Pesan</h6>
                </div>
                <div class="card-body p-4 bg-light d-flex justify-content-center">
                    <!-- WhatsApp Phone Mockup -->
                    <div class="wa-phone">
                        <div class="wa-header">
                            <div class="wa-status-bar">
                                <span>12:45</span>
                                <div class="wa-status-icons">
                                    <i class="fas fa-signal"></i>
                                    <i class="fas fa-wifi"></i>
                                    <i class="fas fa-battery-full"></i>
                                </div>
                            </div>
                            <div class="wa-chat-header">
                                <i class="fas fa-arrow-left"></i>
                                <div class="wa-avatar">
                                    <img src="{{ asset('img/default.jpg') }}" alt="">
                                </div>
                                <div class="wa-chat-info">
                                    <span class="wa-name">Admin Madrasah</span>
                                    <span class="wa-status">online</span>
                                </div>
                                <div class="wa-chat-actions">
                                    <i class="fas fa-video"></i>
                                    <i class="fas fa-phone"></i>
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                        </div>
                        <div class="wa-chat-body" id="previewBody">
                            <div class="wa-msg-received">
                                <div class="wa-bubble">
                                    <div class="wa-msg-text" id="waPreviewText">Tulis pesan untuk melihat preview...</div>
                                    <div class="wa-msg-time">12:45 <i class="fas fa-check-double ml-1"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="wa-footer">
                            <div class="wa-input">
                                <i class="far fa-smile"></i>
                                <span>Ketik pesan</span>
                                <i class="fas fa-paperclip"></i>
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="wa-mic">
                                <i class="fas fa-microphone"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }
    .shadow-success-light { box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }

    .btn-check { position: absolute; clip: rect(0,0,0,0); pointer-events: none; }
    .btn-outline-success { border-width: 2px; }
    .btn-check:checked + .btn-outline-success { background-color: #10b981; border-color: #10b981; color: white; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2); }

    /* Phone Mockup */
    .wa-phone {
        width: 100%; max-width: 280px; height: 500px;
        background: #e5ddd5; border-radius: 35px;
        border: 8px solid #333; overflow: hidden;
        display: flex; flex-direction: column;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .wa-header { background: #075e54; color: white; padding: 10px; }
    .wa-status-bar { display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 8px; padding: 0 5px; }
    .wa-status-icons i { margin-left: 3px; }
    .wa-chat-header { display: flex; align-items: center; }
    .wa-avatar { width: 30px; height: 30px; border-radius: 50%; overflow: hidden; margin: 0 8px; background: #eee; }
    .wa-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .wa-chat-info { flex-grow: 1; display: flex; flex-direction: column; }
    .wa-name { font-size: 12px; font-weight: bold; }
    .wa-status { font-size: 9px; opacity: 0.8; }
    .wa-chat-actions { display: flex; gap: 12px; font-size: 12px; opacity: 0.8; }

    .wa-chat-body { flex-grow: 1; padding: 15px; overflow-y: auto; background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-size: cover; }
    .wa-bubble { background: white; padding: 8px 12px; border-radius: 8px; max-width: 85%; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .wa-msg-received .wa-bubble::before { content: ''; position: absolute; left: -8px; top: 0; border: 8px solid transparent; border-top-color: white; border-right-color: white; }
    .wa-msg-text { font-size: 11px; color: #333; white-space: pre-wrap; word-break: break-word; }
    .wa-msg-time { font-size: 8px; color: #999; text-align: right; margin-top: 4px; }
    .wa-msg-time i { color: #4fc3f7; }

    .wa-footer { padding: 10px; display: flex; align-items: center; gap: 8px; }
    .wa-input { background: white; border-radius: 20px; padding: 6px 12px; display: flex; align-items: center; flex-grow: 1; gap: 10px; font-size: 12px; color: #999; }
    .wa-mic { background: #075e54; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; }

    #waMessage:focus { border-color: #10b981; box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.1); }
</style>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    const waInput = document.getElementById('waMessage');
    const waPreview = document.getElementById('waPreviewText');
    const charCount = document.getElementById('charCount');

    waInput.addEventListener('input', function() {
        updatePreview();
    });

    function setMessage(msg) {
        waInput.value = msg;
        updatePreview();
    }

    function updatePreview() {
        let text = waInput.value;
        charCount.innerText = text.length + ' Karakter';
        
        if (!text) {
            waPreview.innerText = 'Tulis pesan untuk melihat preview...';
            return;
        }

        // Basic WhatsApp Formatting
        text = text
            .replace(/\*(.*?)\*/g, '<b>$1</b>')
            .replace(/_(.*?)_/g, '<i>$1</i>')
            .replace(/~(.*?)~/g, '<del>$1</del>');
        
        waPreview.innerHTML = text;
    }

    function sendBroadcast() {
        const formData = $('#waForm').serialize();
        const btn = $('#btnSend');
        
        if (!waInput.value) {
            Swal.fire('Gagal', 'Pesan tidak boleh kosong.', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: "Kirim pesan ini ke seluruh target terpilih?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'YA, KIRIM SEKARANG'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> SEDANG MENGIRIM...');
                
                $.post('{{ route("admin.wa-gateway.send") }}', formData)
                    .done(res => {
                        Swal.fire('Berhasil', res.message, 'success');
                        waInput.value = '';
                        updatePreview();
                    })
                    .fail(err => {
                        Swal.fire('Gagal', err.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                    })
                    .always(() => {
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> KIRIM PESAN MASSAL');
                    });
            }
        });
    }
</script>
@endpush
