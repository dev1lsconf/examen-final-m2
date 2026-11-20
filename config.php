<?php
// Cambia estos datos por los datos reales de la base de datos del ISP.
$host = 'localhost';
$usuario = 'root';
$clave = '';
$baseDeDatos = 'llibreria';

$conexion = mysqli_connect($host, $usuario, $clave, $baseDeDatos);

if (!$conexion) {
    http_response_code(500);
    exit('Error de conexión con la base de datos: ' . mysqli_connect_error());
}

mysqli_set_charset($conexion, 'utf8mb4');
