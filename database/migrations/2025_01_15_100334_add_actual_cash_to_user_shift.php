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
            if (!Schema::hasColumn('user_shift', 'cash_actual')) {
                $table->decimal('cash_actual', 64, 0)->nullable()->default(0);
            }

            if (!Schema::hasColumn('user_shift', 'cash_out')) {
                $table->decimal('cash_out', 64, 0)->nullable()->default(0);
            }

            if (!Schema::hasColumn('user_shift', 'cash_out_info')) {
                $table->text('cash_out_info')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_shift', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('user_shift', 'cash_actual') ? 'cash_actual' : null,
                Schema::hasColumn('user_shift', 'cash_out') ? 'cash_out' : null,
                Schema::hasColumn('user_shift', 'cash_out_info') ? 'cash_out_info' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
