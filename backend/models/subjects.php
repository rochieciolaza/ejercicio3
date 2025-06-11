<?php
/**
*    File        : backend/models/subjects.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/


function getAllSubjects($conn) 
{
    $sql = "SELECT * FROM subjects";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}


function getSubjectById($conn, $id) 
{
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // i = entero
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); 
}


function createSubject($conn, $name)
{
    try {
        $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->bind_param("s", $name); 
        $stmt->execute();

        return ["inserted" => $stmt->affected_rows];
    } catch (mysqli_sql_exception $e) {
     
        if (str_contains($e->getMessage(), "Duplicate entry")) {
            http_response_code(409); 
            header('Content-Type: application/json');
            echo json_encode(["error" => "Ya existe una materia con ese nombre"]);
            exit();
        } else {
           
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
            exit();
        }
    }
}


function deleteSubject($conn, $id)
{
    try {
        $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return ['deleted' => $stmt->affected_rows];
    } catch (mysqli_sql_exception $e) {
        
        if (str_contains($e->getMessage(), "a foreign key constraint fails")) {
            http_response_code(409);
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se puede eliminar la materia porque está asignada a estudiantes"]);
            exit();
        } else {
   
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar: " . $e->getMessage()]);
            exit();
        }
    }
}
?>
