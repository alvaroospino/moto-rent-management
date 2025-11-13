# Migración de PostgreSQL a MySQL

## Tareas Pendientes

### 1. Configuración de Base de Datos
- [x] Actualizar `config/database.php` para MySQL (puerto 3306, usuario root)
- [x] Actualizar `app/core/Database.php` para DSN MySQL

### 2. Modelos y Lógica de Base de Datos
- [x] Actualizar `app/models/BaseModel.php` - cambiar funciones SQL de PostgreSQL a MySQL
- [x] Revisar y actualizar modelos específicos si es necesario
- [x] Actualizar `app/models/Contrato.php` - cambiar DATEDIFF de PostgreSQL a MySQL

### 3. Controladores
- [x] Actualizar `app/controllers/ContratoController.php` - remover lógica condicional pgsql
- [x] Actualizar `register.php` - remover lógica condicional pgsql

### 4. Esquema de Base de Datos
- [x] Crear `schema_mysql.sql` basado en `schema_postgresql.sql`
- [x] Actualizar tipos de datos y sintaxis para MySQL

### 5. Archivos Adicionales
- [x] Revisar otros archivos PHP por lógica específica de PostgreSQL
- [x] Actualizar Dockerfile si es necesario
- [x] Crear vistas faltantes para préstamos (show.php, edit.php)

### 6. Pruebas
- [x] Probar sintaxis PHP de archivos modificados
- [ ] Probar conexión a MySQL
- [ ] Ejecutar migración de datos si es necesario
- [ ] Verificar funcionalidad completa
