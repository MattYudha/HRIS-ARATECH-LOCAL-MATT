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
mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions

# Ensure correct ownership before generating any cache files
chown -R www-data:www-data storage bootstrap/cache || true

# Run commands as www-data so that generated cache files are not owned by root
su -s /bin/sh www-data -c "php artisan config:clear"
su -s /bin/sh www-data -c "php artisan route:clear"
su -s /bin/sh www-data -c "php artisan view:clear"

# ─── Run migrations ───────────────────────────────────────────────────────────
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

# ─── PHP Upload Config ──────────────────────────────────────────────────────────
echo "⚙️  Setting PHP upload limits..."
cat > /usr/local/etc/php/conf.d/uploads.ini <<'EOF'
upload_max_filesize = 10M
post_max_size = 15M
max_execution_time = 60
max_input_time = 60
memory_limit = 256M
EOF

# ─── Create storage symlink ───────────────────────────────────────────────────
echo "🔗 Creating storage symlink..."
mkdir -p storage/app/public/claims/attachments
chown -R www-data:www-data storage/app/public || true
# Remove broken symlink if exists, then recreate
rm -f public/storage
php artisan storage:link --force

echo "✅ Setup complete! Starting PHP-FPM..."

# ─── Start PHP-FPM ────────────────────────────────────────────────────────────
exec php-fpm
