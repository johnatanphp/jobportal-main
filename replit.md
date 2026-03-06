# Portal de Empleo (Perú) - Notas de Desarrollo

Proyecto basado en CodeIgniter 3 para búsqueda de empleo - configurado para Replit.

## Stack Tecnológico

- **Backend**: PHP 8.2 + CodeIgniter 3 (carpeta personalizada: `ca_app/`, `ca_sys/`)
- **Base de Datos**: PostgreSQL (Replit nativo) via driver `postgre` de CodeIgniter
- **Frontend**: HTML5, CSS3, AdminLTE
- **Gestor de paquetes**: Composer 2.x

## Cambios Realizados para Replit

1. **Configuración de Base de Datos**:
   - Archivo: `ca_app/config/database.php`
   - Se cambió el driver de `mysqli` a `postgre`.
   - Se usan variables de entorno (`PGHOST`, `PGUSER`, etc.) para la conexión.

2. **Ajustes de Esquema (PostgreSQL)**:
   - Las tablas fueron creadas citando los nombres de las columnas (ej: `"company_ID"`) para mantener compatibilidad con el código que espera camelCase, ya que PostgreSQL por defecto convierte todo a minúsculas.
   - La columna `show` en `tbl_cities` fue citada por ser palabra reservada.

3. **Compatibilidad de Consultas**:
   - Se reemplazaron las llamadas `CALL procedure()` por consultas directas `$this->db->get()` en los modelos críticos (ej: `Posted_job.php`) para evitar incompatibilidades con los procedimientos almacenados de MySQL.

4. **URL Dinámica**:
   - `ca_app/config/constants.php` detecta automáticamente la URL de Replit usando `REPLIT_DEV_DOMAIN`.
