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
            $table->unsignedBigInteger('guest_id')->nullable(false)->unique()->comment('利用者ID');
            $table->string('name', 128)->nullable(false)->comment('名');
            $table->string('name_kana', 128)->nullable(false)->comment('カナ名');
            $table->string('zip_code', 16)->nullable(false)->comment('郵便番号');
            $table->string('address', 128)->nullable(false)->comment('住所');
            $table->string('phone', 16)->nullable(false)->comment('電話番号');
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
