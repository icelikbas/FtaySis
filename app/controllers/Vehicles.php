<?php
class Vehicles extends Controller {
    private $vehicleModel;
    private $userModel;
    private $companyModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıfını yükle
        $this->vehicleModel = $this->model('Vehicle');
        $this->userModel = $this->model('User');
        $this->companyModel = $this->model('Company');
    }

    // Araç listesini görüntüleme
    public function index() {
        // Tüm araçları getir
        $vehicles = $this->vehicleModel->getVehicles();
        
        // Görevlendirme modelini yükle
        $assignmentModel = $this->model('Assignment');
        
        // Her araç için aktif görevlendirme bilgisini ekle
        foreach ($vehicles as $vehicle) {
            $vehicle->activeAssignment = $assignmentModel->getActiveAssignmentByVehicle($vehicle->id);
        }

        // Araç tiplerini statik metottan al
        $vehicleTypesList = $this->vehicleModel::getStandardVehicleTypes();

        // Araç tipleri dağılımını hesapla
        $vehicleTypes = [];
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->vehicle_type)) {
                if (!isset($vehicleTypes[$vehicle->vehicle_type])) {
                    $vehicleTypes[$vehicle->vehicle_type] = 0;
                }
                $vehicleTypes[$vehicle->vehicle_type]++;
            }
        }

        $data = [
            'vehicles' => $vehicles,
            'title' => 'Araç Listesi',
            'vehicleTypesList' => $vehicleTypesList
        ];

        $this->view('vehicles/index', $data);
    }

    // Araç detayını gösterme
    public function show($id) {
        // Araç bilgilerini getir
        $vehicle = $this->vehicleModel->getVehicleById($id);

        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('vehicles');
        }

        // Yakıt kayıtlarını getir
        $fuelModel = $this->model('FuelModel');
        $fuelRecords = $fuelModel->getFuelRecordsByVehicle($id);
        $totalFuelCost = $fuelModel->getTotalFuelCostByVehicle($id);

        // Bakım kayıtlarını getir
        $maintenanceModel = $this->model('MaintenanceModel');
        $maintenanceRecords = $maintenanceModel->getMaintenanceRecordsByVehicle($id);

        // Görevlendirme kayıtlarını getir
        $assignmentModel = $this->model('Assignment');
        $assignments = $assignmentModel->getAssignmentsByVehicle($id);

        $data = [
            'title' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_number . ')',
            'vehicle' => $vehicle,
            'fuelRecords' => $fuelRecords,
            'totalFuelCost' => $totalFuelCost,
            'maintenanceRecords' => $maintenanceRecords,
            'assignments' => $assignments
        ];

        $this->view('vehicles/show', $data);
    }

    // Yeni araç ekleme sayfası
    public function add() {
        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                'company_id' => !empty($_POST['company_id']) ? trim($_POST['company_id']) : null,
                'order_number' => !empty($_POST['order_number']) ? trim($_POST['order_number']) : null,
                'equipment_number' => !empty($_POST['equipment_number']) ? trim($_POST['equipment_number']) : null,
                'fixed_asset_number' => !empty($_POST['fixed_asset_number']) ? trim($_POST['fixed_asset_number']) : null,
                'cost_center' => !empty($_POST['cost_center']) ? trim($_POST['cost_center']) : null,
                'production_site' => !empty($_POST['production_site']) ? trim($_POST['production_site']) : null,
                'inspection_date' => !empty($_POST['inspection_date']) ? trim($_POST['inspection_date']) : null,
                'traffic_insurance_agency' => !empty($_POST['traffic_insurance_agency']) ? trim($_POST['traffic_insurance_agency']) : null,
                'traffic_insurance_date' => !empty($_POST['traffic_insurance_date']) ? trim($_POST['traffic_insurance_date']) : null,
                'casco_insurance_agency' => !empty($_POST['casco_insurance_agency']) ? trim($_POST['casco_insurance_agency']) : null,
                'casco_insurance_date' => !empty($_POST['casco_insurance_date']) ? trim($_POST['casco_insurance_date']) : null,
                'work_site' => !empty($_POST['work_site']) ? trim($_POST['work_site']) : null,
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => '',
                'vehicleTypesList' => $this->vehicleModel::getStandardVehicleTypes()
            ];

            // Plaka numarası doğrulama
            if (empty($data['plate_number'])) {
                $data['plate_number_err'] = 'Lütfen plaka numarasını giriniz';
            } elseif ($this->vehicleModel->findVehicleByPlate($data['plate_number'])) {
                $data['plate_number_err'] = 'Bu plaka numarası zaten kayıtlı';
            }

            // Marka doğrulama
            if (empty($data['brand'])) {
                $data['brand_err'] = 'Lütfen marka bilgisini giriniz';
            }

            // Model doğrulama
            if (empty($data['model'])) {
                $data['model_err'] = 'Lütfen model bilgisini giriniz';
            }

            // Yıl doğrulama
            if (empty($data['year'])) {
                $data['year_err'] = 'Lütfen yıl bilgisini giriniz';
            } elseif (!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > date('Y') + 1) {
                $data['year_err'] = 'Geçerli bir yıl giriniz';
            }

            // Araç tipi doğrulama
            if (empty($data['vehicle_type'])) {
                $data['vehicle_type_err'] = 'Lütfen araç tipini seçiniz';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['plate_number_err']) && 
                empty($data['brand_err']) && 
                empty($data['model_err']) && 
                empty($data['year_err']) &&
                empty($data['vehicle_type_err']) &&
                empty($data['status_err'])) {
                
                // Veritabanına kaydet
                if ($this->vehicleModel->addVehicle($data)) {
                    flash('vehicle_message', 'Araç başarıyla eklendi');
                    redirect('vehicles');
                } else {
                    flash('vehicle_message', 'Araç eklenirken bir hata oluştu', 'alert alert-danger');
                    
                    // Şirketleri tekrar yükle
                    $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                    $this->view('vehicles/add', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                $this->view('vehicles/add', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde varsayılan veri
            $data = [
                'plate_number' => '',
                'brand' => '',
                'model' => '',
                'year' => date('Y'),
                'vehicle_type' => '',
                'status' => 'Aktif',
                'company_id' => null,
                'order_number' => '',
                'equipment_number' => '',
                'fixed_asset_number' => '',
                'cost_center' => '',
                'production_site' => '',
                'inspection_date' => '',
                'traffic_insurance_agency' => '',
                'traffic_insurance_date' => '',
                'casco_insurance_agency' => '',
                'casco_insurance_date' => '',
                'work_site' => '',
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => '',
                'companies' => $this->companyModel->getActiveCompaniesForSelect(),
                'vehicleTypesList' => $this->vehicleModel::getStandardVehicleTypes()
            ];

            $this->view('vehicles/add', $data);
        }
    }

    // Araç düzenleme sayfası
    public function edit($id) {
        // Araç bilgisini al
        $vehicle = $this->vehicleModel->getVehicleById($id);

        // Araç bulunamadıysa ana sayfaya yönlendir
        if (!$vehicle) {
            flash('vehicle_message', 'Araç bulunamadı', 'alert alert-danger');
            redirect('vehicles');
        }

        // POST isteği kontrol edilir
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Form verilerini al
            $data = [
                'id' => $id,
                'plate_number' => trim($_POST['plate_number']),
                'brand' => trim($_POST['brand']),
                'model' => trim($_POST['model']),
                'year' => trim($_POST['year']),
                'vehicle_type' => trim($_POST['vehicle_type']),
                'status' => trim($_POST['status']),
                'company_id' => !empty($_POST['company_id']) ? trim($_POST['company_id']) : null,
                'order_number' => !empty($_POST['order_number']) ? trim($_POST['order_number']) : null,
                'equipment_number' => !empty($_POST['equipment_number']) ? trim($_POST['equipment_number']) : null,
                'fixed_asset_number' => !empty($_POST['fixed_asset_number']) ? trim($_POST['fixed_asset_number']) : null,
                'cost_center' => !empty($_POST['cost_center']) ? trim($_POST['cost_center']) : null,
                'production_site' => !empty($_POST['production_site']) ? trim($_POST['production_site']) : null,
                'inspection_date' => !empty($_POST['inspection_date']) ? trim($_POST['inspection_date']) : null,
                'traffic_insurance_agency' => !empty($_POST['traffic_insurance_agency']) ? trim($_POST['traffic_insurance_agency']) : null,
                'traffic_insurance_date' => !empty($_POST['traffic_insurance_date']) ? trim($_POST['traffic_insurance_date']) : null,
                'casco_insurance_agency' => !empty($_POST['casco_insurance_agency']) ? trim($_POST['casco_insurance_agency']) : null,
                'casco_insurance_date' => !empty($_POST['casco_insurance_date']) ? trim($_POST['casco_insurance_date']) : null,
                'work_site' => !empty($_POST['work_site']) ? trim($_POST['work_site']) : null,
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => '',
                'vehicleTypesList' => $this->vehicleModel::getStandardVehicleTypes()
            ];

            // Plaka numarası doğrulama
            if (empty($data['plate_number'])) {
                $data['plate_number_err'] = 'Lütfen plaka numarasını giriniz';
            } else {
                // Eğer plaka değiştiyse ve yeni plaka başka bir araçta mevcutsa hata ver
                $existingVehicle = $this->vehicleModel->findVehicleByPlate($data['plate_number']);
                if ($existingVehicle && $existingVehicle->id != $id) {
                    $data['plate_number_err'] = 'Bu plaka numarası başka bir araçta kayıtlı';
                }
            }

            // Marka doğrulama
            if (empty($data['brand'])) {
                $data['brand_err'] = 'Lütfen marka bilgisini giriniz';
            }

            // Model doğrulama
            if (empty($data['model'])) {
                $data['model_err'] = 'Lütfen model bilgisini giriniz';
            }

            // Yıl doğrulama
            if (empty($data['year'])) {
                $data['year_err'] = 'Lütfen yıl bilgisini giriniz';
            } elseif (!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > date('Y') + 1) {
                $data['year_err'] = 'Geçerli bir yıl giriniz';
            }

            // Araç tipi doğrulama
            if (empty($data['vehicle_type'])) {
                $data['vehicle_type_err'] = 'Lütfen araç tipini seçiniz';
            }

            // Durum doğrulama
            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen durumu seçiniz';
            }

            // Hata yoksa işleme devam et
            if (empty($data['plate_number_err']) && 
                empty($data['brand_err']) && 
                empty($data['model_err']) && 
                empty($data['year_err']) && 
                empty($data['vehicle_type_err']) && 
                empty($data['status_err'])) {
                
                // Veritabanında güncelle
                if ($this->vehicleModel->updateVehicle($data)) {
                    flash('vehicle_message', 'Araç başarıyla güncellendi');
                    redirect('vehicles/show/' . $id);
                } else {
                    flash('vehicle_message', 'Araç güncellenirken bir hata oluştu', 'alert alert-danger');
                    
                    // Şirketleri tekrar yükle
                    $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                    $this->view('vehicles/edit', $data);
                }
            } else {
                // Hatalarla birlikte formu tekrar göster
                $data['companies'] = $this->companyModel->getActiveCompaniesForSelect();
                $this->view('vehicles/edit', $data);
            }
        } else {
            // Sayfa ilk kez yüklendiğinde mevcut verileri göster
            $data = [
                'id' => $id,
                'plate_number' => $vehicle->plate_number,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'vehicle_type' => $vehicle->vehicle_type,
                'status' => $vehicle->status,
                'company_id' => $vehicle->company_id,
                'order_number' => $vehicle->order_number ?? '',
                'equipment_number' => $vehicle->equipment_number ?? '',
                'fixed_asset_number' => $vehicle->fixed_asset_number ?? '',
                'cost_center' => $vehicle->cost_center ?? '',
                'production_site' => $vehicle->production_site ?? '',
                'inspection_date' => $vehicle->inspection_date ?? '',
                'traffic_insurance_agency' => $vehicle->traffic_insurance_agency ?? '',
                'traffic_insurance_date' => $vehicle->traffic_insurance_date ?? '',
                'casco_insurance_agency' => $vehicle->casco_insurance_agency ?? '',
                'casco_insurance_date' => $vehicle->casco_insurance_date ?? '',
                'work_site' => $vehicle->work_site ?? '',
                'plate_number_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'year_err' => '',
                'vehicle_type_err' => '',
                'status_err' => '',
                'companies' => $this->companyModel->getActiveCompaniesForSelect(),
                'vehicleTypesList' => $this->vehicleModel::getStandardVehicleTypes()
            ];

            $this->view('vehicles/edit', $data);
        }
    }

    // Araç silme işlemi
    public function delete($id) {
        // Yalnızca admin kullanıcılar silebilir
        if (!isAdmin()) {
            flash('vehicle_message', 'Araç silme yetkisine sahip değilsiniz', 'alert alert-danger');
            redirect('vehicles');
        }

        // POST isteği kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Aracı sil
            if ($this->vehicleModel->deleteVehicle($id)) {
                flash('vehicle_message', 'Araç başarıyla silindi');
            } else {
                flash('vehicle_message', 'Araç silinirken bir hata oluştu', 'alert alert-danger');
            }
        }

        redirect('vehicles');
    }

    public function getLastKm($id) {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Aracın son kayıtlı kilometresini bul
        $vehicle = $this->vehicleModel->getVehicleById($id);
        
        if($vehicle) {
            // API yanıtı formatında son kilometre bilgisini döndür
            header('Content-Type: application/json');
            
            // İlk olarak aracın kendi kayıtlı kilometresini kullan
            $lastKm = $vehicle->current_km;
            $lastHour = $vehicle->current_hour; // Saatlik değer
            
            // Varsa yakıt kayıtlarından en son kilometreyi al (daha güncel olabilir)
            $fuelModel = $this->model('FuelModel');
            $lastFuelRecord = $fuelModel->getLastFuelRecordByVehicle($id);
            
            if($lastFuelRecord) {
                if($lastFuelRecord->km_reading && $lastFuelRecord->km_reading > $lastKm) {
                    $lastKm = $lastFuelRecord->km_reading;
                }
                
                if($lastFuelRecord->hour_reading && $lastFuelRecord->hour_reading > $lastHour) {
                    $lastHour = $lastFuelRecord->hour_reading;
                }
            }
            
            echo json_encode([
                'success' => true,
                'last_km' => $lastKm,
                'last_hour' => $lastHour
            ]);
        } else {
            // Araç bulunamadı
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Araç bulunamadı'
            ]);
        }
        
        exit;
    }
} 