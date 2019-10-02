<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElephpantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('elephpants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('popular_name');
            $table->text('description')->nullable();
            $table->integer('year')->unsigned();
            $table->string('manufacturer');
            $table->string('sponsor');
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elephpants');
    }
}
