<?php
class Insurance extends Controller {
    private $vehicleModel;
    private $companyModel;

    public function __construct() {
        // Oturum kontrolü
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Model sınıflarını yükle
        $this->vehicleModel = $this->model('Vehicle');
        $this->companyModel = $this->model('Company');
    }

    // Sigorta ve Muayene Listesi Ana Sayfası
    public function index() {
        // Tüm araçları getir
        $vehicles = $this->vehicleModel->getVehicles();
        
        // Araçların sigorta ve muayene bilgilerini düzenle
        $vehicleInsuranceData = $this->prepareVehicleInsuranceData($vehicles);
        
        $data = [
            'title' => 'Sigorta ve Muayene Takibi',
            'vehicles' => $vehicleInsuranceData
        ];

        $this->view('insurance/index', $data);
    }
    
    // Yaklaşan Muayeneler
    public function upcomingInspections() {
        // Bugünden itibaren 30 gün içinde muayenesi gelecek araçlar
        $upcomingInspections = $this->vehicleModel->getUpcomingInspections(30);
        
        $data = [
            'title' => 'Yaklaşan Muayeneler',
            'vehicles' => $upcomingInspections,
            'days' => 30
        ];
        
        $this->view('insurance/upcoming_inspections', $data);
    }
    
    // Yaklaşan Trafik Sigortaları
    public function upcomingTrafficInsurance() {
        // Bugünden itibaren 30 gün içinde trafik sigortası bitecek araçlar
        $upcomingInsurances = $this->getUpcomingTrafficInsurance(30);
        
        $data = [
            'title' => 'Yaklaşan Trafik Sigortaları',
            'vehicles' => $upcomingInsurances,
            'days' => 30
        ];
        
        $this->view('insurance/upcoming_traffic', $data);
    }
    
    // Yaklaşan Kasko Sigortaları
    public function upcomingCascoInsurance() {
        // Bugünden itibaren 30 gün içinde kasko sigortası bitecek araçlar
        $upcomingCasco = $this->getUpcomingCascoInsurance(30);
        
        $data = [
            'title' => 'Yaklaşan Kasko Sigortaları',
            'vehicles' => $upcomingCasco,
            'days' => 30
        ];
        
        $this->view('insurance/upcoming_casco', $data);
    }
    
    // Süresi Geçmiş Muayeneler
    public function expiredInspections() {
        // Muayenesi geçmiş araçlar
        $expiredInspections = $this->getExpiredInspections();
        
        $data = [
            'title' => 'Süresi Geçmiş Muayeneler',
            'vehicles' => $expiredInspections
        ];
        
        $this->view('insurance/expired_inspections', $data);
    }
    
    // Süresi Geçmiş Trafik Sigortaları
    public function expiredTrafficInsurance() {
        // Trafik sigortası geçmiş araçlar
        $expiredInsurances = $this->getExpiredTrafficInsurance();
        
        $data = [
            'title' => 'Süresi Geçmiş Trafik Sigortaları',
            'vehicles' => $expiredInsurances
        ];
        
        $this->view('insurance/expired_traffic', $data);
    }
    
    // Süresi Geçmiş Kasko Sigortaları
    public function expiredCascoInsurance() {
        // Kasko sigortası geçmiş araçlar
        $expiredCasco = $this->getExpiredCascoInsurance();
        
        $data = [
            'title' => 'Süresi Geçmiş Kasko Sigortaları',
            'vehicles' => $expiredCasco
        ];
        
        $this->view('insurance/expired_casco', $data);
    }
    
