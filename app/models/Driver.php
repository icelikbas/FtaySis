<?php
class Driver {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm şoförleri getir
    public function getDrivers() {
        $this->db->query('SELECT * FROM drivers ORDER BY created_at DESC');
        
        $results = $this->db->resultSet();

        return $results;
    }
    
    // Tüm şoförleri getir (alternatif isim)
    public function getAllDrivers() {
        return $this->getDrivers();
    }

    // ID'ye göre şoför getir
    public function getDriverById($id) {
        $this->db->query('SELECT * FROM drivers WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // TC Kimlik numarasına göre şoför bul
    public function findDriverByIdentityNumber($identity_number) {
        $this->db->query('SELECT * FROM drivers WHERE identity_number = :identity_number');
        $this->db->bind(':identity_number', $identity_number);

        $row = $this->db->single();

        // Şoför bulundu mu kontrolü
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Ehliyet numarasına göre şoför bul
    public function findDriverByLicenseNumber($license_number) {
        $this->db->query('SELECT * FROM drivers WHERE license_number = :license_number');
        $this->db->bind(':license_number', $license_number);

        $row = $this->db->single();

        // Şoför bulundu mu kontrolü
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Yeni şoför ekle
    public function addDriver($data) {
        try {
            // Veritabanı tablo yapısını kontrol et
            $this->db->query('DESCRIBE drivers');
            $columns = $this->db->resultSet();
            
            // Mevcut sütun adlarını al
            $columnNames = [];
            foreach ($columns as $column) {
                $columnNames[] = $column->Field;
            }
            
            // Temel sütunlar için SQL sorgusu oluştur
            $sql = 'INSERT INTO drivers (name, surname, identity_number, license_number, primary_license_type, 
                                        phone, email, address, status, company_id';
            
            // İsteğe bağlı tarih alanları
            if (isset($data['license_issue_date']) && !empty($data['license_issue_date'])) {
                $sql .= ', license_issue_date';
            }
            if (isset($data['license_expiry_date']) && !empty($data['license_expiry_date'])) {
                $sql .= ', license_expiry_date';
            }
            
            // Ek alanlar - birth_date alanı için kontrol eklendi
            if (isset($data['birth_date']) && !empty($data['birth_date'])) {
                $sql .= ', birth_date';
            }
            if (isset($data['notes']) && !empty($data['notes'])) {
                $sql .= ', notes';
            }
            
            $sql .= ') VALUES (:name, :surname, :identity_number, :license_number, :primary_license_type, 
                               :phone, :email, :address, :status, :company_id';
            
            // İsteğe bağlı tarih değerleri
            if (isset($data['license_issue_date']) && !empty($data['license_issue_date'])) {
                $sql .= ', :license_issue_date';
            }
            if (isset($data['license_expiry_date']) && !empty($data['license_expiry_date'])) {
                $sql .= ', :license_expiry_date';
            }
            
            // Ek değerler - birth_date değeri için kontrol eklendi
            if (isset($data['birth_date']) && !empty($data['birth_date'])) {
                $sql .= ', :birth_date';
            }
            if (isset($data['notes']) && !empty($data['notes'])) {
                $sql .= ', :notes';
            }
            
            $sql .= ')';
            
            $this->db->query($sql);
            
            // Temel parametreleri bağla
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':surname', $data['surname']);
            $this->db->bind(':identity_number', $data['identity_number']);
            $this->db->bind(':license_number', $data['license_number']);
            $this->db->bind(':primary_license_type', $data['primary_license_type']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':email', isset($data['email']) ? $data['email'] : '');
            $this->db->bind(':address', isset($data['address']) ? $data['address'] : '');
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':company_id', $data['company_id'] ? $data['company_id'] : null);
            
            // İsteğe bağlı tarih parametrelerini bağla
            if (isset($data['license_issue_date']) && !empty($data['license_issue_date'])) {
                // Tarihi YYYY-MM-DD formatına dönüştür
                $formattedIssueDate = $this->formatDate($data['license_issue_date']);
                $this->db->bind(':license_issue_date', $formattedIssueDate);
            }
            if (isset($data['license_expiry_date']) && !empty($data['license_expiry_date'])) {
                // Tarihi YYYY-MM-DD formatına dönüştür
                $formattedExpiryDate = $this->formatDate($data['license_expiry_date']);
                $this->db->bind(':license_expiry_date', $formattedExpiryDate);
            }
            
            // Ek parametreleri bağla - birth_date parametre bağlama kontrolü düzeltildi
            if (isset($data['birth_date']) && !empty($data['birth_date'])) {
                // Tarihi YYYY-MM-DD formatına dönüştür
                $formattedBirthDate = $this->formatDate($data['birth_date']);
                $this->db->bind(':birth_date', $formattedBirthDate);
            }
            if (isset($data['notes']) && !empty($data['notes'])) {
                $this->db->bind(':notes', html_entity_decode($data['notes']));
            }
            
            // Çalıştır
            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Hata durumunda basit sorguya dön
            $this->db->query('INSERT INTO drivers (name, surname, identity_number, license_number, primary_license_type, license_issue_date, license_expiry_date, phone, email, address, status, company_id, birth_date, notes) 
                              VALUES (:name, :surname, :identity_number, :license_number, :primary_license_type, :license_issue_date, :license_expiry_date, :phone, :email, :address, :status, :company_id, :birth_date, :notes)');
            
            // Parametreleri bağla
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':surname', $data['surname']);
            $this->db->bind(':identity_number', $data['identity_number']);
            $this->db->bind(':license_number', $data['license_number']);
            $this->db->bind(':primary_license_type', $data['primary_license_type']);
            
            // Tarih formatı düzelt
            $licenseIssueDate = isset($data['license_issue_date']) && !empty($data['license_issue_date']) ? 
                                $this->formatDate($data['license_issue_date']) : null;
            $licenseExpiryDate = isset($data['license_expiry_date']) && !empty($data['license_expiry_date']) ? 
                                 $this->formatDate($data['license_expiry_date']) : null;
            $birthDate = isset($data['birth_date']) && !empty($data['birth_date']) ? 
                         $this->formatDate($data['birth_date']) : null;
                                 
            $this->db->bind(':license_issue_date', $licenseIssueDate);
            $this->db->bind(':license_expiry_date', $licenseExpiryDate);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':email', $data['email'] ?? '');
            $this->db->bind(':address', $data['address'] ?? '');
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':company_id', $data['company_id'] ? $data['company_id'] : null);
            $this->db->bind(':birth_date', $birthDate);
            $this->db->bind(':notes', isset($data['notes']) ? html_entity_decode($data['notes']) : '');

            // Çalıştır
            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        }
    }
    
