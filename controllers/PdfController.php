<?php
/**
 * controllers/PdfController.php
 * 
 * Genera documentos PDF imprimibles (HTML puro).
 * Los PDFs se abren en nueva pestaña permitiendo al usuario
 * usar Ctrl+P para imprimir o guardar como PDF desde el navegador.
 * 
 * Patrón MVC basado en ReportesController del proyecto juventudiskali.
 */

class PdfController
{
    private PDO     $db;
    private PdfModel $model;

    public function __construct(PDO $conexion)
    {
        $this->db = $conexion;
        require_once 'models/PdfModel.php';
        $this->model = new PdfModel($conexion);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Seguridad de sesión
    // ─────────────────────────────────────────────────────────────────────────

    private function verificarSesion(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            http_response_code(403);
            die('Acceso no autorizado');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  ENRUTADOR - Dirige a la acción solicitada
    // ─────────────────────────────────────────────────────────────────────────

    public function procesar(): void
    {
        $this->verificarSesion();

        $accion = trim($_GET['accion'] ?? '');
        $tipo = trim($_GET['tipo'] ?? '');

        switch ($accion) {
            case 'ticket':
                $this->generarTicket();
                break;
            default:
                http_response_code(400);
                die('Acción no válida');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GENERAR TICKET PDF
    // ─────────────────────────────────────────────────────────────────────────

    private function generarTicket(): void
    {
        $id_venta = (int)($_GET['id'] ?? 0);
        if ($id_venta <= 0) {
            http_response_code(400);
            die('ID de venta invalido');
        }

        $ticket = $this->model->getTicketCompleto($id_venta);
        if (!$ticket) {
            http_response_code(404);
            die('Venta no encontrada');
        }

        $empresa = $this->model->getEmpresaInfo();
        $this->renderTicket($empresa, $ticket);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  RENDERIZAR - Genera HTML imprimible del ticket
    // ─────────────────────────────────────────────────────────────────────────

    private function renderTicket(array $empresa, array $ticket): void
    {
        $venta     = $ticket['venta'];
        $detalles  = $ticket['detalles'];
        $folio     = $ticket['folio'];
        $fecha     = $ticket['fecha'];

        $nombre_empresa = htmlspecialchars($empresa['nombre']);
        $telefono = htmlspecialchars($empresa['telefono']);
        $cliente = htmlspecialchars($venta['cliente_nombre']);
        $sucursal = htmlspecialchars($venta['sucursal_nombre'] ?? 'Sucursal 1');

        // Construir tabla de detalles
        $filas_detalles = '';
        $subtotal_calc = 0;

        foreach ($detalles as $d) {
            $producto = htmlspecialchars($d['nombre_producto']);
            $cantidad = number_format((float)$d['cantidad'], 2, '.', '');
            $precio   = number_format((float)$d['precio_unit'], 2, '.', '');
            $subtotal = number_format((float)$d['subtotal'], 2, '.', '');

            $subtotal_calc += (float)$d['subtotal'];

            $filas_detalles .= "<tr>
                <td style=\"text-align:center;\">{$cantidad}</td>
                <td>{$producto}</td>
                <td style=\"text-align:right;\">\${$precio}</td>
                <td style=\"text-align:right;\">\${$subtotal}</td>
            </tr>";
        }

        $descuento = (float)($venta['descuento'] ?? 0);
        $descuento_monto = round($subtotal_calc * $descuento / 100, 2);
        $total = (float)($venta['total'] ?? ($subtotal_calc - $descuento_monto));
        $subtotal_fmt = number_format($subtotal_calc, 2, '.', ',');
        $descuento_fmt = number_format($descuento_monto, 2, '.', ',');
        $total_fmt = number_format($total, 2, '.', ',');

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Ticket #{$folio}</title>
          <style>
            :root {
              --verde: #0aafa0;
              --verde-dk: #087a70;
              --text: #1a2e2c;
              --muted: #5a8a84;
              --border: #d0ecea;
              --fondo: #f4fffe;
              --alt-row: #e8f9f7;
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }
            html { font-size: 14px; }
            body {
              font-family: 'Segoe UI', Arial, sans-serif;
              background: var(--fondo);
              color: var(--text);
              padding: 0;
            }

            .ticket-header {
              background: var(--verde);
              color: #fff;
              padding: 20px;
              text-align: center;
            }
            .ticket-header h1 {
              font-size: 18px;
              font-weight: 700;
              margin-bottom: 4px;
            }
            .ticket-header p {
              font-size: 12px;
              opacity: 0.9;
            }

            .ticket-info {
              background: #fff;
              padding: 20px;
              border-bottom: 1px solid var(--border);
            }
            .info-row {
              display: grid;
              grid-template-columns: 150px 1fr;
              gap: 16px;
              margin-bottom: 12px;
              font-size: 13px;
            }
            .info-label {
              font-weight: 600;
              color: var(--muted);
            }
            .info-value {
              color: var(--text);
            }

            .ticket-body {
              padding: 20px;
            }

            table {
              width: 100%;
              border-collapse: collapse;
              margin-bottom: 20px;
              font-size: 12px;
            }
            thead tr {
              background: var(--verde);
              color: #fff;
            }
            thead th {
              padding: 10px;
              text-align: left;
              font-weight: 600;
              font-size: 11.5px;
            }
            tbody tr:nth-child(even) { background: var(--alt-row); }
            tbody tr:nth-child(odd) { background: #fff; }
            tbody td {
              padding: 10px;
              border-bottom: 1px solid var(--border);
            }

            .totals-box {
              background: var(--fondo);
              border: 1px solid var(--border);
              border-radius: 8px;
              padding: 16px;
              margin-bottom: 20px;
            }
            .total-row {
              display: flex;
              justify-content: space-between;
              margin-bottom: 8px;
              font-size: 13px;
            }
            .total-row.main {
              font-size: 16px;
              font-weight: 700;
              color: var(--verde);
              border-top: 2px solid var(--border);
              padding-top: 12px;
              margin-top: 12px;
            }

            .ticket-footer {
              text-align: center;
              padding: 20px;
              font-size: 12px;
              color: var(--muted);
              border-top: 1px solid var(--border);
            }

            .print-btn {
              position: fixed;
              bottom: 28px;
              right: 28px;
              background: var(--verde);
              color: #fff;
              border: none;
              border-radius: 50px;
              padding: 13px 26px;
              font-size: 14px;
              font-weight: 700;
              cursor: pointer;
              box-shadow: 0 4px 20px rgba(10,175,160,.4);
              display: flex;
              align-items: center;
              gap: 8px;
              transition: background .2s;
            }
            .print-btn:hover { background: var(--verde-dk); }

            @media print {
              body { background: #fff; font-size: 10pt; }
              .print-btn { display: none !important; }
              .ticket-header, .ticket-info, .ticket-body, .ticket-footer {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
              }
              thead tr {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
              }
              table { page-break-inside: auto; }
              tr { page-break-inside: avoid; page-break-after: auto; }
            }
          </style>
        </head>
        <body>

          <!-- Encabezado del ticket -->
          <div class="ticket-header">
            <h1>{$nombre_empresa}</h1>
            <p>{$sucursal} | Tel: {$telefono}</p>
          </div>

          <!-- Información del ticket -->
          <div class="ticket-info">
            <div class="info-row">
              <span class="info-label">Folio:</span>
              <span class="info-value">#{$folio}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Fecha:</span>
              <span class="info-value">{$fecha}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Cliente:</span>
              <span class="info-value">{$cliente}</span>
            </div>
          </div>

          <!-- Contenido del ticket -->
          <div class="ticket-body">
            <table>
              <thead>
                <tr>
                  <th style="width:80px;text-align:center;">Cantidad</th>
                  <th>Producto</th>
                  <th style="width:100px;text-align:right;">P.Unit.</th>
                  <th style="width:100px;text-align:right;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                {$filas_detalles}
              </tbody>
            </table>

            <!-- Totales -->
            <div class="totals-box">
              <div class="total-row">
                <span>Subtotal:</span>
                <span>\${$subtotal_fmt}</span>
              </div>
              HTML;
        
        if ($descuento > 0) {
            $html .= "<div class=\"total-row\">
                <span>Descuento ({$descuento}%):</span>
                <span>-\${$descuento_fmt}</span>
              </div>";
        }

        $html .= <<<HTML
              <div class="total-row main">
                <span>TOTAL:</span>
                <span>\${$total_fmt}</span>
              </div>
            </div>
          </div>

          <!-- Pie del ticket -->
          <div class="ticket-footer">
            <p><strong>Gracias por su compra</strong></p>
            <p>Para devoluciones contacte a servicio al cliente</p>
          </div>

          <!-- Botón de impresión -->
          <button class="print-btn" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                 stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M6 9V2h12v7"/><rect x="2" y="9" width="20" height="9" rx="2"/>
              <path d="M6 18v4h12v-4"/><circle cx="18" cy="13" r="1" fill="currentColor"/>
            </svg>
            Imprimir / Guardar PDF
          </button>

        </body>
        </html>
        HTML;

        echo $html;
        exit;
    }
}
