<?php
/**
*    File        : backend/server.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

function sendCodeMessage($code, $message = "")
{
    http_response_code($code);
    header("Content-Type: application/json");
    echo json_encode([
        "status" => $code,
        "error" => $message,
        "timestamp" => date("c")
    ]);
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
    sendCodeMessage(200); // 
}


$uri = parse_url($_SERVER['REQUEST_URI']);
$query = $uri['query'] ?? '';
parse_str($query, $query_array);
$module = $query_array['module'] ?? null;


if (!$module)
{
    sendCodeMessage(400, "Módulo no especificado");
}


if (!preg_match('/^\w+$/', $module))
{
    sendCodeMessage(400, "Nombre de módulo inválido");
}


$routeFile = __DIR__ . "/routes/{$module}Routes.php";

if (file_exists($routeFile))
{
    require_once($routeFile);
}
else
{
    sendCodeMessage(404, "Ruta para el módulo '{$module}' no encontrada");
}
