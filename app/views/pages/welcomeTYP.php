<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Hoş Geldiniz'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .welcome-container {
            max-width: 800px;
            width: 100%;
        }
        .logo-container {
            margin-bottom: 2rem;
        }
        .logo-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            color: white;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-custom {
            background-color: white;
            color: #1e3c72;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: #1e3c72;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 3rem;
        }
        .feature-item {
            flex: 0 0 calc(33.333% - 30px);
            margin: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            min-width: 200px;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .feature-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .footer {
            margin-top: 3rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            .feature-item {
                flex: 0 0 calc(50% - 30px);
            }
        }
        @media (max-width: 576px) {
            .feature-item {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="logo-container">
            <i class="fas fa-gas-pump logo-icon"></i>
            <h1>Filo Akaryakıt Takip Sistemi</h1>
        </div>
        
        <p>Araç filonuzu, akaryakıt tüketiminizi ve bakım işlemlerinizi etkili bir şekilde yönetmek için tasarlanan modern bir platform.</p>
        
        <div>
            <a href="<?= BASE_URL ?>/auth/login" class="btn-custom">
                <i class="fas fa-sign-in-alt me-2"></i> Giriş Yap
            </a>
        </div>
        
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="feature-title">Araç Takibi</div>
                <div class="feature-desc">Tüm araçlarınızın detaylı bilgilerini ve durumlarını tek bir yerden takip edin.</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-gas-pump"></i>
                </div>
                <div class="feature-title">Yakıt Yönetimi</div>
                <div class="feature-desc">Yakıt tüketimini izleyin, yakıt verimliliğini artırın ve masrafları azaltın.</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="feature-title">Bakım Planlaması</div>
                <div class="feature-desc">Bakım takvimlerini yönetin ve araç arızalarını minimize edin.</div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Filo Akaryakıt Takip Sistemi</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 