<?php
class Entries extends Controller {
    private $vehicleModel;
    private $driverModel;
    private $fuelModel;
    private $maintenanceModel;
    private $assignmentModel;

    public function __construct() {
        // Oturum kontrolü
        if(!isLoggedIn()) {
            redirect('users/login');
        }

        // Modelleri yükle
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
        $this->fuelModel = $this->model('FuelModel');
        $this->maintenanceModel = $this->model('MaintenanceModel');
        $this->assignmentModel = $this->model('Assignment');
    }

    // Ana sayfa - Veri giriş ekranlarına bağlantılar
    public function index() {
        $data = [
            'title' => 'Veri Girişi'
        ];

        $this->view('entries/index', $data);
    }

    // Yeni araç ekleme kısayolu
    public function vehicle() {
        redirect('vehicles/add');
    }

    // Yeni sürücü ekleme kısayolu
    public function driver() {
        redirect('drivers/add');
    }

    // Yeni görevlendirme ekleme kısayolu
    public function assignment() {
        redirect('assignments/add');
    }

    // Yeni yakıt kaydı ekleme kısayolu
    public function fuel() {
        redirect('fuel/add');
    }

    // Yeni bakım kaydı ekleme kısayolu
    public function maintenance() {
        redirect('maintenance/add');
    }

    // Hızlı araç ekleme
    public function quickVehicle() {
        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'plate_number' => trim($_POST['plate_number']),
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'year' => trim($_POST['year']),
                'vehicle_type' => trim($_POST['vehicle_type']),
                'status' => trim($_POST['status']),
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => ''
            ];

            // Validasyon
            if(empty($data['plate_number'])) {
                $data['plate_number_err'] = 'Lütfen plaka numarası giriniz';
            } elseif($this->vehicleModel->findVehicleByPlate($data['plate_number'])) {
                $data['plate_number_err'] = 'Bu plaka numarası zaten kullanılıyor';
            }

            if(empty($data['brand'])) {
                $data['brand_err'] = 'Lütfen marka giriniz';
            }

            if(empty($data['model'])) {
                $data['model_err'] = 'Lütfen model giriniz';
            }

            if(empty($data['year'])) {
                $data['year_err'] = 'Lütfen üretim yılı giriniz';
            } elseif(!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > date('Y') + 1) {
                $data['year_err'] = 'Lütfen geçerli bir üretim yılı giriniz';
            }

            if(empty($data['vehicle_type'])) {
                $data['vehicle_type_err'] = 'Lütfen araç tipi seçiniz';
            }

            if(empty($data['status'])) {
                $data['status_err'] = 'Lütfen durum seçiniz';
            }

