<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClientesController {

    private Cliente $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    public function listar(): array {
        AuthController::requerirLogin();
        $id_tienda = in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN]) ? 0 : (int)($_SESSION['id_tienda'] ?? 0);
        return $this->model->getAll($id_tienda);
    }

    public function crear(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN, ROL_EMPLEADO);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (empty($_POST['id_tienda'])) {
            $_POST['id_tienda'] = $_SESSION['id_tienda'] ?? 1;
        }

        $ok = $this->model->crear($_POST);
        if ($this->esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Cliente registrado.' : 'Error al registrar.']);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Cliente registrado.' : '❌ Error al registrar cliente.';
        header('Location: ../index.php?page=clientes');
        exit;
    }

    public function actualizar(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = (int)($_POST['id_cliente'] ?? 0);
        $ok = $this->model->actualizar($id, $_POST);
        if ($this->esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'msg' => $ok ? 'Cliente actualizado.' : 'Error al actualizar.']);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Cliente actualizado.' : '❌ Error al actualizar.';
        header('Location: ../index.php?page=clientes');
        exit;
    }

    public function desactivar(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = (int)($_POST['id_cliente'] ?? 0);
        $ok = $this->model->desactivar($id);
        if ($this->esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok]);
            exit;
        }
        $_SESSION['msg'] = $ok ? '✅ Cliente desactivado.' : '❌ Error.';
        header('Location: ../index.php?page=clientes');
        exit;
    }

    public function perfil(int $id): ?array {
        AuthController::requerirLogin();
        $cliente = $this->model->getById($id);
        if (!$cliente) return null;
        $cliente['historial'] = $this->model->getHistorialVentas($id);
        return $cliente;
    }

    /** Endpoint JSON */
    public function apiListar(): void {
        AuthController::requerirLogin();
        header('Content-Type: application/json');
        echo json_encode($this->listar());
        exit;
    }

    private function esAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
