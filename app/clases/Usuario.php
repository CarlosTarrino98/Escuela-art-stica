<?php
class Usuario
{
    private $conn;
    private $tabla = 'users';

    public $user_id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $role;
    public $course_id;

    public function __construct($db)
    {
        $this->conn = $db; // Establecer la conexión a la base de datos
    }

    public function setPassword($password)
    {
        // Hashear la contraseña antes de almacenarla
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    private function sanitizeData($registration = false)
    {
        // Sanitizar nombre y apellidos
        $this->first_name = filter_var($this->first_name, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->last_name = filter_var($this->last_name, FILTER_SANITIZE_SPECIAL_CHARS);

        // Sanitizar email
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        // Sanitizar contraseña solo si NO es durante el registro
        if (!$registration) {
            $this->password = filter_var($this->password, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        // Sanitizar teléfono, dirección y rol
        $this->phone = filter_var($this->phone, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->address = filter_var($this->address, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->role = filter_var($this->role, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function isEmailRegistered()
    {
        // Verificar si el email ya está registrado
        $query = "SELECT user_id FROM " . $this->tabla . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Registro de usuario
    public function register()
    {
        // Validaciones
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email no válido.");
        }
        if ($this->isEmailRegistered()) {
            throw new Exception("Email ya registrado.");
        }

        // Sanitizar datos
        $this->sanitizeData(true);

        // Preparar la consulta
        $query = "INSERT INTO " . $this->tabla . " (email, password, first_name, last_name, phone, address, role, course_id) VALUES (:email, :password, :first_name, :last_name, :phone, :address, :role, :course_id)";
        $stmt = $this->conn->prepare($query);

        // Vinculación de parámetros
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $this->address, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':course_id', $this->course_id);

        if (is_null($this->course_id)) {
            $stmt->bindValue(':course_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {
            $this->user_id = $this->conn->lastInsertId(); // Obtener el último ID insertado
            return $this->user_id;
        } else {
            print_r($stmt->errorInfo());
            throw new Exception("Error al registrar el usuario.");
        }
    }

    // Inicio de sesión del usuario
    public function login()
    {
        // Consulta para iniciar sesión
        $query = "SELECT user_id, first_name, password, role FROM " . $this->tabla . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar la contraseña utilizando password_verify()
            if (password_verify($this->password, $row['password'])) {
                $this->user_id = $row['user_id'];
                $this->first_name = $row['first_name'];
                $this->role = $row['role'];
                return true;
            } else {
                throw new Exception("Credenciales incorrectas.");
            }
        } else {
            throw new Exception("Usuario no encontrado.");
        }
    }

    // Actualizar usuario
    public function update()
    {
        // Consulta para actualizar usuario
        $query = "UPDATE " . $this->tabla . "
                  SET first_name = :first_name,
                      last_name = :last_name,
                      email = :email,
                      phone = :phone,
                      address = :address,
                      role = :role,
                      course_id = :course_id
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->sanitizeData();

        // Vincular nuevos valores
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al actualizar el usuario.");
        }
    }

    // Eliminar usuario
    public function delete()
    {
        // Consulta para eliminar usuario
        $query = "DELETE FROM " . $this->tabla . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Vincular id
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Error al eliminar el usuario.");
        }
    }

    // Obtener rol del usuario
    public function getRole()
    {
        // Consulta para obtener el rol
        $query = "SELECT role FROM " . $this->tabla . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Vincular id
        $stmt->bindParam(':user_id', $this->user_id);

        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['role'];
        } else {
            throw new Exception("Usuario no encontrado.");
        }
    }

    // Obtener usuario por ID
    public function getById()
    {
        // Consulta para obtener usuario por ID
        $query = "SELECT * FROM " . $this->tabla . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Vincular id
        $stmt->bindParam(':user_id', $this->user_id);

        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Usuario no encontrado.");
        }
    }

    // Listar todos los usuarios (opcional, para uso administrativo)
    public function listAll()
    {
        // Consulta para listar todos los usuarios
        $query = "SELECT * FROM " . $this->tabla;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //Control de alumnos
    public function listarAlumnos()
    {
        $query = "SELECT * FROM " . $this->tabla . " WHERE role = 'alumno'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear un nuevo alumno
    public function crearAlumno()
    {
        $this->role = 'alumno'; // Establecer el rol como alumno
        return $this->register(); // Utilizar el método de registro existente
    }

    // Actualizar un alumno
    public function actualizarAlumno()
    {
        $this->role = 'alumno'; // Asegurar que el rol sigue siendo alumno
        return $this->update(); // Utilizar el método de actualización existente
    }

    // Eliminar un alumno
    public function eliminarAlumno()
    {
        $this->role = 'alumno'; // Verificar que es un alumno antes de eliminar
        return $this->delete(); // Utilizar el método de eliminación existente
    }

    // Obtener un alumno por ID
    public function obtenerAlumnoPorId()
    {
        $query = "SELECT * FROM " . $this->tabla . " WHERE user_id = :user_id AND role = 'alumno'";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(':user_id', $this->user_id);

        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Alumno no encontrado.");
        }
    }

    public function obtenerInfoAlumnosConCalificaciones()
    {
        $query = "SELECT 
                u.user_id, 
                u.first_name, 
                u.last_name, 
                c.course_name, 
                s.subject_id, 
                s.subject_name, 
                IFNULL(sp.calification, 0) as calification
              FROM users u
              LEFT JOIN courses c ON u.course_id = c.course_id
              LEFT JOIN subjects s ON c.course_id = s.course_id
              LEFT JOIN student_progress sp ON u.user_id = sp.user_id AND s.subject_id = sp.subject_id
              WHERE u.role = 'alumno'
              ORDER BY u.user_id, s.subject_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
