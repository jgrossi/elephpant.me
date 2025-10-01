<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameTwitterColumnToXHandleInUsersTable extends Migration
{
    public function up()
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('x_handle')->nullable();
            });
            DB::statement('UPDATE users SET x_handle = twitter');
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('twitter');
            });
        } else {
            DB::statement('ALTER TABLE users CHANGE twitter x_handle VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('twitter')->nullable();
            });
            DB::statement('UPDATE users SET twitter = x_handle');
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('x_handle');
            });
        } else {
            DB::statement('ALTER TABLE users CHANGE x_handle twitter VARCHAR(255) NULL');
        }
    }
}
