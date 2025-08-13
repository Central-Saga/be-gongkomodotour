<?php

// Test untuk Unsplash API
// Jalankan dengan: php test_unsplash_api.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\UnsplashService;
use Database\Factories\TravelImageFactory;

echo "=== Testing Unsplash API ===\n\n";

// Test 1: Check API configuration
echo "1. Checking API Configuration:\n";
$unsplashService = new UnsplashService();
$status = $unsplashService->getStatus();

echo "   Configured: " . ($status['configured'] ? '✅ Yes' : '❌ No') . "\n";
echo "   API Key: " . ($status['api_key'] ?? 'Not set') . "\n";
echo "   Base URL: {$status['base_url']}\n\n";

if (!$status['configured']) {
    echo "⚠️  Unsplash API key not configured!\n";
    echo "   Please set UNSPLASH_ACCESS_KEY in your .env file\n\n";

    echo "2. Testing Fallback Services:\n";
    $travelImageFactory = new TravelImageFactory(\Faker\Factory::create());

    for ($i = 1; $i <= 3; $i++) {
        $imageUrl = $travelImageFactory->generateTravelImage();
        echo "   Image {$i}: {$imageUrl}\n";
    }

    echo "\n=== Test Selesai ===\n";
    echo "Gunakan fallback services (Picsum, Placeholder) sampai API key dikonfigurasi.\n";
    exit;
}

// Test 2: Get random photo
echo "2. Testing Random Photo API:\n";
$queries = ['boat', 'sea', 'beach', 'mountain', 'sunset'];

foreach ($queries as $query) {
    echo "   Query: '{$query}'\n";

    $photo = $unsplashService->getRandomPhoto($query);

    if ($photo) {
        echo "      ✅ Photo ID: {$photo['id']}\n";
        echo "      📸 URL: {$photo['url']}\n";
        echo "      👤 Photographer: {$photo['user']['name']} (@{$photo['user']['username']})\n";
        echo "      🔗 Portfolio: {$photo['user']['portfolio_url']}\n";
    } else {
        echo "      ❌ Failed to get photo\n";
    }
    echo "\n";
}

// Test 3: Get multiple random photos
echo "3. Testing Multiple Random Photos API:\n";
$multiplePhotos = $unsplashService->getRandomPhotos('travel', 5);

if (!empty($multiplePhotos)) {
    echo "   ✅ Got " . count($multiplePhotos) . " photos\n";

    foreach ($multiplePhotos as $index => $photo) {
        echo "      Photo " . ($index + 1) . ": ID {$photo['id']} - {$photo['url']}\n";
    }
} else {
    echo "   ❌ Failed to get multiple photos\n";
}
echo "\n";

// Test 4: Search photos
echo "4. Testing Search Photos API:\n";
$searchResults = $unsplashService->searchPhotos('boat', 1, 5);

if (!empty($searchResults)) {
    echo "   ✅ Search successful\n";
    echo "      Total: {$searchResults['total']} photos\n";
    echo "      Total Pages: {$searchResults['total_pages']}\n";
    echo "      Results: " . count($searchResults['results']) . " photos\n";

    foreach ($searchResults['results'] as $index => $photo) {
        echo "      Result " . ($index + 1) . ": ID {$photo['id']} - {$photo['url']}\n";
    }
} else {
    echo "   ❌ Search failed\n";
}
echo "\n";

// Test 5: Test TravelImageFactory with API
echo "5. Testing TravelImageFactory with API:\n";
$travelImageFactory = new TravelImageFactory(\Faker\Factory::create());

$themes = ['boat', 'sea', 'beach', 'mountain', 'sunset'];
foreach ($themes as $theme) {
    $imageUrl = $travelImageFactory->generateImageByTheme($theme);
    echo "   {$theme}: {$imageUrl}\n";
}
echo "\n";

// Test 6: Test direct API call
echo "6. Testing Direct API Call:\n";
$directUrl = $travelImageFactory->getUnsplashImageUrl('boat');
if ($directUrl) {
    echo "   ✅ Direct API call successful: {$directUrl}\n";
} else {
    echo "   ❌ Direct API call failed\n";
}
echo "\n";

// Test 7: Test Carousel Factory with API
echo "7. Testing Carousel Factory with API:\n";
try {
    $carousel = \App\Models\Carousel::factory()->withAssets(2)->create();
    echo "   ✅ Carousel created with ID: {$carousel->id}\n";
    echo "   📊 Assets count: " . $carousel->assets()->count() . "\n";

    // Tampilkan assets
    foreach ($carousel->assets as $asset) {
        echo "      - Asset ID: {$asset->id}, Title: {$asset->title}\n";
        echo "        URL: {$asset->file_url}\n";

        // Test if URL is accessible
        if (strpos($asset->file_url, 'api.unsplash.com') !== false) {
            echo "        ⚠️  This is an API URL, not a direct image URL\n";
        } else {
            echo "        ✅ This is a direct image URL\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error creating carousel: " . $e->getMessage() . "\n";
}

echo "\n=== Test Selesai ===\n";
echo "Jika semua test berhasil, Unsplash API sudah berfungsi dengan baik!\n";
echo "Untuk production, sebaiknya download gambar dari API dan simpan lokal.\n";
