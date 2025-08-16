<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rc4', function (Blueprint $table) {
            $table->longText('data_byte')->nullable()->after('key');
            // 'after("key")' supaya kolom baru diletakkan setelah kolom 'key'
        });
    }

    public function down(): void
    {
        Schema::table('rc4', function (Blueprint $table) {
            $table->dropColumn('data_byte');
        });
    }
};
