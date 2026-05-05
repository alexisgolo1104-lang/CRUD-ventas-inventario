<div id="screen-localizador" class="screen">
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

<!-- ══ REPORTES ═══════════════════════════════════════════ -->