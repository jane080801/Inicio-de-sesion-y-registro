<?php
require_once 'conexion.php';

try {
    $conexion = new Conexion();
    $conn = $conexion->getConexion();
    
    echo "✅ Conexión exitosa a la base de datos!<br>";
    
    // Verificar si la tabla existe
    $stmt = $conn->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "✅ La tabla 'usuarios' existe<br>";
    } else {
        echo "❌ La tabla 'usuarios' NO existe<br>";
    }
    
    $conexion->cerrarConexion();
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>