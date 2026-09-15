<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id('id_store');
            $table->string('store_name', 255);
            $table->text('store_desc')->nullable();
            $table->string('store_address_1', 255)->nullable();
            $table->string('store_address_2', 255)->nullable();
            $table->string('store_phone', 100)->nullable();
            $table->string('store_email', 255)->nullable();
            $table->string('store_logo', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store');
    }
};
