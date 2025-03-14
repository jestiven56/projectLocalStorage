<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
        
        // Iniciar sesión si no está activa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Método para manejar el login
    public function login($email, $password) {
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Formato de correo electrónico inválido'
            ];
        }
        
        // Validar longitud de contraseña
        if (strlen($password) < 6) {
            return [
                'success' => false,
                'message' => 'La contraseña debe tener al menos 6 caracteres'
            ];
        }
        
        // Verificar credenciales en la base de datos
        $user = $this->userModel->validateCredentials($email, $password);
        
        if ($user) {
            // Guardar información de usuario en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            return [
                'success' => true,
                'email' => $user['email']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ];
        }
    }
    
    // Método para verificar si hay una sesión activa
    public function checkSession() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            return [
                'loggedIn' => true,
                'email' => $_SESSION['user_email']
            ];
        }
        
        return [
            'loggedIn' => false
        ];
    }
    
    // Método para cerrar sesión
    public function logout() {
        // Eliminar variables de sesión
        session_unset();
        // Destruir la sesión
        session_destroy();
        
        return [
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ];
    }

    public function createUser($email){

        // Verificar si el email ya está registrado
        if ($this->userModel->getUserByEmail($email)) {
            return [
                'success' => true,
                'message' => 'El correo electrónico ya está registrado'
            ];
        }
        
        // Crear usuario en la base de datos
        $result = $this->userModel->createUser($email);
        
        // Enviar respuesta según el resultado
        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'Usuario creado correctamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['message']
            ];

        } 
    }
}
?>