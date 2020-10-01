<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeColumnToElephpantsTable extends Migration
{
    public function up()
    {
        Schema::table('elephpants', function (Blueprint $table) {
            $table->string('size')->nullable()->unique()->after('image');
        });
    }

    public function down()
    {
        Schema::table('elephpants', function (Blueprint $table) {
            $table->dropColumn(['size']);
        });
    }
}
