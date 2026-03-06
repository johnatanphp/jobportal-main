#!/bin/bash
echo "=== Job Portal Startup ==="

# Initialize MySQL data directory if needed
if [ ! -d "/home/runner/mysql_data/mysql" ]; then
    echo "Initializing MySQL data directory..."
    mkdir -p /home/runner/mysql_data
    mysqld --user=runner --datadir=/home/runner/mysql_data --socket=/tmp/mysql.sock --skip-grant-tables > /tmp/mysqld.log 2>&1 &
    sleep 5
    mysql --socket=/tmp/mysql.sock -u root -e "CREATE DATABASE IF NOT EXISTS jobportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;"
    pkill mysqld
    sleep 2
fi

echo "Starting MySQL server..."
mysqld --user=runner --datadir=/home/runner/mysql_data --socket=/tmp/mysql.sock --port=3306 > /tmp/mysqld.log 2>&1 &

echo "Waiting for MySQL..."
for i in $(seq 1 30); do
    if mysql --socket=/tmp/mysql.sock -u root -e "SELECT 1;" > /dev/null 2>&1; then
        echo "MySQL ready!"
        break
    fi
    sleep 1
done

# Install composer dependencies if not already present
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --ignore-platform-reqs || true
fi

echo "Starting PHP server on port 5000..."
exec php -S 0.0.0.0:5000 -t /home/runner/workspace /home/runner/workspace/router.php
