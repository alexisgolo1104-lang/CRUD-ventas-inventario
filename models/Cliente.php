<?php
require_once __DIR__ . '/../configuration/database.php';

class Cliente {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $id_tienda = 0): array {
        $sql = "SELECT * FROM clientes WHERE activo = 1";
        if ($id_tienda > 0) $sql .= " AND id_tienda = " . (int)$id_tienda;
        return $this->db->query($sql . " ORDER BY nombre")->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function crear(array $d): bool {
        $stmt = $this->db->prepare("
            INSERT INTO clientes (nombre, telefono, correo, direccion, rfc, tipo_cliente, id_tienda, notas, activo, creado_en)
            VALUES (?,?,?,?,?,?,?,?,1,CURDATE())
        ");
        return $stmt->execute([
            $d['nombre']        ?? '',
            $d['telefono']      ?? null,
            $d['correo']        ?? null,
            $d['direccion']     ?? null,
            $d['rfc']           ?? null,
            $d['tipo_cliente']  ?? 'Comprador individual',
            (int)($d['id_tienda'] ?? 1),
            $d['notas']         ?? null,
        ]);
    }

    public function actualizar(int $id, array $d): bool {
        $stmt = $this->db->prepare("
            UPDATE clientes SET
                nombre       = ?,
                telefono     = ?,
                correo       = ?,
                direccion    = ?,
                rfc          = ?,
                tipo_cliente = ?,
                notas        = ?,
                actualizado_en = CURDATE()
            WHERE id_cliente = ?
        ");
        return $stmt->execute([
            $d['nombre']        ?? '',
            $d['telefono']      ?? null,
            $d['correo']        ?? null,
            $d['direccion']     ?? null,
            $d['rfc']           ?? null,
            $d['tipo_cliente']  ?? 'Comprador individual',
            $d['notas']         ?? null,
            $id,
        ]);
    }

    public function desactivar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE clientes SET activo=0, actualizado_en=CURDATE() WHERE id_cliente=?");
        return $stmt->execute([$id]);
    }

    public function getHistorialVentas(int $id_cliente): array {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nombre AS vendedor
            FROM ventas v
            LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
            WHERE v.id_cliente = ?
            ORDER BY v.fecha DESC, v.id_venta DESC
        ");
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll();
    }
}
