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
            // Hapus foreign key lama jika ada
            $table->dropForeign(['loker']);

            // Ubah nama kolom 'loker' menjadi 'loker_id'
            $table->renameColumn('loker', 'loker_id');

            // Tambahkan foreign key baru
            $table->foreign('loker_id')->references('id')->on('model_lokers')->onDelete('cascade');
        });

        // Hapus kolom 'date' setelah memastikan tidak ada dependensi
        Schema::table('model_logs', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_logs', function (Blueprint $table) {
            // Tambahkan kembali kolom 'date'
            $table->date('date')->nullable();

            // Hapus foreign key baru
            $table->dropForeign(['loker_id']);

            // Ubah nama kolom 'loker_id' kembali menjadi 'loker'
            $table->renameColumn('loker_id', 'loker');

            // Tambahkan kembali foreign key lama jika diperlukan
            $table->foreign('loker')->references('id')->on('lokers')->onDelete('cascade');
        });
    }
};
