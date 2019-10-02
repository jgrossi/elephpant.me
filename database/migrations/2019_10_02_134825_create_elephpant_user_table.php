<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElephpantUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('elephpant_user', function (Blueprint $table) {
            $table->bigInteger('elephpant_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('quantity')->default(0)->unsigned();
            $table->timestamps();

            $table->foreign('elephpant_id')->references('id')->on('elephpants');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elephpant_user');
    }
}
