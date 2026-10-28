<?php
/**
 * Universidad Nacional del Centro del Perú - Facultad de Ingeniería de Sistemas
 * Práctica Semana 09: Tecnología Web Backend y Ciclo de Vida
 * Archivo: config.php - Configuración global y directivas de seguridad
 * Configuración de directivas de seguridad y headers HTTP.
 */

// 1. Configuración de reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2. Zona horaria del servidor
date_default_timezone_set('America/Lima');

// 3. Constantes de la aplicación
define('APP_NAME', 'UNCP FIS - Laboratorio 09 (PHP & Backend Architecture)');
define('APP_VERSION', '1.0.0');
define('SERVER_RUNTIME', 'Laragon (Apache 2.4 + PHP ' . PHP_VERSION . ')');

// 4. Parámetros de seguridad de sesión (mitigación de Session Hijacking y XSS)
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 1800, // 30 minutos
        'cookie_httponly' => true, // Evita acceso desde JavaScript
        'cookie_samesite' => 'Lax', // Protección CSRF básica
        'use_strict_mode' => true
    ]);
}

// 5. Cabeceras HTTP de seguridad global
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('X-Powered-By: PHP/' . PHP_VERSION . ' (Laragon/UNCP-FIS)');
