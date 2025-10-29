<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/hs_includes/hs_conexion.php';
$pdo = hs_conectar();

$categoria = $_GET['categoria'] ?? 'todas';

$cats = $pdo->query("SELECT id, nombre, slug FROM hs_categorias ORDER BY nombre")->fetchAll();

if ($categoria === 'todas') {
  $stmt = $pdo->query("SELECT p.*, c.slug FROM hs_productos p JOIN hs_categorias c ON c.id=p.categoria_id WHERE p.activo=1 ORDER BY p.categoria_id, p.nombre");
  $productos = $stmt->fetchAll();
} else {
  $stmt = $pdo->prepare("SELECT p.*, c.slug FROM hs_productos p JOIN hs_categorias c ON c.id=p.categoria_id WHERE p.activo=1 AND c.slug = :slug ORDER BY p.nombre");
  $stmt->execute([':slug' => $categoria]);
  $productos = $stmt->fetchAll();
}
$carrito_count = array_sum(array_column($_SESSION['hs_carrito'] ?? [], 'cantidad'));
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>FoodExpress - Menú</title>
  <link rel="stylesheet" href="hs_css/hs_estilos.css">
  <script defer src="hs_js/hs_funciones.js"></script>
</head>
<body>
<header class="hs-header">
  <img src="hs_assets/hs_logo.png" alt="FoodExpress" class="hs-logo">
  <nav class="hs-nav">
    <a href="hs_menu.php" class="<?= $categoria==='todas'?'hs-active':'';?>">Todas</a>
    <?php foreach($cats as $c): ?>
      <a href="hs_menu.php?categoria=<?= htmlspecialchars($c['slug']); ?>"
         class="<?= $categoria===$c['slug']?'hs-active':'';?>"><?= htmlspecialchars($c['nombre']); ?></a>
    <?php endforeach; ?>
  </nav>
  <a class="hs-cart-link" href="hs_carrito.php">🛒 Carrito <span id="hs_cart_badge"><?= (int)$carrito_count ?></span></a>
</header>

<main class="hs-container">
  <h1>Menú</h1>
  <section class="hs-grid">
    <?php foreach($productos as $p): ?>
      <article class="hs-card">
        <img src="<?= htmlspecialchars($p['imagen'] ?: 'hs_assets/hs_banner.jpg'); ?>" alt="<?= htmlspecialchars($p['nombre']); ?>">
        <div class="hs-card-body">
          <h3><?= htmlspecialchars($p['nombre']); ?></h3>
          <p class="hs-desc"><?= htmlspecialchars($p['descripcion'] ?? ''); ?></p>
          <p class="hs-price">$<?= number_format((float)$p['precio'], 2, ',', '.'); ?></p>
          <button class="hs-btn" onclick="hs_agregarAlCarrito(<?= (int)$p['id']; ?>)">Agregar</button>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
</main>

<footer class="hs-footer">© FoodExpress</footer>
</body>
</html>
