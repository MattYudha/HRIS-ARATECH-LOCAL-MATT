#!/bin/sh
set -e

echo "🚀 Starting HRIS Aratech..."

# ─── Wait for MySQL to be ready ───────────────────────────────────────────────
echo "⏳ Waiting for database connection..."
until php -r "
    \$conn = @new mysqli(
        getenv('DB_HOST') ?: 'db',
        getenv('DB_USERNAME') ?: 'hris_user',
        getenv('DB_PASSWORD') ?: 'hris_secret',
        getenv('DB_DATABASE') ?: 'hrappsprod',
        intval(getenv('DB_PORT') ?: 3306)
    );
    if (\$conn->connect_error) exit(1);
    exit(0);
"; do
    echo "   Database not ready yet, retrying in 3s..."
    sleep 3
done
echo "✅ Database connected!"

# ─── Generate APP_KEY if missing ──────────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
else
    echo "🔑 APP_KEY is set."
fi

# ─── Clear & cache config ─────────────────────────────────────────────────────
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ─── Run migrations ───────────────────────────────────────────────────────────
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

echo "✅ Setup complete! Starting PHP-FPM..."

# ─── Start PHP-FPM ────────────────────────────────────────────────────────────
exec php-fpm
