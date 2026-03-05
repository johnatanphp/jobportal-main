# Portal de Empleo Overall

A PHP CodeIgniter job portal application (employment portal) for Overall, a Latin American recruitment company.

## Architecture

- **Framework**: CodeIgniter 3 (legacy PHP framework)
- **Language**: PHP 8.2.x
- **Database**: MariaDB 10.11 (MySQL compatible), running locally via TCP on 127.0.0.1:3306
- **Server**: PHP built-in development server on port 5000

## Project Structure

- `index.php` - CodeIgniter front controller
- `router.php` - PHP built-in server router (serves static files from `public/`, routes everything else to CodeIgniter)
- `ca_app/` - Application directory (controllers, models, views, config)
- `ca_sys/` - CodeIgniter system files
- `public/` - Static assets (CSS, JS, images)
- `vendor/` - Composer dependencies
- `start.sh` - Startup script (starts PHP server immediately, then initializes + starts MariaDB)

## Configuration Files

- `ca_app/config/constants.php` - App constants including SITE_URL, SMTP, OAuth keys
- `ca_app/config/database.php` - Database connection settings (hostname: 127.0.0.1, port: 3306, db: jobportal, user: root, no password)
- `ca_app/config/config.php` - CodeIgniter core configuration (base_url, sessions, encryption key)

## Database Setup

The database (MariaDB) runs locally. Data directory: `/home/runner/mysql_data`
- TCP: 127.0.0.1:3306
- Socket: `/tmp/mysql.sock`
- Database name: `jobportal`
- Username: `root` (no password)

**Note**: The full database schema is not included in the repository. The `jobportal` database is created automatically on startup, but all tables must be imported from the production schema provided by the development team.

## Running

The workflow "Start application" runs `start.sh` which:
1. Starts the PHP built-in server on port 5000 immediately (so the port opens fast)
2. Initializes MySQL data directory if this is the first run (using `mysql_install_db`)
3. Starts the MariaDB server and waits for it to be ready
4. Ensures the `jobportal` database exists

## Dependencies

Managed via Composer (`composer.json`). Run `composer install` to install:
- `mpdf/mpdf` - PDF generation (CVs, contracts)
- `phpoffice/phpspreadsheet` - Excel report generation
- `aws/aws-sdk-php` - AWS S3 and Lambda integration
- `league/flysystem-aws-s3-v3` - S3 file storage
- `geoip2/geoip2` - IP-based geolocation
- `dragonmantank/cron-expression` - Scheduled task handling

## Known Limitations

- No full database schema in repo — app connects to DB but shows "table doesn't exist" errors until schema is imported
- PHP 8.2 deprecation warnings may appear (legacy CodeIgniter 3 codebase)
- SITE_URL in `constants.php` is set to the Replit dev domain — update if domain changes
