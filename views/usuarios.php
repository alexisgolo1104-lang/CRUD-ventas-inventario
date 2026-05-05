<div id="screen-usuarios" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm" id="usr-contador">Cargando usuarios...</div>
    <button class="btn btn-primary" onclick="abrirModalNuevoUsuario()">+ Nuevo usuario</button>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Tienda</th><th>Estado</th><th>Último acceso</th><th>Acciones</th></tr>
      </thead>
      <tbody id="tbody-usuarios">
        <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Cargando...</td></tr>
      </tbody>
    </table>
  </div>
</div>
