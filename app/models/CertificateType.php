<?php
class CertificateType {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm sertifika türlerini getir
    public function getCertificateTypes() {
        $this->db->query('SELECT * FROM certificate_types ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // ID'ye göre sertifika türü getir
    public function getCertificateTypeById($id) {
        $this->db->query('SELECT * FROM certificate_types WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Belge adına göre belge tipini getir
    public function getCertificateTypeByName($name) {
        $this->db->query('SELECT * FROM certificate_types WHERE name = :name');
        
        $this->db->bind(':name', $name);
        
        return $this->db->single();
    }

    // Yeni sertifika türü ekle
    public function addCertificateType($data) {
        $this->db->query('INSERT INTO certificate_types (name, description) VALUES (:name, :description)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Sertifika türünü güncelle
    public function updateCertificateType($data) {
        $this->db->query('UPDATE certificate_types SET name = :name, description = :description WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Sertifika türünü sil
    public function deleteCertificateType($id) {
        $this->db->query('DELETE FROM certificate_types WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Belirli belge tipine sahip sürücüleri getir
    public function getDriversWithCertificateType($certificateTypeId) {
        $this->db->query('
            SELECT DISTINCT d.*
            FROM drivers d
            JOIN driver_certificates dc ON d.id = dc.driver_id
            WHERE dc.certificate_type_id = :certificate_type_id
            ORDER BY d.name, d.surname
        ');
        
        $this->db->bind(':certificate_type_id', $certificateTypeId);
        
        return $this->db->resultSet();
    }

    // Toplam belge tipi sayısını getir
    public function getTotalCertificateTypeCount() {
        $this->db->query('SELECT COUNT(*) as total FROM certificate_types');
        $row = $this->db->single();
        return $row->total;
    }
    
    // En çok kullanılan belge tiplerini getir
    public function getMostCommonCertificateTypes($limit = 5) {
        $this->db->query('
            SELECT ct.name, COUNT(dc.id) as usage_count
            FROM certificate_types ct
            JOIN driver_certificates dc ON ct.id = dc.certificate_type_id
            GROUP BY ct.id
            ORDER BY usage_count DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Sertifika türü var mı kontrol et
    public function certificateTypeExists($name) {
        $this->db->query('SELECT COUNT(*) as count FROM certificate_types WHERE name = :name');
        $this->db->bind(':name', $name);
        $row = $this->db->single();
        return $row->count > 0;
    }
} 