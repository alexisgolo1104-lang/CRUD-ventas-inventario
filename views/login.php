<div id="login-page" class="login-wrap">
  <div class="login-card" style="position:relative;z-index:2">
    <div class="login-logo">
      <div class="login-icon">HL</div>
      <div class="login-title">HLazcano</div>
      <div class="login-sub">Sistema de Gestión de Inventario</div>
    </div>
    <div class="role-tabs mb-12">
      <div class="role-tab active" id="tab-admin" onclick="setRole('admin',this)">
        <div class="role-tab-icon">🛡️</div>
        <div class="role-tab-label">Administrador</div>
      </div>
      <div class="role-tab" id="tab-emp" onclick="setRole('empleado',this)">
        <div class="role-tab-icon">👤</div>
        <div class="role-tab-label">Empleado</div>
      </div>
    </div>
    <div class="form-group mb-12">
      <label class="form-label">Usuario</label>
      <select class="form-control" id="login-user">
        <option value="superadmin">Hernán Meneses — Superadmin</option>
        <option value="admin1">Gerente Sucursal 1 — Admin</option>
        <option value="admin2">Gerente Sucursal 2 — Admin</option>
      </select>
    </div>
    <div class="form-group" style="margin-bottom:20px">
      <label class="form-label">Contraseña</label>
      <input type="password" class="form-control" value="12345678" placeholder="Contraseña">
    </div>
    <button class="btn btn-primary w-full" style="justify-content:center;padding:11px 0;font-size:14px" onclick="doLogin()" style="background:#1E3A8A;border-color:#1E3A8A;font-size:14px;padding:12px 0">Ingresar al sistema</button>
    <p style="text-align:center;margin-top:14px;font-size:11px;color:var(--hint)">Solo personal autorizado · HLazcano © 2025</p>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════ -->
<!-- APP                                                      -->