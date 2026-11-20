<?php
$localEnvFile = __DIR__ . '/.env';
$localConfig = [];

if (is_file($localEnvFile)) {
    $parsedConfig = parse_ini_file($localEnvFile, false, INI_SCANNER_RAW);
    if ($parsedConfig === false) {
        http_response_code(500);
        exit('No se pudo leer la configuración local de la base de datos.');
    }
    $localConfig = $parsedConfig;
}

$getConfig = static function (string $key, ?string $default = null) use ($localConfig): string {
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    if (array_key_exists($key, $localConfig)) {
        return (string) $localConfig[$key];
    }
    if ($default !== null) {
        return $default;
    }
    http_response_code(500);
    exit('Falta la configuración de la base de datos: ' . $key);
};

$host = $getConfig('DB_HOST', 'localhost');
$usuario = $getConfig('DB_USER');
$clave = $getConfig('DB_PASSWORD');
$baseDeDatos = $getConfig('DB_NAME');

$conexion = mysqli_connect($host, $usuario, $clave, $baseDeDatos);

if (!$conexion) {
    http_response_code(500);
    exit('Error de conexión con la base de datos: ' . mysqli_connect_error());
}

mysqli_set_charset($conexion, 'utf8');
