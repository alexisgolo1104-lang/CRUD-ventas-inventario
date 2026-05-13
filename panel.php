<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HLazcano · Sistema de Inventario</title>
  <style>
/* ── Reset & Variables ─────────────────────────────────── */
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#F4F6FA;--surface:#FFFFFF;--surf2:#EDF0F7;--surf3:#E2E6F0;
  --border:#D8DCE8;--border2:#C4C9D8;
  --text:#12172B;--muted:#5A6075;--hint:#A0A8BE;
  --accent:#C0392B;--accent2:#E74C3C;--accentbg:#FDECEA;--accentlt:#FBBBB5;
  --danger:#C0392B;--dangbg:#FDECEA;--dangli:#FCA5A5;
  --warn:#D68910;--warnbg:#FEF9E7;
  --info:#1A5276;--infobg:#EBF5FB;
  --blue:#1E3A8A;--blue2:#2563EB;--bluebg:#EFF6FF;--bluebd:#BFDBFE;
  --sidebar:#080E1A;--side2:#111827;--side3:#1B2435;--side4:#243044;
  --sidetext:#E2E8F0;--sidemut:#64748B;--sideact:#94A3B8;
  --radius:10px;--radius-lg:14px;--radius-xl:20px;
  --shadow:0 1px 3px rgba(0,0,0,.08),0 4px 16px rgba(0,0,0,.06);
  --shadow-lg:0 4px 24px rgba(0,0,0,.14),0 1px 4px rgba(0,0,0,.06);
  --transition:.18s cubic-bezier(.4,0,.2,1);
}
html,body{height:100%;font-family:'Segoe UI',Arial,sans-serif;font-size:14px;color:var(--text);background:var(--bg);line-height:1.5}
h1,h2,h3,h4{font-family:'Segoe UI',Arial,sans-serif;font-weight:600}
button{font-family:'Segoe UI',Arial,sans-serif;cursor:pointer}
input,select,textarea{font-family:'Segoe UI',Arial,sans-serif}

/* ── Layout ─────────────────────────────────────────────── */
.app{display:flex;height:100vh;overflow:hidden}
.sidebar{width:226px;min-width:226px;background:var(--sidebar);display:flex;flex-direction:column;height:100%;overflow:hidden;position:relative;transition:width var(--transition)}
.main-area{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0}
.content{flex:1;overflow-y:auto;padding:24px;position:relative;scroll-behavior:smooth}

