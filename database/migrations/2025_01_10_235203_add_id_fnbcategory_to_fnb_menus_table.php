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
        Schema::table('fnb_menus', function (Blueprint $table) {
            $table->bigInteger('id_fnbcategory', false, true)->after('id_fnb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fnb_menus', function (Blueprint $table) {
            $table->dropColumn('id_fnbcategory');
        });
    }
};
