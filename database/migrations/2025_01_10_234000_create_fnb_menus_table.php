<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fnb_menus', function (Blueprint $table) {
            $table->id('id_fnb');
            $table->string('fnb_name', 255);
            $table->decimal('fnb_price', 64, 0);
            $table->text('fnb_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fnb_menus');
    }
};
