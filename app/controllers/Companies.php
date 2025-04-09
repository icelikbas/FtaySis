<?php
class Companies extends Controller {
    private $companyModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model yükle
        $this->companyModel = $this->model('Company');
    }

    // Şirketler ana sayfası - tüm şirketleri listeler
    public function index() {
        // Tüm şirketleri getir
        $companies = $this->companyModel->getAllCompanies();

        $data = [
            'companies' => $companies
        ];

        $this->view('companies/index', $data);
    }

    // Şirket detay sayfası
    public function show($id) {
        // Şirket bilgilerini getir
        $company = $this->companyModel->getCompanyById($id);

        // Şirket bulunamadıysa ana sayfaya yönlendir
        if (!$company) {
            flash('company_message', 'Şirket bulunamadı', 'alert alert-danger');
            redirect('companies');
        }

        // Şirkete ait sürücüleri getir
        $driverModel = $this->model('Driver');
        $companyDrivers = $driverModel->getDriversByCompany($id);

        // Şirkete ait araçları getir
        $vehicleModel = $this->model('Vehicle');
        $companyVehicles = $vehicleModel->getVehiclesByCompany($id);

        $data = [
            'company' => $company,
            'company_drivers' => $companyDrivers,
            'company_vehicles' => $companyVehicles
        ];

        $this->view('companies/show', $data);
    }

    // Yeni şirket ekleme sayfası
    public function add() {
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Logo yükleme işlemi
            $logo_url = '';
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logo_url = $this->uploadLogo($_FILES['logo']);
                if (is_string($logo_url) && substr($logo_url, 0, 5) === 'HATA:') {
                    $logoError = substr($logo_url, 5);
                    $logo_url = '';
                }
            }

            // Form verilerini al
            $data = [
                'company_name' => trim($_POST['company_name']),
                'tax_office' => trim($_POST['tax_office']),
                'tax_number' => trim($_POST['tax_number']),
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email']),
                'status' => trim($_POST['status']),
                'logo_url' => $logo_url,
                'company_name_err' => '',
                'tax_number_err' => '',
                'status_err' => ''
            ];

            // Şirket adı doğrulama
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Lütfen şirket adını giriniz';
            }

            // Vergi numarası doğrulama
            if (empty($data['tax_number'])) {
                $data['tax_number_err'] = 'Lütfen vergi numarasını giriniz';
            } elseif (strlen($data['tax_number']) != 10 || !is_numeric($data['tax_number'])) {
                $data['tax_number_err'] = 'Vergi numarası 10 haneli rakam olmalıdır';
            } elseif ($this->companyModel->findCompanyByTaxNumber($data['tax_number'])) {
                $data['tax_number_err'] = 'Bu vergi numarası ile kayıtlı bir şirket bulunmaktadır';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Logo hatası varsa ekle
            if (isset($logoError)) {
                $data['logo_err'] = $logoError;
            }

            // Hata yoksa işleme devam et
            if (empty($data['company_name_err']) && 
                empty($data['tax_number_err']) && 
                empty($data['status_err']) &&
                empty($data['logo_err'])) {
                
                // Veritabanına kaydet
                if ($this->companyModel->addCompany($data)) {
                    flash('company_message', 'Şirket başarıyla eklendi');
                    redirect('companies');
                } else {
                    flash('company_message', 'Şirket eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('companies/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('companies/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'company_name' => '',
                'tax_office' => '',
                'tax_number' => '',
                'address' => '',
                'phone' => '',
                'email' => '',
                'status' => 'Aktif',
                'logo_url' => '',
                'company_name_err' => '',
                'tax_number_err' => '',
                'status_err' => ''
            ];

            $this->view('companies/add', $data);
        }
    }

    // Şirket düzenleme sayfası
    public function edit($id) {
        // Şirket bilgilerini getir
        $company = $this->companyModel->getCompanyById($id);

        // Şirket bulunamadıysa ana sayfaya yönlendir
        if (!$company) {
            flash('company_message', 'Şirket bulunamadı', 'alert alert-danger');
            redirect('companies');
        }

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Logo yükleme işlemi
            $logo_url = $company->logo_url;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $new_logo = $this->uploadLogo($_FILES['logo']);
                if (is_string($new_logo) && substr($new_logo, 0, 5) === 'HATA:') {
                    $logoError = substr($new_logo, 5);
                } else {
                    $logo_url = $new_logo;
                    
                    // Eski logoyu sil (eğer varsa)
                    if (!empty($company->logo_url) && file_exists($company->logo_url)) {
                        unlink($company->logo_url);
                    }
                }
            }

            // Form verilerini al
            $data = [
                'id' => $id,
                'company_name' => trim($_POST['company_name']),
                'tax_office' => trim($_POST['tax_office']),
                'tax_number' => trim($_POST['tax_number']),
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email']),
                'status' => trim($_POST['status']),
                'logo_url' => $logo_url,
                'company_name_err' => '',
                'tax_number_err' => '',
                'status_err' => ''
            ];

            // Şirket adı doğrulama
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Lütfen şirket adını giriniz';
            }

            // Vergi numarası doğrulama
            if (empty($data['tax_number'])) {
                $data['tax_number_err'] = 'Lütfen vergi numarasını giriniz';
            } elseif (strlen($data['tax_number']) != 10 || !is_numeric($data['tax_number'])) {
                $data['tax_number_err'] = 'Vergi numarası 10 haneli rakam olmalıdır';
            } else {
                // Aynı vergi numarasına sahip başka şirket var mı kontrol et
                $existingCompany = $this->companyModel->findCompanyByTaxNumber($data['tax_number']);
                if ($existingCompany && $existingCompany->id != $id) {
                    $data['tax_number_err'] = 'Bu vergi numarası ile kayıtlı başka bir şirket bulunmaktadır';
                }
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Logo hatası varsa ekle
            if (isset($logoError)) {
                $data['logo_err'] = $logoError;
            }

            // Hata yoksa işleme devam et
            if (empty($data['company_name_err']) && 
                empty($data['tax_number_err']) && 
                empty($data['status_err']) &&
                empty($data['logo_err'])) {
                
                // Veritabanında güncelle
                if ($this->companyModel->updateCompany($data)) {
                    flash('company_message', 'Şirket başarıyla güncellendi');
                    redirect('companies');
                } else {
                    flash('company_message', 'Şirket güncellenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('companies/edit', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $this->view('companies/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $id,
                'company_name' => $company->company_name,
                'tax_office' => $company->tax_office,
                'tax_number' => $company->tax_number,
                'address' => $company->address,
                'phone' => $company->phone,
                'email' => $company->email,
                'status' => $company->status,
                'logo_url' => $company->logo_url,
                'company_name_err' => '',
                'tax_number_err' => '',
                'status_err' => ''
            ];

            $this->view('companies/edit', $data);
        }
    }

    // Şirket silme işlemi
    public function delete($id) {
        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Şirketi getir
            $company = $this->companyModel->getCompanyById($id);
            
            if (!$company) {
                flash('company_message', 'Şirket bulunamadı', 'alert alert-danger');
                redirect('companies');
            }

            // Şirketin bağlı kayıtları var mı kontrol et
            if ($this->companyModel->hasRelatedRecords($id)) {
                flash('company_message', 'Bu şirkete bağlı kayıtlar olduğu için silinemez', 'alert alert-danger');
                redirect('companies');
            }

            // Şirketi sil
            if ($this->companyModel->deleteCompany($id)) {
                // Şirket logosu varsa sil
                if (!empty($company->logo_url) && file_exists($company->logo_url)) {
                    unlink($company->logo_url);
                }
                
                flash('company_message', 'Şirket başarıyla silindi');
                redirect('companies');
            } else {
                flash('company_message', 'Şirket silinirken bir hata oluştu', 'alert alert-danger');
                redirect('companies');
            }
        } else {
            redirect('companies');
        }
    }

    // Logo yükleme yardımcı fonksiyonu
    private function uploadLogo($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return 'HATA:Geçersiz dosya türü. Sadece JPEG, PNG ve GIF dosyaları yüklenebilir.';
        }

        if ($file['size'] > $maxSize) {
            return 'HATA:Dosya boyutu çok büyük. Maksimum 5MB yüklenebilir.';
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetDir = 'uploads/company_logos/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        } else {
            return 'HATA:Logo yüklenirken bir hata oluştu.';
        }
    }
} 