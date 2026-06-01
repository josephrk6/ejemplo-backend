<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$db   = 'ejemplo-backend';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("
            SELECT v.id, v.pasajero, c.nombre AS conductor, 
                   v.origen, v.destino, v.estado, v.fecha 
            FROM viajes v 
            JOIN conductores c ON v.conductor_id = c.id
            ORDER BY v.fecha DESC
        ");
        $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($viajes, JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>