            // Hatalar boş ise
            if(empty($data['plate_number_err']) && empty($data['brand_err']) && empty($data['model_err']) && 
               empty($data['year_err']) && empty($data['vehicle_type_err']) && empty($data['status_err'])) {
                
                // Aracı kaydet
                if($this->vehicleModel->addVehicle($data)) {
                    flash('vehicle_message', 'Araç başarıyla eklendi');
                    redirect('entries');
                } else {
                    flash('vehicle_message', 'Araç eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('entries/quick_vehicle', $data);
                }
            } else {
                // Formu hatalarla birlikte göster
                $this->view('entries/quick_vehicle', $data);
            }
        } else {
            // Form verilerini hazırla
            $data = [
                'plate_number' => '',
                'brand' => '',
                'model' => '',
                'year' => date('Y'),
                'vehicle_type' => '',
                'status' => 'Aktif',
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => ''
            ];

            $this->view('entries/quick_vehicle', $data);
        }
    }

    // Hızlı sürücü ekleme
    public function quickDriver() {
        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'name' => trim($_POST['name']),
                'surname' => trim($_POST['surname']),
                'identity_number' => trim($_POST['identity_number']),
                'license_number' => trim($_POST['license_number']),
                'license_type' => trim($_POST['license_type']),
                'phone' => trim($_POST['phone']),
                'status' => trim($_POST['status']),
                'name_err' => '',
                'surname_err' => '',
                'identity_number_err' => '',
                'license_number_err' => '',
                'license_type_err' => '',
                'phone_err' => '',
                'status_err' => ''
            ];

            // Validasyon
            if(empty($data['name'])) {
                $data['name_err'] = 'Lütfen isim giriniz';
            }

            if(empty($data['surname'])) {
                $data['surname_err'] = 'Lütfen soyisim giriniz';
            }

            if(empty($data['identity_number'])) {
                $data['identity_number_err'] = 'Lütfen TC kimlik numarası giriniz';
            } elseif($this->driverModel->findDriverByIdentity($data['identity_number'])) {
                $data['identity_number_err'] = 'Bu TC kimlik numarası zaten kullanılıyor';
            }

            if(empty($data['license_number'])) {
                $data['license_number_err'] = 'Lütfen ehliyet numarası giriniz';
            } elseif($this->driverModel->findDriverByLicense($data['license_number'])) {
                $data['license_number_err'] = 'Bu ehliyet numarası zaten kullanılıyor';
            }

            if(empty($data['license_type'])) {
                $data['license_type_err'] = 'Lütfen ehliyet tipi seçiniz';
            }

            if(empty($data['phone'])) {
                $data['phone_err'] = 'Lütfen telefon numarası giriniz';
            }

            if(empty($data['status'])) {
                $data['status_err'] = 'Lütfen durum seçiniz';
            }

            // Hatalar boş ise
            if(empty($data['name_err']) && empty($data['surname_err']) && empty($data['identity_number_err']) && 
               empty($data['license_number_err']) && empty($data['license_type_err']) && empty($data['phone_err']) && 
               empty($data['status_err'])) {
                
                // Sürücüyü kaydet
                if($this->driverModel->addDriver($data)) {
                    flash('driver_message', 'Sürücü başarıyla eklendi');
                    redirect('entries');
                } else {
                    flash('driver_message', 'Sürücü eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('entries/quick_driver', $data);
                }
            } else {
                // Formu hatalarla birlikte göster
                $this->view('entries/quick_driver', $data);
            }
        } else {
            // Form verilerini hazırla
            $data = [
                'name' => '',
                'surname' => '',
                'identity_number' => '',
                'license_number' => '',
                'license_type' => '',
                'phone' => '',
                'status' => 'Aktif',
                'name_err' => '',
                'surname_err' => '',
                'identity_number_err' => '',
                'license_number_err' => '',
                'license_type_err' => '',
                'phone_err' => '',
                'status_err' => ''
            ];

            $this->view('entries/quick_driver', $data);
        }
    }

    // Hızlı yakıt kaydı
    public function quickFuel() {
        // Aktif araçları ve şoförleri getir
        $vehicles = $this->vehicleModel->getActiveVehicles();
        $drivers = $this->driverModel->getActiveDrivers();

        // POST isteği kontrol
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'cost' => trim($_POST['cost']),
                'km_reading' => trim($_POST['km_reading']),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'date_err' => ''
            ];

            // Validasyon
            if(empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçiniz';
            }

            if(empty($data['fuel_type'])) {
                $data['fuel_type_err'] = 'Lütfen yakıt tipi seçiniz';
            }

            if(empty($data['amount'])) {
                $data['amount_err'] = 'Lütfen yakıt miktarı giriniz';
            } elseif(!is_numeric(str_replace(',', '.', $data['amount']))) {
                $data['amount_err'] = 'Lütfen geçerli bir miktar giriniz';
            }

            if(empty($data['cost'])) {
                $data['cost_err'] = 'Lütfen maliyet giriniz';
            } elseif(!is_numeric(str_replace(',', '.', $data['cost']))) {
                $data['cost_err'] = 'Lütfen geçerli bir maliyet giriniz';
            }

            if(empty($data['km_reading'])) {
                $data['km_reading_err'] = 'Lütfen km bilgisi giriniz';
            } elseif(!is_numeric($data['km_reading'])) {
                $data['km_reading_err'] = 'Lütfen geçerli bir km bilgisi giriniz';
            }

            if(empty($data['date'])) {
                $data['date_err'] = 'Lütfen tarih giriniz';
            }

            // Hatalar boş ise
            if(empty($data['vehicle_id_err']) && empty($data['fuel_type_err']) && empty($data['amount_err']) && 
               empty($data['cost_err']) && empty($data['km_reading_err']) && empty($data['date_err'])) {
                
                // Decimal ayarlamaları
                $data['amount'] = str_replace(',', '.', $data['amount']);
                $data['cost'] = str_replace(',', '.', $data['cost']);
                
                // Kullanıcı ID'sini ekle
                $data['created_by'] = $_SESSION['user_id'];
                
                // Yakıt kaydını kaydet
                if($this->fuelModel->addFuelRecord($data)) {
                    flash('fuel_message', 'Yakıt kaydı başarıyla eklendi');
                    redirect('entries');
                } else {
                    flash('fuel_message', 'Yakıt kaydı eklenirken bir hata oluştu', 'alert alert-danger');
                    $this->view('entries/quick_fuel', $data);
                }
            } else {
                // Formu hatalarla birlikte göster
                $this->view('entries/quick_fuel', $data);
            }
        } else {
            // Form verilerini hazırla
            $data = [
                'vehicle_id' => '',
                'driver_id' => '',
                'fuel_type' => '',
                'amount' => '',
                'cost' => '',
                'km_reading' => '',
                'date' => date('Y-m-d'),
                'notes' => '',
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'vehicle_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'date_err' => ''
            ];

            $this->view('entries/quick_fuel', $data);
        }
    }
} 