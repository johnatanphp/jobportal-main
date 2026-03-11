# 🔐 Portal de Empleo - Acceso Demo

## ✅ Credenciales de Acceso - Empresa

**Email:** `empresa@demo.com`  
**Contraseña:** `Password123!`

---

## 🌐 URLs de Acceso

- **Ruta Login Empresa:** `/employer-login`
- **Dashboard Empresa:** `/employer/dashboard` (después de login)
- **Login Alterno:** `/company_login`

---

## 📋 Detalles de la Cuenta Demo

| Campo | Valor |
|-------|-------|
| Nombre de Empresa | Demo Company |
| RUC/ID Empresa | 12345678 |
| Email de Contacto | empresa@demo.com |
| Estado | Activo (active) |
| Rol | Administrador |
| País | Perú (ID: 1) |

---

## 🛠️ Sistema Configurado

✅ **Backend PHP 8.2** - Router limpio para URLs amigables  
✅ **Base de Datos PostgreSQL** - Conexión interna (helium)  
✅ **Sesiones** - Archivo-based, duración 2 horas  
✅ **Autenticación** - Soporte para bcrypt y texto plano  
✅ **CodeIgniter 3** - Framework MVC personalizado  

---

## 🔑 Detalles Técnicos

- **Driver de Sesión:** File-based (`/tmp/ci_sessions`)
- **Cookie de Sesión:** `ci_session`
- **Duración de Sesión:** 7200 segundos (2 horas)
- **Regeneración:** Cada 300 segundos en operaciones críticas
- **Base de Datos:** PostgreSQL en `helium:5432`
- **Database:** `heliumdb`
- **Usuario DB:** `postgres`

---

## 📦 Estado de Componentes

| Componente | Estado | Notas |
|-----------|--------|-------|
| Servidor PHP | ✅ Ejecutando | Puerto 5000 |
| Base de Datos | ✅ Conectada | 125+ tablas |
| Sesiones | ✅ Configuradas | File-based storage |
| Login Empresa | ✅ Listo | Demo account activa |
| Vistas | ✅ Cargando | Bootstrap 4 + Custom CSS |

---

## 🚀 Próximos Pasos

1. Acceder con credenciales demo
2. Crear/gestionar ofertas de empleo
3. Ver candidaturas de aplicantes
4. Configurar empresa y perfil
5. Integrar más usuarios

---

**Última actualización:** 2026-03-09  
**Versión:** Firebase + V1 Integration
