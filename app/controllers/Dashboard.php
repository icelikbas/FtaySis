<?php
class Dashboard extends Controller {
    private $vehicleModel;
    private $driverModel;
    private $companyModel;
    private $fuelModel;
    private $maintenanceModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->vehicleModel = $this->model('Vehicle');
        $this->driverModel = $this->model('Driver');
        $this->companyModel = $this->model('Company');
        $this->fuelModel = $this->model('FuelModel');
        $this->maintenanceModel = $this->model('MaintenanceModel');
    }

    public function index() {
        // Toplam araç ve sürücü sayıları
        $totalVehicles = $this->vehicleModel->getTotalVehicleCount();
        $activeDrivers = $this->driverModel->getActiveDriverCount();
        $totalCompanies = $this->companyModel->getTotalCompaniesCount();

        // Yaklaşan bakımlar ve muayeneler
        $upcomingMaintenances = $this->maintenanceModel->getUpcomingMaintenances();
        
        // Yaklaşan muayeneler için hata kontrolü
        try {
            $upcomingInspections = $this->vehicleModel->getUpcomingInspections();
        } catch (Exception $e) {
            // Hata oluşursa boş array kullan
            $upcomingInspections = [];
        }
        
        $upcomingMaintenanceCount = count($upcomingMaintenances);

        // Yakıt tüketim verileri
        try {
            $fuelConsumptionMonths = $this->fuelModel->getMonthlyFuelConsumption();
        } catch (Exception $e) {
            $fuelConsumptionMonths = [];
        }
        
        try {
            $fuelConsumptionByType = $this->fuelModel->getFuelConsumptionByType();
        } catch (Exception $e) {
            $fuelConsumptionByType = [];
        }

        // Araç tipleri dağılımı
        try {
            $vehicleTypeDistribution = $this->vehicleModel->getVehicleTypeDistribution();
        } catch (Exception $e) {
            $vehicleTypeDistribution = [];
        }

        // Son eklenen kayıtlar
        try {
            $recentCompanies = $this->companyModel->getRecentCompanies();
        } catch (Exception $e) {
            $recentCompanies = [];
        }
        
        try {
            $recentVehicles = $this->vehicleModel->getRecentVehicles();
        } catch (Exception $e) {
            $recentVehicles = [];
        }
        
        try {
            $recentDrivers = $this->driverModel->getRecentDrivers();
        } catch (Exception $e) {
            $recentDrivers = [];
        }
        
        try {
            $recentMaintenance = $this->maintenanceModel->getRecentMaintenanceRecords();
        } catch (Exception $e) {
            $recentMaintenance = [];
        }

        $data = [
            'title' => 'Dashboard',
            'total_vehicles' => $totalVehicles,
            'active_drivers' => $activeDrivers,
            'total_companies' => $totalCompanies,
            'upcoming_maintenance_count' => $upcomingMaintenanceCount,
            'upcoming_maintenance' => $upcomingMaintenances,
            'upcoming_inspections' => $upcomingInspections,
            'fuel_consumption_months' => $fuelConsumptionMonths,
            'fuel_consumption_by_type' => $fuelConsumptionByType,
            'vehicle_type_distribution' => $vehicleTypeDistribution,
            'recent_companies' => $recentCompanies,
            'recent_vehicles' => $recentVehicles,
            'recent_drivers' => $recentDrivers,
            'recent_maintenance' => $recentMaintenance
        ];

        $this->view('dashboard/index', $data);
    }
} 