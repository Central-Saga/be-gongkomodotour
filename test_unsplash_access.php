<?php

// Test akses ke URL Unsplash
// Jalankan dengan: php test_unsplash_access.php

echo "=== Testing Unsplash URL Access ===\n\n";

// Test beberapa URL Unsplash yang berbeda
$testUrls = [
    // Format 1: Source Unsplash (gratis)
    "https://source.unsplash.com/random/1200x600/?boat",
    "https://source.unsplash.com/random/1200x600/?sea",
    "https://source.unsplash.com/random/1200x600/?beach",

    // Format 2: Picsum (alternatif gratis)
    "https://picsum.photos/1200/600?random=1",
    "https://picsum.photos/1200/600?random=2",
    "https://picsum.photos/1200/600?random=3",

    // Format 3: Unsplash dengan format berbeda
    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop&q=80",

    // Format 4: Placeholder services
    "https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat+Image",
    "https://via.placeholder.com/1200x600/00cc66/ffffff?text=Sea+Image",
    "https://via.placeholder.com/1200x600/cc6600/ffffff?text=Beach+Image",
];

echo "Testing URL access:\n";
foreach ($testUrls as $index => $url) {
    echo "\n" . ($index + 1) . ". {$url}\n";

    // Test dengan cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if ($result === false) {
        echo "   ❌ Error: " . curl_error($ch) . "\n";
    } else {
        echo "   ✅ HTTP Code: {$httpCode}\n";
        echo "   📄 Content-Type: {$contentType}\n";

        if ($httpCode == 200) {
            echo "   🎉 URL accessible!\n";
        } else {
            echo "   ⚠️  URL not accessible (HTTP {$httpCode})\n";
        }
    }

    curl_close($ch);
}

echo "\n=== Alternative Solutions ===\n";
echo "Jika Unsplash tidak berfungsi, gunakan alternatif berikut:\n\n";

echo "1. Picsum Photos (Gratis, Reliable):\n";
echo "   https://picsum.photos/1200/600?random=1\n";
echo "   https://picsum.photos/1200/600?random=2\n";
echo "   https://picsum.photos/1200/600?random=3\n\n";

echo "2. Placeholder.com (Gratis, Customizable):\n";
echo "   https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat+Image\n";
echo "   https://via.placeholder.com/1200x600/00cc66/ffffff?text=Sea+Image\n\n";

echo "3. Unsplash dengan ID spesifik:\n";
echo "   https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop\n";
echo "   https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop\n\n";

echo "4. Local Images (Simpan gambar lokal):\n";
echo "   /storage/images/boat-1.jpg\n";
echo "   /storage/images/sea-1.jpg\n";
echo "   /storage/images/beach-1.jpg\n\n";

echo "=== Test Selesai ===\n";
