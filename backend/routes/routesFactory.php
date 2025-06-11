<?php
/**
*    File        : backend/routes/routesFactory.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

/**
 * Esta función abstrae la lógica de ruteo según el método HTTP (GET, POST, PUT, DELETE).
 * Se puede usar directamente o extenderse pasando funciones personalizadas.
 *
 * @param mysqli $conn              Conexión activa a la base de datos
 * @param array  $customHandlers    Opcional. Array de funciones personalizadas para manejar métodos HTTP.
 * @param string $prefix            Prefijo por defecto para los nombres de los handlers (por ejemplo, 'handle')
 */
function routeRequest($conn, $customHandlers = [], $prefix = 'handle') 
{
    $method = $_SERVER['REQUEST_METHOD']; // Detecta el método HTTP de la solicitud


    $defaultHandlers = [
        'GET'    => $prefix . 'Get',
        'POST'   => $prefix . 'Post',
        'PUT'    => $prefix . 'Put',
        'DELETE' => $prefix . 'Delete'
    ];

    
    $handlers = array_merge($defaultHandlers, $customHandlers);

    if (!isset($handlers[$method])) 
    {
        http_response_code(405);
        echo json_encode(["error" => "Método $method no permitido"]);
        return;
    }

    $handler = $handlers[$method];

 
    if (is_callable($handler)) 
    {
        $handler($conn);
    }
    else
    {
        http_response_code(500);
        echo json_encode(["error" => "Handler para $method no es válido"]);
    }
}
