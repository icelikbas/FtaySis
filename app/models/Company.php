<?php
class Company {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm şirketleri getir
    public function getAllCompanies() {
        $this->db->query('
            SELECT
                c.*,
                (SELECT COUNT(*) FROM drivers WHERE company_id = c.id) as driver_count,
                (SELECT COUNT(*) FROM vehicles WHERE company_id = c.id) as vehicle_count
            FROM
                companies c
            ORDER BY 
                c.created_at DESC
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre şirket getir
    public function getCompanyById($id) {
        $this->db->query('
            SELECT
                c.*,
                (SELECT COUNT(*) FROM drivers WHERE company_id = c.id) as driver_count,
                (SELECT COUNT(*) FROM vehicles WHERE company_id = c.id) as vehicle_count
            FROM
                companies c
            WHERE
                c.id = :id
        ');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // Şirket adına göre ara
    public function findCompanyByName($name) {
        $this->db->query('SELECT * FROM companies WHERE company_name = :name');
        $this->db->bind(':name', $name);

        $row = $this->db->single();

        return $row;
    }

    // Vergi numarasına göre şirket bul
    public function findCompanyByTaxNumber($taxNumber) {
        $this->db->query('SELECT * FROM companies WHERE tax_number = :tax_number');
        $this->db->bind(':tax_number', $taxNumber);

        $row = $this->db->single();

        return $row;
    }

    // Şirket ekle
    public function addCompany($data) {
        $this->db->query('
            INSERT INTO companies (
                company_name, tax_office, tax_number, address, 
                phone, email, logo_url, status
            ) VALUES (
                :company_name, :tax_office, :tax_number, :address, 
                :phone, :email, :logo_url, :status
            )
        ');
        
        // Parametreleri bağla
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':tax_office', $data['tax_office']);
        $this->db->bind(':tax_number', $data['tax_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':logo_url', $data['logo_url']);
        $this->db->bind(':status', $data['status']);

        // Çalıştır
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Şirket güncelle
    public function updateCompany($data) {
        $this->db->query('
            UPDATE companies 
            SET 
                company_name = :company_name, 
                tax_office = :tax_office, 
                tax_number = :tax_number, 
                address = :address, 
                phone = :phone, 
                email = :email, 
                logo_url = :logo_url, 
                status = :status
            WHERE 
                id = :id
        ');
        
        // Parametreleri bağla
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':tax_office', $data['tax_office']);
        $this->db->bind(':tax_number', $data['tax_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':logo_url', $data['logo_url']);
        $this->db->bind(':status', $data['status']);

        // Çalıştır
        return $this->db->execute();
    }

    // Şirket sil
    public function deleteCompany($id) {
        $this->db->query('DELETE FROM companies WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        return $this->db->execute();
    }

    // Durum tipine göre şirket sayısını getir
    public function getCompanyCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as total FROM companies WHERE status = :status');
        $this->db->bind(':status', $status);

        $row = $this->db->single();
        return $row->total;
    }

    // Toplam şirket sayısını getir
    public function getTotalCompaniesCount() {
        $this->db->query('SELECT COUNT(*) as count FROM companies');
        $row = $this->db->single();
        return $row->count;
    }

    // Şirketin bağlı kayıtları var mı kontrol et
    public function hasRelatedRecords($id) {
        // Bağlı sürücüler var mı kontrol et
        $this->db->query('SELECT COUNT(*) as count FROM drivers WHERE company_id = :id');
        $this->db->bind(':id', $id);
        $driverCount = $this->db->single()->count;
        
        if ($driverCount > 0) {
            return true;
        }
        
        // Bağlı araçlar var mı kontrol et
        $this->db->query('SELECT COUNT(*) as count FROM vehicles WHERE company_id = :id');
        $this->db->bind(':id', $id);
        $vehicleCount = $this->db->single()->count;
        
        return ($vehicleCount > 0);
    }

    // Aktif şirketler için seçim listesi al
    public function getActiveCompaniesForSelect() {
        $this->db->query('SELECT id, company_name, company_name as name FROM companies WHERE status = "Aktif" ORDER BY company_name');
        
        return $this->db->resultSet();
    }

    // Şirkete ait araçları getir
    public function getCompanyVehicles($company_id) {
        $this->db->query('
            SELECT * FROM vehicles 
            WHERE company_id = :company_id
            ORDER BY plate_number
        ');
        
        $this->db->bind(':company_id', $company_id);
        
        return $this->db->resultSet();
    }

    // Şirkete ait sürücüleri getir
    public function getCompanyDrivers($company_id) {
        $this->db->query('
            SELECT * FROM drivers 
            WHERE company_id = :company_id
            ORDER BY name, surname
        ');
        
        $this->db->bind(':company_id', $company_id);
        
        return $this->db->resultSet();
    }

    // Son eklenen şirketleri getir
    public function getRecentCompanies($limit = 5) {
        $this->db->query('
            SELECT
                c.*,
                (SELECT COUNT(*) FROM drivers WHERE company_id = c.id) as driver_count,
                (SELECT COUNT(*) FROM vehicles WHERE company_id = c.id) as vehicle_count
            FROM
                companies c
            ORDER BY 
                c.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
} 