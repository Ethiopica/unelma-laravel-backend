<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->unique()->nullable(); // Stripe payment intent ID
            $table->string('stripe_session_id')->nullable(); // Stripe checkout session ID
            $table->string('stripe_price_id')->nullable(); // Price ID used for the purchase
            $table->decimal('amount', 10, 2); // Amount paid in dollars
            $table->string('currency', 3)->default('usd');
            $table->string('status')->default('completed'); // completed, refunded, failed
            $table->integer('product_id')->nullable(); // Product ID if purchased a product
            $table->integer('service_id')->nullable(); // Service ID if purchased a service
            $table->integer('plan_id')->nullable(); // Plan ID if purchased a plan
            $table->integer('quantity')->default(1);
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamp('purchased_at'); // When the purchase was completed
            $table->timestamps();

            $table->index(['user_id', 'purchased_at']);
            $table->index(['product_id', 'service_id', 'plan_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
