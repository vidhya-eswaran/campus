<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::connection('central')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('school_id')->nullable(); // FK to schools table
            $table->enum('role', ['super_admin', 'school_admin'])->default('school_admin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('central')->dropIfExists('users');
    }
}

