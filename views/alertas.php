<div id="screen-alertas" class="screen">
  <div class="grid-4" style="margin-bottom:20px">
    <div class="stat-card" style="border-color:#fca5a5;background:var(--dangbg)">
      <div class="stat-num" style="color:var(--danger)" id="cnt-critico">3</div>
      <div class="stat-lbl" style="color:var(--danger)">Crítico — bajo stock</div>
    </div>
    <div class="stat-card" style="border-color:#f5ce70;background:var(--warnbg)">
      <div class="stat-num" style="color:var(--warn)" id="cnt-alerta">2</div>
      <div class="stat-lbl" style="color:var(--warn)">Alerta — stock mínimo</div>
    </div>
    <div class="stat-card">
      <div class="stat-num">2</div>
      <div class="stat-lbl">Sin movimiento +30 días</div>
    </div>
    <div class="stat-card" style="border-color:var(--accent2);background:var(--accentbg)">
      <div class="stat-num" style="color:var(--accent)" id="cnt-atendidas">0</div>
      <div class="stat-lbl" style="color:var(--accent)">Atendidas hoy</div>
    </div>
  </div>
  <!-- Tabs -->
  <div class="flex gap-8 mb-16" style="flex-wrap:wrap">
    <div class="alert-tab active" data-tab="todas" onclick="setAlertTab(this)">Todas (7)</div>
    <div class="alert-tab" data-tab="critico" onclick="setAlertTab(this)">🔴 Crítico (3)</div>
    <div class="alert-tab" data-tab="alerta" onclick="setAlertTab(this)">🟡 Alerta (2)</div>
    <div class="alert-tab" data-tab="sinmov" onclick="setAlertTab(this)">⏱ Sin movimiento (2)</div>
    <div class="alert-tab" data-tab="atendida" onclick="setAlertTab(this)">✅ Atendidas</div>
    <button class="btn btn-sm" style="margin-left:auto" onclick="openModal('modal-alerta')">Marcar todas atendidas</button>
  </div>
  <!-- Crítico -->
  <div class="alert-section" data-type="critico">
    <div class="section-title" style="font-size:13px;color:var(--danger)">🔴 Bajo stock crítico — requiere compra urgente</div>
    <div class="alert-item danger" id="al-1">
      <div class="alert-dot" style="background:var(--danger)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Nylon Negro 1kg</div>
        <div class="text-sm text-muted">Stock: 2 kg · Mínimo: 5 kg · Déficit: 3 kg</div>
        <div class="text-sm" style="color:var(--danger);margin-top:2px">Sucursal 2 — B-2 · Hace 2 días</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver en inventario</button>
      <button class="btn btn-sm btn-primary" onclick="go('compras',null)">🛒 Comprar ahora</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-1','Hilo Nylon Negro 1kg','2 kg','5 kg','Sucursal 2 — B-2')">✓ Atender</button>
    </div>
    <div class="alert-item danger" id="al-2">
      <div class="alert-dot" style="background:var(--danger)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Mercerizado Rosa 100g</div>
        <div class="text-sm text-muted">Stock: 1 kg · Mínimo: 3 kg · Déficit: 2 kg</div>
        <div class="text-sm" style="color:var(--danger);margin-top:2px">Sucursal 1 — A-1 · Hace 1 día</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver en inventario</button>
      <button class="btn btn-sm btn-primary" onclick="go('compras',null)">🛒 Comprar ahora</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-2','Hilo Mercerizado Rosa 100g','1 kg','3 kg','Sucursal 1 — A-1')">✓ Atender</button>
    </div>
    <div class="alert-item danger" id="al-3" style="border-color:#900;background:#fff0f0">
      <div class="alert-dot" style="background:#900"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Elastano Café 50g <span class="badge badge-danger" style="font-size:10px">URGENTE</span></div>
        <div class="text-sm text-muted">Stock: 0 kg · Mínimo: 2 kg · Sin stock</div>
        <div class="text-sm" style="color:var(--danger);margin-top:2px">Sucursal 1 — B-3 · Hace 4 días</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver en inventario</button>
      <button class="btn btn-sm btn-primary" onclick="go('compras',null)">🛒 Comprar ahora</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-3','Hilo Elastano Café 50g','0 kg','2 kg','Sucursal 1 — B-3')">✓ Atender</button>
    </div>
  </div>
  <!-- Alerta mínimo -->
  <div class="alert-section" data-type="alerta">
    <div class="section-title" style="font-size:13px;color:var(--warn)">🟡 Stock en nivel mínimo — atención requerida</div>
    <div class="alert-item warn" id="al-4">
      <div class="alert-dot" style="background:var(--warn)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Algodón Rojo 250g</div>
        <div class="text-sm text-muted">Stock: 4 kg · Mínimo: 5 kg · Déficit: 1 kg</div>
        <div class="text-sm" style="color:var(--warn);margin-top:2px">Sucursal 1 — A-2 · Hace 3 días</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver en inventario</button>
      <button class="btn btn-sm" onclick="go('compras',null)">🛒 Comprar</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-4','Hilo Algodón Rojo 250g','4 kg','5 kg','Sucursal 1 — A-2')">✓ Atender</button>
    </div>
    <div class="alert-item warn" id="al-5">
      <div class="alert-dot" style="background:var(--warn)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Lana Azul Marino 100g</div>
        <div class="text-sm text-muted">Stock: 3 kg · Mínimo: 3 kg · En límite</div>
        <div class="text-sm" style="color:var(--warn);margin-top:2px">Sucursal 2 — C-1 · Hace 1 día</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver en inventario</button>
      <button class="btn btn-sm" onclick="go('compras',null)">🛒 Comprar</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-5','Hilo Lana Azul Marino 100g','3 kg','3 kg','Sucursal 2 — C-1')">✓ Atender</button>
    </div>
  </div>
  <!-- Sin movimiento -->
  <div class="alert-section" data-type="sinmov">
    <div class="section-title" style="font-size:13px">⏱ Sin movimiento más de 30 días — revisar si hay sobrestock</div>
    <div class="alert-item" id="al-6" style="border-color:var(--border2)">
      <div class="alert-dot" style="background:var(--hint)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Seda Lavanda 50g</div>
        <div class="text-sm text-muted">Último movimiento: 08/02/2025 · Stock: 6 kg · $200.00/kg</div>
        <div class="text-sm" style="color:var(--hint);margin-top:2px">Sucursal 1 — B-1 · <strong>35 días sin mov.</strong></div>
        <div class="text-sm" style="color:var(--info);margin-top:4px">💡 Considera reducir precio, cambiar ubicación o notificar a clientes frecuentes.</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver historial</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-6','Hilo Seda Lavanda 50g','6 kg (sin mov.)','—','Sucursal 1 — B-1')">✓ Marcar atendida</button>
    </div>
    <div class="alert-item" id="al-7" style="border-color:var(--border2)">
      <div class="alert-dot" style="background:var(--hint)"></div>
      <div style="flex:1">
        <div class="font-bold">Hilo Bambú Beige 200g</div>
        <div class="text-sm text-muted">Último movimiento: 01/02/2025 · Stock: 4 kg · $160.00/kg</div>
        <div class="text-sm" style="color:var(--hint);margin-top:2px">Sucursal 2 — C-2 · <strong>42 días sin mov.</strong></div>
        <div class="text-sm" style="color:var(--info);margin-top:4px">💡 Considera reducir precio, cambiar ubicación o notificar a clientes frecuentes.</div>
      </div>
      <button class="btn btn-sm" onclick="go('inventario',null)">Ver historial</button>
      <button class="btn btn-sm" onclick="openAtenderModal('al-7','Hilo Bambú Beige 200g','4 kg (sin mov.)','—','Sucursal 2 — C-2')">✓ Marcar atendida</button>
    </div>
  </div>
  <!-- Atendidas -->
  <div class="alert-section" data-type="atendida" style="display:none">
    <div class="section-title" style="font-size:13px;color:var(--accent)">✅ Alertas atendidas esta semana</div>
    <div id="atendidas-list">
      <div class="alert-item done" style="background:var(--accentbg);border-color:var(--accent2)">
        <div class="alert-dot" style="background:var(--accent)"></div>
        <div style="flex:1">
          <div class="font-bold">Hilo Acrílico Blanco 500g</div>
          <div class="text-sm text-muted">Tipo: Bajo stock · Atendida: 14/03/2025 11:30 · Por: Hernán M.</div>
          <div class="text-sm" style="color:var(--accent)">Acción: Compra realizada: +20 kg</div>
        </div>
        <span class="badge badge-ok">✅ Atendida</span>
        <button class="btn btn-xs" onclick="showToast('Alerta reabierta','warn')">Reabrir</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ LOCALIZADOR ════════════════════════════════════════ -->