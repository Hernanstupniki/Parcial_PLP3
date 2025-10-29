<?php
declare(strict_types=1);

function hs_conectar(): PDO {
  $host = '127.0.0.1';
  $db   = 'hs_foodexpress_db';
  $user = 'root';
  $pass = ''; // ajustar
  $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
  return $pdo;
}
