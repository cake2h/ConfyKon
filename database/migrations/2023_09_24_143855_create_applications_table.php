<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->string('file_path');
            $table->integer('status')->default(0);
            
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('type_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('konfs')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('konfs')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
