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
        Schema::create('InventoryItemBatchLog', function (Blueprint $table) {
            $table->increments('InventoryItemBatchLogID');
            $table->integer('InventoryItemBatchID')->default(0)->index('InventoryItemBatchID');
            $table->unsignedInteger('UserID')->nullable()->index('UserID');
            $table->unsignedInteger('ProductCodeID')->nullable()->index('ProductCodeID')->comment('references Product table');
            $table->string('Name', 128)->nullable();
            $table->string('GTIN', 128)->nullable()->comment('the GTIN - if not FMD it\'s same as barcode');
            $table->string('EnteredBy', 128)->nullable();
            $table->string('FMDStatus', 128)->nullable();
            $table->unsignedInteger('Quantity')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->timestamp('DeletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InventoryItemBatchLog');
    }
};