<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Gagal - Dokumen Tidak Valid</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-card { background: white; border-radius: 30px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 450px; width: 100%; text-align: center; padding: 50px 30px; }
        .error-icon { font-size: 80px; color: #ef4444; margin-bottom: 25px; }
        .btn-home { background: #0f172a; color: white; border-radius: 12px; padding: 12px 30px; font-weight: 700; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="error-card animate__animated animate__shakeX">
        <div class="error-icon"><i class="fas fa-times-circle"></i></div>
        <h3 class="font-weight-bold text-dark mb-2">Verifikasi Gagal</h3>
        <p class="text-muted">Maaf, kode verifikasi tidak ditemukan atau dokumen tidak terdaftar dalam sistem kami.</p>
        <div class="alert alert-danger border-0 rounded-lg mt-4 text-left small">
            <i class="fas fa-exclamation-triangle mr-2"></i> <b>Peringatan:</b> Jika Anda memegang dokumen fisik dengan kode ini, harap waspada terhadap kemungkinan pemalsuan.
        </div>
        <a href="/" class="btn btn-home btn-block mt-4">KEMBALI KE BERANDA</a>
    </div>
</body>
</html>
