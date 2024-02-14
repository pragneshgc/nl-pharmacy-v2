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
        Schema::create('ProductInstruction', function (Blueprint $table) {
            $table->integer('PILID', true);
            $table->string('InstructionID', 12)->nullable();
            $table->string('ProductID', 12)->nullable();
            $table->integer('Type')->nullable();
            $table->integer('Status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ProductInstruction');
    }
};
