<?php
/**
 * reporte_venta.php
 * 
 * Genera un reporte HTML imprimible de venta.
 * Se abre en nueva pestaña permitiendo al usuario usar Ctrl+P para guardar como PDF.
 * 
 * Parámetros GET:
 *   - id: ID de la venta (obtiene datos de BD)
 */

require_once 'configuration/config.php';
session_start();

// Verificar sesión
if (empty($_SESSION['usuario_id'])) {
    die('Acceso no autorizado');
}

require_once 'configuration/database.php';

// Obtener ID de venta
$id_venta = (int)($_GET['id'] ?? 0);
if ($id_venta <= 0) {
    die('ID de venta inválido');
}

try {
    $db = Database::getInstance()->getConnection();

    // ── Obtener datos de venta ──
    $sqlVenta = "
        SELECT 
            v.*,
            COALESCE(c.nombre, 'Cliente General') AS cliente_nombre,
            t.nombre AS sucursal_nombre
        FROM ventas v
        LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
        LEFT JOIN tiendas t ON v.id_tienda = t.id_tienda
        WHERE v.id_venta = ?
    ";
    $stmtVenta = $db->prepare($sqlVenta);
    $stmtVenta->execute([$id_venta]);
    $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        die('Venta no encontrada');
    }

    // ── Obtener detalles de venta ──
    $sqlDetalles = "
        SELECT 
            d.cantidad,
            d.precio_unit,
            d.subtotal,
            p.nombre AS nombre_producto
        FROM detalle_venta d
        JOIN productos p ON d.id_producto = p.id_producto
        WHERE d.id_venta = ?
        ORDER BY d.id_detalle ASC
    ";
    $stmtDetalles = $db->prepare($sqlDetalles);
    $stmtDetalles->execute([$id_venta]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    // ── Preparar datos ──
    $folio = str_pad($venta['id_venta'], 4, '0', STR_PAD_LEFT);
    $fecha = $venta['fecha'] ? date('d/m/Y H:i', strtotime($venta['fecha'])) : date('d/m/Y H:i');
    $cliente = htmlspecialchars($venta['cliente_nombre'] ?? 'Cliente General');
    $sucursal = htmlspecialchars($venta['sucursal_nombre'] ?? 'Sucursal 1');
    $total = (float)($venta['total'] ?? 0);
    $negocio = 'HLazcano — Prendas de Punto';
    $tel = '222-555-0000';

    // Calcular subtotal
    $subtotal = array_sum(array_column($detalles, 'subtotal'));

} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket Venta #<?= $folio ?></title>
  <style>
    /* ── Reset ── */
    *, *::before, *::after { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }
    html { font-size: 14px; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f4f6fa;
      color: #12172b;
      padding: 0;
    }

    /* ── Encabezado ── */
    .report-header {
      background: #1E3A8A;
      color: #fff;
      padding: 18px 32px 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .report-header .org { 
      font-size: 12px; 
      opacity: .85; 
    }
    .report-header .org strong { 
      font-size: 15px;
      display: block;
      margin-bottom: 2px;
    }
    .report-header .fecha { 
      font-size: 11px; 
      opacity: .80; 
      text-align: right; 
    }
    .report-header .fecha strong {
      display: block;
      font-size: 12px;
    }

    /* ── Barra de título ── */
    .report-title-bar {
      background: #fff;
      padding: 16px 32px 12px;
      border-bottom: 2px solid #1E3A8A;
    }
    .report-title-bar h1 { 
      font-size: 20px; 
      font-weight: 700; 
      letter-spacing: -.3px;
      color: #12172b;
    }
    .report-title-bar .subtitle { 
      font-size: 12px; 
      color: #5A6075; 
      margin-top: 4px; 
    }

    /* ── Cuerpo ── */
    .report-body { 
      padding: 24px 32px; 
    }

    /* ── Info de venta ── */
    .info-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
      background: #EFF6FF;
      border: 1px solid #BFDBFE;
      border-radius: 10px;
      padding: 16px;
    }
    .info-item label { 
      font-size: 10px; 
      text-transform: uppercase; 
      letter-spacing: .5px; 
      color: #5A6075; 
      display: block; 
      margin-bottom: 3px;
      font-weight: 600;
    }
    .info-item span { 
      font-size: 13px; 
      font-weight: 600; 
      color: #12172b; 
    }

    /* ── Tabla ── */
    .table-wrap {
      border: 1px solid #D8DCE8;
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }

    table { 
      width: 100%; 
      border-collapse: collapse; 
      font-size: 12.5px; 
    }

    thead tr {
      background: #EDF0F7;
    }

    th {
      padding: 9px 13px;
      text-align: left;
      font-weight: 600;
      font-size: 10.5px;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: #5A6075;
      border-bottom: 1px solid #D8DCE8;
    }

    th.r { text-align: right; }
    th.c { text-align: center; }

    td { 
      padding: 10px 13px; 
      border-bottom: 1px solid #D8DCE8; 
      vertical-align: middle; 
    }

    td.r { text-align: right; }
    td.c { text-align: center; }

    tbody tr:nth-child(even) { 
      background: #f8f9fc; 
    }

    tbody tr:hover {
      background: #f0f1f7;
    }

    tr:last-child td { 
      border-bottom: none; 
    }

    /* ── Totales ── */
    .totals-box {
      background: #EDF0F7;
      border-radius: 10px;
      padding: 16px 20px;
      max-width: 380px;
      margin-left: auto;
      border: 1px solid #D8DCE8;
    }

    .totals-row { 
      display: flex; 
      justify-content: space-between; 
      margin-bottom: 8px; 
      font-size: 13px; 
    }

    .totals-row .label { 
      color: #5A6075; 
    }

    .totals-row .value { 
      font-weight: 600;
      color: #12172b;
    }

    .totals-row.main {
      border-top: 2px solid #D8DCE8;
      padding-top: 12px;
      margin-top: 12px;
      font-size: 16px;
      font-weight: 700;
      color: #2563EB;
    }

    .totals-row.main .label { 
      color: #12172b; 
    }

    .totals-row.main .value { 
      color: #2563EB; 
      font-size: 18px;
    }

    /* ── Pie ── */
    .report-footer {
      margin-top: 32px;
      background: #1E3A8A;
      color: #fff;
      text-align: center;
      padding: 12px;
      font-size: 11px;
      opacity: .95;
    }

    /* ── Botón flotante ── */
    .print-btn {
      position: fixed;
      bottom: 28px;
      right: 28px;
      background: #2563EB;
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 13px 26px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(37,99,235,.4);
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all .2s;
      z-index: 1000;
    }

    .print-btn:hover { 
      background: #1D4ED8;
      box-shadow: 0 6px 28px rgba(37,99,235,.5);
      transform: translateY(-2px);
    }

    .print-btn:active {
      transform: translateY(0);
    }

    .print-icon {
      width: 16px;
      height: 16px;
    }

    /* ── Print Media ── */
    @media print {
      body { 
        background: #fff; 
        font-size: 10pt; 
      }

      .print-btn { 
        display: none !important; 
      }

      .report-header,
      .report-title-bar,
      .report-footer,
      .info-grid,
      .totals-box { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
      }

      thead tr { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
      }

      table { 
        page-break-inside: auto; 
        font-size: 9pt; 
      }

      tr { 
        page-break-inside: avoid; 
        page-break-after: auto; 
      }

      thead { 
        display: table-header-group; 
      }

      tbody tr:nth-child(even) { 
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact; 
      }
    }
  </style>
