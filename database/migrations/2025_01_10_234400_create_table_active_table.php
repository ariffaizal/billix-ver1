<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_active', function (Blueprint $table) {
            $table->id('id_table_active');
            $table->unsignedBigInteger('id_order')->nullable();
            $table->unsignedBigInteger('id_table');
            $table->unsignedTinyInteger('is_openbill')->default(0);
            $table->dateTime('time_start')->nullable();
            $table->time('time_limit')->nullable();
            $table->dateTime('time_end')->nullable();
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->unsignedTinyInteger('is_started')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_active');
    }
};