    // Araç detayları
    public function vehicle($id) {
        // Araç bilgisini getir
        $vehicle = $this->vehicleModel->getVehicleById($id);
        
        if (!$vehicle) {
            flash('error', 'Araç bulunamadı');
            redirect('insurance');
        }
        
        // Şirket bilgisini getir
        $company = null;
        if ($vehicle->company_id) {
            $company = $this->companyModel->getCompanyById($vehicle->company_id);
        }
        
        // Kalan gün sayılarını hesapla
        $inspectionDaysLeft = null;
        $trafficDaysLeft = null;
        $cascoDaysLeft = null;
        
        $today = new DateTime();
        
        if (!empty($vehicle->inspection_date)) {
            $inspectionDate = new DateTime($vehicle->inspection_date);
            $diff = $today->diff($inspectionDate);
            $inspectionDaysLeft = $diff->invert ? -$diff->days : $diff->days;
        }
        
        if (!empty($vehicle->traffic_insurance_date)) {
            $trafficDate = new DateTime($vehicle->traffic_insurance_date);
            $diff = $today->diff($trafficDate);
            $trafficDaysLeft = $diff->invert ? -$diff->days : $diff->days;
        }
        
        if (!empty($vehicle->casco_insurance_date)) {
            $cascoDate = new DateTime($vehicle->casco_insurance_date);
            $diff = $today->diff($cascoDate);
            $cascoDaysLeft = $diff->invert ? -$diff->days : $diff->days;
        }
        
        $data = [
            'title' => $vehicle->plate_number . ' Sigorta ve Muayene Bilgileri',
            'vehicle' => $vehicle,
            'company' => $company,
            'inspectionDaysLeft' => $inspectionDaysLeft,
            'trafficDaysLeft' => $trafficDaysLeft,
            'cascoDaysLeft' => $cascoDaysLeft
        ];
        
        $this->view('insurance/vehicle', $data);
    }
    
    // Yardımcı Metodlar
    
    // Araç sigorta ve muayene verilerini düzenle
    private function prepareVehicleInsuranceData($vehicles) {
        $today = new DateTime();
        $vehicleData = [];
        
        foreach ($vehicles as $vehicle) {
            $data = new stdClass();
            $data->id = $vehicle->id;
            $data->plate_number = $vehicle->plate_number;
            $data->brand = $vehicle->brand;
            $data->model = $vehicle->model;
            $data->status = $vehicle->status;
            
            // Şirket bilgisini ekle
            if (!empty($vehicle->company_id)) {
                $company = $this->companyModel->getCompanyById($vehicle->company_id);
                $data->company_name = ($company && isset($company->company_name)) ? $company->company_name : '-';
            } else {
                $data->company_name = '-';
            }
            
            // Muayene bilgisi
            $data->inspection_date = $vehicle->inspection_date;
            $data->inspection_days_left = null;
            
            if (!empty($vehicle->inspection_date)) {
                $inspectionDate = new DateTime($vehicle->inspection_date);
                $diff = $today->diff($inspectionDate);
                $data->inspection_days_left = $diff->invert ? -$diff->days : $diff->days;
            }
            
            // Trafik sigortası bilgisi
            $data->traffic_insurance_date = $vehicle->traffic_insurance_date;
            $data->traffic_insurance_agency = $vehicle->traffic_insurance_agency;
            $data->traffic_days_left = null;
            
            if (!empty($vehicle->traffic_insurance_date)) {
                $trafficDate = new DateTime($vehicle->traffic_insurance_date);
                $diff = $today->diff($trafficDate);
                $data->traffic_days_left = $diff->invert ? -$diff->days : $diff->days;
            }
            
            // Kasko sigortası bilgisi
            $data->casco_insurance_date = $vehicle->casco_insurance_date;
            $data->casco_insurance_agency = $vehicle->casco_insurance_agency;
            $data->casco_days_left = null;
            
            if (!empty($vehicle->casco_insurance_date)) {
                $cascoDate = new DateTime($vehicle->casco_insurance_date);
                $diff = $today->diff($cascoDate);
                $data->casco_days_left = $diff->invert ? -$diff->days : $diff->days;
            }
            
            $vehicleData[] = $data;
        }
        
        return $vehicleData;
    }
    
