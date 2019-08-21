<?php

use App\Models\Reservation;
use App\Models\ReservationDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('reservation_id')->nullable(false)->unique()->comment(__('database.reservation_details.reservation_id'));
            $table->unsignedSmallInteger('number')->nullable(true)->comment(__('database.reservation_details.number'));
            $table->unsignedInteger('payment')->nullable(true)->comment(__('database.reservation_details.payment'));
            $table->string('room_name', 128)->comment(__('database.reservation_details.room_name'));
            $table->string('guest_name', 128)->comment(__('database.reservation_details.guest_name'));
            $table->string('guest_name_kana', 128)->comment(__('database.reservation_details.guest_name_kana'));
            $table->string('guest_zip_code', 16)->comment(__('database.reservation_details.guest_zip_code'));
            $table->string('guest_address', 128)->comment(__('database.reservation_details.guest_address'));
            $table->string('guest_phone', 16)->comment(__('database.reservation_details.guest_phone'));
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_details');
    }
}
