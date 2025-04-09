<?php
/**
 * Ana Uygulama Sınıfı
 * URL'yi parçalara ayırır ve uygun controller/method'a yönlendirir
 */
class App {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Controller'ı ara
        if(isset($url[0]) && file_exists('app/controllers/' . ucwords($url[0]) . '.php')) {
            // Eğer varsa, mevcut controller'ı ayarla
            $this->currentController = ucwords($url[0]);
            // İlk indisi boşalt
            unset($url[0]);
        }

        // Controller'ı dahil et
        require_once 'app/controllers/' . $this->currentController . '.php';

        // Controller örneği oluştur
        $this->currentController = new $this->currentController;

        // Method kontrolü
        if(isset($url[1])) {
            // Method controller'da var mı diye kontrol et
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // İkinci indisi boşalt
                unset($url[1]);
            } else {
                // Method bulunamadı - 404 göster
                $this->show404();
                return;
            }
        }

        // Parametreleri al
        $this->params = $url ? array_values($url) : [];

        // Controller metodu çağır ve parametreleri geçir
        try {
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } catch (ArgumentCountError $e) {
            // Parametre hatası - 404 göster
            error_log('Parametre hatası: ' . $e->getMessage());
            $this->show404();
        } catch (Error $e) {
            // Hata durumunda yönlendirme veya hata mesajı
            error_log('Uygulama hatası: ' . $e->getMessage());
            include 'app/views/errors/500.php';
        } catch (Exception $e) {
            // Hata durumunda yönlendirme veya hata mesajı
            error_log('Uygulama istisnası: ' . $e->getMessage());
            include 'app/views/errors/500.php';
        }
    }

    /**
     * 404 hata sayfasını göster
     */
    private function show404() {
        header("HTTP/1.0 404 Not Found");
        include 'app/views/errors/404.php';
        exit;
    }

    /**
     * URL'yi parçalar ve dizi olarak döndürür
     * @return array
     */
    public function getUrl() {
        if(isset($_GET['url'])) {
            // URL'yi temizle, parçala ve dizi olarak döndür
            $url = rtrim($_GET['url'], '/');
            // URL'deki tehlikeli karakterleri temizle
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        
        return [];
    }
} 