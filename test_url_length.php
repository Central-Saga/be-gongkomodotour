<?php

// Test untuk memastikan URL tidak terlalu panjang
// Jalankan dengan: php test_url_length.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Database\Factories\TravelImageFactory;
use App\Models\Carousel;
use App\Models\Asset;

echo "=== Testing URL Length ===\n\n";

// Test 1: Check URL length dari TravelImageFactory
echo "1. Testing URL Length from TravelImageFactory:\n";
$travelImageFactory = new TravelImageFactory(\Faker\Factory::create());

$testQueries = ['boat', 'sea', 'beach', 'mountain', 'sunset', 'destination,vacation'];
foreach ($testQueries as $query) {
    $imageUrl = $travelImageFactory->generateTravelImage();
    $urlLength = strlen($imageUrl);

    echo "   Query: '{$query}'\n";
    echo "   URL: {$imageUrl}\n";
    echo "   Length: {$urlLength} characters\n";

    if ($urlLength > 1000) {
        echo "   ⚠️  URL terlalu panjang (>1000 chars)\n";
    } elseif ($urlLength > 500) {
        echo "   ⚠️  URL agak panjang (>500 chars)\n";
    } else {
        echo "   ✅ URL length OK\n";
    }
    echo "\n";
}

// Test 2: Check database column constraints
echo "2. Testing Database Column Constraints:\n";
try {
    // Coba buat carousel dengan assets
    $carousel = Carousel::factory()->withAssets(1)->create();
    echo "   ✅ Carousel created with ID: {$carousel->id}\n";

    // Check assets
    $assets = $carousel->assets;
    foreach ($assets as $asset) {
        echo "   📊 Asset ID: {$asset->id}\n";
        echo "      Title: {$asset->title}\n";
        echo "      File Path Length: " . strlen($asset->file_path) . " chars\n";
        echo "      File URL Length: " . strlen($asset->file_url) . " chars\n";

        // Check if URL is too long
        if (strlen($asset->file_url) > 1000) {
            echo "      ⚠️  File URL terlalu panjang!\n";
        } else {
            echo "      ✅ File URL length OK\n";
        }

        // Check if it's an API URL
        if (strpos($asset->file_url, 'api.unsplash.com') !== false) {
            echo "      ⚠️  This is an API URL, not a direct image URL\n";
        } else {
            echo "      ✅ This is a direct image URL\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";

    if (strpos($e->getMessage(), 'Data too long for column') !== false) {
        echo "   💡 Solution: Run migration to update column types\n";
        echo "      php artisan migrate\n";
    }
}

// Test 3: Test URL accessibility
echo "3. Testing URL Accessibility:\n";
$testUrls = [
    "https://picsum.photos/1200/600?random=1",
    "https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat",
    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
];

foreach ($testUrls as $index => $url) {
    echo "   URL " . ($index + 1) . ": {$url}\n";
    echo "   Length: " . strlen($url) . " characters\n";

    // Test dengan cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($result === false) {
        echo "      ❌ Error: " . curl_error($ch) . "\n";
    } else {
        echo "      ✅ HTTP Code: {$httpCode}\n";
        if ($httpCode == 200) {
            echo "      🎉 URL accessible!\n";
        } else {
            echo "      ⚠️  URL not accessible (HTTP {$httpCode})\n";
        }
    }
    echo "\n";
}

echo "=== Test Selesai ===\n";
echo "Jika ada URL yang terlalu panjang, jalankan migration:\n";
echo "php artisan migrate\n";
echo "\n";
echo "Migration akan mengubah kolom file_url dan file_path menjadi text.\n";
