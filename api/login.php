<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/AuthController.php';

// Verificar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Obtener datos del cuerpo de la solicitud (JSON)
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se recibieron los datos necesarios
if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

$authController = new AuthController();
$result = $authController->login($data['email'], $data['password']);

// Enviar respuesta según el resultado
if ($result['success']) {
    echo json_encode([
        'success' => true, 
        'email' => $result['email']
    ]);
} else {
    http_response_code(401); // Unauthorized
    echo json_encode([
        'success' => false, 
        'message' => $result['message']
    ]);
}
?>