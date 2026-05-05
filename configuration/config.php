<?php
// ── Configuración general de la aplicación ───────────────────
define('APP_NAME',    'HLazcano');
define('APP_VERSION', 'v3.0');
define('APP_LANG',    'es');

// ── Configuración de base de datos ───────────────────────────
define('DB_HOST',     'localhost');
define('DB_NAME',     'hlazcano_db');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_CHARSET',  'utf8mb4');

// ── Rutas base ────────────────────────────────────────────────
define('BASE_URL',    'http://localhost:8080/hlazcano');
define('BASE_PATH',   __DIR__ . '/..');

// ── Sesión ────────────────────────────────────────────────────
define('SESSION_NAME',    'hlazcano_session');
define('SESSION_TIMEOUT',  3600); // 1 hora

// ── Roles del sistema ─────────────────────────────────────────
define('ROL_SUPERADMIN', 'superadmin');
define('ROL_ADMIN',      'admin');
define('ROL_EMPLEADO',   'empleado');
define('ROL_CLIENTE',    'cliente');
