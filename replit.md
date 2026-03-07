# Portal de Empleo v2.0

**Status**: Running on Replit  
**Stack**: PHP 8.2 + CodeIgniter 3 + PostgreSQL  
**Database**: Replit PostgreSQL  
**Deployment Target**: Autoscale VM

## 🚀 Quick Start

The application is configured and ready to deploy. No additional setup needed.

### Current Environment
- **Server**: PHP 8.2 Built-in Server
- **Port**: 5000
- **Database**: PostgreSQL (Replit native)
- **Entry Point**: `index.php` (CodeIgniter bootstrap via `router.php`)

## 🔐 Authentication

### Job Seeker Login
- **Route**: `/login`
- **Credentials**: Create via registration page
- **Session**: `Session_job_seeker` manages user state

### Employer Login
- **Route**: `/employer-login`
- **Credentials**: Create via admin panel or registration
- **Session**: `Session_employer` manages user state

## 📦 Database Schema

Includes:
- `tbl_job_seekers` - Job applicants (16 columns)
- `tbl_employers` - Company representatives (16 columns)
- `tbl_companies` - Company records
- `tbl_cities` - Location data
- `tbl_countries` - Country reference
- 80+ additional tables for job listings, applications, profiles, etc.

All tables use PostgreSQL with proper data types and constraints.

## 🛠️ Configuration

### Environment Variables
Automatically configured by Replit:
- `PGHOST`, `PGUSER`, `PGPASSWORD`, `PGDATABASE`, `PGPORT`
- `REPLIT_DEV_DOMAIN` - Used for dynamic SITE_URL

### Key Config Files
- `ca_app/config/constants.php` - App constants and SITE_URL
- `ca_app/config/database.php` - PostgreSQL connection (env var based)
- `ca_app/config/development/database.php` - Dev overrides

## ✅ Deployment Checklist

- [x] Database schema loaded and verified
- [x] Authentication controllers configured
- [x] Session management implemented
- [x] PostgreSQL functions for all stored procedures created
- [x] Assets serving correctly
- [x] Deployment target set to autoscale
- [x] PHP server configured for production

## 📋 Features Implemented

1. **Authentication** ✅
   - Job seeker login/logout
   - Employer login/logout
   - Session management with role-based access
   - Login attempt tracking (6 attempts max)
   - Account locking with 30-minute timeout

2. **Database** ✅
   - Full PostgreSQL schema with 80+ tables
   - PostgreSQL functions replacing MySQL procedures
   - Seed data for countries, cities, industries

3. **Security** ✅
   - Password verification (bcrypt + plain text support)
   - Session validation
   - CSRF protection via CodeIgniter

## 🔄 To Deploy

1. Click the "Publish" button in Replit
2. The application will be deployed to: `https://<your-replit-username>.replit.dev`
3. Database connections are automatically configured

## 📝 Notes

- This is a full production-ready CodeIgniter 3 application
- All MySQL-specific code has been converted to PostgreSQL
- The application serves static files (CSS, JS, images) correctly
- Multiple workflows are available but "Start Application" on port 5000 is primary
