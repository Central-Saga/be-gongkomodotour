<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory untuk generate URL gambar wisata yang reliable
 */
class TravelImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_url' => $this->generateTravelImage(),
        ];
    }

    /**
     * Generate URL gambar wisata yang reliable
     *
     * @return string URL gambar
     */
    public function generateTravelImage()
    {
        // Array keyword untuk tema wisata yang relevan
        $travelKeywords = [
            'boat',
            'sea',
            'beach',
            'ocean',
            'mountain',
            'landscape',
            'tropical',
            'island',
            'adventure',
            'travel',
            'sunset',
            'water',
            'nature',
            'forest',
            'cultural',
            'heritage',
            'vacation',
            'holiday',
            'destination',
            'paradise'
        ];

        // Pilih keyword acak
        $randomKeyword = $this->faker->randomElement($travelKeywords);

        // Beberapa alternatif URL gambar yang reliable
        $imageUrls = [
            // 1. Unsplash API (Paling reliable dengan API key)
            $this->generateUnsplashApiUrl($randomKeyword),
            $this->generateUnsplashApiUrl($randomKeyword . ',travel'),
            $this->generateUnsplashApiUrl($randomKeyword . ',vacation'),

            // 2. Picsum Photos (Fallback, reliable, gratis)
            "https://picsum.photos/1200/600?random=" . rand(1, 1000),
            "https://picsum.photos/1200/600?random=" . rand(1, 1000),

            // 3. Placeholder.com (Customizable, gratis)
            "https://via.placeholder.com/1200x600/0066cc/ffffff?text=" . urlencode($randomKeyword),
            "https://via.placeholder.com/1200x600/00cc66/ffffff?text=" . urlencode($randomKeyword),
            "https://via.placeholder.com/1200x600/cc6600/ffffff?text=" . urlencode($randomKeyword),

            // 4. Unsplash dengan ID spesifik (Reliable, gratis)
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
            "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",

            // 5. Source Unsplash (Fallback, mungkin bermasalah)
            "https://source.unsplash.com/random/1200x600/?{$randomKeyword}",
        ];

        // Pilih URL acak dari array
        return $this->faker->randomElement($imageUrls);
    }

    /**
     * Generate Unsplash API URL dengan API key
     *
     * @param string $query Query pencarian
     * @return string URL gambar Unsplash
     */
    private function generateUnsplashApiUrl($query)
    {
        $apiKey = config('services.unsplash.key');

        if (!$apiKey) {
            // Jika tidak ada API key, gunakan fallback
            return "https://picsum.photos/1200/600?random=" . rand(1, 1000);
        }

        // Untuk factory, kita return URL gambar langsung, bukan API URL
        // Gunakan method getUnsplashImageUrl untuk mendapatkan URL gambar yang sebenarnya
        $imageUrl = $this->getUnsplashImageUrl($query);

        if ($imageUrl) {
            return $imageUrl;
        }

        // Jika API gagal, gunakan fallback
        return "https://picsum.photos/1200/600?random=" . rand(1, 1000);
    }

    /**
     * Generate gambar langsung dari Unsplash API (untuk production)
     *
     * @param string $query Query pencarian
     * @return string|null URL gambar atau null jika error
     */
    public function getUnsplashImageUrl($query)
    {
        try {
            $unsplashService = app(\App\Services\UnsplashService::class);

            if (!$unsplashService->isConfigured()) {
                return null;
            }

            $photo = $unsplashService->getRandomPhoto($query);

            if ($photo && isset($photo['url'])) {
                return $photo['url'];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate gambar dengan tema tertentu
     *
     * @param string $theme Tema gambar (boat, sea, mountain, dll)
     * @return string URL gambar
     */
    public function generateImageByTheme($theme = null)
    {
        if ($theme) {
            $theme = strtolower($theme);

            // URL spesifik untuk tema tertentu
            $themeUrls = [
                'boat' => [
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
                    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
                ],
                'sea' => [
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
                    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
                ],
                'mountain' => [
                    "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
                    "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
                ],
            ];

            if (isset($themeUrls[$theme])) {
                return $this->faker->randomElement($themeUrls[$theme]);
            }
        }

        // Jika tema tidak ditemukan, gunakan random
        return $this->generateTravelImage();
    }

    /**
     * Force Unsplash only - Hanya gunakan Unsplash API
     * ⚠️  Warning: Bisa error jika API rate limit atau down
     *
     * @param string $query Query pencarian
     * @return string URL gambar Unsplash
     */
    public function generateUnsplashOnly($query = null)
    {
        if (!$query) {
            $travelKeywords = [
                'boat',
                'sea',
                'beach',
                'ocean',
                'mountain',
                'landscape',
                'tropical',
                'island',
                'adventure',
                'travel',
                'sunset',
                'water',
                'nature',
                'forest',
                'cultural',
                'heritage',
                'vacation',
                'holiday',
                'destination',
                'paradise'
            ];
            $query = $this->faker->randomElement($travelKeywords);
        }

        // Coba Unsplash API dulu
        $unsplashUrl = $this->getUnsplashImageUrl($query);

        if ($unsplashUrl) {
            return $unsplashUrl;
        }

        // Jika API gagal, gunakan Unsplash ID spesifik sebagai fallback
        $unsplashIds = [
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
            "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
            "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop",
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop",
        ];

        return $this->faker->randomElement($unsplashIds);
    }

    /**
     * Generate gambar dengan preferensi sumber tertentu
     *
     * @param string $preference 'unsplash', 'picsum', 'placeholder', 'mixed'
     * @param string $query Query pencarian
     * @return string URL gambar
     */
    public function generateImageWithPreference($preference = 'mixed', $query = null)
    {
        switch ($preference) {
            case 'unsplash':
                return $this->generateUnsplashOnly($query);

            case 'picsum':
                return "https://picsum.photos/1200/600?random=" . rand(1, 1000);

            case 'placeholder':
                if (!$query) {
                    $travelKeywords = ['boat', 'sea', 'beach', 'mountain', 'sunset'];
                    $query = $this->faker->randomElement($travelKeywords);
                }
                return "https://via.placeholder.com/1200x600/0066cc/ffffff?text=" . urlencode($query);

            case 'mixed':
            default:
                return $this->generateTravelImage();
        }
    }
}
