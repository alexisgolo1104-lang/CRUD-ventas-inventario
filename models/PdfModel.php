<?php
/**
 * models/PdfModel.php
 * Modelo para gestión de PDFs (tickets, reportes, etc.)
 * Centraliza consultas para generación de documentos
 */

class PdfModel
{
    private PDO $db;

    public function __construct(PDO $conexion)
    {
        $this->db = $conexion;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  VENTA - Obtener datos completos de una venta para ticket
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Obtiene los datos de una venta específica con información de cliente y tienda
     */
    public function getVenta(int $id_venta): ?array
    {
        $sql = "
            SELECT 
                v.*,
                COALESCE(c.nombre, 'Cliente General') AS cliente_nombre,
                COALESCE(c.email, '') AS cliente_email,
                COALESCE(c.telefono, '') AS cliente_telefono,
                t.nombre AS sucursal_nombre
            FROM ventas v
            LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
            LEFT JOIN tiendas t ON v.id_tienda = t.id_tienda
            WHERE v.id_venta = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_venta]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Obtiene los items (detalles) de una venta
     */
    public function getDetallesVenta(int $id_venta): array
    {
        $sql = "
            SELECT 
                d.id_detalle,
                d.cantidad,
                d.precio_unit,
                d.subtotal,
                p.nombre AS nombre_producto,
                c.nombre AS nombre_catalogo
            FROM detalle_venta d
            JOIN productos p ON d.id_producto = p.id_producto
            JOIN catalogo_productos c ON p.id_catalogo = c.id_catalogo
            WHERE d.id_venta = ?
            ORDER BY d.id_detalle ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_venta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Obtiene datos completos para renderizar ticket (venta + detalles)
     */
    public function getTicketCompleto(int $id_venta): ?array
    {
        $venta = $this->getVenta($id_venta);
        if (!$venta) {
            return null;
        }

        $detalles = $this->getDetallesVenta($id_venta);

        return [
            'venta'     => $venta,
            'detalles'  => $detalles,
            'folio'     => str_pad($venta['id_venta'], 4, '0', STR_PAD_LEFT),
            'fecha'     => $venta['fecha'] ? date('d/m/Y H:i', strtotime($venta['fecha'])) : date('d/m/Y H:i'),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  RESUMEN - Estadísticas para encabezado
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Obtiene empresa/configuración (nombre, teléfono, etc.)
     */
    public function getEmpresaInfo(): array
    {
        // Si existe tabla de configuración, consultarla
        // Por ahora retorna datos hardcodeados
        return [
            'nombre'  => 'HLazcano - Prendas de Punto',
            'telefono' => '222-555-0000',
            'email'   => 'info@hlazcano.com',
            'direccion' => 'Dirección',
        ];
    }
}
