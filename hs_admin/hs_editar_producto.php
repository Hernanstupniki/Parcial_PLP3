<?php
declare(strict_types=1);
require __DIR__ . '/../hs_includes/hs_conexion.php';
$pdo = hs_conectar();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: hs_panel.php'); exit; }

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
      UPDATE hs_productos
      SET nombre=:n, descripcion=:d, precio=:p, imagen=:i, categoria_id=:c, activo=:a
      WHERE id=:id
    ");
    $stmt->execute([
      ':n'=>$nombre, ':d'=>$desc, ':p'=>$precio, ':i'=>$img,
      ':c'=>$cat, ':a'=>$activo, ':id'=>$id
    ]);
    header('Location: hs_panel.php'); exit;
  } else {
    $error = "Completa los campos obligatorios.";
  }
}

$stmt = $pdo->prepare("SELECT * FROM hs_productos WHERE id=:id");
$stmt->execute([':id'=>$id]);
$p = $stmt->fetch();
if (!$p) { header('Location: hs_panel.php'); exit; }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar producto</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../hs_css/hs_estilos.css">
</head>
<body>
<main class="hs-container">
  <h1>Editar producto #<?= (int)$p['id']; ?></h1>
  <?php if (!empty($error)) echo "<p class='hs-error'>$error</p>"; ?>
  <form method="post" class="hs-form">
    <label>Nombre* <input name="nombre" value="<?= htmlspecialchars($p['nombre']); ?>" required></label>
    <label>Descripción <input name="descripcion" value="<?= htmlspecialchars((string)$p['descripcion']); ?>"></label>
    <label>Precio* <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars((string)$p['precio']); ?>" required></label>
    <label>Imagen (ruta) <input name="imagen" value="<?= htmlspecialchars((string)$p['imagen']); ?>"></label>
    <label>Categoría*
      <select name="categoria_id" required>
        <?php foreach($cats as $c): ?>
          <option value="<?= (int)$c['id'];?>" <?= $c['id']==$p['categoria_id']?'selected':''; ?>>
            <?= htmlspecialchars($c['nombre']);?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label><input type="checkbox" name="activo" <?= $p['activo'] ? 'checked':''; ?>> Activo</label>
    <button class="hs-btn">Guardar cambios</button>
    <a class="hs-btn-sec" href="hs_panel.php">Cancelar</a>
  </form>
</main>
</body>
</html>
