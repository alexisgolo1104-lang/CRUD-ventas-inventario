<div id="screen-productos" class="screen">

  <!-- ── Encabezado ──────────────────────────────────── -->
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm"><?= count($productos ?? []) ?> productos registrados</div>
    <div class="flex gap-8">
      <button class="btn btn-primary" onclick="nuevoProducto()">+ Nuevo producto</button>
      <button class="btn" onclick="exportarTabla('tbl-productos')">⬇ Exportar</button>
    </div>
  </div>

  <!-- ── Filtros ──────────────────────────────────────── -->
  <div class="filter-bar" id="filtros-productos">
    <input class="form-control" id="prod-buscar" placeholder="🔍 Buscar producto, código..."
           oninput="filtrarProductos()" style="max-width:240px">
    <select class="form-control" id="prod-tipo" onchange="filtrarProductos()" style="max-width:170px">
      <option value="">Todos los tipos</option>
      <option>Acrílico</option><option>Algodón</option><option>Nylon</option>
      <option>Poliéster</option><option>Lana</option><option>Mercerizado</option>
      <option>Elastano</option><option>Bambú</option><option>Seda</option>
    </select>
    <select class="form-control" id="prod-color" onchange="filtrarProductos()" style="max-width:160px">
      <option value="">Todos los colores</option>
      <option>Blanco</option><option>Negro</option><option>Rojo</option>
      <option>Azul</option><option>Verde</option><option>Amarillo</option><option>Rosa</option>
    </select>
    <select class="form-control" id="prod-tienda" onchange="filtrarProductos()" style="max-width:160px">
      <option value="">Todas las tiendas</option>
      <option value="1">Sucursal 1</option><option value="2">Sucursal 2</option>
    </select>
    <select class="form-control" id="prod-stock-fil" onchange="filtrarProductos()" style="max-width:150px">
      <option value="">Todo el stock</option>
      <option value="bajo">⚠ Bajo stock</option>
      <option value="ok">✅ Normal</option>
    </select>
    <button class="btn" onclick="limpiarFiltrosProductos()">✕ Limpiar</button>
  </div>

  <!-- ── Tabla CRUD ────────────────────────────────────── -->
  <div class="table-wrap">
    <table id="tbl-productos">
      <thead>
        <tr>
          <th>Código</th>
          <th>Producto</th>
          <th>Tipo</th>
          <th>Color</th>
          <th>Stock</th>
          <th>Mín.</th>
          <th>P.Compra</th>
          <th>P.Venta</th>
          <th>Proveedor</th>
          <th>Anaquel</th>
          <th>Tienda</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-productos">
        <?php if (!empty($productos)): ?>
          <?php foreach ($productos as $p): ?>
          <tr data-tipo="<?= htmlspecialchars($p['tipo'] ?? '') ?>"
              data-color="<?= htmlspecialchars($p['color'] ?? '') ?>"
              data-tienda="<?= htmlspecialchars($p['sucursal_id'] ?? '') ?>"
              data-stock="<?= ($p['stock'] <= $p['stock_minimo']) ? 'bajo' : 'ok' ?>">
            <td><code><?= htmlspecialchars($p['codigo'] ?? '—') ?></code></td>
            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
            <td><?= htmlspecialchars($p['tipo'] ?? '—') ?></td>
            <td>
              <span class="color-pill">
                <span class="color-swatch" style="background:<?= htmlspecialchars($p['color_hex'] ?? '#ccc') ?>"></span>
                <?= htmlspecialchars($p['color'] ?? '—') ?>
              </span>
            </td>
            <td>
              <?php
                $badgeClass = ($p['stock'] <= $p['stock_minimo'])
                  ? 'badge-danger'
                  : (($p['stock'] <= $p['stock_minimo'] * 1.5) ? 'badge-warn' : 'badge-ok');
                $icono = ($p['stock'] <= $p['stock_minimo']) ? ' ⚠' : '';
              ?>
              <span class="badge <?= $badgeClass ?>"><?= $p['stock'] ?> kg<?= $icono ?></span>
            </td>
            <td><?= $p['stock_minimo'] ?> kg</td>
            <td>$<?= number_format($p['precio_compra'], 2) ?></td>
            <td>$<?= number_format($p['precio_venta'], 2) ?></td>
            <td><?= htmlspecialchars($p['proveedor'] ?? '—') ?></td>
            <td><?= htmlspecialchars($p['anaquel'] ?? '—') ?></td>
            <td>S<?= $p['sucursal_id'] ?? '?' ?></td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon"
                      onclick="editarProducto(<?= $p['id_producto'] ?>)" title="Editar">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger"
                      onclick="confirmarEliminar(<?= $p['id_producto'] ?>, 'producto')" title="Eliminar">🗑</button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Datos demo cuando no hay BD conectada -->
         <tr data-tipo="Acrílico" data-color="Blanco" data-tienda="1" data-stock="ok">
            <td><code>ACR-001</code></td>
            <td><strong>Hilo Acrílico Blanco 500g</strong></td>
            <td>Acrílico</td>
            <td><span class="color-pill"><span class="color-swatch" style="background:#eee;border:1px solid #ccc"></span>Blanco</span></td>
            <td><span class="badge badge-ok">12 kg</span></td>
            <td>5 kg</td><td>$45.00</td><td>$65.00</td>
            <td>Textiles del Norte</td><td>A-1</td><td>S1</td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon" onclick="openModal('modal-producto')">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(1,'producto')">🗑</button>
            </td>
          </tr>
         <tr data-tipo="Nylon" data-color="Negro" data-tienda="2" data-stock="bajo">
            <td><code>NYL-002</code></td>
            <td><strong>Hilo Nylon Negro 1kg</strong></td>
            <td>Nylon</td>
            <td><span class="color-pill"><span class="color-swatch" style="background:#111"></span>Negro</span></td>
            <td><span class="badge badge-danger">2 kg ⚠</span></td>
            <td>5 kg</td><td>$80.00</td><td>$110.00</td>
            <td>Hilos Premium MX</td><td>B-2</td><td>S2</td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon" onclick="openModal('modal-producto')">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(2,'producto')">🗑</button>
            </td>
          </tr>
          <tr data-tipo="Algodón" data-color="Rojo" data-tienda="1" data-stock="bajo">
            <td><code>ALG-003</code></td>
            <td><strong>Hilo Algodón Rojo 250g</strong></td>
            <td>Algodón</td>
            <td><span class="color-pill"><span class="color-swatch" style="background:#e53935"></span>Rojo</span></td>
            <td><span class="badge badge-warn">4 kg</span></td>
            <td>5 kg</td><td>$55.00</td><td>$78.00</td>
            <td>Distribuidora Central</td><td>A-2</td><td>S1</td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon" onclick="openModal('modal-producto')">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(3,'producto')">🗑</button>
            </td>
          </tr>
          <tr data-tipo="Poliéster" data-color="Azul" data-tienda="1" data-stock="ok">
            <td><code>POL-004</code></td>
            <td><strong>Hilo Poliéster Azul 500g</strong></td>
            <td>Poliéster</td>
            <td><span class="color-pill"><span class="color-swatch" style="background:#1976d2"></span>Azul</span></td>
            <td><span class="badge badge-ok">18 kg</span></td>
            <td>5 kg</td><td>$38.00</td><td>$55.00</td>
            <td>Textiles del Norte</td><td>C-1</td><td>S1</td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon" onclick="openModal('modal-producto')">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(4,'producto')">🗑</button>
            </td>
          </tr>
          <tr data-tipo="Lana" data-color="Verde" data-tienda="2" data-stock="ok">
            <td><code>LAN-005</code></td>
            <td><strong>Hilo Lana Verde 100g</strong></td>
            <td>Lana</td>
            <td><span class="color-pill"><span class="color-swatch" style="background:#388e3c"></span>Verde</span></td>
            <td><span class="badge badge-ok">9 kg</span></td>
            <td>3 kg</td><td>$90.00</td><td>$130.00</td>
            <td>Hilos Premium MX</td><td>A-1</td><td>S2</td>
            <td class="tbl-actions">
              <button class="btn btn-sm btn-icon" onclick="openModal('modal-producto')">✏️</button>
              <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(5,'producto')">🗑</button>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- ── Paginación simple ─────────────────────────────── -->
  <div class="flex justify-between items-center" style="margin-top:12px">
    <div class="text-muted text-sm" id="prod-contador">Mostrando todos los productos</div>
    <div class="flex gap-8">
      <button class="btn btn-sm" id="prod-prev" onclick="paginaProducto(-1)" disabled>‹ Anterior</button>
      <span class="text-muted text-sm" id="prod-pagina" style="line-height:30px">Pág. 1</span>
      <button class="btn btn-sm" id="prod-next" onclick="paginaProducto(1)">Siguiente ›</button>
    </div>
  </div>

