<?php
declare(strict_types=1);
require __DIR__ . '/../hs_includes/hs_conexion.php';
$pdo = hs_conectar();

$cats = $pdo->query("SELECT id, nombre FROM hs_categorias ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $desc   = trim($_POST['descripcion'] ?? '');
  $precio = (float)($_POST['precio'] ?? 0);
  $img    = trim($_POST['imagen'] ?? '');
  $cat    = (int)($_POST['categoria_id'] ?? 0);
  $activo = isset($_POST['activo']) ? 1 : 0;

  if ($nombre !== '' && $precio > 0 && $cat > 0) {
    $stmt = $pdo->prepare("
      INSERT INTO hs_productos (nombre, descripcion, precio, imagen, categoria_id, activo)
      VALUES (:n,:d,:p,:i,:c,:a)
    ");
    $stmt->execute([
      ':n'=>$nombre, ':d'=>$desc, ':p'=>$precio,
      ':i'=>$img, ':c'=>$cat, ':a'=>$activo
    ]);
    header('Location: hs_panel.php'); exit;
  } else {
    $error = "Completa los campos obligatorios.";
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Agregar producto</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../hs_css/hs_estilos.css">
</head>
<body>
<main class="hs-container">
  <h1>Agregar producto</h1>
  <?php if (!empty($error)) echo "<p class='hs-error'>$error</p>"; ?>
  <form method="post" class="hs-form">
    <label>Nombre* <input name="nombre" required></label>
    <label>Descripción <input name="descripcion"></label>
    <label>Precio* <input type="number" step="0.01" name="precio" required></label>
    <label>Imagen (ruta) <input name="imagen" placeholder="hs_assets/mi_imagen.jpg"></label>
    <label>Categoría*
      <select name="categoria_id" required>
        <option value="">Seleccionar…</option>
        <?php foreach($cats as $c): ?>
          <option value="<?= (int)$c['id'];?>"><?= htmlspecialchars($c['nombre']);?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label><input type="checkbox" name="activo" checked> Activo</label>
    <button class="hs-btn">Guardar</button>
    <a class="hs-btn-sec" href="hs_panel.php">Cancelar</a>
  </form>
</main>
</body>
</html>
