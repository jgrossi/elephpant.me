<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePrototypeElephpantsFromCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            DELETE eu
            FROM elephpant_user eu
            LEFT JOIN users u ON eu.user_id = u.id
            WHERE elephpant_id IN (52,53,55,62)
            AND u.twitter != 'OGProgrammer';
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
