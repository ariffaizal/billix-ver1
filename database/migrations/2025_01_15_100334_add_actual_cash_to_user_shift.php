<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_shift', function (Blueprint $table) {
            $table->after('initial_capital', function (Blueprint $table) {
                $table->decimal('cash_actual', total: 64, places: 0)->default(0);
                $table->decimal('cash_out', total: 64, places: 0)->default(0);
                $table->text('cash_out_info');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_shift', function (Blueprint $table) {
            //
        });
    }
};
