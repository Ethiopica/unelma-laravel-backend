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
            $table->string('image_url')->nullable()->after('image');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('image');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('featured_image_url')->nullable()->after('featured_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('featured_image_url');
        });
    }
};










