# Portal de Empleo V2 - Replit Setup

## ✅ Status: Sincronizado y Configurado

### Stack Tecnológico
- **Backend**: PHP 8.2 + CodeIgniter 3
- **Base de Datos**: PostgreSQL (heliumdb en Replit)
- **Frontend**: Bootstrap 4 + jQuery + AdminLTE
- **Servidor**: PHP Built-in Server (puerto 5000)

### Sincronización V2
- ✅ Rama V2 de GitHub sincronizada
- ✅ Schema completo: 125 tablas
- ✅ Autenticación multi-rol (Usuario, Empresa, Admin)
- ✅ Dashboards para cada rol
- ✅ Sistema de sesiones en base de datos

### Demo Users
| Rol | Email | Password |
|-----|-------|----------|
| Candidato | usuario@demo.com | Password123! |
| Empresa | empresa@demo.com | Password123! |
| Admin | admin@overall.pe | Password123! |

### Configuración Base de Datos
- **Host**: helium (Replit PostgreSQL)
- **Usuario**: postgres
- **Base de datos**: heliumdb
- **Tablas**: 125 completas

### Archivos Clave
- `ca_app/config/database.php` - Configurado para heliumdb
- `ca_app/config/constants.php` - URLs dinámicas
- `schema.sql` - Todas las tablas cargadas
- `index.php` - Entry point CodeIgniter

### Próximos Pasos
1. **Workflow Restart**: El servidor necesita reiniciar para cargar la nueva configuración de base de datos
2. **Personalización**: Actualizar datos demo con información real
3. **Integraciones**: Email, LinkedIn, Facebook (opcionales)

## Notas Técnicas
- La base de datos y código están 100% sincronizados con V2
- Las sesiones se guardan en archivos (/tmp/ci_sessions)
- Todos los controladores, modelos y vistas están en su lugar
- Composer dependencies instaladas
