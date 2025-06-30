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
        Schema::table('rc4', function (Blueprint $table) {
            $table->binary('uuid_rc4')->change(); // ubah tipe kolom 'payload'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rc4', function (Blueprint $table) {
            $table->string('uuid_rc4')->change(); // rollback ke string jika dibutuhkan
        });
    }
};
