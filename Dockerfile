FROM php:8.2-fpm

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    pdo \
    pdo_sqlite \
    && docker-php-ext-enable bcmath pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY leboncoin /app

# Copy environment configuration
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Install PHP dependencies (with platform requirement fallback)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-interaction \
    --optimize-autoloader \
    --ignore-platform-req=ext-bcmath || true

# Install Node dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build

# Set permissions
RUN chmod -R 755 storage bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

# Create database if it doesn't exist
RUN touch database/database.sqlite

# Clear all caches before starting
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan cache:clear || true
RUN php artisan view:clear || true

# Expose port
EXPOSE 8080

# Start Laravel
CMD ["/app/start.sh"]
