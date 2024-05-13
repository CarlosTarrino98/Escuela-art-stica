<?php
class Foro
{
    private $conn;
    private $tabla = 'forum_topics';

    public $topic_id;
    public $course_id;
    public $user_id;
    public $title;
    public $content;
    public $creation_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para crear un nuevo tema en el foro
    public function crearTema()
    {
        $query = "INSERT INTO " . $this->tabla . " (course_id, user_id, title, content) VALUES (:course_id, :user_id, :title, :content)";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los datos
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener un tema específico del foro
    public function obtenerTema()
    {
        $query = "SELECT * FROM " . $this->tabla . " WHERE topic_id = :topic_id";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los datos
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    }


    // Método para actualizar un tema del foro
    public function actualizarTema()
    {
        $query = "UPDATE " . $this->tabla . " SET title = :title, content = :content WHERE topic_id = :topic_id";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los datos
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':topic_id', $this->topic_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para eliminar un tema del foro
    public function eliminarTema()
    {
        $query = "DELETE FROM " . $this->tabla . " WHERE topic_id = :topic_id";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los datos
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));

        $stmt->bindParam(':topic_id', $this->topic_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para listar todos los temas del foro
    public function listarTemas()
    {
        $query = "SELECT * FROM " . $this->tabla . " ORDER BY creation_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function listarTemasConDetallesUsuario()
    {
        $query = "SELECT ft.*, u.first_name, u.last_name, u.role 
              FROM forum_topics ft 
              JOIN users u ON ft.user_id = u.user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener temas de foro por ID de curso
    public function obtenerTemasPorCurso($course_id)
    {
        $query = "SELECT ft.*, u.first_name, u.last_name, u.role 
              FROM forum_topics ft 
              JOIN users u ON ft.user_id = u.user_id
              WHERE ft.course_id = :course_id
              ORDER BY ft.creation_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTemasPorTitulo($titulo)
    {
        // Modifica la consulta para incluir detalles del usuario
        $query = "SELECT ft.*, u.first_name, u.last_name, u.role 
              FROM " . $this->tabla . " ft 
              JOIN users u ON ft.user_id = u.user_id 
              WHERE ft.title LIKE :title ORDER BY ft.creation_date DESC";

        $stmt = $this->conn->prepare($query);
        $tituloBusqueda = "%$titulo%";
        $stmt->bindParam(':title', $tituloBusqueda);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
