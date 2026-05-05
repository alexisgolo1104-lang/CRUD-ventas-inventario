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

