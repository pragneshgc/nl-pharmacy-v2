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
        Schema::create('ProductWarningLabel', function (Blueprint $table) {
            $table->integer('PWLID', true);
            $table->string('WLID', 12)->nullable();
            $table->string('ProductID', 12)->nullable();
            $table->integer('Type')->nullable();
            $table->integer('Status')->nullable()
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
        Schema::dropIfExists('ProductWarningLabel');
    }
};