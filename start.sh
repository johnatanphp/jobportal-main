#!/bin/bash

MYSQL_DATA=/home/runner/mysql_data
MYSQL_SOCK=/tmp/mysql.sock
MYSQL_LOG=/tmp/mysqld.log
MARIADB_SHARE=/nix/store/a4jsa8kjdn3wlccj2wkvhxqza38rpxzf-mariadb-server-10.11.13/share/mysql

echo "=== Job Portal Startup ==="

# Initialize MySQL data directory if needed
if [ ! -f "$MYSQL_DATA/mysql/global_priv.MAI" ]; then
    echo "Initializing MySQL data directory..."
    rm -rf "$MYSQL_DATA"
    mkdir -p "$MYSQL_DATA"
    
    # Start with --skip-grant-tables to create basic structure
    mysqld --user=runner --datadir=$MYSQL_DATA \
        --socket=$MYSQL_SOCK --port=3306 \
        --skip-grant-tables \
        --log-error=$MYSQL_LOG &
    MPID=$!
    
    # Wait for socket
    echo "Waiting for MySQL socket..."
    for i in $(seq 1 30); do
        if [ -S $MYSQL_SOCK ]; then
            echo "MySQL socket ready after ${i}s"
            break
        fi
        sleep 1
    done
    
    sleep 2
    
    # Initialize system tables using SOURCE
    mysql --socket=$MYSQL_SOCK -u root --connect-timeout=10 -e "
CREATE DATABASE IF NOT EXISTS mysql;
USE mysql;
SOURCE $MARIADB_SHARE/mysql_system_tables.sql;
SOURCE $MARIADB_SHARE/mysql_system_tables_data.sql;
FLUSH PRIVILEGES;
CREATE DATABASE IF NOT EXISTS jobportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
" 2>&1 || echo "Warning: init SQL had errors (may be OK)"
    
    echo "Stopping init MySQL..."
    kill $MPID 2>/dev/null || true
    wait $MPID 2>/dev/null || true
    sleep 3
    rm -f $MYSQL_SOCK
fi

# Start MySQL properly
echo "Starting MySQL server..."
mysqld --user=runner --datadir=$MYSQL_DATA \
    --socket=$MYSQL_SOCK --port=3306 \
    --log-error=$MYSQL_LOG &
MPID=$!

# Wait for MySQL
echo "Waiting for MySQL..."
for i in $(seq 1 30); do
    if mysql --socket=$MYSQL_SOCK -u root -e "SELECT 1;" > /dev/null 2>&1; then
        echo "MySQL ready!"
        break
    fi
    sleep 1
    echo -n "."
done

# Ensure jobportal database exists
mysql --socket=$MYSQL_SOCK -u root -e "CREATE DATABASE IF NOT EXISTS jobportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;" 2>/dev/null || true

# Start PHP built-in server on port 5000
echo "Starting PHP server on port 5000..."
exec php -S 0.0.0.0:5000 -t /home/runner/workspace /home/runner/workspace/router.php
