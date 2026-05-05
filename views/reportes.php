<div id="screen-reportes" class="screen">
  <div class="rep-tabs">
    <div class="rep-tab active" onclick="setRepTab(this);showRepContent('rep-ventas')">📊 Ventas</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-inventario')">📦 Inventario</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-compras')">📥 Compras</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-egresos')">💰 Egresos</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-clientes')">👥 Clientes</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-comparativo')">📈 Comparativo</div>
  </div>
  <div class="filter-bar mb-16">
    <div class="form-group"><label class="form-label">Periodo</label><select class="form-control"><option>Este mes</option><option>Esta semana</option><option>Último trimestre</option><option>Este año</option></select></div>
    <div class="form-group"><label class="form-label">Tienda</label><select class="form-control"><option>Todas las tiendas</option><option>Sucursal 1</option><option>Sucursal 2</option></select></div>
    <div class="form-group"><label class="form-label">Agrupación</label><select class="form-control"><option>Por día</option><option>Por semana</option><option>Por mes</option><option>Por tipo de hilo</option><option>Por empleado</option></select></div>
    <div class="form-group" style="align-self:flex-end;gap:6px;display:flex">
      <button class="btn btn-primary">Ver reporte</button>
      <button class="btn" onclick="showToast('Generando PDF...')">⬇ PDF</button>
      <button class="btn" onclick="showToast('Generando Excel...')">⬇ Excel</button>
    </div>
  </div>

  <!-- VENTAS -->
  <div id="rep-ventas" class="rep-panel">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:#2563EB">$18,420</div><div class="stat-lbl">Ventas totales</div><div class="stat-delta up">↑ 12% vs mes ant.</div></div>
      <div class="stat-card"><div class="stat-num">142</div><div class="stat-lbl">Transacciones</div><div class="stat-delta up">↑ 8 este mes</div></div>
      <div class="stat-card"><div class="stat-num">$129.70</div><div class="stat-lbl">Ticket promedio</div><div class="stat-delta up">↑ 4%</div></div>
      <div class="stat-card"><div class="stat-num">84%</div><div class="stat-lbl">Catálogo vendido</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Ventas por día — Marzo 2025 <small style="color:var(--muted);font-size:11px">Pico máx: 21 Mar — $2,340</small></div>
        <div class="bar-chart" style="height:120px">
          <div class="bar" style="height:45%" title="$800"></div><div class="bar" style="height:68%" title="$1,200"></div><div class="bar" style="height:38%" title="$700"></div>
          <div class="bar" style="height:85%" title="$1,560"></div><div class="bar" style="height:60%" title="$1,100"></div><div class="bar hi" style="height:92%" title="$1,700"></div>
          <div class="bar" style="height:70%" title="$1,280"></div><div class="bar" style="height:55%" title="$1,000"></div><div class="bar" style="height:78%" title="$1,430"></div>
          <div class="bar" style="height:82%" title="$1,500"></div><div class="bar" style="height:65%" title="$1,190"></div><div class="bar" style="height:44%" title="$800"></div>
          <div class="bar hi" style="height:100%" title="$2,340"></div><div class="bar" style="height:74%" title="$1,360"></div>
        </div>
        <div class="flex justify-between mt-8 text-sm text-muted"><span>S1: $11,240</span><span>Pico: $2,340</span><span>S2: $7,180</span></div>
      </div>
      <div class="card">
        <div class="card-title">Top 5 productos más vendidos</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥇 Hilo Acrílico Blanco</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:85%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">$4,420</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥈 Hilo Poliéster Azul</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:67%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$3,410</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥉 Hilo Nylon Negro</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:52%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$2,640</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">4. Hilo Algodón Rojo</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:38%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$1,925</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">5. Hilo Lana Verde</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:24%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$1,210</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Detalle de transacciones — Marzo 2025 <button class="btn btn-sm" onclick="showToast('Exportando tabla...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Fecha</th><th>Cliente</th><th>Productos</th><th>Tienda</th><th>Empleado</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>#0042</td><td>14/03/2025</td><td>María González</td><td>Acrílico + Poliéster</td><td>S1</td><td>Ana R.</td><td><strong>$185.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0041</td><td>14/03/2025</td><td>Venta directa</td><td>Acrílico Blanco x2</td><td>S1</td><td>Ana R.</td><td><strong>$130.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0040</td><td>13/03/2025</td><td>J. Pérez</td><td>Nylon Negro x5</td><td>S2</td><td>Carlos M.</td><td><strong>$550.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0039</td><td>13/03/2025</td><td>Artesanías V.</td><td>Pedido mixto</td><td>S1</td><td>Ana R.</td><td><strong>$1,340.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0038</td><td>12/03/2025</td><td>M. González</td><td>Poliéster Azul x4</td><td>S1</td><td>Hernán M.</td><td><strong>$220.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- INVENTARIO -->
  <div id="rep-inventario" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num">347</div><div class="stat-lbl">Productos registrados</div></div>
      <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$28,450</div><div class="stat-lbl">Valor del inventario</div></div>
      <div class="stat-card" style="border-color:#fca5a5;background:var(--dangbg)"><div class="stat-num" style="color:var(--danger)">5</div><div class="stat-lbl">Bajo stock</div></div>
      <div class="stat-card"><div class="stat-num">2</div><div class="stat-lbl">Sin movimiento +30d</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Distribución por tipo de hilo</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Acrílico</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:55%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">55%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Algodón</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:18%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">18%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Nylon</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:10%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">10%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Poliéster</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:9%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">9%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Lana</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:5%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">5%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Otros</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:3%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">3%</span></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Valor por tienda</div>
        <div style="background:var(--accentbg);border:1px solid var(--accent2);border-radius:10px;padding:14px;margin-bottom:10px">
          <div class="font-bold">Sucursal 1 — Santa María Texmelucan</div>
          <div class="text-muted text-sm">213 productos</div>
          <div style="font-family:'Segoe UI',Arial,sans-serif;font-size:22px;font-weight:700;color:var(--accent)">$18,290</div>
        </div>
        <div style="background:var(--surf2);border-radius:10px;padding:14px">
          <div class="font-bold">Sucursal 2 — Por definir</div>
          <div class="text-muted text-sm">134 productos</div>
          <div style="font-family:'Segoe UI',Arial,sans-serif;font-size:22px;font-weight:700">$10,160</div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Estado actual del inventario <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th><th>Mín.</th><th>Valor</th><th>Tienda</th><th>Anaquel</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>Hilo Acrílico Blanco</td><td>Acrílico</td><td>Blanco</td><td>12 kg</td><td>5 kg</td><td>$540</td><td>S1</td><td>A-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
          <tr><td>Hilo Nylon Negro</td><td>Nylon</td><td>Negro</td><td>2 kg</td><td>5 kg</td><td>$160</td><td>S2</td><td>B-2</td><td><span class="badge badge-danger">⚠ Bajo</span></td></tr>
          <tr><td>Hilo Algodón Rojo</td><td>Algodón</td><td>Rojo</td><td>4 kg</td><td>5 kg</td><td>$220</td><td>S1</td><td>A-2</td><td><span class="badge badge-warn">Mínimo</span></td></tr>
          <tr><td>Hilo Poliéster Azul</td><td>Poliéster</td><td>Azul</td><td>18 kg</td><td>5 kg</td><td>$684</td><td>S1</td><td>C-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
          <tr><td>Hilo Lana Verde</td><td>Lana</td><td>Verde</td><td>9 kg</td><td>3 kg</td><td>$810</td><td>S2</td><td>A-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- COMPRAS -->
  <div id="rep-compras" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$10,515</div><div class="stat-lbl">Total compras</div></div>
      <div class="stat-card"><div class="stat-num">8</div><div class="stat-lbl">Órdenes procesadas</div></div>
      <div class="stat-card"><div class="stat-num">$1,314</div><div class="stat-lbl">Compra promedio</div></div>
      <div class="stat-card"><div class="stat-num">3</div><div class="stat-lbl">Proveedores activos</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Compras por semana — Marzo 2025</div>
        <div class="bar-chart" style="height:100px">
          <div class="bar" style="height:45%"></div>
          <div class="bar hi" style="height:80%"></div>
          <div class="bar" style="height:60%"></div>
          <div class="bar" style="height:35%"></div>
        </div>
        <div class="flex justify-between mt-8 text-sm text-muted"><span>Sem 1</span><span>Sem 2</span><span>Sem 3</span><span>Sem 4</span></div>
      </div>
      <div class="card">
        <div class="card-title">Gasto por proveedor</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Textiles del Norte</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:78%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">$4,425</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Hilos Premium MX</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:63%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$3,800</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Dist. Central</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:38%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$2,290</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Detalle de órdenes <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Fecha</th><th>Proveedor</th><th>Productos</th><th>Tienda</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>OC-0042</td><td>14/03</td><td>Textiles Norte</td><td>Nylon Negro x20, Acrílico x15</td><td>S1</td><td><strong>$2,825</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
          <tr><td>OC-0041</td><td>10/03</td><td>Hilos Premium</td><td>Lana Verde x10, Algodón x8</td><td>S1</td><td><strong>$1,600</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
          <tr><td>OC-0040</td><td>08/03</td><td>Dist. Central</td><td>Poliéster Azul x30</td><td>S2</td><td><strong>$1,140</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- EGRESOS -->
  <div id="rep-egresos" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:var(--danger)">$12,840</div><div class="stat-lbl">Total egresos</div></div>
      <div class="stat-card"><div class="stat-num">$10,515</div><div class="stat-lbl">Compras inventario</div></div>
      <div class="stat-card"><div class="stat-num">$2,325</div><div class="stat-lbl">Gastos operativos</div></div>
      <div class="stat-card" style="border-color:var(--accent2);background:var(--accentbg)"><div class="stat-num" style="color:var(--accent)">$5,580</div><div class="stat-lbl">Utilidad bruta</div></div>
    </div>
    <div class="card" style="margin-bottom:16px">
      <div class="card-title">Desglose de egresos</div>
      <div class="grid-4">
        <div style="padding:12px;background:var(--dangbg);border-radius:10px;text-align:center"><div class="font-bold" style="color:var(--danger)">$10,515</div><div class="text-sm text-muted">Compra de inventario</div></div>
        <div style="padding:12px;background:var(--warnbg);border-radius:10px;text-align:center"><div class="font-bold" style="color:var(--warn)">$1,200</div><div class="text-sm text-muted">Renta del local</div></div>
        <div style="padding:12px;background:var(--surf2);border-radius:10px;text-align:center"><div class="font-bold">$680</div><div class="text-sm text-muted">Servicios (luz/agua)</div></div>
        <div style="padding:12px;background:var(--surf2);border-radius:10px;text-align:center"><div class="font-bold">$445</div><div class="text-sm text-muted">Otros gastos</div></div>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Detalle de gastos operativos <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Fecha</th><th>Concepto</th><th>Categoría</th><th>Tienda</th><th>Monto</th><th>Registró</th></tr></thead>
        <tbody>
          <tr><td>14/03</td><td>Renta mensual del local</td><td><span class="badge badge-warn">Renta</span></td><td>S1</td><td><strong>$800.00</strong></td><td>Hernán M.</td></tr>
          <tr><td>12/03</td><td>Factura de luz</td><td><span class="badge badge-info">Servicios</span></td><td>S1</td><td><strong>$420.00</strong></td><td>Ana R.</td></tr>
          <tr><td>10/03</td><td>Renta mensual Suc. 2</td><td><span class="badge badge-warn">Renta</span></td><td>S2</td><td><strong>$400.00</strong></td><td>Hernán M.</td></tr>
          <tr><td>08/03</td><td>Servicio de internet</td><td><span class="badge badge-info">Servicios</span></td><td>S1</td><td><strong>$260.00</strong></td><td>Ana R.</td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- CLIENTES -->
  <div id="rep-clientes" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num">84</div><div class="stat-lbl">Clientes totales</div><div class="stat-delta up">↑ 6 este mes</div></div>
      <div class="stat-card"><div class="stat-num">$4,820</div><div class="stat-lbl">Ticket promedio</div><div class="stat-delta up">↑ 5%</div></div>
      <div class="stat-card"><div class="stat-num">28</div><div class="stat-lbl">Activos este mes</div></div>
      <div class="stat-card"><div class="stat-num">41</div><div class="stat-lbl">Compras — cliente top</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Nuevos clientes — Marzo 2025</div>
        <div class="bar-chart" style="height:90px">
          <div class="bar" style="height:30%"></div><div class="bar" style="height:50%"></div><div class="bar hi" style="height:80%"></div>
          <div class="bar" style="height:60%"></div><div class="bar" style="height:40%"></div><div class="bar" style="height:70%"></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Clientes por tipo</div>
        <div style="display:flex;flex-direction:column;gap:8px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Comprador individual</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:57%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">48</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Taller / Negocio</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:26%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">22</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Mayorista</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:17%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">14</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Ranking de clientes <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>#</th><th>Cliente</th><th>Tipo</th><th>Compras</th><th>Total acum.</th><th>Última compra</th><th>Ticket prom.</th></tr></thead>
        <tbody>
          <tr><td>🥇</td><td><strong>Taller El Hilo Feliz</strong></td><td><span class="badge badge-ok">Mayorista</span></td><td>41</td><td><strong>$32,100</strong></td><td>10/03/2025</td><td>$783</td></tr>
          <tr><td>🥈</td><td><strong>Textilería Puebla</strong></td><td><span class="badge badge-ok">Mayorista</span></td><td>33</td><td><strong>$24,800</strong></td><td>06/03/2025</td><td>$752</td></tr>
          <tr><td>🥉</td><td><strong>Artesanías del Valle</strong></td><td><span class="badge badge-info">Taller</span></td><td>28</td><td><strong>$18,340</strong></td><td>13/03/2025</td><td>$655</td></tr>
          <tr><td>4</td><td><strong>Confecciones López</strong></td><td><span class="badge badge-info">Taller</span></td><td>19</td><td><strong>$9,100</strong></td><td>28/02/2025</td><td>$479</td></tr>
          <tr><td>5</td><td><strong>María González</strong></td><td><span class="badge badge-gray">Individual</span></td><td>12</td><td><strong>$4,820</strong></td><td>14/03/2025</td><td>$402</td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- COMPARATIVO -->
  <div id="rep-comparativo" class="rep-panel" style="display:none">
    <div class="grid-2" style="margin-bottom:20px">
      <div class="card" style="border-color:var(--accent2);background:var(--accentbg)">
        <div class="card-title">Sucursal 1 — Santa María Texmelucan</div>
        <div class="grid-3">
          <div><div class="stat-num" style="font-size:22px;color:var(--accent)">$11,240</div><div class="stat-lbl">Ventas</div></div>
          <div><div class="stat-num" style="font-size:22px">$6,890</div><div class="stat-lbl">Compras</div></div>
          <div><div class="stat-num" style="font-size:22px">52</div><div class="stat-lbl">Clientes activos</div></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Sucursal 2 — Por definir</div>
        <div class="grid-3">
          <div><div class="stat-num" style="font-size:22px">$7,180</div><div class="stat-lbl">Ventas</div></div>
          <div><div class="stat-num" style="font-size:22px">$3,625</div><div class="stat-lbl">Compras</div></div>
          <div><div class="stat-num" style="font-size:22px">32</div><div class="stat-lbl">Clientes activos</div></div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Rendimiento por producto — S1 vs S2 <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Producto</th><th>Tipo</th><th>Vend. S1</th><th>Vend. S2</th><th>Stock S1</th><th>Stock S2</th><th>Ingreso S1</th><th>Ingreso S2</th><th>Total</th></tr></thead>
        <tbody>
          <tr><td>Hilo Acrílico Blanco</td><td>Acrílico</td><td>8 kg</td><td>4 kg</td><td>10 kg</td><td>6 kg</td><td>$520</td><td>$260</td><td><strong>$780</strong></td></tr>
          <tr><td>Hilo Nylon Negro</td><td>Nylon</td><td>3 kg</td><td>6 kg</td><td>—</td><td>2 kg</td><td>$330</td><td>$660</td><td><strong>$990</strong></td></tr>
          <tr><td>Hilo Poliéster Azul</td><td>Poliéster</td><td>12 kg</td><td>2 kg</td><td>6 kg</td><td>16 kg</td><td>$660</td><td>$110</td><td><strong>$770</strong></td></tr>
          <tr><td>Hilo Lana Verde</td><td>Lana</td><td>2 kg</td><td>5 kg</td><td>7 kg</td><td>4 kg</td><td>$260</td><td>$650</td><td><strong>$910</strong></td></tr>
          <tr><td>Hilo Algodón Rojo</td><td>Algodón</td><td>4 kg</td><td>1 kg</td><td>0 kg</td><td>3 kg</td><td>$312</td><td>$78</td><td><strong>$390</strong></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>
</div>

<!-- ══ USUARIOS ═══════════════════════════════════════════ -->