<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('guest_id')->nullable(false)->unique()->comment(__('database.guest_details.guest_id'));
            $table->string('name', 128)->nullable(false)->comment(__('database.guest_details.name'));
            $table->string('name_kana', 128)->nullable(false)->comment(__('database.guest_details.name_kana'));
            $table->string('zip_code', 16)->nullable(false)->comment(__('database.guest_details.zip_code'));
            $table->string('address', 128)->nullable(false)->comment(__('database.guest_details.address'));
            $table->string('phone', 16)->nullable(false)->comment(__('database.guest_details.phone'));
            $table->timestamps();

            $table->foreign('guest_id')->references('id')->on('guests')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_details');
    }
}
