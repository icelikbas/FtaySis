<?php
class Maintenance extends Controller {
    private $maintenanceModel;
    private $vehicleModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->maintenanceModel = $this->model('MaintenanceModel');
        $this->vehicleModel = $this->model('Vehicle');
    }

    // Bakım kayıtları listesini görüntüleme
    public function index() {
        // Tüm bakım kayıtlarını getir
        $records = $this->maintenanceModel->getMaintenanceRecords();
        
        // Bakım türlerine göre dağılım al
        $typeDistribution = $this->maintenanceModel->getMaintenanceTypeDistribution();
        
        // Toplam bakım maliyeti
        $totalCost = 0;
        foreach($records as $record) {
            $totalCost += $record->cost;
        }
        
        // Duruma göre bakım sayıları
        $statusCounts = [
            'Planlandı' => 0,
            'Devam Ediyor' => 0,
            'Tamamlandı' => 0,
            'İptal' => 0
        ];
        
        foreach($records as $record) {
            if(isset($statusCounts[$record->status])) {
                $statusCounts[$record->status]++;
            }
        }
        
        // Yaklaşan bakımlar - Önümüzdeki 30 gün içinde bakımı yapılacak araçlar
        $today = date('Y-m-d');
        $upcomingMaintenances = [];
        
        foreach($records as $record) {
            if($record->next_maintenance_date && strtotime($record->next_maintenance_date) > strtotime($today) && 
               strtotime($record->next_maintenance_date) <= strtotime('+30 days', strtotime($today))) {
                $upcomingMaintenances[] = $record;
            }
        }
        
        // Yaklaşan kilometre bakımları - Aracın mevcut km'si ile sonraki bakım km'si arasında 1000km kalan araçlar
        $upcomingKmMaintenances = [];
        
        foreach($records as $record) {
            if($record->next_maintenance_km && $record->next_maintenance_km - $record->km_reading <= 1000 && 
               $record->next_maintenance_km - $record->km_reading > 0) {
                $upcomingKmMaintenances[] = $record;
            }
        }
        
        // Servis sağlayıcı dağılımı 
        $serviceProviders = [];
        foreach($records as $record) {
            if(!empty($record->service_provider)) {
                if(!isset($serviceProviders[$record->service_provider])) {
                    $serviceProviders[$record->service_provider] = 0;
                }
                $serviceProviders[$record->service_provider]++;
            }
        }
        arsort($serviceProviders); // En çok kullanılan servis sağlayıcıları sırala
        
        $data = [
            'title' => 'Bakım Kayıtları',
            'records' => $records,
            'typeDistribution' => $typeDistribution,
            'totalCost' => $totalCost,
            'statusCounts' => $statusCounts,
            'upcomingMaintenances' => $upcomingMaintenances,
            'upcomingKmMaintenances' => $upcomingKmMaintenances,
            'serviceProviders' => $serviceProviders
        ];

        $this->view('maintenance/index', $data);
    }

    // Bakım kaydı detaylarını görüntüleme
    public function show($id) {
        // ID'ye göre bakım kaydını getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }

        $data = [
            'title' => 'Bakım Kaydı Detayı',
            'record' => $record
        ];

        $this->view('maintenance/show', $data);
    }

    // Yeni bakım kaydı ekleme
    public function add() {
        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Yeni Bakım Kaydı',
                'vehicle_id' => trim($_POST['vehicle_id']),
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'cost' => trim($_POST['cost']),
                'km_reading' => trim($_POST['km_reading']),
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'service_provider' => isset($_POST['service_provider']) ? trim($_POST['service_provider']) : '',
                'next_maintenance_date' => isset($_POST['next_maintenance_date']) ? trim($_POST['next_maintenance_date']) : '',
                'next_maintenance_km' => isset($_POST['next_maintenance_km']) ? trim($_POST['next_maintenance_km']) : '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            // Verileri doğrula
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçin';
            }

            if (empty($data['maintenance_type'])) {
                $data['maintenance_type_err'] = 'Lütfen bakım türünü seçin';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen bakım açıklamasını girin';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini girin';
            }

            if (empty($data['cost'])) {
                $data['cost_err'] = 'Lütfen bakım maliyetini girin';
            } elseif (!is_numeric($data['cost']) || $data['cost'] < 0) {
                $data['cost_err'] = 'Maliyet geçerli bir sayı olmalıdır';
            }

            if (empty($data['km_reading'])) {
                $data['km_reading_err'] = 'Lütfen kilometre bilgisini girin';
            } elseif (!is_numeric($data['km_reading']) || $data['km_reading'] < 0) {
                $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen bakım durumunu seçin';
            }

            // Hata yoksa kaydet
            if (empty($data['vehicle_id_err']) && empty($data['maintenance_type_err']) && 
                empty($data['description_err']) && empty($data['start_date_err']) && 
                empty($data['cost_err']) && empty($data['km_reading_err']) && 
                empty($data['status_err'])) {
                
                // Eğer bakım "Devam Ediyor" veya "Planlandı" durumundaysa, aracın durumunu güncelle
                if ($data['status'] == 'Devam Ediyor' || $data['status'] == 'Planlandı') {
                    $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Bakımda');
                }
                
                // Kaydı ekle
                if ($this->maintenanceModel->addMaintenanceRecord($data)) {
                    flash('success', 'Bakım kaydı başarıyla eklendi');
                    redirect('maintenance');
                } else {
                    flash('error', 'Bir şeyler yanlış gitti');
                    $this->view('maintenance/add', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('maintenance/add', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Yeni Bakım Kaydı',
                'vehicle_id' => '',
                'maintenance_type' => '',
                'description' => '',
                'start_date' => date('Y-m-d'),
                'end_date' => '',
                'cost' => '',
                'km_reading' => '',
                'status' => '',
                'notes' => '',
                'service_provider' => '',
                'next_maintenance_date' => '',
                'next_maintenance_km' => '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            $this->view('maintenance/add', $data);
        }
    }

    // Bakım kaydı düzenleme
    public function edit($id) {
        // Kaydı getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);

        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }

        // Form gönderilmiş mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini temizle
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Verileri hazırla
            $data = [
                'title' => 'Bakım Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => trim($_POST['vehicle_id']),
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'cost' => trim($_POST['cost']),
                'km_reading' => trim($_POST['km_reading']),
                'status' => trim($_POST['status']),
                'notes' => trim($_POST['notes']),
                'service_provider' => isset($_POST['service_provider']) ? trim($_POST['service_provider']) : '',
                'next_maintenance_date' => isset($_POST['next_maintenance_date']) ? trim($_POST['next_maintenance_date']) : '',
                'next_maintenance_km' => isset($_POST['next_maintenance_km']) ? trim($_POST['next_maintenance_km']) : '',
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            // Verileri doğrula
            if (empty($data['vehicle_id'])) {
                $data['vehicle_id_err'] = 'Lütfen araç seçin';
            }

            if (empty($data['maintenance_type'])) {
                $data['maintenance_type_err'] = 'Lütfen bakım türünü seçin';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Lütfen bakım açıklamasını girin';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Lütfen başlangıç tarihini girin';
            }

            if (empty($data['cost'])) {
                $data['cost_err'] = 'Lütfen bakım maliyetini girin';
            } elseif (!is_numeric($data['cost']) || $data['cost'] < 0) {
                $data['cost_err'] = 'Maliyet geçerli bir sayı olmalıdır';
            }

            if (empty($data['km_reading'])) {
                $data['km_reading_err'] = 'Lütfen kilometre bilgisini girin';
            } elseif (!is_numeric($data['km_reading']) || $data['km_reading'] < 0) {
                $data['km_reading_err'] = 'Kilometre bilgisi geçerli bir sayı olmalıdır';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Lütfen bakım durumunu seçin';
            }

            // Hata yoksa güncelle
            if (empty($data['vehicle_id_err']) && empty($data['maintenance_type_err']) && 
                empty($data['description_err']) && empty($data['start_date_err']) && 
                empty($data['cost_err']) && empty($data['km_reading_err']) && 
                empty($data['status_err'])) {
                
                // Bakım durumu değiştiyse araç durumunu güncelle
                if ($data['status'] != $record->status) {
                    if ($data['status'] == 'Tamamlandı' || $data['status'] == 'İptal') {
                        // Aracın başka devam eden bakımı var mı kontrol et
                        $otherMaintenances = $this->maintenanceModel->getActiveMaintenancesForVehicleExcept($data['vehicle_id'], $id);
                        
                        if (empty($otherMaintenances)) {
                            // Başka devam eden bakım yoksa aracı aktif yap
                            $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Aktif');
                        }
                    } else {
                        // Bakım devam ediyor veya planlandı ise aracı bakımda olarak güncelle
                        $this->maintenanceModel->updateVehicleMaintenanceStatus($data['vehicle_id'], 'Bakımda');
                    }
                }
                
                // Kaydı güncelle
                if ($this->maintenanceModel->updateMaintenanceRecord($data)) {
                    flash('success', 'Bakım kaydı başarıyla güncellendi');
                    redirect('maintenance/show/' . $id);
                } else {
                    flash('error', 'Bir şeyler yanlış gitti');
                    $this->view('maintenance/edit', $data);
                }
            } else {
                // Hata varsa formu yeniden göster
                $this->view('maintenance/edit', $data);
            }
        } else {
            // GET isteği - formu göster
            $data = [
                'title' => 'Bakım Kaydı Düzenle',
                'id' => $id,
                'vehicle_id' => $record->vehicle_id,
                'maintenance_type' => $record->maintenance_type,
                'description' => $record->description,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'cost' => $record->cost,
                'km_reading' => $record->km_reading,
                'status' => $record->status,
                'notes' => $record->notes,
                'service_provider' => $record->service_provider,
                'next_maintenance_date' => $record->next_maintenance_date,
                'next_maintenance_km' => $record->next_maintenance_km,
                'vehicle_id_err' => '',
                'maintenance_type_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'cost_err' => '',
                'km_reading_err' => '',
                'status_err' => '',
                'vehicles' => $this->maintenanceModel->getActiveVehiclesForSelect()
            ];

            $this->view('maintenance/edit', $data);
        }
    }

    // Bakım kaydı silme
    public function delete($id) {
        // Kaydı getir
        $record = $this->maintenanceModel->getMaintenanceRecordById($id);
        
        if (!$record) {
            flash('error', 'Kayıt bulunamadı');
            redirect('maintenance');
        }
        
        // Silme işlemi için form kontrolü
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kaydı sil
            if ($this->maintenanceModel->deleteMaintenanceRecord($id)) {
                flash('success', 'Bakım kaydı başarıyla silindi');
                redirect('maintenance');
            } else {
                flash('error', 'Bakım kaydı silinemedi');
                redirect('maintenance');
            }
        } else {
            // Silme formu göster
            $data = [
                'title' => 'Bakım Kaydı Sil',
                'record' => $record
            ];
            
            $this->view('maintenance/delete', $data);
        }
    }

    // Araç bakım raporları
    public function vehicleReport($vehicle_id) {
        // Aracı kontrol et
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);
        
        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('maintenance');
        }

        // Araç bakım kayıtlarını getir
        $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByVehicle($vehicle_id);
        
        // Toplam bakım maliyetini getir
        $totalCost = $this->maintenanceModel->getTotalMaintenanceCostByVehicle($vehicle_id);

        $data = [
            'vehicle' => $vehicle,
            'maintenanceRecords' => $maintenanceRecords,
            'totalCost' => $totalCost
        ];

        $this->view('maintenance/vehicle_report', $data);
    }

    // Bakım tipine göre analiz raporu
    public function analysis() {
        // Bakım tiplerine göre maliyet analizi
        $costAnalysis = $this->maintenanceModel->getMaintenanceCostAnalysis();
        
        // Duruma göre bakım sayıları
        $plannedCount = $this->maintenanceModel->getMaintenanceCountByStatus('Planlandı');
        $inProgressCount = $this->maintenanceModel->getMaintenanceCountByStatus('Devam Ediyor');
        $completedCount = $this->maintenanceModel->getMaintenanceCountByStatus('Tamamlandı');
        $canceledCount = $this->maintenanceModel->getMaintenanceCountByStatus('İptal');
        
        // Toplam bakım maliyeti
        $totalCost = $this->maintenanceModel->getTotalMaintenanceCost();

        $data = [
            'title' => 'Bakım Analizi',
            'costAnalysis' => $costAnalysis,
            'plannedCount' => $plannedCount,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'canceledCount' => $canceledCount,
            'totalCost' => $totalCost
        ];

        $this->view('maintenance/analysis', $data);
    }
} 