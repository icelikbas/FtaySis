<?php
class Assignment {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm görevlendirmeleri getir
    public function getAssignments() {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model, 
                           CONCAT(d.name, " ", d.surname) as driver_name
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           JOIN drivers d ON va.driver_id = d.id
                           ORDER BY va.created_at DESC');
        
        $results = $this->db->resultSet();

        return $results;
    }

    // ID'ye göre görevlendirme getir
    public function getAssignmentById($id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand as vehicle_brand, v.model as vehicle_model, v.vehicle_type,
                           d.name as driver_name, d.surname as driver_surname,
                           d.phone as driver_phone, d.primary_license_type as driver_license
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           JOIN drivers d ON va.driver_id = d.id
                           WHERE va.id = :id');
        
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();

        return $row;
    }

    // Araç ID'sine göre görevlendirmeleri getir
    public function getAssignmentsByVehicle($vehicle_id) {
        $this->db->query('SELECT va.*, CONCAT(d.name, " ", d.surname) as driver_name
                           FROM vehicle_assignments va 
                           JOIN drivers d ON va.driver_id = d.id
                           WHERE va.vehicle_id = :vehicle_id
                           ORDER BY va.created_at DESC');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Araç ID'sine göre aktif görevlendirmeleri getir
    public function getActiveAssignmentsByVehicle($vehicle_id) {
        $this->db->query('SELECT va.*, CONCAT(d.name, " ", d.surname) as driver_name
                           FROM vehicle_assignments va 
                           JOIN drivers d ON va.driver_id = d.id
                           WHERE va.vehicle_id = :vehicle_id AND va.status = "Aktif"');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Şoför ID'sine göre aktif görevlendirmeleri getir
    public function getActiveAssignmentsByDriver($driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           WHERE va.driver_id = :driver_id AND va.status = "Aktif"');
        
        $this->db->bind(':driver_id', $driver_id);
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Şoför ID'sine göre görevlendirmeleri getir
    public function getAssignmentsByDriver($driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           WHERE va.driver_id = :driver_id
                           ORDER BY va.created_at DESC');
        
        $this->db->bind(':driver_id', $driver_id);
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Araç ve şoför ID'sine göre görevlendirmeleri getir
    public function getAssignmentsByVehicleAndDriver($vehicle_id, $driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model,
                           CONCAT(d.name, " ", d.surname) as driver_name
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           JOIN drivers d ON va.driver_id = d.id
                           WHERE va.vehicle_id = :vehicle_id AND va.driver_id = :driver_id
                           ORDER BY va.created_at DESC');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        $this->db->bind(':driver_id', $driver_id);
        
        $results = $this->db->resultSet();

        return $results;
    }

    // Tüm görevlendirmeleri getir (alternatif isim)
    public function getAllAssignments() {
        return $this->getAssignments();
    }

    // Araç ID'sine göre görevlendirme sayısını getir
    public function getAssignmentCountByVehicle($vehicle_id) {
        $this->db->query('SELECT COUNT(*) as total 
                         FROM vehicle_assignments 
                         WHERE vehicle_id = :vehicle_id');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        
        $row = $this->db->single();
        return $row->total;
    }

    // Şoför ID'sine göre görevlendirme sayısını getir
    public function getAssignmentCountByDriver($driver_id) {
        $this->db->query('SELECT COUNT(*) as total 
                         FROM vehicle_assignments 
                         WHERE driver_id = :driver_id');
        
        $this->db->bind(':driver_id', $driver_id);
        
        $row = $this->db->single();
        return $row->total;
    }

    // Şoför ID'sine göre aktif görevlendirmeyi getir (tekil)
    public function getActiveAssignmentByDriver($driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           WHERE va.driver_id = :driver_id AND va.status = "Aktif"
                           ORDER BY va.created_at DESC
                           LIMIT 1');
        
        $this->db->bind(':driver_id', $driver_id);
        
        $row = $this->db->single();
        return $row;
    }

    // Araç ID'sine göre aktif görevlendirmeyi getir (tekil)
    public function getActiveAssignmentByVehicle($vehicle_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model,
                           CONCAT(d.name, " ", d.surname) as driver_name
                           FROM vehicle_assignments va 
                           JOIN vehicles v ON va.vehicle_id = v.id
                           JOIN drivers d ON va.driver_id = d.id
                           WHERE va.vehicle_id = :vehicle_id AND va.status = "Aktif"
                           ORDER BY va.created_at DESC
                           LIMIT 1');
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        
        $row = $this->db->single();
        return $row;
    }

    // Yeni görevlendirme ekle
    public function addAssignment($data) {
        $this->db->query('INSERT INTO vehicle_assignments (vehicle_id, driver_id, start_date, end_date, status, notes) 
                          VALUES (:vehicle_id, :driver_id, :start_date, :end_date, :status, :notes)');
        
        // Parametreleri bağla
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Görevlendirme güncelle
    public function updateAssignment($data) {
        $this->db->query('UPDATE vehicle_assignments 
                          SET vehicle_id = :vehicle_id, 
                              driver_id = :driver_id, 
                              start_date = :start_date, 
                              end_date = :end_date, 
                              status = :status, 
                              notes = :notes 
                          WHERE id = :id');
        
        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Görevlendirme sil
    public function deleteAssignment($id) {
        $this->db->query('DELETE FROM vehicle_assignments WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Görevlendirme durumunu güncelle
    public function updateAssignmentStatus($id, $status, $end_date = null) {
        if ($end_date) {
            $this->db->query('UPDATE vehicle_assignments SET status = :status, end_date = :end_date WHERE id = :id');
            $this->db->bind(':end_date', $end_date);
        } else {
            $this->db->query('UPDATE vehicle_assignments SET status = :status WHERE id = :id');
        }
        
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Durum tipine göre görevlendirme sayısını getir
    public function getAssignmentCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as total FROM vehicle_assignments WHERE status = :status');
        $this->db->bind(':status', $status);

        $row = $this->db->single();
        return $row->total;
    }

    // Toplam görevlendirme sayısını getir
    public function getTotalAssignmentCount() {
        $this->db->query('SELECT COUNT(*) as total FROM vehicle_assignments');
        $row = $this->db->single();
        return $row->total;
    }

    // Aktif şoförler için seçim listesi al
    public function getActiveDriversForSelect() {
        $this->db->query('SELECT id, name, surname, identity_number 
                          FROM drivers WHERE status = "Aktif" ORDER BY name');
        
        return $this->db->resultSet();
    }

    // Aktif araçlar için seçim listesi al
    public function getActiveVehiclesForSelect() {
        $this->db->query('SELECT id, plate_number, brand, model, vehicle_type 
                          FROM vehicles WHERE status = "Aktif" ORDER BY plate_number');
        
        return $this->db->resultSet();
    }

    // Araca atanmış aktif bir görevlendirme var mı kontrol et
    public function checkVehicleHasActiveAssignment($vehicle_id, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->query('SELECT COUNT(*) as count FROM vehicle_assignments 
                             WHERE vehicle_id = :vehicle_id AND status = "Aktif" AND id != :exclude_id');
            $this->db->bind(':exclude_id', $exclude_id);
        } else {
            $this->db->query('SELECT COUNT(*) as count FROM vehicle_assignments 
                             WHERE vehicle_id = :vehicle_id AND status = "Aktif"');
        }
        
        $this->db->bind(':vehicle_id', $vehicle_id);
        $row = $this->db->single();
        
        return ($row->count > 0);
    }

    // Şoföre atanmış aktif bir görevlendirme var mı kontrol et
    public function checkDriverHasActiveAssignment($driver_id, $exclude_id = null) {
        if ($exclude_id) {
            $this->db->query('SELECT COUNT(*) as count FROM vehicle_assignments 
                             WHERE driver_id = :driver_id AND status = "Aktif" AND id != :exclude_id');
            $this->db->bind(':exclude_id', $exclude_id);
        } else {
            $this->db->query('SELECT COUNT(*) as count FROM vehicle_assignments 
                             WHERE driver_id = :driver_id AND status = "Aktif"');
        }
        
        $this->db->bind(':driver_id', $driver_id);
        $row = $this->db->single();
        
        return ($row->count > 0);
    }

    // En çok görevlendirilen araçları getir
    public function getTopVehicles($limit = 5) {
        $this->db->query('
            SELECT v.id as vehicle_id, v.plate_number, v.brand, v.model, 
                   COUNT(va.id) as assignment_count,
                   (SELECT status FROM vehicle_assignments 
                    WHERE vehicle_id = v.id 
                    ORDER BY created_at DESC 
                    LIMIT 1) as current_status
            FROM vehicles v
            JOIN vehicle_assignments va ON v.id = va.vehicle_id
            GROUP BY v.id, v.plate_number, v.brand, v.model
            ORDER BY assignment_count DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
    
    // En çok görevlendirilen sürücüleri getir
    public function getTopDrivers($limit = 5) {
        $this->db->query('
            SELECT d.id as driver_id, CONCAT(d.name, " ", d.surname) as driver_name, 
                   COUNT(va.id) as assignment_count,
                   (SELECT status FROM vehicle_assignments 
                    WHERE driver_id = d.id 
                    ORDER BY created_at DESC 
                    LIMIT 1) as current_status
            FROM drivers d
            JOIN vehicle_assignments va ON d.id = va.driver_id
            GROUP BY d.id, d.name, d.surname
            ORDER BY assignment_count DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Aktif görevlendirmeleri getir
    public function getActiveAssignments() {
        $this->db->query('
            SELECT va.*, v.plate_number, CONCAT(d.name, " ", d.surname) as driver_name
            FROM vehicle_assignments va
            LEFT JOIN vehicles v ON va.vehicle_id = v.id
            LEFT JOIN drivers d ON va.driver_id = d.id
            WHERE va.status = "Aktif"
            ORDER BY va.start_date DESC
        ');
        
        return $this->db->resultSet();
    }

    // Belirli bir aracın mevcut sürücüsünü getir
    public function getCurrentDriverForVehicle($vehicleId) {
        $this->db->query('
            SELECT id, vehicle_id, driver_id
            FROM vehicle_assignments 
            WHERE vehicle_id = :vehicle_id 
            AND status = "Aktif" 
            ORDER BY start_date DESC, id DESC 
            LIMIT 1
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->single();
    }
} 