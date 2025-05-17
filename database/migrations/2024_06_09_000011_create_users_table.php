<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('surname', 255);
            $table->string('name', 255);
            $table->string('patronymic', 255)->nullable();
            $table->date('birthday');
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->string('phone_number', 20)->nullable();
            $table->boolean('consent_to_mailing')->default(false);
            $table->unsignedBigInteger('education_level_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('study_place_id');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
            $table->foreign('study_place_id')->references('id')->on('study_places')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 