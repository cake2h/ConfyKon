<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица roles не используется, поэтому удаляем её
        if (Schema::hasTable('roles')) {
            Schema::dropIfExists('roles');
        }
    }

    public function down()
    {
        // Ничего не делаем, так как таблица не должна существовать
    }
}; 