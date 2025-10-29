<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/hs_includes/hs_conexion.php';
$pdo = hs_conectar();

if (!isset($_SESSION['hs_carrito'])) {
  $_SESSION['hs_carrito'] = [];
}

/** Utilidades JSON / Totales */
function hs_json(array $arr): void {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($arr);
  exit;
}
function hs_count_items(): int {
  return array_sum(array_column($_SESSION['hs_carrito'] ?? [], 'cantidad'));
}
function hs_total(): float {
  $t = 0.0;
  foreach (array_values($_SESSION['hs_carrito']) as $it) {
    $t += (float)$it['precio'] * (int)$it['cantidad'];
  }
  return $t;
}
function hs_fmt(float $n): string {
  return '$' . number_format($n, 2, ',', '.');
}

/** Endpoints dinámicos (AJAX) */
$action = $_GET['action'] ?? null;

if ($action === 'add') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id > 0) {
    $stmt = $pdo->prepare("SELECT id, nombre, precio FROM hs_productos WHERE id=:id AND activo=1");
    $stmt->execute([':id' => $id]);
    if ($prod = $stmt->fetch()) {
      if (!isset($_SESSION['hs_carrito'][$id])) {
        $_SESSION['hs_carrito'][$id] = [
          'id' => (int)$prod['id'],
          'nombre' => (string)$prod['nombre'],
          'precio' => (float)$prod['precio'],
          'cantidad' => 0
        ];
      }
      $_SESSION['hs_carrito'][$id]['cantidad']++;
    }
  }
  hs_json(['ok' => true, 'count' => hs_count_items()]);
}

if ($action === 'update') {
  $id  = (int)($_POST['id'] ?? 0);
  $qty = max(0, min(99, (int)($_POST['cantidad'] ?? 0)));
  if ($id && isset($_SESSION['hs_carrito'][$id])) {
    if ($qty === 0) {
      unset($_SESSION['hs_carrito'][$id]);
      $total = hs_total();
      hs_json([
        'ok' => true,
        'count' => hs_count_items(),
        'total' => $total,
        'total_fmt' => hs_fmt($total),
        'item_qty' => 0,
        'item_subtotal' => 0.0,
        'item_subtotal_fmt' => hs_fmt(0.0)
      ]);
    } else {
      $_SESSION['hs_carrito'][$id]['cantidad'] = $qty;
      $it  = $_SESSION['hs_carrito'][$id];
      $sub = (float)$it['precio'] * (int)$it['cantidad'];
      $total = hs_total();
      hs_json([
        'ok' => true,
        'count' => hs_count_items(),
        'total' => $total,
        'total_fmt' => hs_fmt($total),
        'item_qty' => (int)$it['cantidad'],
        'item_subtotal' => $sub,
        'item_subtotal_fmt' => hs_fmt($sub)
      ]);
    }
  }
  $total = hs_total();
  hs_json(['ok' => true, 'count' => hs_count_items(), 'total' => $total, 'total_fmt' => hs_fmt($total)]);
}

if ($action === 'clear') {
  $_SESSION['hs_carrito'] = [];
  hs_json(['ok' => true, 'count' => 0]);
}

/** Render HTML (vista carrito) */
$items = array_values($_SESSION['hs_carrito']);
$total = hs_total();
$carrito_count = hs_count_items();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Carrito</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="hs_css/hs_estilos.css">
  <script defer src="hs_js/hs_funciones.js"></script>
</head>
<body>
<header class="hs-header">
  <a href="hs_menu.php" class="hs-btn-sec">← Seguir comprando</a>
  <a class="hs-cart-link" href="hs_carrito.php">🛒 Carrito <span id="hs_cart_badge"><?= (int)$carrito_count ?></span></a>
</header>

<main class="hs-container">
  <h1>Tu carrito</h1>

  <?php if (!$items): ?>
    <p>El carrito está vacío.</p>
  <?php else: ?>
    <table class="hs-table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($items as $it):
        $id  = (int)$it['id'];
        $sub = (float)$it['precio'] * (int)$it['cantidad'];
      ?>
        <tr data-hs-row="<?= $id; ?>">
          <td><?= htmlspecialchars($it['nombre']); ?></td>
          <td><?= hs_fmt((float)$it['precio']); ?></td>
          <td style="max-width:110px">
            <input
              type="number"
              min="0" max="99"
              value="<?= (int)$it['cantidad']; ?>"
              data-hs-qty="<?= $id; ?>"
              oninput="hs_actualizarCantidad(<?= $id; ?>, this.value)">
          </td>
          <td data-hs-subtotal="<?= $id; ?>"><?= hs_fmt($sub); ?></td>
          <td>
            <button type="button" class="hs-link" onclick="hs_quitarItem(<?= $id; ?>)">Quitar</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <div class="hs-total">
      <strong>Total: <span id="hs_total_general"><?= hs_fmt($total); ?></span></strong>
    </div>

    <div class="hs-actions">
      <button type="button" class="hs-btn-sec" onclick="hs_vaciarCarrito()">Vaciar carrito</button>
      <a class="hs-btn" href="hs_checkout.php">Continuar al checkout</a>
    </div>
  <?php endif; ?>
</main>

<footer class="hs-footer">© FoodExpress</footer>
</body>
</html>
