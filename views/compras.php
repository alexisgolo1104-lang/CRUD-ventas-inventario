<div id="screen-compras" class="screen">
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

<!-- ══ CLIENTES ═══════════════════════════════════════════ -->