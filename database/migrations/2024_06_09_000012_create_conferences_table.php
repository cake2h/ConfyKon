<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conferences', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->dateTime('deadline_applications');
            $table->dateTime('deadline_reports');
            $table->foreignId('city_id')->constrained('cities')->onDelete('restrict');
            $table->string('address')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('format_id')->constrained('formats')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conferences');
    }
}; 