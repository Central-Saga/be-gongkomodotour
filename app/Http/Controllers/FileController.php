<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Asset;

class FileController extends Controller
{
    /**
     * Serve file berdasarkan asset ID dengan validasi keamanan
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function serveAsset(string $id)
    {
        try {
            $asset = Asset::findOrFail($id);

            // Jika asset eksternal, redirect ke URL eksternal
            if ($asset->is_external) {
                return redirect($asset->file_url);
            }

            // Jika file_path tidak ada
            if (!$asset->file_path) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            // Validasi path untuk keamanan
            $filePath = $asset->file_path;
            if (str_contains($filePath, '..') || str_contains($filePath, '//')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Path file tidak valid'
                ], 400);
            }

            // Cek apakah file ada di storage
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan di storage'
                ], 404);
            }

            // Ambil file dari storage
            $file = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);

            // Validasi mime type untuk keamanan
            $allowedMimeTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'text/plain'
            ];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tipe file tidak diizinkan'
                ], 400);
            }

            // Return file dengan header yang tepat
            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
                'Cache-Control' => 'public, max-age=31536000', // Cache selama 1 tahun
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengakses file'
            ], 500);
        }
    }

    /**
     * Serve file berdasarkan path langsung (untuk keperluan debugging)
     *
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function serveFile(string $path)
    {
        try {
            // Decode URL path
            $decodedPath = urldecode($path);

            // Validasi path untuk keamanan
            if (str_contains($decodedPath, '..') || str_contains($decodedPath, '//')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Path file tidak valid'
                ], 400);
            }

            // Cek apakah file ada di storage
            if (!Storage::disk('public')->exists($decodedPath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan di storage'
                ], 404);
            }

            // Ambil file dari storage
            $file = Storage::disk('public')->get($decodedPath);
            $mimeType = Storage::disk('public')->mimeType($decodedPath);

            // Validasi mime type untuk keamanan
            $allowedMimeTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'text/plain'
            ];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tipe file tidak diizinkan'
                ], 400);
            }

            // Return file dengan header yang tepat
            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($decodedPath) . '"',
                'Cache-Control' => 'public, max-age=31536000', // Cache selama 1 tahun
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengakses file'
            ], 500);
        }
    }
}
