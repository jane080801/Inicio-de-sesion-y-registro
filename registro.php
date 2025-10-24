<?php
// Permitir CORS para desarrollo
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

// Verificar que es una petición POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Log para debugging
    error_log("Datos recibidos: " . print_r($_POST, true));
    
    // Recibir y limpiar datos
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar_password = $_POST['confirmarPassword'] ?? '';

    // Validaciones básicas
    $errores = [];

    if (empty($usuario)) {
        $errores[] = "El nombre de usuario es obligatorio";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }

    if (empty($password) || strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }

    if ($password !== $confirmar_password) {
        $errores[] = "Las contraseñas no coinciden";
    }

    if (!isset($_POST['politicas'])) {
        $errores[] = "Debes aceptar las políticas de privacidad";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        try {
            $conexion = new Conexion();
            $conn = $conexion->getConexion();

            // Verificar si el usuario o email ya existen
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->execute([$usuario, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errores[] = "El usuario o correo electrónico ya están registrados";
            } else {
                // Hash de la contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertar nuevo usuario
                $stmt = $conn->prepare("INSERT INTO usuarios (usuario, email, password, fecha_registro) VALUES (?, ?, ?, NOW())");
                $resultado = $stmt->execute([$usuario, $email, $password_hash]);
                
                if ($resultado && $stmt->rowCount() > 0) {
                    $mensaje_exito = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                } else {
                    $errores[] = "Error al registrar el usuario";
                }
            }
            
            $conexion->cerrarConexion();
            
        } catch(PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
            error_log("Error PDO: " . $e->getMessage());
        }
    }

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
    } else {
        echo json_encode(['success' => true, 'message' => $mensaje_exito]);
    }
    exit;
    
} else {
    // Si no es POST, devolver error
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'errors' => ['Método no permitido']]);
    exit;
}
?>