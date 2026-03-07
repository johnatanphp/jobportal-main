# Portal de Empleo Overall - Diario de Trabajo

**Desarrollador:** Replit Agent

Este archivo sirve como registro diario de los avances y cambios realizados en el proyecto.

---

## Registro de Cambios Recientes

### 06 de Marzo, 2026
- **Migración a PostgreSQL**: Se configuró la base de datos nativa de Replit (PostgreSQL) y se adaptaron los archivos de configuración (`database.php`, `constants.php`).
- **Corrección de Esquema**: Se crearon manualmente las tablas esenciales (`tbl_post_jobs`, `tbl_companies`, `tbl_countries`, `tbl_ad_codes`, `tbl_cities`, `tbl_job_industries`, `tbl_logs`) para asegurar el funcionamiento de la página de inicio.
- **Refactorización de Modelos**: Se modificó el modelo `Posted_job.php` para reemplazar llamadas a procedimientos almacenados de MySQL por consultas SQL directas compatibles con PostgreSQL.
- **Resolución de Errores**: 
    - Se solucionó el error de "relation tbl_ad_codes does not exist".
    - Se corrigió el problema de nombres de columnas en mayúsculas/minúsculas y palabras reservadas (`show` en `tbl_cities`).
- **Documentación**: Se generó un nuevo `README.md` con la documentación principal y se actualizó `replit.md` con los detalles técnicos de la migración.

### 05 de Marzo, 2026
- **Configuración del Entorno:** Se instaló PHP 8.2 y Composer en el entorno de Replit.
- **Automatización de Inicio:** Configuración del flujo de trabajo "Start application".

---

## Credenciales de Acceso (Demo)

### Empresa / Empleador
- **Email:** `empresa@demo.com`
- **Password:** `Password123!`

### Candidato / Usuario
- **Email:** `usuario@demo.com`
- **Password:** `Password123!`

---

## Instrucciones de Uso
1. Ejecutar el workflow **Start Application**.
2. Acceder a la URL generada por Replit (puerto 8000).
