<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Services\FileUrlService;
use Illuminate\Support\Facades\Storage;

class DebugFileStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:file-storage {--asset-id=} {--file-path=} {--check-all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug file storage dan akses gambar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Debug File Storage - Gong Komodo Tour');
        $this->newLine();

        // Cek storage link
        $this->checkStorageLink();

        // Cek asset berdasarkan ID
        if ($assetId = $this->option('asset-id')) {
            $this->checkAssetById($assetId);
        }

        // Cek file berdasarkan path
        if ($filePath = $this->option('file-path')) {
            $this->checkFileByPath($filePath);
        }

        // Cek semua asset
        if ($this->option('check-all')) {
            $this->checkAllAssets();
        }

        // Jika tidak ada parameter, tampilkan bantuan
        if (!$this->option('asset-id') && !$this->option('file-path') && !$this->option('check-all')) {
            $this->showHelp();
        }

        $this->newLine();
        $this->info('✅ Debug selesai!');
    }

    /**
     * Cek storage link
     */
    private function checkStorageLink()
    {
        $this->info('📁 Checking Storage Link...');

        $publicStoragePath = public_path('storage');
        $storagePath = storage_path('app/public');

        if (is_link($publicStoragePath)) {
            $this->info('✅ Storage link exists');
            $this->line("   Public: {$publicStoragePath}");
            $this->line("   Target: {$storagePath}");
        } else {
            $this->error('❌ Storage link tidak ditemukan');
            $this->line('   Jalankan: php artisan storage:link');
        }

        $this->newLine();
    }

    /**
     * Cek asset berdasarkan ID
     */
    private function checkAssetById($assetId)
    {
        $this->info("🔍 Checking Asset ID: {$assetId}");

        $asset = Asset::find($assetId);

        if (!$asset) {
            $this->error("❌ Asset dengan ID {$assetId} tidak ditemukan");
            return;
        }

        $this->line("   Title: {$asset->title}");
        $this->line("   File Path: {$asset->file_path}");
        $this->line("   Original URL: {$asset->file_url}");
        $this->line("   Is External: " . ($asset->is_external ? 'Yes' : 'No'));

        if (!$asset->is_external && $asset->file_path) {
            $fileInfo = FileUrlService::getFileInfo($asset->file_path);

            if ($fileInfo['exists']) {
                $this->info("✅ File exists in storage");
                $this->line("   Size: " . number_format($fileInfo['size']) . " bytes");
                $this->line("   MIME Type: {$fileInfo['mime_type']}");
                $this->line("   Generated URL: {$fileInfo['url']}");
            } else {
                $this->error("❌ File tidak ditemukan di storage");
                $this->line("   Error: {$fileInfo['error']}");
            }
        }

        $this->newLine();
    }

    /**
     * Cek file berdasarkan path
     */
    private function checkFileByPath($filePath)
    {
        $this->info("🔍 Checking File Path: {$filePath}");

        $fileInfo = FileUrlService::getFileInfo($filePath);

        if ($fileInfo['exists']) {
            $this->info("✅ File exists in storage");
            $this->line("   Size: " . number_format($fileInfo['size']) . " bytes");
            $this->line("   MIME Type: {$fileInfo['mime_type']}");
            $this->line("   Generated URL: {$fileInfo['url']}");
        } else {
            $this->error("❌ File tidak ditemukan di storage");
            $this->line("   Error: {$fileInfo['error']}");
        }

        $this->newLine();
    }

    /**
     * Cek semua asset
     */
    private function checkAllAssets()
    {
        $this->info("🔍 Checking All Assets...");

        $assets = Asset::all();
        $totalAssets = $assets->count();
        $validAssets = 0;
        $invalidAssets = 0;

        $progressBar = $this->output->createProgressBar($totalAssets);
        $progressBar->start();

        foreach ($assets as $asset) {
            if (!$asset->is_external && $asset->file_path) {
                $fileInfo = FileUrlService::getFileInfo($asset->file_path);
                if ($fileInfo['exists']) {
                    $validAssets++;
                } else {
                    $invalidAssets++;
                }
            } else {
                $validAssets++; // External assets dianggap valid
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("📊 Asset Summary:");
        $this->line("   Total Assets: {$totalAssets}");
        $this->line("   Valid Assets: {$validAssets}");
        $this->line("   Invalid Assets: {$invalidAssets}");

        if ($invalidAssets > 0) {
            $this->warn("⚠️  {$invalidAssets} assets memiliki masalah file storage");
        }

        $this->newLine();
    }

    /**
     * Tampilkan bantuan
     */
    private function showHelp()
    {
        $this->info("📖 Usage Examples:");
        $this->line("   php artisan debug:file-storage --asset-id=1");
        $this->line("   php artisan debug:file-storage --file-path=trip/1753966850_cover-login.jpg");
        $this->line("   php artisan debug:file-storage --check-all");
        $this->newLine();

        $this->info("🔧 Common Solutions:");
        $this->line("   1. Jalankan: php artisan storage:link");
        $this->line("   2. Pastikan folder storage/app/public ada dan memiliki permission yang benar");
        $this->line("   3. Cek apakah file benar-benar ada di storage");
        $this->line("   4. Pastikan web server dapat mengakses folder storage");
        $this->newLine();
    }
}
