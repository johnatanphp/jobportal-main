# Job Portal - Portal de Empleo Overall

## Status
- **Application:** CodeIgniter 3 PHP
- **Database:** PostgreSQL (jobportal database on Replit's managed PostgreSQL)
- **Server:** PHP Built-in Server on port 5000
- **Environment:** Replit (migrated from Replit Agent)

## Configuration
- Database: PostgreSQL with schema.sql loaded into `jobportal` database
- Session Storage: Database-backed sessions in `tbl_sessions` table
- Router: router.php for URL rewriting
- Config files: ca_app/config/database.php and ca_app/config/constants.php (generated from .example files)

## Database Connection
- Host: $PGHOST (environment variable)
- User: $PGUSER (environment variable)
- Database: jobportal
- Port: $PGPORT (environment variable)

## Database Tables
40+ tables including:
- tbl_app_config, tbl_sessions, tbl_logs
- tbl_admin
- tbl_countries, tbl_cities
- tbl_employers, tbl_companies
- tbl_job_industries, tbl_post_jobs
- tbl_job_seekers, tbl_applications
- And more...

## Key Files
- `index.php` - CodeIgniter entry point
- `router.php` - URL routing for PHP built-in server
- `ca_app/` - Application code (controllers, models, views, config)
- `ca_sys/` - CodeIgniter core
- `public/` - Static assets
- `vendor/` - Composer dependencies
- `schema.sql` - PostgreSQL schema

## Development
- Server runs via workflow "Start Application": `php -d memory_limit=512M -S 0.0.0.0:5000 router.php`
- Database: `psql -d jobportal` to access
- Configuration: ca_app/config/ directory
- Constants use REPLIT_DEV_DOMAIN env variable for base URL

## Secrets / Environment Variables
The following environment variables can optionally be set for full functionality:
- SMTP_HOST, SMTP_USER, SMTP_PASS, SMTP_PORT - for email sending
- API_LINKEDIN_CLIENT_ID, API_LINKEDIN_CLIENT_SECRET - for LinkedIn login
- API_FB_APP_ID, API_FB_APP_SECRET - for Facebook login
- INDEED_KEY - for Indeed job feed integration
- ADMIN_EMAIL - admin notification email
