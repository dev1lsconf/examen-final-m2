<?php
$host = getenv('DB_HOST') ?: 'localhost';
$usuario = getenv('DB_USER');
$clave = getenv('DB_PASSWORD');
$baseDeDatos = getenv('DB_NAME');

if ($usuario === false || $clave === false || $baseDeDatos === false) {
    http_response_code(500);
    exit('Faltan las variables de entorno DB_USER, DB_PASSWORD o DB_NAME.');
}

$conexion = mysqli_connect($host, $usuario, $clave, $baseDeDatos);

if (!$conexion) {
    http_response_code(500);
    exit('Error de conexión con la base de datos: ' . mysqli_connect_error());
}

mysqli_set_charset($conexion, 'utf8');
