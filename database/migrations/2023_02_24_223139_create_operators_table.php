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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('surgeon_id')->nullable();
            $table->unsignedBigInteger('cardiologist_id')->nullable();
            $table->foreign('surgeon_id', 'operators_surgeon_fk')->references('id')->on('users');
            $table->foreign('cardiologist_id', 'operators_cardiologist_fk')->references('id')->on('users');
            $table->foreign('hospital_id', 'operators_hospital_fk')->references('id')->on('hospitals');
            $table->index(['id', 'hospital_id'], 'operator_idx');
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
        Schema::dropIfExists('operators');
    }
};
