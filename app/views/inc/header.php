<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo SITENAME; ?></title>
    
    <!-- jQuery - diğer kütüphanelerden önce yüklenmeli -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- favicon dosyası  -->
    <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/public/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- DataTables CSS - Bootstrap 5 Uyumlu -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    
    <!-- Custom CSS - En son yükleyerek diğer stilleri geçersiz kılmasını sağlıyoruz -->
    <link rel="stylesheet" href="<?php echo getPublicUrl('css/style.css'); ?>">
</head>
<body>
    <!-- Üst Menü -->
    <nav class="navbar navbar-expand-lg top-navbar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo URLROOT; ?>">
                <i class="fas fa-truck me-2"></i> <?php echo SITENAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="filoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-truck-moving me-2"></i> Filo Yönetimi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/vehicles"><i class="fas fa-truck me-2"></i> Araçlar</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/drivers"><i class="fas fa-user-tie me-2"></i> Sürücüler</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/assignments"><i class="fas fa-tasks me-2"></i> Görevlendirmeler</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="maintenanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tools me-2"></i> Bakım & Yakıt
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/maintenance"><i class="fas fa-wrench me-2"></i> Bakım Takibi</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/fuel"><i class="fas fa-gas-pump me-2"></i> Yakıt Takibi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/tanks"><i class="fas fa-oil-can me-2"></i> Yakıt Tankları</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/purchases"><i class="fas fa-shopping-cart me-2"></i> Yakıt Alımları</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/transfers"><i class="fas fa-exchange-alt me-2"></i> Yakıt Transferleri</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="docsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-alt me-2"></i> Belgeler
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/licenses"><i class="fas fa-id-card me-2"></i> Ehliyet Belgeleri</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/certificates"><i class="fas fa-certificate me-2"></i> Sertifikalar</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/insurance"><i class="fas fa-shield-alt me-2"></i> Sigorta İşlemleri</a></li>
                        </ul>
                    </li>
                    <?php if(isAdmin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-2"></i> Yönetim
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users"><i class="fas fa-users me-2"></i> Kullanıcılar</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/companies"><i class="fas fa-building me-2"></i> Şirketler</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/licensetypes"><i class="fas fa-list-alt me-2"></i> Ehliyet Tipleri</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/certificatetypes"><i class="fas fa-tag me-2"></i> Sertifika Tipleri</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-chart-bar me-2"></i> Raporlar
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/reports"><i class="fas fa-file-alt me-2"></i> Tüm Raporlar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/logs"><i class="fas fa-history me-2"></i> Sistem Logları</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if(isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-profile" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Kullanıcı'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/profile"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/settings"><i class="fas fa-cog me-2"></i> Ayarlar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt me-2"></i> Çıkış</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/users/login"><i class="fas fa-sign-in-alt me-2"></i> Giriş Yap</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Ana İçerik Alanı -->
    <div class="main-content">
        <main role="main" class="container-fluid">
            <?php if(isLoggedIn()): ?>
                <!-- Ana içerik -->
                <div class="row">
                    <div class="col-12">
            <?php else: ?>
                <!-- Giriş yapmamış kullanıcılar için tam genişlik -->
                <div class="row">
                    <div class="col-12">
            <?php endif; ?> 