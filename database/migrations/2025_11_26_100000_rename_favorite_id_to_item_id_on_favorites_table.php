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
        Schema::table('favorites', function (Blueprint $table) {
            $table->renameColumn('favorite_id', 'item_id');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->renameIndex('favorites_favorite_type_favorite_id_index', 'favorites_favorite_type_item_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->renameColumn('item_id', 'favorite_id');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->renameIndex('favorites_favorite_type_item_id_index', 'favorites_favorite_type_favorite_id_index');
        });
    }
};

