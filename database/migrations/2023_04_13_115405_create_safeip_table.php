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
        Schema::create('SafeIP', function (Blueprint $table) {
            $table->integer('SafeIPID', true);
            $table->string('SafeIP', 30)->nullable();
            $table->string('SafeKey', 32)->nullable();
            $table->integer('Status')->nullable();

            $table->index(['SafeIP', 'Status'], 'safeip_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SafeIP');
    }
};