// ══════════════════════════════════════════════════════════
//  crud-extra.js — Productos CRUD + Ventas + helpers
//  Conectado al backend PHP via fetch (AJAX)
// ══════════════════════════════════════════════════════════

function showToast(msg, type='ok'){
  const t=document.createElement('div');
  t.className='toast-msg';
  t.textContent=msg;
  t.style.cssText=`position:fixed;bottom:24px;right:24px;z-index:9999;background:${type==='warn'?'#D68910':'#2563EB'};color:#fff;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:500;box-shadow:0 4px 16px rgba(0,0,0,.2);animation:slideUp .25s ease;pointer-events:none`;
  document.body.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='opacity .3s';setTimeout(()=>t.remove(),300)},2500);
}

const API_BASE = 'index.php?action=';

// ── Utilidad fetch POST ───────────────────────────────────
async function apiPost(action, formData) {
  const resp = await fetch(API_BASE + action, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    body: formData,
  });
  return await parseApiResponse(resp);
}

async function apiGet(action, params = '') {
  const resp = await fetch(API_BASE + action + (params ? '&' + params : ''), {
    credentials: 'same-origin',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
  });
  return await parseApiResponse(resp);
}

async function parseApiResponse(resp) {
  const contentType = resp.headers.get('content-type') || '';
  if (!resp.ok) {
    const body = await resp.text();
    let parsed;
    try { parsed = JSON.parse(body); } catch (e) { parsed = null; }
    const msg = parsed?.msg || parsed?.error || body || `HTTP ${resp.status}`;
    return {
      ok: false,
      status: resp.status,
      error: msg,
      msg: msg,
    };
  }
  if (contentType.includes('application/json')) {
    try {
      return await resp.json();
    } catch (e) {
      const text = await resp.text();
      return { ok: false, msg: 'Respuesta JSON inválida del servidor.', error: 'Respuesta JSON inválida del servidor.', detalle: text };
    }
  }
  const text = await resp.text();
  try {
    const parsed = JSON.parse(text);
    return parsed;
  } catch (e) {
    return { ok: false, msg: 'Respuesta no JSON del servidor.', error: 'Respuesta no JSON del servidor.', detalle: text };
  }
}

// ── Sub-tabs de Ventas ─────────────────────────────────────
function switchVentaTab(tab) {
  document.getElementById('vtab-panel-nueva').style.display     = tab === 'nueva'     ? '' : 'none';
  document.getElementById('vtab-panel-historial').style.display = tab === 'historial' ? '' : 'none';
  document.getElementById('vtab-nueva').classList.toggle('active',     tab === 'nueva');
  document.getElementById('vtab-historial').classList.toggle('active', tab === 'historial');
}

// ── Autocompletar tipo/color al seleccionar del catálogo ──
function autocompletarTipoProd() {
  const sel   = document.getElementById('prod-catalogo');
  const opt   = sel.options[sel.selectedIndex];
  const tipo  = opt.dataset.tipo  || '';
  const tipoId = opt.dataset.tipoId || '';
  const color = opt.dataset.color || '';
  const colorId = opt.dataset.colorId || '';
  const hex   = opt.dataset.hex   || '#cccccc';
  const tipoInp  = document.getElementById('prod-tipo-inp');
  const colorInp = document.getElementById('prod-color-inp');
  const swatch   = document.getElementById('prod-color-swatch');
  const tipoIdField  = document.getElementById('prod-id-tipo');
  const colorIdField = document.getElementById('prod-id-color');
  if (tipoInp)  tipoInp.value          = tipo;
  if (colorInp) colorInp.value         = color;
  if (swatch)   swatch.style.background = hex;
  if (tipoIdField) tipoIdField.value   = tipoId;
  if (colorIdField) colorIdField.value = colorId;
}

// ── Calcular margen al escribir precios ───────────────────
function calcularMargen() {
  const pc   = parseFloat(document.getElementById('prod-p-compra')?.value) || 0;
  const pv   = parseFloat(document.getElementById('prod-p-venta')?.value)  || 0;
  const hint = document.getElementById('prod-margen-hint');
  if (!hint) return;
  if (pc > 0 && pv > 0) {
    const margen = ((pv - pc) / pc * 100).toFixed(1);
    const color  = margen < 0 ? 'var(--danger)' : 'var(--ok,#16a34a)';
    hint.innerHTML = `<span style="color:${color}">Margen: ${margen}%</span>`;
  } else {
    hint.textContent = '';
  }
}
document.addEventListener('input', e => {
  if (e.target.id === 'prod-p-compra' || e.target.id === 'prod-p-venta') calcularMargen();
});

// ── Abrir modal producto (nuevo) ──────────────────────────
function nuevoProducto() {
  document.getElementById('modal-producto-titulo').textContent = 'Nuevo producto';
  document.getElementById('prod-id').value = '';
  ['prod-catalogo','prod-id-tipo','prod-id-color','prod-id-unidad',
   'prod-id-anaquel','prod-id-proveedor','prod-presentacion',
   'prod-posicion','prod-stock','prod-stock-min','prod-p-compra','prod-p-venta',
   'prod-tipo-inp','prod-color-inp','prod-sucursal']
    .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
  const swatch = document.getElementById('prod-color-swatch');
  if (swatch) swatch.style.background = '#ccc';
  const hint = document.getElementById('prod-margen-hint');
  if (hint) hint.textContent = '';
  openModal('modal-producto');
}

