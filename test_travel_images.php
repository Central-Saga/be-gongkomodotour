<?php

// Test untuk gambar wisata yang baru
// Jalankan dengan: php test_travel_images.php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Database\Factories\TravelImageFactory;

echo "=== Testing Travel Image Factory ===\n\n";

// Buat instance factory
$faker = Faker\Factory::create();
$travelImageFactory = new TravelImageFactory($faker);

echo "1. Testing generateTravelImage():\n";
for ($i = 1; $i <= 5; $i++) {
    $imageUrl = $travelImageFactory->generateTravelImage();
    echo "   Image {$i}: {$imageUrl}\n";
}
echo "\n";

echo "2. Testing generateImageByTheme():\n";
$themes = ['boat', 'sea', 'beach', 'mountain', 'sunset'];
foreach ($themes as $theme) {
    $imageUrl = $travelImageFactory->generateImageByTheme($theme);
    echo "   {$theme}: {$imageUrl}\n";
}
echo "\n";

echo "3. Testing URL accessibility:\n";
$testUrls = [
    // Picsum Photos
    "https://picsum.photos/1200/600?random=1",
    "https://picsum.photos/1200/600?random=100",
    "https://picsum.photos/1200/600?random=500",

    // Placeholder.com
    "https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat",
    "https://via.placeholder.com/1200x600/00cc66/ffffff?text=Sea",
    "https://via.placeholder.com/1200x600/ffcc00/000000?text=Beach",

    // Unsplash dengan ID spesifik
    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
];

foreach ($testUrls as $index => $url) {
    echo "   URL " . ($index + 1) . ": {$url}\n";

    // Test dengan cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($result === false) {
        echo "      ❌ Error: " . curl_error($ch) . "\n";
    } else {
        echo "      ✅ HTTP Code: {$httpCode}\n";
        if ($httpCode == 200) {
            echo "      🎉 Accessible!\n";
        } else {
            echo "      ⚠️  Not accessible\n";
        }
    }

    curl_close($ch);
}

echo "\n4. Testing Carousel Factory with new images:\n";
try {
    $carousel = \App\Models\Carousel::factory()->withAssets(2)->create();
    echo "   ✅ Carousel created with ID: {$carousel->id}\n";
    echo "   📊 Assets count: " . $carousel->assets()->count() . "\n";

    // Tampilkan assets
    foreach ($carousel->assets as $asset) {
        echo "      - Asset ID: {$asset->id}, Title: {$asset->title}\n";
        echo "        URL: {$asset->file_url}\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error creating carousel: " . $e->getMessage() . "\n";
}

echo "\n=== Test Selesai ===\n";
echo "Jika semua URL accessible, gambar seharusnya bisa ditampilkan!\n";
echo "Gunakan browser untuk test URL yang dihasilkan.\n";
