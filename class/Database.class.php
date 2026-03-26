<?php
class Database {
    private static $host = "localhost";
    private static $db_name = "keys_boards"; 
    private static $username = "root";       
    private static $password = "root"; 
    private static $port = "8889"; 
    private static $conn = null;

    public static function getLink() {
        if (self::$conn === null) {
            try {
                // Ajout du port et du charset pour éviter les bugs d'accents
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";port=" . self::$port . ";charset=utf8mb4";
                
                self::$conn = new PDO($dsn, self::$username, self::$password);
                
                // Options pour ton MVC : erreurs visibles + résultats sous forme de tableaux associatifs
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch(PDOException $exception) {
                die("❌ Erreur de connexion SQL : Vérifie tes identifiants BDD ! " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}
?>