</head>
<body>

  <!-- Encabezado corporativo -->
  <div class="report-header">
    <div class="org">
      <strong><?= $negocio ?></strong>
      Tel: <?= $tel ?>
    </div>
    <div class="fecha">
      Generado el:<br>
      <strong><?= date('d/m/Y  H:i:s') ?></strong>
    </div>
  </div>

  <!-- Título del reporte -->
  <div class="report-title-bar">
    <h1>TICKET DE VENTA</h1>
    <p class="subtitle">Folio #<?= $folio ?> — <?= $sucursal ?></p>
  </div>

  <!-- Contenido principal -->
  <div class="report-body">

    <!-- Información general -->
    <div class="info-grid">
      <div class="info-item">
        <label>Folio</label>
        <span>#<?= $folio ?></span>
      </div>
      <div class="info-item">
        <label>Fecha</label>
        <span><?= $fecha ?></span>
      </div>
      <div class="info-item">
        <label>Cliente</label>
        <span><?= $cliente ?></span>
      </div>
      <div class="info-item">
        <label>Sucursal</label>
        <span><?= $sucursal ?></span>
      </div>
    </div>

    <!-- Tabla de productos -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th class="c" style="width:50px;">#</th>
            <th>Producto</th>
            <th class="c" style="width:100px;">Cantidad</th>
            <th class="r" style="width:120px;">P. Unit.</th>
            <th class="r" style="width:120px;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($detalles as $i => $d): ?>
          <tr>
            <td class="c"><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($d['nombre_producto'] ?? '—') ?></td>
            <td class="c"><?= number_format((float)$d['cantidad'], 2) ?></td>
            <td class="r">$<?= number_format((float)$d['precio_unit'], 2) ?></td>
            <td class="r" style="font-weight:600;">$<?= number_format((float)$d['subtotal'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Resumen de totales -->
    <div class="totals-box">
      <div class="totals-row">
        <span class="label">Subtotal</span>
        <span class="value">$<?= number_format($subtotal, 2) ?></span>
      </div>
      <?php if ((float)($venta['descuento'] ?? 0) > 0): ?>
      <div class="totals-row">
        <span class="label">Descuento (<?= (float)$venta['descuento'] ?>%)</span>
        <span class="value">-$<?= number_format($subtotal * ((float)$venta['descuento'] / 100), 2) ?></span>
      </div>
      <?php endif; ?>
      <div class="totals-row main">
        <span class="label">TOTAL COBRADO</span>
        <span class="value">$<?= number_format($total, 2) ?></span>
      </div>
    </div>

  </div>

  <!-- Pie del reporte -->
  <div class="report-footer">
    <?= $negocio ?> &nbsp;|&nbsp; <?= $sucursal ?> &nbsp;|&nbsp; Gracias por su compra
  </div>

  <!-- Botón flotante de impresión -->
  <button class="print-btn" onclick="window.print(); return false;" title="Imprimir o guardar como PDF (Ctrl+P)">
    <svg class="print-icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M6 9V2h12v7M2 9h20v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9Z"/>
      <path d="M6 14h12v4H6z"/>
    </svg>
    Imprimir / Guardar PDF
  </button>

</body>
</html>