</div>

<!-- ══ MODAL: Nuevo / Editar Producto ══════════════════════ -->
<div class="modal-overlay" id="modal-producto" onclick="closeOut(event,'modal-producto')">
  <div class="modal" style="max-width:620px">
    <div class="modal-header">
      <div class="modal-title" id="modal-producto-titulo">Nuevo producto</div>
      <button class="modal-close" onclick="closeModal('modal-producto')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="prod-id" value="">
      <input type="hidden" id="prod-id-tipo" value="">
      <input type="hidden" id="prod-id-color" value="">

      <?php
        $tipoMap = [];
        foreach ($prodModel->getTipos() as $t) {
          $tipoMap[$t['id_tipo']] = $t['nombre'];
        }
        $colorMap = [];
        $colorHex = [];
        foreach ($prodModel->getColores() as $c) {
          $colorMap[$c['id_color']] = $c['nombre'];
          $colorHex[$c['id_color']] = $c['codigo_hex'];
        }
      ?>

      <div class="form-row form-2">
        <div class="form-group">
          <label class="form-label">Nombre del producto (catálogo) *</label>
          <select class="form-control" id="prod-catalogo" onchange="autocompletarTipoProd()">
            <option value="">Seleccionar del catálogo...</option>
            <?php foreach ($prodModel->getCatalogos() as $cat): ?>
              <option value="<?= (int)$cat['id_catalogo'] ?>"
                      data-tipo="<?= htmlspecialchars($tipoMap[$cat['id_tipo']] ?? '') ?>"
                      data-tipo-id="<?= (int)$cat['id_tipo'] ?>"
                      data-color="<?= htmlspecialchars($colorMap[$cat['id_color']] ?? '') ?>"
                      data-color-id="<?= (int)$cat['id_color'] ?>"
                      data-hex="<?= htmlspecialchars($colorHex[$cat['id_color']] ?? '#ccc') ?>">
                <?= htmlspecialchars($cat['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="form-hint">Selecciona del catálogo para evitar errores de escritura</div>
        </div>
        <div class="form-group">
          <label class="form-label">Presentación</label>
          <input class="form-control" id="prod-presentacion" placeholder="Ej: 500g, 1kg, 250g">
        </div>
      </div>

      <div class="form-row form-2">
        <div class="form-group">
          <label class="form-label">Tipo de hilo</label>
          <input class="form-control" id="prod-tipo-inp" placeholder="Se asigna del catálogo" readonly
                 style="background:var(--surf2);color:var(--muted)">
        </div>
        <div class="form-group">
          <label class="form-label">Color</label>
          <div class="flex gap-8 items-center">
            <span class="color-pill">
              <span class="color-swatch" id="prod-color-swatch" style="display:inline-block;width:28px;height:28px;border-radius:6px;border:1px solid var(--border);background:#ccc;flex-shrink:0"></span>
              <input class="form-control" id="prod-color-inp" placeholder="Se asigna del catálogo" readonly
                     style="background:var(--surf2);color:var(--muted)">
            </span>
          </div>
        </div>
      </div>

      <div class="form-row form-2">
        <div class="form-group">
          <label class="form-label">Proveedor</label>
          <select class="form-control" id="prod-id-proveedor">
            <option value="">Seleccionar...</option>
            <?php foreach ($prodModel->getProveedores() as $prov): ?>
              <option value="<?= (int)$prov['id_proveedor'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tienda destino *</label>
          <select class="form-control" id="prod-sucursal">
            <option value="1">Sucursal 1 — Santa María Texmelucan</option>
            <option value="2">Sucursal 2 — Por definir</option>
          </select>
        </div>
      </div>

      <div class="form-row form-3">
        <div class="form-group">
          <label class="form-label">Stock inicial *</label>
          <input class="form-control" id="prod-stock" type="number" placeholder="0" min="0">
        </div>
        <div class="form-group">
          <label class="form-label">Stock mínimo *</label>
          <input class="form-control" id="prod-stock-min" type="number" placeholder="5" min="0">
        </div>
        <div class="form-group">
          <label class="form-label">Unidad</label>
          <select class="form-control" id="prod-id-unidad">
            <option value="">Seleccionar...</option>
            <?php foreach ($prodModel->getUnidades() as $u): ?>
              <option value="<?= (int)$u['id_unidad'] ?>"><?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['codigo']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row form-2">
        <div class="form-group">
          <label class="form-label">Precio de compra *</label>
          <input class="form-control" id="prod-p-compra" type="number" placeholder="$0.00" min="0" step="0.01">
        </div>
        <div class="form-group">
          <label class="form-label">Precio de venta *</label>
          <input class="form-control" id="prod-p-venta" type="number" placeholder="$0.00" min="0" step="0.01">
          <div class="form-hint" id="prod-margen-hint"></div>
        </div>
      </div>

      <div class="form-row form-2">
        <div class="form-group">
          <label class="form-label">Anaquel</label>
          <select class="form-control" id="prod-id-anaquel">
            <option value="">Seleccionar...</option>
            <?php foreach ($prodModel->getAnaqueles() as $a): ?>
              <option value="<?= (int)$a['id_anaquel'] ?>"><?= htmlspecialchars($a['codigo']) ?><?= $a['descripcion'] ? ' — '.htmlspecialchars($a['descripcion']) : '' ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Posición en anaquel</label>
          <input class="form-control" id="prod-posicion" placeholder="Ej: Fila 1, Columna 2">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn" onclick="closeModal('modal-producto')">Cancelar</button>
      <button type="button" class="btn btn-primary" onclick="guardarProducto()">✓ Guardar producto</button>
    </div>
  </div>
</div>

<!-- ══ MODAL: Confirmar eliminar ═══════════════════════════ -->
<div class="modal-overlay" id="modal-eliminar-prod" onclick="closeOut(event,'modal-eliminar-prod')">
  <div class="modal" style="max-width:420px">
    <div class="modal-header">
      <div class="modal-title">⚠️ Eliminar producto</div>
      <button class="modal-close" onclick="closeModal('modal-eliminar-prod')">✕</button>
    </div>
    <div class="modal-body">
      <div class="notice notice-danger">
        Esta acción es irreversible. Se eliminará el producto y todo su historial de stock.
        <br><br><strong id="prod-eliminar-nombre"></strong>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-eliminar-prod')">Cancelar</button>
      <button class="btn btn-danger" onclick="ejecutarEliminarProducto()">🗑 Sí, eliminar</button>
    </div>
  </div>
</div>
