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
        Schema::create('InventoryItem', function (Blueprint $table) {
            $table->comment('List of inventory items with product references and barcodes');
            $table->increments('InventoryItemID');
            $table->unsignedInteger('ParentInventoryItemID')->nullable()->index('ParentInventoryItemID');
            $table->unsignedInteger('MergedInventoryItemID')->nullable()->index('MergedInventoryItemID');
            $table->unsignedInteger('BatchID')->default(0)->index('items_batches');
            $table->unsignedInteger('ProductCodeID')->nullable()->index('ProductCodeID')->comment('references Product table');
            $table->unsignedInteger('ProductID')->nullable()->index('ProductID')->comment('Refers to the Product table');
            $table->integer('CountryID')->nullable()->index('CountryID');
            $table->integer('ManufacturerID')->nullable()->index('ManufacturerID');
            $table->float('ItemPrice', 10, 0)->nullable()->comment('price per item');
            $table->float('CurrentItemPrice', 10, 0)->nullable();
            $table->float('OriginalItemPrice', 10, 0)->nullable();
            $table->string('GTIN', 128)->nullable()->index('GTIN')->comment('the GTIN - if not FMD it\'s same as barcode');
            $table->string('FMDExpiryDate', 128)->nullable()->comment('product expiration date (FMD)');
            $table->string('FMDBatchID', 128)->nullable()->index('FMDBatchID')->comment('product batchID (FMD)');
            $table->string('FMDSerialNumber', 128)->nullable()->index('FMDSerialNumber')->comment('individual product serial number (FMD)');
            $table->integer('Quantity')->nullable()->index('Quantity');
            $table->enum('Status', ['SHIPPED', 'STORED', 'MISSING', 'LOST', 'SPLIT', 'ERROR', 'DESTROYED'])->default('STORED')->index('Status')->comment('current status of the item');
            $table->enum('FMDStatus', ['ACTIVE', 'INACTIVE', 'NOT_FMD', 'PROCESSING', 'UNKNOWN'])->default('NOT_FMD')->index('FMDStatus');
            $table->enum('FMDStatusReason', ['SUPPLIED', 'DESTROYED', 'LOCKED', 'EXPORTED', 'SAMPLE', 'STOLEN', 'CHECKED_OUT', 'FREESAMPLE', 'RECALLED', 'EXPIRED', 'WITHDRAWN', 'UNKNOWN'])->default('UNKNOWN');
            $table->string('FMDReturnCode', 128)->nullable();
            $table->string('FMDReturnDesc', 128)->nullable();
            $table->string('Note', 128)->nullable()->index('Note');
            $table->timestamp('CreatedAt')->useCurrent()->comment('when the item was scanned');
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
            $table->timestamp('ProcessedAt')->nullable();
            $table->timestamp('DeletedAt')->nullable();
            $table->string('Barcode', 128)->default('0')->comment('regular barcode or productCode (FMD)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InventoryItem');
    }
};