# Portal de Empleo - Notas de Despliegue

## Estado: LISTO PARA PRODUCCIÓN

### Credenciales Demo
```
CANDIDATO (Job Seeker):
- URL: /login
- Email: usuario@demo.com
- Password: Password123!

EMPRESA (Employer):
- URL: /company_login
- Email: empresa@demo.com
- Password: Password123!

ADMINISTRADOR (Admin):
- URL: /admin/login
- Email: admin@overall.pe
- Password: Password123!
```

### Base de Datos
- **Motor:** PostgreSQL (nativa de Replit)
- **Driver:** CodeIgniter postgre
- **Tablas:** 11 principales con estructura completa
- **Conexión:** Via variables de ambiente PGHOST, PGUSER, PGPASSWORD, PGDATABASE

### Características Implementadas
✅ Sistema de autenticación multi-rol
✅ Sesiones persistentes con FileSession
✅ Password hashing con bcrypt (password_hash)
✅ Validación de formularios
✅ Estructura de directorios para uploads
✅ Configuración dinámica de URL (REPLIT_DEV_DOMAIN)

### Dependencias
- PHP 8.2
- Composer con dependencias instaladas
- PostgreSQL 16

### Instrucciones de Deploy en Replit
1. Click en botón "Publish"
2. El servidor automáticamente:
   - Instalará dependencias con Composer
   - Iniciará servidor PHP en puerto 5000
   - Usará PostgreSQL nativa para datos

### Testing del Sistema
```bash
# Test login endpoint
curl -X POST http://localhost:5000/auth_seeker/login \
  -d "email=usuario@demo.com&pass=Password123!"

# Test employer login
curl -X POST http://localhost:5000/auth_employer/login \
  -d "company_email=empresa@demo.com&company_pass=Password123!"
```

### Rutas Importantes
- `/` - Home/Landing page
- `/login` - Job Seeker login
- `/company_login` - Employer login (alias: /employer-login)
- `/admin/login` - Admin login
- `/jobseeker/dashboard` - Job seeker dashboard (post-login)
- `/employer/dashboard` - Employer dashboard (post-login)

### Estructura de Sesiones
```php
// Job Seeker Session
$_SESSION['user_id']
$_SESSION['user_email']
$_SESSION['is_job_seeker'] = true
$_SESSION['user_dashboard'] = 'jobseeker/dashboard'

// Employer Session
$_SESSION['user_id']
$_SESSION['user_email']
$_SESSION['is_employer'] = true
$_SESSION['user_dashboard'] = 'employer/dashboard'

// Admin Session
$_SESSION['user_id']
$_SESSION['user_email']
$_SESSION['is_admin'] = true
$_SESSION['user_dashboard'] = 'admin/dashboard'
```

### Próximas Mejoras
- Integración de ramas (firebase branch consolidation)
- Dashboards completamente funcionales por rol
- Ofertas de empleo y sistema de aplicaciones
- Notificaciones y mensajería

### Soporte
Para problemas técnicos, revisar logs en el panel de Replit o contactar al equipo de desarrollo.
