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
            $table->foreignId('role_id')->constrained();
            $table->foreignId('hospital_id')->constrained();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('patronymic')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('push')->default(0);
            $table->boolean('sms')->default(0);

            $table->softDeletes();

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
