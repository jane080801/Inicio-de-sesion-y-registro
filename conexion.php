<?php
class Conexion {
    private $host = "localhost";
    private $usuario = "root"; 
    private $password = ""; 
    private $base_datos = "inicio_de_sesion";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->base_datos}", 
                $this->usuario, 
                $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            $this->conn->exec("SET NAMES utf8");
        } catch(PDOException $e) {
            // En desarrollo, muestra el error completo
            echo "Error de conexión: " . $e->getMessage();
            error_log("Error de conexión: " . $e->getMessage());
            die();
        }
    }

    public function getConexion() {
        return $this->conn;
    }

    public function cerrarConexion() {
        $this->conn = null;
    }
}
?>