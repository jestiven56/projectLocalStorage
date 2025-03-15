<?php
// Configuración de la base de datos
define('DB_HOST', 'ovh1.clusters.zeabur.com');
define('DB_USER', 'root');
define('DB_PASS', 'rgxbP3jsMo2657f8q9T4YUAhelaI10wk');
define('DB_NAME', 'zeabur');
define('DB_PORT', 31833);


// Función para obtener la conexión a la base de datos
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer charset
    $conn->set_charset("utf8");
    
    return $conn;
}
?>