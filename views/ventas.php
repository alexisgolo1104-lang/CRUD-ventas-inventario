<div id="screen-ventas" class="screen">

  <!-- ── Sub-tabs de Ventas ───────────────────────────── -->
  <div class="sub-tabs" style="display:flex;gap:0;margin-bottom:16px;border-bottom:1px solid var(--border)">
    <button class="sub-tab active" id="vtab-nueva" onclick="switchVentaTab('nueva')">🛒 Nueva venta</button>
    <button class="sub-tab" id="vtab-historial" onclick="switchVentaTab('historial')">📋 Historial</button>
  </div>

  <!-- ══ PANEL: Nueva Venta ═══════════════════════════════ -->
  <div id="vtab-panel-nueva">
    <div style="display:grid;grid-template-columns:1fr 1fr 340px;gap:16px">
      <!-- Formulario -->
      <div class="card" style="grid-column:span 2">
        <div class="card-title">Registrar venta</div>
        <div class="form-row form-2">
          <div class="form-group"><label class="form-label">Tienda</label>
            <select class="form-control" id="venta-tienda" onchange="updateTicketPreview()">
              <option value="1">Sucursal 1 — Santa María Texmelucan</option>
              <option value="2">Sucursal 2 — Por definir</option>
            </select></div>
          <div class="form-group"><label class="form-label">Cliente</label>
            <select class="form-control" id="venta-cliente" onchange="updateTicketPreview()">
              <option value="">Sin cliente (venta directa)</option>
              <option value="1">María González</option>
              <option value="2">Artesanías del Valle</option>
              <option value="3">Juan Pérez</option>
              <option value="4">Taller El Hilo Feliz</option>
              <option value="5">Rosa Martínez</option>
            </select></div>
        </div>
        <div class="form-group mb-12">
          <label class="form-label">Agregar producto del catálogo</label>
          <div class="flex gap-8">
            <select class="form-control" id="prod-sel-ventas" style="flex:2">
              <option value="">Seleccionar producto...</option>
              <option value="acr-blanco">Hilo Acrílico Blanco 500g — Stock: 12 kg — $65.00/kg</option>
              <option value="nyl-negro">Hilo Nylon Negro 1kg — Stock: 2 kg ⚠ — $110.00/kg</option>
              <option value="alg-rojo">Hilo Algodón Rojo 250g — Stock: 4 kg — $78.00/kg</option>
              <option value="pol-azul">Hilo Poliéster Azul 500g — Stock: 18 kg — $55.00/kg</option>
              <option value="lan-verde">Hilo Lana Verde 100g — Stock: 9 kg — $130.00/kg</option>
              <option value="sed-lav">Hilo Seda Lavanda 50g — Stock: 4 kg — $280.00/kg</option>
              <option value="mer-rosa">Hilo Mercerizado Rosa 100g — Stock: 1 kg ⚠ — $95.00/kg</option>
            </select>
            <input class="form-control" id="cant-inp-ventas" style="width:80px" type="number" value="1" min="0.1" step="0.1">
            <button class="btn btn-primary" onclick="addToCart('ventas');updateTicketPreview()">+ Agregar</button>
          </div>
          <div class="form-hint">El precio se asigna automáticamente desde el catálogo</div>
        </div>
        <div class="table-wrap mb-12">
          <table><thead><tr><th>Producto</th><th>Cant.</th><th>P.Unit</th><th>Subtotal</th><th></th></tr></thead>
          <tbody id="cart-body-ventas"><tr><td colspan="5" style="text-align:center;color:var(--hint);padding:16px">Sin productos — selecciona del catálogo</td></tr></tbody>
          </table>
        </div>
        <div class="cart-total-box">
          <div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-val" id="cart-total-ventas">$0.00</span></div>
          <div class="cart-total-row"><span class="cart-total-label font-bold" style="font-size:15px">Total</span><span class="cart-total-val cart-total-main" id="cart-total-ventas2">$0.00</span></div>
        </div>
        <div class="form-group" style="margin-top:12px">
          <label class="form-label">Notas (opcional)</label>
          <input class="form-control" id="venta-notas" placeholder="Ej: entrega a domicilio, pago diferido...">
        </div>
        <button class="btn btn-primary w-full" style="justify-content:center;padding:11px;margin-top:8px" onclick="registrarVentaFinal()">✓ Registrar venta y ver ticket</button>
      </div>
      <!-- Ticket preview -->
      <div class="card" style="background:var(--surf2);border-style:dashed">
        <div class="card-title" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Vista previa del ticket</div>
        <div id="ticket-preview" style="background:#fff;border:1px solid var(--border);border-radius:8px;padding:16px;font-size:12px;font-family:monospace;line-height:1.7;box-shadow:0 2px 8px rgba(0,0,0,.06)">
          <div style="text-align:center;margin-bottom:10px;padding-bottom:10px;border-bottom:1px dashed #ccc">
            <div style="font-weight:700;font-size:13px">HLazcano — Prendas de Punto</div>
            <div style="font-size:10px;color:#666">Sucursal 1 — Av. Del Trabajo #72, Santa María Texmelucan</div>
            <div style="font-size:10px;color:#666">Tel: 222-555-0000</div>
          </div>
          <div style="color:#888;font-size:10px;margin-bottom:8px">
            <div>Folio: <strong>#----</strong></div><div>Fecha: --/--/---- --:--</div>
            <div>Cliente: <strong>—</strong></div><div>Atendió: <strong>—</strong></div>
          </div>
          <div style="border-top:1px dashed #ccc;padding-top:8px;color:#aaa;font-size:11px;text-align:center">Sin productos</div>
          <div style="border-top:1px dashed #ccc;margin-top:10px;padding-top:8px;display:none" id="ticket-total-section">
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:13px">
              <span>TOTAL</span><span id="ticket-total-val">$0.00</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /vtab-panel-nueva -->

  <!-- ══ PANEL: Historial ══════════════════════════════════ -->
  <div id="vtab-panel-historial" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div class="text-muted text-sm">Historial de ventas registradas</div>
      <button class="btn" onclick="exportarTabla('tbl-ventas')">⬇ Exportar</button>
    </div>
    <div class="filter-bar">
      <input class="form-control" id="venta-buscar" placeholder="🔍 Folio, cliente..." oninput="filtrarVentas()" style="max-width:220px">
      <select class="form-control" id="venta-fil-tienda" onchange="filtrarVentas()" style="max-width:160px">
        <option value="">Todas las tiendas</option><option value="S1">Sucursal 1</option><option value="S2">Sucursal 2</option>
      </select>
      <select class="form-control" id="venta-fil-estado" onchange="filtrarVentas()" style="max-width:140px">
        <option value="">Todos los estados</option><option value="ok">✅ OK</option><option value="cancelada">❌ Cancelada</option>
      </select>
      <input class="form-control" id="venta-fil-desde" type="date" onchange="filtrarVentas()" title="Desde" style="max-width:150px">
      <input class="form-control" id="venta-fil-hasta" type="date" onchange="filtrarVentas()" title="Hasta" style="max-width:150px">
      <button class="btn" onclick="limpiarFiltrosVentas()">✕ Limpiar</button>
    </div>
    <div class="table-wrap">
      <table id="tbl-ventas">
        <thead><tr><th>Folio</th><th>Fecha</th><th>Cliente</th><th>Productos</th><th>Total</th><th>Tienda</th><th>Empleado</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody id="tbody-ventas">
          <tr><td colspan="9" style="text-align:center;padding:24px;color:var(--muted)">Cargando...</td></tr>
        </tbody>
      </table>
    </div>
  </div><!-- /vtab-panel-historial -->

