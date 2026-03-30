<?php
class Database {
    private static $link = null;

    public static function getLink() {
        if (self::$link === null) {
            try {
                self::$link = new PDO(
                    "mysql:host=127.0.0.1;dbname=keys_boards;charset=utf8",
                    "root",
                    ""
                );
                self::$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("❌ Erreur de connexion SQL : Vérifie tes identifiants BDD ! " . $e->getMessage());
            }
        }
        return self::$link;
    }
}
?>