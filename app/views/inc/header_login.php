<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo SITENAME; ?> - Giriş</title>

    <!-- jQuery - diğer kütüphanelerden önce yüklenmeli -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- DataTables CSS - Bootstrap 5 Uyumlu (Giriş sayfasında muhtemelen gereksiz ama genel tutarlılık için kalabilir) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

    <!-- Select2 CSS (Giriş sayfasında muhtemelen gereksiz ama genel tutarlılık için kalabilir) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- SweetAlert2 CSS (Giriş sayfasında muhtemelen gereksiz ama genel tutarlılık için kalabilir) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

    <!-- Custom CSS - En son yükleyerek diğer stilleri geçersiz kılmasını sağlıyoruz -->
    <link rel="stylesheet" href="<?php echo getPublicUrl('css/style.css'); ?>">

    <!-- Giriş Sayfasına Özel Stiller (Opsiyonel) -->
    <style>
        /* Gerekirse buraya sadece giriş sayfasına özel CSS kuralları eklenebilir */
        body {
            background-color: #f8f9fa; /* Hafif bir arka plan rengi */
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* login.php içindeki stiller buraya taşınabilir veya burada genişletilebilir */
    </style>
</head>
<body>
    <!-- Bu dosyada navigasyon veya ana içerik sarmalayıcı yok -->
