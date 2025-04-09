<?php
class FuelPurchase {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm yakıt alımlarını al
    public function getAllPurchases() {
        $this->db->query('
            SELECT p.*, t.name as tank_name, u.name as user_name
            FROM fuel_purchases p
            LEFT JOIN fuel_tanks t ON p.tank_id = t.id
            LEFT JOIN users u ON p.created_by = u.id
            ORDER BY p.date DESC
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre yakıt alımını al
    public function getPurchaseById($id) {
        $this->db->query('
            SELECT p.*, t.name as tank_name, u.name as user_name
            FROM fuel_purchases p
            LEFT JOIN fuel_tanks t ON p.tank_id = t.id
            LEFT JOIN users u ON p.created_by = u.id
            WHERE p.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Tanka göre yakıt alımlarını al
    public function getPurchasesByTank($tankId) {
        $this->db->query('
            SELECT p.*, t.name as tank_name, u.name as user_name
            FROM fuel_purchases p
            LEFT JOIN fuel_tanks t ON p.tank_id = t.id
            LEFT JOIN users u ON p.created_by = u.id
            WHERE p.tank_id = :tank_id
            ORDER BY p.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }

    // Tarihe göre yakıt alımlarını al (belirli tarih aralığında)
    public function getPurchasesByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT p.*, t.name as tank_name, u.name as user_name
            FROM fuel_purchases p
            LEFT JOIN fuel_tanks t ON p.tank_id = t.id
            LEFT JOIN users u ON p.created_by = u.id
            WHERE p.date BETWEEN :start_date AND :end_date
            ORDER BY p.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Yakıt tipine göre alımları getir
    public function getPurchasesByFuelType($fuelType) {
        $this->db->query('
            SELECT p.*, t.name as tank_name, u.name as user_name
            FROM fuel_purchases p
            LEFT JOIN fuel_tanks t ON p.tank_id = t.id
            LEFT JOIN users u ON p.created_by = u.id
            WHERE p.fuel_type = :fuel_type
            ORDER BY p.date DESC
        ');
        
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Yakıt alımı ekle
    public function addPurchase($data) {
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Yakıt alımı kaydını ekle
            $this->db->query('
                INSERT INTO fuel_purchases (supplier_name, fuel_type, amount, cost, unit_price, tank_id, invoice_number, date, notes, created_by)
                VALUES (:supplier_name, :fuel_type, :amount, :cost, :unit_price, :tank_id, :invoice_number, :date, :notes, :created_by)
            ');
            
            // Bağlama işlemleri
            $this->db->bind(':supplier_name', $data['supplier_name']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':cost', $data['cost']);
            $this->db->bind(':unit_price', $data['unit_price']);
            $this->db->bind(':tank_id', $data['tank_id']);
            $this->db->bind(':invoice_number', $data['invoice_number']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':created_by', $data['created_by']);
            
            // Çalıştır
            $purchaseResult = $this->db->execute();
            
            if ($purchaseResult) {
                // Tank güncellemesi için FuelTank modelini kullan
                $tankModel = new FuelTank();
                $tankUpdateResult = $tankModel->updateTankAmount($data['tank_id'], $data['amount'], true);
                
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

    // Yakıt alımını güncelle
    public function updatePurchase($data) {
        // Önceki kayıt bilgilerini al
        $oldRecord = $this->getPurchaseById($data['id']);
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Yakıt alımı kaydını güncelle
            $this->db->query('
                UPDATE fuel_purchases 
                SET supplier_name = :supplier_name, 
                    fuel_type = :fuel_type, 
                    amount = :amount, 
                    cost = :cost, 
                    unit_price = :unit_price, 
                    tank_id = :tank_id, 
                    invoice_number = :invoice_number, 
                    date = :date, 
                    notes = :notes 
                WHERE id = :id
            ');
            
            // Bağlama işlemleri
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':supplier_name', $data['supplier_name']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':cost', $data['cost']);
            $this->db->bind(':unit_price', $data['unit_price']);
            $this->db->bind(':tank_id', $data['tank_id']);
            $this->db->bind(':invoice_number', $data['invoice_number']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':notes', $data['notes']);
            
            // Çalıştır
            $purchaseResult = $this->db->execute();
            
            if ($purchaseResult) {
                // Tank modelini kullan
                $tankModel = new FuelTank();
                
                // Eğer tank değiştiyse
                if ($oldRecord->tank_id != $data['tank_id']) {
                    // Eski tanktan miktar çıkar
                    $tankModel->updateTankAmount($oldRecord->tank_id, $oldRecord->amount, false);
                    // Yeni tanka miktar ekle
                    $tankModel->updateTankAmount($data['tank_id'], $data['amount'], true);
                } else {
                    // Aynı tank, sadece miktar değişimi
                    $amountDiff = $data['amount'] - $oldRecord->amount;
                    if ($amountDiff != 0) {
                        $tankModel->updateTankAmount($data['tank_id'], abs($amountDiff), ($amountDiff > 0));
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
            $this->db->rollback();
            return false;
        }
    }

    // Yakıt alımını sil
    public function deletePurchase($id) {
        // Silinecek kayıt bilgilerini al
        $record = $this->getPurchaseById($id);
        
        if (!$record) {
            return false;
        }
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Kaydı sil
            $this->db->query('DELETE FROM fuel_purchases WHERE id = :id');
            $this->db->bind(':id', $id);
            $deleteResult = $this->db->execute();
            
            if ($deleteResult) {
                // Tank miktarını güncelle (eklenen yakıtı çıkar)
                $tankModel = new FuelTank();
                $tankUpdateResult = $tankModel->updateTankAmount($record->tank_id, $record->amount, false);
                
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

    // Belirli tarihler arasındaki yakıt alım istatistikleri
    public function getPurchaseStatsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT 
                SUM(amount) as total_amount,
                SUM(cost) as total_cost,
                AVG(unit_price) as avg_unit_price,
                COUNT(*) as purchase_count
            FROM fuel_purchases
            WHERE date BETWEEN :start_date AND :end_date
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->single();
    }

    // Yakıt tiplerine göre alım istatistikleri
    public function getPurchaseStatsByFuelType() {
        $this->db->query('
            SELECT 
                fuel_type,
                SUM(amount) as total_amount,
                SUM(cost) as total_cost,
                AVG(unit_price) as avg_unit_price,
                COUNT(*) as purchase_count
            FROM fuel_purchases
            GROUP BY fuel_type
            ORDER BY total_amount DESC
        ');
        
        return $this->db->resultSet();
    }

    // Tedarikçilere göre alım istatistikleri
    public function getPurchaseStatsBySupplier() {
        $this->db->query('
            SELECT 
                supplier_name,
                SUM(amount) as total_amount,
                SUM(cost) as total_cost,
                AVG(unit_price) as avg_unit_price,
                COUNT(*) as purchase_count
            FROM fuel_purchases
            GROUP BY supplier_name
            ORDER BY total_amount DESC
        ');
        
        return $this->db->resultSet();
    }
} 