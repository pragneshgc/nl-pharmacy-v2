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
        Schema::create('BlackList', function (Blueprint $table) {
            $table->integer('BlackListID', true);
            $table->string('Name', 100)->nullable();
            $table->string('Surname', 100)->nullable();
            $table->string('DOB', 10)->nullable();
            $table->string('Sex', 1)->nullable();
            $table->string('DAddress1', 50)->nullable();
            $table->string('DAddress2', 50)->nullable();
            $table->string('DAddress3', 50)->nullable();
            $table->string('DAddress4', 50)->nullable();
            $table->string('DPostcode', 50)->nullable();
            $table->string('DCountryCode', 3)->nullable();
            $table->integer('CreatedDate')->nullable();
            $table->integer('UpdatedDate')->nullable();
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');

            $table->index(['Name', 'Surname', 'DOB', 'DPostcode'], 'NameSurnameDOBDPostcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('BlackList');
    }
};