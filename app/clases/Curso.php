<?php
class Curso
{
    private $conn;
    private $tabla = 'courses';

    public $course_id;
    public $course_name;
    public $cover_image;
    public $profesor_id;
    public $asignaturas;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->asignaturas = array(); // Inicializa asignaturas como un array vacío
    }

    private function sanitizarDatos()
    {
        $this->course_name = htmlspecialchars(strip_tags($this->course_name));
        $this->cover_image = htmlspecialchars(strip_tags($this->cover_image));
        $this->profesor_id = htmlspecialchars(strip_tags($this->profesor_id));
        if (is_array($this->asignaturas)) {
            foreach ($this->asignaturas as $index => $asignatura) {
                $this->asignaturas[$index] = htmlspecialchars(strip_tags($asignatura));
            }
        }
    }

    public function crear()
    {
        $this->sanitizarDatos();
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->tabla . " (course_name, cover_image, profesor_id) VALUES (:course_name, :cover_image, :profesor_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':course_name', $this->course_name);
            $stmt->bindParam(':cover_image', $this->cover_image);
            $stmt->bindParam(':profesor_id', $this->profesor_id);

            if (!$stmt->execute()) {
                throw new Exception("Error al crear el curso.");
            }

            $this->course_id = $this->conn->lastInsertId();

            foreach ($this->asignaturas as $asignatura) {
                $query_asig = "INSERT INTO subjects (subject_name, course_id) VALUES (:subject_name, :course_id)";
                $stmt_asig = $this->conn->prepare($query_asig);
                $stmt_asig->bindParam(':subject_name', $asignatura);
                $stmt_asig->bindParam(':course_id', $this->course_id);

                if (!$stmt_asig->execute()) {
                    throw new Exception("Error al crear asignatura.");
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function actualizar()
    {
        $this->sanitizarDatos();
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->tabla . " SET course_name=:course_name, cover_image=:cover_image, profesor_id=:profesor_id WHERE course_id=:course_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':course_name', $this->course_name);
            $stmt->bindParam(':cover_image', $this->cover_image);
            $stmt->bindParam(':profesor_id', $this->profesor_id);
            $stmt->bindParam(':course_id', $this->course_id);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar el curso.");
            }

            $query_borrar_asig = "DELETE FROM subjects WHERE course_id = :course_id";
            $stmt_borrar_asig = $this->conn->prepare($query_borrar_asig);
            $stmt_borrar_asig->bindParam(':course_id', $this->course_id);
            $stmt_borrar_asig->execute();

            foreach ($this->asignaturas as $asignatura) {
                $query_asig = "INSERT INTO subjects (subject_name, course_id) VALUES (:subject_name, :course_id)";
                $stmt_asig = $this->conn->prepare($query_asig);
                $stmt_asig->bindParam(':subject_name', $asignatura);
                $stmt_asig->bindParam(':course_id', $this->course_id);

                if (!$stmt_asig->execute()) {
                    throw new Exception("Error al agregar asignatura.");
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function eliminar()
    {
        try {
            $this->conn->beginTransaction();

            $query_borrar_asig = "DELETE FROM subjects WHERE course_id = :course_id";
            $stmt_borrar_asig = $this->conn->prepare($query_borrar_asig);
            $stmt_borrar_asig->bindParam(':course_id', $this->course_id);
            $stmt_borrar_asig->execute();

            $query = "DELETE FROM " . $this->tabla . " WHERE course_id = :course_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':course_id', $this->course_id);

            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar el curso.");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function obtenerPorId()
    {
        try {
            $query = "SELECT * FROM " . $this->tabla . " WHERE course_id = :course_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':course_id', $this->course_id);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $curso = $stmt->fetch(PDO::FETCH_ASSOC);

                $query_asig = "SELECT subject_name FROM subjects WHERE course_id = :course_id";
                $stmt_asig = $this->conn->prepare($query_asig);
                $stmt_asig->bindParam(':course_id', $this->course_id);
                $stmt_asig->execute();

                $curso['asignaturas'] = $stmt_asig->fetchAll(PDO::FETCH_COLUMN);

                return $curso;
            } else {
                throw new Exception("Curso no encontrado.");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listar()
    {
        try {
            $query = "SELECT c.course_id, c.course_name, c.cover_image, GROUP_CONCAT(s.subject_name SEPARATOR ', ') AS asignaturas FROM " . $this->tabla . " c LEFT JOIN subjects s ON c.course_id = s.course_id GROUP BY c.course_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // En caso de error, devuelve un array vacío
            return [];
        }
    }
}
