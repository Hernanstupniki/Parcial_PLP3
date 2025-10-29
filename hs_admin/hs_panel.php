<?php
declare(strict_types=1);
require __DIR__ . '/../hs_includes/hs_conexion.php';
$pdo = hs_conectar();

$prods = $pdo->query("
  SELECT p.id, p.nombre, p.precio, p.activo, c.nombre AS categoria
  FROM hs_productos p
  JOIN hs_categorias c ON c.id = p.categoria_id
  ORDER BY p.id DESC
")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../hs_css/hs_estilos.css">
</head>
<body>
<main class="hs-container">
  <h1>Productos</h1>
  <a class="hs-btn" href="hs_agregar_producto.php">Agregar producto</a>
  <table class="hs-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Activo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($prods as $p): ?>
      <tr>
        <td><?= (int)$p['id']; ?></td>
        <td><?= htmlspecialchars($p['nombre']); ?></td>
        <td>$<?= number_format((float)$p['precio'], 2, ',', '.'); ?></td>
        <td><?= htmlspecialchars($p['categoria']); ?></td>
        <td><?= $p['activo'] ? 'Sí' : 'No'; ?></td>
        <td>
          <a class="hs-link" href="hs_editar_producto.php?id=<?= (int)$p['id']; ?>">Editar</a> |
          <?php if ($p['activo']): ?>
            <a class="hs-link" href="hs_toggle_producto.php?id=<?= (int)$p['id']; ?>&a=0">Desactivar</a>
          <?php else: ?>
            <a class="hs-link" href="hs_toggle_producto.php?id=<?= (int)$p['id']; ?>&a=1">Activar</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body>
</html>
