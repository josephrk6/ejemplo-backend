<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db   = getenv('MYSQLDATABASE') ?: 'ejemplo-backend';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM conductores");
    $conductores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($conductores, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>