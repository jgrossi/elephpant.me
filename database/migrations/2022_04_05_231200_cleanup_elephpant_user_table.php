<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CleanupElephpantUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DELETE FROM elephpant_user WHERE quantity <= 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
}
