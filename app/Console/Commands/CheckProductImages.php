<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckProductImages extends Command
{
    protected $signature = 'products:check-images';
    protected $description = 'Check product images and their URLs';

    public function handle()
    {
        $this->info('🔍 Checking Product Images...');
        $this->newLine();

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->warn('No products found in database.');
            return 0;
        }

        $this->info("Found {$products->count()} products:");
        $this->newLine();

        foreach ($products as $product) {
            $this->line("Product ID: {$product->id} - {$product->name}");
            $this->line("  image field: " . ($product->image ?? 'NULL'));
            $this->line("  image_url field: " . ($product->image_url ?? 'NULL'));
            $this->line("  image_local_url: " . ($product->image_local_url ?? 'NULL'));
            $this->line("  full_image_url: " . ($product->full_image_url ?? 'NULL'));
            
            // Check if image exists
            if ($product->image) {
                $disk = config('filesystems.default');
                try {
                    if ($disk === 's3') {
                        $exists = \Storage::disk('s3')->exists($product->image);
                        $this->line("  File exists in Supabase: " . ($exists ? 'YES' : 'NO (file not found)'));
                    }
                } catch (\Exception $e) {
                    $this->line("  File check error: " . $e->getMessage());
                }
            }
            
            $this->newLine();
        }

        $this->info('✅ Check complete!');
        return 0;
    }
}

