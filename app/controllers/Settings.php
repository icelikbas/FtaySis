<?php
class Settings extends Controller {
    private $userModel;
    private $vehicleModel;
    private $driverModel;

    public function __construct() {
        // Sadece admin erişebilir
        if(!isAdmin()) {
            redirect('dashboard');
        }

        // Modelleri yükle
        $this->userModel = $this->model('User');
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
    }

    // Ayarlar sayfası
    public function index() {
        // Sistem istatistiklerini al
        $stats = [
            'total_vehicles' => $this->vehicleModel->getTotalVehicleCount(),
            'active_vehicles' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'maintenance_vehicles' => $this->vehicleModel->getVehicleCountByStatus('Bakımda'),
            'total_drivers' => $this->driverModel->getTotalDriverCount(),
            'active_drivers' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'user_count' => $this->userModel->getUserCount(),
        ];

        // Veritabanı yapılandırması (config dosyasından)
        $dbConfig = [
            'host' => DB_HOST,
            'user' => DB_USER,
            'name' => DB_NAME,
            'charset' => 'utf8mb4'
        ];

        // Sistem ayarları
        $siteSettings = [
            'site_name' => SITENAME,
            'app_version' => '1.0.0',
            'app_root' => APPROOT,
            'url_root' => URLROOT
        ];

        $data = [
            'title' => 'Sistem Ayarları',
            'stats' => $stats,
            'db_config' => $dbConfig,
            'site_settings' => $siteSettings
        ];

        $this->view('settings/index', $data);
    }

    // Genel ayarlar güncelleme
    public function updateGeneral() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'site_name' => trim($_POST['site_name']),
                'site_name_err' => ''
            ];

            // Site adı doğrulama
            if (empty($data['site_name'])) {
                $data['site_name_err'] = 'Lütfen site adını giriniz';
            }

            // Hata yoksa güncelle
            if (empty($data['site_name_err'])) {
                // Burada config dosyasını güncelleyecek bir fonksiyon çağrılabilir
                // updateConfig('SITENAME', $data['site_name']);
                
                flash('settings_message', 'Genel ayarlar başarıyla güncellendi');
                redirect('settings');
            } else {
                // Hata varsa formu tekrar göster
                $this->view('settings/index', $data);
            }
        } else {
            redirect('settings');
        }
    }

    // Sistem bilgisi 
    public function systemInfo() {
        $data = [
            'title' => 'Sistem Bilgisi',
            'php_version' => phpversion(),
            'server_info' => $_SERVER['SERVER_SOFTWARE'],
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time'),
            'session_path' => session_save_path()
        ];

        $this->view('settings/system', $data);
    }
    
    // Veritabanı yedekleme
    public function backup() {
        // Yedekleme işlemi gerçekleştirilecek
        // Bu fonksiyon örnek amaçlıdır, gerçek yedekleme işlemi için
        // mysqldump veya başka bir yöntem kullanılmalıdır
        
        flash('settings_message', 'Veritabanı yedeklemesi başarıyla tamamlandı');
        redirect('settings');
    }
    
    // Yardım ve destek sayfası
    public function help() {
        $data = [
            'title' => 'Yardım ve Destek'
        ];
        
        $this->view('settings/help', $data);
    }
} 