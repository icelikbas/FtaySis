<?php
/**
 * Temel Controller Sınıfı
 * Tüm Controller'lar bu sınıftan türeyecek
 */
class Controller {
    /**
     * Model yükle
     * @param string $model
     * @return object
     */
    public function model($model) {
        // Model dosyasını dahil et
        require_once 'app/models/' . $model . '.php';
        
        // Model örneği oluştur ve döndür
        return new $model();
    }

    /**
     * View yükle
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = []) {
        // View dosyası var mı kontrol et
        if(file_exists('app/views/' . $view . '.php')) {
            // View dosyasını dahil et
            require_once 'app/views/' . $view . '.php';
        } else {
            // View dosyası bulunamadı
            die('Görünüm bulunamadı');
        }
    }
} 