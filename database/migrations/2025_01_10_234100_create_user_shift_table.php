<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('user_shift', function (Blueprint $table) {
        $table->id('id_user_shift');
        $table->unsignedBigInteger('id_user')->nullable();
        $table->dateTime('shift_start');
        $table->dateTime('shift_end')->nullable();
        $table->decimal('initial_capital', 64, 0)->default(0);
        
        // Tambahkan kolom penampung transaksi closing shift di bawah ini
        $table->decimal('cash_actual', 64, 0)->nullable()->default(0);
        $table->decimal('cash_out', 64, 0)->nullable()->default(0);
        $table->text('cash_out_info')->nullable();
        
        $table->text('shift_info')->nullable();
        $table->unsignedTinyInteger('shift_active')->default(1);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('user_shift');
    }
};
