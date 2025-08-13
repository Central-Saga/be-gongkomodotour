<?php

// Test sederhana untuk factory carousel
// Jalankan dengan: php test_carousel_factory.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Carousel;
use App\Models\Asset;

echo "=== Testing Carousel Factory ===\n\n";

// Test 1: Carousel biasa
echo "1. Membuat carousel biasa...\n";
$carousel1 = Carousel::factory()->create();
echo "   - ID: {$carousel1->id}\n";
echo "   - Title: {$carousel1->title}\n";
echo "   - Assets count: " . $carousel1->assets()->count() . "\n\n";

// Test 2: Carousel dengan 1 asset
echo "2. Membuat carousel dengan 1 asset...\n";
$carousel2 = Carousel::factory()->withAssets()->create();
echo "   - ID: {$carousel2->id}\n";
echo "   - Title: {$carousel2->title}\n";
echo "   - Assets count: " . $carousel2->assets()->count() . "\n";

// Tampilkan detail asset
$assets = $carousel2->assets;
foreach ($assets as $asset) {
    echo "   - Asset ID: {$asset->id}, Title: {$asset->title}\n";
    echo "     File URL: {$asset->file_url}\n";
}
echo "\n";

// Test 3: Carousel dengan 3 assets
echo "3. Membuat carousel dengan 3 assets...\n";
$carousel3 = Carousel::factory()->withAssets(3)->create();
echo "   - ID: {$carousel3->id}\n";
echo "   - Title: {$carousel3->title}\n";
echo "   - Assets count: " . $carousel3->assets()->count() . "\n\n";

// Test 4: Buat asset manual untuk carousel
echo "4. Membuat asset manual untuk carousel...\n";
$carousel4 = Carousel::factory()->create();
$asset = Asset::create([
    'assetable_id' => $carousel4->id,
    'assetable_type' => get_class($carousel4),
    'title' => 'Test Asset Manual',
    'description' => 'Asset yang dibuat manual',
    'file_path' => 'https://source.unsplash.com/random/1200x600?boat,sea',
    'file_url' => 'https://source.unsplash.com/random/1200x600?boat,sea',
    'is_external' => true,
]);
echo "   - Carousel ID: {$carousel4->id}\n";
echo "   - Asset ID: {$asset->id}\n";
echo "   - Assets count: " . $carousel4->assets()->count() . "\n\n";

// Test 5: Test primary image attribute
echo "5. Test primary image attribute...\n";
$carousel5 = Carousel::factory()->withAssets()->create();
$primaryImage = $carousel5->primary_image;
if ($primaryImage) {
    echo "   - Carousel ID: {$carousel5->id}\n";
    echo "   - Primary Image ID: {$primaryImage->id}\n";
    echo "   - Primary Image URL: {$primaryImage->file_url}\n";
} else {
    echo "   - Primary image tidak ditemukan\n";
}
echo "\n";

echo "=== Test Selesai ===\n";
echo "Total Carousel: " . Carousel::count() . "\n";
echo "Total Asset: " . Asset::count() . "\n";
