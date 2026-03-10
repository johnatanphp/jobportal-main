# Portal de Empleo Overall - Sistema de Gestión de Empleo

Plataforma web de gestión integrada de ofertas de empleo, candidatos y perfiles empresariales. Migrada de MySQL/MariaDB a **PostgreSQL nativa de Replit** con soporte multi-rol (Admin, Empresa, Candidato).

---

## 🚀 Inicio Rápido

### 1. Ver la Aplicación
La aplicación está publicada y disponible en el dominio Replit asignado.

### 2. Credenciales Demo

#### Candidato / Usuario (Job Seeker)
- **URL:** `/login`
- **Email:** `usuario@demo.com`
- **Password:** `Password123!`

#### Empresa / Empleador
- **URL:** `/company_login`
- **Email:** `empresa@demo.com`
- **Password:** `Password123!`

#### Administrador
- **URL:** `/admin/login`
- **Email:** `admin@overall.pe`
- **Password:** `Password123!`

---

## 📋 Características

✅ **Autenticación Multi-Rol**
- Sistema de sesiones independiente para cada tipo de usuario
- Hash de contraseñas con bcrypt (password_hash)
- Validación de credenciales contra base de datos PostgreSQL

✅ **Base de Datos**
- PostgreSQL nativa (Replit)
- 11 tablas principales
- Soporte para múltiples usuarios demo

✅ **Seguridad**
- Sesiones persistentes con FileSession
- Variables de ambiente para configuración
- URLs dinámicas (REPLIT_DEV_DOMAIN)

✅ **Estructura Profesional**
- Framework CodeIgniter 3
- PHP 8.2
- Manejo de uploads con directorios protegidos

---

## 🔧 Stack Tecnológico

| Componente | Versión | Rol |
|-----------|---------|-----|
| PHP | 8.2 | Backend |
| PostgreSQL | 16 | Base de Datos |
| CodeIgniter | 3 | Framework |
| Bootstrap | 4 | Frontend |
| jQuery | 1.11 | Interactividad |

---

## 📁 Estructura Clave

```
ca_app/
├── controllers/
│   ├── Auth_seeker.php       → Login candidatos
│   ├── Auth_employer.php     → Login empresas
│   └── Auth_admin.php        → Login administradores
├── models/
│   ├── Job_seeker.php        → Modelo de candidatos
│   ├── Employer.php          → Modelo de empresas
│   └── Admin.php             → Modelo de admins
├── libraries/
│   ├── App/Auth/             → Lógica de autenticación
│   └── App/Session/          → Gestión de sesiones
└── config/
    ├── database.php          → Configuración PostgreSQL
    ├── constants.php         → URLs y constantes
    └── routes.php            → Rutas de la aplicación

public/
├── css/                      → Estilos
├── js/                       → JavaScript
├── images/                   → Imágenes
└── uploads/                  → Archivos de usuarios
```

---

## 🎯 Flujo de Login

```
1. Usuario accede a /login, /company_login, o /admin/login
2. Frontend valida formulario
3. POST a Auth_seeker::login(), Auth_employer::login(), etc.
4. Backend autentica contra BD PostgreSQL
5. Si exitoso, crea sesión con role específico
6. Redirige a dashboard del usuario
```

---

## 📊 Tablas de Base de Datos

| Tabla | Descripción |
|-------|-------------|
| `tbl_job_seekers` | Candidatos/usuarios |
| `tbl_employers` | Empresas/empleadores |
| `tbl_admin` | Administradores |
| `tbl_post_jobs` | Ofertas de empleo |
| `tbl_companies` | Datos de empresas |
| `tbl_countries` | Catálogo de países |
| `tbl_cities` | Catálogo de ciudades |
| `tbl_job_industries` | Sectores/industrias |
| `tbl_ad_codes` | Códigos de anuncios |
| `tbl_logs` | Auditoría del sistema |
| `tbl_app_config` | Configuración global |

---

## ⚙️ Configuración Técnica

### Variables de Ambiente (Replit)
- `PGHOST` - Host PostgreSQL
- `PGUSER` - Usuario BD
- `PGPASSWORD` - Contraseña BD
- `PGDATABASE` - Nombre BD
- `PGPORT` - Puerto BD (defecto: 5432)
- `REPLIT_DEV_DOMAIN` - Dominio en desarrollo

### Puerto
- **Desarrollo:** 5000 (PHP Development Server)
- **Producción:** 80 (via Replit autoscale)

---

## 🧪 Testing

### Verificar que el servidor está corriendo
```bash
curl http://localhost:5000/
```

### Probar login de candidato
```bash
curl -X POST http://localhost:5000/auth_seeker/login \
  -d "email=usuario@demo.com&pass=Password123!"
```

### Probar login de empresa
```bash
curl -X POST http://localhost:5000/auth_employer/login \
  -d "company_email=empresa@demo.com&company_pass=Password123!"
```

---

## 📝 Notas de Despliegue

- ✅ Despliegue en Replit: Autoscale (0,5GB RAM, escalable)
- ✅ Base de datos: PostgreSQL nativa
- ✅ Dependencias: Composer installadas
- ✅ Sesiones: File-based (persistentes)

---

## 🔐 Seguridad

- Contraseñas almacenadas con `password_hash()`
- Validación de sesiones por rol
- Variables de ambiente para datos sensibles
- Queries preparadas (CodeIgniter Escape)

---

## 📞 Soporte

Para más información técnica, ver:
- `DEPLOYMENT_NOTES.md` - Notas de despliegue
- `replit.md` - Registro técnico de implementación
