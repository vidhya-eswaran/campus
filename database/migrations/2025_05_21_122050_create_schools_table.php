<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return vo
     */
    public function up()
    {
        Schema::connection('mysql')->create('schools', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // 'schoolA'
        $table->string('db_name');
        $table->string('db_username');
        $table->string('db_password');
        $table->string('db_host')->default('127.0.0.1');
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
        Schema::dropIfExists('schools');
    }
}
