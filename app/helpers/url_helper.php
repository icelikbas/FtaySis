<?php
/**
 * URL Yardımcı Fonksiyonları
 */

// URL'ye yönlendirme
function redirect($page) {
    header('Location: ' . URLROOT . '/' . $page);
    exit;
}

// Tam URL döndürür
function getUrl($path = '') {
    return URLROOT . '/' . $path;
}

// Public klasörüne tam yol döndürür
function getPublicUrl($path = '') {
    return URLROOT . '/public/' . $path;
} 