<?php
// namespace filo_takip\app\controllers;

class Drivers extends Controller {
    private $driverModel;
    private $userModel;
    private $licenseTypeModel;
    private $companyModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıfını yükle
        $this->driverModel = $this->model('Driver');
        $this->userModel = $this->model('User');
        $this->licenseTypeModel = $this->model('LicenseType');
        $this->companyModel = $this->model('Company');
    }

    // Şoför listesini görüntüleme
    public function index() {
        // Tüm şoförleri getir
        $drivers = $this->driverModel->getDrivers();
        
        // Her sürücünün ehliyet sınıflarını al
        foreach ($drivers as $driver) {
            $driver->license_classes = $this->driverModel->getDriverLicenseTypesAsString($driver->id);
        }

        $data = [
            'drivers' => $drivers,
            'title' => 'Şoför Listesi'
        ];

        $this->view('drivers/index', $data);
    }

    // Şoför detaylarını görüntüleme
    public function show($id) {
        // Check for ID
        if (!isset($id) || $id <= 0) {
            flash('error', 'Geçersiz sürücü ID\'si');
            redirect('drivers');
        }

        // Get Driver
        $driver = $this->driverModel->getDriverById($id);

        // Check if driver exists
        if (!$driver) {
            flash('error', 'Sürücü bulunamadı');
            redirect('drivers');
        }

        // Sürücünün ehliyet geçerlilik tarihini güncelle
        $this->updateDriverLicenseExpiryDate($id);

        // Get active assignment
        $activeAssignment = $this->driverModel->getActiveAssignment($id);
        
        // Get driving history
        $drivingHistory = $this->driverModel->getDriverAssignments($id);
        
        // Get driver license types
        $driverLicenseTypes = $this->driverModel->getDriverLicenseTypes($id);
        
        // Get certificates
        $certificateModel = $this->model('DriverCertificate');
        $certificates = $certificateModel->getCertificatesByDriver($id);
        
        $data = [
            'driver' => $driver,
            'activeAssignment' => $activeAssignment,
            'drivingHistory' => $drivingHistory,
            'licenseTypes' => $driverLicenseTypes,
            'driverLicenseTypes' => $driverLicenseTypes,
            'certificates' => $certificates
        ];

        $this->view('drivers/show', $data);
    }

    // Sürücünün ehliyet geçerlilik tarihini güncelleme
    private function updateDriverLicenseExpiryDate($id) {
        // Sürücünün ehliyet bilgilerini getir
        $driverLicenses = $this->driverModel->getDriverLicenseTypes($id);
        
        // Eğer ehliyet bilgileri varsa
        if (!empty($driverLicenses)) {
            // Birincil ehliyet tipini al
            $primaryLicenseType = $this->driverModel->getPrimaryLicenseType($id);
            
            // Birincil ehliyet sınıfını bul
            $primaryLicense = null;
            foreach ($driverLicenses as $license) {
                if ($license->code == $primaryLicenseType) {
                    $primaryLicense = $license;
                    break;
                }
            }
            
            // Eğer birincil ehliyet bulunduysa
            if ($primaryLicense && !empty($primaryLicense->expiry_date) && $primaryLicense->expiry_date != '0000-00-00') {
                // Sürücü bilgilerini güncelle
                $updateData = [
                    'id' => $id,
                    'license_expiry_date' => $primaryLicense->expiry_date
                ];
                
                // Driver modelini kullanarak güncelleme yap
                $this->driverModel->updateDriverExpiryDate($updateData);
            }
        }
    }

    // Yeni şoför ekleme sayfası
    public function add() {
        // Ehliyet sınıflarını getir
        $licenseTypeModel = $this->model('LicenseType');
        $licenseTypes = $licenseTypeModel->getLicenseTypes();

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'name' => trim($_POST['name']),
                'surname' => trim($_POST['surname']),
                'identity_number' => trim($_POST['identity_number']),
                'license_number' => trim($_POST['license_number']),
                'primary_license_type' => trim($_POST['primary_license_type']),
                'license_issue_date' => isset($_POST['license_issue_date']) && !empty($_POST['license_issue_date']) ? trim($_POST['license_issue_date']) : null,
                'license_expiry_date' => isset($_POST['license_expiry_date']) && !empty($_POST['license_expiry_date']) ? trim($_POST['license_expiry_date']) : null,
                'phone' => trim($_POST['phone']),
                'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
                'address' => isset($_POST['address']) ? trim($_POST['address']) : '',
                'status' => trim($_POST['status']),
                'company_id' => !empty($_POST['company_id']) ? trim($_POST['company_id']) : null,
                'birth_date' => isset($_POST['birth_date']) && !empty($_POST['birth_date']) ? trim($_POST['birth_date']) : null,
                'notes' => isset($_POST['notes']) ? $_POST['notes'] : '',
                'license_classes' => isset($_POST['license_classes']) ? trim($_POST['license_classes']) : '',
                'available_license_types' => $licenseTypes,
                'name_err' => '',
                'surname_err' => '',
                'identity_number_err' => '',
                'license_number_err' => '',
                'primary_license_type_err' => '',
                'phone_err' => '',
                'email_err' => '',
                'status_err' => ''
            ];

            // Ad doğrulama
            if (empty($data['name'])) {
                $data['name_err'] = 'Lütfen adı giriniz';
            }

            // Soyad doğrulama
            if (empty($data['surname'])) {
                $data['surname_err'] = 'Lütfen soyadı giriniz';
            }

            // TC Kimlik numarası doğrulama
            if (empty($data['identity_number'])) {
                $data['identity_number_err'] = 'Lütfen TC Kimlik numarasını giriniz';
            } elseif (strlen($data['identity_number']) != 11) {
                $data['identity_number_err'] = 'TC Kimlik numarası 11 haneli olmalıdır';
            } elseif ($this->driverModel->findDriverByIdentityNumber($data['identity_number'])) {
                $data['identity_number_err'] = 'Bu TC Kimlik numarası zaten kayıtlı';
            }

            // Ehliyet numarası doğrulama
            if (empty($data['license_number'])) {
                $data['license_number_err'] = 'Lütfen ehliyet numarasını giriniz';
            } elseif ($this->driverModel->findDriverByLicenseNumber($data['license_number'])) {
                $data['license_number_err'] = 'Bu ehliyet numarası zaten kayıtlı';
            }

            // Ehliyet tipi doğrulama
            if (empty($data['primary_license_type'])) {
                $data['primary_license_type_err'] = 'Lütfen birincil ehliyet tipini giriniz';
            }

            // Telefon doğrulama
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Lütfen telefon numarasını giriniz';
            }

            // E-posta doğrulama (opsiyonel)
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Geçerli bir e-posta adresi giriniz';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['name_err']) && 
                empty($data['surname_err']) && 
                empty($data['identity_number_err']) && 
                empty($data['license_number_err']) &&
                empty($data['primary_license_type_err']) &&
                empty($data['phone_err']) &&
                empty($data['email_err']) &&
                empty($data['status_err'])) {
                
                // Veritabanına kaydet
                $driver_id = $this->driverModel->addDriver($data);
                
                if ($driver_id) {
                    // Birincil ehliyet sınıfını eklemek için lisans verileri hazırla
                    if (!empty($data['primary_license_type'])) {
                        $licenseType = $licenseTypeModel->getLicenseTypeByCode($data['primary_license_type']);
                        
                        if ($licenseType) {
                            $licenseData = [
                                'driver_id' => $driver_id,
                                'license_type_id' => $licenseType->id,
                                'issue_date' => $data['license_issue_date'],
                                'expiry_date' => $data['license_expiry_date'],
                                'notes' => 'Birincil ehliyet'
                            ];
                            
                            $this->driverModel->addDriverLicense($licenseData);
                        }
                    }
                    
                    flash('success', 'Şoför başarıyla eklendi. Şimdi ehliyet bilgilerini düzenleyebilirsiniz.');
                    redirect('licenses/index/' . $driver_id);
                } else {
                    flash('error', 'Şoför eklenirken bir hata oluştu', 'alert alert-danger');
                    
                    // Şirketleri tekrar yükle
                    $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                    $this->view('drivers/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                $this->view('drivers/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'name' => '',
                'surname' => '',
                'identity_number' => '',
                'license_number' => '',
                'primary_license_type' => '',
                'license_issue_date' => '',
                'license_expiry_date' => '',
                'phone' => '',
                'email' => '',
                'address' => '',
                'status' => 'Aktif',
                'company_id' => null,
                'birth_date' => '',
                'notes' => '',
                'license_classes' => '',
                'available_license_types' => $licenseTypes,
                'companies' => $this->companyModel->getActiveCompaniesForSelect(),
                'name_err' => '',
                'surname_err' => '',
                'identity_number_err' => '',
                'license_number_err' => '',
                'primary_license_type_err' => '',
                'phone_err' => '',
                'email_err' => '',
                'status_err' => ''
            ];

            $this->view('drivers/add', $data);
        }
    }

    // Şoför düzenleme sayfası
    public function edit($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form gönderilmiş, işle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Get license types
            $primaryLicenseType = $this->driverModel->getPrimaryLicenseType($id);
            $licenseClasses = $this->driverModel->getDriverLicenseTypesAsString($id);
            $companies = $this->companyModel->getActiveCompaniesForSelect();
            
            // Form verilerini hazırla
            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'surname' => trim($_POST['surname'] ?? ''),
                'identity_number' => trim($_POST['identity_number'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'company_id' => trim($_POST['company_id'] ?? ''),
                'birth_date' => !empty($_POST['birth_date']) ? trim($_POST['birth_date']) : null,
                'address' => trim($_POST['address'] ?? ''),
                'license_classes' => trim($_POST['license_classes'] ?? ''),
                'license_number' => trim($_POST['license_number'] ?? ''),
                'license_issue_date' => !empty($_POST['license_issue_date']) ? trim($_POST['license_issue_date']) : null,
                'notes' => $_POST['notes'] ?? '', // Özel karakterlerin korunması için trim kullanmayacağız
                'status' => trim($_POST['status'] ?? 'Aktif'),
                'primary_license_type' => trim($_POST['primary_license_type'] ?? $primaryLicenseType),
                'name_err' => '',
                'surname_err' => '',
                'identity_number_err' => '',
                'phone_err' => '',
                'email_err' => '',
                'company_id_err' => '',
                'license_classes_err' => '',
                'status_err' => '',
                'primary_license_type_err' => '',
                'companies' => $companies,
                'form_validated' => false,  // Başlangıçta false
            ];
            
            // Validate name
            if(empty($data['name'])) {
                $data['name_err'] = 'Ad alanı zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate surname
            if(empty($data['surname'])) {
                $data['surname_err'] = 'Soyad alanı zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate identity number
            if(empty($data['identity_number'])) {
                $data['identity_number_err'] = 'TC Kimlik No alanı zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            } elseif(strlen($data['identity_number']) != 11) {
                $data['identity_number_err'] = 'TC Kimlik No 11 haneli olmalıdır';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate phone
            if(empty($data['phone'])) {
                $data['phone_err'] = 'Telefon alanı zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate email
            if(!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Geçerli bir e-posta adresi giriniz';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate company
            if(empty($data['company_id'])) {
                $data['company_id_err'] = 'Şirket seçimi zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate license classes
            if(empty($data['license_classes'])) {
                $data['license_classes_err'] = 'Ehliyet sınıfı seçimi zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate primary license type
            if(empty($data['primary_license_type'])) {
                $data['primary_license_type_err'] = 'Birincil ehliyet sınıfı seçimi zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Validate status
            if(empty($data['status'])) {
                $data['status_err'] = 'Durum seçimi zorunludur';
                $data['form_validated'] = true;  // Hata var, validasyon yapıldı
            }
            
            // Kontrol et, hata yoksa veritabanında güncelle
            if(!$data['form_validated']) { // Hiç validasyon hatası yoksa
                
                // Validated, update driver 
                if($this->driverModel->updateDriver($data)) {
                    flash('driver_message', 'Sürücü bilgileri başarıyla güncellendi');
                    redirect('drivers');
                } else {
                    die('Bir hata oluştu');
                }
                
            } else {
                // Hataları göster
                $this->view('drivers/edit', $data);
            }
        } else {
            // Form submit edilmemiş, mevcut sürücü verilerini getir
            
            // Sürücü verisini çek
            $driver = $this->driverModel->getDriverById($id);
            
            if(!$driver) {
                flash('driver_message', 'Sürücü bulunamadı', 'alert alert-danger');
                redirect('drivers');
            }
            
            // Get license types
            $primaryLicenseType = $this->driverModel->getPrimaryLicenseType($id);
            $licenseClasses = $this->driverModel->getDriverLicenseTypesAsString($id);
            $companies = $this->companyModel->getActiveCompaniesForSelect();
            
            $data = [
                'id' => $id,
                'name' => $driver->name ?? '',
                'surname' => $driver->surname ?? '',
                'identity_number' => $driver->identity_number ?? '',
                'phone' => $driver->phone ?? '',
                'email' => $driver->email ?? '',
                'company_id' => $driver->company_id ?? '',
                'birth_date' => $driver->birth_date ?? '',
                'address' => $driver->address ?? '',
                'license_classes' => $licenseClasses ?? '',
                'license_number' => $driver->license_number ?? '',
                'license_issue_date' => $driver->license_issue_date ?? '',
                'notes' => $driver->notes ?? '',
                'status' => $driver->status ?? 'Aktif',
                'primary_license_type' => $primaryLicenseType,
                'companies' => $companies,
                'form_validated' => false,  // İlk yüklemede validasyon yapılmadı
            ];
            
            $this->view('drivers/edit', $data);
        }
    }

    /**
     * Sürücü silme işlemi
     *
     * @param int $id Silinecek sürücü ID'si
     * @return void
     */
    public function delete($id) {
        // Giriş kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Silme işlemi için POST metodu gerektiriyoruz
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sürücünün varlığını kontrol et
            $driver = $this->driverModel->getDriverById($id);
            
            if (!$driver) {
                flash('driver_message', 'Silmek istediğiniz sürücü bulunamadı', 'alert alert-danger');
                redirect('drivers');
            }
            
            // Silme işlemini gerçekleştir
            if ($this->driverModel->deleteDriver($id)) {
                flash('driver_message', 'Sürücü başarıyla silindi', 'alert alert-success');
                redirect('drivers');
            } else {
                flash('driver_message', 'Sürücü silinirken bir hata oluştu. Lütfen tekrar deneyin.', 'alert alert-danger');
                redirect('drivers');
            }
        } else {
            // GET isteği ile silme sayfasını görüntüleme
            redirect('drivers/show/' . $id);
        }
    }

    // Sürücünün ehliyet sınıflarını gösterme
    public function licenses($id) {
        // Sürücüyü doğrudan yeni lisans sayfasına yönlendir
        redirect('licenses/index/' . $id);
    }

    // Sürücüye ehliyet sınıfı ekleme
    public function addLicense($id = null) {
        // Doğrudan licenses controller'ın add metoduna yönlendir
        redirect('licenses/add/' . $id);
    }
    
    // Sürücünün ehliyet sınıfını düzenleme
    public function editLicense($driver_id, $license_id) {
        // Doğrudan licenses controller'ın edit metoduna yönlendir
        redirect('licenses/edit/' . $driver_id . '/' . $license_id);
    }
    
    // Sürücünün ehliyet sınıfını silme
    public function deleteLicense($driver_id, $license_id) {
        // Doğrudan licenses controller'ın delete metoduna yönlendir
        redirect('licenses/delete/' . $driver_id . '/' . $license_id);
    }
} 