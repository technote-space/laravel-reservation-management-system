<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('guest_id')->nullable(false)->comment('利用者ID');
            $table->unsignedBigInteger('room_id')->nullable(false)->comment('部屋ID');
            $table->date('start_date')->nullable(false)->comment('利用開始日');
            $table->date('end_date')->nullable(false)->comment('利用終了日');
            $table->unsignedTinyInteger('number')->nullable(false)->comment('利用人数');
            $table->timestamps();

            $table->foreign('guest_id')->references('id')->on('guests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
