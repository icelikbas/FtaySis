<?php
// Ana uygulama dosyası - Tüm istekler buradan yönlendirilir
define('ROOT', dirname(__FILE__));

// Oturum başlatma
session_start();

// Çekirdek dosyaları dahil et
require_once 'app/config/config.php';
require_once 'app/helpers/url_helper.php';
require_once 'app/helpers/session_helper.php';
require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';

// Modelleri dahil et - Log'dan önce Database yüklenmiş olmalı
require_once 'app/models/Log.php';

// Log helper'ı yükle (Log modeli yüklendikten sonra)
require_once 'app/helpers/log_helper.php';

// Uygulamayı başlat
$app = new App; 