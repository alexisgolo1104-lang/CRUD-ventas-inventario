<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../index.php');
            exit;
        }

        $correo    = trim($_POST['correo']    ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');

        if (!$correo || !$contrasena) {
            $_SESSION['error'] = 'Completa todos los campos.';
            header('Location: ../index.php');
            exit;
        }

        $model   = new Usuario();
        $usuario = $model->verificarCredenciales($correo, $contrasena);

        if (!$usuario) {
            $_SESSION['error'] = 'Credenciales incorrectas.';
            header('Location: ../index.php');
            exit;
        }

        if (!$usuario['activo']) {
            $_SESSION['error'] = 'Tu cuenta está desactivada.';
            header('Location: ../index.php');
            exit;
        }

        // Registrar último acceso
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $model->registrarAcceso($usuario['id_usuario'], $ip);

        // Guardar sesión con las claves reales de la BD
        $_SESSION['usuario_id']    = $usuario['id_usuario'];
        $_SESSION['usuario_nom']   = $usuario['nombre'];
        $_SESSION['usuario_rol']   = $usuario['rol'];
        $_SESSION['usuario_correo'] = $usuario['correo'];
        $_SESSION['id_tienda']     = $usuario['id_tienda'];

        header('Location: ../index.php?page=dashboard');
        exit;
    }

    public function logout(): void {
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    public static function requerirLogin(): void {
        if (empty($_SESSION['usuario_id'])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode([
                    'ok'    => false,
                    'error' => 'No autenticado',
                    'msg'   => 'Debes iniciar sesión para continuar.',
                ]);
                exit;
            }
            header('Location: ../index.php');
            exit;
        }
    }

    public static function requerirRol(string ...$roles): void {
        self::requerirLogin();
        if (!in_array($_SESSION['usuario_rol'] ?? '', $roles)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode([
                    'ok'    => false,
                    'error' => 'Acceso denegado',
                    'msg'   => 'No tienes permiso para realizar esta acción.',
                ]);
                exit;
            }
            http_response_code(403);
            die('<p>Acceso denegado.</p>');
        }
    }
}
