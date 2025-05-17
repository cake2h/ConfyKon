<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->unsignedBigInteger('conference_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};
