<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 128)->nullable(false)->comment(__('database.admins.name'));
            $table->string('email', 128)->nullable(false)->unique()->comment(__('database.admins.email'));
            $table->timestamp('email_verified_at')->nullable()->comment(__('database.admins.email_verified_at'));
            $table->string('password')->nullable(false)->comment(__('database.admins.password'));
            $table->rememberToken()->comment(__('database.admins.remember_token'));
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
        Schema::dropIfExists('admins');
    }
}
