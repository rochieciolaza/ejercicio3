<?php
/**
*    File        : backend/routes/studentsRoutes.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

require_once("./config/databaseConfig.php");          // Conexión a la base de datos
require_once("./routes/routesFactory.php");           // Fábrica de rutas genéricas
require_once("./controllers/studentsController.php"); // Controlador base para estudiantes

routeRequest($conn, [
    'POST' => function($conn) 
    {
     
        $input = json_decode(file_get_contents("php://input"), true);
        if (empty($input['fullname'])) 
        {
            http_response_code(400);
            echo json_encode(["error" => "Falta el nombre"]);
            return;
        }
        handlePost($conn); 
    }
]);


function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (isset($input['id'])) 
    {
        $student = getStudentById($conn, $input['id']);
        echo json_encode($student);
    } 
    else
    {
        $students = getAllStudents($conn);
        echo json_encode($students);
    }
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    
    if ($input['age'] < 0 || $input['age'] > 117) {
        http_response_code(400);
        echo json_encode(["error" => "La edad debe estar entre 0 y 117 años"]);
        return;
    }


    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $input['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409); 
        echo json_encode(["error" => "Ya existe un estudiante con ese email"]);
        return;
    }


    $result = createStudent($conn, $input['fullname'], $input['email'], $input['age']);
    if ($result['inserted'] > 0) 
    {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}


function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if ($input['age'] < 0 || $input['age'] > 117) {
        http_response_code(400);
        echo json_encode(["error" => "La edad debe estar entre 0 y 117 años"]);
        return;
    }

    $result = updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age']);
    if ($result['updated'] > 0) 
    {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    $result = deleteStudent($conn, $input['id']);
    if ($result['deleted'] > 0) 
    {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
