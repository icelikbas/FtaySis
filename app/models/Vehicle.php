<?php
class Vehicle {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm araçları getir
    public function getVehicles() {
        $this->db->query('SELECT * FROM vehicles ORDER BY created_at DESC');
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Tüm araçları getir (alternatif isim)
    public function getAllVehicles() {
        return $this->getVehicles();
    }

    // ID'ye göre araç getir
    public function getVehicleById($id) {
        $this->db->query('SELECT * FROM vehicles WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Plaka numarasına göre araç bul
    public function findVehicleByPlate($plate_number) {
        $this->db->query('SELECT * FROM vehicles WHERE plate_number = :plate_number');
        $this->db->bind(':plate_number', $plate_number);

        $row = $this->db->single();

        // Araç bulundu mu kontrolü
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Yeni araç ekle
    public function addVehicle($data) {
        $this->db->query('INSERT INTO vehicles (plate_number, brand, model, year, vehicle_type, status, company_id, 
                           order_number, equipment_number, fixed_asset_number, cost_center, production_site, 
                           inspection_date, traffic_insurance_agency, traffic_insurance_date, 
                           casco_insurance_agency, casco_insurance_date, work_site) 
                          VALUES (:plate_number, :brand, :model, :year, :vehicle_type, :status, :company_id,
                           :order_number, :equipment_number, :fixed_asset_number, :cost_center, :production_site,
                           :inspection_date, :traffic_insurance_agency, :traffic_insurance_date,
                           :casco_insurance_agency, :casco_insurance_date, :work_site)');
        
        // Parametreleri bağla
        $this->db->bind(':plate_number', $data['plate_number']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':year', $data['year']);
        $this->db->bind(':vehicle_type', $data['vehicle_type']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':order_number', $data['order_number'] ?? null);
        $this->db->bind(':equipment_number', $data['equipment_number'] ?? null);
        $this->db->bind(':fixed_asset_number', $data['fixed_asset_number'] ?? null);
        $this->db->bind(':cost_center', $data['cost_center'] ?? null);
        $this->db->bind(':production_site', $data['production_site'] ?? null);
        $this->db->bind(':inspection_date', $data['inspection_date'] ?? null);
        $this->db->bind(':traffic_insurance_agency', $data['traffic_insurance_agency'] ?? null);
        $this->db->bind(':traffic_insurance_date', $data['traffic_insurance_date'] ?? null);
        $this->db->bind(':casco_insurance_agency', $data['casco_insurance_agency'] ?? null);
        $this->db->bind(':casco_insurance_date', $data['casco_insurance_date'] ?? null);
        $this->db->bind(':work_site', $data['work_site'] ?? null);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Araç güncelle
    public function updateVehicle($data) {
        $this->db->query('UPDATE vehicles 
                          SET plate_number = :plate_number, 
                              brand = :brand, 
                              model = :model, 
                              year = :year, 
                              vehicle_type = :vehicle_type, 
                              status = :status,
                              company_id = :company_id,
                              order_number = :order_number,
                              equipment_number = :equipment_number,
                              fixed_asset_number = :fixed_asset_number,
                              cost_center = :cost_center,
                              production_site = :production_site,
                              inspection_date = :inspection_date,
                              traffic_insurance_agency = :traffic_insurance_agency,
                              traffic_insurance_date = :traffic_insurance_date,
                              casco_insurance_agency = :casco_insurance_agency,
                              casco_insurance_date = :casco_insurance_date,
                              work_site = :work_site
                          WHERE id = :id');
        
        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':plate_number', $data['plate_number']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':year', $data['year']);
        $this->db->bind(':vehicle_type', $data['vehicle_type']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':order_number', $data['order_number'] ?? null);
        $this->db->bind(':equipment_number', $data['equipment_number'] ?? null);
        $this->db->bind(':fixed_asset_number', $data['fixed_asset_number'] ?? null);
        $this->db->bind(':cost_center', $data['cost_center'] ?? null);
        $this->db->bind(':production_site', $data['production_site'] ?? null);
        $this->db->bind(':inspection_date', $data['inspection_date'] ?? null);
        $this->db->bind(':traffic_insurance_agency', $data['traffic_insurance_agency'] ?? null);
        $this->db->bind(':traffic_insurance_date', $data['traffic_insurance_date'] ?? null);
        $this->db->bind(':casco_insurance_agency', $data['casco_insurance_agency'] ?? null);
        $this->db->bind(':casco_insurance_date', $data['casco_insurance_date'] ?? null);
        $this->db->bind(':work_site', $data['work_site'] ?? null);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Araç sil
    public function deleteVehicle($id) {
        $this->db->query('DELETE FROM vehicles WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Durum tipine göre araç sayısını getir
    public function getVehicleCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as total FROM vehicles WHERE status = :status');
        $this->db->bind(':status', $status);

        $row = $this->db->single();
        return $row->total;
    }

    // Toplam araç sayısını getir
    public function getTotalVehicleCount() {
        $this->db->query('SELECT COUNT(*) as total FROM vehicles');
        $row = $this->db->single();
        return $row->total;
    }

    // Standart araç tiplerini döndür
    public static function getStandardVehicleTypes() {
        return [
            'Otomobil', 'Kamyonet', 'Kamyon', 'Otobüs', 'Damperli Kamyon', 
            'Beton Santrali', 'Silindir', 'Loder', 'Bekoloder', 'Ekskavatör', 
            'Akaryakıt Tankı', 'Mikser', 'Çekici', 'Arazöz', 
            'Mobil Beton Pompası', 'Jeneratör', 'İş Makinesi'
        ];
    }

    // Araç tipine göre araç sayısını getir
    public function getVehicleCountByType() {
        $this->db->query('SELECT vehicle_type, COUNT(*) as count FROM vehicles GROUP BY vehicle_type');
        return $this->db->resultSet();
    }

    // Filtrelere göre araçları getir
    public function getVehiclesByFilters($status = '', $type = '', $year = 0, $startDate = '', $endDate = '') {
        $sql = 'SELECT * FROM vehicles WHERE 1=1';
        
        if (!empty($status)) {
            $sql .= ' AND status = :status';
        }
        
        if (!empty($type)) {
            $sql .= ' AND vehicle_type = :type';
        }
        
        if ($year > 0) {
            $sql .= ' AND year = :year';
        }
        
        if (!empty($startDate) && !empty($endDate)) {
            $sql .= ' AND created_at BETWEEN :start_date AND :end_date';
        }
        
        $sql .= ' ORDER BY created_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        
        if (!empty($type)) {
            $this->db->bind(':type', $type);
        }
        
        if ($year > 0) {
            $this->db->bind(':year', $year);
        }
        
        if (!empty($startDate) && !empty($endDate)) {
            $this->db->bind(':start_date', $startDate);
            $this->db->bind(':end_date', $endDate);
        }
        
        return $this->db->resultSet();
    }

    // Şirkete bağlı araçları getir
    public function getVehiclesByCompany($company_id) {
        $this->db->query('SELECT * FROM vehicles WHERE company_id = :company_id ORDER BY plate_number ASC');
        $this->db->bind(':company_id', $company_id);
        
        return $this->db->resultSet();
    }
    
    // Son eklenen araçları getir
    public function getRecentVehicles($limit = 5) {
        $this->db->query('
            SELECT v.*, c.name as company_name
            FROM vehicles v
            LEFT JOIN companies c ON v.company_id = c.id
            ORDER BY v.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Araç tipleri dağılımını getir
    public function getVehicleTypeDistribution() {
        $this->db->query('
            SELECT 
                vehicle_type,
                COUNT(*) as count
            FROM 
                vehicles
            GROUP BY 
                vehicle_type
            ORDER BY 
                count DESC
        ');
        
        return $this->db->resultSet();
    }
    
    // Yaklaşan muayeneleri getir
    public function getUpcomingInspections($days = 30) {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime('+' . $days . ' days'));
        
        $this->db->query('SELECT v.*, c.company_name as company_name 
        FROM vehicles v 
        LEFT JOIN companies c ON v.company_id = c.id 
        WHERE v.inspection_date IS NOT NULL 
        AND v.inspection_date BETWEEN :today AND :future_date 
        ORDER BY v.inspection_date ASC');
        
        $this->db->bind(':today', $today);
        $this->db->bind(':future_date', $futureDate);
        
        return $this->db->resultSet();
    }
} 