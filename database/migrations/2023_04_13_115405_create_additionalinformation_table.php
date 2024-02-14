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
        Schema::create('AdditionalInformation', function (Blueprint $table) {
            $table->integer('AIID', true);
            $table->string('Name', 100)->nullable();
            $table->mediumText('Description')->nullable();
            $table->integer('Type')->nullable();
            $table->integer('Status')->nullable();
            $table->integer('CountryID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AdditionalInformation');
    }
};