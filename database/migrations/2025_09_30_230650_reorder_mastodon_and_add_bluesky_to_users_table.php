<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReorderMastodonAndAddBlueskyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mastodon');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('mastodon')->nullable()->after('twitter');
            $table->string('bluesky')->nullable()->after('mastodon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bluesky', 'mastodon']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('mastodon')->nullable()->after('is_public');
        });
    }
}
