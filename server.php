<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la configuración de base de datos y las rutas
require_once('./backend/config/databaseConfig.php');
require_once('./backend/routes/routesFactory.php');

// Obtener el módulo desde la URL
$module = $_GET['module'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

handleRequest($module, $method);
