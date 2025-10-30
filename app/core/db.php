<?php

date_default_timezone_set("Asia/Taipei");

class DB {
    private $pdo;
    private $table;

    public function __construct($table=null) {
        // 載入設定
        $config = require __DIR__ . '/../../config/db.php';
        // $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        // 組成 DSN 字串
        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            $config['host'],
            $config['dbname'],
            $config['charset']
        );
        // 建立 PDO 連線
        try {
        //     $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
        //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //     PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE utf8mb4_unicode_ci"
        // ]);
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "資料庫連線失敗: " . $e->getMessage();
            exit;
        }
        $this->table = $table;
    }


    public function all($where='', $params=[]) {
        $sql = "SELECT * FROM `{$this->table}`";
        if($where) $sql .= " WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 這樣寫法僅限於主鍵為 id 的情況
    // public function find($id) {
    //     $sql = "SELECT * FROM `{$this->table}` WHERE id = ?";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    public function find($conditions) {
        $sql = "SELECT * FROM `{$this->table}` WHERE ";
        $clauses = [];
        $params = [];
        if (!is_array($conditions)) {
            $conditions = ['id' => $conditions];
        }
        
        foreach($conditions as $col => $value) {
            $clauses[] = "`$col` = ?";
            $params[] = $value;
        }
        $sql .= implode(' AND', $clauses) . " LIMIT 1 ";
        $stmt =$this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO `{$this->table}`";
        $cols = array_keys($data);
        $placeholders = array_fill(0, count($data), '?');
        $sql .= " (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);
        // return $stmt->execute(array_values($data));
        $success = $stmt->execute(array_values($data));
        return $success ? $this->pdo->lastInsertId() : false;
    }

    public function update($id, $data) {
        $sql ="UPDATE `{$this->table}` SET ";
        $set = [];
        foreach($data as $col => $value) {
            $set[] = "`$col` = ?";
        }
        $sql .= " " . implode(', ', $set) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $data[] = $id;

        // debug
        // $debug = false;
        // if($debug) {
        //     echo "SQL: $sql\n";
        //     print_r($data);
        // }
        return $stmt->execute(array_values($data));
    }
        // public function update($data, $where) {
        // $sql ="UPDATE `{$this->table}` SET ";
        // $set = [];
        // foreach($data as $col => $value) {
        //     $set[] = "`$col` = ?";
        // }
        // $sql .= " " . implode(', ', $set);
        // $sql .= " WHERE ";
        // $conditions = [];
        // foreach ($where as $col => $value) {
        //     $conditions[] = "`$col` = ?";
        // }
        // $sql .= implode(' AND ', $conditions);
        // $stmt = $this->pdo->prepare($sql);
        // $params = array_merge(array_values($data), array_values($where));

        // debug
        // $debug = false;
        // if($debug) {
        //     echo "SQL: $sql\n";
        //     print_r($data);
        // }
    //     return $stmt->execute(array_values($params));
    // }

    public function delete($id) {
        $sql = "DELETE FROM `{$this->table}` WHERE id = ?";
        $stmt =$this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // 自訂查詢
    public function query($sql, $params=[]) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>