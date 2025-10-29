<?php
declare(strict_types=1);
session_start();

$items = array_values($_SESSION['hs_carrito'] ?? []);
$total = 0.0;
foreach ($items as $it) $total += ((float)$it['precio']) * ((int)$it['cantidad']);
$carrito_count = array_sum(array_column($_SESSION['hs_carrito'] ?? [], 'cantidad'));
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="hs_css/hs_estilos.css">
</head>
<body>
<header class="hs-header">
  <div class="hs-nav-wrap">
    <button class="hs-burger" id="hs_burger" aria-label="Abrir menú" aria-controls="hs_nav" aria-expanded="false">☰</button>
    <a href="hs_menu.php" style="display:inline-flex;align-items:center;gap:8px;color:#fff;text-decoration:none">
      <img src="hs_assets/hs_logo.png" alt="FoodExpress" class="hs-logo">
      <strong>FoodExpress</strong>
    </a>
  </div>

  <nav class="hs-nav" id="hs_nav">
    <a href="hs_menu.php" class="<?= (basename($_SERVER['PHP_SELF'])==='hs_menu.php')?'hs-active':'';?>">Menú</a>
    <a href="hs_carrito.php" class="<?= (basename($_SERVER['PHP_SELF'])==='hs_carrito.php')?'hs-active':'';?>">Carrito</a>
    <a href="hs_checkout.php" class="<?= (basename($_SERVER['PHP_SELF'])==='hs_checkout.php')?'hs-active':'';?>">Checkout</a>
  </nav>

  <a class="hs-cart-link" href="hs_carrito.php">🛒 Carrito <span id="hs_cart_badge"><?= (int)$carrito_count ?></span></a>
</header>

<main class="hs-container">
  <h1>Finalizar pedido</h1>

  <?php if (!$items): ?>
    <p>Tu carrito está vacío.</p>
  <?php else: ?>
    <div class="hs-summary">
      <h3>Resumen</h3>
      <ul>
        <?php foreach($items as $it): ?>
          <li><?= htmlspecialchars($it['nombre']); ?> × <?= (int)$it['cantidad']; ?></li>
        <?php endforeach; ?>
      </ul>
      <p><strong>Total: $<?= number_format($total, 2, ',', '.'); ?></strong></p>
    </div>

    <form action="hs_guardar_pedido.php" method="post" class="hs-form" onsubmit="hs_btnLoading(this.querySelector('button[type=submit]'), true)">
      <label>Nombre y Apellido
        <input type="text" name="nombre" required>
      </label>
      <label>Teléfono
        <input type="tel" name="telefono" required>
      </label>
      <label>Dirección
        <input type="text" name="direccion" required>
      </label>
      <label>Notas
        <textarea name="notas" rows="3" placeholder="Aclaraciones de entrega"></textarea>
      </label>
      <button class="hs-btn" type="submit">Confirmar pedido</button>
    </form>
  <?php endif; ?>
</main>

<footer class="hs-footer">© FoodExpress</footer>

<script defer src="hs_js/hs_funciones.js"></script>
</body>
</html>
