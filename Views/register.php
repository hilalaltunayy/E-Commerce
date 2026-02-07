<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Kayıt Ol | Kitap Dünyası Yayıncılık</title>
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
        .register-card {
            background: #ffffff;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 500px; /* Kayıt formunda daha fazla alan gerekebilir */
            padding: 40px;
            overflow: hidden;
        }
        
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

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.2);
            border-color: #e67e22;
        }
        .btn-register {
            background-color: #e67e22;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover { background-color: #d35400; }
    </style>
</head>
<body>

<div class="register-card">
    <a href="<?= base_url('login') ?>" class="brand-logo">
        <i class="fas fa-book-open"></i> Kitap Dünyası
    </a>
    
    <div class="welcome-container">
        <span class="welcome-text">Aramıza Katılın</span>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2 small text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('register/save') ?>" method="post">
        <div class="mb-1">
            <label class="form-label small text-muted">Adınız Soyadınız</label>
            <input type="text" name="name" class="form-control" placeholder="Örn: Hilal Altunay" required>
        </div>
        
        <div class="mb-1">
            <label class="form-label small text-muted">E-posta Adresiniz</label>
            <input type="email" name="email" class="form-control" placeholder="E-posta" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-1">
                <label class="form-label small text-muted">Şifre</label>
                <input type="password" name="password" class="form-control" placeholder="******" required>
            </div>
            <div class="col-md-6 mb-1">
                <label class="form-label small text-muted">Şifre Tekrar</label>
                <input type="password" name="password_confirm" class="form-control" placeholder="******" required>
            </div>
        </div>
        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label small text-muted" for="terms">
                <a href="#" style="color: #e67e22;">Kullanım Koşullarını</a> okudum ve kabul ediyorum.
            </label>
        </div>

        <button type="submit" class="btn-register">Hesabımı Oluştur</button>
    </form>

    <p class="text-center small text-muted mt-4 mb-0">
        Zaten üye misiniz? <a href="<?= base_url('login') ?>" class="text-decoration-none fw-bold" style="color: #e67e22;">Giriş Yap</a>
    </p>
</div>

</body>
</html>