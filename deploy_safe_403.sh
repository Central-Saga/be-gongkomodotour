#!/bin/bash

# Script deployment yang aman untuk memperbaiki masalah 403 Forbidden
# Tanpa mengganggu CORS yang sudah ada

echo "🔧 Safe Deployment - Fix 403 Forbidden Issue"
echo "============================================="

# 1. Backup current state
echo "💾 Creating backup..."
cp config/cors.php config/cors.php.backup

# 2. Clear caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# 3. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# 4. Fix file paths in database (only if needed)
echo "🗄️  Checking file paths in database..."
php artisan tinker --execute="
\$count = App\Models\Asset::where('file_path', 'like', 'public/%')->count();
if (\$count > 0) {
    App\Models\Asset::where('file_path', 'like', 'public/%')
        ->get()
        ->each(function(\$asset) {
            \$asset->file_path = substr(\$asset->file_path, 8);
            \$asset->save();
        });
    echo 'Fixed ' . \$count . ' assets with public/ prefix';
} else {
    echo 'No assets need fixing';
}
"

# 5. Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage/app/public
chmod -R 755 public/storage

# 6. Test basic functionality
echo "🧪 Testing basic functionality..."
echo "Testing API health..."
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/api/debug-server 2>/dev/null || echo "000"

# 7. Show summary
echo ""
echo "✅ Safe Deployment Summary:"
echo "=========================="
echo "✅ CORS config backed up"
echo "✅ Caches cleared"
echo "✅ Storage link created"
echo "✅ File paths checked/fixed"
echo "✅ Permissions set"
echo ""
echo "🎯 Next Steps:"
echo "1. Test API: https://api.gongkomodotour.com/"
echo "2. Test file endpoint: https://api.gongkomodotour.com/api/files/asset/46"
echo "3. Monitor logs for any issues"
echo ""
echo "📚 Documentation:"
echo "- 403_FORBIDDEN_SOLUTION.md"
echo ""
echo "🔍 Debug Commands:"
echo "php artisan debug:file-storage --asset-id=46"
echo "tail -f storage/logs/laravel.log"
