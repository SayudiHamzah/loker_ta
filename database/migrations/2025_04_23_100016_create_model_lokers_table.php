<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_lokers', function (Blueprint $table) {
            $table->id();
            $table->string('name_locker');
            $table->string('status');
            $table->unsignedBigInteger('qrcode')->nullable(); // Harus sama dengan tipe id di m_qrcode
            $table->timestamps();

            $table->foreign('qrcode')->references('id')->on('model_q_rcodes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_lokers');
    }
}
