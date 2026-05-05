<div id="screen-clientes" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm" id="cli-contador">Cargando clientes...</div>
    <button class="btn btn-primary" onclick="abrirModalNuevoCliente()">+ Nuevo cliente</button>
  </div>
  <div class="filter-bar">
    <input id="cli-buscar" class="form-control" placeholder="🔍 Buscar nombre, teléfono..." style="max-width:280px" oninput="filtrarClientes()">
    <select id="cli-filtro-tipo" class="form-control" style="max-width:170px" onchange="filtrarClientes()">
      <option value="">Todos los tipos</option>
      <option>Comprador individual</option>
      <option>Taller / Negocio</option>
      <option>Mayorista</option>
      <option>Revendedor</option>
    </select>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Tipo</th>
          <th>RFC</th>
          <th>Tienda</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody-clientes">
        <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Cargando...</td></tr>
      </tbody>
    </table>
  </div>
</div>
