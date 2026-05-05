<div id="screen-cliente-perfil" class="screen">
  <div class="flex items-center gap-12 mb-16">
    <button class="btn" onclick="go('clientes',null)">← Clientes</button>
    <div class="font-bold" style="font-size:15px;font-family:'Segoe UI',Arial,sans-serif">Artesanías del Valle</div>
    <span class="badge badge-info" style="margin-left:4px">Taller</span>
    <div class="flex gap-8 ml-auto">
      <button class="btn" onclick="openModal('modal-cliente')">✏️ Editar</button>
      <button class="btn btn-danger" onclick="confirmDelete('¿Eliminar a Artesanías del Valle? Esta acción no se puede deshacer.',()=>go('clientes',null))">🗑 Eliminar</button>
    </div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Información del cliente</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div><div class="form-label">Nombre</div><div class="font-bold">Artesanías del Valle</div></div>
        <div><div class="form-label">Tipo</div><span class="badge badge-info">Taller / Negocio</span></div>
        <div><div class="form-label">Teléfono</div><div>222-555-0202</div></div>
        <div><div class="form-label">Correo</div><div class="text-sm">[correo@empresa.com]</div></div>
        <div><div class="form-label">Tienda preferida</div><div>Sucursal 1 — Santa María Texmelucan</div></div>
        <div><div class="form-label">RFC</div><div>AVAL920318BC4</div></div>
        <div style="grid-column:span 2"><div class="form-label">Dirección</div><div>Av. Reforma #48, Santa María Texmelucan</div></div>
        <div style="grid-column:span 2"><div class="form-label">Notas</div><div class="text-sm" style="color:var(--muted);background:var(--surf2);padding:8px 10px;border-radius:7px">Taller de tejidos. Pedidos grandes los viernes. Solicita factura cuando el monto supera $500.</div></div>
      </div>
    </div>
    <div>
      <div class="grid-2" style="margin-bottom:14px">
        <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$18,340</div><div class="stat-lbl">Total gastado</div></div>
        <div class="stat-card"><div class="stat-num">28</div><div class="stat-lbl">Compras totales</div></div>
        <div class="stat-card"><div class="stat-num">$655</div><div class="stat-lbl">Ticket promedio</div></div>
        <div class="stat-card"><div class="stat-num">Vie.</div><div class="stat-lbl">Día frecuente</div></div>
      </div>
    </div>
  </div>
  <div class="card mt-16">
    <div class="card-title">Historial de compras <button class="btn btn-sm btn-primary" onclick="go('ventas',null)">+ Nueva venta</button></div>
    <div class="table-wrap">
      <table><thead><tr><th>Folio</th><th>Fecha</th><th>Productos</th><th>Total</th><th>Tienda</th><th></th></tr></thead>
      <tbody>
        <tr><td>#0039</td><td>13/03/2025</td><td>Hilo Acrílico, Nylon, Poliéster</td><td><strong>$1,340.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0031</td><td>05/03/2025</td><td>Hilo Algodón x8, Lana x3</td><td><strong>$890.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0024</td><td>26/02/2025</td><td>Hilo Nylon Negro x10</td><td><strong>$1,100.00</strong></td><td>S2</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0018</td><td>18/02/2025</td><td>Hilo Acrílico Blanco x15</td><td><strong>$975.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0012</td><td>10/02/2025</td><td>Pedido mixto — 6 tipos</td><td><strong>$2,200.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ EMPLEADO: DASHBOARD ════════════════════════════════ -->