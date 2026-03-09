# Portal de Empleo V2 - Replit Setup

## Status: ✅ Sincronizado desde GitHub V2 Branch

### Stack Tecnológico
- **Backend**: PHP 8.2 + CodeIgniter 3
- **Base de Datos**: PostgreSQL (jobportal)
- **Frontend**: Bootstrap 4 + jQuery + AdminLTE
- **Servidor**: PHP Built-in Server (puerto 5000)

### Cambios de V2
- ✅ Schema completo con 125+ tablas
- ✅ Autenticación multi-rol (Usuario, Empresa, Admin)
- ✅ Dashboards para cada rol
- ✅ Sistema de sesiones en base de datos
- ✅ Demo users configurados

### Demo Users
| Rol | Email | Password |
|-----|-------|----------|
| Candidato | usuario@demo.com | Password123! |
| Empresa | empresa@demo.com | Password123! |
| Admin | admin@overall.pe | Password123! |

### Configuración Replit
- Base de datos: jobportal (PostgreSQL)
- Configuración: ca_app/config/
- Router: router.php para URLs reescritas
- Dependencias: Instaladas vía Composer

### URL de Acceso
- Base: Tu dominio Replit en puerto 5000
- Login Usuario: /login
- Login Empresa: /company_login
- Login Admin: /admin/login

### Próximos Pasos
- Personalizar con datos reales
- Configurar integraciones (Email, LinkedIn, Facebook)
- Implementar búsqueda avanzada
- Agregar notificaciones