    // Yaklaşan trafik sigortası olan araçları getir
    private function getUpcomingTrafficInsurance($days = 30) {
        $vehicles = $this->vehicleModel->getVehicles();
        $upcomingInsurances = [];
        $today = new DateTime();
        $future = new DateTime();
        $future->modify('+' . $days . ' days');
        
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->traffic_insurance_date)) {
                $insuranceDate = new DateTime($vehicle->traffic_insurance_date);
                if ($insuranceDate >= $today && $insuranceDate <= $future) {
                    $diff = $today->diff($insuranceDate);
                    $vehicle->days_left = $diff->days;
                    $upcomingInsurances[] = $vehicle;
                }
            }
        }
        
        // Kalan gün sayısına göre sırala
        usort($upcomingInsurances, function($a, $b) {
            return $a->days_left - $b->days_left;
        });
        
        return $upcomingInsurances;
    }
    
    // Yaklaşan kasko sigortası olan araçları getir
    private function getUpcomingCascoInsurance($days = 30) {
        $vehicles = $this->vehicleModel->getVehicles();
        $upcomingCasco = [];
        $today = new DateTime();
        $future = new DateTime();
        $future->modify('+' . $days . ' days');
        
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->casco_insurance_date)) {
                $cascoDate = new DateTime($vehicle->casco_insurance_date);
                if ($cascoDate >= $today && $cascoDate <= $future) {
                    $diff = $today->diff($cascoDate);
                    $vehicle->days_left = $diff->days;
                    $upcomingCasco[] = $vehicle;
                }
            }
        }
        
        // Kalan gün sayısına göre sırala
        usort($upcomingCasco, function($a, $b) {
            return $a->days_left - $b->days_left;
        });
        
        return $upcomingCasco;
    }
    
    // Süresi geçmiş muayenesi olan araçları getir
    private function getExpiredInspections() {
        $vehicles = $this->vehicleModel->getVehicles();
        $expiredInspections = [];
        $today = new DateTime();
        
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->inspection_date)) {
                $inspectionDate = new DateTime($vehicle->inspection_date);
                if ($inspectionDate < $today) {
                    $diff = $today->diff($inspectionDate);
                    $vehicle->days_expired = $diff->days;
                    $expiredInspections[] = $vehicle;
                }
            }
        }
        
        // Gecikme gününe göre sırala (en çok geciken en üstte)
        usort($expiredInspections, function($a, $b) {
            return $b->days_expired - $a->days_expired;
        });
        
        return $expiredInspections;
    }
    
    // Süresi geçmiş trafik sigortası olan araçları getir
    private function getExpiredTrafficInsurance() {
        $vehicles = $this->vehicleModel->getVehicles();
        $expiredInsurances = [];
        $today = new DateTime();
        
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->traffic_insurance_date)) {
                $insuranceDate = new DateTime($vehicle->traffic_insurance_date);
                if ($insuranceDate < $today) {
                    $diff = $today->diff($insuranceDate);
                    $vehicle->days_expired = $diff->days;
                    $expiredInsurances[] = $vehicle;
                }
            }
        }
        
        // Gecikme gününe göre sırala (en çok geciken en üstte)
        usort($expiredInsurances, function($a, $b) {
            return $b->days_expired - $a->days_expired;
        });
        
        return $expiredInsurances;
    }
    
    // Süresi geçmiş kasko sigortası olan araçları getir
    private function getExpiredCascoInsurance() {
        $vehicles = $this->vehicleModel->getVehicles();
        $expiredCasco = [];
        $today = new DateTime();
        
        foreach ($vehicles as $vehicle) {
            if (!empty($vehicle->casco_insurance_date)) {
                $cascoDate = new DateTime($vehicle->casco_insurance_date);
                if ($cascoDate < $today) {
                    $diff = $today->diff($cascoDate);
                    $vehicle->days_expired = $diff->days;
                    $expiredCasco[] = $vehicle;
                }
            }
        }
        
        // Gecikme gününe göre sırala (en çok geciken en üstte)
        usort($expiredCasco, function($a, $b) {
            return $b->days_expired - $a->days_expired;
        });
        
        return $expiredCasco;
    }
} 