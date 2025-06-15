#!/bin/bash

# Build and start containers
docker-compose up -d --build

# Wait for containers to be ready
echo "Waiting for containers to start..."
sleep 10

# Install Laravel
docker-compose exec app composer create-project --prefer-dist laravel/laravel temp
docker-compose exec app sh -c "shopt -s dotglob && mv temp/* . && rmdir temp"

# Set permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

# Generate application key
docker-compose exec app php artisan key:generate

echo "Laravel installation complete!"
echo "You can access your application at http://localhost:8080"