<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id');
            $table->string('report_theme', 255);
            $table->string('file_path', 255)->nullable();
            $table->unsignedBigInteger('report_status_id')->nullable();
            $table->timestamps();

            $table->foreign('report_status_id')->references('id')->on('report_statuses')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}; 