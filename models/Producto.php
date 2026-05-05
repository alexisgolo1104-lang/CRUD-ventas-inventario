<?php
require_once __DIR__ . '/../configuration/database.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Lista todos los productos con joins a catálogo, tipo, color, unidad, anaquel, proveedor */
    public function getAll(int $id_tienda = 0): array {
        $sql = "SELECT p.*,
                       c.nombre        AS catalogo_nombre,
                       c.nombre        AS nombre,
                       t.nombre        AS tipo_nombre,
                       t.nombre        AS tipo_hilo,
                       co.nombre       AS color_nombre,
                       co.nombre       AS color,
                       co.codigo_hex   AS color_hex,
                       u.nombre        AS unidad_nombre,
                       u.codigo        AS unidad_codigo,
                       u.codigo        AS unidad,
                       a.codigo        AS anaquel_codigo,
                       a.codigo        AS anaquel,
                       a.descripcion   AS anaquel_desc,
                       pr.nombre       AS proveedor_nombre,
                       pr.nombre       AS proveedor
                FROM productos p
                LEFT JOIN catalogo_productos c  ON p.id_catalogo  = c.id_catalogo
                LEFT JOIN tipos_hilo         t  ON p.id_tipo       = t.id_tipo
                LEFT JOIN colores            co ON p.id_color      = co.id_color
                LEFT JOIN unidades           u  ON p.id_unidad     = u.id_unidad
                LEFT JOIN anaqueles          a  ON p.id_anaquel    = a.id_anaquel
                LEFT JOIN proveedores        pr ON p.id_proveedor  = pr.id_proveedor
                WHERE p.activo = 1";
        if ($id_tienda > 0) {
            $sql .= " AND p.id_tienda = " . (int)$id_tienda;
        }
        $sql .= " ORDER BY c.nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   c.nombre AS catalogo_nombre,
                   t.nombre AS tipo_nombre,
                   co.nombre AS color_nombre,
                   co.codigo_hex AS color_hex,
                   u.nombre AS unidad_nombre,
                   u.codigo AS unidad_codigo,
                   a.codigo AS anaquel_codigo,
                   a.descripcion AS anaquel_desc,
                   pr.nombre AS proveedor_nombre
            FROM productos p
            LEFT JOIN catalogo_productos c ON p.id_catalogo = c.id_catalogo
            LEFT JOIN tipos_hilo t ON p.id_tipo = t.id_tipo
            LEFT JOIN colores co ON p.id_color = co.id_color
            LEFT JOIN unidades u ON p.id_unidad = u.id_unidad
            LEFT JOIN anaqueles a ON p.id_anaquel = a.id_anaquel
            LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
            WHERE p.id_producto = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Crea un nuevo producto en la tabla productos */
    public function crear(array $d): bool {
        $stmt = $this->db->prepare("
            INSERT INTO productos
                (id_catalogo, id_tipo, id_color, id_unidad, id_proveedor, id_anaquel,
                 id_tienda, presentacion, posicion_anaquel,
                 stock_actual, stock_minimo, precio_compra, precio_venta,
                 activo, creado_en)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1,CURDATE())
        ");
        return $stmt->execute([
            (int)($d['id_catalogo']       ?? 0),
            (int)($d['id_tipo']           ?? 0),
            (int)($d['id_color']          ?? 0),
            (int)($d['id_unidad']         ?? 0),
            ($d['id_proveedor'] ?? null) ?: null,
            ($d['id_anaquel']   ?? null) ?: null,
            (int)($d['id_tienda']         ?? 1),
            $d['presentacion']            ?? '',
            $d['posicion_anaquel']        ?? '',
            (float)($d['stock_actual']    ?? 0),
            (float)($d['stock_minimo']    ?? 0),
            (float)($d['precio_compra']   ?? 0),
            (float)($d['precio_venta']    ?? 0),
        ]);
    }

    /** Actualiza un producto existente */
    public function actualizar(int $id, array $d): bool {
        $fields = [];
        $values = [];

        if (isset($d['id_catalogo']) && (int)$d['id_catalogo'] > 0) {
            $fields[] = 'id_catalogo = ?';
            $values[] = (int)$d['id_catalogo'];
        }
        if (isset($d['id_tipo']) && (int)$d['id_tipo'] > 0) {
            $fields[] = 'id_tipo = ?';
            $values[] = (int)$d['id_tipo'];
        }
        if (isset($d['id_color']) && (int)$d['id_color'] > 0) {
            $fields[] = 'id_color = ?';
            $values[] = (int)$d['id_color'];
        }
        if (isset($d['id_unidad']) && (int)$d['id_unidad'] > 0) {
            $fields[] = 'id_unidad = ?';
            $values[] = (int)$d['id_unidad'];
        }
        if (isset($d['id_proveedor'])) {
            $fields[] = 'id_proveedor = ?';
            $values[] = $d['id_proveedor'] ? (int)$d['id_proveedor'] : null;
        }
        if (isset($d['id_anaquel'])) {
            $fields[] = 'id_anaquel = ?';
            $values[] = $d['id_anaquel'] ? (int)$d['id_anaquel'] : null;
        }
        if (isset($d['id_tienda']) && (int)$d['id_tienda'] > 0) {
            $fields[] = 'id_tienda = ?';
            $values[] = (int)$d['id_tienda'];
        }
        if (isset($d['presentacion'])) {
            $fields[] = 'presentacion = ?';
            $values[] = $d['presentacion'];
        }
        if (isset($d['posicion_anaquel'])) {
            $fields[] = 'posicion_anaquel = ?';
            $values[] = $d['posicion_anaquel'];
        }
        if (isset($d['stock_actual'])) {
            $fields[] = 'stock_actual = ?';
            $values[] = (float)$d['stock_actual'];
        }
        if (isset($d['stock_minimo'])) {
            $fields[] = 'stock_minimo = ?';
            $values[] = (float)$d['stock_minimo'];
        }
        if (isset($d['precio_compra'])) {
            $fields[] = 'precio_compra = ?';
            $values[] = (float)$d['precio_compra'];
        }
        if (isset($d['precio_venta'])) {
            $fields[] = 'precio_venta = ?';
            $values[] = (float)$d['precio_venta'];
        }

        if (empty($fields)) return true; // nothing to update

        $sql = "UPDATE productos SET " . implode(', ', $fields) . ", actualizado_en = CURDATE() WHERE id_producto = ?";
        $values[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /** Desactiva un producto (borrado lógico) */
    public function desactivar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE productos SET activo=0, actualizado_en=CURDATE() WHERE id_producto=?");
        return $stmt->execute([$id]);
    }

    /** Busca productos por nombre de catálogo, tipo o color */
    public function buscar(string $q): array {
        $like = "%$q%";
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre AS catalogo_nombre, t.nombre AS tipo_nombre, co.nombre AS color_nombre, co.codigo_hex
            FROM productos p
            LEFT JOIN catalogo_productos c ON p.id_catalogo = c.id_catalogo
            LEFT JOIN tipos_hilo         t ON p.id_tipo     = t.id_tipo
            LEFT JOIN colores           co ON p.id_color    = co.id_color
            WHERE p.activo = 1
              AND (c.nombre LIKE ? OR t.nombre LIKE ? OR co.nombre LIKE ? OR p.presentacion LIKE ?)
            ORDER BY c.nombre
        ");
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll();
    }

    /** Productos con stock por debajo del mínimo */
    public function getConAlerta(int $id_tienda = 0): array {
        $sql = "SELECT p.*, c.nombre AS catalogo_nombre, t.nombre AS tipo_nombre,
                       co.nombre AS color_nombre, a.codigo AS anaquel_codigo
                FROM productos p
                LEFT JOIN catalogo_productos c ON p.id_catalogo = c.id_catalogo
                LEFT JOIN tipos_hilo         t ON p.id_tipo     = t.id_tipo
                LEFT JOIN colores           co ON p.id_color    = co.id_color
                LEFT JOIN anaqueles          a ON p.id_anaquel  = a.id_anaquel
                WHERE p.activo = 1 AND p.stock_actual <= p.stock_minimo";
        if ($id_tienda > 0) $sql .= " AND p.id_tienda = " . (int)$id_tienda;
        $sql .= " ORDER BY p.stock_actual ASC";
        return $this->db->query($sql)->fetchAll();
    }

    // ── Catálogos auxiliares ──────────────────────────────────

    public function getCatalogos(): array {
        return $this->db->query("
            SELECT c.*, t.nombre AS tipo_nombre, co.nombre AS color_nombre, co.codigo_hex
            FROM catalogo_productos c
            LEFT JOIN tipos_hilo t  ON c.id_tipo  = t.id_tipo
            LEFT JOIN colores   co  ON c.id_color = co.id_color
            WHERE c.activo = 1 ORDER BY c.nombre
        ")->fetchAll();
    }

    public function getTipos(): array {
        return $this->db->query("SELECT * FROM tipos_hilo WHERE activo=1 ORDER BY nombre")->fetchAll();
    }

    public function getColores(): array {
        return $this->db->query("SELECT * FROM colores WHERE activo=1 ORDER BY nombre")->fetchAll();
    }

    public function getUnidades(): array {
        return $this->db->query("SELECT * FROM unidades ORDER BY nombre")->fetchAll();
    }

    public function getAnaqueles(int $id_tienda = 0): array {
        $sql = "SELECT * FROM anaqueles WHERE activo=1";
        if ($id_tienda > 0) $sql .= " AND id_tienda=" . (int)$id_tienda;
        return $this->db->query($sql . " ORDER BY codigo")->fetchAll();
    }

    public function getProveedores(): array {
        return $this->db->query("SELECT * FROM proveedores WHERE activo=1 ORDER BY nombre")->fetchAll();
    }
}