// ── Abrir modal producto (editar) ─────────────────────────
async function editarProducto(id) {
  document.getElementById('modal-producto-titulo').textContent = 'Editar producto';
  const prodIdEl = document.getElementById('prod-id');
  if (!prodIdEl) {
    showToast('❌ Error interno: modal no disponible.');
    return;
  }
  prodIdEl.value = id;
  try {
    const datos = await apiGet('producto_get', 'id_producto=' + id);
    if (!datos || datos.ok === false) {
      showToast('❌ ' + (datos?.msg || datos?.error || 'Error al cargar producto'));
      return;
    }
    if (datos.id_producto) {
      setVal('prod-catalogo',      datos.id_catalogo);
      setVal('prod-id-tipo',       datos.id_tipo);
      setVal('prod-id-color',      datos.id_color);
      setVal('prod-id-unidad',     datos.id_unidad);
      setVal('prod-id-anaquel',    datos.id_anaquel);
      setVal('prod-id-proveedor',  datos.id_proveedor);
      setVal('prod-presentacion',  datos.presentacion);
      setVal('prod-posicion',      datos.posicion_anaquel);
      setVal('prod-stock',         datos.stock_actual || 0);
      setVal('prod-stock-min',     datos.stock_minimo || 0);
      setVal('prod-p-compra',      datos.precio_compra || 0);
      setVal('prod-p-venta',       datos.precio_venta || 0);
      setVal('prod-sucursal',      datos.id_tienda);
      // Set text inputs
      setVal('prod-tipo-inp',      datos.tipo_nombre || '');
      setVal('prod-color-inp',     datos.color_nombre || '');
      const swatch = document.getElementById('prod-color-swatch');
      if (swatch) swatch.style.background = datos.color_hex || '#ccc';
      calcularMargen();
    } else {
      showToast('❌ Producto no encontrado');
      return;
    }
  } catch(e) {
    showToast('❌ Error de conexión');
    return;
  }
  openModal('modal-producto');
}

function setVal(id, val) {
  const el = document.getElementById(id);
  if (el && val !== null && val !== undefined) el.value = val;
}

// ── Guardar producto (crear o actualizar) ─────────────────
async function guardarProducto() {
  const id = document.getElementById('prod-id')?.value || '';
  const id_catalogo = document.getElementById('prod-catalogo')?.value;
  const isEdit = Boolean(id && id !== '0');
  console.log('guardarProducto iniciar', { id, isEdit, id_catalogo });

  if (!id_catalogo) {
    showToast('❌ Selecciona un producto del catálogo');
    return;
  }

  // Validar que precio de venta sea mayor o igual al precio de compra
  const precioCompra = parseFloat(document.getElementById('prod-p-compra')?.value) || 0;
  const precioVenta = parseFloat(document.getElementById('prod-p-venta')?.value) || 0;
  
  if (precioVenta < precioCompra) {
    showToast('❌ El precio de venta debe ser mayor o igual al precio de compra', 'warn');
    return;
  }

  const fd = new FormData();
  fd.append('id_producto',      id);
  fd.append('id_catalogo',      id_catalogo || '');
  fd.append('id_tipo',          document.getElementById('prod-id-tipo')?.value       || '');
  fd.append('id_color',         document.getElementById('prod-id-color')?.value      || '');
  fd.append('id_unidad',        document.getElementById('prod-id-unidad')?.value     || '');
  fd.append('id_anaquel',       document.getElementById('prod-id-anaquel')?.value    || '');
  fd.append('id_proveedor',     document.getElementById('prod-id-proveedor')?.value  || '');
  fd.append('presentacion',     document.getElementById('prod-presentacion')?.value  || '');
  fd.append('posicion_anaquel', document.getElementById('prod-posicion')?.value      || '');
  fd.append('id_tienda',        document.getElementById('prod-sucursal')?.value     || '');
  fd.append('stock_actual',     document.getElementById('prod-stock')?.value         || '0');
  fd.append('stock_minimo',     document.getElementById('prod-stock-min')?.value     || '0');
  fd.append('precio_compra',    precioCompra);
  fd.append('precio_venta',     precioVenta);

  const action = isEdit ? 'producto_actualizar' : 'producto_crear';
  try {
    const res = await apiPost(action, fd);
    console.log('Respuesta del servidor:', res);
    if (res && res.ok === true) {
      closeModal('modal-producto');
      showToast(`✅ ${res.msg || 'Producto guardado correctamente'}`);
      setTimeout(() => {
        cargarInventario(true);
        if (typeof cargarDashboard === 'function') cargarDashboard();
      }, 1200);
      return;
    }

    const errorMessage = res?.msg || res?.error || 'Error al guardar producto';
    console.warn('Guardar producto falló:', errorMessage, res);
    showToast(`❌ ${errorMessage}`);
  } catch (e) {
    console.error('Error en guardarProducto:', e);
    showToast('❌ Error de conexión');
  }
}

// ── Eliminar/desactivar producto ──────────────────────────
let _eliminarId   = null;
let _eliminarTipo = null;

function confirmarEliminar(id, tipo) {
  _eliminarId   = id;
  _eliminarTipo = tipo;
  const modalId = tipo === 'producto' ? 'modal-eliminar-prod' : 'modal-cancelar-venta';
  openModal(modalId);
}

async function ejecutarEliminarProducto() {
  const fd = new FormData();
  fd.append('id_producto', _eliminarId);
  try {
    const res = await apiPost('producto_desactivar', fd);
    closeModal('modal-eliminar-prod');
    showToast(res.ok ? '🗑 Producto desactivado' : '❌ Error al desactivar');
    if (res.ok) {
      const row = document.querySelector(`#tbody-productos tr[data-id="${_eliminarId}"]`);
      if (row) row.remove();
      setTimeout(() => {
        cargarInventario(true);
        if (typeof cargarDashboard === 'function') cargarDashboard();
      }, 800);
    }
  } catch(e) {
    showToast('❌ Error de conexión');
  }
}

