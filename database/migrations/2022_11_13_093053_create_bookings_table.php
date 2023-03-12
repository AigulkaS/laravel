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
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('disease_id')->nullable();
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->timestamp('date_time');
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->foreign('room_id')->references('id')->on('hospital_rooms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('disease_id')->references('id')->on('diseases');
            $table->foreign('condition_id')->references('id')->on('patient_conditions');
            $table->index(['hospital_id', 'room_id', 'date_time'], 'booking_idx');
            $table->softDeletes();
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
