<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.time.LocalDateTime,java.time.format.DateTimeFormatter" %>
<%!
  String escapeHtml(String value) {
    if (value == null) return "";
    return value.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace("\"", "&quot;").replace("'", "&#39;");
  }
%>
<%
  long startTime = System.nanoTime();
  String method = request.getMethod();

  if ("GET".equals(method) && "redirect".equals(request.getParameter("test"))) {
    session.setAttribute("flash_message", "Prueba exitosa de código HTTP 302 en JSP");
    response.sendRedirect("procesar.jsp?status=redirect_completed");
    return;
  }

  if (!"POST".equals(method) && !"redirect_completed".equals(request.getParameter("status"))) {
    response.setStatus(405);
    response.setHeader("Allow", "POST");
    response.setHeader("X-Backend", "JSP/Tomcat");
  %>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>405 | JSP</title>
<style>body{margin:0;background:#0d1117;color:#f0f6fc;font-family:Segoe UI,sans-serif;display:grid;place-items:center;min-height:100vh}.card{max-width:620px;border:1px solid #ef4444;border-radius:16px;background:#161b22;padding:32px;text-align:center}.badge{color:#f87171;font-weight:bold}.btn{display:inline-block;margin-top:16px;padding:10px 16px;border-radius:8px;background:#6366f1;color:white;text-decoration:none}</style></head>
<body><div class="card"><span class="badge">HTTP STATUS 405</span><h1>Método no permitido</h1><p>El endpoint JSP solo acepta solicitudes POST directas.</p><p><strong>Permitido:</strong> POST</p><a class="btn" href="index.jsp">Volver al formulario</a></div></body></html>
  <%
    return;
  }

  String nombre = escapeHtml(request.getParameter("nombre") == null ? "" : request.getParameter("nombre").trim());
  String email = escapeHtml(request.getParameter("email") == null ? "" : request.getParameter("email").trim());
  String materia = escapeHtml(request.getParameter("materia") == null ? "" : request.getParameter("materia").trim());
  String comentario = escapeHtml(request.getParameter("comentario") == null ? "" : request.getParameter("comentario").trim());
  boolean redirectNotice = "redirect_completed".equals(request.getParameter("status"));

  if ("POST".equals(method)) {
    session.setAttribute("ultimo_registro_jsp", nombre);
    session.setAttribute("ultimo_correo_jsp", email);
    response.setStatus(200);
  }
  long elapsedMs = (System.nanoTime() - startTime) / 1_000_000;
%>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Resultado JSP 200</title>
<style>:root{--bg:#0d1117;--card:#161b22;--border:#30363d;--text:#f0f6fc;--muted:#8b949e;--green:#10b981}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:Segoe UI,sans-serif}header{border-bottom:1px solid var(--border);padding:18px 24px}main{max-width:900px;margin:auto;padding:28px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}@media(max-width:760px){.grid{grid-template-columns:1fr}}.card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:22px}.badge{float:right;color:var(--green);border:1px solid #10b98155;background:#10b98122;border-radius:999px;padding:6px 10px}.notice{background:#f59e0b22;border:1px solid #f59e0b55;color:#fbbf24;border-radius:10px;padding:12px;margin-bottom:18px}.btn{display:inline-block;margin-top:18px;padding:10px 16px;border-radius:8px;background:#f97316;color:white;text-decoration:none}.muted{color:var(--muted)}pre{white-space:pre-wrap;color:#93c5fd}</style></head>
<body><header><strong>UNCP FIS • Backend JSP</strong><span class="badge">HTTP 200 OK</span></header><main>
<% if (redirectNotice) { %><div class="notice">Redirección HTTP 302 completada. El servidor llegó aquí mediante GET.</div><% } %>
<div class="grid"><section class="card"><h2>Datos procesados</h2><p><strong>Nombre:</strong> <%= nombre.isEmpty() ? "No enviado" : nombre %></p><p><strong>Email:</strong> <%= email.isEmpty() ? "No enviado" : email %></p><p><strong>Materia:</strong> <%= materia.isEmpty() ? "No enviada" : materia %></p><pre><strong>Comentario:</strong>
<%= comentario.isEmpty() ? "Sin comentario" : comentario %></pre></section><section class="card"><h2>Métricas JSP</h2><p><strong>Servidor:</strong> Apache Tomcat</p><p><strong>Método:</strong> <%= method %></p><p><strong>Tiempo:</strong> <%= elapsedMs %> ms</p><p><strong>Sesión:</strong> <%= session.getId() %></p><p><strong>Fecha:</strong> <%= LocalDateTime.now().format(DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss")) %></p></section></div><a class="btn" href="index.jsp">Volver al formulario JSP</a></main></body></html>
