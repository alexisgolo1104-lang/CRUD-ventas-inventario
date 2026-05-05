<div id="screen-emp-inventario" class="screen">
  <div class="notice notice-warn mb-12">👁️ <strong>Modo solo lectura</strong> — Puedes consultar el inventario pero no modificarlo</div>
  <div class="filter-bar">
    <input class="form-control" id="emp-inv-buscar" placeholder="🔍 Buscar..." style="max-width:260px" oninput="filtrarEmpInventario()">
    <select class="form-control" id="emp-inv-tipo" onchange="filtrarEmpInventario()" style="max-width:160px">
      <option value="">Todos los tipos</option>
      <option>Acrílico</option><option>Algodón</option><option>Nylon</option>
      <option>Poliéster</option><option>Lana</option><option>Mercerizado</option>
    </select>
    <div style="margin-left:auto;padding:6px 12px;background:var(--surf2);border:1px solid var(--border);border-radius:8px;font-size:12px;color:var(--hint)">🔒 Solo lectura</div>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th><th>P.Venta</th><th>Anaquel</th><th>Localizar</th></tr></thead>
      <tbody id="tbody-emp-inventario">
        <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Cargando...</td></tr>
      </tbody>
    </table>
  </div>
</div>
