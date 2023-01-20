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
        Schema::create('todays', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('surgeon_id')->nullable();
            $table->unsignedBigInteger('cardiologist_id')->nullable();
            $table->foreign('surgeon_id', 'todays_surgeon_fk')->references('id')->on('users');
            $table->foreign('cardiologist_id', 'todays_cardiologist_fk')->references('id')->on('users');
            $table->foreign('hospital_id', 'todays_hospital_fk')->references('id')->on('hospitals');
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
        Schema::dropIfExists('todays');
    }
};
