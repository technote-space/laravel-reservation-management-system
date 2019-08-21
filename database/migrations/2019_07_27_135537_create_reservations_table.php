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
            $table->unsignedBigInteger('guest_id')->nullable(false)->comment(__('database.reservations.guest_id'));
            $table->unsignedBigInteger('room_id')->nullable(false)->comment(__('database.reservations.room_id'));
            $table->date('start_date')->nullable(false)->comment(__('database.reservations.start_date'));
            $table->date('end_date')->nullable(false)->comment(__('database.reservations.end_date'));
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
