let currentRole='admin';
const storeLabels=['📍 Todas las tiendas','📍 Sucursal 1 — Santa María Texmelucan','📍 Sucursal 2 — Por definir'];
const pages={
  'dashboard':'Dashboard','inventario':'Inventario','ventas':'Ventas','compras':'Compras',
  'clientes':'Clientes','alertas':'Alertas','localizador':'Localizador','reportes':'Reportes',
  'usuarios':'Usuarios','catalogos':'Catálogos',
  'productos':'Productos','emp-ventas':'Registrar venta','emp-inventario':'Ver inventario','cliente-perfil':'Perfil de cliente','emp-dashboard':'Mi espacio de trabajo'
};

function setRole(role,el){
  currentRole=role;
  document.querySelectorAll('.role-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  const u=document.getElementById('login-user');
  if(role==='admin'){
    u.innerHTML='<option value="superadmin">Hernán Meneses — Superadmin</option><option value="admin1">Gerente Sucursal 1 — Admin</option><option value="admin2">Gerente Sucursal 2 — Admin</option>';
  } else {
    u.innerHTML='<option value="emp1">Ana Ramírez — Empleado S1</option><option value="emp2">Carlos Mendoza — Empleado S2</option>';
  }
}

async function doLogin(){
  // ── Establecer sesión PHP en el backend ──────────────────
  const correoMap={
    'superadmin':'superadmin@hlazcano.com',
    'admin1':    'admin1@hlazcano.com',
    'admin2':    'admin2@hlazcano.com',
    'emp1':      'emp1@hlazcano.com',
    'emp2':      'emp2@hlazcano.com',
  };
  const uv=document.getElementById('login-user').value;
  const correo=correoMap[uv]||'superadmin@hlazcano.com';
  const fdSesion=new FormData();
  fdSesion.append('correo', correo);

  let loginData;
  try {
    const resp = await fetch('index.php?action=sesion_establecer',{method:'POST',credentials:'same-origin',body:fdSesion});
    loginData = await resp.json();
  } catch (e) {
    console.error('Login error:', e);
    showToast('❌ No se pudo iniciar sesión. Revisa tu conexión.');
    return;
  }
  if (!loginData?.ok) {
    showToast('❌ Error al iniciar sesión.');
    return;
  }

  // Guardar token en localStorage para persistir sesión
  localStorage.setItem('usuario_rol', loginData.rol);
  localStorage.setItem('id_tienda', loginData.tienda);
  localStorage.setItem('sesion_activa', 'true');
  localStorage.setItem('sesion_tiempo', Date.now());

  // ── UI del login ─────────────────────────────────────────
  document.getElementById('login-page').style.display='none';
  document.getElementById('app').style.display='flex';
  const isAdmin=currentRole==='admin';
  document.getElementById('admin-nav').style.display=isAdmin?'block':'none';
  document.getElementById('emp-nav').style.display=isAdmin?'none':'block';
  document.getElementById('store-switcher-wrap').style.display=isAdmin?'block':'none';
  document.getElementById('emp-badge-top').style.display=isAdmin?'none':'inline';
  let name='Hernán M.',av='HM',role='Superadmin';
  if(uv==='admin1'){name='Gerente S1';av='G1';role='Admin'}
  else if(uv==='admin2'){name='Gerente S2';av='G2';role='Admin'}
  else if(uv==='emp1'){name='Ana Ramírez';av='AR';role='Empleado · S1'}
  else if(uv==='emp2'){name='Carlos M.';av='CM';role='Empleado · S2'}
  document.getElementById('sb-av').textContent=av;
  document.getElementById('sb-name').textContent=name;
  document.getElementById('sb-role').textContent=role;
  document.getElementById('tu-av').textContent=av;
  document.getElementById('tu-name').textContent=name;
  document.getElementById('sb-version').textContent='Inventario v3.0';
  if(isAdmin){
    clearScreens();
    document.getElementById('screen-dashboard').classList.add('active');
    document.getElementById('page-title').textContent='Dashboard';
  } else {
    clearScreens();
    document.getElementById('screen-emp-dashboard').classList.add('active');
    document.getElementById('page-title').textContent='Mi espacio de trabajo';
  }
  document.querySelectorAll('.sb-item').forEach(i=>i.classList.remove('active'));
  document.querySelector('#'+(isAdmin?'admin':'emp')+'-nav .sb-item:not(.locked)').classList.add('active');
  const contentArea=document.querySelector('.content');
  if(contentArea) contentArea.scrollTop=0;
  window.scrollTo(0,0);

  // ── Cargar datos iniciales de la pantalla activa ──
  setTimeout(() => {
    if (isAdmin) {
      if (typeof cargarDashboard    === 'function') cargarDashboard();
    } else {
      if (typeof cargarEmpDashboard === 'function') cargarEmpDashboard();
    }
  }, 200);
}

function doLogout(){
  document.getElementById('app').style.display='none';
  document.getElementById('login-page').style.display='flex';
  document.getElementById('login-user').value='superadmin';
  localStorage.removeItem('sesion_activa');
  localStorage.removeItem('usuario_rol');
  localStorage.removeItem('id_tienda');
  localStorage.removeItem('sesion_tiempo');
}

function verificarSesionLocal() {
  return localStorage.getItem('sesion_activa') === 'true' &&
         localStorage.getItem('usuario_rol') &&
         localStorage.getItem('id_tienda');
}

function clearScreens(){
  document.querySelectorAll('.screen').forEach(s=>s.classList.remove('active'));
}

function go(name,navEl){
  clearScreens();
  const sc=document.getElementById('screen-'+name);
  if(sc) sc.classList.add('active');
  else{
    console.warn('Screen not found:', name);
  }
  document.querySelectorAll('.sb-item:not(.locked)').forEach(i=>i.classList.remove('active'));
  if(navEl) navEl.classList.add('active');
  // Scroll content area to top always
  const contentArea=document.querySelector('.content');
  if(contentArea) contentArea.scrollTop=0;
  window.scrollTo(0,0);
  document.getElementById('page-title').textContent=pages[name]||name;

  // ── Cargar datos dinámicos según pantalla ──
  if (name === 'clientes')                        { if(typeof cargarClientes==='function')    cargarClientes(); }
  if (name === 'inventario')                      { if(typeof cargarInventario==='function')  cargarInventario(); }
  if (name === 'productos')                       { if(typeof cargarInventario==='function')  cargarInventario(); }
  if (name === 'emp-inventario')                  { if(typeof cargarInventario==='function')  cargarInventario(); }
  if (name === 'usuarios')                        { if(typeof cargarUsuarios==='function')    cargarUsuarios(); }
  if (name === 'dashboard')                       { if(typeof cargarDashboard==='function')   cargarDashboard(); }
  if (name === 'emp-dashboard')                   { if(typeof cargarEmpDashboard==='function')cargarEmpDashboard(); }
  if (name === 'ventas' || name === 'emp-ventas') {
    if(typeof cargarDatosVentas==='function') cargarDatosVentas();
  }
}

function switchStore(){
  const v=document.getElementById('store-select').value;
  const labels=['📍 Todas las tiendas','📍 Sucursal 1 — Santa María Texmelucan','📍 Sucursal 2 — Por definir'];
  document.getElementById('topbar-store').textContent=labels[v]||labels[0];
}

function openModal(id){
  const m=document.getElementById(id);
  if(!m) return;
  m.classList.add('open');
  document.body.style.overflow='hidden';
  // Scroll modal body to top
  setTimeout(()=>{
    const mb=m.querySelector('.modal-body');
    if(mb) mb.scrollTop=0;
  },50);
}
function closeModal(id){
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow='';
}
function closeOut(e,id){
  if(e.target===e.currentTarget) closeModal(id);
}

function setRepTab(el){
  const ca=document.querySelector('.content');if(ca) ca.scrollTop=0;
  document.querySelectorAll('.rep-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  const txt=el.textContent.trim();
  // Show different stats per tab
  const repStats={
    '📊 Ventas':['$18,420','Ventas totales','142','Transacciones','$129.70','Ticket promedio'],
    '📦 Inventario':['347','Productos registrados','$28,450','Valor inventario','5','Bajo stock'],
    '📥 Compras':['$10,515','Total compras','8','Órdenes procesadas','3','Proveedores activos'],
    '💰 Egresos':['$12,840','Total egresos','$10,515','Compras inventario','$5,580','Utilidad bruta'],
    '👥 Clientes':['84','Clientes totales','$4,820','Ticket promedio','28','Activos este mes'],
    '📈 Comparativo':['$11,240','S1 — Ventas','$7,180','S2 — Ventas','84%','Catálogo vendido'],
  };
  const key=Object.keys(repStats).find(k=>txt.includes(k.replace(/[📊📦📥💰👥📈]\s*/,'')));
  const data=repStats[txt]||repStats['📊 Ventas'];
  const cards=document.querySelectorAll('#screen-reportes .stat-card');
  if(cards.length>=3 && data){
    cards[0].querySelector('.stat-num').textContent=data[0];
    cards[0].querySelector('.stat-lbl').textContent=data[1];
    cards[1].querySelector('.stat-num').textContent=data[2];
    cards[1].querySelector('.stat-lbl').textContent=data[3];
    cards[2].querySelector('.stat-num').textContent=data[4];
    cards[2].querySelector('.stat-lbl').textContent=data[5];
  }
}

function showRolInfo(sel){
  const info=document.getElementById('rol-info');
  if(!info) return;
  const msgs={
    'empleado':'<strong>Empleado:</strong> Solo puede registrar ventas, consultar inventario y gestionar clientes en su tienda asignada.',
    'admin':'<strong>Admin:</strong> Gestión completa de su tienda: ventas, compras, inventario, clientes y reportes de su sucursal.',
    'superadmin':'<strong>Superadmin:</strong> Acceso total al sistema — todas las tiendas, usuarios, configuración y reportes globales.'
  };
  info.innerHTML='ℹ️ '+(msgs[sel.value]||msgs['empleado']);
}

// ── Alertas interactivas ──────────────────────────────────
function openAtenderModal(itemId, name, stock, min, loc){
  document.getElementById('alert-modal-name').textContent=name;
  document.getElementById('alert-modal-info').textContent='Stock actual: '+stock+' · Mínimo: '+min+' · '+loc;
  document.getElementById('alert-accion').value='';
  document.getElementById('alert-nota-wrap').style.display='none';
  document.getElementById('alert-accion-preview').style.display='none';
  document.getElementById('alert-nota').value='';
  document.getElementById('modal-alerta').dataset.targetId=itemId;
  openModal('modal-alerta');
}

function toggleAlertNota(sel){
  const wrap=document.getElementById('alert-nota-wrap');
  const preview=document.getElementById('alert-accion-preview');
  const msgs={
    'compra':'Se registrará una compra/entrada. El stock se actualizará automáticamente.',
    'ajuste':'Se ajustará el stock mínimo para este producto.',
    'discontinue':'El producto se marcará como descontinuado.',
    'precio':'El precio se modificará para estimular la venta.',
    'otro':''
  };
  if(sel.value){
    preview.innerHTML='ℹ️ '+msgs[sel.value];
    preview.style.display=msgs[sel.value]?'flex':'none';
  }
  wrap.style.display=(sel.value==='otro'||sel.value==='compra')?'block':'none';
}

function confirmarAtender(){
  const accion=document.getElementById('alert-accion').value;
  if(!accion){showToast('Selecciona una acción primero','warn');return;}
  const targetId=document.getElementById('modal-alerta').dataset.targetId;
  closeModal('modal-alerta');
  if(targetId){
    const item=document.getElementById(targetId);
    if(item){
      item.style.transition='all .4s ease';
      item.style.opacity='0';
      item.style.transform='translateX(30px)';
      setTimeout(()=>{
        item.remove();
        // Update count
        const atEl=document.getElementById('cnt-atendidas');
        if(atEl) atEl.textContent=parseInt(atEl.textContent||'0')+1;
        // Add to atendidas list
        const list=document.getElementById('atendidas-list');
        if(list){
          const d=document.createElement('div');
          const accionTxt=document.getElementById('alert-accion').value||accion;
          const labels={'compra':'Compra realizada','ajuste':'Stock mínimo ajustado','discontinue':'Producto descontinuado','precio':'Precio reducido','otro':'Acción manual'};
          d.className='alert-item done';
          d.style.cssText='background:var(--accentbg);border-color:var(--accent2);animation:fadeUp .3s ease';
          d.innerHTML=`<div class="alert-dot" style="background:var(--accent)"></div><div style="flex:1"><div class="font-bold">${document.getElementById('alert-modal-name').textContent}</div><div class="text-sm text-muted">Atendida ahora · Por: Hernán M.</div><div class="text-sm" style="color:var(--accent)">Acción: ${labels[accion]||accion}</div></div><span class="badge badge-ok">✅ Atendida</span>`;
          list.prepend(d);
        }
        showToast('✅ Alerta marcada como atendida');
        const ca=document.querySelector('.content');if(ca) ca.scrollTop=0;
      },400);
    }
  }
}

function marcarAtendida(btn, itemId){
  const item=document.getElementById(itemId);
  if(!item) return;
  item.style.opacity='0';
  item.style.transform='translateX(20px)';
  item.style.transition='all .3s ease';
  setTimeout(()=>{
    item.remove();
    updateAlertCounts();
    showToast('✅ Alerta marcada como atendida');
  },300);
}

function updateAlertCounts(){
  const criticos=document.querySelectorAll('#screen-alertas .alert-item.danger').length;
  const warns=document.querySelectorAll('#screen-alertas .alert-item.warn').length;
  const badge=document.querySelector('.sb-item .badge');
  const total=criticos+warns;
  if(badge){ badge.textContent=total; if(total===0) badge.style.display='none'; }
  const dot=document.querySelector('.notif-dot');
  if(dot && total===0) dot.style.display='none';
}

function openAlertModal(name, stock, min, loc){
  document.getElementById('alert-modal-name').textContent=name;
  document.getElementById('alert-modal-info').textContent=`Stock actual: ${stock} · Mínimo: ${min} · ${loc}`;
  openModal('modal-alerta');
}

// ── Localizador interactivo ───────────────────────────────
function buscarProducto(){
  const prod=document.getElementById('loc-prod').value;
  const result=document.getElementById('loc-result');
  const cells=document.querySelectorAll('.shelf-cell');
  cells.forEach(c=>{c.classList.remove('found'); c.style.background='';});

  const map={
    'acrilico-blanco':{'cell':'A-1','name':'Hilo Acrílico Blanco 500g','stock':'12 kg','precio':'$65.00/kg','nota':'Fila 1, Columna 2 — frente a entrada principal'},
    'nylon-negro':{'cell':'B-2','name':'Hilo Nylon Negro 1kg','stock':'2 kg ⚠','precio':'$110.00/kg','nota':'Fila 1, Columna 1 — sector central'},
    'algodon-rojo':{'cell':'A-2','name':'Hilo Algodón Rojo 250g','stock':'4 kg','precio':'$78.00/kg','nota':'Fila 2, Columna 1 — continuación pasillo A'},
    'poliester-azul':{'cell':'C-1','name':'Hilo Poliéster Azul 500g','stock':'18 kg','precio':'$55.00/kg','nota':'Fila 1, Columna 3 — fondo derecho'},
    'lana-verde':{'cell':'A-1','name':'Hilo Lana Verde 100g','stock':'9 kg','precio':'$130.00/kg','nota':'Fila 2, Columna 1 — entrada principal'},
    'seda-lavanda':{'cell':'B-1','name':'Hilo Seda Lavanda 50g','stock':'4 kg','precio':'$280.00/kg','nota':'Fila 1, Columna 2 — lado derecho'},
    'mercerizado-rosa':{'cell':'A-1','name':'Hilo Mercerizado Rosa 100g','stock':'1 kg ⚠','precio':'$95.00/kg','nota':'Fila 3, Columna 1 — entrada principal'},
  };
  const item=map[prod];
  if(!item){
    result.innerHTML='<div class="notice notice-warn">Selecciona un producto para localizar</div>';
    return;
  }
  // Highlight cell
  cells.forEach(c=>{ if(c.dataset.code===item.cell){c.classList.add('found');} });
  const isLow=item.stock.includes('⚠');
  result.innerHTML=`
    <div class="notice notice-success" style="margin-bottom:12px">📍 <strong>Anaquel ${item.cell} encontrado</strong></div>
    <div style="background:var(--accentbg);border:1px solid var(--accent2);border-radius:10px;padding:14px;margin-bottom:12px">
      <div class="font-bold" style="margin-bottom:4px">${item.name}</div>
      <div class="text-sm text-muted" style="margin-bottom:6px">${item.nota}</div>
      <div class="flex gap-12">
        <span class="badge ${isLow?'badge-danger':'badge-ok'}">${item.stock}</span>
        <span class="text-sm font-bold">${item.precio}</span>
      </div>
    </div>
    <div class="flex gap-8">
      <button class="btn" style="flex:1;justify-content:center" onclick="go('inventario',null)">Ver inventario</button>
      <button class="btn btn-primary" style="flex:1;justify-content:center" onclick="go('ventas',null)">🛒 Agregar a venta</button>
    </div>`;
}

function clickShelf(el, code){
  document.querySelectorAll('.shelf-cell').forEach(c=>c.style.borderColor='');
  el.style.borderColor='var(--accent)';
  const contents={
    'A-1':['Hilo Acrílico Blanco 500g · 12 kg · $65/kg','Hilo Lana Verde 100g · 9 kg · $130/kg','Hilo Mercerizado Rosa · 1 kg ⚠ · $95/kg'],
    'A-2':['Hilo Algodón Rojo 250g · 4 kg · $78/kg','Hilo Nylon Blanco 1kg · 8 kg · $110/kg','Hilo Algodón Blanco 250g · 3 kg · $78/kg'],
    'B-1':['Hilo Seda Lavanda 50g · 4 kg · $280/kg','Hilo Elastano Amarillo · 6 kg · $170/kg'],
    'B-2':['Hilo Nylon Negro 1kg · 2 kg ⚠ · $110/kg','Hilo Nylon Blanco 500g · 8 kg · $110/kg'],
    'B-3':['Hilo Elastano Café 50g · 0 kg ⚠ · $120/kg'],
    'C-1':['Hilo Poliéster Azul 500g · 18 kg · $55/kg','Hilo Lana Azul Marino · 3 kg · $90/kg'],
    'C-2':['Hilo Bambú Beige 200g · 4 kg · $160/kg'],
    'A-3':[],
    'C-3':[],
  };
  const items=contents[code]||[];
  const res=document.getElementById('loc-result');
  if(items.length===0){
    res.innerHTML=`<div class="notice notice-warn">📦 Anaquel ${code} — sin productos registrados</div>`;
    return;
  }
  res.innerHTML=`<div class="section-title" style="font-size:13px;margin-bottom:10px">Anaquel ${code} — ${items.length} producto(s)</div>`+
    items.map(i=>{
      const low=i.includes('⚠');
      return `<div class="alert-item ${low?'danger':''}" style="margin-bottom:6px">
        <div style="flex:1"><div class="text-sm font-bold">${i.split('·')[0].trim()}</div>
        <div style="font-size:11px;color:var(--muted)">${i.split('·').slice(1).join('·').trim()}</div></div>
        ${low?'<span class="badge badge-danger">⚠ Bajo</span>':'<span class="badge badge-ok">OK</span>'}
      </div>`;
    }).join('');
}

// ── Ventas carrito ────────────────────────────────────────
const cart=[];

function addToCart(screenId){
  const prodSel=document.getElementById('prod-sel-'+screenId);
  const cantInp=document.getElementById('cant-inp-'+screenId);
  if(!prodSel||!cantInp) return;
  const opt=prodSel.options[prodSel.selectedIndex];
  if(!prodSel.value||!opt){showToast('Selecciona un producto primero','warn');return;}
  const prod={
    id:   opt.dataset.id   || prodSel.value,
    name: opt.dataset.name || opt.text,
    price:parseFloat(opt.dataset.price)||0,
    stock:parseFloat(opt.dataset.stock)||0,
    unidad:opt.dataset.unidad||'u',
  };
  if(!prod.id){showToast('Selecciona un producto primero','warn');return;}
  const cant=parseFloat(cantInp.value)||1;
  if(cant<=0){showToast('Cantidad inválida','warn');return;}
  if(cant>prod.stock){showToast(`Stock insuficiente — solo ${prod.stock} ${prod.unidad} disponibles`,'warn');return;}
  const existing=cart.find(c=>c.id===prod.id);
  if(existing){existing.cant+=cant;}
  else{cart.push({...prod,cant});}
  renderCart(screenId);
  cantInp.value=1;
  showToast(`✓ ${prod.name} agregado`);
}

function removeFromCart(id,screenId){
  const idx=cart.findIndex(c=>c.id===id);
  if(idx>-1) cart.splice(idx,1);
  renderCart(screenId);
}

function renderCart(screenId){
  const tbody=document.getElementById('cart-body-'+screenId);
  const totalEl=document.getElementById('cart-total-'+screenId);
  if(!tbody) return;
  if(cart.length===0){
    tbody.innerHTML='<tr><td colspan="5" style="text-align:center;color:var(--hint);padding:20px">Sin productos agregados</td></tr>';
    if(totalEl) totalEl.textContent='$0.00';
    return;
  }
  let total=0;
  tbody.innerHTML=cart.map(item=>{
    const sub=item.price*item.cant;
    total+=sub;
    return `<tr><td>${item.name}</td><td>${item.cant} kg</td><td>$${item.price.toFixed(2)}</td>
      <td><strong>$${sub.toFixed(2)}</strong></td>
      <td><button class="btn btn-xs btn-danger" onclick="removeFromCart('${item.id}','${screenId}')">✕</button></td></tr>`;
  }).join('');
  if(totalEl) totalEl.textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
}

function confirmarVenta(screenId){
  if(cart.length===0){showToast('Agrega productos primero','warn');return;}
  const total=cart.reduce((s,c)=>s+c.price*c.cant,0);
  cart.length=0;
  renderCart(screenId);
  openModal('modal-venta-ok');
  document.getElementById('venta-ok-total').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
}

// ── Toast ─────────────────────────────────────────────────
function showToast(msg, type='ok'){
  const t=document.createElement('div');
  t.className='toast-msg';
  t.textContent=msg;
  t.style.cssText=`position:fixed;bottom:24px;right:24px;z-index:9999;background:${type==='warn'?'#D68910':'#2563EB'};color:#fff;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:500;box-shadow:0 4px 16px rgba(0,0,0,.2);animation:slideUp .25s ease;pointer-events:none`;
  document.body.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='opacity .3s';setTimeout(()=>t.remove(),300)},2500);
}

