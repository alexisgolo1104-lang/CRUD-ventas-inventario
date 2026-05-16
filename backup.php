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

// Manejo de import (recibir ZIP con .sql y restaurar)
$action = $_GET['action'] ?? 'export';
if ($action === 'import') {
    // Solo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
        exit;
    }
    if (!isset($_FILES['backup_file'])) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'No se recibió archivo']);
        exit;
    }
    $uploaded = $_FILES['backup_file'];
    if ($uploaded['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Error en la carga']);
        exit;
    }
    $tmpName = $uploaded['tmp_name'];
    $destZip = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($uploaded['name']);
    move_uploaded_file($tmpName, $destZip);

    $zip = new ZipArchive();
    $logRespaldo = new LogRespaldo();
    if ($zip->open($destZip) !== TRUE) {
        $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'ZIP inválido o no se pudo abrir');
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'ZIP inválido']);
        unlink($destZip);
        exit;
    }
    $extractDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'hlz_import_' . uniqid();
    mkdir($extractDir);
    $zip->extractTo($extractDir);
    $zip->close();

    // Buscar archivo .sql
    $sqlFiles = glob($extractDir . DIRECTORY_SEPARATOR . '*.sql');
    if (count($sqlFiles) === 0) {
        $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'No se encontró archivo SQL en ZIP');
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'No se encontró archivo SQL en ZIP']);
        // cleanup
        @unlink($destZip);
        // remove extracted
        array_map('unlink', glob($extractDir . DIRECTORY_SEPARATOR . '*'));
        @rmdir($extractDir);
        exit;
    }
    $sqlfile = $sqlFiles[0];

    // Ejecutar import usando cliente mysql
    $mysqlExe = 'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysql.exe';
    $command = sprintf('cmd /c "%s --host=%s --user=%s --password=%s %s < %s"',
        $mysqlExe,
        escapeshellarg(DB_HOST),
        escapeshellarg(DB_USER),
        escapeshellarg(DB_PASS),
        escapeshellarg(DB_NAME),
        $sqlfile
    );
    exec($command, $output, $return_var);
    if ($return_var !== 0) {
        $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'Error al importar SQL: ' . implode(' ', $output));
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error al importar SQL']);
    } else {
        $tam = filesize($destZip);
        $logRespaldo->registrarRespaldo($_SESSION['usuario_id'], basename($uploaded['name']), $tam, 'Importado desde ZIP');
        echo json_encode(['ok' => true, 'message' => 'Importado correctamente']);
    }

    // cleanup
    @unlink($destZip);
    array_map('unlink', glob($extractDir . DIRECTORY_SEPARATOR . '*'));
    @rmdir($extractDir);
    exit;
}

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

// If action=export requested, send a zip containing the .sql
if ($action === 'export') {
    $zipname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_FILENAME) . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipname, ZipArchive::CREATE) !== TRUE) {
        $logRespaldo->registrarRespaldoFallido($_SESSION['usuario_id'], 'No se pudo crear ZIP');
        http_response_code(500);
        die('Error creando ZIP');
    }
    $zip->addFile($filepath, $filename);
    $zip->close();

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . pathinfo($filename, PATHINFO_FILENAME) . '.zip"');
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);

    // cleanup
    unlink($zipname);
    unlink($filepath);
    exit;
}

// Default behavior: send raw SQL (backwards compat)
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