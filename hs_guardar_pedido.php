<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/hs_includes/hs_conexion.php';

$items = array_values($_SESSION['hs_carrito'] ?? []);
if (!$items) { header('Location: hs_menu.php'); exit; }

// Datos del formulario
$nombre    = trim($_POST['nombre'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas     = trim($_POST['notas'] ?? '');

if ($nombre === '' || $telefono === '' || $direccion === '') {
  http_response_code(400);
  echo "Faltan datos obligatorios.";
  exit;
}

// Armar mapa cantidad por producto desde la sesión (límite 0–99)
$qtyById = [];
foreach ($items as $it) {
  $id = (int)$it['id'];
  $qty = max(0, min(99, (int)($it['cantidad'] ?? 0)));
  if ($id > 0 && $qty > 0) $qtyById[$id] = $qty;
}
if (!$qtyById) { header('Location: hs_menu.php'); exit; }

$pdo = hs_conectar();
$pdo->beginTransaction();

try {
  // Traer precios oficiales desde la BD (solo activos)
  $in  = implode(',', array_fill(0, count($qtyById), '?'));
  $sql = "SELECT id, nombre, precio FROM hs_productos WHERE activo=1 AND id IN ($in)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array_keys($qtyById));
  $productos = $stmt->fetchAll();

  if (!$productos) throw new RuntimeException('Productos no disponibles.');

  // Calcular total solo con datos del servidor
  $total = 0.0;
  $lineas = [];
  foreach ($productos as $p) {
    $id  = (int)$p['id'];
    $nom = (string)$p['nombre'];
    $pu  = (float)$p['precio'];
    $q   = (int)$qtyById[$id];
    $sub = $pu * $q;
    $total += $sub;
    $lineas[] = [
      'producto_id' => $id,
      'nombre'      => $nom,
      'precio'      => $pu,
      'cantidad'    => $q,
      'subtotal'    => $sub,
    ];
  }

  // Insert en pedidos
  $ins = $pdo->prepare("
    INSERT INTO hs_pedidos (nombre_cliente, telefono, direccion, notas, total)
    VALUES (:n, :t, :d, :no, :tot)
  ");
  $ins->execute([
    ':n'=>$nombre, ':t'=>$telefono, ':d'=>$direccion, ':no'=>$notas, ':tot'=>$total
  ]);
  $pedido_id = (int)$pdo->lastInsertId();

  // Insert detalle por cada línea
  $det = $pdo->prepare("
    INSERT INTO hs_detalle_pedido
      (pedido_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal)
    VALUES (:p, :prod, :nom, :pu, :cant, :sub)
  ");
  foreach ($lineas as $l) {
    $det->execute([
      ':p'    => $pedido_id,
      ':prod' => $l['producto_id'],
      ':nom'  => $l['nombre'],
      ':pu'   => $l['precio'],
      ':cant' => $l['cantidad'],
      ':sub'  => $l['subtotal'],
    ]);
  }

  $pdo->commit();
  $_SESSION['hs_carrito'] = [];

  echo "<!doctype html><meta charset='utf-8'><link rel='stylesheet' href='hs_css/hs_estilos.css'>
  <div class='hs-confirm'><h1>¡Pedido confirmado!</h1>
  <p>Gracias, ".htmlspecialchars($nombre).". Tu número de pedido es <strong>#{$pedido_id}</strong>.</p>
  <p>Total: <strong>$".number_format($total,2,',','.')."</strong></p>
  <a class='hs-btn' href='hs_menu.php'>Volver al menú</a></div>";

} catch (Throwable $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo "Error al guardar el pedido.";
  // error_log($e->getMessage());
}
