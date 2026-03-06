# Portal de Empleo (Perú)

## Tecnologías
- PHP 8.3 (CodeIgniter)
- PostgreSQL (Base de datos interna de Replit)
- Servidor: PHP Built-in Server

## Configuración Realizada
1. **Base de Datos**: Se migró el esquema de MySQL a PostgreSQL y se configuró en `ca_app/config/database.php`.
2. **URL del Sitio**: Configurada dinámicamente en `ca_app/config/constants.php`.
3. **Enrutamiento**: Se creó `router.php` para manejar las rutas de CodeIgniter en el servidor interno de PHP.
4. **Dependencias**: PHP 8.3 y Composer instalados.

## Cómo ejecutar
El proyecto se inicia automáticamente.
Comando manual: `php -S 0.0.0.0:8000 router.php`
