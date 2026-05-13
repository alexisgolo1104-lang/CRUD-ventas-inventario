<?php
require_once __DIR__ . '/../configuration/database.php';

class Venta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $id_tienda = 0): array {
        $sql = "SELECT v.*,
                       u.nombre  AS vendedor,
                       c.nombre  AS cliente
                FROM ventas v
                LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
                LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                WHERE 1=1";
        if ($id_tienda > 0) $sql .= " AND v.id_tienda = " . (int)$id_tienda;
        $sql .= " ORDER BY v.fecha DESC, v.id_venta DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM ventas WHERE id_venta = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getDetalle(int $id_venta): array {
        $stmt = $this->db->prepare("
            SELECT d.*,
                   cp.nombre   AS producto,
                   p.presentacion
            FROM detalle_venta d
            JOIN productos          p  ON d.id_producto = p.id_producto
            JOIN catalogo_productos cp ON p.id_catalogo = cp.id_catalogo
            WHERE d.id_venta = ?
        ");
        $stmt->execute([$id_venta]);
        return $stmt->fetchAll();
    }

    /**     * Marca una venta como cancelada/devolución y guarda el motivo en notas.
     */
    public function cancelar(int $id_venta, string $notas): bool {
        $stmt = $this->db->prepare("UPDATE ventas SET notas = ? WHERE id_venta = ?");
        return $stmt->execute([$notas, $id_venta]);
    }

    /**     * Registra una venta completa con sus ítems y descuenta stock.
     * $venta = [id_usuario, id_cliente, id_tienda, descuento, notas]
     * $items = [['id_producto', 'cantidad', 'precio_unit'], ...]
     */
    public function registrar(array $venta, array $items): bool|int {
        $this->db->beginTransaction();
        try {
            // Calcular totales
            $subtotal = 0;
            foreach ($items as $it) {
                $subtotal += (float)$it['cantidad'] * (float)$it['precio_unit'];
            }
            $descuento = (float)($venta['descuento'] ?? 0);
            $total     = $subtotal * (1 - $descuento / 100);

            $folio = 'V-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $stmt = $this->db->prepare("
                INSERT INTO ventas (folio, id_usuario, id_cliente, id_tienda, descuento, subtotal, total, notas, fecha)
                VALUES (?,?,?,?,?,?,?,?,CURDATE())
            ");
            $stmt->execute([
                $folio,
                (int)$venta['id_usuario'],
                ($venta['id_cliente'] ?? null) ?: null,
                (int)$venta['id_tienda'],
                $descuento,
                round($subtotal, 2),
                round($total, 2),
                $venta['notas'] ?? null,
            ]);
            $id_venta = (int)$this->db->lastInsertId();

            $stmtDet   = $this->db->prepare("
                INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unit, subtotal)
                VALUES (?,?,?,?,?)
            ");
            $stmtStockCheck = $this->db->prepare("
                SELECT stock_actual FROM productos WHERE id_producto = ?
            ");
            $stmtStock = $this->db->prepare("
                UPDATE productos SET stock_actual = stock_actual - ?, actualizado_en = CURDATE()
                WHERE id_producto = ?
            ");
            foreach ($items as $it) {
                $id_prod = (int)$it['id_producto'];
                $cant = (float)$it['cantidad'];
                $stmtStockCheck->execute([$id_prod]);
                $stock_actual = (float)$stmtStockCheck->fetchColumn();
                if ($stock_actual < $cant) {
                    throw new \Exception("Stock insuficiente para producto ID $id_prod");
                }
                $sub = round($cant * (float)$it['precio_unit'], 2);
                $stmtDet->execute([
                    $id_venta,
                    $id_prod,
                    $cant,
                    (float)$it['precio_unit'],
                    $sub,
                ]);
                $stmtStock->execute([$cant, $id_prod]);
            }

            $this->db->commit();
            return $id_venta;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
