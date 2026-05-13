<?php
// ── Bootstrap ────────────────────────────────────────────
require_once 'configuration/config.php';
require_once 'configuration/database.php';

// Configurar opciones de sesión
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_set_cookie_params([
    'lifetime' => SESSION_TIMEOUT,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,
    'httponly' => false,
]);

// session_name(SESSION_NAME);
session_start();

// ── Autoload de modelos y controladores ──────────────────
require_once 'models/Usuario.php';
require_once 'models/Producto.php';
require_once 'models/Venta.php';
require_once 'models/Cliente.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/InventarioController.php';
require_once 'controllers/VentasController.php';
require_once 'controllers/ClientesController.php';

$action = $_GET['action'] ?? '';

// ═══════════════════════════════════════════════════════════
//  Router de acciones (AJAX y formularios)
// ═══════════════════════════════════════════════════════════
switch ($action) {

    // ── Sesión desde SPA ──────────────────────────────────
    case 'sesion_establecer':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $correo = trim($_POST['correo'] ?? '');
        $model  = new Usuario();
        $user   = $model->getByCorreo($correo);
        header('Content-Type: application/json');
        if ($user && $user['activo']) {
            $_SESSION['usuario_id']    = $user['id_usuario'];
            $_SESSION['usuario_nom']   = $user['nombre'];
            $_SESSION['usuario_rol']   = $user['rol'];
            $_SESSION['usuario_correo']= $user['correo'];
            $_SESSION['id_tienda']     = $user['id_tienda'];
            session_write_close(); // Forzar escritura de la sesión
            echo json_encode(['ok' => true, 'rol' => $user['rol'], 'tienda' => $user['id_tienda']]);
        } else {
            echo json_encode(['ok' => false]);
        }
        exit;

    case 'sesion_verificar':
        header('Content-Type: application/json');
        if (!empty($_SESSION['usuario_id'])) {
            echo json_encode(['ok' => true, 'usuario_id' => $_SESSION['usuario_id']]);
        } else {
            echo json_encode(['ok' => false]);
        }
        exit;

    // ── Auth ──────────────────────────────────────────────
    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // ── Respaldo ──────────────────────────────────────────
    case 'respaldo_historial':
        header('Content-Type: application/json');
        require_once 'models/LogRespaldo.php';
        $logModel = new LogRespaldo();
        echo json_encode(['ok' => true, 'historial' => $logModel->getHistorial(), 'ultimo' => $logModel->getUltimoRespaldo()]);
        exit;

    // ── Inventario / Productos ────────────────────────────
    case 'inventario_listar':
        header('Content-Type: application/json');
        echo json_encode((new InventarioController())->listar());
        exit;

    case 'inventario_datos':
        header('Content-Type: application/json');
        echo json_encode((new InventarioController())->datosFormulario());
        exit;

    case 'producto_crear':
        (new InventarioController())->crear();
        break;

    case 'producto_actualizar':
        (new InventarioController())->actualizar();
        break;

    case 'producto_desactivar':
        (new InventarioController())->desactivar();
        break;

    case 'producto_get':
        AuthController::requerirLogin();
        $id = (int)($_GET['id_producto'] ?? 0);
        header('Content-Type: application/json');
        echo json_encode((new Producto())->getById($id));
        exit;

    case 'inventario_buscar':
        AuthController::requerirLogin();
        $q = trim($_GET['q'] ?? '');
        header('Content-Type: application/json');
        echo json_encode($q ? (new Producto())->buscar($q) : []);
        exit;

    case 'inventario_alertas':
        AuthController::requerirLogin();
        header('Content-Type: application/json');
        $idT = in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN]) ? 0 : (int)($_SESSION['id_tienda'] ?? 0);
        echo json_encode((new Producto())->getConAlerta($idT));
        exit;

    // ── Ventas ────────────────────────────────────────────
    case 'ventas_listar':
        header('Content-Type: application/json');
        echo json_encode((new VentasController())->listar());
        exit;

    case 'ventas_datos':
        header('Content-Type: application/json');
        echo json_encode((new VentasController())->datosFormulario());
        exit;

    case 'venta_registrar':
        (new VentasController())->registrar();
        break;

    case 'venta_detalle':
        AuthController::requerirLogin();
        $id = (int)($_GET['id_venta'] ?? 0);
        header('Content-Type: application/json');
        echo json_encode((new VentasController())->detalle($id));
        exit;

    // ── Clientes ──────────────────────────────────────────
    case 'clientes_listar':
        header('Content-Type: application/json');
        echo json_encode((new ClientesController())->listar());
        exit;

    case 'cliente_crear':
        (new ClientesController())->crear();
        break;

    case 'cliente_actualizar':
        (new ClientesController())->actualizar();
        break;

    case 'cliente_desactivar':
        (new ClientesController())->desactivar();
        break;

    case 'cliente_get':
        AuthController::requerirLogin();
        $id = (int)($_GET['id_cliente'] ?? 0);
        header('Content-Type: application/json');
        echo json_encode((new Cliente())->getById($id));
        exit;

    case 'cliente_perfil':
        AuthController::requerirLogin();
        $id = (int)($_GET['id_cliente'] ?? 0);
        header('Content-Type: application/json');
        echo json_encode((new ClientesController())->perfil($id));
        exit;

    // ── Usuarios ──────────────────────────────────────────
    case 'usuarios_listar':
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        header('Content-Type: application/json');
        echo json_encode((new Usuario())->getAll());
        exit;

    case 'usuario_crear':
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $ok = (new Usuario())->crear($_POST);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Usuario creado.' : 'Error al crear.']);
        exit;

    case 'usuario_actualizar':
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $id = (int)($_POST['id_usuario'] ?? 0);
        $ok = (new Usuario())->actualizar($id, $_POST);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Usuario actualizado.' : 'Error al actualizar.']);
        exit;

    case 'usuario_desactivar':
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $id = (int)($_POST['id_usuario'] ?? 0);
        $ok = (new Usuario())->darDeBaja($id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok]);
        exit;

    case 'usuario_reactivar':
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $id = (int)($_POST['id_usuario'] ?? 0);
        $ok = (new Usuario())->reactivar($id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok]);
        exit;

    // ── Vista principal (SPA) ─────────────────────────────
    default:
        include 'panel.php';
        break;
}
