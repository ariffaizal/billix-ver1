<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices_discounts', function (Blueprint $table) {
            $table->id('id_pr_discount');
            $table->string('name', 100);
            $table->decimal('price', 64, 0);
            $table->unsignedTinyInteger('member_only')->default(0);
            $table->timestamps();
        });

        Schema::create('prices_packages', function (Blueprint $table) {
            $table->id('id_pr_package');
            $table->string('name', 100);
            $table->decimal('price', 64, 0);
            $table->time('time_limit');
            $table->timestamps();
        });

        Schema::create('prices_openbills', function (Blueprint $table) {
            $table->id('id_pr_openbill');
            $table->string('name', 100);
            $table->decimal('price', 64, 0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices_openbills');
        Schema::dropIfExists('prices_packages');
        Schema::dropIfExists('prices_discounts');
    }
};
