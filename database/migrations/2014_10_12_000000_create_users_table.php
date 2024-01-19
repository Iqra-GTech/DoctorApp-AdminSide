<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('role_id');
            $table->string('email');
            $table->string('password');
            $table->string('phone_number');
            $table->rememberToken();
            $table->string('verified')->default('0');
            $table->timestamps();
            $table->string('active')->default('1');
            $table->string('created_by');
            $table->string('del')->default('0');
        });

        Schema::create('user_meta', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('role_id');
            $table->string('option');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('module_history', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('module_id');
            $table->string('description');
            $table->string('date');
            $table->string('updated_by');
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
        Schema::dropIfExists('user_meta');
        Schema::dropIfExists('module_history');
    }
}
