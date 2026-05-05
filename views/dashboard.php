<div id="screen-dashboard" class="screen active">
  <div class="grid-4">
    <div class="stat-card"><div class="stat-num" style="color:#2563EB" id="dash-ventas-mes">$0</div><div class="stat-lbl">Ventas del mes</div><div class="stat-delta up" id="dash-ventas-delta">—</div></div>
    <div class="stat-card"><div class="stat-num" id="dash-productos">0</div><div class="stat-lbl">Productos registrados</div><div class="stat-delta up" id="dash-prod-delta">—</div></div>
    <div class="stat-card"><div class="stat-num" style="color:var(--danger)" id="dash-alertas">0</div><div class="stat-lbl">Alertas activas</div><div class="stat-delta dn" id="dash-alertas-delta">—</div></div>
    <div class="stat-card"><div class="stat-num" id="dash-clientes">0</div><div class="stat-lbl">Clientes registrados</div><div class="stat-delta up" id="dash-clientes-delta">—</div></div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Últimas ventas<button class="btn btn-sm" onclick="go('ventas',null)">Ver todas →</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Tienda</th></tr></thead>
        <tbody id="dash-tbody-ventas">
          <tr><td colspan="4" style="text-align:center;padding:16px;color:var(--muted)">Cargando...</td></tr>
        </tbody></table>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Productos con alerta<button class="btn btn-sm" onclick="go('alertas',null)">Ver alertas →</button></div>
      <div id="dash-alertas-lista">
        <div style="text-align:center;padding:16px;color:var(--muted)">Cargando...</div>
      </div>
    </div>
  </div>
</div>
