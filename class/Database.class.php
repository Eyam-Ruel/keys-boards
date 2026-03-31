<?php
class Database {
    private static $host     = "localhost";
    private static $db_name  = "keys_boards";
    private static $username = "root";
    private static $password = "";       
    private static $port     = "3306";   
    private static $conn     = null;
    // private static $password = "root"; 
    // private static $port = "8889";

    public static function getLink() {
        if (self::$conn === null) {
            try {
                $dsn = "mysql:host=" . self::$host
                     . ";dbname="   . self::$db_name
                     . ";port="     . self::$port
                     . ";charset=utf8mb4";

                self::$conn = new PDO($dsn, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die("❌ Database connection error: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>