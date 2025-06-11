<?php
/**
*    File        : backend/models/students.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/


function getAllStudents($conn) 
{
    $sql = "SELECT * FROM students";

    
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}


function getStudentById($conn, $id) 
{
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

  
    return $result->fetch_assoc(); 
}

function createStudent($conn, $fullname, $email, $age) 
{
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $age);
    $stmt->execute();

    return 
    [
        'inserted' => $stmt->affected_rows,       
        'id' => $conn->insert_id                   
    ];
}


function updateStudent($conn, $id, $fullname, $email, $age) 
{
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    $stmt->execute();

    return ['updated' => $stmt->affected_rows];
}


function deleteStudent($conn, $id)
{
    try {
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return ['deleted' => $stmt->affected_rows];
    } catch (mysqli_sql_exception $e) {
       
        if (str_contains($e->getMessage(), "a foreign key constraint fails")) {
            http_response_code(409);
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se puede eliminar el estudiante porque tiene materias asociadas"]);
            exit();
        } else {
           
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar: " . $e->getMessage()]);
            exit();
        }
    }
}
?>
