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

        Schema::table('model_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('qrcode_id')->nullable()->after('loker_id');

            // Tambahkan foreign key
            $table->foreign('qrcode_id')
                ->references('id')
                ->on('model_q_rcodes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_logs', function (Blueprint $table) {
            $table->dropForeign(['qrcode_id']);
            $table->dropColumn('qrcode_id');
        });
    }
};
