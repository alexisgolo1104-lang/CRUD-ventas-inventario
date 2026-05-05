<div id="screen-catalogos" class="screen">
  <div class="notice notice-info mb-16">ℹ️ Los catálogos son los datos predeterminados del sistema. Usarlos en formularios evita errores de escritura.</div>
  <!-- Tabs de catálogo -->
  <div class="flex gap-6 mb-16" style="border-bottom:2px solid var(--border);padding-bottom:0">
    <button class="cat-tab active" data-cat="tipos" onclick="setCatTab(this,'tipos')">📋 Tipos de hilo</button>
    <button class="cat-tab" data-cat="colores" onclick="setCatTab(this,'colores')">🎨 Colores</button>
    <button class="cat-tab" data-cat="proveedores" onclick="setCatTab(this,'proveedores')">🏭 Proveedores</button>
    <button class="cat-tab" data-cat="anaqueles" onclick="setCatTab(this,'anaqueles')">🗄️ Anaqueles</button>
    <button class="cat-tab" data-cat="unidades" onclick="setCatTab(this,'unidades')">📏 Unidades</button>
  </div>

  <!-- TIPOS DE HILO -->
  <div id="cat-tipos" class="cat-panel">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Tipos de hilo</span> <span class="badge badge-gray">9 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-tipo')">+ Nuevo tipo de hilo</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar tipo de hilo..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>#</th><th>Nombre</th><th>Descripción</th><th>Productos usando</th><th>Creado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td>1</td><td><strong>Acrílico</strong></td><td class="text-muted">Hilo acrílico sintético para calcetines</td><td><span class="badge badge-ok">34 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray text-sm">En uso</span></td></tr>
        <tr><td>2</td><td><strong>Algodón</strong></td><td class="text-muted">Fibra natural, suave y transpirable</td><td><span class="badge badge-ok">18 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>3</td><td><strong>Nylon</strong></td><td class="text-muted">Alta resistencia y durabilidad</td><td><span class="badge badge-ok">12 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>4</td><td><strong>Poliéster</strong></td><td class="text-muted">Multiusos, fácil mantenimiento</td><td><span class="badge badge-ok">22 productos</span></td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>5</td><td><strong>Lana</strong></td><td class="text-muted">Natural, cálido, para temporada fría</td><td><span class="badge badge-ok">8 productos</span></td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>6</td><td><strong>Mercerizado</strong></td><td class="text-muted">Acabado brillante y sedoso</td><td><span class="badge badge-ok">6 productos</span></td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>7</td><td><strong>Elastano</strong></td><td class="text-muted">Con elasticidad, mezcla deportiva</td><td><span class="badge badge-ok">5 productos</span></td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>8</td><td><strong>Bambú</strong></td><td class="text-muted">Fibra ecológica, antibacterial</td><td><span class="badge badge-ok">4 productos</span></td><td>20/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>9</td><td><strong>Seda</strong></td><td class="text-muted">Premium, suave y brillante</td><td><span class="badge badge-ok">3 productos</span></td><td>01/06/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- COLORES -->
  <div id="cat-colores" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Colores</span> <span class="badge badge-gray">12 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-color')">+ Nuevo color</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar color..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>#</th><th>Muestra</th><th>Nombre</th><th>Código Hex</th><th>Productos usando</th><th>Creado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td>1</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFFFFF;border:1px solid #ccc;border-radius:5px"></span></td><td><strong>Blanco</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFFFFF</code></td><td>28 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>2</td><td><span style="display:inline-block;width:32px;height:24px;background:#000000;border-radius:5px"></span></td><td><strong>Negro</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#000000</code></td><td>24 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>3</td><td><span style="display:inline-block;width:32px;height:24px;background:#FF0000;border-radius:5px"></span></td><td><strong>Rojo</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FF0000</code></td><td>16 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>4</td><td><span style="display:inline-block;width:32px;height:24px;background:#0000FF;border-radius:5px"></span></td><td><strong>Azul</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#0000FF</code></td><td>20 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>5</td><td><span style="display:inline-block;width:32px;height:24px;background:#008000;border-radius:5px"></span></td><td><strong>Verde</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#008000</code></td><td>14 productos</td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>6</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFFF00;border:1px solid #ddd;border-radius:5px"></span></td><td><strong>Amarillo</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFFF00</code></td><td>8 productos</td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>7</td><td><span style="display:inline-block;width:32px;height:24px;background:#800080;border-radius:5px"></span></td><td><strong>Morado</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#800080</code></td><td>6 productos</td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>8</td><td><span style="display:inline-block;width:32px;height:24px;background:#A52A2A;border-radius:5px"></span></td><td><strong>Café</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#A52A2A</code></td><td>5 productos</td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>9</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFA500;border-radius:5px"></span></td><td><strong>Naranja</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFA500</code></td><td>4 productos</td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>10</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFC0CB;border-radius:5px"></span></td><td><strong>Rosa</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFC0CB</code></td><td>6 productos</td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>11</td><td><span style="display:inline-block;width:32px;height:24px;background:#722F37;border-radius:5px"></span></td><td><strong>Vino</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#722F37</code></td><td>3 productos</td><td>20/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>12</td><td><span style="display:inline-block;width:32px;height:24px;background:#00FFFF;border-radius:5px;border:1px solid #ddd"></span></td><td><strong>Aqua</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#00FFFF</code></td><td>2 productos</td><td>01/06/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- PROVEEDORES -->
  <div id="cat-proveedores" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Proveedores</span> <span class="badge badge-gray">4 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-proveedor')">+ Nuevo proveedor</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar proveedor..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>Proveedor</th><th>Teléfono</th><th>Correo</th><th>Dirección</th><th>Órdenes</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td><strong>Textiles del Norte S.A.</strong></td><td>222-100-0001</td><td>[correo@empresa.com]</td><td>Blvd. Industrial #45, Puebla</td><td><span class="badge badge-ok">12 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Hilos Premium MX</strong></td><td>222-100-0002</td><td>[correo@empresa.com]</td><td>Av. Textil #89, Tlaxcala</td><td><span class="badge badge-ok">8 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Distribuidora Central</strong></td><td>222-100-0003</td><td>[correo@empresa.com]</td><td>Calle Fábrica #12, Puebla</td><td><span class="badge badge-ok">5 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Fibras Naturales del Sur</strong></td><td>222-100-0004</td><td>—</td><td>—</td><td><span class="badge badge-gray">0 órdenes</span></td><td><span class="badge badge-gray">Inactivo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm btn-danger" onclick="confirmDelete('¿Eliminar proveedor Fibras Naturales del Sur? No tiene órdenes registradas.',()=>showToast('Proveedor eliminado'))">🗑</button></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- ANAQUELES -->
  <div id="cat-anaqueles" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Anaqueles</span> <span class="badge badge-gray">8 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-anaquel')">+ Nuevo anaquel</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px">
      <input class="form-control" placeholder="🔍 Buscar anaquel...">
      <select class="form-control" style="max-width:180px"><option>Todas las tiendas</option><option>Sucursal 1</option><option>Sucursal 2</option></select>
    </div>
    <div class="grid-2">
      <div>
        <div class="table-wrap">
          <table><thead><tr><th>Código</th><th>Descripción</th><th>Tienda</th><th>Productos</th><th>Acciones</th></tr></thead>
          <tbody>
            <tr><td><strong>A-1</strong></td><td>Anaquel A, sección 1 — entrada principal</td><td>S1</td><td>5 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>A-2</strong></td><td>Anaquel A, sección 2 — continuación</td><td>S1</td><td>4 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>A-3</strong></td><td>Anaquel A, sección 3 — fondo</td><td>S1</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-1</strong></td><td>Anaquel B, sección 1 — lado derecho</td><td>S1</td><td>2 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-2</strong></td><td>Anaquel B, sección 2 — central</td><td>S1</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-3</strong></td><td>Anaquel B, sección 3 — esquina</td><td>S1</td><td>1 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>C-1</strong></td><td>Anaquel C, sección 1 — Sucursal 2</td><td>S2</td><td>4 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>C-2</strong></td><td>Anaquel C, sección 2 — Sucursal 2</td><td>S2</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
          </tbody></table>
        </div>
      </div>
      <div>
        <div class="card" style="margin-bottom:14px">
          <div class="card-title" style="font-size:12px">Plano visual — Sucursal 1</div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
            <div style="padding:10px;background:var(--accentbg);border:1px solid var(--accent2);border-radius:8px;text-align:center;font-size:12px;font-weight:700;color:var(--accent)">A-1<br><span style="font-size:10px;font-weight:400">5 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">A-2<br><span style="font-size:10px">4 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">A-3<br><span style="font-size:10px">3 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">B-1<br><span style="font-size:10px">2 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">B-2<br><span style="font-size:10px">3 prod.</span></div>
            <div style="padding:10px;background:var(--warnbg);border:1px solid #f5ce70;border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--warn)">B-3<br><span style="font-size:10px">1 prod.</span></div>
          </div>
        </div>
        <div class="card">
          <div class="card-title" style="font-size:12px">Plano visual — Sucursal 2</div>
          <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:6px">
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">C-1<br><span style="font-size:10px">4 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">C-2<br><span style="font-size:10px">3 prod.</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- UNIDADES -->
  <div id="cat-unidades" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Unidades de medida</span> <span class="badge badge-gray">4 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-unidad')">+ Nueva unidad</button>
    </div>
    <div class="table-wrap">
      <table><thead><tr><th>Abrev.</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td><strong>kg</strong></td><td>Kilogramos</td><td class="text-muted">Peso estándar para hilos a granel</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>g</strong></td><td>Gramos</td><td class="text-muted">Precisión en presentaciones pequeñas</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>pieza</strong></td><td>Piezas</td><td class="text-muted">Conteo por unidad individual</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>rollo</strong></td><td>Rollos</td><td class="text-muted">Presentación en rollo continuo</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ EMPLEADO: VENTAS ═══════════════════════════════════ -->