<?php
class RespuestasForo
{
    // Conexión a la base de datos y nombre de la tabla
    private $conn;
    private $table_name = 'forum_replies';

    // Propiedades del objeto
    public $reply_id;
    public $topic_id;
    public $user_id;
    public $content;
    public $reply_date;

    // Constructor con conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Crear una nueva respuesta
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table_name . ' 
                  SET topic_id=:topic_id, user_id=:user_id, content=:content';

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->content = htmlspecialchars(strip_tags($this->content));

        // Vincular valores
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':content', $this->content);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Obtener respuestas por ID del tema
    public function readByTopicId()
    {
        $query = 'SELECT r.reply_id, r.topic_id, r.user_id, r.content, r.reply_date, u.first_name, u.last_name 
                  FROM ' . $this->table_name . ' r
                  LEFT JOIN users u ON r.user_id = u.user_id
                  WHERE r.topic_id = :topic_id 
                  ORDER BY r.reply_date ASC';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->execute();

        return $stmt;
    }


    // Actualizar una respuesta
    public function update()
    {
        $query = 'UPDATE ' . $this->table_name . ' 
                  SET content=:content 
                  WHERE reply_id=:reply_id';

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->reply_id = htmlspecialchars(strip_tags($this->reply_id));

        // Vincular valores
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':reply_id', $this->reply_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Eliminar una respuesta
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE reply_id=:reply_id';

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->reply_id = htmlspecialchars(strip_tags($this->reply_id));

        // Vincular valor
        $stmt->bindParam(':reply_id', $this->reply_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function obtenerPorTema($topic_id)
    {
        $query = "SELECT * FROM respuestas_foro WHERE topic_id = :topic_id ORDER BY creation_date DESC";
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los datos
        $topic_id = htmlspecialchars(strip_tags($topic_id));
        $stmt->bindParam(':topic_id', $topic_id);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En RespuestasForo.php
    public function obtenerRespuestaPorId()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE reply_id = :reply_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":reply_id", $this->reply_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
