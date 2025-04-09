<?php
class LicenseType {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm ehliyet tiplerini getir
    public function getLicenseTypes() {
        $this->db->query('
            SELECT lt.*, 
                   (SELECT COUNT(*) FROM driver_licenses WHERE license_type_id = lt.id) as driver_count
            FROM license_types lt 
            ORDER BY lt.code
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre ehliyet tipini getir
    public function getLicenseTypeById($id) {
        $this->db->query('SELECT * FROM license_types WHERE id = :id');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Koda göre ehliyet tipini getir
    public function getLicenseTypeByCode($code) {
        $this->db->query('SELECT * FROM license_types WHERE code = :code');
        
        $this->db->bind(':code', $code);
        
        return $this->db->single();
    }

    // Yeni ehliyet tipi ekle
    public function addLicenseType($data) {
        $this->db->query('
            INSERT INTO license_types (code, name, description)
            VALUES (:code, :name, :description)
        ');
        
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        return $this->db->execute();
    }

    // Ehliyet tipini güncelle
    public function updateLicenseType($data) {
        $this->db->query('
            UPDATE license_types
            SET code = :code,
                name = :name,
                description = :description
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        return $this->db->execute();
    }

    // Ehliyet tipini sil
    public function deleteLicenseType($id) {
        $this->db->query('DELETE FROM license_types WHERE id = :id');
        
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Belirli ehliyet tipine sahip sürücüleri getir
    public function getDriversByLicenseType($licenseTypeId) {
        $this->db->query('
            SELECT DISTINCT d.*
            FROM drivers d
            JOIN driver_licenses dl ON d.id = dl.driver_id
            WHERE dl.license_type_id = :license_type_id
            ORDER BY d.name, d.surname
        ');
        
        $this->db->bind(':license_type_id', $licenseTypeId);
        
        return $this->db->resultSet();
    }

    // Belirli bir koda sahip ehliyet tipine sahip sürücüleri getir
    public function getDriversByLicenseCode($licenseCode) {
        $this->db->query('
            SELECT d.*, CONCAT(d.name, " ", d.surname) as full_name, lt.code, lt.name as license_name
            FROM drivers d
            JOIN driver_licenses dl ON d.id = dl.driver_id
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE lt.code = :license_code
            ORDER BY d.name, d.surname
        ');
        
        $this->db->bind(':license_code', $licenseCode);
        
        return $this->db->resultSet();
    }

    // Toplam ehliyet tipi sayısını getir
    public function getTotalLicenseTypeCount() {
        $this->db->query('SELECT COUNT(*) as total FROM license_types');
        $row = $this->db->single();
        return $row->total;
    }
    
    // En çok kullanılan ehliyet tiplerini getir
    public function getMostCommonLicenseTypes($limit = 5) {
        $this->db->query('
            SELECT lt.code, lt.name, COUNT(dl.id) as driver_count
            FROM license_types lt
            JOIN driver_licenses dl ON lt.id = dl.license_type_id
            GROUP BY lt.id
            ORDER BY driver_count DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }
} 