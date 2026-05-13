<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Cliente.php';

class VentasController {

    private Venta    $ventaModel;
    private Producto $productoModel;
    private Cliente  $clienteModel;

    public function __construct() {
        $this->ventaModel    = new Venta();
        $this->productoModel = new Producto();
        $this->clienteModel  = new Cliente();
    }

    public function listar(): array {
        AuthController::requerirLogin();
        $id_tienda = in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN]) ? 0 : (int)($_SESSION['id_tienda'] ?? 0);
        return $this->ventaModel->getAll($id_tienda);
    }

    public function registrar(): void {
        AuthController::requerirRol(ROL_SUPERADMIN, ROL_ADMIN, ROL_EMPLEADO);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $items = json_decode($_POST['items'] ?? '[]', true);
        if (empty($items)) {
            $this->responder(false, 'El carrito está vacío.', 'ventas');
            return;
        }

        $venta = [
            'id_usuario' => (int)($_SESSION['usuario_id'] ?? 1),
            'id_cliente' => (int)($_POST['id_cliente'] ?? 0) ?: null,
            'id_tienda'  => (int)($_SESSION['id_tienda'] ?? $_POST['id_tienda'] ?? 1),
            'descuento'  => (float)($_POST['descuento'] ?? 0),
            'notas'      => $_POST['notas'] ?? null,
        ];

        try {
            $result = $this->ventaModel->registrar($venta, $items);
            $ok     = $result !== false;
            $msg    = $ok ? 'Venta registrada.' : 'Error al registrar venta.';
        } catch (\Exception $e) {
            $ok     = false;
            $msg    = 'Error: ' . $e->getMessage();
        }

        if ($this->esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'id_venta' => $result ?: null, 'msg' => $msg]);
            exit;
        }
        $_SESSION['msg']          = $ok ? '✅ ' . $msg : '❌ ' . $msg;
        $_SESSION['ultima_venta'] = $result ?: null;
        header('Location: ../index.php?page=venta-confirmada');
        exit;
    }

    public function detalle(int $id_venta): array {
        AuthController::requerirLogin();
        return [
            'venta'  => $this->ventaModel->getById($id_venta),
            'items'  => $this->ventaModel->getDetalle($id_venta),
        ];
    }

    public function datosFormulario(): array {
        AuthController::requerirLogin();
        $id_tienda = in_array($_SESSION['usuario_rol'] ?? '', [ROL_SUPERADMIN]) ? 0 : (int)($_SESSION['id_tienda'] ?? 0);
        return [
            'productos' => $this->productoModel->getAll($id_tienda),
            'clientes'  => $this->clienteModel->getAll($id_tienda),
        ];
    }

    /** Endpoint JSON: lista de ventas */
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

    private function responder(bool $ok, string $msg, string $page): void {
        if ($this->esAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok, 'msg' => $msg]);
            exit;
        }
        $_SESSION['error'] = $msg;
        header("Location: ../index.php?page=$page");
        exit;
    }
}
