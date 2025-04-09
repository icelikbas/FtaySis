<?php
class FuelModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm yakıt kayıtlarını al
    public function getFuelRecords() {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id 
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            ORDER BY f.date DESC
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre yakıt kaydını al
    public function getFuelRecordById($id) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f 
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Araca göre yakıt kayıtlarını al
    public function getFuelRecordsByVehicle($vehicleId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.vehicle_id = :vehicle_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }

    // Sürücüye göre yakıt kayıtlarını al
    public function getFuelRecordsByDriver($driverId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.driver_id = :driver_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':driver_id', $driverId);
        
        return $this->db->resultSet();
    }

    // Tanka göre yakıt kayıtlarını al
    public function getFuelRecordsByTank($tankId) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.tank_id = :tank_id
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }

    // Tarihe göre yakıt kayıtlarını al (belirli tarih aralığında)
    public function getFuelRecordsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.date BETWEEN :start_date AND :end_date
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Yakıt kaydı ekle
    public function addFuelRecord($data) {
        try {
            // Transaction başlat
            $this->db->beginTransaction();
            
            // Tarih formatını düzelt
            $date = $data['date'];
            
            // Saati kontrol et ve ekle (varsa)
            if(isset($data['time'])) {
                $date .= ' ' . $data['time'];
            }
            
            // SQL sorgusu
            $this->db->query('INSERT INTO fuel_records (vehicle_id, driver_id, tank_id, fuel_type, amount, cost, km_reading, hour_reading, date, notes, created_at, created_by) 
                            VALUES(:vehicle_id, :driver_id, :tank_id, :fuel_type, :amount, :cost, :km_reading, :hour_reading, :date, :notes, NOW(), :created_by)');
            
            // Bind values
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':driver_id', !empty($data['driver_id']) ? $data['driver_id'] : null);
            $this->db->bind(':tank_id', $data['tank_id']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':cost', $data['cost']);
            $this->db->bind(':km_reading', $data['km_reading']);
            $this->db->bind(':hour_reading', $data['hour_reading']);
            $this->db->bind(':date', $date);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':created_by', $_SESSION['user_id']);
            
            // Sorguyu çalıştır
            $result = $this->db->execute();
            
            if($result) {
                // Tanktan yakıt miktarını azalt
                $tankModel = new FuelTank();
                if($tankModel->updateTankAmount($data['tank_id'], $data['amount'], false)) {
                    // Her şey başarılı, işlemi tamamla
                    $this->db->commit();
                    return true;
                } else {
                    // Tank güncelleme başarısız
                    $this->db->rollback();
                    error_log('Tank güncellemesi başarısız oldu. Tank ID: ' . $data['tank_id'] . ', Miktar: ' . $data['amount']);
                    return false;
                }
            } else {
                // Sorgu başarısız
                $this->db->rollback();
                error_log('Yakıt kaydı ekleme sorgusu başarısız oldu.');
                return false;
            }
        } catch (Exception $e) {
            // Hata oluştu, rollback yap
            try {
                $this->db->rollback();
            } catch (Exception $innerEx) {
                // Transaction zaten yoksa hata oluşabilir, bu hatayı yok sayabiliriz
                error_log('Rollback sırasında hata: ' . $innerEx->getMessage());
            }
            // Hatayı logla
            error_log('Yakıt kaydı eklenirken hata: ' . $e->getMessage());
            throw $e; // Hatayı yukarı ilet
        }
    }

    // Yakıt kaydını güncelle
    public function updateFuelRecord($data) {
        // Önceki kayıt bilgilerini al
        $oldRecord = $this->getFuelRecordById($data['id']);
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Tankları kontrol et
            $tankModel = new FuelTank();
            
            // Eğer tank değiştiyse veya miktar arttıysa
            if ($oldRecord->tank_id != $data['tank_id'] || $data['amount'] > $oldRecord->amount) {
                $checkAmount = $data['amount'];
                
                // Aynı tanksa sadece farkı kontrol et
                if ($oldRecord->tank_id == $data['tank_id']) {
                    $checkAmount = $data['amount'] - $oldRecord->amount;
                }
                
                // Yeterli yakıt var mı kontrol et
                if (!$tankModel->checkTankAmount($data['tank_id'], $checkAmount)) {
                    return false; // Yeterli yakıt yoksa işlemi durdur
                }
            }
            
            $this->db->query('
                UPDATE fuel_records 
                SET vehicle_id = :vehicle_id, 
                    driver_id = :driver_id, 
                    tank_id = :tank_id,
                    fuel_type = :fuel_type, 
                    amount = :amount, 
                    cost = :cost, 
                    km_reading = :km_reading, 
                    hour_reading = :hour_reading,
                    date = :date, 
                    notes = :notes, 
                    updated_at = NOW()
                WHERE id = :id
            ');
            
            // Bağlama işlemleri
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':driver_id', $data['driver_id']);
            $this->db->bind(':tank_id', $data['tank_id']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':cost', $data['cost']);
            $this->db->bind(':km_reading', $data['km_reading']);
            $this->db->bind(':hour_reading', $data['hour_reading']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':notes', $data['notes']);
            
            // Çalıştır
            $updateResult = $this->db->execute();
            
            if($updateResult) {
                // Eğer tank değiştiyse
                if ($oldRecord->tank_id != $data['tank_id']) {
                    // Eski tanka yakıtı geri ekle
                    $tankModel->updateTankAmount($oldRecord->tank_id, $oldRecord->amount, true);
                    // Yeni tanktan yakıtı çıkar
                    $tankModel->updateTankAmount($data['tank_id'], $data['amount'], false);
                } else {
                    // Aynı tank, sadece miktar değişimi
                    $amountDiff = $data['amount'] - $oldRecord->amount;
                    if ($amountDiff != 0) {
                        $tankModel->updateTankAmount($data['tank_id'], abs($amountDiff), ($amountDiff < 0));
                    }
                }
                
                // Transaction başarılı
                $this->db->commit();
                return true;
            }
            
            // Herhangi bir sorun olursa rollback yap
            $this->db->rollback();
            return false;
        } catch (Exception $e) {
            // Hata oluştu, rollback yap
            try {
                $this->db->rollback();
            } catch (Exception $innerEx) {
                // Transaction zaten yoksa hata oluşabilir, bu hatayı yok sayabiliriz
                error_log('Rollback sırasında hata: ' . $innerEx->getMessage());
            }
            // Hatayı logla
            error_log('Yakıt kaydı eklenirken hata: ' . $e->getMessage());
            throw $e; // Hatayı yukarı ilet
        }
    }

    // Yakıt kaydını sil
    public function deleteFuelRecord($id) {
        // Silinecek kayıt bilgilerini al
        $record = $this->getFuelRecordById($id);
        
        if (!$record) {
            return false;
        }
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            $this->db->query('DELETE FROM fuel_records WHERE id = :id');
            $this->db->bind(':id', $id);
            $deleteResult = $this->db->execute();
            
            if($deleteResult) {
                // Tanka yakıtı geri ekle
                $tankModel = new FuelTank();
                $tankUpdateResult = $tankModel->updateTankAmount($record->tank_id, $record->amount, true);
                
                if ($tankUpdateResult) {
                    // Transaction başarılı
                    $this->db->commit();
                    return true;
                }
            }
            
            // Herhangi bir sorun olursa rollback yap
            $this->db->rollback();
            return false;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    // Araç yakıt tüketim özeti
    public function getVehicleFuelConsumption($vehicleId) {
        $this->db->query('
            SELECT 
                SUM(amount) as total_fuel,
                SUM(cost) as total_cost,
                MIN(date) as first_record,
                MAX(date) as last_record,
                COUNT(*) as record_count,
                AVG(cost / amount) as avg_price_per_unit
            FROM fuel_records
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->single();
    }

    // Toplam yakıt istatistikleri
    public function getTotalFuelStats() {
        $this->db->query('
            SELECT 
                SUM(amount) as total_fuel,
                SUM(cost) as total_cost,
                COUNT(DISTINCT vehicle_id) as vehicle_count,
                COUNT(*) as record_count,
                AVG(cost / amount) as avg_price_per_unit
            FROM fuel_records
        ');
        
        return $this->db->single();
    }

    // Belirli tarihler arasındaki yakıt istatistikleri
    public function getFuelStatsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT 
                SUM(amount) as total_fuel,
                SUM(cost) as total_cost,
                COUNT(DISTINCT vehicle_id) as vehicle_count,
                COUNT(*) as record_count,
                AVG(cost / amount) as avg_price_per_unit
            FROM fuel_records
            WHERE date BETWEEN :start_date AND :end_date
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->single();
    }

    // Yakıt tiplerine göre istatistikler
    public function getFuelStatsByType() {
        $this->db->query('
            SELECT 
                fuel_type,
                SUM(amount) as total_amount,
                SUM(cost) as total_cost,
                COUNT(*) as record_count,
                AVG(cost / amount) as avg_price
            FROM fuel_records
            GROUP BY fuel_type
            ORDER BY total_amount DESC
        ');
        
        return $this->db->resultSet();
    }

    // Toplam yakıt kaydı sayısı
    public function getFuelRecordCount() {
        $this->db->query('SELECT COUNT(*) as count FROM fuel_records');
        $row = $this->db->single();
        return $row->count;
    }

    // Araç başına toplam yakıt maliyeti
    public function getTotalFuelCostByVehicle($vehicleId) {
        $this->db->query('
            SELECT SUM(cost) as total_cost
            FROM fuel_records
            WHERE vehicle_id = :vehicle_id
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        $result = $this->db->single();
        return $result ? $result->total_cost : 0;
    }

    // Seçim kutuları için aktif araçlar
    public function getActiveVehiclesForSelect() {
        $this->db->query('
            SELECT id, CONCAT(brand, " ", model, " (", plate_number, ")") as vehicle_name
            FROM vehicles
            WHERE status = "Aktif"
            ORDER BY brand, model
        ');
        
        return $this->db->resultSet();
    }

    // Seçim kutuları için aktif sürücüler
    public function getActiveDriversForSelect() {
        $this->db->query('
            SELECT id, CONCAT(name, " ", surname) as full_name
            FROM drivers
            WHERE status = "Aktif"
            ORDER BY name, surname
        ');
        
        return $this->db->resultSet();
    }

    // Bir araca ait son yakıt kaydını al
    public function getLastFuelRecordByVehicle($vehicleId) {
        $this->db->query('
            SELECT id, vehicle_id, driver_id, tank_id, fuel_type, amount, cost, km_reading, hour_reading, date, notes  
            FROM fuel_records
            WHERE vehicle_id = :vehicle_id
            ORDER BY date DESC, id DESC
            LIMIT 1
        ');
        
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->single();
    }
    
    // Son yakıt birim fiyatını getir
    public function getLastFuelUnitPrice($fuelType) {
        $this->db->query('
            SELECT cost/amount as unit_price
            FROM fuel_records
            WHERE fuel_type = :fuel_type AND amount > 0
            ORDER BY date DESC
            LIMIT 1
        ');
        
        $this->db->bind(':fuel_type', $fuelType);
        
        $result = $this->db->single();
        return $result ? $result->unit_price : 0;
    }
    
    // Aylık yakıt tüketimini getir
    public function getMonthlyFuelConsumption($months = 6) {
        $this->db->query('
            SELECT 
                DATE_FORMAT(date, "%Y-%m") as month,
                DATE_FORMAT(date, "%b %Y") as month_name,
                SUM(amount) as total_amount,
                SUM(cost) as total_cost
            FROM 
                fuel_records
            WHERE 
                date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            GROUP BY 
                DATE_FORMAT(date, "%Y-%m"),
                DATE_FORMAT(date, "%b %Y")
            ORDER BY 
                DATE_FORMAT(date, "%Y-%m") ASC
        ');
        
        $this->db->bind(':months', $months, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    // Yakıt türüne göre tüketimi al
    public function getFuelConsumptionByType($months = 6) {
        $this->db->query('
            SELECT 
                fuel_type,
                SUM(amount) as total_amount,
                SUM(cost) as total_cost
            FROM 
                fuel_records
            WHERE 
                date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            GROUP BY 
                fuel_type
            ORDER BY 
                total_amount DESC
        ');
        
        $this->db->bind(':months', $months, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    // Yakıt türüne göre kayıtları getir
    public function getFuelRecordsByType($fuelType) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.fuel_type = :fuel_type
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Tarih aralığı ve yakıt türüne göre kayıtları getir
    public function getFuelRecordsByDateRangeAndType($startDate, $endDate, $fuelType) {
        $this->db->query('
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE f.date BETWEEN :start_date AND :end_date
            AND f.fuel_type = :fuel_type
            ORDER BY f.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Gelişmiş filtreleme için kayıtları getir
    public function getFilteredFuelRecords($filters) {
        $sql = '
            SELECT f.*, v.plate_number, v.brand, v.model, CONCAT(d.name, " ", d.surname) as driver_name, t.name as tank_name,
                   t.current_amount as tank_current_amount, t.capacity as tank_capacity, t.type as tank_type,
                   t.fuel_type as tank_fuel_type
            FROM fuel_records f
            JOIN vehicles v ON f.vehicle_id = v.id
            LEFT JOIN drivers d ON f.driver_id = d.id
            JOIN fuel_tanks t ON f.tank_id = t.id
            WHERE 1=1
        ';
        
        $params = [];
        
        // Araç filtresi
        if (!empty($filters['vehicle_id'])) {
            $sql .= ' AND f.vehicle_id = :vehicle_id';
            $params[':vehicle_id'] = $filters['vehicle_id'];
        }
        
        // Sürücü filtresi
        if (!empty($filters['driver_id'])) {
            $sql .= ' AND f.driver_id = :driver_id';
            $params[':driver_id'] = $filters['driver_id'];
        }
        
        // Tank filtresi
        if (!empty($filters['tank_id'])) {
            $sql .= ' AND f.tank_id = :tank_id';
            $params[':tank_id'] = $filters['tank_id'];
        }
        
        // Yakıt türü filtresi
        if (!empty($filters['fuel_type'])) {
            $sql .= ' AND f.fuel_type = :fuel_type';
            $params[':fuel_type'] = $filters['fuel_type'];
        }
        
        // Tarih aralığı filtresi
        if (!empty($filters['start_date'])) {
            $sql .= ' AND f.date >= :start_date';
            $params[':start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= ' AND f.date <= :end_date';
            $params[':end_date'] = $filters['end_date'];
        }
        
        // Sıralama
        $sql .= ' ORDER BY f.date DESC';
        
        $this->db->query($sql);
        
        // Parametreleri bağla
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->resultSet();
    }

    // Yakıt türlerini getir
    public function getFuelTypes() {
        return ['Dizel', 'Benzin']; // Desteklenen yakıt türleri
    }

    // Araçların yakıt tüketimi
    public function getVehicleFuelConsumptionSummary() {
        $this->db->query('
            SELECT 
                v.id as vehicle_id,
                v.plate_number,
                v.brand,
                v.model,
                SUM(CASE WHEN MONTH(f.date) = MONTH(CURRENT_DATE()) AND YEAR(f.date) = YEAR(CURRENT_DATE()) THEN f.amount ELSE 0 END) as current_month_amount,
                SUM(CASE WHEN MONTH(f.date) = MONTH(CURRENT_DATE()) AND YEAR(f.date) = YEAR(CURRENT_DATE()) THEN f.cost ELSE 0 END) as current_month_cost,
                MAX(f.fuel_type) as fuel_type,
                COUNT(f.id) as total_records,
                SUM(f.amount) as total_amount,
                SUM(f.cost) as total_cost,
                MAX(f.date) as last_refueling_date
            FROM 
                vehicles v
            LEFT JOIN 
                fuel_records f ON v.id = f.vehicle_id
            WHERE 
                v.status = "Aktif"
            GROUP BY 
                v.id, v.plate_number, v.brand, v.model
            ORDER BY 
                current_month_amount DESC, total_amount DESC
        ');
        
        return $this->db->resultSet();
    }

    // Bir araç için son kullanılan sürücüyü bul
    public function getLastDriverForVehicle($vehicleId) {
        $this->db->query("SELECT driver_id FROM fuel_records 
                          WHERE vehicle_id = :vehicle_id AND driver_id IS NOT NULL 
                          ORDER BY date DESC LIMIT 1");
        $this->db->bind(':vehicle_id', $vehicleId);
        
        $row = $this->db->single();
        
        return $row;
    }

    // Yakıt türü için son birim fiyatını getir
    public function getLastUnitPriceForFuelType($fuelType) {
        // Önce yakıt alımlarından son birim fiyatını kontrol et
        $this->db->query("SELECT unit_price FROM fuel_purchases 
                          WHERE fuel_type = :fuel_type AND unit_price > 0
                          ORDER BY date DESC LIMIT 1");
        $this->db->bind(':fuel_type', $fuelType);
        
        $row = $this->db->single();
        
        if($row) {
            return $row->unit_price;
        }
        
        // Yakıt alımı bulunamazsa, yakıt kayıtlarından hesapla
        $this->db->query("SELECT amount, cost FROM fuel_records 
                          WHERE fuel_type = :fuel_type AND amount > 0 AND cost > 0
                          ORDER BY date DESC LIMIT 1");
        $this->db->bind(':fuel_type', $fuelType);
        
        $row = $this->db->single();
        
        if($row && $row->amount > 0) {
            return $row->cost / $row->amount;
        }
        
        return 0;
    }
} 