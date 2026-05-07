#!/bin/bash
set -e

# Wait for MySQL to be ready (proper check instead of just sleep)
if [ "$DB_HOST" = "db" ]; then
  echo "Waiting for database to be ready..."
  until php -r "try { new PDO('mysql:host=db;dbname=photobooth_db', 'root', 'root'); exit(0); } catch(Exception \$e) { exit(1); }" 2>/dev/null; do
    echo "Database is not ready yet... waiting"
    sleep 3
  done
  echo "Database is ready!"
fi

# Ensure permissions
mkdir -p /var/www/html/logs
mkdir -p /var/www/html/app/views
mkdir -p /var/www/html/public/uploads

# Pass environment variables to Apache
echo "export DB_SOCKET=\"$DB_SOCKET\"" >> /etc/apache2/envvars
echo "export DB_HOST=\"$DB_HOST\"" >> /etc/apache2/envvars
echo "export DB_USER=\"$DB_USER\"" >> /etc/apache2/envvars
echo "export DB_PASS=\"$DB_PASS\"" >> /etc/apache2/envvars
echo "export DB_NAME=\"$DB_NAME\"" >> /etc/apache2/envvars

chown -R www-data:www-data /var/www/html/logs
chown -R www-data:www-data /var/www/html/app/views
chown -R www-data:www-data /var/www/html/public/uploads

# Execute the CMD
exec "$@"
