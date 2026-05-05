<div id="app" class="app" style="display:none">

  <!-- SIDEBAR -->
  <nav class="sidebar" id="sidebar">
    <div class="sb-logo">
      <div class="sb-logo-mark">HL</div>
      <div>
        <div class="sb-logo-text">HLazcano</div>
        <div class="sb-logo-sub" id="sb-version">Inventario v3.1</div>
      </div>
    </div>
    <div class="sb-store" id="store-switcher-wrap">
      <label>Tienda activa</label>
      <select id="store-select" onchange="switchStore()">
        <option value="0">📍 Todas las tiendas</option>
        <option value="1">📍 Sucursal 1 — Santa María Texmelucan</option>
        <option value="2">📍 Sucursal 2 — Por definir</option>
      </select>
    </div>

    <!-- ── NAV ADMIN / SUPERADMIN ───────────────────────── -->
    <nav id="admin-nav">

      <div class="sb-section">Principal</div>
      <div class="sb-item active" onclick="go('dashboard',this)">
        <span class="icon">◉</span>Dashboard
      </div>

      <div class="sb-section">Inventario</div>
      <div class="sb-item" onclick="go('inventario',this)">
        <span class="icon">📦</span>Inventario
      </div>
      <div class="sb-item" onclick="go('productos',this)">
        <span class="icon">🏷️</span>Productos
      </div>
      <div class="sb-item" onclick="go('alertas',this)">
        <span class="icon">🔔</span>Alertas<span class="badge">5</span>
      </div>
      <div class="sb-item" onclick="go('localizador',this)">
        <span class="icon">🗺️</span>Localizador
      </div>

      <div class="sb-section">Ventas</div>
      <div class="sb-item" onclick="go('ventas',this)">
        <span class="icon">🛒</span>Ventas
      </div>
      <div class="sb-item" onclick="go('clientes',this)">
        <span class="icon">👥</span>Clientes
      </div>

      <div class="sb-section">Compras</div>
      <div class="sb-item" onclick="go('compras',this)">
        <span class="icon">📥</span>Compras
      </div>

      <div class="sb-section">Análisis</div>
      <div class="sb-item" onclick="go('reportes',this)">
        <span class="icon">📊</span>Reportes
      </div>

      <div class="sb-section">Configuración</div>
      <div class="sb-item" onclick="go('usuarios',this)">
        <span class="icon">👤</span>Usuarios
      </div>
      <div class="sb-item" onclick="go('catalogos',this)">
        <span class="icon">📋</span>Catálogos
      </div>

    </nav><!-- /admin-nav -->

    <!-- ── NAV EMPLEADO ─────────────────────────────────── -->
    <nav id="emp-nav" style="display:none">

      <div class="sb-section">Mi trabajo</div>
      <div class="sb-item active" onclick="go('emp-dashboard',this)">
        <span class="icon">◉</span>Mi turno
      </div>

      <div class="sb-section">Ventas</div>
      <div class="sb-item" onclick="go('emp-ventas',this)">
        <span class="icon">🛒</span>Registrar venta
      </div>
      <div class="sb-item" onclick="go('clientes',this)">
        <span class="icon">👥</span>Clientes
      </div>

      <div class="sb-section">Inventario</div>
      <div class="sb-item" onclick="go('emp-inventario',this)">
        <span class="icon">📦</span>Ver inventario
      </div>
      <div class="sb-item" onclick="go('alertas',this)">
        <span class="icon">🔔</span>Alertas<span class="badge">5</span>
      </div>
      <div class="sb-item" onclick="go('localizador',this)">
        <span class="icon">🗺️</span>Localizador
      </div>

      <div class="sb-section">Sin acceso</div>
      <div class="sb-item locked"><span class="icon">📊</span>Reportes<span class="sb-lock">🔒</span></div>
      <div class="sb-item locked"><span class="icon">📥</span>Compras<span class="sb-lock">🔒</span></div>
      <div class="sb-item locked"><span class="icon">📋</span>Catálogos<span class="sb-lock">🔒</span></div>

    </nav><!-- /emp-nav -->

    <div class="sb-footer">
      <div class="sb-user">
        <div class="sb-avatar" id="sb-av">HM</div>
        <div class="sb-info">
          <div class="sb-name" id="sb-name">Hernán Meneses</div>
          <div class="sb-role" id="sb-role">Superadmin</div>
        </div>
        <button class="sb-logout" onclick="doLogout()">↩</button>
      </div>
    </div>
  </nav>

  <!-- MAIN -->
  <div class="main-area">
    <div class="topbar">
      <div class="page-title" id="page-title">Dashboard</div>
      <div class="topbar-store" id="topbar-store">📍 Todas las tiendas</div>
      <span id="emp-badge-top" style="display:none" class="emp-badge">👤 Empleado</span>
      <div class="topbar-notif" onclick="go('alertas',null)">🔔<div class="notif-dot"></div></div>
      <div class="topbar-user">
        <div class="tu-avatar" id="tu-av">HM</div>
        <span class="tu-name" id="tu-name">Hernán M.</span>
        <span>▾</span>
      </div>
    </div>
    <div class="content">

<!-- ══ DASHBOARD ══════════════════════════════════════════ -->
