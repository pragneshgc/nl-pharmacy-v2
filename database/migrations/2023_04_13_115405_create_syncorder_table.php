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
        Schema::create('SyncOrder', function (Blueprint $table) {
            $table->increments('SyncOrderID');
            $table->unsignedInteger('ClientID')->default(0);
            $table->string('Value', 50)->default('0');
            $table->integer('Type')->default(1)->comment('1 - reference number, 2 - number of orders');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SyncOrder');
    }
};