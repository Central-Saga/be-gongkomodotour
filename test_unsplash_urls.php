<?php

// Test untuk memastikan URL Unsplash valid
// Jalankan dengan: php test_unsplash_urls.php

echo "=== Testing Unsplash URLs ===\n\n";

// Test URL format yang lama (yang bermasalah)
echo "1. Testing old URL format:\n";
$oldUrl = "https://source.unsplash.com/random/1200x600?sunset,water";
echo "   Old URL: {$oldUrl}\n";
echo "   Status: " . (filter_var($oldUrl, FILTER_VALIDATE_URL) ? "Valid URL" : "Invalid URL") . "\n\n";

// Test URL format yang baru (yang sudah diperbaiki)
echo "2. Testing new URL format:\n";
$newUrls = [
    "https://source.unsplash.com/random/1200x600/?sunset",
    "https://source.unsplash.com/random/1200x600/?sunset,travel",
    "https://source.unsplash.com/random/1920x1080/?water",
    "https://source.unsplash.com/random/1600x900/?beach",
    "https://source.unsplash.com/random/1200x600/?mountain&orientation=landscape",
];

foreach ($newUrls as $index => $url) {
    echo "   URL " . ($index + 1) . ": {$url}\n";
    echo "   Status: " . (filter_var($url, FILTER_VALIDATE_URL) ? "Valid URL" : "Invalid URL") . "\n";
}

echo "\n3. Testing keyword combinations:\n";
$keywords = ['boat', 'sea', 'beach', 'ocean', 'mountain', 'sunset', 'water'];
foreach ($keywords as $keyword) {
    $url = "https://source.unsplash.com/random/1200x600/?{$keyword}";
    echo "   {$keyword}: {$url}\n";
}

echo "\n4. Testing different dimensions:\n";
$dimensions = [
    '1200x600' => 'Landscape (carousel)',
    '1920x1080' => 'Full HD',
    '1600x900' => 'HD',
    '800x600' => 'Small landscape',
    '600x800' => 'Portrait'
];

foreach ($dimensions as $dim => $desc) {
    $url = "https://source.unsplash.com/random/{$dim}/?boat";
    echo "   {$dim} ({$desc}): {$url}\n";
}

echo "\n5. Testing with additional parameters:\n";
$baseUrl = "https://source.unsplash.com/random/1200x600/?boat";
$params = [
    '&orientation=landscape' => 'Landscape orientation',
    '&fit=crop' => 'Crop fit',
    '&blur=0' => 'No blur',
    '&grayscale' => 'Grayscale',
    '&quality=80' => 'Quality 80%'
];

foreach ($params as $param => $desc) {
    $url = $baseUrl . $param;
    echo "   {$desc}: {$url}\n";
}

echo "\n=== Test Selesai ===\n";
echo "URL format yang baru menggunakan:\n";
echo "- Forward slash sebelum query parameter (?)\n";
echo "- Keyword tunggal atau kombinasi dengan koma\n";
echo "- Parameter tambahan dengan ampersand (&)\n";
echo "- Dimensi yang bervariasi\n";
