<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../models/Producto.php';

class InventarioController {

    private Producto $model;

    public function __construct() {
        $this->model = new Producto();
    }

    /** Lista productos del inventario (filtrado por tienda de sesión si aplica) */
    public function listar(): array {
        AuthController::requerirLogin();
        $id_tienda = (int)($_SESSION['id_tienda'] ?? 0);
        // Superadmin ve todo; admin/empleado solo su tienda
        if (in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN])) {
            $id_tienda = 0; // todas
        }
        return $this->model->getAll($id_tienda);
    }

    /** Retorna los catálogos auxiliares para el formulario de producto */
    public function datosFormulario(): array {
        AuthController::requerirLogin();
        $id_tienda = (int)($_SESSION['id_tienda'] ?? 1);
        return [
            'catalogos'   => $this->model->getCatalogos(),
            'tipos'       => $this->model->getTipos(),
            'colores'     => $this->model->getColores(),
            'unidades'    => $this->model->getUnidades(),
            'anaqueles'   => $this->model->getAnaqueles($id_tienda),
            'proveedores' => $this->model->getProveedores(),
        ];
    }

    public function crear(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Asignar tienda de sesión si no viene en POST
        if (empty($_POST['id_tienda'])) {
            $_POST['id_tienda'] = $_SESSION['id_tienda'] ?? 1;
        }

        $ok = $this->model->crear($_POST);
        if (self::esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Producto creado.' : 'Error al crear producto.']);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Producto creado.' : '❌ Error al crear producto.';
        header('Location: ../index.php?page=inventario');
        exit;
    }

    public function actualizar(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = (int)($_POST['id_producto'] ?? 0);
        $ok = $this->model->actualizar($id, $_POST);
        if (self::esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Producto actualizado.' : 'Error al actualizar.']);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Producto actualizado.' : '❌ Error al actualizar.';
        header('Location: ../index.php?page=inventario');
        exit;
    }

    public function desactivar(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = (int)($_POST['id_producto'] ?? 0);
        $ok = $this->model->desactivar($id);
        if (self::esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok]);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Producto desactivado.' : '❌ Error.';
        header('Location: ../index.php?page=inventario');
        exit;
    }

    public function buscar(): array {
        AuthController::requerirLogin();
        $q = trim($_GET['q'] ?? '');
        return $q ? $this->model->buscar($q) : [];
    }

    public function alertas(): array {
        AuthController::requerirLogin();
        $id_tienda = in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN]) ? 0 : (int)($_SESSION['id_tienda'] ?? 0);
        return $this->model->getConAlerta($id_tienda);
    }

    /** Endpoint JSON: retorna todos los datos para el front */
    public function apiListar(): void {
        AuthController::requerirLogin();
        header('Content-Type: application/json');
        echo json_encode($this->listar());
        exit;
    }

    private static function esAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
