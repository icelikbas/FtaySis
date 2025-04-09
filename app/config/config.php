<?php
/**
 * Uygulama Yapılandırma Dosyası
 */

// Veritabanı Parametreleri
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'filo_takip');

// Uygulama Kök Dizini
define('APPROOT', dirname(dirname(__FILE__)));

// URL Kök Dizini (kendi hosting ortamınıza göre değiştirin)
define('URLROOT', 'http://localhost/filo_takip');

// Site Adı
define('SITENAME', 'Filo Takip Sistemi');

// Uygulama Sürümü
define('APPVERSION', '1.0.0'); 