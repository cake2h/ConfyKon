<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conference_education_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->foreignId('education_level_id')->constrained('education_levels')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['conference_id', 'education_level_id'], 'conf_edu_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conference_education_levels');
    }
}; 