</div>

<!-- ══ MODAL: Detalle de venta ═══════════════════════════════ -->
<div class="modal-overlay" id="modal-detalle-venta" onclick="closeOut(event,'modal-detalle-venta')">
  <div class="modal" style="max-width:520px">
    <div class="modal-header">
      <div class="modal-title" id="modal-detalle-titulo">Detalle de venta</div>
      <button class="modal-close" onclick="closeModal('modal-detalle-venta')">✕</button>
    </div>
    <div class="modal-body" id="modal-detalle-body">
      <div style="text-align:center;color:var(--hint);padding:32px">Cargando...</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-detalle-venta')">Cerrar</button>
      <button class="btn" onclick="showToast('Ticket listo para imprimir')">🖨 Imprimir</button>
      <button class="btn" onclick="showToast('Enviando por WhatsApp...')">📤 WhatsApp</button>
    </div>
  </div>
</div>

<!-- ══ MODAL: Cancelar venta ══════════════════════════════════ -->
<div class="modal-overlay" id="modal-cancelar-venta" onclick="closeOut(event,'modal-cancelar-venta')">
  <div class="modal" style="max-width:420px">
    <div class="modal-header">
      <div class="modal-title">⚠️ Cancelar venta</div>
      <button class="modal-close" onclick="closeModal('modal-cancelar-venta')">✕</button>
    </div>
    <div class="modal-body">
      <div class="notice notice-danger">¿Seguro que deseas cancelar esta venta? El stock de los productos se restaurará automáticamente.<br><br>Folio: <strong id="cancelar-venta-folio"></strong></div>
      <div class="form-group" style="margin-top:12px">
        <label class="form-label">Motivo de cancelación *</label>
        <select class="form-control" id="cancelar-motivo">
          <option value="">Seleccionar...</option>
          <option>Error de registro</option><option>Devolución del cliente</option>
          <option>Pago rechazado</option><option>Otro</option>
        </select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cancelar-venta')">No, mantener</button>
      <button class="btn btn-danger" onclick="ejecutarCancelarVenta()">✕ Sí, cancelar venta</button>
    </div>
  </div>
</div>
