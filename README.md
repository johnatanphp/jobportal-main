# Portal de Empleo Overall - Diario de Trabajo

**Desarrollador:** Replit Agent

Este archivo sirve como registro diario de los avances y cambios realizados en el proyecto.

---

## Registro de Cambios Recientes

### 07 de Marzo, 2026
- **Integración de Usuarios**: Se crearon tablas y cuentas de demo para administrador, empresa y usuario.
- **Configuración de Despliegue**: Se configuró el proyecto para despliegue en Replit con autoscaling.
- **Documentación de Credenciales**: Se agregaron todas las rutas de login y credenciales de prueba en el README.

### 06 de Marzo, 2026
- **Migración a PostgreSQL**: Se configuró la base de datos nativa de Replit (PostgreSQL) y se adaptaron los archivos de configuración (`database.php`, `constants.php`).
- **Corrección de Esquema**: Se crearon manualmente las tablas esenciales (`tbl_post_jobs`, `tbl_companies`, `tbl_countries`, `tbl_ad_codes`, `tbl_cities`, `tbl_job_industries`, `tbl_logs`) para asegurar el funcionamiento de la página de inicio.
- **Refactorización de Modelos**: Se modificó el modelo `Posted_job.php` para reemplazar llamadas a procedimientos almacenados de MySQL por consultas SQL directas compatibles con PostgreSQL.
- **Resolución de Errores**: 
    - Se solucionó el error de "relation tbl_ad_codes does not exist".
    - Se corrigió el problema de nombres de columnas en mayúsculas/minúsculas y palabras reservadas (`show` en `tbl_cities`).
- **Documentación**: Se generó un nuevo `README.md` con la documentación principal y se actualizó `replit.md` con los detalles técnicos de la migración.

---

## Credenciales de Acceso (Demo)

### Administrador
- **URL Login:** `/admin/login`
- **Email:** `admin@overall.pe`
- **Password:** `Password123!`

### Empresa / Empleador
- **URL Login:** `/employer/login`
- **Email:** `empresa@demo.com`
- **Password:** `Password123!`

### Candidato / Usuario (Job Seeker)
- **URL Login:** `/login`
- **Email:** `usuario@demo.com`
- **Password:** `Password123!`

---

## Tipos de Usuarios

- **Admin**: Acceso completo a panel de administración, gestión de empresas, usuarios y configuración del sistema.
- **Empresa/Empleador**: Gestión de ofertas de empleo, candidatos, procesos de selección y reportes.
- **Usuario/Cliente**: Búsqueda de empleos, aplicación a ofertas, gestión de perfil y documentos.

---

## Instrucciones de Uso

1. Ejecutar el workflow **Start Application**.
2. Acceder a la URL generada por Replit (puerto 5000).
3. Usar una de las credenciales demo según el rol que se desee probar.

---

## Estado del Proyecto

- ✅ Configuración de base de datos PostgreSQL
- ✅ Creación de tablas principales
- ✅ Cuentas de demo para pruebas
- ✅ Configuración de despliegue
- 🔄 **EN PROGRESO**: Corrección de errores PHP y optimización de sesiones para diferentes roles

---

## Próximos Pasos

- Revisar y corregir errores PHP en vistas de login
- Optimizar sistema de sesiones y permisos por rol
- Completar esquema de base de datos con tablas faltantes
- Pruebas de funcionalidad end-to-end
