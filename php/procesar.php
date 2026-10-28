<?php
/**
 * Universidad Nacional del Centro del Perú - Facultad de Ingeniería de Sistemas
 * Práctica Semana 09: Tecnología Web Backend y Ciclo de Vida
 * Archivo: procesar.php - Procesamiento de solicitud HTTP en Laragon
 * Validación, sanitización y respuestas HTTP.
 */

$startExecutionTime = microtime(true);
$startMemory = memory_get_usage();

require_once __DIR__ . '/config.php';

// Verificación de seguridad de register_globals (restringido en la guía)
if (ini_get('register_globals')) {
    http_response_code(500);
    die("Error crítico de seguridad: register_globals está habilitado.");
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Caso 1: Prueba de redirección 302 (Paso 4 de la guía)
if ($requestMethod === 'GET' && isset($_GET['test']) && $_GET['test'] === 'redirect') {
    $_SESSION['flash_message'] = 'Prueba exitosa de código de estado HTTP 302 (Found / Redirect)';
    http_response_code(302);
    header('Location: procesar.php?status=redirect_completed');
    exit;
}

// Caso 2: Manejo de código 405 Method Not Allowed si se accede directamente por GET sin datos (Paso 4 de la guía)
if ($requestMethod !== 'POST' && !isset($_GET['status'])) {
    http_response_code(405);
    header('Allow: POST');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>405 Method Not Allowed | UNCP FIS</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { background: #0d1117; color: #f0f6fc; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
            .error-card { background: #161b22; border: 1px solid #ef4444; border-radius: 16px; padding: 2.5rem; max-width: 600px; text-align: center; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2); }
            .badge-405 { display: inline-block; background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.35rem 1rem; border-radius: 9999px; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-bottom: 1rem; }
            h1 { font-size: 1.6rem; margin-bottom: 0.75rem; color: #ffffff; }
            p { color: #8b949e; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; }
            .tech-box { background: #0b0e14; border: 1px solid #30363d; border-radius: 8px; padding: 1rem; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; text-align: left; color: #93c5fd; margin-bottom: 1.5rem; }
            .btn { display: inline-block; background: #6366f1; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 600; }
        </style>
    </head>
    <body>
        <div class="error-card">
            <span class="badge-405">HTTP STATUS 405</span>
            <h1>Método HTTP No Permitido</h1>
            <p>Se intentó invocar el endpoint <code>procesar.php</code> mediante el método <strong>GET</strong>. Según la especificación del servicio backend, este controlador solo procesa transacciones seguras bajo el método <strong>POST</strong>.</p>
            <div class="tech-box">
                > REQUEST_METHOD: GET<br>
                > ALLOWED_METHODS: POST<br>
                > HEADER ENVIADO: Allow: POST<br>
                > STATUS_CODE: 405 Method Not Allowed
            </div>
            <a href="index.html" class="btn">⬅️ Regresar al Formulario (POST)</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Caso 3: Procesamiento exitoso mediante POST (Status 200 OK)
$isRedirectNotice = isset($_GET['status']) && $_GET['status'] === 'redirect_completed';

// Extracción y sanitización rigurosa cumpliendo con filter_input y htmlspecialchars
$rawNombre = filter_input(INPUT_POST, 'nombre', FILTER_DEFAULT) ?? '';
$rawEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
$rawMateria = filter_input(INPUT_POST, 'materia', FILTER_DEFAULT) ?? '';
$rawComentario = filter_input(INPUT_POST, 'comentario', FILTER_DEFAULT) ?? '';

// Validación lógica de tipos
$emailValido = filter_var($rawEmail, FILTER_VALIDATE_EMAIL);

// Sanitización de salida contra ataques XSS
$nombre = htmlspecialchars(trim($rawNombre), ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars(trim($rawEmail), ENT_QUOTES, 'UTF-8');
$materia = htmlspecialchars(trim($rawMateria), ENT_QUOTES, 'UTF-8');
$comentario = htmlspecialchars(trim($rawComentario), ENT_QUOTES, 'UTF-8');

// Guardado de estado en sesión (demostración de cómo se mantiene estado en HTTP stateless)
if ($requestMethod === 'POST') {
    $_SESSION['ultimo_registro'] = [
        'nombre' => $nombre,
        'email' => $email,
        'materia' => $materia,
        'fecha' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ];
    http_response_code(200);
}

// Captura de métricas de ejecución
$executionTimeMs = round((microtime(true) - $startExecutionTime) * 1000, 3);
$memoryUsageKb = round((memory_get_usage() - $startMemory) / 1024, 2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNCP FIS | Procesamiento Backend PHP 200 OK</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #0d1117;
            --bg-card: #161b22;
            --border-color: #30363d;
            --primary: #6366f1;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --text-main: #f0f6fc;
            --text-muted: #8b949e;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: var(--font-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        header {
            border-bottom: 1px solid var(--border-color);
            background: rgba(22, 27, 34, 0.9);
            padding: 1.25rem 2rem;
        }
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-title h1 { font-size: 1.15rem; color: #fff; font-weight: 700; }
        .brand-title p { font-size: 0.8rem; color: var(--text-muted); }

        .http-badge {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 0.85rem;
        }

        main {
            flex: 1;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        .alert-redirect {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #fbbf24;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2rem;
        }
        @media (max-width: 900px) {
            .grid-layout { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        h2 { font-size: 1.25rem; margin-bottom: 1.25rem; color: #fff; display: flex; align-items: center; gap: 0.5rem; }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .table-data th, .table-data td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }
        .table-data th {
            color: var(--text-muted);
            font-weight: 600;
            width: 35%;
        }
        .table-data td {
            color: #e5e7eb;
            font-family: var(--font-mono);
        }

        .code-block {
            background: #0b0e14;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: #93c5fd;
            overflow-x: auto;
            max-height: 280px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }
        .metric-box {
            background: #0b0e14;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        .metric-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--accent);
            font-family: var(--font-mono);
        }
        .metric-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 0.25rem;
        }

        .btn-return {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #21262d;
            border: 1px solid var(--border-color);
            color: #f0f6fc;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 2rem;
            transition: all 0.2s ease;
        }
        .btn-return:hover {
            background: #30363d;
            border-color: #8b949e;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-container">
            <div class="brand-title">
                <h1>UNCP FIS | Respuesta del Servidor Backend (PHP)</h1>
                <p>Servidor Web: Laragon (Apache/2.4) • PHP <?php echo PHP_VERSION; ?></p>
            </div>
            <div class="http-badge">
                STATUS: <?php echo http_response_code(); ?> <?php echo (http_response_code() === 200 ? 'OK' : 'FOUND'); ?>
            </div>
        </div>
    </header>

    <main>
        <?php if ($isRedirectNotice): ?>
            <div class="alert-redirect">
                <strong>🔄 Redirección HTTP 302 Verificada:</strong> 
                <?php echo htmlspecialchars($_SESSION['flash_message'] ?? 'Redirección procesada con éxito.'); ?>
            </div>
        <?php endif; ?>

        <div class="grid-layout">
            <!-- Datos Procesados y Sanitizados -->
            <section class="card">
                <h2>📋 Datos Validados del Formulario (POST)</h2>
                <table class="table-data">
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo !empty($nombre) ? $nombre : '<span style="color: #6b7280;">(No provisto)</span>'; ?></td>
                    </tr>
                    <tr>
                        <th>Email Sanitizado:</th>
                        <td>
                            <?php if ($emailValido): ?>
                                <span style="color: #10b981;"><?php echo $email; ?> (Válido)</span>
                            <?php else: ?>
                                <span style="color: #ef4444;"><?php echo !empty($email) ? $email . ' (Formato inválido)' : '(Vacío)'; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Materia / Asignatura:</th>
                        <td><?php echo !empty($materia) ? $materia : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Comentario (Safe HTML):</th>
                        <td><?php echo !empty($comentario) ? nl2br($comentario) : '<span style="color: #6b7280;">(Sin comentario)</span>'; ?></td>
                    </tr>
                    <tr>
                        <th>ID de Sesión (PHP):</th>
                        <td><?php echo htmlspecialchars(session_id()); ?></td>
                    </tr>
                    <tr>
                        <th>Almacenamiento Sesión:</th>
                        <td>
                            <?php 
                                if (isset($_SESSION['ultimo_registro'])) {
                                    echo "OK - Registrado en \$_SESSION";
                                } else {
                                    echo "Sesión activa sin registro previo";
                                }
                            ?>
                        </td>
                    </tr>
                </table>

                <div class="metrics-grid">
                    <div class="metric-box">
                        <div class="metric-value"><?php echo $executionTimeMs; ?> ms</div>
                        <div class="metric-label">Tiempo de Ejecución</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-value"><?php echo $memoryUsageKb; ?> KB</div>
                        <div class="metric-label">Memoria Asignada</div>
                    </div>
                </div>

                <a href="index.html" class="btn-return">⬅️ Volver al Formulario</a>
            </section>

            <!-- Inspección de Headers HTTP y Request -->
            <section class="card">
                <h2>🔍 Headers & Metadatos de la Petición HTTP</h2>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                    Variables superglobales inspeccionadas desde <span style="font-family: var(--font-mono); color: #93c5fd;">$_SERVER</span>:
                </p>
                <div class="code-block">
REQUEST_METHOD : <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? ''); ?><br>
SERVER_PROTOCOL: <?php echo htmlspecialchars($_SERVER['SERVER_PROTOCOL'] ?? ''); ?><br>
HTTP_HOST      : <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? ''); ?><br>
HTTP_USER_AGENT: <?php echo htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 70)); ?>...<br>
CONTENT_TYPE   : <?php echo htmlspecialchars($_SERVER['CONTENT_TYPE'] ?? 'N/A'); ?><br>
CONTENT_LENGTH : <?php echo htmlspecialchars($_SERVER['CONTENT_LENGTH'] ?? '0'); ?> bytes<br>
SERVER_SOFTWARE: <?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? ''); ?><br>
REMOTE_ADDR    : <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? ''); ?><br>
GATEWAY_INTERFACE: <?php echo htmlspecialchars($_SERVER['GATEWAY_INTERFACE'] ?? ''); ?>
                </div>

                <h2 style="margin-top: 1.5rem; font-size: 1.1rem;">💡 Ciclo de Vida Demostrado</h2>
                <p style="font-size: 0.85rem; color: var(--text-muted);">
                    Al finalizar el renderizado de este template, el proceso de PHP invocará su fase de <strong>Destrucción / Garbage Collection</strong>, liberando los <code><?php echo $memoryUsageKb; ?> KB</code> consumidos. Las variables locales perecen, pero el estado persiste en la sesión protegida en disco/cookie.
                </p>
            </section>
        </div>
    </main>

</body>
</html>
