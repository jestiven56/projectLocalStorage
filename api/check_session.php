<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/AuthController.php';

// Verificar que sea una solicitud GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$authController = new AuthController();
$result = $authController->checkSession();

echo json_encode($result);
?>