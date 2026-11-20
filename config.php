<?php
$host = 'localhost';
$usuario = 'mylibreriacb';
$clave = '@Examen-modulo-2';
$baseDeDatos = 'libreria-m2';

$conexion = mysqli_connect($host, $usuario, $clave, $baseDeDatos);

if (!$conexion) {
    http_response_code(500);
    exit('Error de conexión con la base de datos: ' . mysqli_connect_error());
}

mysqli_set_charset($conexion, 'utf8');
