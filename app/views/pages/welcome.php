<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> - Hoş Geldiniz</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo getPublicUrl('css/style.css'); ?>">
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
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .feature-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        .feature-item:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .feature-item:hover .feature-icon {
            transform: scale(1.2);
            color: #ffcc00;
        }
        .feature-item:hover .feature-title {
            color: #ffcc00;
        }
        .feature-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-item:hover:before {
            opacity: 1;
        }
        .feature-desc {
            font-size: 1rem;
            line-height: 1.6;
            transition: opacity 0.3s ease;
        }
        .feature-item:hover .feature-desc {
            opacity: 0.9;
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
            <i class="fas fa-truck logo-icon"></i>
            <h1><?php echo $data['title']; ?></h1>
        </div>
        
        <p><?php echo $data['description']; ?></p>
        
        <div>
            <a href="<?php echo URLROOT; ?>/users/login" class="btn-custom">
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
        
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo getPublicUrl('js/main.js'); ?>"></script>
</body>
</html> 