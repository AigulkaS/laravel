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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('hospital_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('patronymic')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('push')->default(0);
            $table->boolean('sms')->default(0);

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
        Schema::dropIfExists('users');
    }
};
