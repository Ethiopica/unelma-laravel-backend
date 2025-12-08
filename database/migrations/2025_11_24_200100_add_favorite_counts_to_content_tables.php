<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('blogs', 'favorite_count')) {
                $table->unsignedBigInteger('favorite_count')->default(0)->after('views');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'favorite_count')) {
                $table->unsignedBigInteger('favorite_count')->default(0)->after('order');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'favorite_count')) {
                $table->unsignedBigInteger('favorite_count')->default(0)->after('order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'favorite_count')) {
                $table->dropColumn('favorite_count');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'favorite_count')) {
                $table->dropColumn('favorite_count');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'favorite_count')) {
                $table->dropColumn('favorite_count');
            }
        });
    }
};




























