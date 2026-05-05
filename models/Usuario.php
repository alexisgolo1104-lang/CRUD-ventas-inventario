<?php
require_once __DIR__ . '/../configuration/database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        return $this->db->query("
            SELECT u.*, t.nombre AS tienda_nombre
            FROM usuarios u
            LEFT JOIN tiendas t ON u.id_tienda = t.id_tienda
            ORDER BY u.nombre
        ")->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getByCorreo(string $correo): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch() ?: null;
    }

    public function crear(array $d): bool {
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nombre, correo, contrasena, rol, id_tienda, activo, creado_en)
            VALUES (?,?,?,?,?,1,CURDATE())
        ");
        return $stmt->execute([
            $d['nombre'],
            $d['correo'],
            $d['contrasena'],          // en producción: password_hash()
            $d['rol']      ?? 'empleado',
            (int)($d['id_tienda'] ?? 1),
        ]);
    }

    public function actualizar(int $id, array $d): bool {
        $stmt = $this->db->prepare("
            UPDATE usuarios SET nombre=?, correo=?, rol=?, id_tienda=? WHERE id_usuario=?
        ");
        return $stmt->execute([
            $d['nombre'],
            $d['correo'],
            $d['rol'],
            (int)($d['id_tienda'] ?? 1),
            $id,
        ]);
    }

    public function cambiarPassword(int $id, string $nueva): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET contrasena=? WHERE id_usuario=?");
        return $stmt->execute([$nueva, $id]);   // en producción: password_hash()
    }

    public function darDeBaja(int $id): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo=0 WHERE id_usuario=?");
        return $stmt->execute([$id]);
    }

    public function reactivar(int $id): bool {
        $stmt = $this->db->prepare("UPDATE usuarios SET activo=1 WHERE id_usuario=?");
        return $stmt->execute([$id]);
    }

    /**
     * Verifica credenciales. La BD guarda contraseña en texto plano (diseño existente);
     * retorna el usuario si coincide o null si no.
     */
    public function verificarCredenciales(string $correo, string $contrasena): ?array {
        $usuario = $this->getByCorreo($correo);
        if (!$usuario) return null;
        // Comparación directa (esquema actual usa texto plano)
        if ($usuario['contrasena'] !== $contrasena) return null;
        return $usuario;
    }

    public function registrarAcceso(int $id, string $ip): void {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET ultimo_acceso=CURDATE(), ip_ultimo=? WHERE id_usuario=?"
        );
        $stmt->execute([$ip, $id]);
    }

    public function getTiendas(): array {
        return $this->db->query("SELECT * FROM tiendas WHERE activo=1 ORDER BY nombre")->fetchAll();
    }
}
