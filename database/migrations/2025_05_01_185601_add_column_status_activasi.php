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
        Schema::table('model_q_rcodes', function (Blueprint $table) {
            $table->enum('status_activitas', ['0', '1'])->default('1')->after('qrcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_q_rcodes', function (Blueprint $table) {
            $table->dropColumn('status_activitas');
        });
    }
};
