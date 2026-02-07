<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Giriş Yap | Kitap Dünyası Yayıncılık</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: #f4f7f6;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            overflow: hidden; /* Animasyonun taşmasını engeller */
        }
        
        /* İstediğin Font ve Renk Tanımlaması */
        .brand-logo, .welcome-text {
            font-family: 'Script MT Bold', 'Dancing Script', cursive; 
            color: #e67e22 !important; 
        }

        .brand-logo {
            font-size: 32px;
            font-weight: 800;
            text-decoration: none;
            display: block;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Hoş Geldiniz Animasyonu */
        .welcome-container {
            overflow: hidden;
            margin-bottom: 25px;
        }
        .welcome-text {
            font-size: 26px;
            font-weight: bold;
            display: block;
            text-align: center;
            animation: slideInRight 1.5s ease-out forwards;
        }

        @keyframes slideInRight {
            0% { transform: translateX(100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        /* Form Elemanları */
        .form-control {
            position: relative;
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            
        }
        .form-control:focus {
         border-color: #e67e22;
        box-shadow: 0 0 0 0.15rem rgba(230, 126, 34, 0.3);
        outline: none;
        }
        .btn-login {
            background-color: #e67e22;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }
        .btn-login:hover { background-color: #d35400; }
        .divider { height: 1px; background: #eee; margin: 25px 0; position: relative; }
        .divider::after {
            content: "VEYA";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: #fff; padding: 0 10px; color: #999; font-size: 11px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <a href="#" class="brand-logo">
        <i class="fas fa-book-open"></i> Kitap Dünyası
    </a>
    
    <div class="welcome-container">
        <span class="welcome-text">Hoş Geldiniz</span>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center py-2 mb-4 rounded-3">
            <div id="error-message">
                <small><?= session()->getFlashdata('error') ?></small>
            </div>
            <div id="countdown-timer" class="fw-bold mt-1" style="display:none;">
                <small>Kalan Süre: <span id="seconds">0</span> sn</small>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login/auth') ?>" method="post" id="login-form">
        <div class="mb-2">
            <label class="form-label small text-muted">E-posta Adresiniz</label>
            <input type="email" name="email" class="form-control" placeholder="E-posta" required>
        </div>
        <div class="mb-2">
            <label class="form-label small text-muted">Şifreniz</label>
            <input type="password" name="password" class="form-control" placeholder="Şifre" required>
        </div>
        
        <div class="d-flex justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label small text-muted" for="remember">Beni Hatırla</label>
            </div>
            <a href="#" class="small text-decoration-none" style="color: #e67e22;">Şifremi Unuttum</a>
        </div>

        <button type="submit" id="submit-btn" class="btn-login">Giriş Yap</button>
    </form>

    <div class="divider"></div>

    <div class="social-login d-flex justify-content-center gap-3 mb-4">
        <a href="#" class="btn btn-outline-light border text-dark btn-sm rounded-circle"><i class="fab fa-google"></i></a>
        <a href="#" class="btn btn-outline-light border text-dark btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
    </div>

    <p class="text-center small text-muted mb-0">
        Hesabınız yok mu? <a href="<?= base_url('register') ?>" class="text-decoration-none fw-bold" style="color: #e67e22;">Hemen Üye Ol</a>
    </p>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const errorMsg = "<?= session()->getFlashdata('error') ?>";
    const match = errorMsg.match(/(\d+)\s+saniye/); 

    if (match) {
        let timeLeft = parseInt(match[1]);
        const countdownDiv = document.getElementById('countdown-timer');
        const secondsSpan = document.getElementById('seconds');
        const submitBtn = document.getElementById('submit-btn');

        countdownDiv.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';

        const timer = setInterval(function() {
            timeLeft--;
            if (secondsSpan) secondsSpan.innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownDiv.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                if(document.getElementById('error-message')) 
                    document.getElementById('error-message').style.display = 'none';
            }
        }, 1000);
    }
});
</script>

</body>
</html>