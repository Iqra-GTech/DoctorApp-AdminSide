<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_manager', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('module_icon')->nullable();
            $table->string('created_by');
            $table->string('active')->default('1');
            $table->timestamps();
            $table->string('table_name');
            $table->string('role_id');

        }); 

        Schema::create('module_mata', function (Blueprint $table) {
            $table->id();
            $table->string('module_id');
            $table->string('type');
            $table->string('option');
            $table->text('value')->nullable();
            $table->string('required')->default('0');
            $table->string('dependency');
            $table->timestamps();
            $table->string('import_option')->default('0');
            $table->text('comma_separated_values')->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('module_manager');
        Schema::dropIfExists('module_meta');
    }
}
