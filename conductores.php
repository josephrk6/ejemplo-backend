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
        $tipo = $_GET['tipo'] ?? 'lista';
        
        if ($tipo === 'lista') {
            $stmt = $pdo->query("SELECT * FROM conductores");
            $conductores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($conductores, JSON_UNESCAPED_UNICODE);
        }
        
        if ($tipo === 'estadisticas') {
            $total = $pdo->query("SELECT COUNT(*) FROM conductores")->fetchColumn();
            $activos = $pdo->query("SELECT COUNT(*) FROM conductores WHERE estado = 'Activo'")->fetchColumn();
            $inactivos = $pdo->query("SELECT COUNT(*) FROM conductores WHERE estado = 'Inactivo'")->fetchColumn();
            
            echo json_encode([
                'total' => $total,
                'activos' => $activos,
                'inactivos' => $inactivos
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO conductores (nombre, estado) VALUES (?, ?)");
        $stmt->execute([$data['nombre'], $data['estado']]);
        echo json_encode(['mensaje' => 'Conductor creado correctamente'], JSON_UNESCAPED_UNICODE);
    }

    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("DELETE FROM conductores WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(['mensaje' => 'Conductor eliminado correctamente'], JSON_UNESCAPED_UNICODE);
    }

    if ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE conductores SET nombre = ?, estado = ? WHERE id = ?");
        $stmt->execute([$data['nombre'], $data['estado'], $data['id']]);
        echo json_encode(['mensaje' => 'Conductor actualizado correctamente'], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>