<?php
class Fuel extends Controller {
    private $fuelModel;
    private $vehicleModel;
    private $driverModel;
    private $tankModel;
    private $userModel;
    private $assignmentModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->fuelModel = $this->model('FuelModel');
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
        $this->tankModel = $this->model('FuelTank');
        $this->userModel = $this->model('User');
        $this->assignmentModel = $this->model('Assignment');
    }

    // Yakıt kayıtları listesini görüntüleme
    public function index() {
        // Filtre oturumunu temizle
        if (isset($_SESSION['fuel_filters'])) {
            unset($_SESSION['fuel_filters']);
        }
        
        // Tüm yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecords();
        
        // Araçların yakıt tüketim özetini getir
        $vehicleConsumption = $this->fuelModel->getVehicleFuelConsumptionSummary();
        
        // Yakıt türüne göre toplam tüketimi getir
        $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType(12); // Son 12 ay
        
        $data = [
            'title' => 'Yakıt Kayıtları',
            'records' => $records,
            'vehicle_consumption' => $vehicleConsumption,
            'fuel_consumption_by_type' => $fuelConsumptionByType,
            'vehicles' => $this->fuelModel->getActiveVehiclesForSelect(),
            'drivers' => $this->fuelModel->getActiveDriversForSelect(),
            'tanks' => $this->tankModel->getActiveTanks(),
            'fuel_types' => $this->fuelModel->getFuelTypes(),
            'filters' => [
                'vehicle_id' => '',
                'driver_id' => '',
                'tank_id' => '',
                'fuel_type' => '',
                'start_date' => '',
                'end_date' => ''
            ]
        ];
        
        $this->view('fuel/index', $data);
    }

    // Yakıt kaydı detaylarını görüntüleme
    public function show($id) {
        // ID'ye göre yakıt kaydını getir
        $record = $this->fuelModel->getFuelRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('fuel');
        }

        $data = [
            'title' => 'Yakıt Kaydı Detayı',
            'record' => $record
        ];

        $this->view('fuel/show', $data);
    }

    // Yeni yakıt kaydı ekleme
    public function add() {
        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get last user
            $user_id = $_SESSION['user_id'];
            
            // Get form values
            $data = [
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => trim($_POST['driver_id']),
                'tank_id' => trim($_POST['tank_id']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => floatval(trim($_POST['amount'])),
                'cost' => trim($_POST['cost']) == '' ? null : floatval(trim($_POST['cost'])),
                'km_reading' => trim($_POST['km_reading']) == '' ? null : intval(trim($_POST['km_reading'])),
                'hour_reading' => trim($_POST['hour_reading']) == '' ? null : floatval(trim($_POST['hour_reading'])),
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'vehicles' => $this->vehicleModel->getVehicles(),
                'drivers' => $this->driverModel->getDrivers(),
                'tanks' => $this->tankModel->getActiveTanks(),
                'users' => $this->userModel->getUsers(),
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'date_err' => '',
                'cost_err' => ''
            ];
            
            // Validate vehicle
            if(empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen bir araç seçin';
            }
            
            // Validate tank
            if(empty($data['tank_id'])) {
                $data['tank_id_err'] = 'Lütfen bir yakıt tankı seçin';
            }
            
            // Validate fuel type
            if(empty($data['fuel_type'])) {
                $data['fuel_type_err'] = 'Yakıt türü boş olamaz';
            }
            
            // Validate amount
            if(empty($data['amount'])) {
                $data['amount_err'] = 'Lütfen yakıt miktarını girin';
            } elseif($data['amount'] <= 0) {
                $data['amount_err'] = 'Yakıt miktarı 0\'dan büyük olmalıdır';
            } else {
                // Check if amount is valid for the tank
                $tank = $this->tankModel->getTankById($data['tank_id']);
                if($tank && $data['amount'] > $tank->current_amount) {
                    $data['amount_err'] = 'Yakıt miktarı tankın mevcut miktarından fazla olamaz';
                }
            }
            
            // Validate date
            if(empty($data['date'])) {
                $data['date_err'] = 'Lütfen tarih seçin';
            }
            
            // Validate cost if provided
            if(!empty($data['cost']) && $data['cost'] <= 0) {
                $data['cost_err'] = 'Tutar 0\'dan büyük olmalıdır';
            }
            
            // Make sure no errors
            if(empty($data['vehicle_id_err']) && empty($data['tank_id_err']) && 
               empty($data['fuel_type_err']) && empty($data['amount_err']) && 
               empty($data['date_err']) && empty($data['cost_err'])) {
                try {
                    // Add fuel record - tank update işlemi artık model içinde yapılıyor
                    if($this->fuelModel->addFuelRecord($data)) {
                        flash('success', 'Yakıt kaydı başarıyla eklendi');
                        redirect('fuel');
                    } else {
                        // Model tarafında bir hata oluşmuş
                        flash('error', 'Yakıt kaydı eklenirken bir hata oluştu. Tank miktarını kontrol edin.');
                        $this->view('fuel/add', $data);
                    }
                } catch (Exception $e) {
                    error_log('Yakıt kaydı ekleme hatası: ' . $e->getMessage());
                    flash('error', 'Sistem hatası: ' . $e->getMessage());
                    $this->view('fuel/add', $data);
                }
            } else {
                // Load view with errors
                $this->view('fuel/add', $data);
            }
        } else {
            // Init data
            $data = [
                'vehicle_id' => '',
                'driver_id' => '',
                'tank_id' => '',
                'fuel_type' => '',
                'amount' => '',
                'cost' => '',
                'km_reading' => '',
                'hour_reading' => '',
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
                'notes' => '',
                'vehicles' => $this->vehicleModel->getVehicles(),
                'drivers' => $this->driverModel->getDrivers(),
                'tanks' => $this->tankModel->getActiveTanks(),
                'users' => $this->userModel->getUsers()
            ];
            
            // Load view
            $this->view('fuel/add', $data);
        }
    }

    // Yakıt kaydı düzenleme
    public function edit($id) {
        // Kaydı getir
        $record = $this->fuelModel->getFuelRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('fuel');
        }

        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Yakıt Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => trim($_POST['vehicle_id']),
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'tank_id' => trim($_POST['tank_id']),
                'fuel_type' => trim($_POST['fuel_type']),
                'amount' => trim($_POST['amount']),
                'cost' => !empty($_POST['cost']) ? trim($_POST['cost']) : null,
                'km_reading' => !empty($_POST['km_reading']) ? trim($_POST['km_reading']) : null,
                'hour_reading' => !empty($_POST['hour_reading']) ? trim($_POST['hour_reading']) : null,
                'date' => trim($_POST['date']),
                'notes' => trim($_POST['notes']),
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'hour_reading_err' => '',
                'date_err' => '',
                'vehicles' => $this->fuelModel->getActiveVehiclesForSelect(),
                'drivers' => $this->fuelModel->getActiveDriversForSelect(),
                'tanks' => $this->tankModel->getActiveTanks()
            ];

            // Verileri doğrula
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçin';
            }

            if (empty($data['tank_id'])) {
                $data['tank_id_err'] = 'Lütfen yakıt tankı seçin';
            } else if ($data['tank_id'] != $record->tank_id) {
                // Tank değiştiyse, yeni tanktaki yakıt miktarını kontrol et
                $tank = $this->tankModel->getTankById($data['tank_id']);
                if ($tank && $data['amount'] > $tank->current_amount) {
                    $data['amount_err'] = 'Tanktaki yakıt miktarı yetersiz. Mevcut miktar: ' . $tank->current_amount;
                }
            } else if ($data['amount'] > $record->amount) {
                // Aynı tank ama miktar artırılmış, yeterli yakıt var mı kontrol et
                $additionalAmount = $data['amount'] - $record->amount;
                $tank = $this->tankModel->getTankById($data['tank_id']);
                if ($tank && $additionalAmount > $tank->current_amount) {
                    $data['amount_err'] = 'Tanktaki yakıt miktarı yetersiz. Mevcut miktar: ' . $tank->current_amount;
                }
            }

            if (empty($data['fuel_type'])) {
                $data['fuel_type_err'] = 'Lütfen yakıt türünü girin';
            }

            if (empty($data['amount'])) {
                $data['amount_err'] = 'Lütfen yakıt miktarını girin';
            } elseif (!is_numeric($data['amount']) || $data['amount'] <= 0) {
                $data['amount_err'] = 'Yakıt miktarı sıfırdan büyük bir sayı olmalıdır';
            }

            // Cost artık opsiyonel
            if (!empty($data['cost']) && (!is_numeric($data['cost']) || $data['cost'] < 0)) {
                $data['cost_err'] = 'Tutar geçerli bir sayı olmalıdır';
            }

            // Kilometre ve saat bilgileri artık opsiyonel
            if (!empty($data['km_reading']) && (!is_numeric($data['km_reading']) || $data['km_reading'] < 0)) {
                $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
            }

            if (!empty($data['hour_reading']) && (!is_numeric($data['hour_reading']) || $data['hour_reading'] < 0)) {
                $data['hour_reading_err'] = 'Çalışma saati bilgisi geçerli bir sayı olmalıdır';
            }

            if (empty($data['date'])) {
                $data['date_err'] = 'Lütfen tarih girin';
            }

            // Hata yoksa güncelle
            if (empty($data['vehicle_id_err']) && empty($data['tank_id_err']) && empty($data['fuel_type_err']) && 
                empty($data['amount_err']) && empty($data['cost_err']) && empty($data['km_reading_err']) && 
                empty($data['hour_reading_err']) && empty($data['date_err'])) {
                
                // Kaydı güncelle
                if ($this->fuelModel->updateFuelRecord($data)) {
                    flash('success', 'Yakıt kaydı başarıyla güncellendi');
                    redirect('fuel/show/' . $id);
                } else {
                    flash('error', 'Bir şeyler yanlış gitti. Lütfen tank miktarını kontrol edin.');
                    $this->view('fuel/edit', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('fuel/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yakıt Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => $record->vehicle_id,
                'driver_id' => $record->driver_id,
                'tank_id' => $record->tank_id,
                'fuel_type' => $record->fuel_type,
                'amount' => $record->amount,
                'cost' => $record->cost,
                'km_reading' => $record->km_reading,
                'hour_reading' => $record->hour_reading,
                'date' => $record->date,
                'notes' => $record->notes,
                'vehicle_id_err' => '',
                'driver_id_err' => '',
                'tank_id_err' => '',
                'fuel_type_err' => '',
                'amount_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'hour_reading_err' => '',
                'date_err' => '',
                'vehicles' => $this->fuelModel->getActiveVehiclesForSelect(),
                'drivers' => $this->fuelModel->getActiveDriversForSelect(),
                'tanks' => $this->tankModel->getActiveTanks()
            ];

            $this->view('fuel/edit', $data);
        }
    }

    // Yakıt kaydı silme
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kaydı getir
            $record = $this->fuelModel->getFuelRecordById($id);

            if (!$record) {
                flash('error', 'Kayıt bulunamadı');
                redirect('fuel');
            }

            // Kaydı sil
            if ($this->fuelModel->deleteFuelRecord($id)) {
                flash('success', 'Yakıt kaydı başarıyla silindi');
            } else {
                flash('error', 'Kayıt silinemedi');
            }

            redirect('fuel');
        } else {
            redirect('fuel');
        }
    }

    // Araca göre yakıt kayıtları
    public function vehicle($vehicleId) {
        // Araç bilgilerini al
        $vehicle = $this->vehicleModel->getVehicleById($vehicleId);

        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('fuel');
        }

        // Araca ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByVehicle($vehicleId);

        // Toplam tüketim istatistikleri
        $stats = $this->fuelModel->getVehicleFuelConsumption($vehicleId);

        $data = [
            'title' => 'Araç Yakıt Kayıtları: ' . $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_number . ')',
            'vehicle' => $vehicle,
            'records' => $records,
            'stats' => $stats
        ];

        $this->view('fuel/vehicle', $data);
    }

    // Sürücüye göre yakıt kayıtları
    public function driver($driverId) {
        // Sürücü bilgilerini al
        $driver = $this->driverModel->getDriverById($driverId);

        if (!$driver) {
            flash('error', 'Sürücü bulunamadı');
            redirect('fuel');
        }

        // Sürücüye ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByDriver($driverId);

        $data = [
            'title' => 'Sürücü Yakıt Kayıtları: ' . $driver->name . ' ' . $driver->surname,
            'driver' => $driver,
            'records' => $records
        ];

        $this->view('fuel/driver', $data);
    }

    // Tanka göre yakıt kayıtları
    public function tank($tankId) {
        // Tank bilgilerini al
        $tank = $this->tankModel->getTankById($tankId);

        if (!$tank) {
            flash('error', 'Tank bulunamadı');
            redirect('fuel');
        }

        // Bu tanka ait yakıt kayıtlarını getir
        $records = $this->fuelModel->getFuelRecordsByTank($tankId);

        $data = [
            'title' => 'Tank Yakıt Dağıtım Kayıtları: ' . $tank->name,
            'tank' => $tank,
            'records' => $records
        ];

        $this->view('fuel/tank', $data);
    }

    // Yakıt istatistikleri
    public function stats() {
        // Genel yakıt istatistikleri
        $totalStats = $this->fuelModel->getTotalFuelStats();
        
        // Yakıt tipine göre istatistikler
        $typeStats = $this->fuelModel->getFuelStatsByType();

        $data = [
            'title' => 'Yakıt İstatistikleri',
            'totalStats' => $totalStats,
            'typeStats' => $typeStats
        ];

        $this->view('fuel/stats', $data);
    }

    // API endpoint to get last driver for vehicle
    public function getLastDriverForVehicle($vehicleId = null) {
        // JSON çıktısı için içerik tipini ayarla
        header('Content-Type: application/json');

        // API yanıtlarını hazırla
        $response = [];
        
        try {
            // Araç ID kontrolü
            if(!$vehicleId) {
                $response = ['success' => false, 'message' => 'Araç ID eksik'];
                echo json_encode($response);
                return;
            }
            
            // Doğrudan aktif görevlendirmeden sürücüyü kontrol et
            if(isset($this->assignmentModel) && method_exists($this->assignmentModel, 'getCurrentDriverForVehicle')) {
                $assignment = $this->assignmentModel->getCurrentDriverForVehicle($vehicleId);
                
                if($assignment && isset($assignment->driver_id)) {
                    $response = ['success' => true, 'driver_id' => $assignment->driver_id, 'source' => 'assignments'];
                } else {
                    $response = ['success' => false, 'message' => 'Bu araç için aktif görevlendirme bulunamadı'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Assignment modeli mevcut değil veya metot bulunamadı'];
            }
        } catch (Exception $e) {
            $response = [
                'success' => false, 
                'message' => 'Sunucu hatası: ' . $e->getMessage(),
                'error' => true
            ];
        }
        
        // JSON çıktısını oluşturmadan önce çıktı tamponunu temizle
        if (ob_get_length()) ob_clean();
        
        // JSON çıktısını gönder
        echo json_encode($response);
        exit(); // Çıktıdan sonra çalışmayı durdur
    }

    // API endpoint to get last unit price for fuel type
    public function getLastUnitPrice($fuelType = null) {
        // HTTP başlığını ayarla
        header('Content-Type: application/json');
        
        // Yakıt türü kontrolü
        if(!$fuelType) {
            echo json_encode(['success' => false, 'message' => 'Yakıt türü eksik']);
            return;
        }
        
        // Get the last unit price for this fuel type
        $unitPrice = $this->fuelModel->getLastUnitPriceForFuelType($fuelType);
        
        if($unitPrice) {
            echo json_encode(['success' => true, 'unit_price' => $unitPrice]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Birim fiyat bulunamadı']);
        }
    }

    // Kayıtları filtrele
    public function filter() {
        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Filtreleme parametrelerini hazırla
            $filters = [
                'vehicle_id' => !empty($_POST['vehicle_id']) ? trim($_POST['vehicle_id']) : '',
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : '',
                'tank_id' => !empty($_POST['tank_id']) ? trim($_POST['tank_id']) : '',
                'fuel_type' => !empty($_POST['fuel_type']) ? trim($_POST['fuel_type']) : '',
                'start_date' => !empty($_POST['start_date']) ? trim($_POST['start_date']) : '',
                'end_date' => !empty($_POST['end_date']) ? trim($_POST['end_date']) : ''
            ];
            
            // Filtrelenmiş kayıtları getir
            $records = $this->fuelModel->getFilteredFuelRecords($filters);
            
            // Araçların yakıt tüketim özetini getir
            $vehicleConsumption = $this->fuelModel->getVehicleFuelConsumptionSummary();
            
            // Yakıt türüne göre toplam tüketimi getir
            $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType(12); // Son 12 ay
            
            // Filtre durumunu session'a kaydet
            $_SESSION['fuel_filters'] = $filters;
            
            $data = [
                'title' => 'Yakıt Kayıtları - Filtrelenmiş',
                'records' => $records,
                'vehicle_consumption' => $vehicleConsumption,
                'fuel_consumption_by_type' => $fuelConsumptionByType,
                'vehicles' => $this->fuelModel->getActiveVehiclesForSelect(),
                'drivers' => $this->fuelModel->getActiveDriversForSelect(),
                'tanks' => $this->tankModel->getActiveTanks(),
                'fuel_types' => $this->fuelModel->getFuelTypes(),
                'filters' => $filters
            ];
            
            $this->view('fuel/index', $data);
        } else {
            redirect('fuel');
        }
    }

    // Yakıt türüne göre kayıtları görüntüleme
    public function type($fuelType) {
        // URL'den gelen yakıt tipini temizle
        $fuelType = filter_var($fuelType, FILTER_SANITIZE_STRING);
        
        // Yakıt türüne göre kayıtları getir
        $records = $this->fuelModel->getFuelRecordsByType($fuelType);
        
        $data = [
            'title' => $fuelType . ' Yakıt Kayıtları',
            'records' => $records,
            'fuel_type' => $fuelType
        ];
        
        $this->view('fuel/type', $data);
    }
} 