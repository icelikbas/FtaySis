<?php
/**
 * PDO Database Sınıfı
 * - Veritabanı bağlantısı oluşturur
 * - Prepared statements ile sorguları çalıştırır
 * - Güvenli sorgular için bind işlemleri yapar
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // DSN oluştur
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        // PDO instance oluştur
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Sorgu hazırla
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Değer bağla
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Sorguyu çalıştır
    public function execute() {
        return $this->stmt->execute();
    }

    // Tüm kayıtları getir
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Tek kayıt getir
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Kayıt sayısını getir
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Son eklenen ID'yi getir
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
    
    // Transaction başlat
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }
    
    // Transaction commit
    public function commit() {
        return $this->dbh->commit();
    }
    
    // Transaction rollback
    public function rollback() {
        return $this->dbh->rollBack();
    }
} 