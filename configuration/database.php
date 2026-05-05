<?php
require_once __DIR__ . '/config.php';

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            // Muestra el error real para facilitar diagnóstico
            die(json_encode([
                'ok'    => false,
                'error' => 'Error de conexión a la base de datos.',
                'detalle' => $e->getMessage(),
                'config' => [
                    'host'    => DB_HOST,
                    'dbname'  => DB_NAME,
                    'user'    => DB_USER,
                ]
            ]));
        }
    }

    /** Retorna la instancia única (Singleton) */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
