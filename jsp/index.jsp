<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UNCP FIS | Backend JSP</title>
  <style>
    :root{--bg:#0d1117;--card:#161b22;--border:#30363d;--text:#f0f6fc;--muted:#8b949e;--orange:#f97316;--green:#10b981}
    *{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);font-family:Segoe UI,sans-serif;min-height:100vh;display:grid;place-items:center;padding:24px}
    .card{width:min(680px,100%);background:var(--card);border:1px solid var(--border);border-radius:16px;padding:28px;box-shadow:0 16px 40px #0005}
    h1{margin-top:0}.badge{display:inline-block;padding:5px 10px;border-radius:999px;background:#10b98122;color:var(--green);border:1px solid #10b98155;font-size:.85rem}
    label{display:block;margin-top:14px;font-weight:600} input,select,textarea{width:100%;margin-top:7px;padding:12px;background:#0b0e14;color:var(--text);border:1px solid var(--border);border-radius:8px}
    textarea{min-height:90px}.actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}.btn{border:0;border-radius:8px;padding:11px 14px;background:#f97316;color:white;text-decoration:none;font-weight:700;cursor:pointer}.secondary{background:#374151}.danger{background:#991b1b}
    .links{margin-top:22px;padding-top:18px;border-top:1px solid var(--border)}a{color:#fdba74}
  </style>
</head>
<body>
  <main class="card">
    <span class="badge">Servidor JSP separado • Tomcat</span>
    <h1>Formulario JSP</h1>
    <p>Comparación PHP/Laragon vs JSP/Tomcat con procesamiento HTTP POST, 302 y 405.</p>
    <form action="procesar.jsp" method="post" id="jspForm">
      <label for="nombre">Nombre completo *</label><input id="nombre" name="nombre" required minlength="3">
      <label for="email">Correo electrónico *</label><input id="email" name="email" type="email" required>
      <label for="materia">Asignatura</label><select id="materia" name="materia"><option>Desarrollo de Aplicaciones Web</option><option>Arquitectura Cliente-Servidor</option><option>Sistemas Distribuidos</option></select>
      <label for="comentario">Comentario</label><textarea id="comentario" name="comentario"></textarea>
      <div class="actions"><button class="btn" type="submit">Enviar POST 200</button><a class="btn secondary" href="procesar.jsp?test=redirect">Probar 302 Redirect</a><a class="btn danger" href="procesar.jsp">Probar 405</a></div>
    </form>
    <div class="links">PHP: <a href="http://localhost/php/index.html">http://localhost/php/index.html</a></div>
  </main>
  <script src="app.js"></script>
</body>
</html>
