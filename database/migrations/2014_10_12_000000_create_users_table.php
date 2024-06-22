<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birthday')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('password');

            $table->string('diplom_path')->nullable();

            $table->string('city')->nullable();
            $table->string('study_place')->nullable();

            $table->string('role')->nullable()->default('user');
            $table->foreignId('edu_id')->nullable()->constrained('education_levels');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