/* ── Sidebar ─────────────────────────────────────────────── */
.sb-logo{padding:20px 16px 16px;border-bottom:1px solid var(--side3);display:flex;align-items:center;gap:11px}
.sb-logo-mark{width:34px;height:34px;background:#C0392B;border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',Arial,sans-serif;font-weight:800;font-size:14px;color:#fff;flex-shrink:0;letter-spacing:-.5px}
.sb-logo-text{font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:13px;color:#fff;line-height:1.2}
.sb-logo-sub{font-size:10px;color:var(--sidemut);font-weight:400}
.sb-store{margin:10px 10px 0;background:var(--side2);border:1px solid var(--side3);border-radius:var(--radius);padding:8px 10px;cursor:pointer}
.sb-store label{display:block;font-size:9px;color:var(--sidemut);text-transform:uppercase;letter-spacing:.6px;margin-bottom:3px}
.sb-store select{width:100%;background:transparent;border:none;color:var(--sidetext);font-size:11.5px;font-family:'Segoe UI',Arial,sans-serif;cursor:pointer;outline:none;padding:0}
.sb-store select option{background:#1a1714;color:var(--sidetext)}
.sb-section{padding:10px 14px 3px;font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:var(--sidemut);font-weight:500}
.sb-item{display:flex;align-items:center;gap:9px;padding:8px 12px;margin:1px 8px;border-radius:8px;cursor:pointer;color:var(--sideact);font-size:12.5px;transition:all var(--transition);position:relative;user-select:none}
.sb-item:hover{background:var(--side2);color:var(--sidetext)}
.sb-item.active{background:#2563EB;color:#fff}
.sb-item .icon{font-size:14px;width:18px;text-align:center;flex-shrink:0}
.sb-item .badge{margin-left:auto;background:#C0392B;color:#fff;font-size:9px;border-radius:10px;padding:1px 6px;font-weight:600;line-height:1.4}
.sb-item.locked{opacity:.45;cursor:not-allowed}
.sb-item.locked:hover{background:transparent;color:var(--sideact)}
.sb-lock{margin-left:auto;font-size:10px;opacity:.5}
.sb-footer{margin-top:auto;padding:12px;border-top:1px solid var(--side3)}
.sb-user{display:flex;align-items:center;gap:9px}
.sb-avatar{width:32px;height:32px;border-radius:50%;background:#C0392B;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#fff;flex-shrink:0}
.sb-info{flex:1;min-width:0}
.sb-name{font-size:12px;color:var(--sidetext);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sb-role{font-size:10px;color:var(--sidemut)}
.sb-logout{padding:4px 8px;border-radius:6px;border:1px solid var(--side3);background:transparent;color:var(--sidemut);font-size:11px;transition:all var(--transition)}
.sb-logout:hover{background:var(--side3);color:var(--sidetext)}
.emp-role-tag{font-size:9px;background:#7B4F0022;border:1px solid #D6840044;color:#D68400;border-radius:4px;padding:1px 5px;margin-left:4px}

/* ── Topbar ──────────────────────────────────────────────── */
.topbar{height:54px;background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 24px;gap:12px;position:sticky;top:0;z-index:10;flex-shrink:0}
.page-title{font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:15px;flex:1}
.topbar-store{font-size:11.5px;color:var(--muted);background:var(--surf2);border:1px solid var(--border);padding:5px 11px;border-radius:7px}
.topbar-notif{position:relative;width:34px;height:34px;border-radius:8px;border:1px solid var(--border);background:var(--surface);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:15px;transition:background var(--transition)}
.topbar-notif:hover{background:var(--surf2)}
.notif-dot{position:absolute;top:5px;right:5px;width:8px;height:8px;background:var(--danger);border-radius:50%;border:2px solid #fff}
.topbar-user{display:flex;align-items:center;gap:7px;padding:5px 11px;border:1px solid var(--border);border-radius:8px;background:var(--surface);cursor:pointer;transition:background var(--transition)}
.topbar-user:hover{background:var(--surf2)}
.tu-avatar{width:24px;height:24px;border-radius:50%;background:#C0392B;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff}
.tu-name{font-size:12px;font-weight:500}
.emp-badge{font-size:9px;background:#FFF0D6;border:1px solid #D6840066;color:#7B4F00;padding:2px 6px;border-radius:5px;font-weight:600}

/* ── Screens ─────────────────────────────────────────────── */
.screen{display:none;animation:fadeUp .2s ease}
.screen.active{display:block}
@keyframes fadeUp{from{opacity:0}to{opacity:1}}

/* ── Common Components ───────────────────────────────────── */
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:20px}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:18px;box-shadow:var(--shadow)}
.card-sm{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:14px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:16px 18px;box-shadow:var(--shadow)}
.stat-num{font-family:'Segoe UI',Arial,sans-serif;font-size:28px;font-weight:700;line-height:1;margin-bottom:4px}
.stat-lbl{font-size:12px;color:var(--muted)}
.stat-delta{font-size:11px;margin-top:5px}
.stat-delta.up{color:#2563EB}
.stat-delta.dn{color:var(--danger)}
.card-title{font-family:'Segoe UI',Arial,sans-serif;font-weight:600;font-size:13px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between}
.section-title{font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:16px;margin-bottom:16px}

/* ── Buttons ─────────────────────────────────────────────── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid var(--border);background:var(--surface);color:var(--text);transition:all var(--transition);white-space:nowrap;line-height:1}
.btn:hover{background:var(--surf2);border-color:var(--border2)}
.btn:active{transform:scale(.98)}
.btn-primary{background:#2563EB;color:#fff;border-color:#2563EB}
.btn-primary:hover{background:#1D4ED8}
.btn-danger{background:var(--dangbg);color:var(--danger);border-color:#f5c6c2}
.btn-sm{padding:5px 10px;font-size:12px;border-radius:7px}
.btn-xs{padding:3px 8px;font-size:11px;border-radius:6px}
.btn-icon{width:30px;height:30px;padding:0;justify-content:center;border-radius:7px}

/* ── Form ────────────────────────────────────────────────── */
.form-row{display:grid;gap:14px;margin-bottom:14px}
.form-2{grid-template-columns:1fr 1fr}
.form-3{grid-template-columns:1fr 1fr 1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-label{font-size:11.5px;font-weight:500;color:var(--muted)}
.form-control{padding:8px 11px;border:1px solid var(--border);border-radius:8px;font-size:13px;background:var(--surface);color:var(--text);outline:none;transition:border var(--transition);width:100%}
.form-control:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
select.form-control{cursor:pointer}
textarea.form-control{resize:vertical;min-height:72px}
.form-hint{font-size:11px;color:var(--hint)}
.form-section{font-size:11px;text-transform:uppercase;letter-spacing:.6px;font-weight:600;color:var(--muted);margin:16px 0 8px;padding-top:10px;border-top:1px solid var(--border)}

/* ── Table ───────────────────────────────────────────────── */
.table-wrap{border-radius:var(--radius);border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow)}
table{width:100%;border-collapse:collapse;font-size:12.5px}
th{background:var(--surf2);padding:9px 13px;text-align:left;font-weight:600;font-size:10.5px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);border-bottom:1px solid var(--border);white-space:nowrap}
td{padding:10px 13px;border-bottom:1px solid var(--border);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:var(--surf2)}
.tbl-actions{display:flex;gap:5px;align-items:center}

/* ── Sub-tabs (ventas, etc.) ─────────────────────────────── */
.sub-tab{background:none;border:none;border-bottom:2px solid transparent;padding:8px 16px;font-size:13px;font-weight:500;color:var(--muted);cursor:pointer;margin-bottom:-1px;transition:color .15s,border-color .15s}
.sub-tab:hover{color:var(--text)}
.sub-tab.active{color:#2563EB;border-bottom-color:#2563EB}

/* ── Badges ──────────────────────────────────────────────── */
.badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:500;white-space:nowrap}
.badge-ok{background:#EFF6FF;color:#1E3A8A}
.badge-warn{background:var(--warnbg);color:#7D6608}
.badge-danger{background:var(--dangbg);color:var(--danger)}
.badge-info{background:var(--infobg);color:var(--info)}
.badge-gray{background:var(--surf2);color:var(--muted)}
.badge-superadmin{background:var(--infobg);color:var(--info)}
.badge-admin{background:var(--warnbg);color:var(--warn)}
.badge-empleado{background:var(--surf2);color:var(--muted)}

/* ── Filter Bar ──────────────────────────────────────────── */
.filter-bar{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:12px 14px;display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;box-shadow:var(--shadow)}
.filter-bar .form-control{flex:1;min-width:120px}

/* ── Notice ──────────────────────────────────────────────── */
.notice{border-radius:var(--radius);padding:10px 14px;font-size:12.5px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.notice-info{background:var(--infobg);border:1px solid #B8D4E8;color:var(--info)}
.notice-warn{background:var(--warnbg);border:1px solid #F5CE70;color:var(--warn)}
.notice-danger{background:var(--dangbg);border:1px solid #FCA5A5;color:var(--danger)}
.notice-success{background:#EFF6FF;border:1px solid #BFDBFE;color:#1E3A8A}

/* ── Modal ───────────────────────────────────────────────── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(26,23,20,.5);z-index:200;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(2px)}
.modal-overlay.open{display:flex;animation:fadeIn .15s ease}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.modal{background:var(--surface);border-radius:var(--radius-xl);width:100%;max-width:580px;max-height:88vh;overflow-y:auto;box-shadow:var(--shadow-lg)}
.modal-header{padding:20px 24px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:var(--surface);border-radius:var(--radius-xl) var(--radius-xl) 0 0;z-index:1}
.modal-title{font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:16px}
.modal-body{padding:20px 24px}
.modal-footer{padding:16px 24px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end;background:var(--surf2);border-radius:0 0 var(--radius-xl) var(--radius-xl)}
.modal-close{width:30px;height:30px;border-radius:7px;border:1px solid var(--border);background:var(--surface);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--muted);transition:all var(--transition)}
.modal-close:hover{background:var(--surf2);color:var(--text)}

/* ── Bar Chart ───────────────────────────────────────────── */
.bar-chart{display:flex;align-items:flex-end;gap:5px;height:90px;padding-top:8px}
.bar-chart .bar{flex:1;border-radius:4px 4px 0 0;background:#60A5FA;min-height:6px;transition:opacity .2s}
.bar-chart .bar:hover{opacity:.8}
.bar-chart .bar.hi{background:#2563EB}

/* ── Color Pill ──────────────────────────────────────────── */
.color-pill{display:inline-flex;align-items:center;gap:5px;padding:2px 7px;border-radius:20px;font-size:11px;border:1px solid var(--border);background:var(--surface)}
.color-swatch{width:10px;height:10px;border-radius:50%;flex-shrink:0}

/* ── Alert Item ──────────────────────────────────────────── */
.alert-item{display:flex;align-items:center;gap:11px;padding:12px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface);margin-bottom:8px;transition:all var(--transition)}
.alert-item.danger{border-color:#fca5a5;background:var(--dangbg)}
.alert-item.warn{border-color:#f5ce70;background:var(--warnbg)}
.alert-item.done{background:var(--surf2);opacity:.7}
.alert-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}

/* ── Login ───────────────────────────────────────────────── */
.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;position:relative;background:linear-gradient(135deg,#080E1A 0%,#1B2435 50%,#C0392B 100%) center/cover no-repeat}.login-wrap::before{content:"";position:absolute;inset:0;background:rgba(8,12,25,.62);z-index:0}.login-wrap>*{position:relative;z-index:1}
.login-card{background:var(--surface);border-radius:var(--radius-xl);padding:36px;width:100%;max-width:400px;box-shadow:var(--shadow-lg)}
.login-logo{text-align:center;margin-bottom:28px}
.login-icon{width:58px;height:58px;background:#C0392B;border-radius:15px;margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',Arial,sans-serif;font-weight:800;font-size:22px;color:#fff;letter-spacing:-1px;box-shadow:0 6px 20px rgba(192,57,43,.55)}
.login-title{font-family:'Segoe UI',Arial,sans-serif;font-weight:800;font-size:22px;margin-bottom:4px}
.login-sub{font-size:13px;color:var(--muted)}
.role-tabs{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:22px}
.role-tab{padding:11px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;text-align:center;transition:all var(--transition);background:var(--surface)}
.role-tab:hover{border-color:#93C5FD;background:#EFF6FF}
.role-tab.active{border-color:#2563EB;background:#EFF6FF}
.role-tab-icon{font-size:20px;margin-bottom:5px}
.role-tab-label{font-size:12.5px;font-weight:500;color:var(--muted)}
.role-tab.active .role-tab-label{color:#2563EB}

/* ── Shelf Grid (localizador) ────────────────────────────── */
.shelf-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.shelf-cell{height:72px;border-radius:10px;border:2px solid var(--border);background:var(--surf2);display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all var(--transition);font-size:12px;font-weight:600;color:var(--muted);gap:3px}
.shelf-cell:hover{border-color:#2563EB;color:#2563EB;background:#EFF6FF}
.shelf-cell.found{border-color:var(--accent);background:var(--accent);color:#fff}
.shelf-cell.has-items{border-color:#93C5FD;background:#EFF6FF;color:#2563EB}

/* ── Report tabs ─────────────────────────────────────────── */
.rep-tabs{display:flex;gap:6px;margin-bottom:18px;flex-wrap:wrap}
.rep-tab{padding:7px 14px;border-radius:8px;border:1px solid var(--border);background:var(--surface);font-size:12.5px;font-weight:500;cursor:pointer;transition:all var(--transition)}
.rep-tab:hover{background:var(--surf2)}
.rep-tab.active{background:#2563EB;color:#fff;border-color:#2563EB}

/* ── Carrito (ventas) ────────────────────────────────────── */
.cart-total-box{background:var(--surf2);border-radius:10px;padding:14px 16px;margin:12px 0}
.cart-total-row{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px}
.cart-total-row:last-child{margin-bottom:0;border-top:1px solid var(--border);padding-top:10px;margin-top:10px}
.cart-total-label{font-size:13px;color:var(--muted)}
.cart-total-val{font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:13px}
.cart-total-main{font-size:24px;color:#2563EB}

/* ── Misc ────────────────────────────────────────────────── */
.divider{border:none;border-top:1px solid var(--border);margin:16px 0}
.flex{display:flex}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-between{justify-content:space-between}
.gap-8{gap:8px}.gap-12{gap:12px}.gap-16{gap:16px}
.mt-4{margin-top:4px}.mt-8{margin-top:8px}.mt-16{margin-top:16px}
.mb-8{margin-bottom:8px}.mb-12{margin-bottom:12px}.mb-16{margin-bottom:16px}
.text-sm{font-size:12px}.text-muted{color:var(--muted)}.text-danger{color:var(--danger)}
.text-accent{color:#2563EB}.font-bold{font-weight:600}.w-full{width:100%}
.empty-state{text-align:center;padding:60px 20px;color:var(--hint)}
.empty-state .icon{font-size:48px;margin-bottom:14px}
.empty-state h3{font-family:'Segoe UI',Arial,sans-serif;font-size:16px;color:var(--muted);margin-bottom:6px}

/* Cat tabs */
.cat-tab{padding:9px 18px;border:none;background:transparent;font-size:13px;font-weight:500;cursor:pointer;border-bottom:3px solid transparent;color:var(--muted);transition:all .15s;margin-bottom:-2px}
.cat-tab:hover{color:var(--text)}
.cat-tab.active{color:var(--accent);border-bottom-color:var(--accent);font-weight:600}
.cat-panel{}
.rep-panel{}
@keyframes slideUp{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
.shelf-cell.found{background:#EFF6FF!important;border-color:#2563EB!important;color:#2563EB!important;font-weight:700;box-shadow:0 0 0 3px rgba(37,99,235,.2)}
.alert-tab{padding:6px 14px;border-radius:20px;border:1px solid var(--border);background:var(--surface);font-size:12px;font-weight:500;cursor:pointer;transition:all .15s}
.alert-tab.active{background:#2563EB;color:#fff;border-color:#2563EB}
.alert-section{margin-bottom:20px}
.cat-tab{padding:9px 18px;border:none;background:transparent;font-size:13px;font-weight:500;cursor:pointer;border-bottom:3px solid transparent;color:var(--muted);transition:all .15s;margin-bottom:-2px}
.cat-tab:hover{color:var(--text)}
.cat-tab.active{color:#2563EB;border-bottom-color:#2563EB;font-weight:600}
.cat-panel{}
.rep-panel{}  </style>
</head>
<body>
<div id="login-page" class="login-wrap">
  <div class="login-card" style="position:relative;z-index:2">
    <div class="login-logo">
      <div class="login-icon">HL</div>
      <div class="login-title">HLazcano</div>
      <div class="login-sub">Sistema de Gestión de Inventario</div>
    </div>
    <div class="form-group mb-12">
      <label class="form-label">Usuario o correo</label>
      <input type="text" class="form-control" id="login-usuario" placeholder="Ingresa tu usuario o correo">
    </div>
    <div class="form-group mb-16">
      <label class="form-label">Contraseña</label>
      <div style="position:relative;">
        <input type="password" class="form-control" id="login-password" placeholder="Contraseña" style="padding-right:40px;">
        <button type="button" onclick="togglePassword('login-password')" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;color:var(--muted);">👁️‍🗨️</button>
      </div>
    </div>
    <button class="btn btn-primary w-full mb-12" style="justify-content:center;padding:11px 0;font-size:14px" onclick="doLogin()">Ingresar al sistema</button>
    <button class="btn btn-secondary w-full mb-8" onclick="openModal('modal-registro')">Crear cuenta nueva</button>
    <button class="btn btn-link w-full" onclick="openModal('modal-recuperar')">¿Olvidaste tu contraseña?</button>
    <p style="text-align:center;margin-top:14px;font-size:11px;color:var(--hint)">Solo personal autorizado · HLazcano © 2025</p>
  </div>
</div>

<div class="modal-overlay" id="modal-registro" onclick="closeOut(event,'modal-registro')">
  <div class="modal" style="max-width:520px">
    <div class="modal-header">
      <div class="modal-title">Crear cuenta nueva</div>
      <button class="modal-close" onclick="closeModal('modal-registro')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="registro-nombre" placeholder="Ej. Jorge Pérez">
      </div>
      <div class="form-group">
        <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="registro-correo" placeholder="ejemplo@empresa.com">
      </div>
      <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="registro-contrasena" placeholder="Mínimo 6 caracteres">
      </div>
      <div class="form-group">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" class="form-control" id="registro-confirmar" placeholder="Repite la contraseña">
      </div>
      <div class="form-group">
        <label class="form-label">Rol</label>
        <select class="form-control" id="registro-rol">
          <option value="empleado">Empleado</option>
          <option value="admin">Administrador</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Sucursal</label>
        <select class="form-control" id="registro-tienda">
          <option value="1">Sucursal 1</option>
          <option value="2">Sucursal 2</option>
        </select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-registro')">Cancelar</button>
      <button class="btn btn-primary" onclick="doRegistro()">Crear cuenta</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal-recuperar" onclick="closeOut(event,'modal-recuperar')">
  <div class="modal" style="max-width:480px">
    <div class="modal-header">
      <div class="modal-title">Recuperar contraseña</div>
      <button class="modal-close" onclick="closeModal('modal-recuperar')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Correo registrado</label>
        <input type="email" class="form-control" id="recuperar-correo" placeholder="ejemplo@empresa.com">
      </div>
      <div id="recuperar-info" class="notice notice-info" style="display:none;white-space:pre-wrap;"></div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-recuperar')">Cerrar</button>
      <button class="btn btn-primary" onclick="doRecuperar()">Enviar recuperación</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════ -->
<!-- APP                                                      --><div id="app" class="app" style="display:none">

  <!-- SIDEBAR -->
  <nav class="sidebar" id="sidebar">
    <div class="sb-logo">
      <div class="sb-logo-mark">HL</div>
      <div>
        <div class="sb-logo-text">HLazcano</div>
        <div class="sb-logo-sub" id="sb-version">Inventario v3.0</div>
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
    <nav id="admin-nav">
      <div class="sb-section">Principal</div>
      <div class="sb-item active" onclick="go('dashboard',this)"><span class="icon">◉</span>Dashboard</div>
      <div class="sb-section">Operación</div>
      <div class="sb-item" onclick="go('inventario',this)"><span class="icon">📦</span>Inventario</div>
      <div class="sb-item" onclick="go('ventas',this)"><span class="icon">🛒</span>Ventas</div>
      <div class="sb-item" onclick="go('compras',this)"><span class="icon">📥</span>Compras</div>
      <div class="sb-item" onclick="go('clientes',this)"><span class="icon">👥</span>Clientes</div>
      <div class="sb-item" onclick="go('alertas',this)"><span class="icon">🔔</span>Alertas<span class="badge">5</span></div>
      <div class="sb-item" onclick="go('localizador',this)"><span class="icon">🗺️</span>Localizador</div>
      <div class="sb-section">Análisis</div>
      <div class="sb-item" onclick="go('reportes',this)"><span class="icon">📊</span>Reportes</div>
      <div class="sb-section">Configuración</div>
      <div class="sb-item" onclick="go('usuarios',this)"><span class="icon">👤</span>Usuarios</div>
      <div class="sb-item" onclick="go('catalogos',this)"><span class="icon">📋</span>Catálogos</div>
      <div class="sb-item" onclick="go('respaldo',this)"><span class="icon">💾</span>Respaldo</div>
    </nav>
    <nav id="emp-nav" style="display:none">
      <div class="sb-section">Mi trabajo</div>
      <div class="sb-item active" onclick="go('emp-dashboard',this)"><span class="icon">◉</span>Mi turno</div>
      <div class="sb-item" onclick="go('emp-ventas',this)"><span class="icon">🛒</span>Registrar venta</div>
      <div class="sb-item" onclick="go('emp-inventario',this)"><span class="icon">📦</span>Ver inventario</div>
      <div class="sb-item" onclick="go('clientes',this)"><span class="icon">👥</span>Clientes</div>
      <div class="sb-item" onclick="go('alertas',this)"><span class="icon">🔔</span>Alertas<span class="badge">5</span></div>
      <div class="sb-item" onclick="go('localizador',this)"><span class="icon">🗺️</span>Localizador</div>
      <div class="sb-section">Sin acceso</div>
      <div class="sb-item locked"><span class="icon">📊</span>Reportes<span class="sb-lock">🔒</span></div>
      <div class="sb-item locked"><span class="icon">📥</span>Compras<span class="sb-lock">🔒</span></div>
      <div class="sb-item locked"><span class="icon">📋</span>Catálogos<span class="sb-lock">🔒</span></div>
    </nav>
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

<!-- ══ DASHBOARD ══════════════════════════════════════════ --><div id="screen-dashboard" class="screen active">
  <div class="grid-4">
    <div class="stat-card"><div class="stat-num" style="color:#2563EB">$18,420</div><div class="stat-lbl">Ventas del mes</div><div class="stat-delta up">↑ 12% vs mes anterior</div></div>
    <div class="stat-card"><div class="stat-num">347</div><div class="stat-lbl">Productos registrados</div><div class="stat-delta up">↑ 8 nuevos este mes</div></div>
    <div class="stat-card"><div class="stat-num" style="color:var(--danger)">5</div><div class="stat-lbl">Alertas activas</div><div class="stat-delta dn">3 críticas · 2 mínimo</div></div>
    <div class="stat-card"><div class="stat-num">84</div><div class="stat-lbl">Clientes registrados</div><div class="stat-delta up">↑ 6 este mes</div></div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Ventas por día — Marzo 2025<button class="btn btn-sm" onclick="go('reportes',null)">Ver reporte →</button></div>
      <div class="bar-chart">
        <div class="bar" style="height:55%"></div><div class="bar" style="height:72%"></div><div class="bar" style="height:48%"></div>
        <div class="bar" style="height:85%"></div><div class="bar" style="height:63%"></div><div class="bar hi" style="height:91%"></div>
        <div class="bar" style="height:70%"></div><div class="bar" style="height:58%"></div><div class="bar" style="height:77%"></div>
        <div class="bar" style="height:82%"></div><div class="bar" style="height:66%"></div><div class="bar hi" style="height:45%"></div>
        <div class="bar" style="height:90%"></div><div class="bar" style="height:75%"></div>
      </div>
      <div class="flex justify-between mt-8 text-sm text-muted"><span>S1: $11,240</span><span>S2: $7,180</span></div>
    </div>
    <div class="card">
      <div class="card-title">Productos con alerta<button class="btn btn-sm" onclick="go('alertas',null)">Ver alertas →</button></div>
      <div class="alert-item danger"><div class="alert-dot" style="background:var(--danger)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Nylon Negro 1kg</div><div class="text-sm text-muted">Stock: 2 kg · Mín: 5 kg · S2</div></div><button class="btn btn-sm" onclick="go('compras',null)">🛒 Comprar</button></div>
      <div class="alert-item danger"><div class="alert-dot" style="background:var(--danger)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Elastano Café 50g</div><div class="text-sm text-muted">Stock: 0 kg · Sin stock · S1</div></div><button class="btn btn-sm" onclick="go('compras',null)">🛒 Comprar</button></div>
      <div class="alert-item warn"><div class="alert-dot" style="background:var(--warn)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Algodón Rojo 250g</div><div class="text-sm text-muted">Stock: 4 kg · Mín: 5 kg · S1</div></div><button class="btn btn-sm">Ver</button></div>
    </div>
  </div>
  <div class="card">
    <div class="card-title">Últimos movimientos</div>
    <div class="table-wrap">
      <table><thead><tr><th>Fecha</th><th>Tipo</th><th>Producto</th><th>Cantidad</th><th>Tienda</th><th>Usuario</th><th>Estado</th></tr></thead>
      <tbody>
        <tr><td>14/03/2025</td><td><span class="badge badge-ok">Venta</span></td><td>Hilo Acrílico Blanco 500g</td><td>3 kg</td><td>Sucursal 1</td><td>Ana R.</td><td><span class="badge badge-ok">OK</span></td></tr>
        <tr><td>14/03/2025</td><td><span class="badge badge-info">Compra</span></td><td>Hilo Nylon Negro 1kg</td><td>20 kg</td><td>Sucursal 2</td><td>Carlos M.</td><td><span class="badge badge-ok">OK</span></td></tr>
        <tr><td>13/03/2025</td><td><span class="badge badge-warn">Ajuste</span></td><td>Hilo Algodón Rojo 250g</td><td>−1 kg</td><td>Sucursal 1</td><td>Admin</td><td><span class="badge badge-warn">Ajuste</span></td></tr>
        <tr><td>13/03/2025</td><td><span class="badge badge-ok">Venta</span></td><td>Hilo Poliéster Azul 500g</td><td>5 kg</td><td>Sucursal 1</td><td>Ana R.</td><td><span class="badge badge-ok">OK</span></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ INVENTARIO ═════════════════════════════════════════ -->
<?php
  try {
    $prodModel    = new Producto();
    $productos_inv = $prodModel->getAll(0);
    $total_prods  = count($productos_inv);
  } catch(\Exception $e) {
    $productos_inv = [];
    $total_prods   = 0;
  }
?>
<div id="screen-inventario" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm" id="prod-contador"><?= $total_prods ?> producto<?= $total_prods !== 1 ? 's' : '' ?> registrado<?= $total_prods !== 1 ? 's' : '' ?></div>
    <div class="flex gap-8">
      <button class="btn btn-primary" onclick="nuevoProducto()">+ Nuevo producto</button>
      <button class="btn" onclick="exportarTabla('tbl-inventario')">⬇ Exportar</button>
    </div>
  </div>
  <div class="filter-bar">
    <input id="prod-buscar" class="form-control" placeholder="🔍 Buscar producto..." style="max-width:240px" oninput="filtrarProductos()">
    <select id="prod-tipo" class="form-control" style="max-width:170px" onchange="filtrarProductos()">
      <option value="">Todos los tipos</option>
      <?php foreach ($prodModel->getTipos() as $t): ?>
        <option value="<?= htmlspecialchars($t['nombre']) ?>"><?= htmlspecialchars($t['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <select id="prod-color" class="form-control" style="max-width:160px" onchange="filtrarProductos()">
      <option value="">Todos los colores</option>
      <?php foreach ($prodModel->getColores() as $c): ?>
        <option value="<?= htmlspecialchars($c['nombre']) ?>"><?= htmlspecialchars($c['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <select id="prod-tienda" class="form-control" style="max-width:160px" onchange="filtrarProductos()">
      <option value="">Todas las tiendas</option>
      <option value="1">Sucursal 1</option>
      <option value="2">Sucursal 2</option>
    </select>
    <select id="prod-stock-fil" class="form-control" style="max-width:140px" onchange="filtrarProductos()">
      <option value="">Todo el stock</option>
      <option value="bajo">⚠ Bajo stock</option>
      <option value="normal">✅ Normal</option>
    </select>
  </div>
  <div class="table-wrap">
    <table id="tbl-inventario">
      <thead><tr><th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th><th>Mín.</th><th>P.Compra</th><th>P.Venta</th><th>Tienda</th><th>Anaquel</th><th>Acciones</th></tr></thead>
      <tbody id="tbody-productos">
      <?php foreach ($productos_inv as $p):
        $stockBajo = (float)$p['stock_actual'] <= (float)$p['stock_minimo'];
        $hex       = htmlspecialchars($p['color_hex'] ?? '#ccc');
        $stockData = $stockBajo ? 'bajo' : 'normal';
      ?>
        <tr data-id="<?= $p['id_producto'] ?>"
            data-tipo="<?= htmlspecialchars($p['tipo_nombre'] ?? '') ?>"
            data-color="<?= htmlspecialchars($p['color_nombre'] ?? '') ?>"
            data-tienda="<?= $p['id_tienda'] ?>"
            data-stock="<?= $stockData ?>">
          <td><strong><?= htmlspecialchars($p['catalogo_nombre'] ?? '—') ?></strong><br>
              <span class="text-muted" style="font-size:11px"><?= htmlspecialchars($p['presentacion'] ?? '') ?></span></td>
          <td><?= htmlspecialchars($p['tipo_nombre'] ?? '—') ?></td>
          <td>
            <span class="color-pill">
              <span class="color-swatch" style="background:<?= $hex ?>;border:1px solid #ccc;width:12px;height:12px;border-radius:50%;display:inline-block"></span>
              <?= htmlspecialchars($p['color_nombre'] ?? '—') ?>
            </span>
          </td>
          <td>
            <span class="badge <?= $stockBajo ? 'badge-danger' : 'badge-ok' ?>">
              <?= number_format((float)$p['stock_actual'], 2) ?> <?= htmlspecialchars($p['unidad_codigo'] ?? '') ?>
            </span>
          </td>
          <td><?= number_format((float)$p['stock_minimo'], 2) ?> <?= htmlspecialchars($p['unidad_codigo'] ?? '') ?></td>
          <td>$<?= number_format((float)($p['precio_compra'] ?? 0), 2) ?></td>
          <td>$<?= number_format((float)($p['precio_venta']  ?? 0), 2) ?></td>
          <td>S<?= $p['id_tienda'] ?></td>
          <td><?= htmlspecialchars($p['anaquel_codigo'] ?? '—') ?></td>
          <td class="tbl-actions">
            <button class="btn btn-sm btn-icon" onclick="editarProducto(<?= $p['id_producto'] ?>)" title="Editar">✏️</button>
            <button class="btn btn-sm btn-icon btn-danger" onclick="confirmarEliminar(<?= $p['id_producto'] ?>,'producto')" title="Desactivar">🗑</button>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($productos_inv)): ?>
        <tr><td colspan="10" style="text-align:center;padding:24px;color:var(--muted)">No hay productos registrados.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ══ PRODUCTOS ══════════════════════════════════════════ -->
<?php include __DIR__.'/views/productos.php'; ?>

<!-- ══ VENTAS ═════════════════════════════════════════════ -->
<div id="screen-ventas" class="screen">
  <div style="display:grid;grid-template-columns:1fr 1fr 340px;gap:16px">
    <!-- Formulario -->
    <div class="card" style="grid-column:span 2">
      <div class="card-title">Nueva venta</div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Tienda</label>
          <select class="form-control" id="venta-tienda" onchange="updateTicketPreview()">
            <option>Sucursal 1 — Santa María Texmelucan</option>
            <option>Sucursal 2 — Por definir</option>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Cliente</label>
          <select class="form-control" id="venta-cliente" onchange="updateTicketPreview()">
            <option value="">Sin cliente (venta directa)</option>
          </select>
        </div>
      </div>
      <div class="form-group mb-12">
        <label class="form-label">Agregar producto del catálogo</label>
        <div class="form-group mb-8" style="margin-bottom:8px">
          <label class="form-label">Filtrar por material</label>
          <select class="form-control" id="filtro-material-ventas" onchange="filtrarMaterialesVentas()">
            <option value="">Todos los materiales</option>
            <option value="Acrílico">Acrílico</option>
            <option value="Algodón">Algodón</option>
            <option value="Nylon">Nylon</option>
            <option value="Poliéster">Poliéster</option>
            <option value="Lana">Lana</option>
            <option value="Mercerizado">Mercerizado</option>
            <option value="Elastano">Elastano</option>
            <option value="Bambú">Bambú</option>
            <option value="Seda">Seda</option>
          </select>
        </div>
        <div class="flex gap-8">
          <select class="form-control" id="prod-sel-ventas" style="flex:2">
            <option value="">Seleccionar producto...</option>
          </select>
          <input class="form-control" id="cant-inp-ventas" style="width:80px" type="number" value="1" min="0.1" step="0.1">
          <button class="btn btn-primary" onclick="addToCart('ventas');updateTicketPreview()">+ Agregar</button>
        </div>
        <div class="form-hint">El precio se asigna automáticamente desde el catálogo</div>
      </div>
      <div class="table-wrap mb-12">
        <table><thead><tr><th>Producto</th><th>Cant.</th><th>P.Unit</th><th>Subtotal</th><th></th></tr></thead>
        <tbody id="cart-body-ventas"><tr><td colspan="5" style="text-align:center;color:var(--hint);padding:16px">Sin productos — selecciona del catálogo</td></tr></tbody>
        </table>
      </div>
      <div class="cart-total-box">
        <div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-val" id="cart-subtotal-ventas">$0.00</span></div>
        <div class="cart-total-row">
          <span class="cart-total-label">Descuento (%)</span>
          <input class="form-control" id="cart-descuento-ventas" type="number" style="width:80px;padding:4px 8px;font-size:12px" min="0" max="100" value="0" onchange="actualizarTotalConDescuento('ventas')" oninput="actualizarTotalConDescuento('ventas')">
        </div>
        <div class="cart-total-row"><span class="cart-total-label font-bold" style="font-size:15px">Total</span><span class="cart-total-val cart-total-main" id="cart-total-ventas">$0.00</span></div>
      </div>
      <button class="btn btn-primary w-full" style="justify-content:center;padding:11px;margin-top:8px" onclick="registrarVentaFinal()">✓ Registrar venta y ver ticket</button>
    </div>
    <!-- Ticket preview en tiempo real -->
    <div class="card" style="background:var(--surf2);border-style:dashed">
      <div class="card-title" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Vista previa del ticket</div>
      <div id="ticket-preview" style="background:#fff;border:1px solid var(--border);border-radius:8px;padding:16px;font-size:12px;font-family:monospace;line-height:1.7;box-shadow:0 2px 8px rgba(0,0,0,.06)">
        <div style="text-align:center;margin-bottom:10px;padding-bottom:10px;border-bottom:1px dashed #ccc">
          <div style="font-weight:700;font-size:13px">HLazcano — Prendas de Punto</div>
          <div style="font-size:10px;color:#666">Sucursal 1 — Av. Del Trabajo #72, Santa María Texmelucan</div>
          <div style="font-size:10px;color:#666">Tel: 222-555-0000</div>
        </div>
        <div style="color:#888;font-size:10px;margin-bottom:8px">
          <div>Folio: <strong>#----</strong></div>
          <div>Fecha: --/--/---- --:--</div>
          <div>Cliente: <strong>—</strong></div>
          <div>Atendió: <strong>—</strong></div>
        </div>
        <div style="border-top:1px dashed #ccc;padding-top:8px;color:#aaa;font-size:11px;text-align:center">Sin productos</div>
        <div style="border-top:1px dashed #ccc;margin-top:10px;padding-top:8px;display:none" id="ticket-total-section">
          <div style="display:flex;justify-content:space-between;font-weight:700;font-size:13px">
            <span>TOTAL</span><span id="ticket-total-val">$0.00</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Historial reciente -->
  <div class="card" style="margin-top:16px">
    <div class="card-title">Ventas recientes</div>
    <div class="table-wrap">
      <table id="tbl-ventas"><thead><tr><th>Folio</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Tienda</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody id="tbody-ventas">
        <tr id="ventas-loading"><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Cargando ventas…</td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ COMPRAS ════════════════════════════════════════════ --><div id="screen-compras" class="screen">
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Registrar compra / entrada de inventario</div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Proveedor</label><select class="form-control"><option>Seleccionar proveedor...</option><option>Textiles del Norte S.A.</option><option>Hilos Premium MX</option><option>Distribuidora Central</option><option>+ Nuevo proveedor</option></select></div>
        <div class="form-group"><label class="form-label">Tienda destino</label><select class="form-control"><option>Sucursal 1 — Santa María Texmelucan</option><option>Sucursal 2</option></select></div>
      </div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Fecha</label><input type="date" class="form-control" value="2025-03-14"></div>
        <div class="form-group"><label class="form-label">Folio / Referencia</label><input class="form-control" placeholder="FAC-2025-0042"></div>
      </div>
      <div class="form-group mb-12">
        <label class="form-label">Agregar producto</label>
        <div class="flex gap-8">
          <select class="form-control" style="flex:2"><option>Seleccionar del catálogo...</option><option>Hilo Acrílico Blanco 500g · Stock actual: 12 kg</option><option>Hilo Nylon Negro 1kg · Stock actual: 2 kg ⚠</option><option>Hilo Algodón Rojo 250g · Stock actual: 4 kg</option><option>Hilo Poliéster Azul 500g · Stock actual: 18 kg</option></select>
          <input class="form-control" style="width:75px" type="number" placeholder="Cant.">
          <input class="form-control" style="width:90px" type="number" placeholder="P.Compra">
          <button class="btn btn-primary">+</button>
        </div>
      </div>
      <div class="table-wrap mb-12">
        <table><thead><tr><th>Producto</th><th>Cantidad</th><th>P.Compra</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
          <tr><td>Hilo Nylon Negro 1kg <span class="badge badge-danger">⚠ Bajo</span></td><td>20 kg</td><td>$80.00</td><td><strong>$1,600.00</strong></td><td><button class="btn btn-xs btn-danger">✕</button></td></tr>
        </tbody></table>
      </div>
      <div class="cart-total-box">
        <div class="cart-total-row"><span class="cart-total-label font-bold" style="font-size:15px">Total compra</span><span class="cart-total-val cart-total-main">$1,600.00</span></div>
      </div>
      <div class="form-group mb-12"><label class="form-label">Notas</label><textarea class="form-control" placeholder="Observaciones sobre la compra..."></textarea></div>
      <button class="btn btn-primary w-full" style="justify-content:center;padding:11px">✓ Registrar entrada de inventario</button>
    </div>
    <div class="card">
      <div class="card-title">Historial de compras</div>
      <div class="table-wrap">
        <table><thead><tr><th>Fecha</th><th>Proveedor</th><th>Total</th><th>Tienda</th></tr></thead>
        <tbody>
          <tr><td>14/03</td><td>Textiles Norte</td><td>$2,825.00</td><td>S1</td></tr>
          <tr><td>10/03</td><td>Hilos Premium</td><td>$4,200.00</td><td>S1</td></tr>
          <tr><td>05/03</td><td>Dist. Central</td><td>$2,890.00</td><td>S1</td></tr>
          <tr><td>28/02</td><td>Textiles Norte</td><td>$1,500.00</td><td>S2</td></tr>
        </tbody></table>
      </div>
    </div>
  </div>
</div>

<!-- ══ MODAL: Detalle de venta ═══════════════════════════════ -->
<div class="modal-overlay" id="modal-detalle-venta" onclick="closeOut(event,'modal-detalle-venta')">
  <div class="modal" style="max-width:520px">
    <div class="modal-header">
      <div class="modal-title" id="modal-detalle-titulo">Detalle de venta</div>
      <button class="modal-close" onclick="closeModal('modal-detalle-venta')">✕</button>
    </div>
    <div class="modal-body" id="modal-detalle-body">
      <div style="text-align:center;color:var(--hint);padding:32px">Cargando...</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-detalle-venta')">Cerrar</button>
      <button class="btn" onclick="showToast('Ticket listo para imprimir')">🖨 Imprimir</button>
      <button class="btn" onclick="showToast('Enviando por WhatsApp...')">📤 WhatsApp</button>
    </div>
  </div>
</div>

<!-- ══ CLIENTES ═══════════════════════════════════════════ -->
<div id="screen-clientes" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm" id="cli-contador">Cargando clientes…</div>
    <button class="btn btn-primary" onclick="nuevoCliente()">+ Nuevo cliente</button>
  </div>
  <div class="filter-bar">
    <input id="cli-buscar" class="form-control" placeholder="🔍 Buscar nombre, teléfono..." style="max-width:280px" oninput="filtrarClientes()">
    <select id="cli-fil-tipo" class="form-control" style="max-width:170px" onchange="filtrarClientes()">
      <option value="">Todos los tipos</option>
      <option value="Comprador individual">Individual</option>
      <option value="Taller / Negocio">Taller</option>
      <option value="Mayorista">Mayorista</option>
      <option value="Revendedor">Revendedor</option>
    </select>
    <select id="cli-fil-tienda" class="form-control" style="max-width:160px" onchange="filtrarClientes()">
      <option value="">Todas las tiendas</option>
      <option value="1">Sucursal 1</option>
      <option value="2">Sucursal 2</option>
    </select>
  </div>
  <div class="table-wrap">
    <table id="tbl-clientes">
      <thead><tr><th>Cliente</th><th>Teléfono</th><th>Correo</th><th>Tipo</th><th>RFC</th><th>Tienda</th><th>Acciones</th></tr></thead>
      <tbody id="tbody-clientes">
        <tr id="clientes-loading"><td colspan="7" style="text-align:center;padding:24px;color:var(--muted)">Cargando clientes…</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ══ ALERTAS ════════════════════════════════════════════ --><div id="screen-alertas" class="screen">
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

<!-- ══ LOCALIZADOR ════════════════════════════════════════ --><div id="screen-localizador" class="screen">
  <div class="filter-bar mb-16">
    <div class="form-group" style="flex:2;min-width:0"><label class="form-label">Producto</label><select class="form-control" id="loc-prod"><option value="">Seleccionar producto...</option><option value="acrilico-blanco">Hilo Acrílico Blanco 500g → A-1</option><option value="nylon-negro">Hilo Nylon Negro 1kg → B-2</option><option value="algodon-rojo">Hilo Algodón Rojo 250g → A-2</option><option value="poliester-azul">Hilo Poliéster Azul 500g → C-1</option><option value="lana-verde">Hilo Lana Verde 100g → A-1</option><option value="seda-lavanda">Hilo Seda Lavanda 50g → B-1</option><option value="mercerizado-rosa">Hilo Mercerizado Rosa 100g → A-1</option></select></div>
    <div class="form-group" style="flex:1;min-width:0"><label class="form-label">Color</label><select class="form-control"><option>Cualquier color</option><option>⚪ Blanco</option><option>⚫ Negro</option><option>🔴 Rojo</option><option>🔵 Azul</option><option>🟢 Verde</option><option>🌸 Rosa</option><option>💜 Lavanda</option></select></div>
    <div class="form-group" style="flex:1;min-width:0"><label class="form-label">Tipo</label><select class="form-control"><option>Cualquier tipo</option><option>Acrílico</option><option>Nylon</option><option>Poliéster</option><option>Lana</option><option>Seda</option><option>Mercerizado</option></select></div>
    <div class="form-group" style="align-self:flex-end"><button class="btn btn-primary" onclick="buscarProducto()">🔍 Buscar</button></div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Plano del local — Sucursal 1</div>
      <div class="shelf-grid" id="shelf-grid">
        <div class="shelf-cell has-items" data-code="A-1" onclick="clickShelf(this,'A-1')">A-1<span style="font-size:10px">5 prod.</span></div>
        <div class="shelf-cell has-items" data-code="A-2" onclick="clickShelf(this,'A-2')">A-2<span style="font-size:10px">4 prod.</span></div>
        <div class="shelf-cell" data-code="A-3" onclick="clickShelf(this,'A-3')">A-3<span style="font-size:10px">0 prod.</span></div>
        <div class="shelf-cell has-items" data-code="B-1" onclick="clickShelf(this,'B-1')">B-1<span style="font-size:10px">2 prod.</span></div>
        <div class="shelf-cell has-items" data-code="B-2" onclick="clickShelf(this,'B-2')">B-2<span style="font-size:10px">3 prod.</span></div>
        <div class="shelf-cell has-items" data-code="B-3" onclick="clickShelf(this,'B-3')">B-3<span style="font-size:10px">1 prod.</span></div>
        <div class="shelf-cell has-items" data-code="C-1" onclick="clickShelf(this,'C-1')">C-1<span style="font-size:10px">2 prod.</span></div>
        <div class="shelf-cell has-items" data-code="C-2" onclick="clickShelf(this,'C-2')">C-2<span style="font-size:10px">1 prod.</span></div>
        <div class="shelf-cell" data-code="C-3" onclick="clickShelf(this,'C-3')">C-3<span style="font-size:10px">0 prod.</span></div>
      </div>
      <div class="notice notice-info mt-8 text-sm" style="margin-bottom:0">💡 Haz clic en un anaquel para ver su contenido, o busca por producto arriba</div>
    </div>
    <div class="card">
      <div class="card-title">Resultado de búsqueda</div>
      <div id="loc-result" style="min-height:80px">
        <div class="empty-state" style="padding:30px 20px">
          <div class="icon">🗺️</div>
          <h3>Realiza una búsqueda</h3>
          <p style="font-size:12px">Selecciona un producto o haz clic en un anaquel del plano</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══ REPORTES ═══════════════════════════════════════════ --><div id="screen-reportes" class="screen">
  <div class="rep-tabs">
    <div class="rep-tab active" onclick="setRepTab(this);showRepContent('rep-ventas')">📊 Ventas</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-inventario')">📦 Inventario</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-compras')">📥 Compras</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-egresos')">💰 Egresos</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-clientes')">👥 Clientes</div>
    <div class="rep-tab" onclick="setRepTab(this);showRepContent('rep-comparativo')">📈 Comparativo</div>
  </div>
  <div class="filter-bar mb-16">
    <div class="form-group"><label class="form-label">Periodo</label><select class="form-control"><option>Este mes</option><option>Esta semana</option><option>Último trimestre</option><option>Este año</option></select></div>
    <div class="form-group"><label class="form-label">Tienda</label><select class="form-control"><option>Todas las tiendas</option><option>Sucursal 1</option><option>Sucursal 2</option></select></div>
    <div class="form-group"><label class="form-label">Agrupación</label><select class="form-control"><option>Por día</option><option>Por semana</option><option>Por mes</option><option>Por tipo de hilo</option><option>Por empleado</option></select></div>
    <div class="form-group" style="align-self:flex-end;gap:6px;display:flex">
      <button class="btn btn-primary">Ver reporte</button>
      <button class="btn" onclick="showToast('Generando PDF...')">⬇ PDF</button>
      <button class="btn" onclick="showToast('Generando Excel...')">⬇ Excel</button>
    </div>
  </div>

  <!-- VENTAS -->
  <div id="rep-ventas" class="rep-panel">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:#2563EB">$18,420</div><div class="stat-lbl">Ventas totales</div><div class="stat-delta up">↑ 12% vs mes ant.</div></div>
      <div class="stat-card"><div class="stat-num">142</div><div class="stat-lbl">Transacciones</div><div class="stat-delta up">↑ 8 este mes</div></div>
      <div class="stat-card"><div class="stat-num">$129.70</div><div class="stat-lbl">Ticket promedio</div><div class="stat-delta up">↑ 4%</div></div>
      <div class="stat-card"><div class="stat-num">84%</div><div class="stat-lbl">Catálogo vendido</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Ventas por día — Marzo 2025 <small style="color:var(--muted);font-size:11px">Pico máx: 21 Mar — $2,340</small></div>
        <div class="bar-chart" style="height:120px">
          <div class="bar" style="height:45%" title="$800"></div><div class="bar" style="height:68%" title="$1,200"></div><div class="bar" style="height:38%" title="$700"></div>
          <div class="bar" style="height:85%" title="$1,560"></div><div class="bar" style="height:60%" title="$1,100"></div><div class="bar hi" style="height:92%" title="$1,700"></div>
          <div class="bar" style="height:70%" title="$1,280"></div><div class="bar" style="height:55%" title="$1,000"></div><div class="bar" style="height:78%" title="$1,430"></div>
          <div class="bar" style="height:82%" title="$1,500"></div><div class="bar" style="height:65%" title="$1,190"></div><div class="bar" style="height:44%" title="$800"></div>
          <div class="bar hi" style="height:100%" title="$2,340"></div><div class="bar" style="height:74%" title="$1,360"></div>
        </div>
        <div class="flex justify-between mt-8 text-sm text-muted"><span>S1: $11,240</span><span>Pico: $2,340</span><span>S2: $7,180</span></div>
      </div>
      <div class="card">
        <div class="card-title">Top 5 productos más vendidos</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥇 Hilo Acrílico Blanco</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:85%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">$4,420</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥈 Hilo Poliéster Azul</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:67%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$3,410</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">🥉 Hilo Nylon Negro</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:52%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$2,640</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">4. Hilo Algodón Rojo</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:38%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$1,925</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:170px">5. Hilo Lana Verde</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:24%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$1,210</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Detalle de transacciones — Marzo 2025 <button class="btn btn-sm" onclick="showToast('Exportando tabla...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Fecha</th><th>Cliente</th><th>Productos</th><th>Tienda</th><th>Empleado</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>#0042</td><td>14/03/2025</td><td>María González</td><td>Acrílico + Poliéster</td><td>S1</td><td>Ana R.</td><td><strong>$185.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0041</td><td>14/03/2025</td><td>Venta directa</td><td>Acrílico Blanco x2</td><td>S1</td><td>Ana R.</td><td><strong>$130.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0040</td><td>13/03/2025</td><td>J. Pérez</td><td>Nylon Negro x5</td><td>S2</td><td>Carlos M.</td><td><strong>$550.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0039</td><td>13/03/2025</td><td>Artesanías V.</td><td>Pedido mixto</td><td>S1</td><td>Ana R.</td><td><strong>$1,340.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
          <tr><td>#0038</td><td>12/03/2025</td><td>M. González</td><td>Poliéster Azul x4</td><td>S1</td><td>Hernán M.</td><td><strong>$220.00</strong></td><td><span class="badge badge-ok">✅ OK</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- INVENTARIO -->
  <div id="rep-inventario" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num">347</div><div class="stat-lbl">Productos registrados</div></div>
      <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$28,450</div><div class="stat-lbl">Valor del inventario</div></div>
      <div class="stat-card" style="border-color:#fca5a5;background:var(--dangbg)"><div class="stat-num" style="color:var(--danger)">5</div><div class="stat-lbl">Bajo stock</div></div>
      <div class="stat-card"><div class="stat-num">2</div><div class="stat-lbl">Sin movimiento +30d</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Distribución por tipo de hilo</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Acrílico</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:55%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">55%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Algodón</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:18%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">18%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Nylon</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:10%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">10%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Poliéster</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:9%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">9%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Lana</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:5%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">5%</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:120px">Otros</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:12px"><div style="width:3%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">3%</span></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Valor por tienda</div>
        <div style="background:var(--accentbg);border:1px solid var(--accent2);border-radius:10px;padding:14px;margin-bottom:10px">
          <div class="font-bold">Sucursal 1 — Santa María Texmelucan</div>
          <div class="text-muted text-sm">213 productos</div>
          <div style="font-family:'Segoe UI',Arial,sans-serif;font-size:22px;font-weight:700;color:var(--accent)">$18,290</div>
        </div>
        <div style="background:var(--surf2);border-radius:10px;padding:14px">
          <div class="font-bold">Sucursal 2 — Por definir</div>
          <div class="text-muted text-sm">134 productos</div>
          <div style="font-family:'Segoe UI',Arial,sans-serif;font-size:22px;font-weight:700">$10,160</div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Estado actual del inventario <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th><th>Mín.</th><th>Valor</th><th>Tienda</th><th>Anaquel</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>Hilo Acrílico Blanco</td><td>Acrílico</td><td>Blanco</td><td>12 kg</td><td>5 kg</td><td>$540</td><td>S1</td><td>A-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
          <tr><td>Hilo Nylon Negro</td><td>Nylon</td><td>Negro</td><td>2 kg</td><td>5 kg</td><td>$160</td><td>S2</td><td>B-2</td><td><span class="badge badge-danger">⚠ Bajo</span></td></tr>
          <tr><td>Hilo Algodón Rojo</td><td>Algodón</td><td>Rojo</td><td>4 kg</td><td>5 kg</td><td>$220</td><td>S1</td><td>A-2</td><td><span class="badge badge-warn">Mínimo</span></td></tr>
          <tr><td>Hilo Poliéster Azul</td><td>Poliéster</td><td>Azul</td><td>18 kg</td><td>5 kg</td><td>$684</td><td>S1</td><td>C-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
          <tr><td>Hilo Lana Verde</td><td>Lana</td><td>Verde</td><td>9 kg</td><td>3 kg</td><td>$810</td><td>S2</td><td>A-1</td><td><span class="badge badge-ok">Normal</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- COMPRAS -->
  <div id="rep-compras" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$10,515</div><div class="stat-lbl">Total compras</div></div>
      <div class="stat-card"><div class="stat-num">8</div><div class="stat-lbl">Órdenes procesadas</div></div>
      <div class="stat-card"><div class="stat-num">$1,314</div><div class="stat-lbl">Compra promedio</div></div>
      <div class="stat-card"><div class="stat-num">3</div><div class="stat-lbl">Proveedores activos</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Compras por semana — Marzo 2025</div>
        <div class="bar-chart" style="height:100px">
          <div class="bar" style="height:45%"></div>
          <div class="bar hi" style="height:80%"></div>
          <div class="bar" style="height:60%"></div>
          <div class="bar" style="height:35%"></div>
        </div>
        <div class="flex justify-between mt-8 text-sm text-muted"><span>Sem 1</span><span>Sem 2</span><span>Sem 3</span><span>Sem 4</span></div>
      </div>
      <div class="card">
        <div class="card-title">Gasto por proveedor</div>
        <div style="display:flex;flex-direction:column;gap:10px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Textiles del Norte</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:78%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">$4,425</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Hilos Premium MX</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:63%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">$3,800</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Dist. Central</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:38%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">$2,290</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Detalle de órdenes <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Fecha</th><th>Proveedor</th><th>Productos</th><th>Tienda</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
          <tr><td>OC-0042</td><td>14/03</td><td>Textiles Norte</td><td>Nylon Negro x20, Acrílico x15</td><td>S1</td><td><strong>$2,825</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
          <tr><td>OC-0041</td><td>10/03</td><td>Hilos Premium</td><td>Lana Verde x10, Algodón x8</td><td>S1</td><td><strong>$1,600</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
          <tr><td>OC-0040</td><td>08/03</td><td>Dist. Central</td><td>Poliéster Azul x30</td><td>S2</td><td><strong>$1,140</strong></td><td><span class="badge badge-ok">✅ Recibida</span></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- EGRESOS -->
  <div id="rep-egresos" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num" style="color:var(--danger)">$12,840</div><div class="stat-lbl">Total egresos</div></div>
      <div class="stat-card"><div class="stat-num">$10,515</div><div class="stat-lbl">Compras inventario</div></div>
      <div class="stat-card"><div class="stat-num">$2,325</div><div class="stat-lbl">Gastos operativos</div></div>
      <div class="stat-card" style="border-color:var(--accent2);background:var(--accentbg)"><div class="stat-num" style="color:var(--accent)">$5,580</div><div class="stat-lbl">Utilidad bruta</div></div>
    </div>
    <div class="card" style="margin-bottom:16px">
      <div class="card-title">Desglose de egresos</div>
      <div class="grid-4">
        <div style="padding:12px;background:var(--dangbg);border-radius:10px;text-align:center"><div class="font-bold" style="color:var(--danger)">$10,515</div><div class="text-sm text-muted">Compra de inventario</div></div>
        <div style="padding:12px;background:var(--warnbg);border-radius:10px;text-align:center"><div class="font-bold" style="color:var(--warn)">$1,200</div><div class="text-sm text-muted">Renta del local</div></div>
        <div style="padding:12px;background:var(--surf2);border-radius:10px;text-align:center"><div class="font-bold">$680</div><div class="text-sm text-muted">Servicios (luz/agua)</div></div>
        <div style="padding:12px;background:var(--surf2);border-radius:10px;text-align:center"><div class="font-bold">$445</div><div class="text-sm text-muted">Otros gastos</div></div>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Detalle de gastos operativos <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Fecha</th><th>Concepto</th><th>Categoría</th><th>Tienda</th><th>Monto</th><th>Registró</th></tr></thead>
        <tbody>
          <tr><td>14/03</td><td>Renta mensual del local</td><td><span class="badge badge-warn">Renta</span></td><td>S1</td><td><strong>$800.00</strong></td><td>Hernán M.</td></tr>
          <tr><td>12/03</td><td>Factura de luz</td><td><span class="badge badge-info">Servicios</span></td><td>S1</td><td><strong>$420.00</strong></td><td>Ana R.</td></tr>
          <tr><td>10/03</td><td>Renta mensual Suc. 2</td><td><span class="badge badge-warn">Renta</span></td><td>S2</td><td><strong>$400.00</strong></td><td>Hernán M.</td></tr>
          <tr><td>08/03</td><td>Servicio de internet</td><td><span class="badge badge-info">Servicios</span></td><td>S1</td><td><strong>$260.00</strong></td><td>Ana R.</td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- CLIENTES -->
  <div id="rep-clientes" class="rep-panel" style="display:none">
    <div class="grid-4" style="margin-bottom:20px">
      <div class="stat-card"><div class="stat-num">84</div><div class="stat-lbl">Clientes totales</div><div class="stat-delta up">↑ 6 este mes</div></div>
      <div class="stat-card"><div class="stat-num">$4,820</div><div class="stat-lbl">Ticket promedio</div><div class="stat-delta up">↑ 5%</div></div>
      <div class="stat-card"><div class="stat-num">28</div><div class="stat-lbl">Activos este mes</div></div>
      <div class="stat-card"><div class="stat-num">41</div><div class="stat-lbl">Compras — cliente top</div></div>
    </div>
    <div class="grid-2">
      <div class="card">
        <div class="card-title">Nuevos clientes — Marzo 2025</div>
        <div class="bar-chart" style="height:90px">
          <div class="bar" style="height:30%"></div><div class="bar" style="height:50%"></div><div class="bar hi" style="height:80%"></div>
          <div class="bar" style="height:60%"></div><div class="bar" style="height:40%"></div><div class="bar" style="height:70%"></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Clientes por tipo</div>
        <div style="display:flex;flex-direction:column;gap:8px">
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Comprador individual</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:57%;height:100%;background:var(--accent);border-radius:4px"></div></div><span class="text-sm font-bold">48</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Taller / Negocio</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:26%;height:100%;background:var(--accent2);border-radius:4px"></div></div><span class="text-sm font-bold">22</span></div>
          <div class="flex items-center gap-12"><span class="text-sm" style="width:160px">Mayorista</span><div style="flex:1;background:var(--surf2);border-radius:4px;height:10px"><div style="width:17%;height:100%;background:var(--border2);border-radius:4px"></div></div><span class="text-sm font-bold">14</span></div>
        </div>
      </div>
    </div>
    <div class="card" style="margin-top:16px">
      <div class="card-title">Ranking de clientes <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>#</th><th>Cliente</th><th>Tipo</th><th>Compras</th><th>Total acum.</th><th>Última compra</th><th>Ticket prom.</th></tr></thead>
        <tbody>
          <tr><td>🥇</td><td><strong>Taller El Hilo Feliz</strong></td><td><span class="badge badge-ok">Mayorista</span></td><td>41</td><td><strong>$32,100</strong></td><td>10/03/2025</td><td>$783</td></tr>
          <tr><td>🥈</td><td><strong>Textilería Puebla</strong></td><td><span class="badge badge-ok">Mayorista</span></td><td>33</td><td><strong>$24,800</strong></td><td>06/03/2025</td><td>$752</td></tr>
          <tr><td>🥉</td><td><strong>Artesanías del Valle</strong></td><td><span class="badge badge-info">Taller</span></td><td>28</td><td><strong>$18,340</strong></td><td>13/03/2025</td><td>$655</td></tr>
          <tr><td>4</td><td><strong>Confecciones López</strong></td><td><span class="badge badge-info">Taller</span></td><td>19</td><td><strong>$9,100</strong></td><td>28/02/2025</td><td>$479</td></tr>
          <tr><td>5</td><td><strong>María González</strong></td><td><span class="badge badge-gray">Individual</span></td><td>12</td><td><strong>$4,820</strong></td><td>14/03/2025</td><td>$402</td></tr>
        </tbody></table>
      </div>
    </div>
  </div>

  <!-- COMPARATIVO -->
  <div id="rep-comparativo" class="rep-panel" style="display:none">
    <div class="grid-2" style="margin-bottom:20px">
      <div class="card" style="border-color:var(--accent2);background:var(--accentbg)">
        <div class="card-title">Sucursal 1 — Santa María Texmelucan</div>
        <div class="grid-3">
          <div><div class="stat-num" style="font-size:22px;color:var(--accent)">$11,240</div><div class="stat-lbl">Ventas</div></div>
          <div><div class="stat-num" style="font-size:22px">$6,890</div><div class="stat-lbl">Compras</div></div>
          <div><div class="stat-num" style="font-size:22px">52</div><div class="stat-lbl">Clientes activos</div></div>
        </div>
      </div>
      <div class="card">
        <div class="card-title">Sucursal 2 — Por definir</div>
        <div class="grid-3">
          <div><div class="stat-num" style="font-size:22px">$7,180</div><div class="stat-lbl">Ventas</div></div>
          <div><div class="stat-num" style="font-size:22px">$3,625</div><div class="stat-lbl">Compras</div></div>
          <div><div class="stat-num" style="font-size:22px">32</div><div class="stat-lbl">Clientes activos</div></div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Rendimiento por producto — S1 vs S2 <button class="btn btn-sm" onclick="showToast('Exportando...')">⬇ Exportar</button></div>
      <div class="table-wrap">
        <table><thead><tr><th>Producto</th><th>Tipo</th><th>Vend. S1</th><th>Vend. S2</th><th>Stock S1</th><th>Stock S2</th><th>Ingreso S1</th><th>Ingreso S2</th><th>Total</th></tr></thead>
        <tbody>
          <tr><td>Hilo Acrílico Blanco</td><td>Acrílico</td><td>8 kg</td><td>4 kg</td><td>10 kg</td><td>6 kg</td><td>$520</td><td>$260</td><td><strong>$780</strong></td></tr>
          <tr><td>Hilo Nylon Negro</td><td>Nylon</td><td>3 kg</td><td>6 kg</td><td>—</td><td>2 kg</td><td>$330</td><td>$660</td><td><strong>$990</strong></td></tr>
          <tr><td>Hilo Poliéster Azul</td><td>Poliéster</td><td>12 kg</td><td>2 kg</td><td>6 kg</td><td>16 kg</td><td>$660</td><td>$110</td><td><strong>$770</strong></td></tr>
          <tr><td>Hilo Lana Verde</td><td>Lana</td><td>2 kg</td><td>5 kg</td><td>7 kg</td><td>4 kg</td><td>$260</td><td>$650</td><td><strong>$910</strong></td></tr>
          <tr><td>Hilo Algodón Rojo</td><td>Algodón</td><td>4 kg</td><td>1 kg</td><td>0 kg</td><td>3 kg</td><td>$312</td><td>$78</td><td><strong>$390</strong></td></tr>
        </tbody></table>
      </div>
    </div>
  </div>
</div>

<!-- ══ USUARIOS ═══════════════════════════════════════════ --><div id="screen-usuarios" class="screen">
  <div class="flex justify-between items-center mb-12">
    <div class="text-muted text-sm">7 usuarios registrados</div>
    <button class="btn btn-primary" onclick="openModal('modal-usuario')">+ Nuevo usuario</button>
  </div>
  <div class="table-wrap">
    <table><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Tienda</th><th>Estado</th><th>Último acceso</th><th>Acciones</th></tr></thead>
    <tbody>
      <tr><td><strong>Hernán Meneses Lazcano</strong></td><td>[correo@empresa.com]</td><td><span class="badge badge-superadmin">Superadmin</span></td><td>Todas</td><td><span class="badge badge-ok">Activo</span></td><td>Hoy 09:32</td><td class="tbl-actions"><button class="btn btn-sm btn-icon">✏️</button></td></tr>
      <tr><td><strong>Gerente Sucursal 1</strong></td><td>[correo@empresa.com]</td><td><span class="badge badge-admin">Admin</span></td><td>Sucursal 1</td><td><span class="badge badge-ok">Activo</span></td><td>Hoy 08:15</td><td class="tbl-actions"><button class="btn btn-sm btn-icon" onclick="openModal('modal-usuario')">✏️</button><button class="btn btn-sm btn-icon btn-danger">🗑</button></td></tr>
      <tr><td><strong>Ana Ramírez</strong></td><td>[correo@empresa.com]</td><td><span class="badge badge-empleado">Empleado</span></td><td>Sucursal 1</td><td><span class="badge badge-ok">Activo</span></td><td>Hoy 09:00</td><td class="tbl-actions"><button class="btn btn-sm btn-icon" onclick="openModal('modal-usuario')">✏️</button><button class="btn btn-sm btn-icon btn-danger">🗑</button></td></tr>
      <tr><td><strong>Carlos Mendoza</strong></td><td>[correo@empresa.com]</td><td><span class="badge badge-empleado">Empleado</span></td><td>Sucursal 2</td><td><span class="badge badge-ok">Activo</span></td><td>Ayer</td><td class="tbl-actions"><button class="btn btn-sm btn-icon" onclick="openModal('modal-usuario')">✏️</button><button class="btn btn-sm btn-icon btn-danger">🗑</button></td></tr>
      <tr><td><strong>Laura Flores</strong></td><td>[correo@empresa.com]</td><td><span class="badge badge-empleado">Empleado</span></td><td>Sucursal 1</td><td><span class="badge badge-gray">Inactivo</span></td><td>Hace 2 semanas</td><td class="tbl-actions"><button class="btn btn-sm btn-icon" onclick="openModal('modal-usuario')">✏️</button><button class="btn btn-sm btn-icon btn-danger">🗑</button></td></tr>
    </tbody></table>
  </div>
</div>

<!-- ══ CATÁLOGOS ══════════════════════════════════════════ --><div id="screen-catalogos" class="screen">
  <div class="notice notice-info mb-16">ℹ️ Los catálogos son los datos predeterminados del sistema. Usarlos en formularios evita errores de escritura.</div>
  <!-- Tabs de catálogo -->
  <div class="flex gap-6 mb-16" style="border-bottom:2px solid var(--border);padding-bottom:0">
    <button class="cat-tab active" data-cat="tipos" onclick="setCatTab(this,'tipos')">📋 Tipos de hilo</button>
    <button class="cat-tab" data-cat="colores" onclick="setCatTab(this,'colores')">🎨 Colores</button>
    <button class="cat-tab" data-cat="proveedores" onclick="setCatTab(this,'proveedores')">🏭 Proveedores</button>
    <button class="cat-tab" data-cat="anaqueles" onclick="setCatTab(this,'anaqueles')">🗄️ Anaqueles</button>
    <button class="cat-tab" data-cat="unidades" onclick="setCatTab(this,'unidades')">📏 Unidades</button>
  </div>

  <!-- TIPOS DE HILO -->
  <div id="cat-tipos" class="cat-panel">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Tipos de hilo</span> <span class="badge badge-gray">9 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-tipo')">+ Nuevo tipo de hilo</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar tipo de hilo..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>#</th><th>Nombre</th><th>Descripción</th><th>Productos usando</th><th>Creado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td>1</td><td><strong>Acrílico</strong></td><td class="text-muted">Hilo acrílico sintético para calcetines</td><td><span class="badge badge-ok">34 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray text-sm">En uso</span></td></tr>
        <tr><td>2</td><td><strong>Algodón</strong></td><td class="text-muted">Fibra natural, suave y transpirable</td><td><span class="badge badge-ok">18 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>3</td><td><strong>Nylon</strong></td><td class="text-muted">Alta resistencia y durabilidad</td><td><span class="badge badge-ok">12 productos</span></td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>4</td><td><strong>Poliéster</strong></td><td class="text-muted">Multiusos, fácil mantenimiento</td><td><span class="badge badge-ok">22 productos</span></td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>5</td><td><strong>Lana</strong></td><td class="text-muted">Natural, cálido, para temporada fría</td><td><span class="badge badge-ok">8 productos</span></td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>6</td><td><strong>Mercerizado</strong></td><td class="text-muted">Acabado brillante y sedoso</td><td><span class="badge badge-ok">6 productos</span></td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>7</td><td><strong>Elastano</strong></td><td class="text-muted">Con elasticidad, mezcla deportiva</td><td><span class="badge badge-ok">5 productos</span></td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>8</td><td><strong>Bambú</strong></td><td class="text-muted">Fibra ecológica, antibacterial</td><td><span class="badge badge-ok">4 productos</span></td><td>20/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
        <tr><td>9</td><td><strong>Seda</strong></td><td class="text-muted">Premium, suave y brillante</td><td><span class="badge badge-ok">3 productos</span></td><td>01/06/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-tipo')">✏️ Editar</button><span class="badge badge-gray">En uso</span></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- COLORES -->
  <div id="cat-colores" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Colores</span> <span class="badge badge-gray">12 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-color')">+ Nuevo color</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar color..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>#</th><th>Muestra</th><th>Nombre</th><th>Código Hex</th><th>Productos usando</th><th>Creado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td>1</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFFFFF;border:1px solid #ccc;border-radius:5px"></span></td><td><strong>Blanco</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFFFFF</code></td><td>28 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>2</td><td><span style="display:inline-block;width:32px;height:24px;background:#000000;border-radius:5px"></span></td><td><strong>Negro</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#000000</code></td><td>24 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>3</td><td><span style="display:inline-block;width:32px;height:24px;background:#FF0000;border-radius:5px"></span></td><td><strong>Rojo</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FF0000</code></td><td>16 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>4</td><td><span style="display:inline-block;width:32px;height:24px;background:#0000FF;border-radius:5px"></span></td><td><strong>Azul</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#0000FF</code></td><td>20 productos</td><td>01/01/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>5</td><td><span style="display:inline-block;width:32px;height:24px;background:#008000;border-radius:5px"></span></td><td><strong>Verde</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#008000</code></td><td>14 productos</td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>6</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFFF00;border:1px solid #ddd;border-radius:5px"></span></td><td><strong>Amarillo</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFFF00</code></td><td>8 productos</td><td>05/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>7</td><td><span style="display:inline-block;width:32px;height:24px;background:#800080;border-radius:5px"></span></td><td><strong>Morado</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#800080</code></td><td>6 productos</td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>8</td><td><span style="display:inline-block;width:32px;height:24px;background:#A52A2A;border-radius:5px"></span></td><td><strong>Café</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#A52A2A</code></td><td>5 productos</td><td>10/02/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>9</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFA500;border-radius:5px"></span></td><td><strong>Naranja</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFA500</code></td><td>4 productos</td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>10</td><td><span style="display:inline-block;width:32px;height:24px;background:#FFC0CB;border-radius:5px"></span></td><td><strong>Rosa</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#FFC0CB</code></td><td>6 productos</td><td>15/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>11</td><td><span style="display:inline-block;width:32px;height:24px;background:#722F37;border-radius:5px"></span></td><td><strong>Vino</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#722F37</code></td><td>3 productos</td><td>20/03/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
        <tr><td>12</td><td><span style="display:inline-block;width:32px;height:24px;background:#00FFFF;border-radius:5px;border:1px solid #ddd"></span></td><td><strong>Aqua</strong></td><td><code style="background:var(--surf2);padding:2px 7px;border-radius:4px">#00FFFF</code></td><td>2 productos</td><td>01/06/2024</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-color')">✏️ Editar</button></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- PROVEEDORES -->
  <div id="cat-proveedores" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Proveedores</span> <span class="badge badge-gray">4 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-proveedor')">+ Nuevo proveedor</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px"><input class="form-control" placeholder="🔍 Buscar proveedor..."></div>
    <div class="table-wrap">
      <table><thead><tr><th>Proveedor</th><th>Teléfono</th><th>Correo</th><th>Dirección</th><th>Órdenes</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td><strong>Textiles del Norte S.A.</strong></td><td>222-100-0001</td><td>[correo@empresa.com]</td><td>Blvd. Industrial #45, Puebla</td><td><span class="badge badge-ok">12 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Hilos Premium MX</strong></td><td>222-100-0002</td><td>[correo@empresa.com]</td><td>Av. Textil #89, Tlaxcala</td><td><span class="badge badge-ok">8 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Distribuidora Central</strong></td><td>222-100-0003</td><td>[correo@empresa.com]</td><td>Calle Fábrica #12, Puebla</td><td><span class="badge badge-ok">5 órdenes</span></td><td><span class="badge badge-ok">Activo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm" onclick="go('compras',null)">Ver compras</button></td></tr>
        <tr><td><strong>Fibras Naturales del Sur</strong></td><td>222-100-0004</td><td>—</td><td>—</td><td><span class="badge badge-gray">0 órdenes</span></td><td><span class="badge badge-gray">Inactivo</span></td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-proveedor')">✏️ Editar</button><button class="btn btn-sm btn-danger" onclick="confirmDelete('¿Eliminar proveedor Fibras Naturales del Sur? No tiene órdenes registradas.',()=>showToast('Proveedor eliminado'))">🗑</button></td></tr>
      </tbody></table>
    </div>
  </div>

  <!-- ANAQUELES -->
  <div id="cat-anaqueles" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Anaqueles</span> <span class="badge badge-gray">8 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-anaquel')">+ Nuevo anaquel</button>
    </div>
    <div class="filter-bar" style="margin-bottom:12px">
      <input class="form-control" placeholder="🔍 Buscar anaquel...">
      <select class="form-control" style="max-width:180px"><option>Todas las tiendas</option><option>Sucursal 1</option><option>Sucursal 2</option></select>
    </div>
    <div class="grid-2">
      <div>
        <div class="table-wrap">
          <table><thead><tr><th>Código</th><th>Descripción</th><th>Tienda</th><th>Productos</th><th>Acciones</th></tr></thead>
          <tbody>
            <tr><td><strong>A-1</strong></td><td>Anaquel A, sección 1 — entrada principal</td><td>S1</td><td>5 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>A-2</strong></td><td>Anaquel A, sección 2 — continuación</td><td>S1</td><td>4 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>A-3</strong></td><td>Anaquel A, sección 3 — fondo</td><td>S1</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-1</strong></td><td>Anaquel B, sección 1 — lado derecho</td><td>S1</td><td>2 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-2</strong></td><td>Anaquel B, sección 2 — central</td><td>S1</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>B-3</strong></td><td>Anaquel B, sección 3 — esquina</td><td>S1</td><td>1 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>C-1</strong></td><td>Anaquel C, sección 1 — Sucursal 2</td><td>S2</td><td>4 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
            <tr><td><strong>C-2</strong></td><td>Anaquel C, sección 2 — Sucursal 2</td><td>S2</td><td>3 prod.</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-anaquel')">✏️</button><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Ver</button></td></tr>
          </tbody></table>
        </div>
      </div>
      <div>
        <div class="card" style="margin-bottom:14px">
          <div class="card-title" style="font-size:12px">Plano visual — Sucursal 1</div>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
            <div style="padding:10px;background:var(--accentbg);border:1px solid var(--accent2);border-radius:8px;text-align:center;font-size:12px;font-weight:700;color:var(--accent)">A-1<br><span style="font-size:10px;font-weight:400">5 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">A-2<br><span style="font-size:10px">4 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">A-3<br><span style="font-size:10px">3 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">B-1<br><span style="font-size:10px">2 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">B-2<br><span style="font-size:10px">3 prod.</span></div>
            <div style="padding:10px;background:var(--warnbg);border:1px solid #f5ce70;border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--warn)">B-3<br><span style="font-size:10px">1 prod.</span></div>
          </div>
        </div>
        <div class="card">
          <div class="card-title" style="font-size:12px">Plano visual — Sucursal 2</div>
          <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:6px">
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">C-1<br><span style="font-size:10px">4 prod.</span></div>
            <div style="padding:10px;background:var(--surf2);border-radius:8px;text-align:center;font-size:12px;font-weight:600;color:var(--muted)">C-2<br><span style="font-size:10px">3 prod.</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- UNIDADES -->
  <div id="cat-unidades" class="cat-panel" style="display:none">
    <div class="flex justify-between items-center mb-12">
      <div><span class="font-bold">Unidades de medida</span> <span class="badge badge-gray">4 registros</span></div>
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-cat-unidad')">+ Nueva unidad</button>
    </div>
    <div class="table-wrap">
      <table><thead><tr><th>Abrev.</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr><td><strong>kg</strong></td><td>Kilogramos</td><td class="text-muted">Peso estándar para hilos a granel</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>g</strong></td><td>Gramos</td><td class="text-muted">Precisión en presentaciones pequeñas</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>pieza</strong></td><td>Piezas</td><td class="text-muted">Conteo por unidad individual</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
        <tr><td><strong>rollo</strong></td><td>Rollos</td><td class="text-muted">Presentación en rollo continuo</td><td class="tbl-actions"><button class="btn btn-sm" onclick="openModal('modal-cat-unidad')">✏️ Editar</button></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ RESPALDO ════════════════════════════════════════════ --><div id="screen-respaldo" class="screen">
  <div class="notice notice-info mb-16">💾 <strong>Respaldo de base de datos</strong> · Crea una copia de seguridad completa de todos los datos del sistema</div>
  
  <div class="card">
    <div class="card-title">Generar respaldo</div>
    <div class="mb-16">
      <p class="text-muted mb-12">El respaldo incluye todas las tablas de la base de datos: productos, ventas, clientes, usuarios, catálogos, etc. El archivo se descargará automáticamente en formato SQL.</p>
      <div class="flex gap-12 items-center">
        <button class="btn btn-primary" onclick="generarRespaldo()" id="btn-respaldo">
          <span id="btn-text">💾 Generar respaldo</span>
          <span id="btn-loading" style="display:none">⏳ Generando...</span>
        </button>
        <div class="text-sm text-muted">
          <div id="ultimo-respaldo-info">Último respaldo: <strong>Cargando...</strong></div>
          <div>Tamaño aproximado: <strong>~2MB</strong></div>
        </div>
      </div>
    </div>
    
    <div class="divider"></div>
    
    <div class="section-title">Historial de respaldos</div>
    <div class="table-wrap" id="historial-respaldo">
      <table>
        <thead><tr><th>Fecha</th><th>Usuario</th><th>Archivo</th><th>Tamaño</th><th>Estado</th></tr></thead>
        <tbody id="tbody-historial">
          <tr><td colspan="5" style="text-align:center;color:var(--hint);padding:20px">Cargando historial...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ══ EMPLEADO: VENTAS ═══════════════════════════════════ --><div id="screen-emp-ventas" class="screen">
  <div class="notice notice-info mb-16">🏪 <strong>Sucursal 1 — Santa María Texmelucan</strong> · Solo puedes registrar ventas en tu tienda asignada</div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Nueva venta</div>
      <div class="form-group mb-12"><label class="form-label">Cliente (opcional)</label>
        <select class="form-control"><option>Sin cliente (venta directa)</option><option>María González · 222-555-0101</option><option>Juan Pérez · 222-555-0303</option><option>Artesanías del Valle</option><option>+ Registrar nuevo cliente</option></select>
      </div>
      <div class="form-group mb-12"><label class="form-label">Agregar producto</label>
        <div class="flex gap-8">
          <select class="form-control" style="flex:2"><option>Seleccionar del catálogo...</option><option>Hilo Acrílico Blanco 500g · Stock: 12 kg · $65/kg</option><option>Hilo Poliéster Azul 500g · Stock: 18 kg · $55/kg</option><option>Hilo Lana Verde 100g · Stock: 9 kg · $130/kg</option></select>
          <input class="form-control" style="width:80px" type="number" value="1">
          <button class="btn btn-primary">+</button>
        </div>
      </div>
      <div class="table-wrap mb-12">
        <table><thead><tr><th>Producto</th><th>Cant.</th><th>Precio</th><th>Subtotal</th><th></th></tr></thead>
        <tbody><tr><td>Hilo Acrílico Blanco 500g</td><td>2 kg</td><td>$65.00</td><td><strong>$130.00</strong></td><td><button class="btn btn-xs btn-danger">✕</button></td></tr></tbody>
        </table>
      </div>
      <div class="cart-total-box"><div class="cart-total-row"><span class="cart-total-label font-bold" style="font-size:15px">Total</span><span class="cart-total-val cart-total-main">$130.00</span></div></div>
      <button class="btn btn-primary w-full" style="justify-content:center;padding:11px">✓ Confirmar venta</button>
    </div>
    <div class="card">
      <div class="card-title">Mis ventas de hoy</div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Hora</th></tr></thead>
        <tbody>
          <tr><td>#0042</td><td>M. González</td><td>$185.00</td><td>11:32</td></tr>
          <tr><td>#0041</td><td>Directa</td><td>$65.00</td><td>10:15</td></tr>
          <tr><td>#0040</td><td>J. Pérez</td><td>$440.00</td><td>09:48</td></tr>
        </tbody></table>
      </div>
      <div class="cart-total-box mt-12" style="margin-bottom:0"><div class="cart-total-row"><span class="cart-total-label">Total del turno</span><span class="cart-total-val" style="color:var(--accent)">$690.00</span></div></div>
    </div>
  </div>
</div>

<!-- ══ EMPLEADO: INVENTARIO ═══════════════════════════════ --><div id="screen-emp-inventario" class="screen">
  <div class="notice notice-warn mb-12">👁️ <strong>Modo solo lectura</strong> — Puedes consultar el inventario pero no modificarlo · Sucursal 1</div>
  <div class="filter-bar">
    <input class="form-control" placeholder="🔍 Buscar..." style="max-width:260px">
    <select class="form-control" style="max-width:160px"><option>Todos los tipos</option><option>Acrílico</option><option>Algodón</option><option>Nylon</option></select>
    <select class="form-control" style="max-width:150px"><option>Todos los colores</option><option>⚪ Blanco</option><option>⚫ Negro</option></select>
    <div style="margin-left:auto;padding:6px 12px;background:var(--surf2);border:1px solid var(--border);border-radius:8px;font-size:12px;color:var(--hint)">🔒 Solo lectura</div>
  </div>
  <div class="table-wrap">
    <table><thead><tr><th>Producto</th><th>Tipo</th><th>Color</th><th>Stock</th><th>P.Venta</th><th>Anaquel</th><th>Localizar</th></tr></thead>
    <tbody>
      <tr><td>Hilo Acrílico Blanco 500g</td><td>Acrílico</td><td><span class="color-pill"><span class="color-swatch" style="background:#eee;border:1px solid #ccc"></span>Blanco</span></td><td><span class="badge badge-ok">12 kg</span></td><td>$65.00</td><td>A-1</td><td><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button></td></tr>
      <tr><td>Hilo Nylon Negro 1kg</td><td>Nylon</td><td><span class="color-pill"><span class="color-swatch" style="background:#111"></span>Negro</span></td><td><span class="badge badge-danger">2 kg ⚠</span></td><td>$110.00</td><td>B-2</td><td><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button></td></tr>
      <tr><td>Hilo Poliéster Azul 500g</td><td>Poliéster</td><td><span class="color-pill"><span class="color-swatch" style="background:#1976d2"></span>Azul</span></td><td><span class="badge badge-ok">18 kg</span></td><td>$55.00</td><td>C-1</td><td><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button></td></tr>
      <tr><td>Hilo Lana Verde 100g</td><td>Lana</td><td><span class="color-pill"><span class="color-swatch" style="background:#388e3c"></span>Verde</span></td><td><span class="badge badge-ok">9 kg</span></td><td>$130.00</td><td>A-1</td><td><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button></td></tr>
    </tbody></table>
  </div>
</div>

    </div><!-- /content -->
  </div><!-- /main-area -->
</div><!-- /app -->

<!-- ══ ALERTAS (enhanced) ════════════════════════════════ -->
<!-- Replace existing alerts screen - done via JS overwrite -->

<!-- ══ PERFIL CLIENTE ══════════════════════════════════════ --><div id="screen-cliente-perfil" class="screen">
  <div class="flex items-center gap-12 mb-16">
    <button class="btn" onclick="go('clientes',null)">← Clientes</button>
    <div class="font-bold" style="font-size:15px;font-family:'Segoe UI',Arial,sans-serif">Artesanías del Valle</div>
    <span class="badge badge-info" style="margin-left:4px">Taller</span>
    <div class="flex gap-8 ml-auto">
      <button class="btn" onclick="openModal('modal-cliente')">✏️ Editar</button>
      <button class="btn btn-danger" onclick="confirmDelete('¿Eliminar a Artesanías del Valle? Esta acción no se puede deshacer.',()=>go('clientes',null))">🗑 Eliminar</button>
    </div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Información del cliente</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div><div class="form-label">Nombre</div><div class="font-bold">Artesanías del Valle</div></div>
        <div><div class="form-label">Tipo</div><span class="badge badge-info">Taller / Negocio</span></div>
        <div><div class="form-label">Teléfono</div><div>222-555-0202</div></div>
        <div><div class="form-label">Correo</div><div class="text-sm">[correo@empresa.com]</div></div>
        <div><div class="form-label">Tienda preferida</div><div>Sucursal 1 — Santa María Texmelucan</div></div>
        <div><div class="form-label">RFC</div><div>AVAL920318BC4</div></div>
        <div style="grid-column:span 2"><div class="form-label">Dirección</div><div>Av. Reforma #48, Santa María Texmelucan</div></div>
        <div style="grid-column:span 2"><div class="form-label">Notas</div><div class="text-sm" style="color:var(--muted);background:var(--surf2);padding:8px 10px;border-radius:7px">Taller de tejidos. Pedidos grandes los viernes. Solicita factura cuando el monto supera $500.</div></div>
      </div>
    </div>
    <div>
      <div class="grid-2" style="margin-bottom:14px">
        <div class="stat-card"><div class="stat-num" style="color:var(--accent)">$18,340</div><div class="stat-lbl">Total gastado</div></div>
        <div class="stat-card"><div class="stat-num">28</div><div class="stat-lbl">Compras totales</div></div>
        <div class="stat-card"><div class="stat-num">$655</div><div class="stat-lbl">Ticket promedio</div></div>
        <div class="stat-card"><div class="stat-num">Vie.</div><div class="stat-lbl">Día frecuente</div></div>
      </div>
    </div>
  </div>
  <div class="card mt-16">
    <div class="card-title">Historial de compras <button class="btn btn-sm btn-primary" onclick="go('ventas',null)">+ Nueva venta</button></div>
    <div class="table-wrap">
      <table><thead><tr><th>Folio</th><th>Fecha</th><th>Productos</th><th>Total</th><th>Tienda</th><th></th></tr></thead>
      <tbody>
        <tr><td>#0039</td><td>13/03/2025</td><td>Hilo Acrílico, Nylon, Poliéster</td><td><strong>$1,340.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0031</td><td>05/03/2025</td><td>Hilo Algodón x8, Lana x3</td><td><strong>$890.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0024</td><td>26/02/2025</td><td>Hilo Nylon Negro x10</td><td><strong>$1,100.00</strong></td><td>S2</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0018</td><td>18/02/2025</td><td>Hilo Acrílico Blanco x15</td><td><strong>$975.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
        <tr><td>#0012</td><td>10/02/2025</td><td>Pedido mixto — 6 tipos</td><td><strong>$2,200.00</strong></td><td>S1</td><td><button class="btn btn-xs">Ver</button></td></tr>
      </tbody></table>
    </div>
  </div>
</div>

<!-- ══ EMPLEADO: DASHBOARD ════════════════════════════════ --><div id="screen-emp-dashboard" class="screen">
  <div class="notice notice-info mb-16">📍 <strong>Estás operando en Sucursal 1 — Santa María Texmelucan</strong> · Turno: 09:00 – 17:00 · Hoy, 20/03/2025</div>
  <div class="grid-4" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card" style="border-color:var(--accent2)"><div class="stat-num" style="color:var(--accent)">$1,840</div><div class="stat-lbl">Mis ventas hoy</div></div>
    <div class="stat-card"><div class="stat-num">12</div><div class="stat-lbl">Transacciones</div></div>
    <div class="stat-card" style="border-color:#fca5a5"><div class="stat-num" style="color:var(--danger)">3</div><div class="stat-lbl">Alertas activas</div></div>
    <div class="stat-card"><div class="stat-num">$153</div><div class="stat-lbl">Ticket promedio</div></div>
  </div>
  <div class="grid-2">
    <div class="card">
      <div class="card-title">Acciones rápidas</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('emp-ventas',null)">
          <span style="font-size:22px">🛒</span><span class="font-bold">Registrar venta</span><span class="text-muted text-sm">Agregar productos</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('clientes',null)">
          <span style="font-size:22px">👥</span><span class="font-bold">Clientes</span><span class="text-muted text-sm">Buscar o registrar</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px" onclick="go('localizador',null)">
          <span style="font-size:22px">🗺️</span><span class="font-bold">Localizador</span><span class="text-muted text-sm">Encontrar producto</span>
        </button>
        <button class="btn" style="padding:16px;flex-direction:column;height:auto;justify-content:center;gap:6px;border-color:#fca5a5;background:var(--dangbg)" onclick="go('alertas',null)">
          <span style="font-size:22px">🔔</span><span class="font-bold" style="color:var(--danger)">Alertas</span><span class="text-muted text-sm">3 activas</span>
        </button>
      </div>
    </div>
    <div class="card">
      <div class="card-title">Mis ventas de hoy</div>
      <div class="table-wrap">
        <table><thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Hora</th></tr></thead>
        <tbody>
          <tr><td>#0042</td><td>M. González</td><td>$185.00</td><td>11:32</td></tr>
          <tr><td>#0041</td><td>Venta directa</td><td>$130.00</td><td>10:15</td></tr>
          <tr><td>#0040</td><td>J. Pérez</td><td>$65.00</td><td>09:48</td></tr>
          <tr><td>#0039</td><td>Artesanías V.</td><td>$440.00</td><td>09:12</td></tr>
          <tr><td>#0038</td><td>Venta directa</td><td>$55.00</td><td>08:55</td></tr>
        </tbody></table>
      </div>
      <div class="cart-total-box mt-8" style="margin-bottom:0">
        <div class="cart-total-row"><span class="cart-total-label">Total del turno</span><span class="cart-total-val" style="color:var(--accent)">$875.00</span></div>
      </div>
    </div>
  </div>
  <div class="card mt-16">
    <div class="card-title" style="color:var(--danger)">⚠ Alertas activas — Sucursal 1 <span class="badge badge-danger">3</span></div>
    <div class="alert-item danger"><div class="alert-dot" style="background:var(--danger)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Nylon Negro 1kg</div><div class="text-sm text-muted">2 kg — bajo stock · Anaquel B-2</div></div><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button><span class="badge badge-gray">🔒 Solo admin</span></div>
    <div class="alert-item danger"><div class="alert-dot" style="background:var(--danger)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Mercerizado Rosa 100g</div><div class="text-sm text-muted">1 kg — bajo stock · Anaquel A-1</div></div><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button><span class="badge badge-gray">🔒 Solo admin</span></div>
    <div class="alert-item danger"><div class="alert-dot" style="background:var(--danger)"></div><div style="flex:1"><div class="font-bold text-sm">Hilo Elastano Café 50g</div><div class="text-sm text-muted">0 kg — sin stock · Anaquel B-3</div></div><button class="btn btn-sm" onclick="go('localizador',null)">🗺️ Localizar</button><span class="badge badge-gray">🔒 Solo admin</span></div>
  </div>
</div>


<!-- ════════════════════════════════════════════════════════ -->
<!-- MODALES                                                  -->
<!-- ════════════════════════════════════════════════════════ -->

<!-- Modal: Nuevo Cliente -->
<div class="modal-overlay" id="modal-cliente" onclick="closeOut(event,'modal-cliente')">
  <div class="modal">
    <div class="modal-header"><div class="modal-title" id="modal-cliente-titulo">Registrar nuevo cliente</div><button class="modal-close" onclick="closeModal('modal-cliente')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="cli-id" value="">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Nombre completo *</label><input id="cli-nombre" class="form-control" placeholder="Nombre del cliente o empresa"></div>
        <div class="form-group"><label class="form-label">Teléfono *</label><input id="cli-telefono" class="form-control" type="tel" placeholder="222-555-0000"></div>
      </div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Correo electrónico</label><input id="cli-correo" class="form-control" type="email" placeholder="correo@ejemplo.com"></div>
        <div class="form-group"><label class="form-label">Tipo de cliente *</label>
          <select id="cli-tipo" class="form-control">
            <option value="Comprador individual">Comprador individual</option>
            <option value="Taller / Negocio">Taller / Negocio</option>
            <option value="Mayorista">Mayorista</option>
            <option value="Revendedor">Revendedor</option>
          </select>
        </div>
      </div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Tienda</label>
          <select id="cli-id-tienda" class="form-control">
            <option value="1">Sucursal 1 — Santa María Texmelucan</option>
            <option value="2">Sucursal 2</option>
          </select>
        </div>
        <div class="form-group"><label class="form-label">RFC (opcional)</label><input id="cli-rfc" class="form-control" placeholder="GOML820512XY3"></div>
      </div>
      <div class="form-group"><label class="form-label">Dirección</label><input id="cli-direccion" class="form-control" placeholder="Calle, colonia, ciudad..."></div>
      <div class="form-group"><label class="form-label">Notas</label><textarea id="cli-notas" class="form-control" placeholder="Observaciones, preferencias..."></textarea></div>
    </div>
    <div class="modal-footer"><button class="btn" onclick="closeModal('modal-cliente')">Cancelar</button><button class="btn btn-primary" onclick="guardarCliente()">✓ Guardar cliente</button></div>
  </div>
</div>

<!-- Modal: Nuevo Usuario -->
<div class="modal-overlay" id="modal-usuario" onclick="closeOut(event,'modal-usuario')">
  <div class="modal">
    <div class="modal-header"><div class="modal-title">Nuevo usuario</div><button class="modal-close" onclick="closeModal('modal-usuario')">✕</button></div>
    <div class="modal-body">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Nombre completo *</label><input class="form-control" placeholder="Nombre del empleado"></div>
        <div class="form-group"><label class="form-label">Correo electrónico *</label><input class="form-control" type="email" placeholder="nombre@hlazano.com"></div>
      </div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Rol *</label><select class="form-control" onchange="showRolInfo(this)"><option value="empleado">Empleado — ventas e inventario</option><option value="admin">Admin — gestión de tienda</option><option value="superadmin">Superadmin — acceso total</option></select></div>
        <div class="form-group"><label class="form-label">Tienda asignada *</label><select class="form-control"><option>Sucursal 1 — Santa María Texmelucan</option><option>Sucursal 2 — Por definir</option><option>Todas (solo superadmin)</option></select></div>
      </div>
      <div class="notice notice-info" id="rol-info">ℹ️ <strong>Empleado:</strong> solo puede registrar ventas, consultar inventario y gestionar clientes en su tienda asignada.</div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Contraseña temporal *</label><input class="form-control" type="password" placeholder="Mín. 8 caracteres"></div>
        <div class="form-group"><label class="form-label">Confirmar contraseña *</label><input class="form-control" type="password"></div>
      </div>
    </div>
    <div class="modal-footer"><button class="btn" onclick="closeModal('modal-usuario')">Cancelar</button><button class="btn btn-primary">✓ Crear usuario</button></div>
  </div>
</div>

<!-- Modal: Nuevo tipo catálogo -->
<div class="modal-overlay" id="modal-cat-tipo" onclick="closeOut(event,'modal-cat-tipo')">
  <div class="modal" style="max-width:460px">
    <div class="modal-header"><div class="modal-title">Nuevo tipo de hilo</div><button class="modal-close" onclick="closeModal('modal-cat-tipo')">✕</button></div>
    <div class="modal-body">
      <div class="form-group mb-12"><label class="form-label">Nombre del tipo *</label><input class="form-control" placeholder="Ej: Bambú, Viscosa, Ramie..."><div class="form-hint">Aparecerá en todos los formularios como opción predeterminada</div></div>
      <div class="form-group"><label class="form-label">Descripción</label><textarea class="form-control" placeholder="Características principales del tipo de hilo..."></textarea></div>
      <div class="notice notice-info mt-12" style="margin-bottom:0">ℹ️ Usar catálogos predeterminados evita errores de escritura en todo el sistema.</div>
    </div>
    <div class="modal-footer"><button class="btn" onclick="closeModal('modal-cat-tipo')">Cancelar</button><button class="btn btn-primary">✓ Guardar tipo</button></div>
  </div>
</div>

<!-- ══ VENTA CONFIRMADA ══════════════════════════════════ --><div id="screen-venta-confirmada" class="screen">
  <div class="notice notice-success mb-16" style="font-size:13px;font-weight:600">
    <span class="venta-folio">✅ Venta registrada exitosamente</span>
  </div>
  <div style="display:grid;grid-template-columns:1fr 320px;gap:20px">
    <div class="card">
      <div class="card-title">Resumen de la venta — <span class="font-bold">#0043</span></div>
      <div class="text-sm text-muted venta-cliente-info" style="margin-bottom:14px">Cliente: — · Sucursal 1</div>
      <div class="table-wrap mb-12">
        <table><thead><tr><th>Producto</th><th>Cantidad</th><th>P.Unit.</th><th>Subtotal</th><th>Acción</th></tr></thead>
        <tbody class="venta-items-body">
          <tr><td colspan="5" style="text-align:center;color:var(--hint)">Sin productos</td></tr>
        </tbody></table>
      </div>
      <div class="cart-total-box">
        <div class="cart-total-row"><span class="cart-total-label">Subtotal</span><span class="cart-total-val venta-subtotal">$0.00</span></div>
        <div class="cart-total-row"><span class="cart-total-label font-bold" style="font-size:15px">Total cobrado</span><span class="cart-total-val cart-total-main venta-total-main">$0.00</span></div>
      </div>
      <div class="flex gap-8 mt-12">
        <button class="btn" onclick="go('ventas',null)">← Nueva venta</button>
        <button class="btn" onclick="showToast('Descargando ticket...')">⬇ Descargar ticket</button>
        <button class="btn" onclick="showToast('Enviando por WhatsApp...')">📤 Enviar por WhatsApp</button>
        <button class="btn btn-primary" onclick="showToast('Imprimiendo...')">🖨 Imprimir</button>
      </div>
    </div>
    <div class="card" style="background:var(--surf2)">
      <div class="card-title" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Ticket de venta</div>
      <div class="ticket-side" style="background:#fff;border:1px solid var(--border);border-radius:8px;padding:16px;font-size:11px;font-family:monospace;line-height:1.7;box-shadow:0 2px 8px rgba(0,0,0,.06)">
        — ticket —
      </div>
      <div class="flex gap-8 mt-12">
        <button class="btn btn-sm" style="flex:1;justify-content:center" onclick="showToast('Descargando PDF...')">⬇ PDF</button>
        <button class="btn btn-sm" style="flex:1;justify-content:center" onclick="showToast('Imprimiendo...')">🖨 Imprimir</button>
      </div>
    </div>
  </div>
</div>
    </div><!-- /.content -->
  </div><!-- /.main-area -->
</div><!-- /.app -->
<!-- Modal: Atender alerta -->
<div class="modal-overlay" id="modal-alerta" onclick="closeOut(event,'modal-alerta')">
  <div class="modal" style="max-width:500px">
    <div class="modal-header"><div class="modal-title">Marcar alerta como atendida</div><button class="modal-close" onclick="closeModal('modal-alerta')">✕</button></div>
    <div class="modal-body">
      <div class="notice notice-danger mb-12" id="alert-modal-info-box">
        <div>
          <div class="font-bold" id="alert-modal-name">Hilo Nylon Negro 1kg</div>
          <div class="text-sm" id="alert-modal-info">Stock actual: 2 kg · Mínimo: 5 kg · Sucursal 2</div>
        </div>
      </div>
      <div class="form-group mb-12">
        <label class="form-label">¿Cómo se atendió esta alerta? *</label>
        <select class="form-control" id="alert-accion" onchange="toggleAlertNota(this)">
          <option value="">Seleccionar acción...</option>
          <option value="compra">Se realizó una compra / entrada de inventario</option>
          <option value="ajuste">Se ajustó el stock mínimo</option>
          <option value="discontinue">Se decidió no reponer (producto descontinuado)</option>
          <option value="precio">Se redujo el precio para estimular venta</option>
          <option value="otro">Otro — agregar nota manualmente</option>
        </select>
      </div>
      <div class="form-group" id="alert-nota-wrap" style="display:none">
        <label class="form-label">Notas adicionales (opcional)</label>
        <textarea class="form-control" id="alert-nota" placeholder="Describe la acción tomada..."></textarea>
      </div>
      <div class="notice notice-info" id="alert-accion-preview" style="display:none"></div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-alerta')">Cancelar</button>
      <button class="btn btn-primary" onclick="confirmarAtender()">✓ Confirmar — marcar como atendida</button>
    </div>
  </div>
</div>

<!-- Modal: Cambiar contraseña (solo admin) -->
<div class="modal-overlay" id="modal-cambiar-clave" onclick="closeOut(event,'modal-cambiar-clave')">
  <div class="modal" style="max-width:440px">
    <div class="modal-header"><div class="modal-title">🔑 Cambiar contraseña</div><button class="modal-close" onclick="closeModal('modal-cambiar-clave')">✕</button></div>
    <div class="modal-body">
      <div class="notice notice-warn mb-12">⚠️ Solo el Administrador puede cambiar contraseñas de usuarios.</div>
      <div class="form-group mb-12"><label class="form-label">Nueva contraseña *</label><input class="form-control" type="password" placeholder="Mínimo 8 caracteres, incluir números y letras"></div>
      <div class="form-group mb-12"><label class="form-label">Confirmar nueva contraseña *</label><input class="form-control" type="password"></div>
      <div class="notice notice-info" style="font-size:12px">⚠️ El usuario deberá cambiar su contraseña en su próximo acceso. Se enviará una notificación al correo registrado.</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cambiar-clave')">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal('modal-cambiar-clave');showToast('✅ Contraseña cambiada exitosamente')">🔑 Cambiar contraseña</button>
    </div>
  </div>
</div>

<!-- Modal: Nuevo color catálogo -->
<div class="modal-overlay" id="modal-cat-color" onclick="closeOut(event,'modal-cat-color')">
  <div class="modal" style="max-width:440px">
    <div class="modal-header"><div class="modal-title">🎨 Nuevo color</div><button class="modal-close" onclick="closeModal('modal-cat-color')">✕</button></div>
    <div class="modal-body">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Nombre del color *</label><input class="form-control" id="nuevo-color-nombre" placeholder="Ej: Turquesa, Lavanda..." oninput="updateColorPreview()"></div>
        <div class="form-group"><label class="form-label">Código hexadecimal *</label><input class="form-control" id="nuevo-color-hex" placeholder="#40E0D0" oninput="updateColorPreview()"></div>
      </div>
      <div class="form-group mb-12">
        <label class="form-label">Vista previa</label>
        <div id="color-preview-box" style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--surf2);border-radius:8px">
          <span id="color-preview-swatch" style="display:inline-block;width:48px;height:48px;background:#ccc;border-radius:8px;border:1px solid var(--border)"></span>
          <span id="color-preview-name" class="font-bold text-muted">Vista previa</span>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Selección rápida</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px">
          <span onclick="setQuickColor('#FFFFFF','Blanco')" style="width:28px;height:28px;background:#FFFFFF;border:1px solid #ccc;border-radius:6px;cursor:pointer" title="Blanco"></span>
          <span onclick="setQuickColor('#000000','Negro')" style="width:28px;height:28px;background:#000000;border-radius:6px;cursor:pointer" title="Negro"></span>
          <span onclick="setQuickColor('#FF0000','Rojo')" style="width:28px;height:28px;background:#FF0000;border-radius:6px;cursor:pointer" title="Rojo"></span>
          <span onclick="setQuickColor('#0000FF','Azul')" style="width:28px;height:28px;background:#0000FF;border-radius:6px;cursor:pointer" title="Azul"></span>
          <span onclick="setQuickColor('#008000','Verde')" style="width:28px;height:28px;background:#008000;border-radius:6px;cursor:pointer" title="Verde"></span>
          <span onclick="setQuickColor('#FFFF00','Amarillo')" style="width:28px;height:28px;background:#FFFF00;border:1px solid #ddd;border-radius:6px;cursor:pointer" title="Amarillo"></span>
          <span onclick="setQuickColor('#800080','Morado')" style="width:28px;height:28px;background:#800080;border-radius:6px;cursor:pointer" title="Morado"></span>
          <span onclick="setQuickColor('#FFC0CB','Rosa')" style="width:28px;height:28px;background:#FFC0CB;border-radius:6px;cursor:pointer" title="Rosa"></span>
          <span onclick="setQuickColor('#40E0D0','Turquesa')" style="width:28px;height:28px;background:#40E0D0;border-radius:6px;cursor:pointer" title="Turquesa"></span>
          <span onclick="setQuickColor('#A52A2A','Café')" style="width:28px;height:28px;background:#A52A2A;border-radius:6px;cursor:pointer" title="Café"></span>
          <span onclick="setQuickColor('#FFA500','Naranja')" style="width:28px;height:28px;background:#FFA500;border-radius:6px;cursor:pointer" title="Naranja"></span>
          <span onclick="setQuickColor('#722F37','Vino')" style="width:28px;height:28px;background:#722F37;border-radius:6px;cursor:pointer" title="Vino"></span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cat-color')">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal('modal-cat-color');showToast('✅ Color guardado en catálogo')">✓ Guardar color</button>
    </div>
  </div>
</div>

<!-- Modal: Nuevo proveedor catálogo -->
<div class="modal-overlay" id="modal-cat-proveedor" onclick="closeOut(event,'modal-cat-proveedor')">
  <div class="modal" style="max-width:500px">
    <div class="modal-header"><div class="modal-title">🏭 Nuevo proveedor</div><button class="modal-close" onclick="closeModal('modal-cat-proveedor')">✕</button></div>
    <div class="modal-body">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Nombre del proveedor *</label><input class="form-control" placeholder="Razón social o nombre"></div>
        <div class="form-group"><label class="form-label">Teléfono *</label><input class="form-control" placeholder="222-555-0000"></div>
      </div>
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Correo electrónico</label><input class="form-control" type="email" placeholder="contacto@proveedor.com"></div>
        <div class="form-group"><label class="form-label">Estado</label><select class="form-control"><option>Activo</option><option>Inactivo</option></select></div>
      </div>
      <div class="form-group mb-12"><label class="form-label">Dirección</label><input class="form-control" placeholder="Calle, ciudad, estado..."></div>
      <div class="form-group"><label class="form-label">Notas (condiciones de pago, tiempos de entrega...)</label><textarea class="form-control" placeholder="Ej: Crédito a 30 días, entrega en 3-5 días hábiles"></textarea></div>
      <div class="notice notice-info mt-8" style="font-size:12px">✓ Aparecerá en el selector de Proveedor al registrar productos y compras.</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cat-proveedor')">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal('modal-cat-proveedor');showToast('✅ Proveedor guardado')">✓ Guardar proveedor</button>
    </div>
  </div>
</div>

<!-- Modal: Nuevo anaquel catálogo -->
<div class="modal-overlay" id="modal-cat-anaquel" onclick="closeOut(event,'modal-cat-anaquel')">
  <div class="modal" style="max-width:460px">
    <div class="modal-header"><div class="modal-title">🗄️ Nuevo anaquel</div><button class="modal-close" onclick="closeModal('modal-cat-anaquel')">✕</button></div>
    <div class="modal-body">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Código del anaquel *</label><input class="form-control" placeholder="Ej: A-1, B-2, D-1..."><div class="form-hint">Usa un código corto y claro</div></div>
        <div class="form-group"><label class="form-label">Tienda *</label><select class="form-control"><option>Sucursal 1 — Santa María Texmelucan</option><option>Sucursal 2 — Por definir</option></select></div>
      </div>
      <div class="form-group mb-12"><label class="form-label">Descripción / ubicación *</label><input class="form-control" placeholder="Ej: Anaquel D, sección 1 — área nueva"></div>
      <div class="notice notice-success" style="font-size:12px">✓ El anaquel quedará disponible para asignar productos en el inventario.</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cat-anaquel')">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal('modal-cat-anaquel');showToast('✅ Anaquel guardado')">✓ Guardar anaquel</button>
    </div>
  </div>
</div>

<!-- Modal: Nueva unidad -->
<div class="modal-overlay" id="modal-cat-unidad" onclick="closeOut(event,'modal-cat-unidad')">
  <div class="modal" style="max-width:420px">
    <div class="modal-header"><div class="modal-title">📏 Nueva unidad de medida</div><button class="modal-close" onclick="closeModal('modal-cat-unidad')">✕</button></div>
    <div class="modal-body">
      <div class="form-row form-2">
        <div class="form-group"><label class="form-label">Abreviatura *</label><input class="form-control" placeholder="Ej: lb, oz, m..."></div>
        <div class="form-group"><label class="form-label">Nombre completo *</label><input class="form-control" placeholder="Ej: Libras, Onzas, Metros..."></div>
      </div>
      <div class="form-group"><label class="form-label">Descripción</label><input class="form-control" placeholder="Para qué tipo de producto se usa"></div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-cat-unidad')">Cancelar</button>
      <button class="btn btn-primary" onclick="closeModal('modal-cat-unidad');showToast('✅ Unidad guardada')">✓ Guardar unidad</button>
    </div>
  </div>
</div>

<!-- Modal: Confirmar acción -->
<div class="modal-overlay" id="modal-confirm" onclick="closeOut(event,'modal-confirm')">
  <div class="modal" style="max-width:420px">
    <div class="modal-header"><div class="modal-title">⚠️ Confirmar acción</div><button class="modal-close" onclick="closeModal('modal-confirm')">✕</button></div>
    <div class="modal-body">
      <p class="text-sm" id="confirm-msg" style="margin-bottom:12px">¿Estás seguro de continuar?</p>
      <div class="notice notice-warn">Esta acción no se puede deshacer fácilmente.</div>
    </div>
    <div class="modal-footer">
      <button class="btn" onclick="closeModal('modal-confirm')">Cancelar</button>
      <button class="btn btn-danger" id="confirm-ok">Sí, continuar</button>
    </div>
  </div>
</div>

<!-- Modal: Venta registrada OK -->
<div class="modal-overlay" id="modal-venta-ok" onclick="closeOut(event,'modal-venta-ok')">
  <div class="modal" style="max-width:460px">
    <div class="modal-header" style="background:var(--accentbg);border-radius:var(--radius-xl) var(--radius-xl) 0 0">
      <div class="modal-title" style="color:var(--accent)">✅ Venta registrada exitosamente</div>
      <button class="modal-close" onclick="closeModal('modal-venta-ok')">✕</button>
    </div>
    <div class="modal-body" style="text-align:center;padding:30px 24px">
      <div style="font-size:56px;margin-bottom:12px">🛒</div>
      <div style="font-family:'Segoe UI',Arial,sans-serif;font-weight:700;font-size:22px;margin-bottom:4px" id="venta-ok-total">$0.00</div>
      <div class="text-muted text-sm mb-16">Folio generado automáticamente · Inventario actualizado</div>
      <div class="flex gap-8" style="justify-content:center;flex-wrap:wrap">
        <button class="btn" onclick="closeModal('modal-venta-ok');showToast('Ticket listo para imprimir')">🖨 Imprimir ticket</button>
        <button class="btn" onclick="closeModal('modal-venta-ok');showToast('Enviando por WhatsApp...')">📤 WhatsApp</button>
        <button class="btn btn-primary" onclick="closeModal('modal-venta-ok')">← Nueva venta</button>
      </div>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>
<script>
let _sessionUser = null;
const storeLabels=['📍 Todas las tiendas','📍 Sucursal 1 — Santa María Texmelucan','📍 Sucursal 2 — Por definir'];
const pages={
  'dashboard':'Dashboard','inventario':'Inventario','ventas':'Ventas','compras':'Compras',
  'clientes':'Clientes','alertas':'Alertas','localizador':'Localizador','reportes':'Reportes',
  'usuarios':'Usuarios','catalogos':'Catálogos','respaldo':'Respaldo',
  'emp-ventas':'Registrar venta','emp-inventario':'Ver inventario','cliente-perfil':'Perfil de cliente','emp-dashboard':'Mi espacio de trabajo'
};

async function doLogin(){
  const usuario = document.getElementById('login-usuario').value.trim();
  const contrasena = document.getElementById('login-password').value;
  if (!usuario || !contrasena) {
    showToast('Completa usuario y contraseña.');
    return;
  }

  const fd = new FormData();
  fd.append('usuario', usuario);
  fd.append('contrasena', contrasena);

  let loginData;
  try {
    const resp = await fetch('index.php?action=login', {
      method: 'POST',
      credentials: 'same-origin',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });
    loginData = await resp.json();
  } catch (err) {
    console.error('Login error:', err);
    showToast('❌ No se pudo iniciar sesión. Revisa tu conexión.');
    return;
  }

  if (!loginData?.ok) {
    showToast('❌ ' + (loginData.msg || 'Credenciales incorrectas.'));
    return;
  }

  loginSuccess(loginData);
}

function loginSuccess(data){
  _sessionUser = data;
  document.getElementById('login-page').style.display='none';
  document.getElementById('app').style.display='flex';
  const isAdmin = data.rol === 'superadmin' || data.rol === 'admin';
  document.getElementById('admin-nav').style.display = isAdmin ? 'block' : 'none';
  document.getElementById('emp-nav').style.display = isAdmin ? 'none' : 'block';
  document.getElementById('store-switcher-wrap').style.display = isAdmin ? 'block' : 'none';
  document.getElementById('emp-badge-top').style.display = isAdmin ? 'none' : 'inline';
  const name = data.nombre || data.correo || 'Usuario';
  const av = name.split(' ').map(w => w[0]).slice(0,2).join('').toUpperCase();
  const roleLabel = data.rol === 'superadmin' ? 'Superadmin' : data.rol === 'admin' ? 'Admin' : 'Empleado';
  document.getElementById('sb-av').textContent = av;
  document.getElementById('sb-name').textContent = name;
  document.getElementById('sb-role').textContent = roleLabel;
  document.getElementById('tu-av').textContent = av;
  document.getElementById('tu-name').textContent = name;
  document.getElementById('sb-version').textContent = 'Inventario v3.0';
  if (isAdmin) {
    clearScreens();
    document.getElementById('screen-dashboard').classList.add('active');
    document.getElementById('page-title').textContent = 'Dashboard';
  } else {
    clearScreens();
    document.getElementById('screen-emp-dashboard').classList.add('active');
    document.getElementById('page-title').textContent = 'Mi espacio de trabajo';
  }
  document.querySelectorAll('.sb-item').forEach(i => i.classList.remove('active'));
  const firstNav = document.querySelector('#' + (isAdmin ? 'admin' : 'emp') + '-nav .sb-item:not(.locked)');
  if (firstNav) firstNav.classList.add('active');
  const contentArea = document.querySelector('.content');
  if (contentArea) contentArea.scrollTop = 0;
  window.scrollTo(0,0);
}

async function doRegistro(){
  const nombre = document.getElementById('registro-nombre').value.trim();
  const correo = document.getElementById('registro-correo').value.trim();
  const contrasena = document.getElementById('registro-contrasena').value;
  const confirmar = document.getElementById('registro-confirmar').value;
  const rol = document.getElementById('registro-rol').value;
  const tienda = document.getElementById('registro-tienda').value;

  if (!nombre || !correo || !contrasena || !confirmar) {
    showToast('Completa todos los campos de registro.');
    return;
  }
  if (contrasena !== confirmar) {
    showToast('Las contraseñas no coinciden.');
    return;
  }
  if (contrasena.length < 6) {
    showToast('La contraseña debe tener al menos 6 caracteres.');
    return;
  }

  const fd = new FormData();
  fd.append('nombre', nombre);
  fd.append('correo', correo);
  fd.append('contrasena', contrasena);
  fd.append('confirmar', confirmar);
  fd.append('rol', rol);
  fd.append('id_tienda', tienda);

  try {
    const resp = await fetch('index.php?action=registro', {
      method: 'POST',
      credentials: 'same-origin',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });
    const data = await resp.json();
    if (!data.ok) {
      showToast('❌ ' + data.msg);
      return;
    }
    showToast('✅ ' + data.msg);
    // Limpiar campos del formulario
    document.getElementById('registro-nombre').value = '';
    document.getElementById('registro-correo').value = '';
    document.getElementById('registro-contrasena').value = '';
    document.getElementById('registro-confirmar').value = '';
    document.getElementById('registro-rol').value = 'empleado';
    document.getElementById('registro-tienda').value = '1';
    closeModal('modal-registro');
  } catch (err) {
    console.error('Registro error:', err);
    showToast('❌ No se pudo completar el registro.');
  }
}

async function doRecuperar(){
  const correo = document.getElementById('recuperar-correo').value.trim();
  const info = document.getElementById('recuperar-info');
  info.style.display = 'none';
  info.textContent = '';

  if (!correo) {
    showToast('Ingresa tu correo para recuperar contraseña.');
    return;
  }

  const fd = new FormData();
  fd.append('correo', correo);

  try {
    const resp = await fetch('index.php?action=recuperar_password', {
      method: 'POST',
      credentials: 'same-origin',
      body: fd,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });

    const text = await resp.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch (parseErr) {
      console.error('Recuperar contraseña parse error:', parseErr, 'response text:', text);
      showToast('❌ Error de servidor. Revisa la consola.');
      return;
    }

    if (!resp.ok) {
      showToast('❌ ' + (data.msg || 'Error HTTP ' + resp.status));
      return;
    }
    if (!data.ok) {
      showToast('❌ ' + data.msg);
      return;
    }
    showToast('✅ ' + data.msg);
    info.textContent = 'Tu contraseña temporal es: ' + data.tempPassword;
    info.style.display = 'block';
  } catch (err) {
    console.error('Recuperar contraseña error:', err);
    showToast('❌ No se pudo procesar la recuperación.');
  }
}


function doLogout(){
  fetch('index.php?action=logout', {
    method: 'POST',
    credentials: 'same-origin'
  }).catch(() => {});
  document.getElementById('app').style.display='none';
  document.getElementById('login-page').style.display='flex';
  document.getElementById('login-usuario').value='';
  document.getElementById('login-password').value='';
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
  // ── Lazy-load datos de la BD al navegar ──────────────────
  if(name==='clientes')     cargarClientes();
  if(name==='ventas')       cargarDatosVentas();
  if(name==='inventario')   cargarInventario();
  if(name==='respaldo')     cargarHistorialRespaldo();
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
const catalogProducts=[
  {id:'acr-blanco',name:'Hilo Acrílico Blanco 500g',tipo:'Acrílico',price:65,stock:12},
  {id:'nyl-negro',name:'Hilo Nylon Negro 1kg',tipo:'Nylon',price:110,stock:2,low:true},
  {id:'alg-rojo',name:'Hilo Algodón Rojo 250g',tipo:'Algodón',price:78,stock:4,min:true},
  {id:'pol-azul',name:'Hilo Poliéster Azul 500g',tipo:'Poliéster',price:55,stock:18},
  {id:'lan-verde',name:'Hilo Lana Verde 100g',tipo:'Lana',price:130,stock:9},
  {id:'sed-lav',name:'Hilo Seda Lavanda 50g',tipo:'Seda',price:280,stock:4},
  {id:'mer-rosa',name:'Hilo Mercerizado Rosa 100g',tipo:'Mercerizado',price:95,stock:1,low:true},
];

function addToCart(screenId){
  const prodSel=document.getElementById('prod-sel-'+screenId);
  const cantInp=document.getElementById('cant-inp-'+screenId);
  if(!prodSel||!cantInp) return;
  const opt = prodSel.options[prodSel.selectedIndex];
  const prodId = prodSel.value || (opt?.dataset?.id || '');
  if(!prodId || !opt){
    showToast('Selecciona un producto primero','warn');
    return;
  }
  const prod = {
    id: prodId,
    name: opt.dataset.name || opt.text,
    price: parseFloat(opt.dataset.price) || 0,
    stock: parseFloat(opt.dataset.stock) || 0,
    unidad: opt.dataset.unidad || 'u'
  };
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
  const subtotalEl=document.getElementById('cart-subtotal-'+screenId);
  const descuentoEl=document.getElementById('cart-descuento-'+screenId);
  const totalEl=document.getElementById('cart-total-'+screenId);
  if(!tbody) return;
  if(cart.length===0){
    tbody.innerHTML='<tr><td colspan="5" style="text-align:center;color:var(--hint);padding:20px">Sin productos agregados</td></tr>';
    if(subtotalEl) subtotalEl.textContent='$0.00';
    if(totalEl) totalEl.textContent='$0.00';
    return;
  }
  let subtotal=0;
  tbody.innerHTML=cart.map(item=>{
    const sub=item.price*item.cant;
    subtotal+=sub;
    return `<tr><td>${item.name}</td><td>${item.cant} kg</td><td>$${item.price.toFixed(2)}</td>
      <td><strong>$${sub.toFixed(2)}</strong></td>
      <td><button class="btn btn-xs btn-danger" onclick="removeFromCart('${item.id}','${screenId}')">✕</button></td></tr>`;
  }).join('');
  const descuentoPorcentaje=parseFloat(descuentoEl?.value||0);
  const descuentoMonto=(subtotal*descuentoPorcentaje)/100;
  const total=subtotal-descuentoMonto;
  if(subtotalEl) subtotalEl.textContent='$'+subtotal.toLocaleString('es-MX',{minimumFractionDigits:2});
  if(totalEl) totalEl.textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
}

function actualizarTotalConDescuento(screenId){
  renderCart(screenId);
}

function filtrarMaterialesVentas(){
  const materialSelec=document.getElementById('filtro-material-ventas')?.value||'';
  const prodSel=document.getElementById('prod-sel-ventas');
  if(!prodSel) return;
  Array.from(prodSel.options).forEach(opt=>{
    if(opt.value==='') return;
    const tipo=opt.dataset.tipo||'';
    if(materialSelec==='' || tipo===materialSelec){
      opt.style.display='';
    } else {
      opt.style.display='none';
    }
  });
}

function confirmarVenta(screenId){
  if(cart.length===0){showToast('Agrega productos primero','warn');return;}
  const subtotal=cart.reduce((s,c)=>s+c.price*c.cant,0);
  const descuentoEl=document.getElementById('cart-descuento-'+screenId);
  const descuentoPorcentaje=parseFloat(descuentoEl?.value||0);
  const descuentoMonto=(subtotal*descuentoPorcentaje)/100;
  const total=subtotal-descuentoMonto;
  cart.length=0;
  renderCart(screenId);
  openModal('modal-venta-ok');
  document.getElementById('venta-ok-total').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
}

// ── Password toggle ─────────────────────────────────────
function togglePassword(inputId){
  const input = document.getElementById(inputId);
  const type = input.type === 'password' ? 'text' : 'password';
  input.type = type;
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

function registrarVentaFinal(){
  if(cart.length===0){showToast('Agrega productos primero','warn');return;}
  const tienda=document.getElementById('venta-tienda');
  const cliente=document.getElementById('venta-cliente');
  const notas=document.getElementById('venta-notas');
  const selectedCliente = cliente?.options[cliente.selectedIndex];
  const cId = selectedCliente?.dataset?.id || cliente?.value || '';
  const cName = selectedCliente?.textContent || '—';
  const tName = tienda?.options[tienda.selectedIndex]?.textContent || 'Sucursal 1';
  const items = cart.map(item => ({
    id_producto: item.id,
    cantidad: item.cant,
    precio_unit: item.price,
  }));
  const total = cart.reduce((s,c)=>s+c.price*c.cant,0);

  const fd = new FormData();
  fd.append('id_cliente', cId || '');
  fd.append('id_tienda', tienda?.value || '1');
  fd.append('notas', notas?.value || '');
  fd.append('items', JSON.stringify(items));

  apiPost('venta_registrar', fd).then(res=>{
    if (!res || !res.ok) {
      showToast('❌ ' + (res?.msg || res?.error || 'Error al registrar venta'), 'warn');
      return;
    }
    const folio = res.id_venta ? String(res.id_venta).padStart(4,'0') : '----';
    const now = new Date();
    const fecha = `${String(now.getDate()).padStart(2,'0')}/${String(now.getMonth()+1).padStart(2,'0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
    clearScreens();
    const sc=document.getElementById('screen-venta-confirmada');
    if(sc){
      sc.classList.add('active');
      sc.querySelector('.venta-folio').textContent='Venta registrada exitosamente — Folio #'+folio+' · $'+total.toLocaleString('es-MX',{minimumFractionDigits:2})+' · '+fecha;
      sc.querySelector('.venta-cliente-info').textContent='Cliente: '+cName+' · '+tName+' · '+fecha;
      const tbody=sc.querySelector('.venta-items-body');
      tbody.innerHTML=cart.map(item=>`<tr><td>${item.name}</td><td>${item.cant} kg</td><td>$${item.price.toFixed(2)}</td><td><strong>$${(item.price*item.cant).toFixed(2)}</strong></td><td>—</td></tr>`).join('');
      sc.querySelector('.venta-subtotal').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
      sc.querySelector('.venta-total-main').textContent='$'+total.toLocaleString('es-MX',{minimumFractionDigits:2});
      const ticketSide=sc.querySelector('.ticket-side');
      if(ticketSide){
        ticketSide.innerHTML=`
          <div style="text-align:center;margin-bottom:10px;padding-bottom:8px;border-bottom:1px dashed #ccc">
            <div style="font-weight:700">HLazcano — Prendas de Punto</div>
            <div style="font-size:10px;color:#777">${tName}</div>
            <div style="font-size:10px;color:#777">Tel: 222-555-0000</div>
          </div>
          <div style="font-size:10px;margin-bottom:8px;line-height:1.9">
            <div>Folio: <strong>#${folio}</strong></div>
            <div>Fecha: <strong>${fecha}</strong></div>
            <div>Cliente: <strong>${cName==='—'?'—':cName}</strong></div>
          </div>
          <div style="border-top:1px dashed #ccc;padding-top:8px">
            ${cart.map(item=>`<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:2px"><span>${item.name.replace('Hilo ','')}</span><span>$${(item.price*item.cant).toFixed(2)}</span></div><div style="font-size:10px;color:#aaa;margin-bottom:5px">${item.cant} kg</div>`).join('')}
          </div>
          <div style="border-top:1px dashed #ccc;margin-top:8px;padding-top:8px">
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:14px"><span>TOTAL</span><span>$${total.toLocaleString('es-MX',{minimumFractionDigits:2})}</span></div>
          </div>`;
      }
      cart.length=0;
      renderCart('ventas');
      updateTicketPreview();
    }
    document.getElementById('page-title').textContent='Confirmar venta';
    const contentArea=document.querySelector('.content');
    if(contentArea) contentArea.scrollTop=0;
    window.scrollTo(0,0);
  }).catch(err=>{
    console.error('Error registrar venta:', err);
    showToast('❌ Error al registrar venta.', 'warn');
  });
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

// ── Función para generar respaldo ────────────────────────
async function generarRespaldo() {
  const btn = document.getElementById('btn-respaldo');
  const btnText = document.getElementById('btn-text');
  const btnLoading = document.getElementById('btn-loading');
  
  if (!btn || !btnText || !btnLoading) return;
  
  // Cambiar a estado de carga
  btn.disabled = true;
  btnText.style.display = 'none';
  btnLoading.style.display = 'inline';
  
  try {
    const response = await fetch('backup.php', {
      method: 'POST',
      credentials: 'same-origin'
    });
    
    if (!response.ok) {
      throw new Error('Error en la respuesta del servidor');
    }
    
    // Crear un blob con la respuesta y descargar
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'respaldo_hlazcano_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.sql';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
    
    showToast('✅ Respaldo generado y descargado exitosamente');
    
    // Recargar historial
    cargarHistorialRespaldo();
    
  } catch (error) {
    console.error('Error generando respaldo:', error);
    showToast('❌ Error al generar el respaldo', 'warn');
  } finally {
    // Restaurar estado del botón
    btn.disabled = false;
    btnText.style.display = 'inline';
    btnLoading.style.display = 'none';
  }
}

// ── Cargar historial de respaldos ─────────────────────────
async function cargarHistorialRespaldo() {
  try {
    const response = await fetch('index.php?action=respaldo_historial', {
      credentials: 'same-origin'
    });
    const data = await response.json();
    
    if (data.ok) {
      // Actualizar información del último respaldo
      const ultimoInfo = document.getElementById('ultimo-respaldo-info');
      if (ultimoInfo && data.ultimo) {
        const fecha = new Date(data.ultimo.fecha_generacion);
        ultimoInfo.innerHTML = `Último respaldo: <strong>${fecha.toLocaleDateString('es-ES')} ${fecha.toLocaleTimeString('es-ES')}</strong>`;
      } else {
        ultimoInfo.innerHTML = 'Último respaldo: <strong>Nunca</strong>';
      }
      
      // Mostrar historial
      const tbody = document.getElementById('tbody-historial');
      if (tbody && data.historial) {
        if (data.historial.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--hint);padding:20px">No hay respaldos registrados</td></tr>';
        } else {
          tbody.innerHTML = data.historial.map(log => {
            const fecha = new Date(log.fecha_generacion);
            const tamano = (log.tamano_bytes / 1024 / 1024).toFixed(2) + ' MB';
            const estado = log.exito ? '<span class="badge badge-ok">✅ Exitoso</span>' : '<span class="badge badge-danger">❌ Fallido</span>';
            return `<tr>
              <td>${fecha.toLocaleDateString('es-ES')} ${fecha.toLocaleTimeString('es-ES')}</td>
              <td>${log.nombre_usuario}</td>
              <td>${log.nombre_archivo}</td>
              <td>${tamano}</td>
              <td>${estado}</td>
            </tr>`;
          }).join('');
        }
      }
    }
  } catch (error) {
    console.error('Error cargando historial:', error);
  }
}</script>
<script src="public/js/crud-extra.js?v=2"></script>
</body>
</html>
