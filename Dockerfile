# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies (without dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install npm dependencies and build
RUN npm ci || npm install
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copy .env.example to .env if .env doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate app key
RUN php artisan key:generate --force || true

# Cache configuration
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Expose port 80
EXPOSE 80

# Start Apache with migrations
CMD php artisan migrate --force && apache2-foreground

