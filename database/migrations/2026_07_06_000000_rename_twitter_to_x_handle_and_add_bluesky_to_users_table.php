<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('twitter', 'x_handle');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('bluesky')->nullable()->after('mastodon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bluesky');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('x_handle', 'twitter');
        });
    }
};
