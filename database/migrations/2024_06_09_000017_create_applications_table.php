<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('presentation_type_id');
            $table->unsignedBigInteger('report_id')->nullable();
            $table->unsignedBigInteger('participation_type_id');
            $table->unsignedBigInteger('application_status_id');
            $table->string('contributors', 255)->nullable();
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('presentation_type_id')->references('id')->on('presentation_types')->onDelete('restrict');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('participation_type_id')->references('id')->on('participation_types')->onDelete('restrict');
            $table->foreign('application_status_id')->references('id')->on('application_statuses')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}; 