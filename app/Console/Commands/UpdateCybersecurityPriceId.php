<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCybersecurityPriceId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cybersecurity:update-price-id {price_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Stripe price ID for Cybersecurity service plans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $priceId = $this->argument('price_id');
        
        if (empty($priceId)) {
            $this->error('Price ID is required');
            return 1;
        }

        // Find Cyber Security service (case-insensitive, handles both "Cybersecurity" and "Cyber Security")
        $service = Service::where(function($query) {
            $query->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', ['cybersecurity'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%cyber%security%']);
        })->first();

        if (!$service) {
            $this->error('Cybersecurity service not found');
            return 1;
        }

        $this->info("Found Cybersecurity service (ID: {$service->id})");

        // Check if plans table exists, create if it doesn't
        if (!Schema::hasTable('plans')) {
            $this->warn('Plans table does not exist. Creating it...');
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->decimal('price', 10, 2)->default(0);
                $table->string('period')->nullable();
                $table->string('stripe_price_id')->nullable();
                $table->json('features')->nullable();
                $table->timestamps();
            });
            $this->info('Plans table created successfully');
        }

        // Update all plans for this service
        $updated = DB::table('plans')
            ->where('service_id', $service->id)
            ->update(['stripe_price_id' => $priceId]);

        if ($updated > 0) {
            $this->info("Successfully updated {$updated} plan(s) with price ID: {$priceId}");
        } else {
            $this->warn("No plans found for Cybersecurity service. Creating a default plan...");
            
            // Create a default plan if none exists
            DB::table('plans')->insert([
                'service_id' => $service->id,
                'name' => 'Cybersecurity Plan',
                'price' => 0,
                'period' => null,
                'stripe_price_id' => $priceId,
                'features' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->info("Created default plan with price ID: {$priceId}");
        }

        return 0;
    }
}
