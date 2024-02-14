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
        Schema::create('InventoryBarcode', function (Blueprint $table) {
            $table->integer('InventoryBarcodeID', true);
            $table->string('Title')->nullable();
            $table->string('GTIN', 128)->default('0')->comment('regular barcode or productCode (FMD)');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
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
        Schema::dropIfExists('InventoryBarcode');
    }
};