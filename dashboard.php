<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .welcome {
            color: #333;
            margin-bottom: 20px;
        }
        .user-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-logout {
            background: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-logout:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="welcome">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
        
        <div class="user-info">
            <h3>Tu información:</h3>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>ID de usuario:</strong> <?php echo $_SESSION['user_id']; ?></p>
        </div>
        
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</body>
</html>