    // Tarih formatı kontrolü ve dönüşümü
    private function formatDate($dateString) {
        if (empty($dateString) || $dateString == '0000-00-00' || $dateString == '0000-00-00 00:00:00') {
            return null;
        }
        
        // Tarih zaten YYYY-MM-DD formatında mı?
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }
        
        // Diğer formatlardan dönüşüm
        $timestamp = strtotime($dateString);
        if ($timestamp === false) {
            return null;
        }
        
        return date('Y-m-d', $timestamp);
    }

    // Şoför güncelle
    public function updateDriver($data) {
        // Önce tabloyu ve sütunları kontrol edelim
        try {
            // SQL sorgusunu dinamik olarak oluşturalım
            $sql = 'UPDATE drivers 
                    SET name = :name, 
                        surname = :surname, 
                        identity_number = :identity_number, 
                        phone = :phone, 
                        email = :email, 
                        address = :address, 
                        license_number = :license_number,
                        company_id = :company_id,
                        birth_date = :birth_date,
                        license_issue_date = :license_issue_date, 
                        license_expiry_date = :license_expiry_date,
                        primary_license_type = :primary_license_type,
                        notes = :notes,
                        status = :status
                    WHERE id = :id';
            
            $this->db->query($sql);
            
            // Temel parametreleri bağla
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':surname', $data['surname']);
            $this->db->bind(':identity_number', $data['identity_number']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':email', isset($data['email']) ? $data['email'] : '');
            $this->db->bind(':address', isset($data['address']) ? $data['address'] : '');
            $this->db->bind(':license_number', $data['license_number']);
            $this->db->bind(':company_id', !empty($data['company_id']) ? $data['company_id'] : null);
            $this->db->bind(':primary_license_type', $data['primary_license_type']);
            $this->db->bind(':status', $data['status']);
            
            // Tarih alanlarını düzelt
            $birthDate = isset($data['birth_date']) && !empty($data['birth_date']) ? 
                         $this->formatDate($data['birth_date']) : null;
            $licenseIssueDate = isset($data['license_issue_date']) && !empty($data['license_issue_date']) ? 
                                $this->formatDate($data['license_issue_date']) : null;
            $licenseExpiryDate = isset($data['license_expiry_date']) && !empty($data['license_expiry_date']) ? 
                                 $this->formatDate($data['license_expiry_date']) : null;
                                  
            $this->db->bind(':birth_date', $birthDate);
            $this->db->bind(':license_issue_date', $licenseIssueDate);
            $this->db->bind(':license_expiry_date', $licenseExpiryDate);
            
            // Notes alanını düzelt
            $this->db->bind(':notes', isset($data['notes']) ? html_entity_decode($data['notes']) : '');
            
            // Çalıştır
            return $this->db->execute();
        } catch (Exception $e) {
            // Hata durumunda basitleştirilmiş sorgu kullan
            error_log('Şoför güncelleme hatası: ' . $e->getMessage());
            return false;
        }
    }

    // Şoför sil
    public function deleteDriver($id) {
        $this->db->query('DELETE FROM drivers WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Durum tipine göre şoför sayısını getir
    public function getDriverCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as total FROM drivers WHERE status = :status');
        $this->db->bind(':status', $status);

        $row = $this->db->single();
        return $row->total;
    }

    // Toplam şoför sayısını getir
    public function getTotalDriverCount() {
        $this->db->query('SELECT COUNT(*) as total FROM drivers');
        $row = $this->db->single();
        return $row->total;
    }

    // Ehliyet tipine göre şoför sayısını getir
    public function getDriverCountByLicenseType() {
        $this->db->query('SELECT primary_license_type, COUNT(*) as count FROM drivers GROUP BY primary_license_type');
        return $this->db->resultSet();
    }

    // Şoföre atanmış araçları getir
    public function getDriverAssignments($driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model, v.vehicle_type 
                         FROM vehicle_assignments va 
                         JOIN vehicles v ON va.vehicle_id = v.id
                         WHERE va.driver_id = :driver_id
                         ORDER BY va.start_date DESC');
        
        $this->db->bind(':driver_id', $driver_id);
        
        return $this->db->resultSet();
    }
    
    // Sürücünün aktif görevini getir
    public function getActiveAssignment($driver_id) {
        $this->db->query('SELECT va.*, v.plate_number, v.brand, v.model, v.vehicle_type 
                         FROM vehicle_assignments va 
                         JOIN vehicles v ON va.vehicle_id = v.id
                         WHERE va.driver_id = :driver_id AND va.status = "Aktif"
                         ORDER BY va.start_date DESC
                         LIMIT 1');
        
        $this->db->bind(':driver_id', $driver_id);
        
        return $this->db->single();
    }
    
    // Filtrelere göre sürücüleri getir
    public function getDriversByFilters($status = '', $licenseType = '', $assignmentStatus = null) {
        $sql = 'SELECT d.* FROM drivers d WHERE 1=1';
        
        if (!empty($status)) {
            $sql .= ' AND d.status = :status';
        }
        
        if (!empty($licenseType)) {
            $sql .= ' AND d.primary_license_type = :license_type';
        }
        
        if ($assignmentStatus !== null) {
            if ($assignmentStatus) {
                // Aktif görevlendirmesi olan sürücüler
                $sql .= ' AND d.id IN (SELECT DISTINCT driver_id FROM vehicle_assignments WHERE status = "Aktif")';
            } else {
                // Aktif görevlendirmesi olmayan sürücüler
                $sql .= ' AND d.id NOT IN (SELECT DISTINCT driver_id FROM vehicle_assignments WHERE status = "Aktif")';
            }
        }
        
        $sql .= ' ORDER BY d.name ASC';
        
        $this->db->query($sql);
        
        if (!empty($status)) {
            $this->db->bind(':status', $status);
        }
        
        if (!empty($licenseType)) {
            $this->db->bind(':license_type', $licenseType);
        }
        
        return $this->db->resultSet();
    }

    // Sürücünün tüm ehliyet sınıflarını getir
    public function getDriverLicenseTypes($driverId) {
        $this->db->query('
            SELECT lt.*, dl.id, 
                   dl.issue_date, 
                   dl.expiry_date, 
                   dl.notes
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id
            ORDER BY lt.code
        ');
        
        $this->db->bind(':driver_id', $driverId);
        
        return $this->db->resultSet();
    }
    
    // Sürücü için yeni ehliyet sınıfı ekle
    public function addDriverLicense($data) {
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
    
    // Sürücünün ehliyet sınıfını güncelle
    public function updateDriverLicense($data) {
        $this->db->query('
            UPDATE driver_licenses
            SET license_type_id = :license_type_id,
                issue_date = :issue_date,
                expiry_date = :expiry_date,
                notes = :notes
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':license_type_id', $data['license_type_id']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':expiry_date', $data['expiry_date']);
        $this->db->bind(':notes', $data['notes']);
        
        return $this->db->execute();
    }
    
    // Sürücünün ehliyet sınıfını sil
    public function deleteDriverLicense($id) {
        $this->db->query('DELETE FROM driver_licenses WHERE id = :id');
        
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Ehliyet sınıfı bilgisini ID'ye göre getir
    public function getDriverLicenseById($id) {
        $this->db->query('
            SELECT dl.*, lt.code, lt.name, lt.description
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Tüm ehliyet tiplerini getir (select kutusu için)
    public function getAllLicenseTypes() {
        $this->db->query('SELECT * FROM license_types ORDER BY code');
        
        return $this->db->resultSet();
    }
    
    // Sürücünün belirli bir ehliyet tipine sahip olup olmadığını kontrol et
    public function checkDriverHasLicenseType($driverId, $licenseCode) {
        $this->db->query('
            SELECT COUNT(*) as count
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id AND lt.code = :license_code
        ');
        
        $this->db->bind(':driver_id', $driverId);
        $this->db->bind(':license_code', $licenseCode);
        
        $row = $this->db->single();
        
        return ($row->count > 0);
    }
    
    // Sürücünün ehliyet sınıflarını virgülle ayrılmış liste olarak getir
    public function getDriverLicenseTypesAsString($driverId) {
        $licenseTypes = $this->getDriverLicenseTypes($driverId);
        
        if (empty($licenseTypes)) {
            return '';
        }
        
        $codes = [];
        foreach ($licenseTypes as $license) {
            $codes[] = $license->code;
        }
        
        return implode(', ', $codes);
    }

    // Sürücünün belirli ehliyet tipine ait detaylı bilgilerini getir
    public function getDriverLicenseByType($driverId, $licenseTypeId) {
        $this->db->query('
            SELECT dl.*
            FROM driver_licenses dl
            WHERE dl.driver_id = :driver_id AND dl.license_type_id = :license_type_id
        ');
        
        $this->db->bind(':driver_id', $driverId);
        $this->db->bind(':license_type_id', $licenseTypeId);
        
        return $this->db->single();
    }

    // Sürücünün belirli bir ehliyet tipine sahip olup olmadığını statik olarak kontrol et (view'lardan çağırmak için)
    public static function hasLicenseType($driverId, $licenseCode) {
        $db = new Database;
        $db->query('
            SELECT COUNT(*) as count
            FROM driver_licenses dl
            JOIN license_types lt ON dl.license_type_id = lt.id
            WHERE dl.driver_id = :driver_id AND lt.code = :license_code
        ');
        
        $db->bind(':driver_id', $driverId);
        $db->bind(':license_code', $licenseCode);
        
        $row = $db->single();
        
        return ($row->count > 0);
    }

    // Sürücünün birincil ehliyet tipini getir
    public function getPrimaryLicenseType($driver_id) {
        $this->db->query('SELECT primary_license_type FROM drivers WHERE id = :id');
        $this->db->bind(':id', $driver_id);
        
        $row = $this->db->single();
        
        return $row ? $row->primary_license_type : '';
    }
    
    // Şirkete bağlı sürücüleri getir
    public function getDriversByCompany($company_id) {
        $this->db->query('SELECT * FROM drivers WHERE company_id = :company_id ORDER BY name ASC');
        $this->db->bind(':company_id', $company_id);
        
        return $this->db->resultSet();
    }
    
    // Sadece ehliyet geçerlilik tarihini güncelle
    public function updateDriverExpiryDate($data) {
        $this->db->query('UPDATE drivers SET license_expiry_date = :license_expiry_date WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':license_expiry_date', $data['license_expiry_date']);
        
        return $this->db->execute();
    }
    
    // Son eklenen sürücüleri getir
    public function getRecentDrivers($limit = 5) {
        $this->db->query('
            SELECT d.*, c.name as company_name
            FROM drivers d
            LEFT JOIN companies c ON d.company_id = c.id
            ORDER BY d.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Aktif sürücü sayısını getir
    public function getActiveDriverCount() {
        $this->db->query('SELECT COUNT(*) as count FROM drivers WHERE status = "Aktif"');
        $row = $this->db->single();
        return $row->count;
    }
    
    // Ehliyet tipleri dağılımını getir
    public function getLicenseTypeDistribution() {
        $this->db->query('
            SELECT 
                primary_license_type as license_type,
                COUNT(*) as count
            FROM 
                drivers
            GROUP BY 
                primary_license_type
            ORDER BY 
                count DESC
        ');
        
        return $this->db->resultSet();
    }
} 