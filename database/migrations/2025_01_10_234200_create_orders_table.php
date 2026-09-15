<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->string('order_type', 50);
            $table->dateTime('order_time')->nullable();
            $table->unsignedTinyInteger('order_status')->default(0);
            $table->dateTime('cancel_time')->nullable();
            $table->string('bill_name', 255)->nullable();
            $table->string('pay_method', 50)->nullable();
            $table->decimal('price_subtotal', 64, 0)->default(0);
            $table->decimal('price_discount', 64, 0)->default(0);
            $table->decimal('price_total', 64, 0)->default(0);
            $table->decimal('cash_tendered', 64, 0)->default(0);
            $table->decimal('cash_change', 64, 0)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('id_user_shift')->nullable();
            $table->unsignedBigInteger('id_member')->nullable();
            $table->string('member_name', 255)->nullable();
            $table->string('member_no', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
