# Portal de Empleo (Perú) - Notas de Desarrollo

Proyecto basado en CodeIgniter 3 para búsqueda de empleo - configurado para Replit.

## Stack Tecnológico

- **Backend**: PHP 8.2 + CodeIgniter 3 (carpeta personalizada: `ca_app/`, `ca_sys/`)
- **Base de Datos**: PostgreSQL (Replit nativo) via driver `postgre` de CodeIgniter
- **Frontend**: HTML5, CSS3, AdminLTE
- **Gestor de paquetes**: Composer 2.x

## Configuración de Replit

- **Servidor**: `php -d memory_limit=512M -S 0.0.0.0:5000 router.php`
- **Puerto**: 5000
- **Entrada**: `router.php` → `index.php` (CodeIgniter)
- **Dependencias**: Composer (ejecutar `composer install` si falta la carpeta `vendor/`)

## Archivos de Configuración Creados

1. **`ca_app/config/constants.php`** (no rastrear en git):
   - Define `SITE_URL` dinámicamente usando `REPLIT_DEV_DOMAIN`
   - Contiene credenciales SMTP, keys de API (LinkedIn, Facebook)

2. **`ca_app/config/database.php`** (no rastrear en git):
   - Driver: `postgre`
   - Usa variables de entorno: `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`, `PGPORT`

3. **`schema.sql`**:
   - Esquema PostgreSQL completo generado desde los modelos
   - Incluye todas las tablas necesarias para el funcionamiento del portal
   - Incluye datos iniciales (países, ciudades, industrias, etc.)

## Cambios Realizados para Replit

1. **Configuración de Base de Datos**:
   - Driver: `postgre` (PostgreSQL nativo de Replit)
   - Variables de entorno para conexión segura

2. **URL Dinámica**:
   - `ca_app/config/constants.php` detecta automáticamente la URL usando `REPLIT_DEV_DOMAIN`

3. **Esquema de Base de Datos**:
   - Creado desde cero ya que no había SQL dump disponible
   - Columnas corregidas para compatibilidad con el código:
     - `tbl_post_jobs`: usa `sts`, `last_date`, `dated`, `is_featured`, `industry_ID`
     - `tbl_companies`: usa `sts` para estado activo
     - `tbl_logs`: incluye `session_data`, `get_data`, `post_data`, `server_data`
     - `tbl_job_industries`: incluye `top_category`

4. **Dependencias de Composer**:
   - `vendor/` instalado via `composer install --no-dev`
   - Incluye: AWS SDK, mPDF, PhpSpreadsheet, GeoIP2, Spatie URL Signer

## Variables de Entorno Requeridas

- `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`, `PGPORT` - Conexión PostgreSQL (auto-configuradas)
- `REPLIT_DEV_DOMAIN` - URL del dominio (auto-configurada)
- Opcionales: `SMTP_HOST`, `SMTP_USER`, `SMTP_PASS`, `ADMIN_EMAIL`

## Notas de Compatibilidad

- Las tablas usan comillas dobles para columnas con mayúsculas (ej: `"industry_ID"`)
- PostgreSQL es case-sensitive con identificadores; las columnas en el código usan CamelCase
- Los procedimientos almacenados MySQL (`CALL procedure()`) no son compatibles con PostgreSQL
