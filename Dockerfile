FROM dunglas/frankenphp:php8.3-alpine

# Set default working directory (expected by FrankenPHP Caddy defaults)
WORKDIR /app

# Install necessary system libraries & PHP extensions via the built-in tool
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    && apk add --no-cache \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Bake the Caddyfile into the image so it works even without source code
COPY docker/frankenphp/Caddyfile /etc/frankenphp/Caddyfile

# Apply custom PHP configuration (increase execution time, memory limit, etc.)
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
