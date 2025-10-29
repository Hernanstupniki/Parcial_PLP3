/**
 * FoodExpress – Interactividad Niveles 2 y 4
 * - Carrito dinámico sin recarga (add/update/clear con fetch + JSON)
 * - Subtotales/total en vivo y validación de cantidades (0–99)
 * - Badge del carrito en el header
 * - Menú hamburguesa responsive + helpers de loading (aria-busy / overlay)
 */
const HS_MAX_QTY = 99;

/** ---------- Carrito (Nivel 2) ---------- */
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

    // Actualiza DOM
    hs_setBadge(data.count);

    if (cantidad === 0) {
      const row = document.querySelector(`[data-hs-row="${idProducto}"]`);
      if (row) row.remove();
    } else {
      const subCell = document.querySelector(`[data-hs-subtotal="${idProducto}"]`);
      if (subCell) subCell.textContent = data.item_subtotal_fmt;
      const qtyInput = document.querySelector(`[data-hs-qty="${idProducto}"]`);
      if (qtyInput) qtyInput.value = String(data.item_qty);
    }

    const totalEl = document.getElementById('hs_total_general');
    if (totalEl) totalEl.textContent = data.total_fmt;

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

/** ---------- UX helpers (Nivel 4) ---------- */
document.addEventListener('DOMContentLoaded', () => {
  const burger = document.getElementById('hs_burger');
  const nav = document.getElementById('hs_nav');
  if (burger && nav) {
    burger.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
});

function hs_btnLoading(btn, state = true){
  if (!btn) return;
  btn.setAttribute('aria-busy', state ? 'true' : 'false');
  btn.disabled = !!state;
}

function hs_showLoader(show = true){
  let el = document.getElementById('hs_global_loader');
  if (!el){
    el = document.createElement('div');
    el.id = 'hs_global_loader';
    el.innerHTML = '<div class="hs-spinner" role="status" aria-live="polite" aria-label="Cargando"></div>';
    document.body.appendChild(el);
  }
  el.classList.toggle('is-active', !!show);
}
