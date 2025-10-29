<?php
declare(strict_types=1);
session_start();
$items = array_values($_SESSION['hs_carrito'] ?? []);
$total = 0.0;
foreach ($items as $it) $total += $it['precio'] * $it['cantidad'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Checkout</title>
  <link rel="stylesheet" href="hs_css/hs_estilos.css">
</head>
<body>
<header class="hs-header">
  <a href="hs_carrito.php" class="hs-btn-sec">← Volver al carrito</a>
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

    <form action="hs_guardar_pedido.php" method="post" class="hs-form">
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
</body>
</html>
