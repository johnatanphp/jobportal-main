# Portal de Empleo (Perú) - Job Portal

PHP CodeIgniter 3 job portal application migrated to Replit environment.

## Stack Tecnológico

- **Backend**: PHP 8.2 + CodeIgniter 3 (custom folders: `ca_app/`, `ca_sys/`)
- **Database**: PostgreSQL (Replit native) via `postgre` driver
- **Frontend**: HTML5, CSS3, AdminLTE
- **Package Manager**: Composer 2.x

## Replit Configuration

- **Server**: `php -d memory_limit=512M -S 0.0.0.0:5000 router.php`
- **Port**: 5000
- **Entry point**: `router.php` → `index.php` (CodeIgniter bootstrap)
- **Dependencies**: Install with `composer install` if `vendor/` folder is missing

## Configuration Files

1. **`ca_app/config/constants.php`** (not tracked in git):
   - Defines `SITE_URL` dynamically using `REPLIT_DEV_DOMAIN`
   - Contains SMTP settings, API keys (LinkedIn, Facebook)
   - Read from environment variables

2. **`ca_app/config/database.php`** (main config):
   - Driver: `postgre` (PostgreSQL)
   - Uses env vars: `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`, `PGPORT`

3. **`ca_app/config/development/database.php`** (dev override):
   - Same PostgreSQL settings using env vars

## Replit Migration Changes

### PHP Module
- PHP 8.2 module installed via Replit package manager
- Extensions: pdo_pgsql, pgsql, curl, mbstring, gd, zip, mysqli

### Database
- Uses Replit's native PostgreSQL (not MySQL/MariaDB)
- Schema defined in `schema.sql`
- PostgreSQL functions created to replace MySQL stored procedures

### MySQL to PostgreSQL Migration
- All `CALL stored_procedure()` → `SELECT * FROM pg_function()`
- All `$Q->next_result()` calls removed (MySQL multi-result not needed in PG)
- MySQL backtick table names converted to plain names
- Double-quoted string literals in SQL converted to single-quoted
- PostgreSQL functions created for all stored procedures (see schema.sql)
- `GROUP_CONCAT` → `STRING_AGG` migration (partially done, not all paths used)

### Column Naming
- PostgreSQL is case-sensitive with identifiers
- CamelCase columns quoted: `"company_ID"`, `"employer_ID"`, `"industry_ID"`, `"seeker_ID"`, `"user_ID"`
- Primary keys quoted: `"ID"` for all tables

## Environment Variables Required

- `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`, `PGPORT` - Auto-configured by Replit
- `REPLIT_DEV_DOMAIN` - Auto-configured by Replit
- Optional: `SMTP_HOST`, `SMTP_USER`, `SMTP_PASS`, `ADMIN_EMAIL`

## Database Schema

Run `psql ... < schema.sql` to initialize the database. The schema includes:
- 80+ tables for the full job portal
- PostgreSQL functions replacing MySQL stored procedures
- Initial seed data for countries, cities, industries
