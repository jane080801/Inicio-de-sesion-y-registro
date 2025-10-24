<?php
require_once 'conexion.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar datos
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    // Validaciones básicas
    $errores = [];

    if (empty($usuario)) {
        $errores[] = "El nombre de usuario es obligatorio";
    }

    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria";
    }

    // Si no hay errores, proceder con el login
    if (empty($errores)) {
        try {
            $conexion = new Conexion();
            $conn = $conexion->getConexion();

            // Buscar usuario
            $stmt = $conn->prepare("SELECT id, usuario, email, password FROM usuarios WHERE usuario = ? OR email = ?");
            $stmt->execute([$usuario, $usuario]);
            
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verificar contraseña
                if (password_verify($password, $user['password'])) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['usuario'] = $user['usuario'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['logged_in'] = true;
                    
                    $mensaje_exito = "¡Inicio de sesión exitoso!";
                } else {
                    $errores[] = "Contraseña incorrecta";
                }
            } else {
                $errores[] = "Usuario no encontrado";
            }
            
            $conexion->cerrarConexion();
            
        } catch(PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
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
}
?>