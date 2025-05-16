<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('answer', 255);
            $table->unsignedBigInteger('question_theme_id');
            $table->unsignedBigInteger('conference_id');
            $table->timestamps();

            $table->foreign('question_theme_id')->references('id')->on('question_themes')->onDelete('restrict');
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faq');
    }
}; 