<?php
class DriverCertificate {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Sürücünün tüm sertifikalarını getir
    public function getCertificatesByDriver($driver_id) {
        $this->db->query('
            SELECT dc.*, ct.name as certificate_type_name 
            FROM driver_certificates dc
            JOIN certificate_types ct ON dc.certificate_type_id = ct.id
            WHERE dc.driver_id = :driver_id
            ORDER BY dc.expiry_date ASC
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        return $this->db->resultSet();
    }

    // ID'ye göre sertifika getir
    public function getCertificateById($id) {
        $this->db->query('
            SELECT dc.*, ct.name as certificate_type_name 
            FROM driver_certificates dc
            JOIN certificate_types ct ON dc.certificate_type_id = ct.id
            WHERE dc.id = :id
        ');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Süresi geçmiş sertifikaları getir
    public function getExpiredCertificates() {
        $this->db->query('
            SELECT dc.*, ct.name as certificate_type_name,
                   d.name, d.surname
            FROM driver_certificates dc
            JOIN certificate_types ct ON dc.certificate_type_id = ct.id
            JOIN drivers d ON dc.driver_id = d.id
            WHERE dc.expiry_date < CURDATE()
            ORDER BY dc.expiry_date ASC
        ');
        
        return $this->db->resultSet();
    }

    // Yakında süresi dolacak sertifikaları getir (30 gün)
    public function getSoonExpiringCertificates() {
        $this->db->query('
            SELECT dc.*, ct.name as certificate_type_name,
                   d.name, d.surname
            FROM driver_certificates dc
            JOIN certificate_types ct ON dc.certificate_type_id = ct.id
            JOIN drivers d ON dc.driver_id = d.id
            WHERE dc.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY dc.expiry_date ASC
        ');
        
        return $this->db->resultSet();
    }

    // Sürücülerin sertifika istatistiklerini getir
    public function getDriverCertificateStats() {
        $this->db->query('
            SELECT d.*,
                   COUNT(dc.id) as certificate_count,
                   SUM(CASE WHEN dc.expiry_date < CURDATE() THEN 1 ELSE 0 END) as expired_count,
                   SUM(CASE WHEN dc.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as soon_expiring_count
            FROM drivers d
            LEFT JOIN driver_certificates dc ON d.id = dc.driver_id
            GROUP BY d.id
            ORDER BY d.name, d.surname
        ');
        
        return $this->db->resultSet();
    }

    // Yeni sertifika ekle
    public function addCertificate($data) {
        $this->db->query('
            INSERT INTO driver_certificates (
                driver_id, certificate_type_id, certificate_number,
                issue_date, expiry_date, issuing_authority, notes
            ) VALUES (
                :driver_id, :certificate_type_id, :certificate_number,
                :issue_date, :expiry_date, :issuing_authority, :notes
            )
        ');
        
        // Bind values
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':certificate_type_id', $data['certificate_type_id']);
        $this->db->bind(':certificate_number', $data['certificate_number']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':issuing_authority', $data['issuing_authority']);
        $this->db->bind(':notes', $data['notes']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Sertifika güncelle
    public function updateCertificate($data) {
        $this->db->query('
            UPDATE driver_certificates SET
                certificate_type_id = :certificate_type_id,
                certificate_number = :certificate_number,
                issue_date = :issue_date,
                expiry_date = :expiry_date,
                issuing_authority = :issuing_authority,
                notes = :notes
            WHERE id = :id
        ');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':certificate_type_id', $data['certificate_type_id']);
        $this->db->bind(':certificate_number', $data['certificate_number']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':issuing_authority', $data['issuing_authority']);
        $this->db->bind(':notes', $data['notes']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Sertifika sil
    public function deleteCertificate($id) {
        $this->db->query('DELETE FROM driver_certificates WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Sertifika var mı kontrol et
    public function certificateExists($driver_id, $certificate_type_id, $certificate_number) {
        $this->db->query('
            SELECT COUNT(*) as count 
            FROM driver_certificates 
            WHERE driver_id = :driver_id 
            AND certificate_type_id = :certificate_type_id
            AND certificate_number = :certificate_number
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        $this->db->bind(':certificate_type_id', $certificate_type_id);
        $this->db->bind(':certificate_number', $certificate_number);
        
        $row = $this->db->single();
        return $row->count > 0;
    }
} 