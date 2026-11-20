# Guia rapida: OWASP ZAP para la app Agencia

Esta actividad se puede hacer con MySQL local o con Supabase. ZAP analiza la aplicacion por HTTP; no necesita conectarse directamente a la base de datos.

## 1. Preparar la app

1. Si usas XAMPP, deja `AGENCIA_DB_DRIVER=mysql` en `.env`.
2. Si usas Supabase, ejecuta `supabase/schema.sql` en el SQL Editor y configura `.env` con:

```env
AGENCIA_DB_DRIVER=pgsql
AGENCIA_DB_HOST=db.TU-PROYECTO.supabase.co
AGENCIA_DB_PORT=5432
AGENCIA_DB_NAME=postgres
AGENCIA_DB_SCHEMA=agencia
AGENCIA_DB_USER=postgres
AGENCIA_DB_PASSWORD=TU_PASSWORD
```

3. Abre la app en el navegador, por ejemplo:

El schema recomendado para Supabase es `agencia`. La conexion PHP usa `search_path`, asi que las consultas pueden seguir usando `usuarios`, `paquetes`, `destinos` y `reservas` sin escribir `agencia.usuarios` en cada archivo.

```text
http://localhost/Agencia/
```

## 2. Escaneo basico con ZAP

1. Abre OWASP ZAP.
2. Selecciona "Automated Scan".
3. En "URL to attack", escribe la URL local de la app.
4. Ejecuta primero el spider/crawler.
5. Ejecuta el active scan solo contra tu entorno de pruebas.

## 3. Escaneo autenticado

Para que ZAP revise rutas de usuario/admin:

1. Crea un usuario de prueba.
2. En ZAP, configura el navegador con el proxy de ZAP.
3. Inicia sesion manualmente en la app.
4. Verifica que ZAP capture las rutas privadas.
5. Ejecuta el active scan sobre el sitio capturado.

## 4. Evidencias para el informe

Incluye capturas de:

1. URL objetivo configurada en ZAP.
2. Arbol de sitios detectado.
3. Alertas encontradas por nivel de riesgo.
4. Detalle de al menos una alerta relevante.
5. Exportacion del reporte HTML/PDF.

## 5. Notas de seguridad

- Usa una base de datos de prueba.
- No ejecutes active scan contra produccion.
- No subas claves de Supabase ni Hunter.io al repositorio.
- Revisa especialmente SQL Injection, XSS, cookies sin flags seguros y formularios sin proteccion CSRF.
