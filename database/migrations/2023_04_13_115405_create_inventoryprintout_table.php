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
        Schema::create('InventoryPrintout', function (Blueprint $table) {
            $table->increments('InventoryPrintoutID');
            $table->unsignedInteger('InventoryItemID')->default(0)->index('InventoryItemID');
            $table->unsignedInteger('UserID')->default(0)->index('UserID');
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InventoryPrintout');
    }
};