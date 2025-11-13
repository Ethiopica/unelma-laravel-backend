<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, use raw SQL as renameColumn may not work
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE subscriptions CHANGE type name VARCHAR(255) NOT NULL');
        } else {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('type', 'name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL, use raw SQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE subscriptions CHANGE name type VARCHAR(255) NOT NULL');
        } else {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('name', 'type');
            });
        }
    }
};
