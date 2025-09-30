<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameTwitterColumnToXHandleInUsersTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE users CHANGE twitter x_handle VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE users CHANGE x_handle twitter VARCHAR(255) NULL');
    }
}
