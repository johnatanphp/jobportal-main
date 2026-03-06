# Portal de Empleo (Perú)

Proyecto basado en CodeIgniter para búsqueda de empleo.

## Estructura Estándar para Servidores (Local/Producción)

Esta estructura está preparada para ser desplegada en cualquier servidor LAMP (Linux, Apache, MySQL, PHP) o WAMP (Windows) de forma rápida.

### 1. Requisitos
- PHP 7.4 o superior (Compatible con PHP 8.x)
- MySQL / MariaDB
- Apache con `mod_rewrite` habilitado

### 2. Instalación Local / Servidor
1.  **Archivos**: Copia todo el contenido de la raíz a tu directorio público (`www`, `public_html` o `htdocs`).
2.  **Base de Datos**: 
    - Crea una base de datos llamada `jobportal_db` (o el nombre que prefieras).
    - Importa el archivo `jobportal_full.sql` incluido en la raíz.
    - Configura el acceso en `ca_app/config/database.php`.
3.  **Configuración de URL**:
    - El archivo `ca_app/config/constants.php` detecta automáticamente la URL base. No es necesario editarlo manualmente para cambios de dominio simples.
4.  **Apache (.htaccess)**:
    - Se incluye un archivo `.htaccess` estándar para eliminar `index.php` de las URLs.

### 3. Notas de Desarrollo
- **Framework**: CodeIgniter 3.
- **Frontend**: HTML5, CSS3 (AdminLTE para el panel).
- **Entorno**: Configurado en `development` por defecto en `index.php`.
