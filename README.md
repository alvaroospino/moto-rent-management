# Moto Rent Management System - Despliegue en Render

Este proyecto es un sistema de gestión de alquiler de motocicletas desarrollado en PHP con base de datos PostgreSQL, preparado para desplegarse en Render usando Docker.

## Requisitos Previos

- Cuenta en [Render](https://render.com)
- Git repository con el código del proyecto

## Pasos para el Despliegue

### 1. Preparar la Base de Datos PostgreSQL en Render

1. Ve a tu dashboard de Render y crea un nuevo **PostgreSQL** database.
2. Elige un nombre para tu base de datos (ej: `moto_rent_db`).
3. Selecciona el plan gratuito o el que prefieras.
4. Una vez creada, copia la **Internal Database URL** (formato: `postgresql://user:password@host:port/dbname`).

### 2. Ejecutar el Schema en la Base de Datos

1. Conecta a tu base de datos PostgreSQL usando una herramienta como pgAdmin, DBeaver, o psql.
2. Ejecuta el contenido del archivo `schema_postgresql.sql` para crear las tablas y estructura de la base de datos.

### 3. Crear el Servicio Web en Render

1. En tu dashboard de Render, crea un nuevo **Web Service**.
2. Conecta tu repositorio de Git.
3. Configura el servicio:
   - **Runtime**: Docker
   - **Dockerfile Path**: `./Dockerfile` (deja por defecto)
   - **Build Command**: (deja vacío)
   - **Start Command**: (deja vacío, usa el CMD del Dockerfile)

### 4. Configurar Variables de Entorno

En la sección **Environment** del servicio web, agrega las siguientes variables de entorno:

```
DB_HOST=tu-host-de-render-postgresql
DB_NAME=tu-nombre-de-base-datos
DB_USERNAME=tu-usuario-postgresql
DB_PASSWORD=tu-password-postgresql
DB_CHARSET=utf8
```

**Nota**: Puedes obtener estos valores de la **Internal Database URL** de tu base de datos PostgreSQL en Render:
- `postgresql://user:password@host:port/dbname`
- DB_HOST = host
- DB_NAME = dbname
- DB_USERNAME = user
- DB_PASSWORD = password

### 5. Desplegar

1. Haz clic en **Create Web Service**.
2. Render construirá y desplegará automáticamente tu aplicación.
3. Una vez completado, obtendrás una URL pública (ej: `https://tu-app.onrender.com`).

## Estructura del Proyecto

- `app/`: Código de la aplicación PHP
- `public/`: Archivos públicos y punto de entrada
- `config/`: Configuraciones de base de datos
- `Dockerfile`: Configuración de Docker
- `schema_postgresql.sql`: Schema de base de datos PostgreSQL

## URLs Amigables

La aplicación está configurada para usar URLs amigables gracias a:
- Apache con mod_rewrite habilitado
- Archivo `.htaccess` en el directorio `public/`
- Configuración en el Dockerfile que sirve desde `public/`

## Notas Importantes

- El contenedor Docker está configurado para servir la aplicación desde el directorio `public/`.
- Asegúrate de que todas las rutas en tu código PHP sean relativas o usen `$_SERVER['DOCUMENT_ROOT']` correctamente.
- Para desarrollo local, puedes usar XAMPP o similar con PostgreSQL.
- El archivo `.env` no se incluye en el contenedor por seguridad (está en `.dockerignore`).

## Solución de Problemas

- **Error de conexión a BD**: Verifica las variables de entorno y que la base de datos esté accesible.
- **Página en blanco**: Revisa los logs de Apache en Render para errores PHP.
- **URLs no funcionan**: Asegúrate de que el `.htaccess` esté en `public/` y que mod_rewrite esté habilitado.

## Próximos Pasos

Después del despliegue inicial:
1. Probar todas las funcionalidades
2. Configurar un dominio personalizado (opcional)
3. Configurar backups automáticos de la base de datos
4. Monitorear el rendimiento y escalar si es necesario
