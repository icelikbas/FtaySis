<?php
class Licenses extends Controller {
    private $licenseModel;
    private $driverModel;
    private $licenseTypeModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->driverModel = $this->model('Driver');
        $this->licenseTypeModel = $this->model('LicenseType');
        $this->licenseModel = $this->model('License'); // Yeni oluşturduğumuz License modelini kullan
    }
    
    // Ana sayfa - sürücünün ehliyetlerini göster
    public function index($driver_id = null) {
        // Eğer sürücü ID belirtilmemişse, tüm ehliyet tiplerini listele
        if ($driver_id === null) {
            // Tüm ehliyet tiplerini getir
            $licenseTypes = $this->licenseTypeModel->getLicenseTypes();
            
            $data = [
                'licenseTypes' => $licenseTypes,
                'title' => 'Ehliyet Sınıfları'
            ];
            
            $this->view('licensetypes/index', $data);
            return;
        }
        
        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($driver_id);
        
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı', 'alert alert-danger');
            redirect('drivers');
        }
        
        // Sürücünün sahip olduğu ehliyet sınıflarını getir
        $driverLicenses = $this->licenseModel->getDriverLicenses($driver_id);
        
        // Sürücünün sahip olduğu ehliyet kodlarını belirle
        $hasLicenseTypes = [];
        foreach($this->licenseTypeModel->getLicenseTypes() as $type) {
            $hasLicenseTypes[$type->code] = false;
            foreach($driverLicenses as $license) {
                if(isset($license->license_type_id) && $license->license_type_id == $type->id) {
                    $hasLicenseTypes[$type->code] = true;
                    break;
                }
            }
        }
        
        $data = [
            'driver' => $driver,
            'licenseTypes' => $driverLicenses,
            'hasLicenseTypes' => $hasLicenseTypes
        ];
        
        $this->view('licenses/index', $data);
    }
    
    // Yeni ehliyet sınıfı ekle
    public function add($id = null) {
        // ID parametre kontrol
        if ($id === null) {
            flash('error', 'Geçersiz sürücü ID');
            redirect('drivers');
        }

        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($id);

        if (!$driver) {
            flash('error', 'Sürücü bulunamadı');
            redirect('drivers');
        }

        // Tüm ehliyet tiplerini getir
        $licenseTypes = $this->licenseTypeModel->getLicenseTypes();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'driver_id' => $id,
                'license_type_id' => trim($_POST['license_type_id']),
                'issue_date' => !empty($_POST['issue_date']) ? trim($_POST['issue_date']) : null,
                'expiry_date' => !empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : null,
                'notes' => trim($_POST['notes']),
                'driver' => $driver,
                'licenseTypes' => $licenseTypes,
                'license_type_id_err' => ''
            ];

            // Ehliyet sınıfı doğrulama
            if (empty($data['license_type_id'])) {
                $data['license_type_id_err'] = 'Lütfen ehliyet sınıfı seçin';
            } else {
                // Aynı ehliyet sınıfının daha önce eklenip eklenmediğini kontrol et
                $existingLicense = $this->licenseModel->getDriverLicenseByType($id, $data['license_type_id']);
                if ($existingLicense) {
                    $data['license_type_id_err'] = 'Bu ehliyet sınıfı zaten eklenmiş';
                }
            }

            // Hata yoksa işleme devam et
            if (empty($data['license_type_id_err'])) {
                if ($this->licenseModel->addLicense($data)) {
                    flash('success', 'Ehliyet sınıfı başarıyla eklendi');
                    
                    // Eğer eklenen ehliyet, sürücünün birincil ehliyet sınıfı ise
                    // Sürücünün license_expiry_date alanını güncelle
                    $licenseType = $this->licenseTypeModel->getLicenseTypeById($data['license_type_id']);
                    if ($licenseType && $licenseType->code == $driver->primary_license_type) {
                        $updateData = [
                            'id' => $id,
                            'license_expiry_date' => $data['expiry_date']
                        ];
                        $this->driverModel->updateDriverExpiryDate($updateData);
                    }
                    
                    redirect('licenses/index/' . $id);
                } else {
                    flash('error', 'Ehliyet sınıfı eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('licenses/add', $data);
                }
            } else {
                // Hataları göster
                $this->view('licenses/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'driver_id' => $id,
                'license_type_id' => '',
                'issue_date' => '',
                'expiry_date' => '',
                'notes' => '',
                'driver' => $driver,
                'licenseTypes' => $licenseTypes,
                'license_type_id_err' => ''
            ];

            $this->view('licenses/add', $data);
        }
    }
    
    // Ehliyet sınıfını düzenle
    public function edit($driver_id, $license_id) {
        // Sürücü bilgilerini getir
        $driver = $this->driverModel->getDriverById($driver_id);
        
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı');
            redirect('drivers');
        }
        
        // Ehliyet kaydını getir
        $license = $this->licenseModel->getLicenseById($license_id);
        
        if (!$license) {
            flash('error', 'Ehliyet kaydı bulunamadı');
            redirect('licenses/index/' . $driver_id);
        }
        
        // Tüm ehliyet tiplerini getir
        $licenseTypes = $this->licenseTypeModel->getLicenseTypes();
        
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Verileri hazırla
            $data = [
                'id' => $license_id,
                'driver_id' => $driver_id,
                'license_type_id' => trim($_POST['license_type_id']),
                'issue_date' => !empty($_POST['issue_date']) ? trim($_POST['issue_date']) : null,
                'expiry_date' => !empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : null,
                'notes' => trim($_POST['notes']),
                'driver' => $driver,
                'license' => $license,
                'licenseTypes' => $licenseTypes,
                'license_type_id_err' => ''
            ];
            
            // Ehliyet sınıfı değiştiyse, bu sınıfın zaten eklenmiş olup olmadığını kontrol et
            if ($data['license_type_id'] != $license->license_type_id) {
                $existingLicense = $this->licenseModel->getDriverLicenseByType($driver_id, $data['license_type_id']);
                if ($existingLicense) {
                    $data['license_type_id_err'] = 'Bu ehliyet sınıfı zaten eklenmiş';
                }
            }
            
            // Hata yoksa işleme devam et
            if (empty($data['license_type_id_err'])) {
                // Güncelleme işlemi yap
                if ($this->licenseModel->updateLicense($data)) {
                    flash('success', 'Ehliyet bilgileri başarıyla güncellendi');
                    
                    // Eğer güncellenen ehliyet, sürücünün birincil ehliyet sınıfı ise
                    // Sürücünün license_expiry_date alanını güncelle
                    $licenseType = $this->licenseTypeModel->getLicenseTypeById($data['license_type_id']);
                    if ($licenseType && $licenseType->code == $driver->primary_license_type) {
                        $updateData = [
                            'id' => $driver_id,
                            'license_expiry_date' => $data['expiry_date']
                        ];
                        $this->driverModel->updateDriverExpiryDate($updateData);
                    }
                    
                    redirect('licenses/index/' . $driver_id);
                } else {
                    flash('error', 'Ehliyet bilgileri güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('licenses/edit', $data);
                }
            } else {
                // Hataları göster
                $this->view('licenses/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $license_id,
                'driver_id' => $driver_id,
                'license_type_id' => $license->license_type_id,
                'issue_date' => $license->issue_date,
                'expiry_date' => $license->expiry_date,
                'notes' => $license->notes,
                'driver' => $driver,
                'license' => $license,
                'licenseTypes' => $licenseTypes,
                'license_type_id_err' => ''
            ];
            
            $this->view('licenses/edit', $data);
        }
    }
    
    // Ehliyet sınıfını sil
    public function delete($driver_id, $license_id) {
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sürücü kontrolü
            $driver = $this->driverModel->getDriverById($driver_id);
            
            if (!$driver) {
                flash('error', 'Sürücü bulunamadı');
                redirect('drivers');
            }
            
            // Ehliyet kaydını sil
            if ($this->licenseModel->deleteLicense($license_id)) {
                flash('success', 'Ehliyet kaydı başarıyla silindi');
            } else {
                flash('error', 'Ehliyet kaydı silinirken bir hata oluştu', 'alert alert-danger');
            }
            
            redirect('licenses/index/' . $driver_id);
        } else {
            redirect('licenses/index/' . $driver_id);
        }
    }
} 