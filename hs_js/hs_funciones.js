/**
 * FoodExpress – Interactividad Nivel 2
 * - Agregar/Quitar/Actualizar productos sin recargar (fetch + JSON)
 * - Recalcular subtotales y total en vivo
 * - Validar cantidades (min=0, max=99)
 * - Actualizar contador de ítems (badge) en el header
 */
const HS_MAX_QTY = 99;

function hs_setBadge(count) {
  const badge = document.getElementById('hs_cart_badge');
  if (badge && typeof count === 'number') badge.textContent = count;
}

async function hs_agregarAlCarrito(idProducto) {
  try {
    const res = await fetch(`hs_carrito.php?action=add&id=${idProducto}`, {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (!data?.ok) throw new Error('No se pudo agregar');
    hs_setBadge(data.count);
  } catch (e) {
    alert('No se pudo agregar al carrito.');
    console.error(e);
  }
}

async function hs_quitarItem(idProducto) {
  await hs_actualizarCantidad(idProducto, 0);
}

async function hs_actualizarCantidad(idProducto, cant) {
  let cantidad = parseInt(cant, 10);
  if (Number.isNaN(cantidad)) cantidad = 0;
  if (cantidad < 0) cantidad = 0;
  if (cantidad > HS_MAX_QTY) cantidad = HS_MAX_QTY;

  try {
    const form = new FormData();
    form.append('id', String(idProducto));
    form.append('cantidad', String(cantidad));

    const res = await fetch(`hs_carrito.php?action=update`, {
      method: 'POST',
      body: form,
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (!data?.ok) throw new Error('No se pudo actualizar');

    // Actualiza DOM: fila, totales y badge
    hs_setBadge(data.count);

    // Si eliminaron el item, quitamos la fila
    if (cantidad === 0) {
      const row = document.querySelector(`[data-hs-row="${idProducto}"]`);
      if (row) row.remove();
    } else {
      const subCell = document.querySelector(`[data-hs-subtotal="${idProducto}"]`);
      if (subCell) subCell.textContent = data.item_subtotal_fmt;
      const qtyInput = document.querySelector(`[data-hs-qty="${idProducto}"]`);
      if (qtyInput) qtyInput.value = String(data.item_qty);
    }

    // Total general
    const totalEl = document.getElementById('hs_total_general');
    if (totalEl) totalEl.textContent = data.total_fmt;

    // Si ya no hay items, recargar vista mínima (o mostrás un vacío)
    if (data.count === 0) location.href = 'hs_carrito.php';
  } catch (e) {
    alert('No se pudo actualizar el carrito.');
    console.error(e);
  }
}

async function hs_vaciarCarrito() {
  try {
    const res = await fetch(`hs_carrito.php?action=clear`, {
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    if (!data?.ok) throw new Error('No se pudo vaciar');
    hs_setBadge(0);
    location.href = 'hs_carrito.php';
  } catch (e) {
    alert('No se pudo vaciar el carrito.');
    console.error(e);
  }
}
