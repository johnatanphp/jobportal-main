# Portal de Empleo (Perú)

Proyecto basado en CodeIgniter 3 para búsqueda de empleo - configurado para Replit.

## Stack Tecnológico

- **Backend**: PHP 8.2 + CodeIgniter 3 (carpeta personalizada: `ca_app/`, `ca_sys/`)
- **Base de Datos**: PostgreSQL (Replit nativo) via driver `postgre` de CodeIgniter
- **Frontend**: HTML5, CSS3, AdminLTE
- **Compositor de paquetes**: Composer 2.x

## Estructura del Proyecto

- `ca_app/` - Directorio de la aplicación (controladores, modelos, vistas, config)
- `ca_sys/` - Framework CodeIgniter 3 (renombrado de `system/`)
- `public/` - Assets estáticos y uploads
- `vendor/` - Dependencias de Composer
- `index.php` - Punto de entrada principal
- `router.php` - Router para PHP built-in server

## Configuración de Replit

- **PHP**: 8.2 (módulo `php-8.2`)
- **Puerto**: 8000 (mapeado a puerto externo 80)
- **Base de datos**: PostgreSQL (variables de entorno: `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`)
- **Workflow**: `php -S 0.0.0.0:8000 router.php`

## Archivos de Configuración

- `ca_app/config/database.php` - Configuración de BD (usa variables de entorno PostgreSQL)
- `ca_app/config/constants.php` - Constantes globales (URL base detectada automáticamente desde `REPLIT_DEV_DOMAIN`)
- `ca_app/config/config.php` - Configuración de CodeIgniter

## Dependencias Clave (Composer)

- `mpdf/mpdf` - Generación de PDFs
- `phpoffice/phpspreadsheet` - Exportación Excel
- `aws/aws-sdk-php` - Integración AWS S3
- `giggsey/libphonenumber-for-php` - Validación teléfonos

## Notas de Migración

- Proyecto original usaba MySQL/MariaDB; migrado a PostgreSQL para Replit
- El driver `postgre` de CodeIgniter quita comillas en identificadores automáticamente
- Algunas tablas fueron creadas manualmente en PostgreSQL con columnas en el formato correcto
- Stored procedures MySQL reemplazados por queries directas en el modelo `Posted_job.php`
- El archivo `jobportal_full.sql` es un redirect de Google Drive (no SQL real)
