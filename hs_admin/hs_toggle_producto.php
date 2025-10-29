<?php
declare(strict_types=1);
require __DIR__ . '/../hs_includes/hs_conexion.php';
$pdo = hs_conectar();

$id = (int)($_GET['id'] ?? 0);
$a  = (int)($_GET['a'] ?? 0); // 1 = activar, 0 = desactivar

if ($id <= 0 || ($a !== 0 && $a !== 1)) {
  header('Location: hs_panel.php'); exit;
}

$stmt = $pdo->prepare("UPDATE hs_productos SET activo = :a WHERE id = :id");
$stmt->execute([':a' => $a, ':id' => $id]);

header('Location: hs_panel.php');
