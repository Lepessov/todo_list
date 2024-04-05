#!/bin/bash

# Laravel setup
echo "Setting up Laravel project..."
composer install
cp .env.development .env
php artisan key:generate

# Database setup
echo "Setting up database..."
php artisan migrate:fresh --seed

# Generate Swagger documentation
echo "Generating Swagger documentation..."
php artisan l5-swagger:generate

# Start the development server
echo "Starting Laravel development server..."
php artisan serve
