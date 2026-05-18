<?php
require_once __DIR__ . '/configuration/config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    die('Acceso no autorizado');
}

require_once __DIR__ . '/configuration/database.php';

$id_venta = (int)($_GET['id'] ?? 0);
if ($id_venta <= 0) {
    http_response_code(400);
    die('ID de venta inválido');
}

try {
    $db = Database::getInstance()->getConnection();

    $stmtVenta = $db->prepare("SELECT v.*, COALESCE(c.nombre, 'Cliente General') AS cliente_nombre, t.nombre AS sucursal_nombre FROM ventas v LEFT JOIN clientes c ON v.id_cliente = c.id_cliente LEFT JOIN tiendas t ON v.id_tienda = t.id_tienda WHERE v.id_venta = ?");
    $stmtVenta->execute([$id_venta]);
    $venta = $stmtVenta->fetch();

    if (!$venta) {
        http_response_code(404);
        die('Venta no encontrada');
    }

    $stmtItems = $db->prepare("SELECT d.cantidad, d.precio_unit, d.subtotal, cp.nombre AS nombre_producto FROM detalle_venta d JOIN productos p ON d.id_producto = p.id_producto JOIN catalogo_productos cp ON p.id_catalogo = cp.id_catalogo WHERE d.id_venta = ? ORDER BY d.id_detalle ASC");
    $stmtItems->execute([$id_venta]);
    $items = $stmtItems->fetchAll();

    // Generar datos del ticket
    $fechaVenta = $venta['fecha'] ? date('d/m/Y H:i', strtotime($venta['fecha'])) : date('d/m/Y H:i');
    $nombreCliente = htmlspecialchars($venta['cliente_nombre'] ?? 'Cliente General');
    $tienda = htmlspecialchars($venta['sucursal_nombre'] ?? 'Sucursal 1');
    $folio = str_pad($venta['id_venta'], 4, '0', STR_PAD_LEFT);

    // Construir detalles del ticket con estilo tipo recibo
    $detalle_html = '';
    $subtotal_calc = 0;

    foreach ($items as $d) {
        $producto = htmlspecialchars($d['nombre_producto']);
        $cantidad = number_format((float)$d['cantidad'], 2, '.', '');
        $precio   = number_format((float)$d['precio_unit'], 2, '.', '');
        $subtotal = number_format((float)$d['subtotal'], 2, '.', '');

        $subtotal_calc += (float)$d['subtotal'];

        $detalle_html .= "<div class=\"item-block\">
            <div class=\"item-main\"><span class=\"item-name\">{$producto}</span><span class=\"item-total\">\${$subtotal}</span></div>
            <div class=\"item-meta\">{$cantidad} x \${$precio}</div>
        </div>";
    }

    $descuento = (float)($venta['descuento'] ?? 0);
    $descuento_monto = round($subtotal_calc * $descuento / 100, 2);
    $total = (float)($venta['total'] ?? ($subtotal_calc - $descuento_monto));
    $subtotal_fmt = number_format($subtotal_calc, 2, '.', ',');
    $descuento_fmt = number_format($descuento_monto, 2, '.', ',');
    $total_fmt = number_format($total, 2, '.', ',');

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Ticket #<?php echo htmlspecialchars($folio); ?></title>
      <style>
        :root {
          --verde: #0aafa0;
          --verde-dk: #087a70;
          --text: #1a2e2c;
          --muted: #5a8a84;
          --border: #d0ecea;
          --fondo: #ebfaf8;
          --surface: #f9fffe;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 13px; }
        body {
          min-height: 100vh;
          background: var(--fondo);
          color: var(--text);
          font-family: 'Courier New', Courier, monospace;
          padding: 16px 12px 32px;
          display: flex;
          justify-content: center;
        }

        .ticket-card {
          width: min(360px, 100%);
          max-width: 360px;
          background: var(--surface);
          border: 1px solid var(--border);
          border-radius: 14px;
          box-shadow: 0 16px 30px rgba(0, 0, 0, 0.08);
          overflow: hidden;
        }

        .ticket-body {
          padding: 16px 16px 14px;
        }

        .ticket-section {
          margin-bottom: 12px;
        }
        .ticket-section:last-child {
          margin-bottom: 0;
        }

        .ticket-header {
          text-align: center;
          margin-bottom: 14px;
          padding-bottom: 12px;
          border-bottom: 1px dashed #ccc;
        }
        .ticket-header .title {
          font-size: 15px;
          font-weight: 700;
          letter-spacing: 0.8px;
          margin-bottom: 4px;
        }
        .ticket-header .subtitle {
          color: #555;
          font-size: 11px;
          line-height: 1.6;
        }

        .ticket-info {
          font-size: 12px;
          line-height: 1.9;
          color: #2f423f;
        }
        .ticket-info div {
          margin-bottom: 5px;
        }
        .ticket-info strong {
          font-weight: 700;
        }

        .items-block {
          border-top: 1px dashed #ccc;
          padding-top: 12px;
          margin-top: 12px;
        }

        .item-block {
          margin-bottom: 12px;
        }
        .item-main {
          display: flex;
          justify-content: space-between;
          gap: 12px;
          font-size: 11px;
        }
        .item-name {
          max-width: 70%;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
        .item-total {
          text-align: right;
        }
        .item-meta {
          color: #666;
          font-size: 10px;
          margin-top: 3px;
          letter-spacing: 0.2px;
        }

        .summary-block {
          border-top: 1px dashed #ccc;
          padding-top: 12px;
          margin-top: 12px;
          font-size: 11px;
        }
        .summary-row {
          display: flex;
          justify-content: space-between;
          margin-bottom: 8px;
          color: #2f423f;
        }
        .summary-row.total {
          margin-top: 10px;
          font-size: 14px;
          font-weight: 700;
          color: var(--verde);
        }

        .ticket-footer {
          text-align: center;
          padding-top: 16px;
          border-top: 1px dashed #ccc;
          color: #667a76;
          font-size: 11px;
          line-height: 1.7;
        }

        .print-btn {
          position: fixed;
          bottom: 22px;
          right: 22px;
          background: var(--verde);
          color: #fff;
          border: none;
          border-radius: 999px;
          padding: 12px 20px;
          font-size: 13px;
          font-weight: 700;
          cursor: pointer;
          box-shadow: 0 8px 24px rgba(10, 175, 160, 0.32);
          display: flex;
          align-items: center;
          gap: 8px;
          z-index: 999;
        }
        .print-btn:hover { background: var(--verde-dk); }

        @media print {
          body {
            background: #fff;
            padding: 0;
          }
          .ticket-card {
            box-shadow: none;
            border: none;
            width: 100%;
            margin: 0;
          }
          .print-btn { display: none !important; }
          @page { size: 80mm auto; margin: 6mm; }
        }
      </style>
    </head>
    <body>
      <div class="ticket-card">
        <div class="ticket-body">
          <div class="ticket-section ticket-header">
            <div class="title">HLazcano — Prendas de Punto</div>
            <div class="subtitle"><?php echo $tienda; ?> | Tel: 222-555-0000</div>
          </div>

          <div class="ticket-section ticket-info">
            <div>Folio: <strong>#<?php echo htmlspecialchars($folio); ?></strong></div>
            <div>Fecha: <strong><?php echo htmlspecialchars($fechaVenta); ?></strong></div>
            <div>Cliente: <strong><?php echo $nombreCliente; ?></strong></div>
            <div>Atendió: <strong><?php echo htmlspecialchars($_SESSION['usuario_nom'] ?? ''); ?></strong></div>
          </div>

          <div class="ticket-section items-block">
            <?php if (empty($detalle_html)): ?>
              <div style="color:#999; font-size:12px; text-align:center; padding:18px 0;">Sin productos</div>
            <?php else: ?>
              <?php echo $detalle_html; ?>
            <?php endif; ?>
          </div>

          <div class="ticket-section summary-block">
            <div class="summary-row"><span>Subtotal</span><span>$<?php echo $subtotal_fmt; ?></span></div>
            <?php if ($descuento > 0): ?>
              <div class="summary-row"><span>Descuento (<?php echo number_format($descuento, 2, '.', ','); ?>%)</span><span>- $<?php echo $descuento_fmt; ?></span></div>
            <?php endif; ?>
            <div class="summary-row total"><span>TOTAL</span><span>$<?php echo $total_fmt; ?></span></div>
          </div>

          <div class="ticket-section ticket-footer">
            <div><strong>Gracias por su compra</strong></div>
            <div>Para devoluciones contacte a servicio al cliente</div>
          </div>
        </div>
      </div>

      <button class="print-btn" onclick="window.print(); return false;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M6 9V2h12v7"/><rect x="2" y="9" width="20" height="9" rx="2"/>
          <path d="M6 18v4h12v-4"/><circle cx="18" cy="13" r="1" fill="currentColor"/>
        </svg>
        Imprimir / Guardar PDF
      </button>

    </body>
    </html>
    <?php

} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
    exit;
}