// ── Filtrar tabla de productos ────────────────────────────
function filtrarProductos() {
  const q      = (document.getElementById('prod-buscar')?.value  || '').toLowerCase();
  const tipo   = document.getElementById('prod-tipo')?.value     || '';
  const color  = document.getElementById('prod-color')?.value    || '';
  const tienda = document.getElementById('prod-tienda')?.value   || '';
  const stock  = document.getElementById('prod-stock-fil')?.value || '';
  const rows   = document.querySelectorAll('#tbody-productos tr[data-tipo]');
  let visible  = 0;
  rows.forEach(tr => {
    const txt   = tr.textContent.toLowerCase();
    const match =
      (!q      || txt.includes(q))                &&
      (!tipo   || tr.dataset.tipo  === tipo)       &&
      (!color  || tr.dataset.color === color)      &&
      (!tienda || tr.dataset.tienda === tienda)    &&
      (!stock  || tr.dataset.stock  === stock);
    tr.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  const c = document.getElementById('prod-contador');
  if (c) c.textContent = `Mostrando ${visible} producto${visible !== 1 ? 's' : ''}`;
}

function limpiarFiltrosProductos() {
  ['prod-buscar','prod-tipo','prod-color','prod-tienda','prod-stock-fil'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  filtrarProductos();
}

// ── Filtrar tabla de ventas ───────────────────────────────
function filtrarVentas() {
  const q      = (document.getElementById('venta-buscar')?.value    || '').toLowerCase();
  const tienda = document.getElementById('venta-fil-tienda')?.value  || '';
  const estado = document.getElementById('venta-fil-estado')?.value  || '';
  const rows   = document.querySelectorAll('#tbody-ventas tr[data-tienda]');
  rows.forEach(tr => {
    const txt   = tr.textContent.toLowerCase();
    const match =
      (!q      || txt.includes(q))              &&
      (!tienda || tr.dataset.tienda === tienda) &&
      (!estado || tr.dataset.estado === estado);
    tr.style.display = match ? '' : 'none';
  });
}

function limpiarFiltrosVentas() {
  ['venta-buscar','venta-fil-tienda','venta-fil-estado','venta-fil-desde','venta-fil-hasta'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  filtrarVentas();
}

// ── Ver detalle de venta (fetch real) ────────────────────

async function verDetalleVenta(id) {
  const titulo = document.getElementById('modal-detalle-titulo');
  const body   = document.getElementById('modal-detalle-body');
  if (titulo) titulo.textContent = `Detalle de venta #${String(id).padStart(4,'0')}`;
  if (body) body.innerHTML = '<p style="padding:20px;text-align:center">Cargando…</p>';
  openModal('modal-detalle-venta');
  try {
    const data = await apiGet('venta_detalle', 'id_venta=' + id);
    if (!data || data.ok === false || !data.venta) {
      if (body) body.innerHTML = '<p>' + (data?.msg || data?.error || 'Sin datos o error al cargar') + '</p>';
      return;
    }
    const v    = data.venta;
    const rows = (data.items || []).map(it => `
      <tr>
        <td>${it.producto || ''}</td>
        <td>${it.cantidad} ${it.presentacion || ''}</td>
        <td>$${parseFloat(it.precio_unit).toFixed(2)}</td>
        <td>$${parseFloat(it.subtotal).toFixed(2)}</td>
      </tr>`).join('');
    body.innerHTML = `
      <div class="notice notice-info" style="margin-bottom:12px">
        <div><strong>Folio:</strong> ${v.folio}</div>
        <div><strong>Fecha:</strong> ${v.fecha}</div>
        <div><strong>Cliente:</strong> ${v.cliente || 'Venta directa'}</div>
        <div><strong>Estado:</strong> <span class="badge ${v.estado === 'completada' ? 'badge-ok' : 'badge-danger'}">${v.estado === 'completada' ? '✅ OK' : (v.estado === 'cancelada' ? '❌ CANCELADA' : '↩️ DEVOLUCIÓN')}</span></div>
        ${v.motivo_cancelacion ? `<div><strong>Motivo:</strong> ${v.motivo_cancelacion}</div>` : ''}
        <div><strong>Descuento:</strong> ${v.descuento || 0}%</div>
      </div>
      <div class="table-wrap">
        <table><thead><tr><th>Producto</th><th>Cant.</th><th>P.Unit</th><th>Subtotal</th></tr></thead>
        <tbody>${rows}</tbody></table>
      </div>
      <div class="cart-total-box" style="margin-top:12px">
        <div class="cart-total-row">
          <span class="cart-total-label">Subtotal</span>
          <span class="cart-total-val">$${parseFloat(v.subtotal||0).toFixed(2)}</span>
        </div>
        <div class="cart-total-row">
          <span class="cart-total-label font-bold">Total</span>
          <span class="cart-total-val cart-total-main">$${parseFloat(v.total||0).toFixed(2)}</span>
        </div>
      </div>`;
  } catch(e) {
    if (body) body.innerHTML = '<p>Error al cargar detalle.</p>';
  }
}

// ── Cancelar venta ────────────────────────────────────────
let _cancelarVentaId = null;

function cancelarVenta(id) {
  _cancelarVentaId = id;
  openModal('modal-cancelar-venta');
}

function ejecutarCancelarVenta() {
  const motivo = document.getElementById('cancelar-motivo')?.value;
  if (!motivo) { showToast('⚠️ Selecciona un motivo'); return; }

  closeModal('modal-cancelar-venta');
  showToast(`✅ Venta #${String(_cancelarVentaId).padStart(4,'0')} marcada para cancelar`);
}

// ── CRUD Clientes ────────────────────────────────────────
async function guardarCliente() {
  const id = document.getElementById('cli-id')?.value;
  const fd = new FormData();
  fd.append('id_cliente',   id || '');
  fd.append('nombre',       document.getElementById('cli-nombre')?.value      || '');
  fd.append('telefono',     document.getElementById('cli-telefono')?.value    || '');
  fd.append('correo',       document.getElementById('cli-correo')?.value      || '');
  fd.append('direccion',    document.getElementById('cli-direccion')?.value   || '');
  fd.append('rfc',          document.getElementById('cli-rfc')?.value         || '');
  fd.append('tipo_cliente', document.getElementById('cli-tipo')?.value        || 'Comprador individual');
  fd.append('id_tienda',    document.getElementById('cli-id-tienda')?.value   || '1');
  fd.append('notas',        document.getElementById('cli-notas')?.value       || '');

  const action = id ? 'cliente_actualizar' : 'cliente_crear';
  try {
    const res = await apiPost(action, fd);
    closeModal('modal-cliente');
    showToast(res.ok ? `✅ ${res.msg}` : `❌ ${res.msg}`);
    if (res.ok) {
      if (typeof cargarDashboard === 'function') cargarDashboard();
      if (typeof cargarClientes === 'function') cargarClientes();
      setTimeout(() => location.reload(), 1200);
    }
  } catch(e) {
    showToast('❌ Error de conexión');
  }
}

// ── Exportar tabla a CSV ──────────────────────────────────
function exportarTabla(tableId) {
  const table = document.getElementById(tableId);
  if (!table) return;
  const rows = table.querySelectorAll('tr');
  const csv  = Array.from(rows).map(tr =>
    Array.from(tr.querySelectorAll('th,td'))
        .map(td => `"${td.textContent.replace(/"/g,'""').trim()}"`)
        .join(',')
  ).join('\n');
  const blob = new Blob(['\ufeff' + csv], {type:'text/csv;charset=utf-8'});
  const url  = URL.createObjectURL(blob);
  const a    = document.createElement('a'); a.href=url; a.download=tableId+'.csv'; a.click();
  URL.revokeObjectURL(url);
  showToast('✅ Exportado como CSV');
}

// ── Paginación simple ────────────────────────────────────
let _prodPagina = 1;

function paginaProducto(dir) {
  _prodPagina = Math.max(1, _prodPagina + dir);
  const el = document.getElementById('prod-pagina');
  if (el) el.textContent = 'Pág. ' + _prodPagina;
  showToast('📄 Página ' + _prodPagina);
}

// ── CRUD Clientes — funciones adicionales ─────────────────

function nuevoCliente() {
  document.getElementById('modal-cliente-titulo').textContent = 'Nuevo cliente';
  document.getElementById('cli-id').value = '';
  ['cli-nombre','cli-telefono','cli-correo','cli-rfc','cli-direccion','cli-notas'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
  const tipo = document.getElementById('cli-tipo');
  if (tipo) tipo.value = 'Comprador individual';
  const tienda = document.getElementById('cli-id-tienda');
  if (tienda) tienda.value = '1';
  openModal('modal-cliente');
}

async function editarCliente(id) {
  document.getElementById('modal-cliente-titulo').textContent = 'Editar cliente';
  document.getElementById('cli-id').value = id;
  try {
    const datos = await apiGet('cliente_get', 'id_cliente=' + id);
    if (datos && datos.id_cliente) {
      setVal('cli-nombre',    datos.nombre);
      setVal('cli-telefono',  datos.telefono);
      setVal('cli-correo',    datos.correo);
      setVal('cli-tipo',      datos.tipo_cliente);
      setVal('cli-id-tienda', datos.id_tienda);
      setVal('cli-rfc',       datos.rfc);
      setVal('cli-direccion', datos.direccion);
      setVal('cli-notas',     datos.notas);
    }
  } catch(e) { /* abre modal aunque falle la carga */ }
  openModal('modal-cliente');
}

let _desactivarClienteId = null;
let _desactivarClienteNombre = '';

function confirmarDesactivarCliente(id, nombre) {
  _desactivarClienteId = id;
  _desactivarClienteNombre = nombre;
  // Reutilizar modal de confirmación genérico si existe, o showToast
  if (typeof openConfirm === 'function') {
    openConfirm(`¿Desactivar al cliente "${nombre}"?`, ejecutarDesactivarCliente);
  } else {
    if (confirm(`¿Desactivar al cliente "${nombre}"?`)) ejecutarDesactivarCliente();
  }
}

async function ejecutarDesactivarCliente() {
  const fd = new FormData();
  fd.append('id_cliente', _desactivarClienteId);
  try {
    const res = await apiPost('cliente_desactivar', fd);
    showToast(res.ok ? `🗑 Cliente desactivado` : '❌ Error al desactivar');
    if (res.ok) {
      const row = document.querySelector(`#tbody-clientes tr[data-id="${_desactivarClienteId}"]`);
      if (row) row.style.opacity = '0.3';
      setTimeout(() => location.reload(), 1200);
    }
  } catch(e) {
    showToast('❌ Error de conexión');
  }
}

function filtrarClientes() {
  const q      = (document.getElementById('cli-buscar')?.value   || '').toLowerCase();
  const tipo   = document.getElementById('cli-filtro-tipo')?.value  || '';
  const tienda = document.getElementById('cli-fil-tienda')?.value|| '';
  const rows   = document.querySelectorAll('#tbody-clientes tr[data-tipo]');
  let visible  = 0;
  rows.forEach(tr => {
    const txt   = tr.textContent.toLowerCase();
    const match =
      (!q      || txt.includes(q))                  &&
      (!tipo   || tr.dataset.tipo   === tipo)        &&
      (!tienda || tr.dataset.tienda === tienda);
    tr.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  const c = document.getElementById('cli-contador');
  if (c) c.textContent = `${visible} cliente${visible !== 1 ? 's' : ''} registrado${visible !== 1 ? 's' : ''}`;
}

// ══════════════════════════════════════════════════════════
//  Carga dinámica de datos desde la BD (AJAX)
// ══════════════════════════════════════════════════════════

// ── Cargar tabla de Clientes ──────────────────────────────
let _clientesCargados = false;

async function cargarClientes() {
  if (_clientesCargados) return;
  try {
    const data = await apiGet('clientes_listar');
    if (!Array.isArray(data)) return;
    const tbody = document.getElementById('tbody-clientes');
    const contador = document.getElementById('cli-contador');
    if (!tbody) return;
    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">No hay clientes registrados.</td></tr>';
      if (contador) contador.textContent = '0 clientes registrados';
      return;
    }
    const tipoBadge = t => ({
      'Taller / Negocio': 'badge-info',
      'Mayorista':        'badge-ok',
      'Revendedor':       'badge-warn',
    }[t] || 'badge-gray');

    tbody.innerHTML = data.map(cl => `
      <tr data-id="${cl.id_cliente}"
          data-tipo="${esc(cl.tipo_cliente)}"
          data-tienda="${cl.id_tienda}">
        <td><strong>${esc(cl.nombre)}</strong></td>
        <td>${esc(cl.telefono||'—')}</td>
        <td>${esc(cl.correo||'—')}</td>
        <td><span class="badge ${tipoBadge(cl.tipo_cliente)}">${esc(cl.tipo_cliente||'Individual')}</span></td>
        <td>${esc(cl.rfc||'—')}</td>
        <td>S${cl.id_tienda}</td>
        <td class="tbl-actions">
          <button class="btn btn-sm btn-icon" onclick="editarCliente(${cl.id_cliente})" title="Editar">✏️</button>
          <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarDesactivarCliente(${cl.id_cliente},'${esc(cl.nombre).replace(/'/g,"\\'")}')">🗑</button>
        </td>
      </tr>`).join('');

    if (contador) contador.textContent = `${data.length} cliente${data.length!==1?'s':''} registrado${data.length!==1?'s':''}`;
    _clientesCargados = true;
  } catch(e) {
    console.error('Error al cargar clientes:', e);
  }
}

// ── Cargar datos de Ventas (selects + historial) ──────────
let _ventasDatosOk = false;

async function cargarDatosVentas() {
  // Cargar datos del formulario (clientes + productos)
  if (!_ventasDatosOk) {
    try {
      const data = await apiGet('ventas_datos');
      // Llenar select de clientes
      const selCli = document.getElementById('venta-cliente');
      if (selCli && data.clientes) {
        selCli.innerHTML = '<option value="">Sin cliente (venta directa)</option>' +
          data.clientes.map(cl =>
            `<option value="${esc(cl.nombre)}" data-id="${cl.id_cliente}">${esc(cl.nombre)}</option>`
          ).join('');
      }
      // Llenar select de productos
      const selProd = document.getElementById('prod-sel-ventas');
      if (selProd && data.productos) {
        selProd.innerHTML = '<option value="">Seleccionar producto...</option>' +
          data.productos.map(p => {
            const bajo = parseFloat(p.stock_actual) <= parseFloat(p.stock_minimo);
            const nombre = `${p.catalogo_nombre||''} ${p.presentacion||''}`.trim();
            const unidad = p.unidad_codigo||'u';
            const stock  = parseFloat(p.stock_actual).toFixed(2);
            const precio = parseFloat(p.precio_venta||0).toFixed(2);
            const tipo = p.tipo_hilo||'';
            return `<option value="${p.id_producto}"
              data-id="${p.id_producto}"
              data-name="${esc(nombre)}"
              data-price="${parseFloat(p.precio_venta||0)}"
              data-stock="${parseFloat(p.stock_actual)}"
              data-unidad="${esc(unidad)}"
              data-tipo="${esc(tipo)}"
              >${esc(nombre)} — Stock: ${stock} ${unidad}${bajo?' ⚠':''} — $${precio}/${unidad}</option>`;
          }).join('');
      }
      _ventasDatosOk = true;
    } catch(e) {
      console.error('Error al cargar datos de ventas:', e);
    }
  }

  // Cargar historial de ventas
  try {
    const ventas = await apiGet('ventas_listar');
    const tbody  = document.getElementById('tbody-ventas');
    if (!tbody || !Array.isArray(ventas)) return;
    if (ventas.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">No hay ventas registradas.</td></tr>';
      return;
    }
    tbody.innerHTML = ventas.slice(0,20).map(v => {
      const estado = v.estado || 'completada';
      const badge  = estado === 'completada' ? 'badge-ok' : 'badge-danger';
      const label  = estado === 'completada' ? '✅ OK' : '❌ ' + estado;
      const folio  = v.folio || '#' + String(v.id_venta).padStart(4,'0');
      return `<tr data-tienda="${v.id_tienda}" data-estado="${esc(estado)}">
        <td>${esc(folio)}</td>
        <td>${esc(v.fecha||'—')}</td>
        <td>${esc(v.cliente||'Venta directa')}</td>
        <td><strong>$${parseFloat(v.total||0).toLocaleString('es-MX',{minimumFractionDigits:2})}</strong></td>
        <td>S${v.id_tienda}</td>
        <td><span class="badge ${badge}">${label}</span></td>
        <td class="tbl-actions"><button class="btn btn-sm" onclick="verDetalleVenta(${v.id_venta})">Ver</button></td>
      </tr>`;
    }).join('');
  } catch(e) {
    console.error('Error al cargar ventas:', e);
  }
}

// ── Cargar Inventario (reload desde BD) ───────────────────
async function cargarInventario() {
  // El inventario ya se renderiza desde PHP en la carga inicial.
  // Solo recargamos si el usuario llega después de una venta.
  // (No hace nada por defecto; se puede forzar con cargarInventario(true))
}

// ── Utilidad escapar HTML ─────────────────────────────────
function esc(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g,'&amp;')
    .replace(/</g,'&lt;')
    .replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;');
}

function renderDashboardBarChart(containerId, rows) {
  const container = document.getElementById(containerId);
  if (!container) return;
  if (!Array.isArray(rows) || rows.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted)">Sin datos disponibles.</div>';
    return;
  }
  const maxValue = Math.max(...rows.map(r => parseFloat(r.value) || 0), 1);
  container.innerHTML = rows.map(row => {
    const value = parseFloat(row.value) || 0;
    const width = Math.max(6, Math.min(100, (value / maxValue) * 100));
    return `
      <div class="dash-bar-row">
        <div class="dash-bar-label">${esc(row.label)}</div>
        <div class="dash-bar-track"><div class="dash-bar-fill" style="width:${width}%"></div></div>
        <div class="dash-bar-value">${esc(row.valueText || row.value)}</div>
      </div>`;
  }).join('');
}

function renderDashboardLineChart(containerId, rows) {
  const container = document.getElementById(containerId);
  if (!container) return;
  if (!Array.isArray(rows) || rows.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted)">Sin datos disponibles.</div>';
    return;
  }
  try {
    const values = rows.map(r => parseFloat(r.value) || 0);
    const maxValue = Math.max(...values, 1);
    const points = rows.map((row, index) => {
      const x = rows.length > 1 ? (index * 100 / (rows.length - 1)).toFixed(2) : 50;
      const y = (100 - ((parseFloat(row.value) || 0) / maxValue) * 100).toFixed(2);
      return `${x},${y}`;
    }).join(' ');
    container.innerHTML = `
      <div class="dash-line-chart">
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="dash-line-svg">
          <polyline points="${points}" fill="none" stroke="#2563EB" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" />
          ${rows.map((row, index) => {
            const x = rows.length > 1 ? (index * 100 / (rows.length - 1)).toFixed(2) : 50;
            const y = (100 - ((parseFloat(row.value) || 0) / maxValue) * 100).toFixed(2);
            return `<circle cx="${x}" cy="${y}" r="2.5" fill="#2563EB" />`;
          }).join('')}
        </svg>
        <div class="dash-line-labels">
          ${rows.map(row => `<div class="dash-line-label">${esc(row.label)}<span>${esc(row.valueText || row.value)}</span></div>`).join('')}
        </div>
      </div>`;
  } catch(e) {
    // Error handling for chart rendering
    container.innerHTML = '<div style="color:var(--danger)">Error renderizando gráfico</div>';
  }
}

function renderDashboardPieChart(containerId, rows) {
  const container = document.getElementById(containerId);
  if (!container) return;
  if (!Array.isArray(rows) || rows.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted)">Sin datos disponibles.</div>';
    return;
  }
  try {
    const colors = ['#2563EB','#F59E0B','#10B981','#EF4444','#8B5CF6','#14B8A6','#F97316'];
    const total = rows.reduce((sum, row) => sum + (parseFloat(row.value) || 0), 0);
    if (total === 0) {
      container.innerHTML = '<div style="text-align:center;padding:16px;color:var(--muted)">Sin valores numéricos.</div>';
      return;
    }
    let accumulated = 0;
    const segments = rows.map((row, index) => {
      const value = parseFloat(row.value) || 0;
      const start = accumulated / total * 100;
      const size = value / total * 100;
      accumulated += value;
      return `${colors[index % colors.length]} ${start}% ${start + size}%`;
    }).join(', ');
    container.innerHTML = `
      <div class="dash-pie-wrap">
        <div class="dash-pie-ring" style="background: conic-gradient(${segments});">
          <div class="dash-pie-center">${total}</div>
        </div>
        <div class="dash-pie-legend">
          ${rows.map((row, index) => {
            const value = parseFloat(row.value) || 0;
            return `<div class="dash-pie-item"><span class="dash-pie-key" style="background:${colors[index % colors.length]}"></span><span>${esc(row.label)}</span><strong>${esc(row.valueText || value)}</strong></div>`;
          }).join('')}
        </div>
      </div>`;
  } catch(e) {
    // Error handling for chart rendering
    container.innerHTML = '<div style="color:var(--danger)">Error renderizando gráfico</div>';
  }
}

// ── Función para abrir modal de nuevo cliente limpio ──────
function abrirModalNuevoCliente() {
  // Limpiar campos del modal
  const campos = ['cli-id','cli-nombre','cli-telefono','cli-correo','cli-direccion','cli-rfc','cli-notas'];
  campos.forEach(id => { const el = document.getElementById(id); if(el) el.value = ''; });
  const tipo = document.getElementById('cli-tipo');
  if(tipo) tipo.value = 'Comprador individual';
  openModal('modal-cliente');
}

// ══════════════════════════════════════════════════════════
//  FUNCIONES CARGAR DINÁMICAS — todas las pantallas
// ══════════════════════════════════════════════════════════

// ── Inventario ────────────────────────────────────────────
let _inventarioCargado = false;
async function cargarInventario(forzar = false) {
  if (_inventarioCargado && !forzar) return;
  try {
    const data = await apiGet('inventario_listar');
    if (!Array.isArray(data)) return;

    // Llenar tbody-inventario
    const tbody = document.getElementById('tbody-inventario') || document.getElementById('tbody-productos');
    const cont  = document.getElementById('inv-contador') || document.getElementById('prod-contador');
    if (!tbody) return;
    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;padding:24px;color:var(--muted)">No hay productos registrados.</td></tr>';
      if (cont) cont.textContent = '0 productos';
      return;
    }
    tbody.innerHTML = data.map(p => {
      const bajo = parseFloat(p.stock_actual) <= parseFloat(p.stock_minimo);
      const badge = bajo ? 'badge-danger' : (parseFloat(p.stock_actual) <= parseFloat(p.stock_minimo)*1.5 ? 'badge-warn' : 'badge-ok');
      const stockStatus = bajo ? 'bajo' : 'normal';
      return `<tr data-tipo="${esc(p.tipo_hilo||'')}" data-color="${esc(p.color||'')}" data-stock="${stockStatus}" data-tienda="${p.id_tienda}" data-id="${p.id_producto}">
        <td><strong>${esc(p.nombre)}</strong></td>
        <td>${esc(p.tipo_hilo||'—')}</td>
        <td>${esc(p.color||'—')}</td>
        <td><span class="badge ${badge}">${esc(p.stock_actual)} ${esc(p.unidad||'kg')}${bajo?' ⚠':''}</span></td>
        <td>${esc(p.stock_minimo)} ${esc(p.unidad||'kg')}</td>
        <td>$${parseFloat(p.precio_compra||0).toFixed(2)}</td>
        <td>$${parseFloat(p.precio_venta||0).toFixed(2)}</td>
        <td>S${esc(p.id_tienda||'?')}</td>
        <td>${esc(p.anaquel||'—')}</td>
        <td class="tbl-actions">
          <button class="btn btn-sm btn-icon" onclick="editarProducto(${p.id_producto})" title="Editar">✏️</button>
          <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(${p.id_producto},'producto')">🗑</button>
        </td>
      </tr>`;
    }).join('');
    if (cont) cont.textContent = `${data.length} producto${data.length!==1?'s':''} registrado${data.length!==1?'s':''}`;
    _inventarioCargado = true;

    // También llenar tbody-emp-inventario (vista empleado)
    const tbodyEmp = document.getElementById('tbody-emp-inventario');
    if (tbodyEmp) {
      tbodyEmp.innerHTML = data.map(p => {
        const bajo = parseFloat(p.stock_actual) <= parseFloat(p.stock_minimo);
        const badge = bajo ? 'badge-danger' : 'badge-ok';
        return `<tr data-tipo="${esc(p.tipo_hilo||'')}">
          <td>${esc(p.nombre)}</td>
          <td>${esc(p.tipo_hilo||'—')}</td>
          <td>${esc(p.color||'—')}</td>
          <td><span class="badge ${badge}">${esc(p.stock_actual)} ${esc(p.unidad||'kg')}${bajo?' ⚠':''}</span></td>
          <td>$${parseFloat(p.precio_venta||0).toFixed(2)}</td>
          <td>${esc(p.anaquel||'—')}</td>
          <td><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button></td>
        </tr>`;
      }).join('');
    }
  } catch(e) {
    console.error('Error cargarInventario:', e);
  }
}

function filtrarInventario() {
  const q     = (document.getElementById('inv-buscar')?.value || '').toLowerCase();
  const tipo  = document.getElementById('inv-tipo')?.value  || '';
  const stock = document.getElementById('inv-stock')?.value || '';
  let vis = 0;
  document.querySelectorAll('#tbody-inventario tr[data-id]').forEach(tr => {
    const txt = tr.textContent.toLowerCase();
    const ok  = (!q || txt.includes(q)) &&
                (!tipo  || tr.dataset.tipo  === tipo) &&
                (!stock || tr.dataset.stock === stock);
    tr.style.display = ok ? '' : 'none';
    if (ok) vis++;
  });
  const c = document.getElementById('inv-contador');
  if (c) c.textContent = `${vis} producto${vis!==1?'s':''} registrado${vis!==1?'s':''}`;
}

function filtrarEmpInventario() {
  const q    = (document.getElementById('emp-inv-buscar')?.value || '').toLowerCase();
  const tipo = document.getElementById('emp-inv-tipo')?.value  || '';
  document.querySelectorAll('#tbody-emp-inventario tr[data-tipo]').forEach(tr => {
    const ok = (!q || tr.textContent.toLowerCase().includes(q)) && (!tipo || tr.dataset.tipo === tipo);
    tr.style.display = ok ? '' : 'none';
  });
}

// ── Usuarios ──────────────────────────────────────────────
let _usuariosCargados = false;
async function cargarUsuarios() {
  if (_usuariosCargados) return;
  try {
    const data  = await apiGet('usuarios_listar');
    const tbody = document.getElementById('tbody-usuarios');
    const cont  = document.getElementById('usr-contador');
    if (!tbody || !Array.isArray(data)) return;
    if (data.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">No hay usuarios registrados.</td></tr>';
      return;
    }
    const rolBadge = r => ({'superadmin':'badge-superadmin','admin':'badge-admin','empleado':'badge-empleado'}[r]||'badge-gray');
    tbody.innerHTML = data.map(u => `
      <tr data-id="${u.id_usuario}">
        <td><strong>${esc(u.nombre)}</strong></td>
        <td>${esc(u.correo||'—')}</td>
        <td><span class="badge ${rolBadge(u.rol)}">${esc(u.rol)}</span></td>
        <td>${esc(u.tienda_nombre||'Todas')}</td>
        <td><span class="badge ${u.activo?'badge-ok':'badge-gray'}">${u.activo?'Activo':'Inactivo'}</span></td>
        <td>${esc(u.ultimo_acceso||'—')}</td>
        <td class="tbl-actions">
          <button class="btn btn-sm btn-icon" onclick="editarUsuario(${u.id_usuario})">✏️</button>
          ${u.activo ? `<button class="btn btn-sm btn-icon btn-danger" onclick="desactivarUsuario(${u.id_usuario})">🗑</button>` : `<button class="btn btn-sm" onclick="reactivarUsuario(${u.id_usuario})">Reactivar</button>`}
        </td>
      </tr>`).join('');
    if (cont) cont.textContent = `${data.length} usuario${data.length!==1?'s':''} registrado${data.length!==1?'s':''}`;
    _usuariosCargados = true;
  } catch(e) {
    console.error('Error cargarUsuarios:', e);
  }
}

function abrirModalNuevoUsuario() {
  const campos = ['usr-id','usr-nombre','usr-correo','usr-pass','usr-pass2'];
  campos.forEach(id => { const el = document.getElementById(id); if(el) el.value = ''; });
  openModal('modal-usuario');
}

async function guardarUsuario() {
  const id = document.getElementById('usr-id')?.value;
  const fd = new FormData();
  fd.append('id_usuario',  id || '');
  fd.append('nombre',      document.getElementById('usr-nombre')?.value || '');
  fd.append('correo',      document.getElementById('usr-correo')?.value || '');
  fd.append('rol',         document.getElementById('usr-rol')?.value    || 'empleado');
  fd.append('id_tienda',   document.getElementById('usr-tienda')?.value || '1');
  fd.append('contrasena',  document.getElementById('usr-pass')?.value   || '');
  const action = id ? 'usuario_actualizar' : 'usuario_crear';
  try {
    const res = await apiPost(action, fd);
    closeModal('modal-usuario');
    showToast(res.ok ? `✅ ${res.msg}` : `❌ ${res.msg}`);
    if (res.ok) { _usuariosCargados = false; await cargarUsuarios(); }
  } catch(e) { showToast('❌ Error de conexión'); }
}

async function desactivarUsuario(id) {
  const fd = new FormData(); fd.append('id_usuario', id);
  try {
    const res = await apiPost('usuario_desactivar', fd);
    showToast(res.ok ? '🗑 Usuario desactivado' : '❌ Error');
    if (res.ok) { _usuariosCargados = false; await cargarUsuarios(); }
  } catch(e) { showToast('❌ Error de conexión'); }
}

async function reactivarUsuario(id) {
  const fd = new FormData(); fd.append('id_usuario', id);
  try {
    const res = await apiPost('usuario_reactivar', fd);
    showToast(res.ok ? '✅ Usuario reactivado' : '❌ Error');
    if (res.ok) { _usuariosCargados = false; await cargarUsuarios(); }
  } catch(e) { showToast('❌ Error de conexión'); }
}

// ── Dashboard ─────────────────────────────────────────────
async function cargarDashboard() {
  try {
    const hoy = new Date();
    const thisMonth = `${hoy.getFullYear()}-${String(hoy.getMonth()+1).padStart(2,'0')}`;

    const ventas = await apiGet('ventas_listar');
    if (Array.isArray(ventas)) {
      const ventasMes = ventas.filter(v => (v.fecha||'').startsWith(thisMonth));
      const totalMes = ventasMes.reduce((s,v) => s + parseFloat(v.total||0), 0);
      const elMes = document.getElementById('dash-ventas-mes');
      if (elMes) elMes.textContent = '$' + totalMes.toLocaleString('es-MX',{minimumFractionDigits:2});
      const elVentas = document.getElementById('dash-ventas-delta');
      if (elVentas) elVentas.textContent = `${ventasMes.length} venta${ventasMes.length!==1?'s':''} este mes`;

      const last7 = Array.from({length:7}, (_,idx) => {
        const d = new Date();
        d.setDate(hoy.getDate() - (6 - idx));
        const label = d.toLocaleDateString('es-ES', { weekday: 'short', day: '2-digit' });
        return { key: `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`, label };
      });
      const ventasPorDia = last7.map(day => ({
        label: day.label,
        value: ventas.filter(v => (v.fecha||'') === day.key).length,
      }));
      renderDashboardLineChart('dash-chart-ventas', ventasPorDia.map(item => ({
        ...item,
        valueText: `${item.value} venta${item.value!==1?'s':''}`,
      })));
    }

    const clientes = await apiGet('clientes_listar');
    if (Array.isArray(clientes)) {
      const el = document.getElementById('dash-clientes');
      if (el) el.textContent = clientes.length;
      const nuevosMes = clientes.filter(c => (c.creado_en||'').startsWith(thisMonth)).length;
      const clientDelta = document.getElementById('dash-clientes-delta');
      if (clientDelta) clientDelta.textContent = `+${nuevosMes} este mes`;
      const tipos = clientes.reduce((acc, cliente) => {
        const tipo = cliente.tipo_cliente || 'Otro';
        acc[tipo] = (acc[tipo] || 0) + 1;
        return acc;
      }, {});
      const tiposRows = Object.entries(tipos).sort((a,b) => b[1]-a[1]).map(([label, value]) => ({
        label,
        value,
        valueText: `${value} cliente${value!==1?'s':''}`,
      }));
      renderDashboardPieChart('dash-chart-clientes', tiposRows);
    }

    const alertas = await apiGet('inventario_alertas');
    if (Array.isArray(alertas)) {
      const el = document.getElementById('dash-alertas');
      if (el) el.textContent = alertas.length;
      const lista = document.getElementById('dash-alertas-lista');
      if (lista) {
        lista.innerHTML = alertas.slice(0,5).map(p => `
          <div class="alert-item danger">
            <div class="alert-dot" style="background:var(--danger)"></div>
            <div style="flex:1">
              <div class="font-bold text-sm">${esc(p.nombre)}</div>
              <div class="text-sm text-muted">Stock: ${p.stock_actual} · Mín: ${p.stock_minimo}</div>
            </div>
            <button class="btn btn-sm" onclick="go('compras',null)">🛒 Comprar</button>
          </div>`).join('') || '<div style="text-align:center;padding:16px;color:var(--muted)">Sin alertas activas ✅</div>';
      }
      const delta = document.getElementById('dash-alertas-delta');
      if (delta) delta.textContent = `${alertas.length} activa${alertas.length!==1?'s':''}`;
    }

    const inv = await apiGet('inventario_listar');
    if (Array.isArray(inv)) {
      const el = document.getElementById('dash-productos');
      if (el) el.textContent = inv.length;
      const nuevosProds = inv.filter(p => (p.creado_en||'').startsWith(thisMonth)).length;
      const prodDelta = document.getElementById('dash-prod-delta');
      if (prodDelta) prodDelta.textContent = `+${nuevosProds} este mes`;
      const stockRows = inv
        .map(p => ({
          label: p.catalogo_nombre || p.nombre || 'Producto',
          value: parseFloat(p.stock_actual) || 0,
          valueText: `${parseFloat(p.stock_actual).toFixed(2)} ${p.unidad_codigo || p.unidad || 'u'}`,
        }))
        .sort((a,b) => b.value - a.value)
        .slice(0,6);
      renderDashboardBarChart('dash-chart-inventario', stockRows);
    }
  } catch(e) {
    console.error('Error cargarDashboard:', e);
  }
}

// ── Dashboard empleado ────────────────────────────────────
async function cargarEmpDashboard() {
  try {
    const ventas = await apiGet('ventas_listar');
    if (!Array.isArray(ventas)) return;
    const hoy = new Date().toISOString().slice(0,10);
    const ventasHoy = ventas.filter(v => (v.fecha||'').startsWith(hoy));
    const totalHoy = ventasHoy.reduce((s,v) => s + parseFloat(v.total||0), 0);

    const elTotal = document.getElementById('emp-ventas-hoy');
    if (elTotal) elTotal.textContent = '$' + totalHoy.toLocaleString('es-MX',{minimumFractionDigits:2});
    const elTrans = document.getElementById('emp-transacciones');
    if (elTrans) elTrans.textContent = ventasHoy.length;
    const elProm = document.getElementById('emp-ticket-prom');
    if (elProm) elProm.textContent = ventasHoy.length ? '$' + (totalHoy/ventasHoy.length).toFixed(2) : '$0';
    const elTurno = document.getElementById('emp-total-turno');
    if (elTurno) elTurno.textContent = '$' + totalHoy.toFixed(2);

    const tbody = document.getElementById('emp-tbody-ventas');
    if (tbody) {
      tbody.innerHTML = ventasHoy.slice(0,5).map(v => `
        <tr>
          <td>${esc(v.folio||'#'+String(v.id_venta).padStart(4,'0'))}</td>
          <td>${esc(v.cliente||'Directa')}</td>
          <td>$${parseFloat(v.total||0).toFixed(2)}</td>
          <td>${esc((v.fecha||'').slice(11,16)||'—')}</td>
        </tr>`).join('') || '<tr><td colspan="4" style="text-align:center;color:var(--muted)">Sin ventas hoy</td></tr>';
    }

    const alertas = await apiGet('inventario_alertas');
    if (Array.isArray(alertas)) {
      const el = document.getElementById('emp-alertas-count');
      if (el) el.textContent = alertas.length;
      const lbl = document.getElementById('emp-alertas-label');
      if (lbl) lbl.textContent = `${alertas.length} activa${alertas.length!==1?'s':''}`;
    }
  } catch(e) {
    console.error('Error cargarEmpDashboard:', e);
  }
}
