<?php
require_once __DIR__ . '/../configuration/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    private function isJsonRequest(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
    }

    private function jsonResponse(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function generarContrasenaTemporal(int $length = 10): string {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $pass;
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($this->isJsonRequest()) {
                $this->jsonResponse(['ok' => false, 'msg' => 'Método inválido.']);
            }
            header('Location: ../index.php');
            exit;
        }

        $usuarioInput = trim($_POST['usuario'] ?? '');
        $contrasena   = trim($_POST['contrasena'] ?? '');

        if (!$usuarioInput || !$contrasena) {
            if ($this->isJsonRequest()) {
                $this->jsonResponse(['ok' => false, 'msg' => 'Completa todos los campos.']);
            }
            $_SESSION['error'] = 'Completa todos los campos.';
            header('Location: ../index.php');
            exit;
        }

        $model   = new Usuario();
        $usuario = $model->verificarCredenciales($usuarioInput, $contrasena);

        if (!$usuario) {
            if ($this->isJsonRequest()) {
                $this->jsonResponse(['ok' => false, 'msg' => 'Credenciales incorrectas.']);
            }
            $_SESSION['error'] = 'Credenciales incorrectas.';
            header('Location: ../index.php');
            exit;
        }

        if (!$usuario['activo']) {
            if ($this->isJsonRequest()) {
                $this->jsonResponse(['ok' => false, 'msg' => 'Tu cuenta está desactivada.']);
            }
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

        if ($this->isJsonRequest()) {
            $this->jsonResponse([
                'ok'     => true,
                'nombre' => $usuario['nombre'],
                'rol'    => $usuario['rol'],
                'correo' => $usuario['correo'],
                'tienda' => $usuario['id_tienda'],
            ]);
        }

        header('Location: ../index.php?page=dashboard');
        exit;
    }

    public function registro(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['ok' => false, 'msg' => 'Método inválido.']);
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $correo      = trim($_POST['correo'] ?? '');
        $contrasena  = trim($_POST['contrasena'] ?? '');
        $confirmar   = trim($_POST['confirmar'] ?? '');
        $rol         = trim($_POST['rol'] ?? 'empleado');
        $id_tienda   = max(1, (int)($_POST['id_tienda'] ?? 1));

        if (!$nombre || !$correo || !$contrasena || !$confirmar) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Completa todos los campos de registro.']);
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Ingresa un correo válido.']);
        }

        if ($contrasena !== $confirmar) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Las contraseñas no coinciden.']);
        }

        if (strlen($contrasena) < 6) {
            $this->jsonResponse(['ok' => false, 'msg' => 'La contraseña debe tener al menos 6 caracteres.']);
        }

        $rol = in_array($rol, ['admin', 'empleado']) ? $rol : 'empleado';

        $model = new Usuario();
        if ($model->existeCorreo($correo)) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Ya existe una cuenta con ese correo.']);
        }

        $ok = $model->crear([
            'nombre'      => $nombre,
            'correo'      => $correo,
            'contrasena'  => $contrasena,
            'rol'         => $rol,
            'id_tienda'   => $id_tienda,
        ]);

        if ($ok) {
            $this->jsonResponse(['ok' => true, 'msg' => 'Cuenta creada correctamente. Ya puedes iniciar sesión.']);
        }

        $this->jsonResponse(['ok' => false, 'msg' => 'No se pudo crear la cuenta. Intenta de nuevo.']);
    }

    public function recuperarPassword(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['ok' => false, 'msg' => 'Método inválido.']);
        }

        $correo = trim($_POST['correo'] ?? '');
        if (!$correo) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Ingresa tu correo para recuperar contraseña.']);
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['ok' => false, 'msg' => 'Ingresa un correo válido.']);
        }

        $model = new Usuario();
        $usuario = $model->getByCorreo($correo);
        if (!$usuario || !$usuario['activo']) {
            $this->jsonResponse(['ok' => false, 'msg' => 'No se encontró una cuenta activa con ese correo.']);
        }

        $tempPass = $this->generarContrasenaTemporal(10);
        $ok = $model->resetPasswordByCorreo($correo, $tempPass);
        if ($ok) {
            $this->jsonResponse([
                'ok'           => true,
                'msg'          => 'Se generó una contraseña temporal. Inicia sesión y cámbiala después.',
                'tempPassword' => $tempPass,
            ]);
        } else {
            $this->jsonResponse(['ok' => false, 'msg' => 'No se pudo restablecer la contraseña. Intenta más tarde.']);
        }
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
