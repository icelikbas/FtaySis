<?php
class Log {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tüm sistem loglarını getir
    public function getLogs($limit = null) {
        if ($limit) {
            $this->db->query('SELECT sl.*, u.name as user_name 
                              FROM system_logs sl
                              LEFT JOIN users u ON sl.user_id = u.id
                              ORDER BY sl.created_at DESC
                              LIMIT :limit');
            $this->db->bind(':limit', $limit);
        } else {
            $this->db->query('SELECT sl.*, u.name as user_name 
                              FROM system_logs sl
                              LEFT JOIN users u ON sl.user_id = u.id
                              ORDER BY sl.created_at DESC');
        }
        
        return $this->db->resultSet();
    }

    // Belirli bir ID'ye ait logu getir
    public function getLogById($id) {
        $this->db->query('SELECT sl.*, u.name as user_name 
                          FROM system_logs sl
                          LEFT JOIN users u ON sl.user_id = u.id
                          WHERE sl.id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Belirli bir kullanıcıya ait logları getir
    public function getLogsByUserId($user_id) {
        $this->db->query('SELECT * FROM system_logs 
                          WHERE user_id = :user_id
                          ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }

    // Belirli bir log türüne ait logları getir
    public function getLogsByType($type) {
        $this->db->query('SELECT sl.*, u.name as user_name 
                          FROM system_logs sl
                          LEFT JOIN users u ON sl.user_id = u.id
                          WHERE sl.type = :type
                          ORDER BY sl.created_at DESC');
        $this->db->bind(':type', $type);
        
        return $this->db->resultSet();
    }

    // Log ekle
    public function addLog($data) {
        try {
            // Tablo var mı kontrol et
            $this->db->query("SHOW TABLES LIKE 'system_logs'");
            $tableExists = $this->db->resultSet();
            
            // Tablo yoksa sessizce başarısız ol
            if (empty($tableExists)) {
                error_log('system_logs tablosu bulunamadı');
                return false;
            }
            
            $this->db->query('INSERT INTO system_logs (user_id, action, type, ip_address, details) 
                            VALUES (:user_id, :action, :type, :ip_address, :details)');
            
            // Verileri bağla
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':action', $data['action']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':ip_address', $data['ip_address']);
            $this->db->bind(':details', $data['details']);
            
            // Çalıştır
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Log ekleme hatası: ' . $e->getMessage());
            return false;
        }
    }

    // Log sil
    public function deleteLog($id) {
        $this->db->query('DELETE FROM system_logs WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Belirli bir tarihten önceki logları sil
    public function deleteOldLogs($date) {
        $this->db->query('DELETE FROM system_logs WHERE created_at < :date');
        $this->db->bind(':date', $date);
        
        return $this->db->execute();
    }

    // Log oluşturma yardımcı metodu
    public function create($action, $type, $details = '') {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $data = [
            'user_id' => $user_id,
            'action' => $action,
            'type' => $type,
            'ip_address' => $ip_address,
            'details' => $details
        ];
        
        return $this->addLog($data);
    }

    // Log sayımını al
    public function getLogCount() {
        $this->db->query('SELECT COUNT(*) as total FROM system_logs');
        $row = $this->db->single();
        return $row->total;
    }

    // Belirli bir türdeki log sayımını al
    public function getLogCountByType($type) {
        $this->db->query('SELECT COUNT(*) as total FROM system_logs WHERE type = :type');
        $this->db->bind(':type', $type);
        
        $row = $this->db->single();
        return $row->total;
    }

    // Belirli bir kullanıcının log sayımını al
    public function getLogCountByUser($user_id) {
        $this->db->query('SELECT COUNT(*) as total FROM system_logs WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $row = $this->db->single();
        return $row->total;
    }
} 