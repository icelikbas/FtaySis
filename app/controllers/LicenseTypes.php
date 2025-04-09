<?php
class LicenseTypes extends Controller {
    private $licenseTypeModel;
    private $driverModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->licenseTypeModel = $this->model('LicenseType');
        $this->driverModel = $this->model('Driver');
    }

    // Ehliyet tipleri listesini göster
    public function index() {
        // Ehliyet tiplerini getir
        $licenseTypes = $this->licenseTypeModel->getLicenseTypes();
        
        // Her ehliyet tipi için sürücü sayısını ekle
        foreach ($licenseTypes as $licenseType) {
            $drivers = $this->licenseTypeModel->getDriversByLicenseType($licenseType->id);
            $licenseType->driver_count = count($drivers);
        }

        $data = [
            'licenseTypes' => $licenseTypes,
            'title' => 'Ehliyet Sınıfları'
        ];

        $this->view('licensetypes/index', $data);
    }

    // Yeni ehliyet tipi ekleme sayfası
    public function add() {
        // Sadece admin kullanıcılar ehliyet tipi ekleyebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('licensetypes');
        }

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'code' => strtoupper(trim($_POST['code'])),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'code_err' => '',
                'name_err' => ''
            ];

            // Kod doğrulama
            if (empty($data['code'])) {
                $data['code_err'] = 'Lütfen ehliyet kod bilgisini giriniz';
            } elseif ($this->licenseTypeModel->getLicenseTypeByCode($data['code'])) {
                $data['code_err'] = 'Bu ehliyet kodu zaten kayıtlı';
            }

            // İsim doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen ehliyet adını giriniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['code_err']) && empty($data['name_err'])) {
                // Veritabanına kaydet
                if ($this->licenseTypeModel->addLicenseType($data)) {
                    flash('success', 'Ehliyet tipi başarıyla eklendi');
                    redirect('licensetypes');
                } else {
                    flash('error', 'Ehliyet tipi eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('licensetypes/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('licensetypes/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'code' => '',
                'name' => '',
                'description' => '',
                'code_err' => '',
                'name_err' => ''
            ];

            $this->view('licensetypes/add', $data);
        }
    }

    // Ehliyet tipi düzenleme sayfası
    public function edit($id) {
        // Sadece admin kullanıcılar ehliyet tipi düzenleyebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('licensetypes');
        }

        // Ehliyet tipi bilgisini al
        $licenseType = $this->licenseTypeModel->getLicenseTypeById($id);

        // Ehliyet tipi bulunamadıysa ana sayfaya yönlendir
        if (!$licenseType) {
            flash('error', 'Ehliyet tipi bulunamadı', 'alert alert-danger');
            redirect('licensetypes');
        }

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'code' => strtoupper(trim($_POST['code'])),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'code_err' => '',
                'name_err' => ''
            ];

            // Kod doğrulama
            if (empty($data['code'])) {
                $data['code_err'] = 'Lütfen ehliyet kod bilgisini giriniz';
            } else {
                // Eğer kod değiştiyse ve başka bir tipe aitse hata ver
                $existingLicenseType = $this->licenseTypeModel->getLicenseTypeByCode($data['code']);
                if ($existingLicenseType && $existingLicenseType->id != $id) {
                    $data['code_err'] = 'Bu ehliyet kodu zaten kayıtlı';
                }
            }

            // İsim doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen ehliyet adını giriniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['code_err']) && empty($data['name_err'])) {
                // Veritabanında güncelle
                if ($this->licenseTypeModel->updateLicenseType($data)) {
                    flash('success', 'Ehliyet tipi başarıyla güncellendi');
                    redirect('licensetypes');
                } else {
                    flash('error', 'Ehliyet tipi güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('licensetypes/edit', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('licensetypes/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $licenseType->id,
                'code' => $licenseType->code,
                'name' => $licenseType->name,
                'description' => $licenseType->description,
                'code_err' => '',
                'name_err' => ''
            ];

            $this->view('licensetypes/edit', $data);
        }
    }

    // Ehliyet tipi silme işlemi
    public function delete($id) {
        // Sadece admin kullanıcılar ehliyet tipi silebilir
        if (!isAdmin()) {
            flash('error', 'Bu işlem için yetkiniz yok', 'alert alert-danger');
            redirect('licensetypes');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Bu ehliyet tipine sahip sürücüleri kontrol et
            $drivers = $this->licenseTypeModel->getDriversByLicenseType($id);
            
            if (!empty($drivers)) {
                flash('error', 'Bu ehliyet tipine sahip sürücüler var. Önce sürücülerin ehliyet bilgilerini güncelleyin.', 'alert alert-danger');
                redirect('licensetypes');
            }
            
            // Ehliyet tipini sil
            if ($this->licenseTypeModel->deleteLicenseType($id)) {
                flash('success', 'Ehliyet tipi başarıyla silindi');
            } else {
                flash('error', 'Ehliyet tipi silinirken bir hata oluştu', 'alert alert-danger');
            }
        }

        redirect('licensetypes');
    }

    // Belirli ehliyet tipine sahip sürücüleri listele
    public function drivers($id) {
        // Ehliyet tipi bilgisini al
        $licenseType = $this->licenseTypeModel->getLicenseTypeById($id);

        // Ehliyet tipi bulunamadıysa ana sayfaya yönlendir
        if (!$licenseType) {
            flash('error', 'Ehliyet tipi bulunamadı', 'alert alert-danger');
            redirect('licensetypes');
        }

        // Bu ehliyet tipine sahip sürücüleri getir
        $drivers = $this->licenseTypeModel->getDriversByLicenseType($id);
        
        // Tüm sürücülerin ehliyet bilgilerini getir
        $driverLicenses = [];
        foreach ($drivers as $driver) {
            $licenseInfo = $this->driverModel->getDriverLicenseByType($driver->id, $id);
            $driverLicenses[$driver->id] = $licenseInfo;
        }

        $data = [
            'licenseType' => $licenseType,
            'drivers' => $drivers,
            'driverLicenses' => $driverLicenses,
            'title' => $licenseType->name . ' Ehliyet Sahibi Sürücüler'
        ];

        $this->view('licensetypes/drivers', $data);
    }
} 