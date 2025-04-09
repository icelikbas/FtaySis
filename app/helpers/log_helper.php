<?php
// Log işlemleri için yardımcı fonksiyonlar

// Log model sınıfını dahil et
// require_once 'app/models/Log.php';

/**
 * Oturum açma işlemini logla
 *
 * @param string $username - Giriş yapan kullanıcının adı
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logLogin($username, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Kullanıcı adı: ' . $username;
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Oturum açıldı', 'login', $logDetails);
}

/**
 * Oturum kapatma işlemini logla
 *
 * @param string $username - Çıkış yapan kullanıcının adı
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logLogout($username, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Kullanıcı adı: ' . $username;
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Oturum kapatıldı', 'logout', $logDetails);
}

/**
 * Kayıt oluşturma işlemini logla
 *
 * @param string $module - Modül adı (users, vehicles, drivers, vb.)
 * @param int $recordId - Oluşturulan kaydın ID'si
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logCreate($module, $recordId, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Modül: ' . $module . ', ID: ' . $recordId;
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Kayıt oluşturuldu', 'create', $logDetails);
}

/**
 * Kayıt güncelleme işlemini logla
 *
 * @param string $module - Modül adı (users, vehicles, drivers, vb.)
 * @param int $recordId - Güncellenen kaydın ID'si
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logUpdate($module, $recordId, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Modül: ' . $module . ', ID: ' . $recordId;
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Kayıt güncellendi', 'update', $logDetails);
}

/**
 * Kayıt silme işlemini logla
 *
 * @param string $module - Modül adı (users, vehicles, drivers, vb.)
 * @param int $recordId - Silinen kaydın ID'si
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logDelete($module, $recordId, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Modül: ' . $module . ', ID: ' . $recordId;
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Kayıt silindi', 'delete', $logDetails);
}

/**
 * Hata durumunu logla
 *
 * @param string $errorMessage - Hata mesajı
 * @param string $module - Modül adı (isteğe bağlı)
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logError($errorMessage, $module = '', $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    $logDetails = 'Hata: ' . $errorMessage;
    if (!empty($module)) {
        $logDetails .= ', Modül: ' . $module;
    }
    if (!empty($details)) {
        $logDetails .= ', ' . $details;
    }
    return $logModel->create('Hata oluştu', 'error', $logDetails);
}

/**
 * Sistem olayını logla
 *
 * @param string $action - Gerçekleştirilen işlem
 * @param string $type - Log türü
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logSystem($action, $type, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    return $logModel->create($action, $type, $details);
}

/**
 * Güvenlikle ilgili olayları logla
 *
 * @param string $action - Gerçekleştirilen işlem
 * @param string $details - Ek detaylar (isteğe bağlı)
 * @return bool
 */
function logSecurity($action, $details = '') {
    // Log modelini oluştur
    $logModel = new Log();
    
    return $logModel->create($action, 'security', $details);
} 