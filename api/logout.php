<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/AuthController.php';

// Verificar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$authController = new AuthController();
$result = $authController->logout();

echo json_encode($result);
?>