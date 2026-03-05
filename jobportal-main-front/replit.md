# Portal de Empleo Overall

A PHP CodeIgniter job portal application (employment portal) for Overall, a Latin American recruitment company.

## Architecture

- **Framework**: CodeIgniter PHP (legacy version)
- **Language**: PHP 8.2.x
- **Database**: MariaDB 10.11 (MySQL compatible)
- **Server**: PHP built-in development server

## Project Structure

- `index.php` - CodeIgniter front controller
- `router.php` - PHP built-in server router
- `ca_app/` - Application directory (controllers, models, views, config)
- `ca_sys/` - CodeIgniter system files
- `public/` - Static assets (CSS, JS, images)
- `start.sh` - Startup script (starts MariaDB + PHP server)

## Configuration Files

- `ca_app/config/constants.php` - App constants including SITE_URL
- `ca_app/config/database.php` - Database connection settings
- `ca_app/config/config.php` - CodeIgniter configuration

## Database Setup

The database (MariaDB) runs locally. Data directory: `/home/runner/mysql_data`
- Socket: `/tmp/mysql.sock`
- Port: 3306
- Database name: `jobportal`
- Username: `root` (no password)

**Note**: The full database schema is not included in the repository. A minimal schema has been created to allow the app to run. To fully use the app, import the complete schema provided by the development team.

## Running

The workflow "Start application" runs `start.sh` which:
1. Initializes MySQL data directory if needed
2. Starts the MariaDB server
3. Runs the PHP built-in server on port 5000

## Known Limitations

- No full database schema included in repo - app shows empty data
- Session configured for file-based storage (not database) for compatibility
- PHP 8.2 deprecation warnings suppressed (legacy CodeIgniter code)
- CORS issue with fonts in Replit browser preview (minor, cosmetic)

## Dependencies

Managed via Composer (`composer.json`). Run `composer install --ignore-platform-req=php` to install.
