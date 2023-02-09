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
        Schema::create('permission_roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->index('permission_id', 'permission_role_permission_idx');
            $table->index('role_id', 'permission_role_role_idx');

            $table->foreign('permission_id', 'permission_role_permission_fk')->on('permissions')->references('id');
            $table->foreign('role_id', 'permission_role_role_fk')->on('roles')->references('id');

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
        Schema::dropIfExists('permission_roles');
    }
};
