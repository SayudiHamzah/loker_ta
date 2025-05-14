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
        Schema::table('model_lokers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('qrcode_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // Bisa diganti 'cascade' jika ingin otomatis terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_lokers', function (Blueprint $table) {
            Schema::table('lockers', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        });
    }
};
