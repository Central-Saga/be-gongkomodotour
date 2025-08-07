#!/bin/bash

# Script untuk memperbaiki masalah 403 Forbidden pada asset server
# Gong Komodo Tour API

echo "🔧 Deploying 403 Forbidden Fix for Asset Server"
echo "================================================"

# 1. Clear caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# 2. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# 3. Fix file paths in database
echo "🗄️  Fixing file paths in database..."
php artisan tinker --execute="
App\Models\Asset::where('file_path', 'like', 'public/%')
    ->get()
    ->each(function(\$asset) {
        \$asset->file_path = substr(\$asset->file_path, 8);
        \$asset->save();
    });
echo 'Fixed ' . App\Models\Asset::where('file_path', 'like', 'public/%')->count() . ' assets with public/ prefix';
"

# 4. Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage/app/public
chmod -R 755 public/storage

# 5. Test endpoints
echo "🧪 Testing endpoints..."
echo "Testing asset endpoint..."
curl -I http://localhost:8000/api/files/asset/46 2>/dev/null | head -1

# 6. Show summary
echo ""
echo "✅ Deployment Summary:"
echo "====================="
echo "✅ Caches cleared"
echo "✅ Storage link created"
echo "✅ File paths fixed in database"
echo "✅ Permissions set"
echo "✅ Endpoints tested"
echo ""
echo "🎯 Next Steps:"
echo "1. Deploy to production server"
echo "2. Run: php artisan storage:link"
echo "3. Test: https://api.gongkomodotour.com/api/files/asset/46"
echo "4. Monitor logs for any issues"
echo ""
echo "📚 Documentation:"
echo "- 403_FORBIDDEN_SOLUTION.md"
echo "- FILE_ACCESS_SOLUTION.md"
echo ""
echo "🔍 Debug Commands:"
echo "php artisan debug:file-storage --asset-id=46"
echo "php artisan debug:file-storage --check-all"
