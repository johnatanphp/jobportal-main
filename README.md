# Portal de Empleo Overall - Sistema de Gestión de Empleo

Plataforma web basada en CodeIgniter 3 (PHP 8.2) para la gestión integrada de ofertas de empleo, candidatos y perfiles empresariales.

---

## Credenciales de Acceso (Demo)

### Administrador
- **URL:** `/admin/login`
- **Email:** `admin@overall.pe`
- **Contraseña:** `Password123!`

### Empresa / Empleador
- **URL:** `/company_login` (o `/employer-login`)
- **Email:** `empresa@demo.com`
- **Contraseña:** `Password123!`

### Candidato / Usuario (Job Seeker)
- **URL:** `/login`
- **Email:** `usuario@demo.com`
- **Contraseña:** `Password123!`

---

## Tipos de Usuarios

- **Admin**: Acceso completo a panel de administración, gestión de empresas, usuarios y configuración del sistema.
- **Empresa/Empleador**: Gestión de ofertas de empleo, candidatos, procesos de selección y reportes.
- **Usuario/Candidato**: Búsqueda de empleos, aplicación a ofertas, gestión de perfil y documentos.

---

## Instrucciones de Inicio

1. Verificar que el workflow **Start Application** está en ejecución
2. Acceder a la URL generada por Replit (puerto 5000)
3. Usar una de las credenciales demo según el rol deseado
4. Las sesiones se mantienen mediante cookies de sesión

---

## Estructura de Tecnología

- **Framework:** CodeIgniter 3
- **PHP:** 8.2
- **Base de Datos:** PostgreSQL (nativa de Replit)
- **Frontend:** Bootstrap 4, jQuery, Select2

---

## Tablas Principales de Base de Datos

| Tabla | Descripción |
|-------|-------------|
| `tbl_job_seekers` | Perfil de candidatos/usuarios |
| `tbl_employers` | Perfil de empresas/empleadores |
| `tbl_admin` | Administradores del sistema |
| `tbl_post_jobs` | Ofertas de empleo publicadas |
| `tbl_companies` | Datos empresariales |
| `tbl_countries` | Países |
| `tbl_cities` | Ciudades |
| `tbl_job_industries` | Industrias/sectores |
| `tbl_ad_codes` | Códigos de anuncios |
| `tbl_logs` | Registros de auditoría |
| `tbl_app_config` | Configuración global |

---

## Estado del Proyecto

- ✅ Configuración PostgreSQL en Replit
- ✅ Base de datos con tablas principales
- ✅ Cuentas demo para todos los roles
- ✅ Sistema de sesiones por rol
- ✅ Formularios de login funcionales
- 🔄 **En desarrollo:** Dashboards específicos por rol, integración de features

---

## Notas de Desarrollo

- **reCAPTCHA:** Actualmente deshabilitado para facilitar pruebas
- **URLs de Login:** Ver sección "Credenciales de Acceso"
- **Puerto:** La aplicación corre en puerto 5000
- **Dominio dinámico:** Se detecta automáticamente via `REPLIT_DEV_DOMAIN`
