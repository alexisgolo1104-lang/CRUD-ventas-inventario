<?php
require_once __DIR__ . '/configuration/config.php';

// Verificar sesión
session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_rol'])) {
    http_response_code(403);
    die('Acceso no autorizado');
}

// Verificar que sea administrador o superadmin
if (!in_array($_SESSION['usuario_rol'], ['admin', 'superadmin'])) {
    http_response_code(403);
    die('Permisos insuficientes');
}

require_once __DIR__ . '/configuration/database.php';
require_once __DIR__ . '/models/LogRespaldo.php';

// Configuración del respaldo
$fecha = date('Y-m-d_H-i-s');
$filename = "respaldo_hlazcano_{$fecha}.sql";
$filepath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

// Comando mysqldump
$command = sprintf(
    '"C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe" --host=%s --user=%s --password=%s %s > %s',
    escapeshellarg(DB_HOST),
    escapeshellarg(DB_USER),
    escapeshellarg(DB_PASS),
    escapeshellarg(DB_NAME),
    escapeshellarg($filepath)
);

// Ejecutar el comando
exec($command, $output, $return_var);

$logRespaldo = new LogRespaldo();

if ($return_var !== 0) {
    // Registrar respaldo fallido
    $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'Error al ejecutar mysqldump: ' . implode(' ', $output));
    http_response_code(500);
    die('Error al generar el respaldo');
}

// Verificar que el archivo se creó
if (!file_exists($filepath)) {
    $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'Archivo de respaldo no encontrado');
    http_response_code(500);
    die('Error: archivo de respaldo no encontrado');
}

// Obtener tamaño del archivo
$tamanoBytes = filesize($filepath);

// Registrar respaldo exitoso
$logRespaldo->registrarRespaldo($_SESSION['usuario_id'], $filename, $tamanoBytes, 'Respaldo generado exitosamente');

// Enviar headers para descarga
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Leer y enviar el archivo
readfile($filepath);

// Eliminar el archivo temporal
unlink($filepath);

exit;
?></content>
<parameter name="filePath">c:\laragon\www\hlazcanov2f\backup.php