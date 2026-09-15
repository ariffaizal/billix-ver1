<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom role belum ada sebelum menambahkan
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }

            // Tambahkan kolom is_protected
            if (!Schema::hasColumn('users', 'is_protected')) {
                $table->boolean('is_protected')->default(false)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_protected')) {
                $table->dropColumn('is_protected');
            }
        });
    }
};