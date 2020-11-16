<?php namespace App\Database;

use PDO;

class Connector {

    //TODO Consider to move it to configuration file.
    const DSN = "mysql:host=172.17.0.2;dbname=shop";
    const DATABASE_USERNAME = "root";
    const DATABASE_PASSWORD = "root";

    private static $databaseConnection;

    private function __construct() {
    }

    /**
     * @return PDO
     */
    public static function getInstance() {

        if (!self::$databaseConnection) {
            self::$databaseConnection = new PDO(
                self::DSN,
                self::DATABASE_USERNAME,
                self::DATABASE_PASSWORD
            );
        }

        return self::$databaseConnection;
    }
}