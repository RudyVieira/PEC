<?php

namespace App\Lib;

use PDO;
use PDOException;

class Database {
    private static $pdo;

    public static function getConnection() {
        if (self::$pdo === null) {
            $config = json_decode(file_get_contents(__DIR__ . '/../../config/database.json'), true);

            $host = $config['host'];
            $user = $config['user'];
            $password = $config['password'];
            $database = $config['database'];
            $port = $config['port'];

            $dsn = "mysql:host=$host;dbname=$database;port=$port";
            try {
                self::$pdo = new PDO($dsn, $user, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}