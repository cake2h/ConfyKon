<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->unsignedBigInteger('moder_id');
            $table->unsignedBigInteger('konf_id');

            $table->foreign('moder_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('konf_id')->references('id')->on('konfs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};
