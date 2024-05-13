<?php
class Consultas
{
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = 'consultations';

    // Propiedades del objeto
    public $consultation_id;
    public $student_id;
    public $subject;
    public $message;
    public $creation_date;
    public $answered;

    // Constructor con conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Crear una nueva consulta
    public function crearConsulta($studentId, $subject, $message)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET student_id=:student_id, subject=:subject, message=:message";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $studentId = htmlspecialchars(strip_tags($studentId));
        $subject = htmlspecialchars(strip_tags($subject));
        $message = htmlspecialchars(strip_tags($message));

        // Vincular valores
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Obtener todas las consultas
    public function obtenerTodasLasConsultas()
    {
        // Query para obtener todas las consultas y sus respuestas
        $query = "SELECT c.*, u.first_name, u.last_name, r.message AS respuesta
              FROM consultations c
              JOIN users u ON c.student_id = u.user_id
              LEFT JOIN consultation_responses r ON c.consultation_id = r.consultation_id
              ORDER BY c.creation_date DESC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        // Devolver los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerConsultasSinRespuesta() {
        $sql = "SELECT c.*, u.first_name, u.last_name
                FROM consultations c
                INNER JOIN users u ON c.student_id = u.user_id
                LEFT JOIN consultation_responses cr ON c.consultation_id = cr.consultation_id
                WHERE cr.response_id IS NULL";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $consultas;
    }
    public function responderConsulta($consulta_id, $teacher_id, $respuesta)
    {
        try {
            $query = "INSERT INTO consultation_responses (consultation_id, teacher_id, message) VALUES (:consulta_id, :teacher_id, :respuesta)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":consulta_id", $consulta_id, PDO::PARAM_INT);
            $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
            $stmt->bindParam(":respuesta", $respuesta, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error al responder consulta: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarConsulta($consulta_id) {
        // Preparar la consulta SQL para eliminar la consulta por su ID
        $query = "DELETE FROM consultations WHERE consultation_id = :consulta_id";

        // Preparar la declaración
        $stmt = $this->conn->prepare($query);

        // Vincular el parámetro
        $stmt->bindValue(':consulta_id', $consulta_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // La consulta se eliminó correctamente
            return true;
        } else {
            // La consulta no se pudo eliminar
            return false;
        }
    }
}
