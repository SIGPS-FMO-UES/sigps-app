#!/bin/bash
set -e

echo "🚀 Iniciando entorno de desarrollo para SIGPS..."

# Crear directorios necesarios si no existen
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache

# Configurar permisos de almacenamiento y bootstrap/cache
echo "🔧 Configurando permisos de almacenamiento..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Crear archivo .env si no existe
if [ ! -f "/var/www/.env" ]; then
    if [ -f "/var/www/.env.example" ]; then
        echo "📄 Creando .env a partir de .env.example..."
        cp /var/www/.env.example /var/www/.env
    fi
fi

# Generar APP_KEY si falta en .env
if [ -f "/var/www/.env" ]; then
    if ! grep -q "^APP_KEY=base64:" /var/www/.env; then
        echo "🔑 Generando APP_KEY..."
        php artisan key:generate --no-interaction
    fi
fi

# Instalar dependencias de Composer si no existen
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "📦 Instalando dependencias de PHP con Composer..."
    composer install --prefer-dist --no-interaction
fi

# Preparar base de datos SQLite si está seleccionada
DB_CONN=$(grep -E "^DB_CONNECTION=" /var/www/.env 2>/dev/null | cut -d '=' -f2 | tr -d ' ' || echo "sqlite")
if [ "$DB_CONN" = "sqlite" ] || [ -z "$DB_CONN" ]; then
    if [ ! -f "/var/www/database/database.sqlite" ]; then
        echo "🗄️ Creando base de datos SQLite (database/database.sqlite)..."
        touch /var/www/database/database.sqlite
        chown www-data:www-data /var/www/database/database.sqlite
        chmod 664 /var/www/database/database.sqlite
    else
        chown www-data:www-data /var/www/database/database.sqlite
        chmod 664 /var/www/database/database.sqlite
    fi
fi

# Limpiar cachés para asegurar que cualquier cambio en rutas o vistas se refleje en vivo
echo "🧹 Limpiando cachés de Laravel para desarrollo..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Enlace simbólico de storage
echo "🔗 Verificando enlace simbólico de almacenamiento..."
php artisan storage:link --force || true

# Migraciones automáticas (si la base de datos está lista)
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force || echo "⚠️ Advertencia: No se pudieron ejecutar las migraciones automáticamente."

# Si no existen los assets de Vite compilados ni el servidor Vite dev corriendo, compilar assets iniciales
if [ ! -f "/var/www/public/build/manifest.json" ] && [ ! -f "/var/www/public/hot" ]; then
    echo "⚡ No se encontró manifest de Vite en public/build/manifest.json"
    if [ ! -d "/var/www/node_modules" ]; then
        echo "📦 Instalando dependencias de Node (npm install)..."
        npm install --prefer-offline --no-audit || true
    fi
    echo "🎨 Compilando assets iniciales con Vite (npm run build)..."
    npm run build || echo "⚠️ Advertencia: npm run build falló, continuando inicio..."
fi

echo "✅ Entorno de desarrollo listo!"

# Si se pasa un comando personalizado, ejecutarlo (ej. docker compose exec app bash o artisan)
if [ "$#" -gt 0 ]; then
    exec "$@"
else
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
