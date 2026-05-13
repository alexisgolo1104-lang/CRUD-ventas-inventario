<div id="screen-inventario" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm" id="inv-contador">Cargando inventario...</div>
    <div class="flex gap-8">
      <button class="btn btn-primary" onclick="nuevoProducto()">+ Nuevo producto</button>
      <button class="btn" onclick="exportarTabla('tbl-inventario')">⬇ Exportar</button>
    </div>
  </div>
  <div class="filter-bar">
    <input class="form-control" id="inv-buscar" placeholder="🔍 Buscar producto..." style="max-width:240px" oninput="filtrarInventario()">
    <select class="form-control" id="inv-tipo" onchange="filtrarInventario()" style="max-width:170px">
      <option value="">Todos los tipos</option>
      <option>Acrílico</option><option>Algodón</option><option>Nylon</option>
      <option>Poliéster</option><option>Lana</option><option>Mercerizado</option>
      <option>Elastano</option><option>Bambú</option><option>Seda</option>
    </select>
    <select class="form-control" id="inv-stock" onchange="filtrarInventario()" style="max-width:150px">
      <option value="">Todo el stock</option>
      <option value="bajo">⚠ Bajo stock</option>
      <option value="ok">✅ Normal</option>
    </select>
  </div>
  <div class="table-wrap">
    <table id="tbl-inventario">
      <thead>
        <tr>
          <th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th>
          <th>Mín.</th><th>P.Compra</th><th>P.Venta</th><th>Tienda</th><th>Anaquel</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-inventario">
        <tr><td colspan="10" style="text-align:center;padding:24px;color:var(--muted)">Cargando...</td></tr>
      </tbody>
    </table>
  </div>
</div>
