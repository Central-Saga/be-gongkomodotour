<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GooglePlacesService
{
    protected $apiKey;
    protected $placeId;

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key');
        $this->placeId = config('services.google.place_id'); // Place ID untuk Gong Komodo Tour
    }

    /**
     * Ambil review terbaru dari Google Places
     *
     * @param int $limit
     * @return array
     */
    public function getLatestReviews(int $limit = 5): array
    {
        try {
            // Cek cache terlebih dahulu (cache selama 1 jam)
            $cacheKey = "google_reviews_{$limit}";
            $cachedReviews = Cache::get($cacheKey);

            if ($cachedReviews) {
                return $cachedReviews;
            }

            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $this->placeId,
                'fields' => 'reviews',
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']['reviews'])) {
                    $reviews = collect($data['result']['reviews'])
                        ->take($limit)
                        ->map(function ($review) {
                            return [
                                'author_name' => $review['author_name'] ?? 'Anonymous',
                                'rating' => $review['rating'] ?? 5,
                                'text' => $review['text'] ?? '',
                                'time' => $review['time'] ?? now()->timestamp,
                                'profile_photo_url' => $review['profile_photo_url'] ?? null,
                                'source' => 'google_review',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        })
                        ->toArray();

                    // Cache hasil selama 1 jam
                    Cache::put($cacheKey, $reviews, now()->addHour());

                    return $reviews;
                }
            }

            Log::warning('Failed to fetch Google reviews', [
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching Google reviews: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Ambil semua testimonial (internal + Google reviews)
     *
     * @param int $googleLimit
     * @param int $internalLimit
     * @return array
     */
    public function getAllTestimonials(int $googleLimit = 5, int $internalLimit = 10): array
    {
        // Ambil testimonial dari database
        $internalTestimonials = \App\Models\Testimonial::approved()
            ->with('trip')
            ->orderBy('created_at', 'desc')
            ->limit($internalLimit)
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'author_name' => $testimonial->customer_name,
                    'rating' => $testimonial->rating,
                    'text' => $testimonial->review,
                    'time' => $testimonial->created_at->timestamp,
                    'profile_photo_url' => null,
                    'source' => 'internal',
                    'trip' => $testimonial->trip ? [
                        'id' => $testimonial->trip->id,
                        'name' => $testimonial->trip->name,
                    ] : null,
                    'created_at' => $testimonial->created_at,
                    'updated_at' => $testimonial->updated_at,
                ];
            })
            ->toArray();

        // Ambil review dari Google
        $googleReviews = $this->getLatestReviews($googleLimit);

        // Gabungkan dan urutkan berdasarkan waktu terbaru
        $allTestimonials = array_merge($internalTestimonials, $googleReviews);

        usort($allTestimonials, function ($a, $b) {
            return $b['time'] - $a['time'];
        });

        return $allTestimonials;
    }

    /**
     * Ambil testimonial yang di-highlight
     *
     * @param int $limit
     * @return array
     */
    public function getHighlightedTestimonials(int $limit = 5): array
    {
        $highlightedTestimonials = \App\Models\Testimonial::highlighted()
            ->approved()
            ->with('trip')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'author_name' => $testimonial->customer_name,
                    'rating' => $testimonial->rating,
                    'text' => $testimonial->review,
                    'time' => $testimonial->created_at->timestamp,
                    'profile_photo_url' => null,
                    'source' => 'internal',
                    'trip' => $testimonial->trip ? [
                        'id' => $testimonial->trip->id,
                        'name' => $testimonial->trip->name,
                    ] : null,
                    'created_at' => $testimonial->created_at,
                    'updated_at' => $testimonial->updated_at,
                ];
            })
            ->toArray();

        return $highlightedTestimonials;
    }
}
