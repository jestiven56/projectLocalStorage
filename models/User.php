<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    
    // Constructor
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    // Método para verificar si las credenciales son válidas
    public function validateCredentials($email, $password) {
        // Preparar consulta con parámetros para prevenir inyección SQL
        $stmt = $this->conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificar si la contraseña coincide
            // Asumimos que las contraseñas están hasheadas con password_hash()
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
    
    // Método para obtener usuario por su ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, email, nombre FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    // Método para obtener usuario por su email
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, email, nombre FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }

    // Método para crear un nuevo usuario
    public function createUser($email) {
        // Verificar si el email ya está registrado
        if ($this->getUserByEmail($email)) {
            return [
                'success' => true,
                'message' => 'El correo electrónico ya está registrado'
            ];
        }
        
        // Hashear la contraseña
        $password = '123456'; // Contraseña por defecto
        $nombre = 'Usuario';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar consulta con parámetros para prevenir inyección SQL
        $stmt = $this->conn->prepare("INSERT INTO users (email, password, nombre) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $passwordHash, $nombre);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Usuario creado correctamente'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error al crear el usuario'
            ];
        }
    }

    
    // Cerrar conexión cuando ya no se necesita
    public function __destruct() {
        $this->conn->close();
    }
}
?>