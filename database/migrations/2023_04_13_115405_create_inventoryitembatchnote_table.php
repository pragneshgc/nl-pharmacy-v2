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
        Schema::create('InventoryItemBatchNote', function (Blueprint $table) {
            $table->integer('InventoryItemBatchNoteID', true);
            $table->unsignedInteger('InventoryItemBatchID')->nullable()->index('InventoryItemBatchID');
            $table->unsignedInteger('UserID')->nullable()->comment('Relates to the Inventory user this note was added by');
            $table->integer('Type')->default(1)->comment('1 - Critical, 2 - Medical Information, 3 - Other, 4 - prescription specific');
            $table->text('Note')->nullable();
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
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
        Schema::dropIfExists('InventoryItemBatchNote');
    }
};