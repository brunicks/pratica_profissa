<?php
class Database {
    private static $pdo;

    public static function getConnection() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:host=localhost;dbname=carros_db", "root", "");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>
