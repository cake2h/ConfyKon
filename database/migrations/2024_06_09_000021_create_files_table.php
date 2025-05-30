<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->unsignedBigInteger('conference_id');
            $table->timestamps();

            $table->foreign('conference_id')
                  ->references('id')
                  ->on('conferences')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
}; 