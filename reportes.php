<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db   = getenv('MYSQLDATABASE') ?: 'ejemplo-backend';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("
        SELECT r.id, r.descripcion, r.tipo, r.estado, r.fecha,
               v.pasajero, v.origen, v.destino
        FROM reportes r
        JOIN viajes v ON r.viaje_id = v.id
        ORDER BY r.fecha DESC
    ");
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reportes, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>