<?php
class FuelTransfer {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm yakıt transferlerini al
    public function getAllTransfers() {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            ORDER BY t.date DESC
        ');
        
        return $this->db->resultSet();
    }

    // ID'ye göre yakıt transferini al
    public function getTransferById($id) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.id = :id
        ');
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Tank ID'sine göre yakıt transferlerini al (kaynak veya hedef olarak)
    public function getTransfersByTank($tankId) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.source_tank_id = :tank_id OR t.destination_tank_id = :tank_id
            ORDER BY t.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }

    // Belirli tarih aralığındaki yakıt transferlerini al
    public function getTransfersByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.date BETWEEN :start_date AND :end_date
            ORDER BY t.date DESC
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Yakıt tipine göre transferleri getir
    public function getTransfersByFuelType($fuelType) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.fuel_type = :fuel_type
            ORDER BY t.date DESC
        ');
        
        $this->db->bind(':fuel_type', $fuelType);
        
        return $this->db->resultSet();
    }

    // Yeni yakıt transferi ekle
    public function addTransfer($data) {
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Önce kaynak tankta yeterli yakıt olup olmadığını kontrol et
            $tankModel = new FuelTank();
            if (!$tankModel->checkTankAmount($data['source_tank_id'], $data['amount'])) {
                return false; // Yeterli yakıt yoksa işlemi durdur
            }
            
            // Transfer kaydını ekle
            $this->db->query('
                INSERT INTO fuel_transfers (source_tank_id, destination_tank_id, fuel_type, amount, date, notes, created_by)
                VALUES (:source_tank_id, :destination_tank_id, :fuel_type, :amount, :date, :notes, :created_by)
            ');
            
            // Bağlama işlemleri
            $this->db->bind(':source_tank_id', $data['source_tank_id']);
            $this->db->bind(':destination_tank_id', $data['destination_tank_id']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':created_by', $data['created_by']);
            
            // Çalıştır
            $transferResult = $this->db->execute();
            
            if ($transferResult) {
                // Tankları güncelle
                // Kaynak tanktan miktar çıkar
                $sourceUpdateResult = $tankModel->updateTankAmount($data['source_tank_id'], $data['amount'], false);
                
                if ($sourceUpdateResult) {
                    // Hedef tanka miktar ekle
                    $destinationUpdateResult = $tankModel->updateTankAmount($data['destination_tank_id'], $data['amount'], true);
                    
                    if ($destinationUpdateResult) {
                        // İşlemler başarılı
                        $this->db->commit();
                        return true;
                    }
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

    // Yakıt transferini güncelle
    public function updateTransfer($data) {
        // Önceki kayıt bilgilerini al
        $oldRecord = $this->getTransferById($data['id']);
        
        if (!$oldRecord) {
            return false;
        }
        
        // Eğer transfer miktarı artırılıyorsa, kaynak tankta yeterli yakıt olup olmadığını kontrol et
        if ($data['amount'] > $oldRecord->amount) {
            $additionalAmount = $data['amount'] - $oldRecord->amount;
            $tankModel = new FuelTank();
            if (!$tankModel->checkTankAmount($data['source_tank_id'], $additionalAmount)) {
                return false; // Yeterli yakıt yoksa işlemi durdur
            }
        }
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Önce eski işlemleri geri al
            $tankModel = new FuelTank();
            
            // Eski kaynak tanka miktarı geri ekle
            $tankModel->updateTankAmount($oldRecord->source_tank_id, $oldRecord->amount, true);
            
            // Eski hedef tanktan miktarı çıkar
            $tankModel->updateTankAmount($oldRecord->destination_tank_id, $oldRecord->amount, false);
            
            // Transfer kaydını güncelle
            $this->db->query('
                UPDATE fuel_transfers 
                SET source_tank_id = :source_tank_id, 
                    destination_tank_id = :destination_tank_id,
                    fuel_type = :fuel_type,
                    amount = :amount,
                    date = :date,
                    notes = :notes
                WHERE id = :id
            ');
            
            // Bağlama işlemleri
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':source_tank_id', $data['source_tank_id']);
            $this->db->bind(':destination_tank_id', $data['destination_tank_id']);
            $this->db->bind(':fuel_type', $data['fuel_type']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':date', $data['date']);
            $this->db->bind(':notes', $data['notes']);
            
            // Çalıştır
            $transferResult = $this->db->execute();
            
            if ($transferResult) {
                // Yeni bilgilere göre tankları güncelle
                // Kaynak tanktan miktar çıkar
                $sourceUpdateResult = $tankModel->updateTankAmount($data['source_tank_id'], $data['amount'], false);
                
                if ($sourceUpdateResult) {
                    // Hedef tanka miktar ekle
                    $destinationUpdateResult = $tankModel->updateTankAmount($data['destination_tank_id'], $data['amount'], true);
                    
                    if ($destinationUpdateResult) {
                        // İşlemler başarılı
                        $this->db->commit();
                        return true;
                    }
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

    // Yakıt transferini sil
    public function deleteTransfer($id) {
        // Silinecek kayıt bilgilerini al
        $record = $this->getTransferById($id);
        
        if (!$record) {
            return false;
        }
        
        // Transaction başlat
        $this->db->beginTransaction();
        
        try {
            // Önce tankları güncelle (transferi geri al)
            $tankModel = new FuelTank();
            
            // Kaynak tanka miktarı geri ekle
            $sourceUpdateResult = $tankModel->updateTankAmount($record->source_tank_id, $record->amount, true);
            
            if ($sourceUpdateResult) {
                // Hedef tanktan miktarı çıkar
                $destinationUpdateResult = $tankModel->updateTankAmount($record->destination_tank_id, $record->amount, false);
                
                if ($destinationUpdateResult) {
                    // Kaydı sil
                    $this->db->query('DELETE FROM fuel_transfers WHERE id = :id');
                    $this->db->bind(':id', $id);
                    $deleteResult = $this->db->execute();
                    
                    if ($deleteResult) {
                        // İşlemler başarılı
                        $this->db->commit();
                        return true;
                    }
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

    // Belirli tarihler arasında yapılan transferlerin toplamını al
    public function getTransferTotalsByDateRange($startDate, $endDate) {
        $this->db->query('
            SELECT 
                fuel_type,
                SUM(amount) as total_amount,
                COUNT(*) as transfer_count
            FROM fuel_transfers
            WHERE date BETWEEN :start_date AND :end_date
            GROUP BY fuel_type
        ');
        
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    // Kaynak tank ID'sine göre yakıt transferlerini al
    public function getTransfersBySourceTank($tankId) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.source_tank_id = :tank_id
            ORDER BY t.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }
    
    // Hedef tank ID'sine göre yakıt transferlerini al
    public function getTransfersByDestinationTank($tankId) {
        $this->db->query('
            SELECT t.*, 
                   s.name as source_name, 
                   d.name as destination_name, 
                   u.name as user_name,
                   s.id as source_tank_id,
                   d.id as destination_tank_id,
                   t.date as transfer_date
            FROM fuel_transfers t
            LEFT JOIN fuel_tanks s ON t.source_tank_id = s.id
            LEFT JOIN fuel_tanks d ON t.destination_tank_id = d.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.destination_tank_id = :tank_id
            ORDER BY t.date DESC
        ');
        
        $this->db->bind(':tank_id', $tankId);
        
        return $this->db->resultSet();
    }
} 