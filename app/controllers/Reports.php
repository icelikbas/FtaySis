<?php
class Reports extends Controller {
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

    // Ana sayfa - Tüm rapor türlerine genel bakış
    public function index() {
        // Genel istatistikleri al
        $vehicleStats = [
            'total' => $this->vehicleModel->getTotalVehicleCount(),
            'active' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'inactive' => $this->vehicleModel->getVehicleCountByStatus('Pasif'),
            'maintenance' => $this->vehicleModel->getVehicleCountByStatus('Bakımda')
        ];

        $driverStats = [
            'total' => $this->driverModel->getTotalDriverCount(),
            'active' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'inactive' => $this->driverModel->getDriverCountByStatus('Pasif'),
            'onLeave' => $this->driverModel->getDriverCountByStatus('İzinli')
        ];

        $assignmentStats = [
            'total' => $this->assignmentModel->getTotalAssignmentCount(),
            'active' => $this->assignmentModel->getAssignmentCountByStatus('Aktif'),
            'completed' => $this->assignmentModel->getAssignmentCountByStatus('Tamamlandı'),
            'cancelled' => $this->assignmentModel->getAssignmentCountByStatus('İptal')
        ];

        $fuelStats = $this->fuelModel->getTotalFuelStats();
        $maintenanceStats = $this->maintenanceModel->getTotalMaintenanceStats();

        $data = [
            'title' => 'Raporlar',
            'vehicleStats' => $vehicleStats,
            'driverStats' => $driverStats,
            'assignmentStats' => $assignmentStats,
            'fuelStats' => $fuelStats,
            'maintenanceStats' => $maintenanceStats
        ];

        $this->view('reports/index', $data);
    }

