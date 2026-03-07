# Job Portal Project

Este proyecto es un portal de empleo basado en CodeIgniter.

## Configuración Realizada
- **Base de Datos:** PostgreSQL (Base de datos: `jobportal`, Usuario: `postgres`)
- **Framework:** CodeIgniter 3
- **Servidor:** PHP Built-in Server (Puerto 5000)
- **Sesiones:** Configuradas para usar la tabla `tbl_sessions` en PostgreSQL.
- **Correcciones de Base de Datos:**
  - Se crearon las tablas esenciales (`tbl_ad_codes`, `tbl_countries`, `tbl_cities`, `tbl_job_industries`, `tbl_companies`, `tbl_post_jobs`, `tbl_logs`).
  - Se corrigió la capitalización de columnas (`employer_ID`, `company_ID`, `industry_ID`) para compatibilidad con PostgreSQL.
  - Se ajustó el tipo de columna `is_featured` a SMALLINT.
  - Se creó la tabla `tbl_sessions` para el manejo de sesiones por base de datos.

## Archivos Importantes
- `ca_app/`: Directorio de la aplicación (Controllers, Models, Views).
- `ca_sys/`: Directorio del sistema CodeIgniter.
- `public/`: Archivos estáticos (CSS, JS, Imágenes).
- `ca_app/config/database.php`: Configuración de la base de datos.
- `ca_app/config/config.php`: Configuración general y de sesiones.
