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
        Schema::create('Instruction', function (Blueprint $table) {
            $table->integer('InstructionID', true);
            $table->string('Name', 100)->nullable();
            $table->text('Description')->nullable();
            $table->integer('Type')->default(1);
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');
            $table->integer('Lang')->nullable()
                ->comment('corresponds to the CountryID in Country table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Instruction');
    }
};