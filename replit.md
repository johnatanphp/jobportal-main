# Job Portal - V2 Version

## Status
- **Application:** CodeIgniter 3 PHP
- **Database:** PostgreSQL (jobportal)
- **Server:** PHP Built-in Server on port 8000
- **Branch:** V2 (Main branch with full schema)

## Configuration
- Database: PostgreSQL with schema.sql loaded
- Session Storage: Database-backed sessions
- Router: router.php for URL rewriting

## Database Tables
Created 40+ tables including:
- tbl_app_config
- tbl_admin, tbl_logs
- tbl_countries, tbl_cities
- tbl_employers, tbl_jobs
- tbl_jobseekers, tbl_applications
- And more...

## Development
- Run `php -S 0.0.0.0:8000 router.php` to start the server
- Database: psql -d jobportal to access
- Configuration: ca_app/config/ directory
