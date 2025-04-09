<?php
class Certificates extends Controller {
    private $driverModel;
    private $certificateModel;
    private $certificateTypeModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->driverModel = $this->model('Driver');
        $this->certificateModel = $this->model('DriverCertificate');
        $this->certificateTypeModel = $this->model('CertificateType');
    }
    
    // Ana sayfa - süresi yakında dolacak ve süresi geçmiş belgeleri listele
    public function index($driver_id = null) {
        // Eğer bir sürücü ID'si belirtilmişse o sürücüye ait belgeleri göster
        if ($driver_id !== null) {
            $this->showDriverCertificates($driver_id);
            return;
        }
        
        // Süresi geçmiş belgeleri getir
        $expiredCertificates = $this->certificateModel->getExpiredCertificates();
        
        // Yakında süresi dolacak belgeleri getir
        $soonExpiringCertificates = $this->certificateModel->getSoonExpiringCertificates();
        
        // Sürücülerin belge istatistiklerini getir
        $driverCertificateStats = $this->certificateModel->getDriverCertificateStats();
        
        $data = [
            'title' => 'Sertifikalar',
            'expiredCertificates' => $expiredCertificates,
            'soonExpiringCertificates' => $soonExpiringCertificates,
            'driverCertificateStats' => $driverCertificateStats
        ];
        
        $this->view('certificates/dashboard', $data);
    }
    
    // Sürücünün belgelerini listele
    private function showDriverCertificates($driver_id) {
        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($driver_id);
        
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Sürücünün belgelerini getir
        $certificates = $this->certificateModel->getCertificatesByDriver($driver_id);
        
        $data = [
            'driver' => $driver,
            'certificates' => $certificates,
            'title' => $driver->name . ' ' . $driver->surname . ' - Sertifikalar'
        ];
        
        $this->view('certificates/index', $data);
    }
    
    // Yeni belge ekleme sayfası
    public function add($driver_id = null) {
        // Sürücü ID kontrolü
        if ($driver_id === null) {
            // Eğer sürücü ID'si belirtilmemişse, sürücü listesine yönlendir
            flash('info', 'Lütfen önce sertifika eklemek istediğiniz sürücüyü seçin', 'alert alert-info');
            redirect('drivers');
        }
        
        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($driver_id);
        
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Sertifika türlerini getir
        $certificateTypes = $this->certificateTypeModel->getCertificateTypes();
        
        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Form verilerini al
            $data = [
                'driver_id' => $driver_id,
                'certificate_type_id' => trim($_POST['certificate_type_id']),
                'certificate_number' => trim($_POST['certificate_number']),
                'issue_date' => trim($_POST['issue_date']),
                'expiry_date' => trim($_POST['expiry_date']),
                'issuing_authority' => trim($_POST['issuing_authority']),
                'notes' => trim($_POST['notes']),
                'driver' => $driver,
                'certificateTypes' => $certificateTypes,
                'certificate_type_id_err' => '',
                'certificate_number_err' => ''
            ];
            
            // Doğrulama
            if (empty($data['certificate_type_id'])) {
                $data['certificate_type_id_err'] = 'Lütfen sertifika türünü seçiniz';
            }
            
            if (empty($data['certificate_number'])) {
                $data['certificate_number_err'] = 'Lütfen sertifika numarasını giriniz';
            } else {
                // Aynı sertifika var mı kontrolü
                if ($this->certificateModel->certificateExists($driver_id, $data['certificate_type_id'], $data['certificate_number'])) {
                    $data['certificate_number_err'] = 'Bu sertifika numarası zaten kayıtlı';
                }
            }
            
            // Hata yoksa işleme devam et
            if (empty($data['certificate_type_id_err']) && empty($data['certificate_number_err'])) {
                if ($this->certificateModel->addCertificate($data)) {
                    flash('success', 'Sertifika başarıyla eklendi');
                    redirect('certificates/index/' . $driver_id);
                } else {
                    flash('error', 'Sertifika eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('certificates/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('certificates/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'driver_id' => $driver_id,
                'certificate_type_id' => '',
                'certificate_number' => '',
                'issue_date' => '',
                'expiry_date' => '',
                'issuing_authority' => '',
                'notes' => '',
                'driver' => $driver,
                'certificateTypes' => $certificateTypes,
                'certificate_type_id_err' => '',
                'certificate_number_err' => ''
            ];
            
            $this->view('certificates/add', $data);
        }
    }
    
    // Belge düzenleme sayfası
    public function edit($driver_id = null, $certificate_id = null) {
        // Parametre kontrolü
        if ($driver_id === null || $certificate_id === null) {
            flash('error', 'Geçersiz parametre', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($driver_id);
        
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Belge bilgilerini getir
        $certificate = $this->certificateModel->getCertificateById($certificate_id);
        
        if (!$certificate) {
            flash('error', 'Belge bulunamadı', 'alert alert-danger');
            redirect('certificates/index/' . $driver_id);
        }
        
        // Sertifika türlerini getir
        $certificateTypes = $this->certificateTypeModel->getCertificateTypes();
        
        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Form verilerini al
            $data = [
                'id' => $certificate_id,
                'driver_id' => $driver_id,
                'certificate_type_id' => trim($_POST['certificate_type_id']),
                'certificate_number' => trim($_POST['certificate_number']),
                'issue_date' => trim($_POST['issue_date']),
                'expiry_date' => trim($_POST['expiry_date']),
                'issuing_authority' => trim($_POST['issuing_authority']),
                'notes' => trim($_POST['notes']),
                'driver' => $driver,
                'certificate' => $certificate,
                'certificateTypes' => $certificateTypes,
                'certificate_type_id_err' => '',
                'certificate_number_err' => ''
            ];
            
            // Doğrulama
            if (empty($data['certificate_type_id'])) {
                $data['certificate_type_id_err'] = 'Lütfen sertifika türünü seçiniz';
            }
            
            if (empty($data['certificate_number'])) {
                $data['certificate_number_err'] = 'Lütfen sertifika numarasını giriniz';
            }
            
            // Hata yoksa işleme devam et
            if (empty($data['certificate_type_id_err']) && empty($data['certificate_number_err'])) {
                if ($this->certificateModel->updateCertificate($data)) {
                    flash('success', 'Sertifika başarıyla güncellendi');
                    redirect('certificates/index/' . $driver_id);
                } else {
                    flash('error', 'Sertifika güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('certificates/edit', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('certificates/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $certificate_id,
                'driver_id' => $driver_id,
                'certificate_type_id' => $certificate->certificate_type_id,
                'certificate_number' => $certificate->certificate_number,
                'issue_date' => $certificate->issue_date,
                'expiry_date' => $certificate->expiry_date,
                'issuing_authority' => $certificate->issuing_authority,
                'notes' => $certificate->notes,
                'driver' => $driver,
                'certificate' => $certificate,
                'certificateTypes' => $certificateTypes,
                'certificate_type_id_err' => '',
                'certificate_number_err' => ''
            ];
            
            $this->view('certificates/edit', $data);
        }
    }
    
    // Belge silme işlemi
    public function delete($driver_id = null, $certificate_id = null) {
        // Parametre kontrolü
        if ($driver_id === null || $certificate_id === null) {
            flash('error', 'Geçersiz parametre', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Yalnızca POST istekleri kabul edilir
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('certificates/index/' . $driver_id);
        }
        
        // Belge bilgilerini getir
        $certificate = $this->certificateModel->getCertificateById($certificate_id);
        
        if (!$certificate) {
            flash('error', 'Belge bulunamadı', 'alert alert-danger');
            redirect('certificates/index/' . $driver_id);
        }
        
        // Belge silme işlemi
        if ($this->certificateModel->deleteCertificate($certificate_id)) {
            flash('success', 'Belge başarıyla silindi');
        } else {
            flash('error', 'Belge silinirken bir hata oluştu', 'alert alert-danger');
        }
        
        redirect('certificates/index/' . $driver_id);
    }
} 