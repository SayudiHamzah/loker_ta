<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai')->constrained('model_pegawais')->onDelete('cascade');
            $table->foreignId('loker')->constrained('model_lokers')->onDelete('cascade');
            $table->date('date');
            $table->time('waktu_penggunaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_logs');
    }
}
