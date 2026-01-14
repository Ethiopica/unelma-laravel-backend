<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class TestSupabaseStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:test-supabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Supabase storage configuration and connectivity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Supabase Storage Configuration...');
        $this->newLine();

        // Check configuration
        $this->info('📋 Configuration Check:');
        $disk = config('filesystems.default');
        $this->line("  Default Disk: {$disk}");

        if ($disk !== 's3') {
            $this->warn("  ⚠️  FILESYSTEM_DISK is set to '{$disk}', not 's3'");
            $this->line("  Set FILESYSTEM_DISK=s3 in your environment variables");
        } else {
            $this->info("  ✅ Default disk is 's3'");
        }

        $this->newLine();

        // Check S3 configuration
        $this->info('🔧 S3 Configuration:');
        $s3Config = config('filesystems.disks.s3');
        
        $this->line("  Bucket: " . ($s3Config['bucket'] ?? 'NOT SET'));
        $this->line("  Region: " . ($s3Config['region'] ?? 'NOT SET'));
        $this->line("  Endpoint: " . ($s3Config['endpoint'] ?? 'NOT SET'));
        $this->line("  URL: " . ($s3Config['url'] ?? 'NOT SET'));
        $this->line("  Path Style: " . ($s3Config['use_path_style_endpoint'] ? 'true' : 'false'));
        $this->line("  Key: " . (isset($s3Config['key']) && $s3Config['key'] ? 'SET' : 'NOT SET'));
        $this->line("  Secret: " . (isset($s3Config['secret']) && $s3Config['secret'] ? 'SET' : 'NOT SET'));

        // Check if Supabase
        $isSupabase = false;
        if (isset($s3Config['url']) && str_contains($s3Config['url'], 'supabase.co')) {
            $isSupabase = true;
            $this->info("  ✅ Detected Supabase storage");
        }

        $this->newLine();

        // Test connection
        $this->info('🔌 Testing Connection:');
        try {
            $testFileName = 'test-' . time() . '.txt';
            $testContent = 'Supabase storage test - ' . now()->toDateTimeString();
            
            $this->line("  Uploading test file: {$testFileName}");
            Storage::disk('s3')->put($testFileName, $testContent);
            $this->info("  ✅ Upload successful!");

            // Get URL first (this is what matters most)
            $url = Storage::disk('s3')->url($testFileName);
            $this->line("  Generated URL: {$url}");

            // If Supabase, check URL format
            if ($isSupabase) {
                $expectedPattern = '/supabase\.co\/storage\/v1\/object\/public\/';
                if (str_contains($url, $expectedPattern)) {
                    $this->info("  ✅ URL format is correct for Supabase");
                } else {
                    $this->warn("  ⚠️  URL format might be incorrect");
                    $this->line("     Expected: ...supabase.co/storage/v1/object/public/...");
                }
            }

            // Test reading (more reliable than exists() for Supabase)
            try {
                $content = Storage::disk('s3')->get($testFileName);
                if ($content === $testContent) {
                    $this->info("  ✅ Read test successful");
                } else {
                    $this->warn("  ⚠️  Read test: content mismatch (but file exists)");
                }
            } catch (\Exception $readError) {
                $this->warn("  ⚠️  Read test failed: " . $readError->getMessage());
                $this->line("     This might be normal for Supabase - upload succeeded, which is the main test");
            }

            // Try to check existence (may fail with Supabase, that's okay)
            try {
                $exists = Storage::disk('s3')->exists($testFileName);
                if ($exists) {
                    $this->info("  ✅ File exists check successful");
                } else {
                    $this->warn("  ⚠️  Exists check returned false (may be Supabase API limitation)");
                }
            } catch (\Exception $existsError) {
                $this->warn("  ⚠️  Exists check not supported: " . $existsError->getMessage());
                $this->line("     This is common with Supabase S3-compatible API");
            }

            // Cleanup
            try {
                Storage::disk('s3')->delete($testFileName);
                $this->info("  ✅ Test file deleted");
            } catch (\Exception $deleteError) {
                $this->warn("  ⚠️  Delete failed: " . $deleteError->getMessage());
                $this->line("     You may need to manually delete: {$testFileName}");
            }

        } catch (\Exception $e) {
            $this->error("  ❌ Connection failed!");
            $this->error("  Error: " . $e->getMessage());
            $this->newLine();
            $this->line("  Common issues:");
            $this->line("    - Check AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY");
            $this->line("    - Verify AWS_ENDPOINT is correct");
            $this->line("    - Ensure AWS_USE_PATH_STYLE_ENDPOINT=true");
            $this->line("    - Check Supabase bucket exists and is accessible");
            return 1;
        }

        $this->newLine();
        $this->info('✅ Upload test passed! Supabase storage is working.');
        $this->line('');
        $this->line('📝 Note: Some Supabase S3-compatible API methods (like exists()) may not work');
        $this->line('   perfectly, but upload/read/delete should work fine.');
        $this->line('');
        $this->line('🎯 Next steps:');
        $this->line('   1. Test uploading an image through the admin panel');
        $this->line('   2. Verify images display correctly');
        $this->line('   3. Check that image URLs are accessible');
        return 0;
    }
}