// ── Confirm dialogs ───────────────────────────────────────
function confirmDelete(msg, onConfirm){
  document.getElementById('confirm-msg').textContent=msg;
  document.getElementById('confirm-ok').onclick=()=>{closeModal('modal-confirm');onConfirm();};
  openModal('modal-confirm');
}

// ── Alertas tabs ──────────────────────────────────────────
function setAlertTab(tab){
  document.querySelectorAll('.alert-tab').forEach(t=>t.classList.remove('active'));
  tab.classList.add('active');
  const type=tab.dataset.tab;
  document.querySelectorAll('.alert-section').forEach(s=>{
    s.style.display=(type==='todas'||s.dataset.type===type||!type)?'block':'none';
  });
}

// ── Init ──────────────────────────────────────────────────
// ── Ticket preview en tiempo real ────────────────────────
function updateTicketPreview(){
  const tienda=document.getElementById('venta-tienda');
  const cliente=document.getElementById('venta-cliente');
  const tName=tienda?tienda.value:'Sucursal 1 — Santa María Texmelucan';
  const cName=cliente?cliente.options[cliente.selectedIndex].text:'—';
  const isEmp=(document.getElementById('emp-nav')&&document.getElementById('emp-nav').style.display!=='none');
  const atendio=isEmp?'Ana Ramírez':'Hernán Meneses';
  
  const now=new Date();
  const fecha=`${String(now.getDate()).padStart(2,'0')}/${String(now.getMonth()+1).padStart(2,'0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
  
  const tp=document.getElementById('ticket-preview');
  if(!tp) return;
  
  // Build product lines
  const cartLines=cart.map(item=>`
    <div style="display:flex;justify-content:space-between;margin-bottom:3px">
      <span style="flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">${item.name.replace('Hilo ','').replace(' 500g','').replace(' 250g','').replace(' 100g','')}</span>
      <span style="margin-left:8px;white-space:nowrap">$${(item.price*item.cant).toFixed(2)}</span>
    </div>
    <div style="font-size:10px;color:#888;margin-bottom:4px">${item.cant} kg × $${item.price.toFixed(2)}</div>`).join('');
  
  const total=cart.reduce((s,c)=>s+c.price*c.cant,0);
  const folioNum=String(43+Math.floor(Math.random()*3)).padStart(4,'0');
  
  tp.innerHTML=`
    <div style="text-align:center;margin-bottom:10px;padding-bottom:10px;border-bottom:1px dashed #ccc">
      <div style="font-weight:700;font-size:13px">HLazcano — Prendas de Punto</div>
      <div style="font-size:10px;color:#666">${tName}</div>
      <div style="font-size:10px;color:#666">Tel: 222-555-0000</div>
    </div>
    <div style="font-size:10px;margin-bottom:10px;line-height:1.8">
      <div>Folio: <strong>#${folioNum}</strong></div>
      <div>Fecha: <strong>${fecha}</strong></div>
      <div>Cliente: <strong>${cName==='Sin cliente (venta directa)'?'—':cName}</strong></div>
      <div>Atendió: <strong>${atendio}</strong></div>
    </div>
    <div style="border-top:1px dashed #ccc;padding-top:8px;min-height:30px">
      ${cart.length===0?'<div style="color:#aaa;font-size:11px;text-align:center">Sin productos</div>':cartLines}
    </div>
    ${cart.length>0?`
    <div style="border-top:1px dashed #ccc;margin-top:8px;padding-top:8px">
      <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:4px"><span>Subtotal</span><span>$${total.toFixed(2)}</span></div>
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:13px"><span>TOTAL</span><span>$${total.toLocaleString('es-MX',{minimumFractionDigits:2})}</span></div>
    </div>`:''}`;
}

async function registrarVentaFinal(){
  if(cart.length===0){showToast('Agrega productos primero','warn');return;}
  const tienda=document.getElementById('venta-tienda');
  const clienteSel=document.getElementById('venta-cliente');
  const descuento=document.getElementById('venta-descuento');
  const notas=document.getElementById('venta-notas');
  const cName=clienteSel&&clienteSel.value?clienteSel.options[clienteSel.selectedIndex].text:'—';
  const cId=clienteSel?clienteSel.options[clienteSel.selectedIndex]?.dataset?.id||'':'' ;
  const total=cart.reduce((s,c)=>s+c.price*c.cant,0);

  // Build items array for backend
  const items = cart.map(item=>({
    id_producto: item.id,
    cantidad:    item.cant,
    precio_unit: item.price,
  }));

  const fd=new FormData();
  fd.append('id_cliente', cId||'');
  fd.append('descuento',  descuento?descuento.value||0:0);
  fd.append('notas',      notas?notas.value||'':'');
  fd.append('id_tienda',  tienda.value);
  fd.append('items',      JSON.stringify(items));

  try {
    const res=await fetch('index.php?action=venta_registrar',{
      method:'POST',
      credentials:'same-origin',
      headers:{
        'X-Requested-With':'XMLHttpRequest',
        'Accept':'application/json'
      },
      body:fd,
    });
    let data;
    try {
      data = await res.json();
    } catch (e) {
      const text = await res.text();
      showToast('❌ Respuesta inválida del servidor.', 'warn');
      console.error('venta_registrar response invalid JSON:', text);
      return;
    }
    if (!res.ok || !data.ok) {
      showToast('❌ '+(data.msg||data.error||'Error al registrar'), 'warn');
      return;
    }

    const folio=String(data.id_venta||'???').padStart(4,'0');
    const tName=tienda?tienda.options[tienda.selectedIndex]?.text||'Sucursal 1':'Sucursal 1';
    const now=new Date();
    const fecha=`${String(now.getDate()).padStart(2,'0')}/${String(now.getMonth()+1).padStart(2,'0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

    // Show confirmation screen
    clearScreens();
    const sc=document.getElementById('screen-venta-confirmada');
    if(sc){
      sc.classList.add('active');
      sc.querySelector('.venta-folio').textContent='Venta registrada exitosamente — Folio #'+folio+' · $'+total.toLocaleString('es-MX',{minimumFractionDigits:2})+' · '+fecha;
      sc.querySelector('.venta-cliente-info').textContent='Cliente: '+cName+' · '+tName+' · '+fecha;
      const tbody=sc.querySelector('.venta-items-body');
      tbody.innerHTML=cart.map(item=>`<tr>
        <td>${item.name}</td><td>${item.cant} ${item.unidad||'u'}</td><td>$${item.price.toFixed(2)}</td>
        <td><strong>$${(item.price*item.cant).toFixed(2)}</strong></td><td>—</td>
      </tr>`).join('');
      sc.querySelector('.venta-subtotal').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
      sc.querySelector('.venta-total-main').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
      const ticketSide=sc.querySelector('.ticket-side');
      if(ticketSide){
        ticketSide.innerHTML=`
          <div style="text-align:center;margin-bottom:10px;padding-bottom:8px;border-bottom:1px dashed #ccc">
            <div style="font-weight:700">HLazcano — Prendas de Punto</div>
            <div style="font-size:10px;color:#777">${tName}</div>
          </div>
          <div style="font-size:10px;margin-bottom:8px;line-height:1.9">
            <div>Folio: <strong>#${folio}</strong></div>
            <div>Fecha: <strong>${fecha}</strong></div>
            <div>Cliente: <strong>${cName==='—'?'—':cName}</strong></div>
          </div>
          <div style="border-top:1px dashed #ccc;padding-top:8px">
            ${cart.map(item=>`<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:2px"><span>${item.name.replace('Hilo ','')}</span><span>$${(item.price*item.cant).toFixed(2)}</span></div><div style="font-size:10px;color:#aaa;margin-bottom:5px">${item.cant} ${item.unidad||'u'}</div>`).join('')}
          </div>
          <div style="border-top:1px dashed #ccc;margin-top:8px;padding-top:8px">
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:14px"><span>TOTAL</span><span>$${total.toLocaleString('es-MX',{minimumFractionDigits:2})}</span></div>
          </div>`;
      }
      cart.length=0;
      renderCart('ventas');
      updateTicketPreview();
    }
    showToast('✅ Venta #'+folio+' registrada');
    document.getElementById('page-title').textContent='Confirmar venta';
    const contentArea=document.querySelector('.content');
    if(contentArea) contentArea.scrollTop=0;
    window.scrollTo(0,0);
  } catch(e){
    showToast('❌ Error de conexión','warn');
  }
}

// ── Catálogos tabs ────────────────────────────────────────
function setCatTab(el, panelId){
  const ca=document.querySelector('.content');if(ca) ca.scrollTop=0;
  document.querySelectorAll('.cat-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.cat-panel').forEach(p=>p.style.display='none');
  const panel=document.getElementById('cat-'+panelId);
  if(panel) panel.style.display='block';
  const titles={'tipos':'Catálogos — Tipos de hilo','colores':'Catálogos — Colores','proveedores':'Catálogos — Proveedores','anaqueles':'Catálogos — Anaqueles','unidades':'Catálogos — Unidades'};
  document.getElementById('page-title').textContent=titles[panelId]||'Catálogos';
}

// ── Reportes sub-panels ───────────────────────────────────
function showRepContent(panelId){
  document.querySelectorAll('.rep-panel').forEach(p=>p.style.display='none');
  const p=document.getElementById(panelId);
  if(p) p.style.display='block';
}

// ── Usuarios: dar de baja / reactivar ─────────────────────
function darDeBaja(nombre, btn){
  confirmDelete('¿Dar de baja a '+nombre+'? El usuario no podrá iniciar sesión. Puedes reactivar su cuenta en cualquier momento.', ()=>{
    const row=btn.closest('tr');
    const statusCell=row.querySelector('.badge-ok');
    if(statusCell){
      statusCell.className='badge badge-gray';
      statusCell.textContent='Inactivo';
    }
    btn.textContent='✅ Reactivar';
    btn.style.background='var(--accentbg)';
    btn.style.borderColor='var(--accent2)';
    btn.style.color='var(--accent)';
    btn.onclick=()=>reactivarUsuario(nombre,btn);
    showToast('Usuario '+nombre+' dado de baja');
  });
}

function reactivarUsuario(nombre, btn){
  const row=btn.closest('tr');
  const statusCell=row.querySelector('.badge-gray');
  if(statusCell){
    statusCell.className='badge badge-ok';
    statusCell.textContent='Activo';
  }
  btn.textContent='⛔ Baja';
  btn.style.background='';
  btn.style.borderColor='';
  btn.style.color='';
  btn.onclick=()=>darDeBaja(nombre,btn);
  showToast('✅ '+nombre+' reactivado');
}

function updateColorPreview(){
  const nombre=document.getElementById('nuevo-color-nombre');
  const hex=document.getElementById('nuevo-color-hex');
  const swatch=document.getElementById('color-preview-swatch');
  const nameEl=document.getElementById('color-preview-name');
  if(nombre&&hex&&swatch&&nameEl){
    const h=hex.value.trim();
    if(h.match(/^#[0-9A-Fa-f]{6}$/)){swatch.style.background=h;}
    nameEl.textContent=nombre.value||'Vista previa';
  }
}
function setQuickColor(hex,nombre){
  const hn=document.getElementById('nuevo-color-nombre');
  const hh=document.getElementById('nuevo-color-hex');
  if(hn) hn.value=nombre;
  if(hh) hh.value=hex;
  updateColorPreview();
}

// Add venta-confirmada to pages map
pages['venta-confirmada']='Confirmar venta';

document.addEventListener('keydown',e=>{
  if(e.key==='Escape'){
    document.querySelectorAll('.modal-overlay.open').forEach(m=>{
    m.classList.remove('open');
  });
  document.body.style.overflow='';
  }
});