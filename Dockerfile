FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including bcmath
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install Composer dependencies (without dev for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application files
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Install npm dependencies and build assets
RUN npm ci --omit=dev || npm install --omit=dev || true
RUN npm run build || true

# Create storage directories and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Create .env from .env.example if not exists
RUN if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || touch .env; fi

# Expose port
EXPOSE 8080

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Generate app key if not set\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force 2>/dev/null || true\n\
fi\n\
\n\
# Clear and cache config\n\
php artisan config:clear 2>/dev/null || true\n\
php artisan route:clear 2>/dev/null || true\n\
php artisan view:clear 2>/dev/null || true\n\
\n\
# Run migrations (skip if fails - might not have DB yet)\n\
php artisan migrate --force 2>/dev/null || echo "Migration skipped"\n\
\n\
# Start the server\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}\n\
' > /start.sh && chmod +x /start.sh

# Start command
CMD ["/start.sh"]
