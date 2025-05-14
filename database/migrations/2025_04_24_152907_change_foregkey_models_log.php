<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('model_logs', function (Blueprint $table) {
            $table->dropForeign(['pegawai']);
            $table->renameColumn('pegawai', 'user_id');
        });

        Schema::table('model_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('model_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'pegawai');
        });

        Schema::table('model_logs', function (Blueprint $table) {
            $table->foreign('pegawai')->references('id')->on('model_pegawais')->onDelete('cascade');
        });
    }
};
