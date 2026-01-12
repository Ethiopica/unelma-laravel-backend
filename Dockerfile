FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsodium-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    sodium \
    opcache

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
RUN npm ci --omit=dev 2>/dev/null || npm install --omit=dev 2>/dev/null || true
RUN npm run build 2>/dev/null || true

# Create storage directories and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Remove any .env file - Render provides environment variables directly
RUN rm -f .env .env.example 2>/dev/null || true

# Expose port (Render uses PORT env variable)
EXPOSE 10000

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Starting Laravel application..."\n\
\n\
# Generate app key if not set\n\
if [ -z "$APP_KEY" ]; then\n\
    echo "Generating APP_KEY..."\n\
    php artisan key:generate --force || true\n\
fi\n\
\n\
# Create storage link\n\
php artisan storage:link 2>/dev/null || true\n\
\n\
# Clear and cache config\n\
echo "Clearing caches..."\n\
php artisan config:clear || true\n\
php artisan route:clear || true\n\
php artisan view:clear || true\n\
php artisan cache:clear || true\n\
\n\
# Run migrations\n\
echo "Running migrations..."\n\
php artisan migrate --force || echo "Migration failed or skipped"\n\
\n\
# Render uses PORT env variable (default 10000)\n\
echo "Starting server on port ${PORT:-10000}..."\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}\n\
' > /start.sh && chmod +x /start.sh

# Start command
CMD ["/start.sh"]
