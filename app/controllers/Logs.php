<?php
class Logs extends Controller {
    private $logModel;
    private $userModel;

    public function __construct() {
        // Sadece admin erişebilir
        if(!isAdmin()) {
            redirect('dashboard');
        }

        // Modelleri yükle
        $this->logModel = $this->model('Log');
        $this->userModel = $this->model('User');
    }

    // Log listesi sayfası
    public function index() {
        // Tüm logları al
        $logs = $this->logModel->getLogs();
        
        $data = [
            'title' => 'Sistem Logları',
            'logs' => $logs
        ];

        $this->view('logs/index', $data);
    }

    // Log detay sayfası
    public function show($id) {
        // ID'ye göre log al
        $log = $this->logModel->getLogById($id);
        
        // Log bulunamadıysa
        if(!$log) {
            flash('log_message', 'Log kaydı bulunamadı', 'alert alert-danger');
            redirect('logs');
        }
        
        $data = [
            'title' => 'Log Detayı',
            'log' => $log
        ];

        $this->view('logs/show', $data);
    }

    // Log silme işlemi
    public function delete($id) {
        // POST isteği kontrol et
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // ID'ye göre log al
            $log = $this->logModel->getLogById($id);
            
            // Log bulunamadıysa
            if(!$log) {
                flash('log_message', 'Log kaydı bulunamadı', 'alert alert-danger');
                redirect('logs');
            }
            
            // Logu sil
            if($this->logModel->deleteLog($id)) {
                // Silme işlemini de logla
                Log::create('Log silindi', 'delete', 'ID: ' . $id);
                
                flash('log_message', 'Log kaydı başarıyla silindi', 'alert alert-success');
                redirect('logs');
            } else {
                flash('log_message', 'Log kaydı silinemedi', 'alert alert-danger');
                redirect('logs');
            }
        } else {
            redirect('logs');
        }
    }

    // Belirli bir türdeki logları listele
    public function type($type) {
        // Türe göre logları al
        $logs = $this->logModel->getLogsByType($type);
        
        $data = [
            'title' => ucfirst($type) . ' Logları',
            'logs' => $logs,
            'type' => $type
        ];

        $this->view('logs/index', $data);
    }

    // Belirli bir kullanıcının logları
    public function user($user_id) {
        // Kullanıcı var mı kontrol et
        $user = $this->userModel->getUserById($user_id);
        
        if(!$user) {
            flash('log_message', 'Kullanıcı bulunamadı', 'alert alert-danger');
            redirect('logs');
        }
        
        // Kullanıcının loglarını al
        $logs = $this->logModel->getLogsByUserId($user_id);
        
        $data = [
            'title' => $user->name . ' Kullanıcısı Logları',
            'logs' => $logs,
            'user' => $user
        ];

        $this->view('logs/index', $data);
    }

    // Eski logları temizleme
    public function clean() {
        // POST isteği kontrol et
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Güvenlik kontrolü için tekrar admin kontrolü
            if(!isAdmin()) {
                flash('log_message', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
                redirect('logs');
            }
            
            // Tarih kontrolü
            $daysToKeep = isset($_POST['days']) ? (int)$_POST['days'] : 30;
            
            if($daysToKeep < 1) {
                flash('log_message', 'Geçersiz gün sayısı', 'alert alert-danger');
                redirect('logs');
            }
            
            // Belirtilen günden daha eski logları silme
            $date = new DateTime();
            $date->modify('-' . $daysToKeep . ' days');
            $oldDate = $date->format('Y-m-d H:i:s');
            
            if($this->logModel->deleteOldLogs($oldDate)) {
                // Temizleme işlemini de logla
                Log::create('Eski loglar temizlendi', 'maintenance', $daysToKeep . ' günden eski loglar silindi');
                
                flash('log_message', $daysToKeep . ' günden eski loglar başarıyla temizlendi', 'alert alert-success');
                redirect('logs');
            } else {
                flash('log_message', 'Loglar temizlenemedi', 'alert alert-danger');
                redirect('logs');
            }
        } else {
            $data = [
                'title' => 'Log Temizleme'
            ];
            
            $this->view('logs/clean', $data);
        }
    }

    // Log istatistikleri
    public function stats() {
        // Toplam log sayısı
        $totalLogs = $this->logModel->getLogCount();
        
        // Log türlerine göre sayılar
        $loginLogs = $this->logModel->getLogCountByType('login');
        $logoutLogs = $this->logModel->getLogCountByType('logout');
        $createLogs = $this->logModel->getLogCountByType('create');
        $updateLogs = $this->logModel->getLogCountByType('update');
        $deleteLogs = $this->logModel->getLogCountByType('delete');
        $errorLogs = $this->logModel->getLogCountByType('error');
        
        // Kullanıcılar ve log sayıları
        $users = $this->userModel->getUsers();
        $userLogs = [];
        
        foreach($users as $user) {
            $userLogs[$user->id] = [
                'name' => $user->name,
                'count' => $this->logModel->getLogCountByUser($user->id)
            ];
        }
        
        $data = [
            'title' => 'Log İstatistikleri',
            'totalLogs' => $totalLogs,
            'loginLogs' => $loginLogs,
            'logoutLogs' => $logoutLogs,
            'createLogs' => $createLogs,
            'updateLogs' => $updateLogs,
            'deleteLogs' => $deleteLogs,
            'errorLogs' => $errorLogs,
            'userLogs' => $userLogs
        ];
        
        $this->view('logs/stats', $data);
    }
} 