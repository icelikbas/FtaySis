<?php
class MaintenanceModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm bakım kayıtlarını al
    public function getMaintenanceRecords() {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id 
            ORDER BY m.start_date DESC
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre bakım kaydını al
    public function getMaintenanceRecordById($id) {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model, u.name as user_name
            FROM maintenance_records m 
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            LEFT JOIN users u ON m.created_by = u.id
            WHERE m.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Araca göre bakım kayıtlarını al
    public function getMaintenanceRecordsByVehicle($vehicleId) {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.vehicle_id = :vehicle_id
            ORDER BY m.start_date DESC
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }

    // Bakım türüne göre kayıtları al
    public function getMaintenanceRecordsByType($type) {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.maintenance_type = :type
            ORDER BY m.start_date DESC
        ');
        
        $this->db->bind(':type', $type);
        
        return $this->db->resultSet();
    }

    // Tarihe göre bakım kayıtlarını al (belirli tarih aralığında)
    public function getMaintenanceRecordsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.start_date BETWEEN :start_date AND :end_date
            ORDER BY m.start_date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Bakım kaydı ekle
    public function addMaintenanceRecord($data) {
        $this->db->query('
            INSERT INTO maintenance_records (vehicle_id, maintenance_type, description, cost, km_reading, start_date, end_date, status, notes, created_by, service_provider, next_maintenance_date, next_maintenance_km)
            VALUES (:vehicle_id, :maintenance_type, :description, :cost, :km_reading, :start_date, :end_date, :status, :notes, :created_by, :service_provider, :next_maintenance_date, :next_maintenance_km)
        ');
        
        // Bağlama işlemleri
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':maintenance_type', $data['maintenance_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':km_reading', $data['km_reading']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', !empty($data['end_date']) ? $data['end_date'] : null);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
        $this->db->bind(':created_by', $_SESSION['user_id']);
        $this->db->bind(':service_provider', !empty($data['service_provider']) ? $data['service_provider'] : null);
        $this->db->bind(':next_maintenance_date', !empty($data['next_maintenance_date']) ? $data['next_maintenance_date'] : null);
        $this->db->bind(':next_maintenance_km', !empty($data['next_maintenance_km']) ? $data['next_maintenance_km'] : null);
        
        // Çalıştır
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Bakım kaydını güncelle
    public function updateMaintenanceRecord($data) {
        $this->db->query('
            UPDATE maintenance_records 
            SET vehicle_id = :vehicle_id, 
                maintenance_type = :maintenance_type, 
                description = :description, 
                cost = :cost, 
                km_reading = :km_reading, 
                start_date = :start_date, 
                end_date = :end_date,
                status = :status, 
                notes = :notes, 
                service_provider = :service_provider,
                next_maintenance_date = :next_maintenance_date,
                next_maintenance_km = :next_maintenance_km,
                updated_at = NOW()
            WHERE id = :id
        ');
        
        // Bağlama işlemleri
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':maintenance_type', $data['maintenance_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':km_reading', $data['km_reading']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', !empty($data['end_date']) ? $data['end_date'] : null);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
        $this->db->bind(':service_provider', !empty($data['service_provider']) ? $data['service_provider'] : null);
        $this->db->bind(':next_maintenance_date', !empty($data['next_maintenance_date']) ? $data['next_maintenance_date'] : null);
        $this->db->bind(':next_maintenance_km', !empty($data['next_maintenance_km']) ? $data['next_maintenance_km'] : null);
        
        // Çalıştır
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Bakım kaydını sil
    public function deleteMaintenanceRecord($id) {
        $this->db->query('DELETE FROM maintenance_records WHERE id = :id');
        
        $this->db->bind(':id', $id);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Yaklaşan bakımları al
    public function getUpcomingMaintenances($daysThreshold = 30, $kmThreshold = 1000) {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+{$daysThreshold} days"));
        
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.status != "Tamamlandı"
            ORDER BY m.start_date DESC
            LIMIT 5
        ');
        
        return $this->db->resultSet();
    }

    // Geçmiş bakımları kontrol et
    public function getOverdueMaintenances() {
        $today = date('Y-m-d');
        
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            WHERE m.start_date < :today AND m.status != "Tamamlandı"
            ORDER BY m.start_date ASC
        ');
        
        $this->db->bind(':today', $today);
        
        return $this->db->resultSet();
    }

    // Araç başına toplam bakım maliyeti
    public function getTotalMaintenanceCostByVehicle($vehicleId) {
        $this->db->query('
            SELECT SUM(cost) as total_cost
            FROM maintenance_records
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        $result = $this->db->single();
        return $result->total_cost;
    }

    // Bakım türlerine göre istatistikler
    public function getMaintenanceStatsByType() {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as record_count,
                SUM(cost) as total_cost,
                AVG(cost) as avg_cost
            FROM maintenance_records
            GROUP BY maintenance_type
            ORDER BY record_count DESC
        ');
        
        return $this->db->resultSet();
    }

    // Toplam bakım istatistikleri
    public function getTotalMaintenanceStats() {
        $this->db->query('
            SELECT 
                COUNT(*) as record_count,
                SUM(cost) as total_cost,
                AVG(cost) as avg_cost,
                COUNT(DISTINCT vehicle_id) as vehicle_count
            FROM maintenance_records
        ');
        
        return $this->db->single();
    }

    // Toplam bakım kaydı sayısı
    public function getMaintenanceRecordCount() {
        $this->db->query('SELECT COUNT(*) as count FROM maintenance_records');
        $row = $this->db->single();
        return $row->count;
    }

    // Son bakım kayıtlarını getir
    public function getRecentMaintenanceRecords($limit = 5) {
        $this->db->query('
            SELECT m.*, v.plate_number, v.brand, v.model, c.name as company_name
            FROM maintenance_records m
            LEFT JOIN vehicles v ON m.vehicle_id = v.id
            LEFT JOIN companies c ON v.company_id = c.id
            ORDER BY m.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Bakım türlerine göre dağılımı getir
    public function getMaintenanceTypeDistribution() {
        $this->db->query('
            SELECT 
                maintenance_type,
                COUNT(*) as count,
                SUM(cost) as total_cost
            FROM 
                maintenance_records
            GROUP BY 
                maintenance_type
            ORDER BY 
                count DESC
        ');
        
        return $this->db->resultSet();
    }

    // Aktif araçları select için getir
    public function getActiveVehiclesForSelect() {
        $this->db->query('
            SELECT v.id, v.plate_number, CONCAT(v.brand, " ", v.model) as vehicle_name
            FROM vehicles v
            WHERE v.status = "Aktif" OR v.status = "Bakımda"
            ORDER BY v.plate_number ASC
        ');
        
        return $this->db->resultSet();
    }
    
    // Araç bakım durumunu güncelle
    public function updateVehicleMaintenanceStatus($vehicle_id, $status) {
        $this->db->query('UPDATE vehicles SET status = :status WHERE id = :vehicle_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':vehicle_id', $vehicle_id);
        return $this->db->execute();
    }
    
    // Bir araç için belirli bir kayıt dışındaki aktif bakımları getir
    public function getActiveMaintenancesForVehicleExcept($vehicle_id, $record_id) {
        $this->db->query('
            SELECT * FROM maintenance_records 
            WHERE vehicle_id = :vehicle_id 
            AND id != :record_id
            AND (status = "Devam Ediyor" OR status = "Planlandı")
        ');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        $this->db->bind(':record_id', $record_id);
        
        return $this->db->resultSet();
    }
} 