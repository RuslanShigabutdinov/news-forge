#!/bin/bash
set -e

# Add /var/www/html to Git safe directories
git config --global --add safe.directory /var/www/html

# Install Composer dependencies
composer install --no-interaction --no-plugins --no-scripts --optimize-autoloader

# Set permissions for vendor
chown -R www-data:www-data /var/www/html/vendor
chmod -R 755 /var/www/html/vendor

# Create required directories if they don't exist
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate application key if it doesn't exist
if [ -z "$(grep '^APP_KEY=' .env | grep -v '=$')" ]; then
    php artisan key:generate
fi

# Run migrations if the database is ready
if [ "$DB_HOST" != "" ]; then
    # Wait for the database to be ready
    until nc -z -v -w30 $DB_HOST 5433; do
        echo "Waiting for database connection..."
        # Wait for 5 seconds before check again
        sleep 5
    done
    php artisan migrate --force
fi

# Execute the provided command (which is typically php-fpm)
exec "$@"