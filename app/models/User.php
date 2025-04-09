<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Kullanıcı kaydı
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
        
        // Değerleri bağla
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Çalıştır
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Kullanıcı girişi
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        
        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // E-posta ile kullanıcı bulma
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Satır kontrolü
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // ID ile kullanıcı getir
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    // Tüm kullanıcıları getir
    public function getUsers() {
        $this->db->query("SELECT * FROM users ORDER BY name ASC");
        
        return $this->db->resultSet();
    }

    // Kullanıcı sayısını getir
    public function getUserCount() {
        $this->db->query('SELECT COUNT(*) as total FROM users');
        $row = $this->db->single();
        return $row->total;
    }

    // Kullanıcı güncelleme
    public function updateUser($data) {
        // Şifre değiştirilecek mi kontrolü
        if(empty($data['password'])) {
            $this->db->query('UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':role', $data['role']);
            $this->db->bind(':id', $data['id']);
        } else {
            $this->db->query('UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':role', $data['role']);
            $this->db->bind(':id', $data['id']);
        }

        // Çalıştır
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Kullanıcı silme
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        // Çalıştır
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
} 