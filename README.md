# Portal de Empleo Overall - Diario de Trabajo

**Desarrollador:** [johnatanphp](https://github.com/johnatanphp)

Este archivo sirve como registro diario de los avances y cambios realizados en el proyecto.

---

## Registro de Cambios Recientes

### 05 de Marzo, 2026
- **Configuración del Entorno:** Se instaló PHP 8.2, MariaDB y Composer en el entorno de Replit.
- **Automatización de Inicio:** Se creó el script `start.sh` que gestiona de forma automática:
    - La búsqueda de rutas absolutas para los binarios del sistema.
    - La inicialización del directorio de datos de MariaDB.
    - La descarga del respaldo de la base de datos desde Google Drive.
    - La creación de la base de datos `jobportal` y la importación del archivo SQL.
    - El levantamiento del servidor MariaDB y el servidor PHP integrado.
- **Configuración de la Aplicación:**
    - Generación de `ca_app/config/constants.php` y `ca_app/config/database.php`.
    - Actualización dinámica de `SITE_URL` para que coincida con la URL del entorno actual.
    - Ajuste de credenciales de base de datos para acceso local.
- **Workflow:** Configuración del flujo de trabajo "Start application" para un despliegue rápido.

### 04 de Marzo, 2026
- **Clonación del Repositorio:** Se realizó la carga inicial del código fuente desde el repositorio oficial.
- **Análisis de Estructura:** Identificación de los componentes de CodeIgniter y requerimientos de sistema.

---

## Instrucciones de Uso Diario
1. Ejecutar el workflow **Start application**.
2. El sistema verificará automáticamente la integridad de la base de datos.
3. Acceder a la vista previa web en el puerto 5000.
