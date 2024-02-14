<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User', function (Blueprint $table) {
            $table->integer('UserID', true);
            $table->string('Title', 50)->nullable();
            $table->string('Name', 100)->nullable();
            $table->string('Surname', 100)->nullable();
            $table->string('Email', 100)->nullable();
            $table->string('Password', 50)->nullable();
            $table->integer('CreatedDate')->nullable();
            $table->integer('ModifiedDate')->nullable();
            $table->integer('LastLogin')->nullable();
            $table->string('IP', 16)->nullable();
            $table->integer('Status')->nullable();
            $table->string('Username', 100)->nullable();
            $table->integer('Admin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('User');
    }
};