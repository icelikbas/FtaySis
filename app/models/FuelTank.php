<?php
class FuelTank {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm yakıt tanklarını al
    public function getAllTanks() {
        $this->db->query('SELECT * FROM fuel_tanks ORDER BY type, name');
        return $this->db->resultSet();
    }

    // Tüm yakıt tanklarını al (getFuelTanks için alternatif)
    public function getTanks() {
        return $this->getAllTanks();
    }

    // Aktif yakıt tanklarını al
    public function getActiveTanks() {
        $this->db->query('SELECT * FROM fuel_tanks WHERE status = "Aktif" ORDER BY type, name');
        return $this->db->resultSet();
    }

    // ID'ye göre tank bilgisini al
    public function getTankById($id) {
        $this->db->query('SELECT * FROM fuel_tanks WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Yeni tank ekle
    public function addTank($data) {
        $this->db->query('
            INSERT INTO fuel_tanks (name, type, capacity, current_amount, location, status, fuel_type)
            VALUES (:name, :type, :capacity, :current_amount, :location, :status, :fuel_type)
        ');
        
        // Bağlama işlemleri
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':current_amount', $data['current_amount']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':fuel_type', $data['fuel_type']);
        
        // Çalıştır
        return $this->db->execute();
    }

    // Tank bilgilerini güncelle
    public function updateTank($data) {
        $this->db->query('
            UPDATE fuel_tanks
            SET name = :name, 
                type = :type, 
                capacity = :capacity, 
                current_amount = :current_amount, 
                location = :location, 
                status = :status,
                fuel_type = :fuel_type
            WHERE id = :id
        ');
        
        // Bağlama işlemleri
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':current_amount', $data['current_amount']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':fuel_type', $data['fuel_type']);
        
        // Çalıştır
        return $this->db->execute();
    }

    // Tank sil
    public function deleteTank($id) {
        $this->db->query('DELETE FROM fuel_tanks WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Tank miktarını güncelle (yakıt alım veya transfer sonrası)
    public function updateTankAmount($tankId, $amount, $isAddition = true) {
        if ($isAddition) {
            $this->db->query('
                UPDATE fuel_tanks 
                SET current_amount = current_amount + :amount 
                WHERE id = :id
            ');
        } else {
            $this->db->query('
                UPDATE fuel_tanks 
                SET current_amount = current_amount - :amount 
                WHERE id = :id
            ');
        }
        
        $this->db->bind(':id', $tankId);
        $this->db->bind(':amount', $amount);
        
        return $this->db->execute();
    }

    // Aktif tanklar için seçim listesi
    public function getTanksForSelect() {
        $this->db->query('SELECT id, name, type, current_amount, capacity FROM fuel_tanks WHERE status = "Aktif" ORDER BY name');
        return $this->db->resultSet();
    }

    // Tankın mevcut yakıt miktarını kontrol et
    public function checkTankAmount($tankId, $requiredAmount) {
        $this->db->query('SELECT current_amount FROM fuel_tanks WHERE id = :id');
        $this->db->bind(':id', $tankId);
        $result = $this->db->single();
        
        if ($result && $result->current_amount >= $requiredAmount) {
            return true;
        }
        
        return false;
    }
} 