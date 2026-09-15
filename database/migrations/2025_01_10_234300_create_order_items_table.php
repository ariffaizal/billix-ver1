<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_item');
            $table->unsignedBigInteger('id_order');
            $table->unsignedTinyInteger('is_fnb')->default(0);
            $table->unsignedBigInteger('id_fnb')->nullable();
            $table->string('fnb_name', 255)->nullable();
            $table->decimal('fnb_price', 64, 0)->nullable();
            $table->unsignedInteger('fnb_qty')->nullable();
            $table->decimal('fnb_amount', 64, 0)->nullable();
            $table->unsignedTinyInteger('is_table')->default(0);
            $table->unsignedBigInteger('id_table')->nullable();
            $table->string('table_name', 255)->nullable();
            $table->unsignedBigInteger('id_pr_package')->nullable();
            $table->string('package_name', 100)->nullable();
            $table->decimal('package_price', 64, 0)->nullable();
            $table->time('package_time_limit')->nullable();
            $table->unsignedBigInteger('id_pr_openbill')->nullable();
            $table->string('openbill_name', 100)->nullable();
            $table->decimal('openbill_price', 64, 0)->nullable();
            $table->dateTime('openbill_time_start')->nullable();
            $table->unsignedInteger('openbill_totaltime')->nullable();
            $table->decimal('openbill_totalprice', 64, 0)->nullable();
            $table->decimal('items_amount', 64, 0)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
