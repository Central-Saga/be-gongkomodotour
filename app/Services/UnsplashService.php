<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnsplashService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.unsplash.com';

    public function __construct()
    {
        $this->apiKey = config('services.unsplash.key');
    }

    /**
     * Get random photo by query
     *
     * @param string $query Search query
     * @param array $options Additional options
     * @return array|null Photo data or null if error
     */
    public function getRandomPhoto($query, $options = [])
    {
        if (!$this->apiKey) {
            Log::warning('Unsplash API key not configured');
            return null;
        }

        try {
            $params = array_merge([
                'query' => $query,
                'orientation' => 'landscape',
                'w' => 1200,
                'h' => 600,
                'fit' => 'crop',
                'client_id' => $this->apiKey
            ], $options);

            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/photos/random", $params);

            if ($response->successful()) {
                $data = $response->json();

                // Log successful API call
                Log::info('Unsplash API call successful', [
                    'query' => $query,
                    'photo_id' => $data['id'] ?? 'unknown'
                ]);

                return [
                    'id' => $data['id'] ?? null,
                    'url' => $data['urls']['regular'] ?? null,
                    'thumb' => $data['urls']['thumb'] ?? null,
                    'small' => $data['urls']['small'] ?? null,
                    'full' => $data['urls']['full'] ?? null,
                    'alt' => $data['alt_description'] ?? null,
                    'description' => $data['description'] ?? null,
                    'user' => [
                        'name' => $data['user']['name'] ?? null,
                        'username' => $data['user']['username'] ?? null,
                        'portfolio_url' => $data['user']['portfolio_url'] ?? null,
                    ],
                    'links' => [
                        'html' => $data['links']['html'] ?? null,
                        'download' => $data['links']['download'] ?? null,
                    ]
                ];
            } else {
                Log::error('Unsplash API call failed', [
                    'query' => $query,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Unsplash API error', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get multiple random photos
     *
     * @param string $query Search query
     * @param int $count Number of photos (max 30)
     * @param array $options Additional options
     * @return array Array of photos
     */
    public function getRandomPhotos($query, $count = 10, $options = [])
    {
        if (!$this->apiKey) {
            return [];
        }

        // Limit count to 30 (Unsplash API limit)
        $count = min($count, 30);

        try {
            $params = array_merge([
                'query' => $query,
                'count' => $count,
                'orientation' => 'landscape',
                'w' => 1200,
                'h' => 600,
                'fit' => 'crop',
                'client_id' => $this->apiKey
            ], $options);

            $response = Http::timeout(15)
                ->get("{$this->baseUrl}/photos/random", $params);

            if ($response->successful()) {
                $photos = $response->json();

                Log::info('Unsplash API call successful', [
                    'query' => $query,
                    'count' => $count,
                    'photos_returned' => count($photos)
                ]);

                return array_map(function ($photo) {
                    return [
                        'id' => $photo['id'] ?? null,
                        'url' => $photo['urls']['regular'] ?? null,
                        'thumb' => $photo['urls']['thumb'] ?? null,
                        'small' => $photo['urls']['small'] ?? null,
                        'full' => $photo['urls']['full'] ?? null,
                        'alt' => $photo['alt_description'] ?? null,
                        'description' => $photo['description'] ?? null,
                        'user' => [
                            'name' => $photo['user']['name'] ?? null,
                            'username' => $photo['user']['username'] ?? null,
                            'portfolio_url' => $photo['user']['portfolio_url'] ?? null,
                        ],
                        'links' => [
                            'html' => $photo['links']['html'] ?? null,
                            'download' => $photo['links']['download'] ?? null,
                        ]
                    ];
                }, $photos);
            } else {
                Log::error('Unsplash API call failed', [
                    'query' => $query,
                    'count' => $count,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            Log::error('Unsplash API error', [
                'query' => $query,
                'count' => $count,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Search photos by query
     *
     * @param string $query Search query
     * @param int $page Page number
     * @param int $perPage Photos per page (max 30)
     * @param array $options Additional options
     * @return array Search results
     */
    public function searchPhotos($query, $page = 1, $perPage = 20, $options = [])
    {
        if (!$this->apiKey) {
            return [];
        }

        // Limit per page to 30 (Unsplash API limit)
        $perPage = min($perPage, 30);

        try {
            $params = array_merge([
                'query' => $query,
                'page' => $page,
                'per_page' => $perPage,
                'orientation' => 'landscape',
                'client_id' => $this->apiKey
            ], $options);

            $response = Http::timeout(15)
                ->get("{$this->baseUrl}/search/photos", $params);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Unsplash search successful', [
                    'query' => $query,
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $data['total'] ?? 0,
                    'total_pages' => $data['total_pages'] ?? 0
                ]);

                return [
                    'total' => $data['total'] ?? 0,
                    'total_pages' => $data['total_pages'] ?? 0,
                    'results' => array_map(function ($photo) {
                        return [
                            'id' => $photo['id'] ?? null,
                            'url' => $photo['urls']['regular'] ?? null,
                            'thumb' => $photo['urls']['thumb'] ?? null,
                            'small' => $photo['urls']['small'] ?? null,
                            'full' => $photo['urls']['full'] ?? null,
                            'alt' => $photo['alt_description'] ?? null,
                            'description' => $photo['description'] ?? null,
                            'user' => [
                                'name' => $photo['user']['name'] ?? null,
                                'username' => $photo['user']['username'] ?? null,
                                'portfolio_url' => $photo['user']['portfolio_url'] ?? null,
                            ],
                            'links' => [
                                'html' => $photo['links']['html'] ?? null,
                                'download' => $photo['links']['download'] ?? null,
                            ]
                        ];
                    }, $data['results'] ?? [])
                ];
            } else {
                Log::error('Unsplash search failed', [
                    'query' => $query,
                    'page' => $page,
                    'per_page' => $perPage,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }
        } catch (\Exception $e) {
            Log::error('Unsplash search error', [
                'query' => $query,
                'page' => $page,
                'per_page' => $perPage,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Check if API key is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiKey);
    }

    /**
     * Get API key status
     *
     * @return array
     */
    public function getStatus()
    {
        return [
            'configured' => $this->isConfigured(),
            'api_key' => $this->isConfigured() ? '***' . substr($this->apiKey, -4) : null,
            'base_url' => $this->baseUrl
        ];
    }
}
