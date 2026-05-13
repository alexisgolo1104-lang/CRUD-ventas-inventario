<?php
require_once __DIR__ . '/../configuration/Database.php';

class LogRespaldo {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Registrar un respaldo exitoso
     */
    public function registrarRespaldo($idUsuario, $nombreArchivo, $tamanoBytes, $notas = null) {
        $sql = "INSERT INTO logs_respaldo (id_usuario, nombre_archivo, tamano_bytes, exito, notas)
                VALUES (?, ?, ?, TRUE, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idUsuario, $nombreArchivo, $tamanoBytes, $notas]);
    }

    /**
     * Registrar un respaldo fallido
     */
    public function registrarRespaldoFallido($idUsuario, $error) {
        $sql = "INSERT INTO logs_respaldo (id_usuario, nombre_archivo, tamano_bytes, exito, notas)
                VALUES (?, 'ERROR', 0, FALSE, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idUsuario, $error]);
    }

    /**
     * Obtener historial de respaldos
     */
    public function getHistorial($limit = 50) {
        $sql = "SELECT lr.*, u.nombre as nombre_usuario
                FROM logs_respaldo lr
                JOIN usuarios u ON lr.id_usuario = u.id_usuario
                ORDER BY lr.fecha_generacion DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener último respaldo exitoso
     */
    public function getUltimoRespaldo() {
        $sql = "SELECT lr.*, u.nombre as nombre_usuario
                FROM logs_respaldo lr
                JOIN usuarios u ON lr.id_usuario = u.id_usuario
                WHERE lr.exito = TRUE
                ORDER BY lr.fecha_generacion DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}