# Portal de Empleo (Perú)

Proyecto cargado desde `https://github.com/johnatanphp/jobportal-main.git`.

## Configuración Realizada
1.  **Código**: Se clonó el repositorio y se movieron los archivos a la raíz.
2.  **Base de Datos**: 
    - Se utilizó la base de datos PostgreSQL interna de Replit (`heliumdb`).
    - Se migró el esquema de MySQL a PostgreSQL mediante un script de conversión.
    - Configuración en `ca_app/config/database.php`.
3.  **Servidor Web**:
    - Configurado para ejecutarse con PHP 8.0 (o la versión disponible en Nix).
    - Se creó `router.php` para manejar el enrutamiento de CodeIgniter.
    - `SITE_URL` configurado dinámicamente en `ca_app/config/constants.php`.
4.  **Dependencias**: Instaladas mediante Composer.

## Ejecución
El proyecto está configurado para ejecutarse con el comando:
`php -S 0.0.0.0:8000 router.php`
