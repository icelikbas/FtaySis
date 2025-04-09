<?php
/**
 * License Model
 * Sürücülere ait ehliyet kayıtlarını yönetir
 */
class License {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Sürücünün tüm ehliyet sınıflarını getirir
     * @param int $driver_id Sürücü ID
     * @return array Ehliyet kayıtları
     */
    public function getDriverLicenses($driver_id) {
        $this->db->query('
            SELECT dl.*, lt.code, lt.name, lt.description 
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id
            ORDER BY lt.code
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        
        return $this->db->resultSet();
    }

    /**
     * Ehliyet kaydını ID'ye göre getirir
     * @param int $id Ehliyet kaydı ID
     * @return object Ehliyet kaydı
     */
    public function getLicenseById($id) {
        $this->db->query('
            SELECT dl.*, lt.code, lt.name as license_name, lt.description
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    /**
     * Sürücünün belirli bir ehliyet sınıfına sahip olup olmadığını kontrol eder
     * @param int $driver_id Sürücü ID
     * @param int $license_type_id Ehliyet sınıfı ID
     * @return object|false Ehliyet kaydı veya false
     */
    public function getDriverLicenseByType($driver_id, $license_type_id) {
        $this->db->query('
            SELECT * FROM driver_licenses 
            WHERE driver_id = :driver_id AND license_type_id = :license_type_id
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        $this->db->bind(':license_type_id', $license_type_id);
        
        $row = $this->db->single();
        
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    /**
     * Yeni ehliyet kaydı ekler
     * @param array $data Ehliyet verileri
     * @return bool İşlem başarılı mı
     */
    public function addLicense($data) {
        $this->db->query('
            INSERT INTO driver_licenses (driver_id, license_type_id, issue_date, expiry_date, notes)
            VALUES (:driver_id, :license_type_id, :issue_date, :expiry_date, :notes)
        ');
        
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':license_type_id', $data['license_type_id']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }

    /**
     * Ehliyet kaydını günceller
     * @param array $data Ehliyet verileri
     * @return bool İşlem başarılı mı
     */
    public function updateLicense($data) {
        $this->db->query('
            UPDATE driver_licenses
            SET license_type_id = :license_type_id,
                issue_date = :issue_date,
                expiry_date = :expiry_date,
                notes = :notes
            WHERE id = :id AND driver_id = :driver_id
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':license_type_id', $data['license_type_id']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }

    /**
     * Ehliyet kaydını siler
     * @param int $id Ehliyet kaydı ID
     * @return bool İşlem başarılı mı
     */
    public function deleteLicense($id) {
        $this->db->query('DELETE FROM driver_licenses WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Sürücünün sahip olduğu tüm ehliyet kodlarını getirir
     * @param int $driver_id Sürücü ID
     * @return array Ehliyet kodları
     */
    public function getDriverLicenseCodes($driver_id) {
        $this->db->query('
            SELECT lt.code
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        
        $result = $this->db->resultSet();
        $codes = [];
        
        foreach($result as $item) {
            $codes[] = $item->code;
        }
        
        return $codes;
    }
    
    /**
     * Sürücünün sahip olduğu ve süresi yaklaşan veya geçmiş ehliyetlerini getirir
     * @param int $driver_id Sürücü ID
     * @param int $days Gün sayısı
     * @return array Ehliyet kayıtları
     */
    public function getExpiringLicenses($driver_id, $days = 90) {
        $this->db->query('
            SELECT dl.*, lt.code, lt.name, lt.description 
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id 
              AND dl.expiry_date IS NOT NULL
              AND dl.expiry_date <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
            ORDER BY dl.expiry_date
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        $this->db->bind(':days', $days);
        
        return $this->db->resultSet();
    }
} 