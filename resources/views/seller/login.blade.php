<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Portal - Madrasah Digital</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;600&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #090d16 0%, #030712 100%);
            color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }
        
        /* Grid background effect */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 0);
            background-size: 24px 24px;
            z-index: 0;
        }

        /* Glowing Orbs */
        .orb-1 {
            position: absolute;
            top: -200px; right: -200px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            z-index: 0;
        }
        .orb-2 {
            position: absolute;
            bottom: -200px; left: -200px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            z-index: 0;
        }
        
        .login-card {
            background: rgba(17, 24, 39, 0.65);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            padding: 40px;
            z-index: 1;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        
        .logo-box {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.25);
            color: #fff;
        }
        
        .console-tag {
            font-family: 'Fira Code', monospace;
            background: rgba(139, 92, 246, 0.1);
            color: #c084fc;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            display: inline-block;
            margin-bottom: 15px;
            border: 1px solid rgba(139, 92, 246, 0.2);
            font-weight: 600;
        }
        
        h2 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 0%, #9ca3af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        p.subtitle {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        
        label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            color: #9ca3af;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 15px; top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 14px 16px 14px 45px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.05);
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
        }
        
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.3);
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 20px -3px rgba(139, 92, 246, 0.4);
        }
        
        .hint-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px dashed rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 15px;
            margin-top: 30px;
            text-align: left;
        }
        
        .hint-box h6 {
            font-size: 11px;
            color: #3b82f6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .hint-box p {
            font-size: 11px;
            color: #9ca3af;
            font-family: 'Fira Code', monospace;
            line-height: 1.5;
        }
        
        /* SweetAlert Styling Overrides */
        .swal2-popup {
            background: #111827 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 20px !important;
            color: #f3f4f6 !important;
        }
    </style>
</head>
<body>
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    
    <div class="login-card">
        <div class="logo-box">
            <i class="fas fa-terminal fa-2x"></i>
        </div>
        
        <span class="console-tag"><i class="fas fa-code mr-1"></i> DEVELOPER PORTAL v1.0</span>
        
        <h2>Licensor Command Center</h2>
        <p class="subtitle">Silakan autentikasi untuk mengelola lisensi klien.</p>
        
        <form action="{{ url('seller/login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Email Developer</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="developer@mail.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Master Passcode</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="passcode" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt mr-2"></i> ENTER CONSOLE
            </button>
        </form>
        
        <div class="hint-box">
            <h6><i class="fas fa-info-circle mr-1"></i> Developer Account Info:</h6>
            <p>Email: <span style="color: #fff;">developer@madrasah.digital</span></p>
            <p>Passcode: <span style="color: #fff;">dev12345</span></p>
        </div>
    </div>

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#8b5cf6'
        });
    </script>
    @endif
    
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    @endif
</body>
</html>
