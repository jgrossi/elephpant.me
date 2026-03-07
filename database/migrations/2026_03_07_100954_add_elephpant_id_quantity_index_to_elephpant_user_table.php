<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('elephpant_user', function (Blueprint $table) {
            $table->index(['elephpant_id', 'quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elephpant_user', function (Blueprint $table) {
            $table->dropIndex(['elephpant_id', 'quantity']);
        });
    }
};
