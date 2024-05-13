<?php
class Profesor {
    private $conn;
    private $tabla = 'teachers';
    public $teacher_id;
    public $first_name;
    public $last_name;
    public $description;
    public $photo;
    public $course_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function sanitizarDatos() {
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
    }

    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (first_name, last_name, description, photo, course_id) VALUES (:first_name, :last_name, :description, :photo, :course_id)";
        $stmt = $this->conn->prepare($query);

        $this->sanitizarDatos();

        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':course_id', $this->course_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al crear el profesor.");
        }
    }

    public function actualizar() {
        $query = "UPDATE " . $this->tabla . " SET first_name=:first_name, last_name=:last_name, description=:description, photo=:photo, course_id=:course_id WHERE teacher_id=:teacher_id";
        $stmt = $this->conn->prepare($query);

        $this->sanitizarDatos();

        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':teacher_id', $this->teacher_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al actualizar el profesor.");
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . $this->tabla . " WHERE teacher_id = :teacher_id";
        $stmt = $this->conn->prepare($query);

        $this->teacher_id = htmlspecialchars(strip_tags($this->teacher_id));
        $stmt->bindParam(':teacher_id', $this->teacher_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al eliminar el profesor.");
        }
    }

    public function obtenerPorId($profesor_id) {
        $query = "SELECT * FROM teachers WHERE teacher_id = :teacher_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':teacher_id', $profesor_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function listar() {
        $query = "SELECT * FROM " . $this->tabla;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function listarProfesores() {
        $query = "SELECT t.teacher_id, t.first_name, t.last_name, c.course_name 
                  FROM teachers t
                  LEFT JOIN courses c ON t.course_id = c.course_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
