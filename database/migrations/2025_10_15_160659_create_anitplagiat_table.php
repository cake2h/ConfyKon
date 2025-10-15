<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antiplagiat_reports', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->index()->comment('ID документа в системе Антиплагиат');
            $table->unsignedBigInteger('user_id')->index()->comment('Пользователь, выполнивший проверку');
            $table->string('title')->nullable()->comment('Название документа');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antiplagiat_reports');
    }
};
