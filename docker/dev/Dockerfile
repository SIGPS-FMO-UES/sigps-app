FROM php:8.4-fpm

# Argumentos para UID y GID del usuario del host
ARG UID=1000
ARG GID=1000

# Instalar dependencias del sistema y herramientas de desarrollo
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    libpq-dev \
    libicu-dev \
    nginx \
    supervisor \
    ca-certificates \
    gnupg \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP (PostgreSQL, MySQL, SQLite, etc.)
RUN docker-php-ext-install pdo_pgsql pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip opcache intl

# Instalar extensión de Redis (opcional pero recomendada para colas/cache)
RUN pecl install redis && docker-php-ext-enable redis || true

# Instalar Node.js 20 LTS y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar usuario www-data con el UID/GID del host para evitar problemas de permisos
RUN groupmod -o -g ${GID} www-data || true \
    && usermod -o -u ${UID} -g www-data www-data || true \
    && mkdir -p /home/www-data \
    && chown -R www-data:www-data /home/www-data

# Directorio de trabajo
WORKDIR /var/www

# Copiar configuraciones personalizadas para desarrollo
COPY docker/dev/php.ini /usr/local/etc/php/conf.d/custom-dev.ini
COPY docker/dev/nginx.conf /etc/nginx/sites-available/default
COPY docker/dev/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/dev/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80 5173

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
