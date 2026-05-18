<div id="screen-venta-confirmada" class="screen">
  <div class="confirmada-body">
    <div style="width:100%;display:flex;flex-direction:column;gap:16px">
      <div class="notice notice-success" style="font-size:13px;font-weight:600;margin:0">
        <span class="venta-folio">✅ Venta registrada exitosamente</span>
      </div>
      <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(320px,360px);gap:20px;align-items:start">
        <div class="card">
          <div class="card-title">Resumen de la venta — <span class="font-bold">#0043</span></div>
          <div class="text-sm text-muted venta-cliente-info" style="margin-bottom:14px">Cliente: —</div>
          <div class="table-wrap mb-12">
            <table><thead><tr><th>Producto</th><th>Cantidad</th><th>P.Unit.</th><th>Subtotal</th><th>Acción</th></tr></thead>
            <tbody class="venta-items-body">
              <tr><td colspan="5" style="text-align:center;color:var(--hint)">Sin productos</td></tr>
            </tbody></table>
          </div>
          <div class="cart-total-box">
            <div class="cart-total-row">
              <span class="cart-total-label">Subtotal</span>
              <span class="cart-total-val venta-subtotal">$0.00</span>
            </div>
            <div class="cart-total-row venta-desc-row" style="display:none">
              <span class="cart-total-label" style="color:var(--danger)">Descuento</span>
              <span class="cart-total-val venta-desc-val" style="color:var(--danger)">-$0.00</span>
            </div>
            <div class="cart-total-row">
              <span class="cart-total-label font-bold" style="font-size:15px">Total cobrado</span>
              <span class="cart-total-val cart-total-main venta-total-main">$0.00</span>
            </div>
          </div>
          <div class="flex gap-8 mt-12">
            <button class="btn" onclick="go('ventas',null)">← Nueva venta</button>
            <button class="btn" onclick="descargarTicketPDF()">⬇ Descargar ticket</button>
            <button class="btn" onclick="showToast('Enviando por WhatsApp...')">📤 Enviar por WhatsApp</button>
            <button class="btn btn-primary" onclick="imprimirSoloTicket()">🖨 Imprimir</button>
          </div>
        </div>
        <div class="card" style="background:var(--surf2)">
          <div class="card-title" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Ticket de venta</div>
          <div class="ticket-side" style="background:#fff;border:1px solid var(--border);border-radius:8px;padding:16px;font-size:11px;font-family:monospace;line-height:1.7;box-shadow:0 2px 8px rgba(0,0,0,.06)">
            — ticket —
          </div>
          <div class="flex gap-8 mt-12">
            <button class="btn btn-sm" style="flex:1;justify-content:center" onclick="descargarTicketPDF()">⬇ PDF</button>
            <button class="btn btn-sm" style="flex:1;justify-content:center" onclick="imprimirSoloTicket()">🖨 Imprimir</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
