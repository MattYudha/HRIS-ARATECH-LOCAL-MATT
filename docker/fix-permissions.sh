#!/bin/sh
# ─────────────────────────────────────────────────────────────────────────────
# fix-permissions.sh
#
# Script ini dijalankan di dalam container app setelah setiap deploy.
# Tujuan: Memberikan permission yang aman ke direktori storage & cache.
#
# BUKAN chmod -R 777 — itu adalah "dosa besar" di lingkungan enterprise.
# Referensi: OWASP Server Security Guidelines
# ─────────────────────────────────────────────────────────────────────────────
set -e

TARGET_DIRS="/var/www/html/storage /var/www/html/bootstrap/cache"

echo "  → Transferring ownership to www-data..."
chown -R www-data:www-data $TARGET_DIRS

echo "  → Setting directory permissions to 775..."
find $TARGET_DIRS -type d -exec chmod 775 {} \;

echo "  → Setting file permissions to 664..."
find $TARGET_DIRS -type f -exec chmod 664 {} \;

echo "  ✓ Permissions fixed (775/664 — www-data owned)"