    // Araç raporları
    public function vehicles() {
        // Filtre parametrelerini al
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $year = isset($_GET['year']) ? intval($_GET['year']) : 0;

        // Tarihlere göre filtreleme için
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Araçları filtrelere göre getir
        $vehicles = $this->vehicleModel->getVehiclesByFilters($status, $type, $year);

        // Her araç için yakıt ve bakım bilgilerini topla
        foreach ($vehicles as $vehicle) {
            $vehicle->fuelStats = $this->fuelModel->getVehicleFuelConsumption($vehicle->id);
            $vehicle->maintenanceCost = $this->maintenanceModel->getTotalMaintenanceCostByVehicle($vehicle->id);
            $vehicle->assignment_count = $this->assignmentModel->getAssignmentCountByVehicle($vehicle->id);
        }

        // İstatistikler
        $vehicleStats = [
            'total' => $this->vehicleModel->getTotalVehicleCount(),
            'active' => $this->vehicleModel->getVehicleCountByStatus('Aktif'),
            'inactive' => $this->vehicleModel->getVehicleCountByStatus('Pasif'),
            'maintenance' => $this->vehicleModel->getVehicleCountByStatus('Bakımda')
        ];

        // Araç tipleri için dağılım
        $vehicleTypeDistribution = $this->vehicleModel->getVehicleCountByType();

        $data = [
            'title' => 'Araç Raporları',
            'vehicles' => $vehicles,
            'vehicleStats' => $vehicleStats,
            'vehicleTypeDistribution' => $vehicleTypeDistribution,
            'filters' => [
                'status' => $status,
                'type' => $type,
                'year' => $year,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/vehicles', $data);
    }

    // Sürücü raporları
    public function drivers() {
        // Filtre parametrelerini al
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $licenseType = isset($_GET['license_type']) ? trim($_GET['license_type']) : '';
        $assignmentStatus = isset($_GET['assignment_status']) ? trim($_GET['assignment_status']) : '';

        // Sürücüleri filtrelere göre getir
        $drivers = $this->driverModel->getDriversByFilters($status, $licenseType, $assignmentStatus !== '' ? $assignmentStatus : null);

        // Her sürücü için görevlendirme bilgilerini topla
        foreach ($drivers as $driver) {
            $driver->assignment_count = $this->assignmentModel->getAssignmentCountByDriver($driver->id);
            $driver->current_assignment = $this->assignmentModel->getActiveAssignmentByDriver($driver->id);
        }

        // Aktif görevlendirmesi olan sürücü sayısını bul
        $this->db = new Database;
        $this->db->query('SELECT COUNT(DISTINCT driver_id) as count FROM vehicle_assignments WHERE status = "Aktif"');
        $assignedDriverCount = $this->db->single()->count;

        // İstatistikler
        $driverStats = [
            'total' => $this->driverModel->getTotalDriverCount(),
            'active' => $this->driverModel->getDriverCountByStatus('Aktif'),
            'inactive' => $this->driverModel->getDriverCountByStatus('Pasif'),
            'onLeave' => $this->driverModel->getDriverCountByStatus('İzinli'),
            'assigned' => $assignedDriverCount,
            'total_assignments' => $this->assignmentModel->getTotalAssignmentCount()
        ];

        // Ehliyet tiplerine göre dağılım
        $licenseTypeDistribution = $this->driverModel->getDriverCountByLicenseType();

        $data = [
            'title' => 'Sürücü Raporları',
            'drivers' => $drivers,
            'driverStats' => $driverStats,
            'licenseDistribution' => $licenseTypeDistribution,
            'filters' => [
                'status' => $status,
                'license_type' => $licenseType,
                'assignment_status' => $assignmentStatus
            ]
        ];

        $this->view('reports/drivers', $data);
    }

    // Yakıt raporları
    public function fuel() {
        // Filtre parametrelerini al
        $filters = [
            'vehicle_id' => isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : null,
            'driver_id' => isset($_GET['driver_id']) ? intval($_GET['driver_id']) : null,
            'fuel_type' => isset($_GET['fuel_type']) ? trim($_GET['fuel_type']) : null,
            'start_date' => isset($_GET['start_date']) ? trim($_GET['start_date']) : null,
            'end_date' => isset($_GET['end_date']) ? trim($_GET['end_date']) : null
        ];

        // Yakıt kayıtlarını filtrelere göre getir (Modeldeki getFilteredFuelRecords kullanılıyor)
        $fuelRecords = $this->fuelModel->getFilteredFuelRecords($filters);

        // Filtrelenmiş kayıtlara göre istatistikleri hesapla
        $totalFuel = 0;
        $totalCost = 0;
        $recordCount = count($fuelRecords);
        $fuelTypeStatsAgg = []; // Yakıt tipine göre toplama

        foreach ($fuelRecords as $record) {
            $totalFuel += $record->amount;
            $totalCost += $record->cost;

            // Yakıt tipi istatistiklerini topla
            if (!isset($fuelTypeStatsAgg[$record->fuel_type])) {
                $fuelTypeStatsAgg[$record->fuel_type] = ['total_amount' => 0, 'total_cost' => 0, 'record_count' => 0];
            }
            $fuelTypeStatsAgg[$record->fuel_type]['total_amount'] += $record->amount;
            $fuelTypeStatsAgg[$record->fuel_type]['total_cost'] += $record->cost;
            $fuelTypeStatsAgg[$record->fuel_type]['record_count']++;
        }

        $avgPricePerUnit = ($totalFuel > 0) ? ($totalCost / $totalFuel) : 0;

        $fuelStats = (object)[
            'total_fuel' => $totalFuel,
            'total_cost' => $totalCost,
            'record_count' => $recordCount,
            'avg_price_per_unit' => $avgPricePerUnit
            // 'vehicle_count' istatistiği bu hesaplamada yok, gerekirse eklenebilir.
        ];

        // Yakıt türlerine göre dağılım için verileri yeniden düzenle
        $fuelTypeDistribution = [];
        foreach ($fuelTypeStatsAgg as $fuelType => $stats) {
             $fuelTypeDistribution[] = (object)[
                'fuel_type' => $fuelType,
                'total_liters' => $stats['total_amount'], // Görünüm 'total_liters' bekliyor
                'total_cost' => $stats['total_cost']
            ];
        }
        // Miktara göre sırala (isteğe bağlı)
        usort($fuelTypeDistribution, function($a, $b) {
            return $b->total_liters <=> $a->total_liters;
        });


        // Araç ve sürücü listeleri (Filtre dropdownları için)
        // Not: getAllDrivers yerine getActiveDriversForSelect kullanmak daha mantıklı olabilir
        // Ancak mevcut kod getAllDrivers kullandığı için şimdilik onu bırakıyoruz.
        // Eğer sürücü adları eksik geliyorsa, getAllDrivers metodunu kontrol etmek gerekir.
        $vehicles = $this->vehicleModel->getAllVehicles(); // Veya getActiveVehiclesForSelect
        $drivers = $this->driverModel->getAllDrivers(); // Veya getActiveDriversForSelect

        $data = [
            'title' => 'Yakıt Raporları',
            'fuelRecords' => $fuelRecords,
            'fuelStats' => $fuelStats,
            // 'fuelTypeStats' => $fuelTypeStats, // Artık fuelTypeDistribution kullanılıyor
            'fuelTypeDistribution' => $fuelTypeDistribution,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'filters' => $filters // Filtre değerlerini view'e gönder
        ];

        $this->view('reports/fuel', $data);
    }

    // Bakım raporları
    public function maintenance() {
        // Filtre parametrelerini al
        $vehicleId = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;
        $maintenanceType = isset($_GET['maintenance_type']) ? trim($_GET['maintenance_type']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Bakım kayıtlarını filtrelere göre getir
        $maintenanceRecords = [];
        if (!empty($startDate) && !empty($endDate)) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByDateRange($startDate, $endDate);
        } elseif ($vehicleId > 0) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByVehicle($vehicleId);
        } elseif (!empty($maintenanceType)) {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByType($maintenanceType);
        } else {
            $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecords();
        }

        // Duruma göre filtreleme
        if (!empty($status)) {
            $filteredRecords = [];
            foreach ($maintenanceRecords as $record) {
                if ($record->status == $status) {
                    $filteredRecords[] = $record;
                }
            }
            $maintenanceRecords = $filteredRecords;
        }

        // Bakım istatistikleri
        $maintenanceStats = $this->maintenanceModel->getTotalMaintenanceStats();

        // Bakım türlerine göre istatistikler
        $maintenanceTypeStats = $this->maintenanceModel->getMaintenanceStatsByType();

        // Yaklaşan bakımlar
        $upcomingMaintenances = $this->maintenanceModel->getUpcomingMaintenances();

        // Araç listesi
        $vehicles = $this->vehicleModel->getAllVehicles();

        $data = [
            'title' => 'Bakım Raporları',
            'maintenanceRecords' => $maintenanceRecords,
            'maintenanceStats' => $maintenanceStats,
            'maintenanceTypeStats' => $maintenanceTypeStats,
            'upcomingMaintenances' => $upcomingMaintenances,
            'vehicles' => $vehicles,
            'filters' => [
                'vehicle_id' => $vehicleId,
                'maintenance_type' => $maintenanceType,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/maintenance', $data);
    }

    // Görevlendirme raporları
    public function assignments() {
        // Filtre parametrelerini al
        $vehicleId = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;
        $driverId = isset($_GET['driver_id']) ? intval($_GET['driver_id']) : 0;
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
        $endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

        // Görevlendirmeleri filtrelere göre getir
        $assignments = [];
        if ($vehicleId > 0 && $driverId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByVehicleAndDriver($vehicleId, $driverId);
        } elseif ($vehicleId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByVehicle($vehicleId);
        } elseif ($driverId > 0) {
            $assignments = $this->assignmentModel->getAssignmentsByDriver($driverId);
        } else {
            $assignments = $this->assignmentModel->getAllAssignments();
        }

        // Duruma göre filtreleme
        if (!empty($status)) {
            $filteredAssignments = [];
            foreach ($assignments as $assignment) {
                if ($assignment->status == $status) {
                    $filteredAssignments[] = $assignment;
                }
            }
            $assignments = $filteredAssignments;
        }

        // Tarihe göre filtreleme
        if (!empty($startDate) && !empty($endDate)) {
            $filteredAssignments = [];
            foreach ($assignments as $assignment) {
                if (strtotime($assignment->start_date) >= strtotime($startDate) && 
                    (empty($assignment->end_date) || strtotime($assignment->end_date) <= strtotime($endDate))) {
                    $filteredAssignments[] = $assignment;
                }
            }
            $assignments = $filteredAssignments;
        }

        // Görevlendirme istatistikleri
        $assignmentStats = [
            'total' => $this->assignmentModel->getTotalAssignmentCount(),
            'active' => $this->assignmentModel->getAssignmentCountByStatus('Aktif'),
            'completed' => $this->assignmentModel->getAssignmentCountByStatus('Tamamlandı'),
            'cancelled' => $this->assignmentModel->getAssignmentCountByStatus('İptal')
        ];
        
        // En çok görevlendirilen araçları ve sürücüleri al
        $topVehicles = $this->assignmentModel->getTopVehicles(5);
        $topDrivers = $this->assignmentModel->getTopDrivers(5);

        // Araç ve sürücü listeleri
        $vehicles = $this->vehicleModel->getAllVehicles();
        $drivers = $this->driverModel->getAllDrivers();

        $data = [
            'title' => 'Görevlendirme Raporları',
            'assignments' => $assignments,
            'assignmentStats' => $assignmentStats,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'topVehicles' => $topVehicles,
            'topDrivers' => $topDrivers,
            'filters' => [
                'vehicle_id' => $vehicleId,
                'driver_id' => $driverId,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];

        $this->view('reports/assignments', $data);
    }

    // Genel maliyet raporu
    public function costs() {
        // Tarih aralığını al
        $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? trim($_GET['start_date']) : date('Y-m-01'); // Ayın başlangıcı
        $endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? trim($_GET['end_date']) : date('Y-m-t');  // Ayın sonu
        $vehicleId = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : 0;
        $costType = isset($_GET['cost_type']) ? trim($_GET['cost_type']) : '';

        // Yakıt maliyetleri
        $fuelStats = $this->fuelModel->getFuelStatsByDateRange($startDate, $endDate);
        $fuelCostTotal = ($fuelStats && isset($fuelStats->total_cost)) ? $fuelStats->total_cost : 0;
        
        // Bakım maliyetleri
        $maintenanceRecords = $this->maintenanceModel->getMaintenanceRecordsByDateRange($startDate, $endDate);
        $maintenanceCost = 0;
        foreach ($maintenanceRecords as $record) {
            if (isset($record->cost)) {
                $maintenanceCost += $record->cost;
            }
        }
        
        // Araç başına maliyet dağılımı
        $vehicles = $this->vehicleModel->getAllVehicles();
        $vehicleCosts = [];
        $costDetails = [];
        $monthlyCosts = [];
        
        // Aylık maliyet dağılımını hazırla (basitleştirilmiş)
        $currentMonth = date('n', strtotime($startDate));
        $currentYear = date('Y', strtotime($startDate));
        
        // Son 6 ay için veri oluştur (gerçek veriler yerine örnek)
        for ($i = 0; $i < 6; $i++) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            
            if ($month <= 0) {
                $month += 12;
                $year--;
            }
            
            $monthName = '';
            switch($month) {
                case 1: $monthName = 'Ocak'; break;
                case 2: $monthName = 'Şubat'; break;
                case 3: $monthName = 'Mart'; break;
                case 4: $monthName = 'Nisan'; break;
                case 5: $monthName = 'Mayıs'; break;
                case 6: $monthName = 'Haziran'; break;
                case 7: $monthName = 'Temmuz'; break;
                case 8: $monthName = 'Ağustos'; break;
                case 9: $monthName = 'Eylül'; break;
                case 10: $monthName = 'Ekim'; break;
                case 11: $monthName = 'Kasım'; break;
                case 12: $monthName = 'Aralık'; break;
            }
            
            $monthlyCosts[] = (object)[
                'month_name' => $monthName,
                'year' => $year,
                'fuel_cost' => 0,
                'maintenance_cost' => 0,
                'total_cost' => 0
            ];
        }
        
        foreach ($vehicles as $vehicle) {
            $fuelCost = 0;
            $fuelRecords = $this->fuelModel->getFuelRecordsByVehicle($vehicle->id);
            
            if (!empty($fuelRecords)) {
                foreach ($fuelRecords as $record) {
                    if (isset($record->date) && strtotime($record->date) >= strtotime($startDate) && strtotime($record->date) <= strtotime($endDate)) {
                        $fuelCost += $record->cost;
                        
                        // Detaylı kayıt ekle
                        $costDetails[] = (object)[
                            'date' => $record->date,
                            'vehicle_id' => $vehicle->id,
                            'plate_number' => $vehicle->plate_number,
                            'cost_type' => 'fuel',
                            'description' => $record->fuel_type . ' - ' . $record->amount . ' lt',
                            'amount' => $record->cost
                        ];
                    }
                }
            }
            
            $maintenanceCostByVehicle = 0;
            $maintenanceRecordsByVehicle = $this->maintenanceModel->getMaintenanceRecordsByVehicle($vehicle->id);
            
            if (!empty($maintenanceRecordsByVehicle)) {
                foreach ($maintenanceRecordsByVehicle as $record) {
                    if (isset($record->start_date) && strtotime($record->start_date) >= strtotime($startDate) && strtotime($record->start_date) <= strtotime($endDate)) {
                        $maintenanceCostByVehicle += $record->cost;
                        
                        // Detaylı kayıt ekle
                        $costDetails[] = (object)[
                            'date' => $record->start_date,
                            'vehicle_id' => $vehicle->id,
                            'plate_number' => $vehicle->plate_number,
                            'cost_type' => 'maintenance',
                            'description' => $record->maintenance_type . ' - ' . $record->description,
                            'amount' => $record->cost
                        ];
                    }
                }
            }
            
            $vehicleCosts[] = (object)[
                'vehicle_id' => $vehicle->id,
                'plate_number' => $vehicle->plate_number,
                'fuel_cost' => $fuelCost,
                'maintenance_cost' => $maintenanceCostByVehicle,
                'total_cost' => $fuelCost + $maintenanceCostByVehicle
            ];
        }
        
        // Tarihe göre sırala
        usort($costDetails, function($a, $b) {
            return strtotime($b->date) - strtotime($a->date);
        });
        
        // Maliyet özeti
        $costSummary = [
            'total_cost' => $fuelCostTotal + $maintenanceCost,
            'fuel_cost' => $fuelCostTotal,
            'maintenance_cost' => $maintenanceCost
        ];
        
        $data = [
            'title' => 'Maliyet Raporu',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'vehicle_id' => $vehicleId,
                'cost_type' => $costType
            ],
            'vehicles' => $vehicles,
            'fuelStats' => $fuelStats,
            'maintenanceCost' => $maintenanceCost,
            'vehicleCosts' => $vehicleCosts,
            'costDetails' => $costDetails,
            'monthlyCosts' => $monthlyCosts,
            'costSummary' => $costSummary
        ];
        
        $this->view('reports/costs', $data);
    }

    // Özel rapor oluşturma
    public function custom() {
        // Form gönderildi mi kontrol et
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Form verilerini al
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $reportType = trim($_POST['report_type']);
            $startDate = trim($_POST['start_date']);
            $endDate = trim($_POST['end_date']);
            $groupBy = isset($_POST['group_by']) ? trim($_POST['group_by']) : '';
            
            // Rapor türüne göre işlem yap
            switch ($reportType) {
                case 'fuel':
                    redirect('reports/fuel?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                case 'maintenance':
                    redirect('reports/maintenance?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                case 'assignments':
                    redirect('reports/assignments?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                case 'costs':
                    redirect('reports/costs?start_date=' . $startDate . '&end_date=' . $endDate);
                    break;
                default:
                    flash('report_message', 'Geçersiz rapor türü', 'alert alert-danger');
                    redirect('reports/custom');
                    break;
            }
        } else {
            $data = [
                'title' => 'Özel Rapor Oluştur'
            ];
            
            $this->view('reports/custom', $data);
        }
    }
}
