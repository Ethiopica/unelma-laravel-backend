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
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('name');
            }

            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('price');
            }

            if (! Schema::hasColumn('products', 'highlights')) {
                $table->text('highlights')->nullable()->after('sku');
            }

            if (! Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 2, 1)->nullable()->after('description');
            }

            if (! Schema::hasColumn('products', 'image_url')) {
                $table->text('image_url')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('products', 'sku')) {
                $table->dropColumn('sku');
            }

            if (Schema::hasColumn('products', 'highlights')) {
                $table->dropColumn('highlights');
            }

            if (Schema::hasColumn('products', 'rating')) {
                $table->dropColumn('rating');
            }

            if (Schema::hasColumn('products', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
};

