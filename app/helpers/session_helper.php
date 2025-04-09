<?php
/**
 * Oturum Yardımcı Fonksiyonları
 */

// Flash mesaj yardımcısı
// ÖRNEK - flash('register_success', 'Kayıt oldunuz', 'alert alert-success');
// DISPLAY IN VIEW - <?php echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Giriş yapılıp yapılmadığını kontrol eder
function isLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

// Kullanıcının admin olup olmadığını kontrol eder
function isAdmin() {
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        return true;
    } else {
        return false;
    }
} 