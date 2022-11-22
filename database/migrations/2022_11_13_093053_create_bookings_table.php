<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::drop('bookings');
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('surgeon_id');
            $table->unsignedBigInteger('cardiologist_id');
            $table->unsignedBigInteger('dispatcher_id');
            $table->unsignedBigInteger('room_id');
            $table->foreign('surgeon_id')->references('id')->on('users');
            $table->foreign('cardiologist_id')->references('id')->on('users');
            $table->foreign('dispatcher_id')->references('id')->on('users');
            $table->unsignedBigInteger('hospital_id');
            $table->foreign('room_id')->references('id')->on('hospital_rooms');
            $table->unsignedBigInteger('disease_id');
            $table->string('brigade');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->index(['hospital_id', 'room_id', 'start_time'], 'booking_idx');
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
        Schema::dropIfExists('bookings');
    }
};
