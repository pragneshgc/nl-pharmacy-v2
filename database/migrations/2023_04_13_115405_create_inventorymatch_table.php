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
        Schema::create('InventoryMatch', function (Blueprint $table) {
            $table->integer('InventoryMatchID', true);
            $table->integer('ParentID')->nullable();
            $table->integer('CountryID')->nullable();
            $table->integer('ManufacturerID')->nullable();
            $table->integer('BarcodeType')->default(1);
            $table->unsignedInteger('ProductCodeID')->nullable()->default(0)->comment('references Product table');
            $table->float('DefaultItemPrice', 10, 0)->nullable()->comment('price per item');
            $table->string('GTIN', 128)->default('0')->comment('regular barcode or productCode (FMD)');
            $table->string('Note', 128)->nullable()->index('Note');
            $table->unsignedInteger('UserID')->nullable();
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
        Schema::dropIfExists('InventoryMatch');
    }
};