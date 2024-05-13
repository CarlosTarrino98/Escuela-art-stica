<?php
class Asignatura {
    private $conn;
    private $tabla = 'subjects';
    public $subject_id;
    public $subject_name;
    public $description;
    public $course_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function sanitizeData() {
        $this->subject_name = htmlspecialchars(strip_tags($this->subject_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
    }

    public function create() {
        $query = "INSERT INTO " . $this->tabla . " SET subject_name=:subject_name, description=:description, course_id=:course_id";
        $stmt = $this->conn->prepare($query);

        $this->sanitizeData();

        $stmt->bindParam(':subject_name', $this->subject_name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al crear la asignatura.");
        }
    }

    public function update() {
        $query = "UPDATE " . $this->tabla . " SET subject_name=:subject_name, description=:description, course_id=:course_id WHERE subject_id=:subject_id";
        $stmt = $this->conn->prepare($query);

        $this->sanitizeData();

        $stmt->bindParam(':subject_name', $this->subject_name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':subject_id', $this->subject_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al actualizar la asignatura.");
        }
    }

    public function delete() {
        $query = "DELETE FROM " . $this->tabla . " WHERE subject_id = :subject_id";
        $stmt = $this->conn->prepare($query);

        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $stmt->bindParam(':subject_id', $this->subject_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al eliminar la asignatura.");
        }
    }

    public function getById() {
        $query = "SELECT * FROM " . $this->tabla . " WHERE subject_id = :subject_id";
        $stmt = $this->conn->prepare($query);

        $this->subject_id = htmlspecialchars(strip_tags($this->subject_id));
        $stmt->bindParam(':subject_id', $this->subject_id);

        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Asignatura no encontrada.");
        }
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->tabla;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
