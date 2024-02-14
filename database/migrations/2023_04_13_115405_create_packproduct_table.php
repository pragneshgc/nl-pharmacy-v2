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
        Schema::create('PackProduct', function (Blueprint $table) {
            $table->integer('PackProductID', true);
            $table->integer('Order')->default(0);
            $table->string('Code', 20)->nullable();
            $table->text('Description')->nullable();
            $table->string('Quantity', 50)->nullable();
            $table->string('Dosage', 50)->nullable();
            $table->string('Unit', 50)->nullable();
            $table->string('ProductCode', 20)->nullable();
            $table->integer('Instruction')->nullable()
                ->comment('0 - don not print instructions, 1 - print instructions');
            $table->integer('Status')->nullable()->default(1)
                ->comment('0 - inactive, 1 - active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PackProduct');
    }
};