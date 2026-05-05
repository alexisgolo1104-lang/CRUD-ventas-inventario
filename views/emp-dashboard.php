<div id="screen-emp-dashboard" class="screen">
  <div class="notice notice-info mb-16" id="emp-dash-sucursal">📍 <strong>Cargando sucursal...</strong></div>
  <div class="grid-4" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card" style="border-color:var(--accent2)"><div class="stat-num" style="color:var(--accent)" id="emp-ventas-hoy">$0</div><div class="stat-lbl">Mis ventas hoy</div></div>
    <div class="stat-card"><div class="stat-num" id="emp-transacciones">0</div><div class="stat-lbl">Transacciones</div></div>
    <div class="stat-card" style="border-color:#fca5a5"><div class="stat-num" style="color:var(--danger)" id="emp-alertas-count">0</div><div class="stat-lbl">Alertas activas</div></div>
    <div class="stat-card"><div class="stat-num" id="emp-ticket-prom">$0</div><div class="stat-lbl">Ticket promedio</div></div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Acciones rápidas</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('emp-ventas',null)">
          <span style="font-size:22px">🛒</span><span class="font-bold">Registrar venta</span><span class="text-muted text-sm">Agregar productos</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('clientes',null)">
          <span style="font-size:22px">👥</span><span class="font-bold">Clientes</span><span class="text-muted text-sm">Buscar o registrar</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('localizador',null)">
          <span style="font-size:22px">🗺️</span><span class="font-bold">Localizador</span><span class="text-muted text-sm">Encontrar producto</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px;border-color:#fca5a5;background:var(--dangbg)" onclick="go('alertas',null)">
          <span style="font-size:22px">🔔</span><span class="font-bold" style="color:var(--danger)">Alertas</span><span class="text-muted text-sm" id="emp-alertas-label">—</span>
        </button>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Mis ventas de hoy</div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Hora</th></tr></thead>
        <tbody id="emp-tbody-ventas">
          <tr><td colspan="4" style="text-align:center;padding:16px;color:var(--muted)">Cargando...</td></tr>
        </tbody></table>
      </div>
      <div class="cart-total-box mt-8" style="margin-bottom:0">
        <div class="cart-total-row"><span class="cart-total-label">Total del turno</span><span class="cart-total-val" style="color:var(--accent)" id="emp-total-turno">$0.00</span></div>
      </div>
    </div>
  </div>
</div>
