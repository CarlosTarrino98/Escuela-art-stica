<?php
class Database {
    private $host = "localhost";
    private $db_name = "artistics_school_db";
    private $username = "root";
    private $password = ""; // Aquí deberías almacenar tu contraseña de manera segura, por ejemplo, en un archivo de configuración externo

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            // Establecer el modo de error PDO para lanzar excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Lanzar una excepción en lugar de imprimir un mensaje de error
            throw new Exception("Error de conexión: " . $exception->getMessage());
        }

        return $this->conn;
    }

    public function closeConnection() {
        $this->conn = null; // Cierra la conexión
    }
}
?>
