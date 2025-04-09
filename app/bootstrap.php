<?php
// Uygulama başlatma dosyası
// Gerekli dosyaları ve kaynakları yükler

// Config dosyasını yükle
require_once 'config/config.php';

// Helper fonksiyonlarını yükle
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

// Core Libraries
require_once 'core/Controller.php';
require_once 'core/App.php';
require_once 'core/Database.php';

// Model sınıfları
require_once 'models/Log.php';

// Helper fonksiyonları (modeller yüklendikten sonra)
require_once 'helpers/log_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    require_once 'libraries/' . $className . '.php';
}); 