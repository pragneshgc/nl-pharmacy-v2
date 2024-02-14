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
        Schema::create('InventoryItemBatch', function (Blueprint $table) {
            $table->increments('InventoryItemBatchID');
            $table->unsignedInteger('UserID')->nullable()->index('batches_users');
            $table->unsignedInteger('AuthorizerID')->nullable()->index('AuthorizerID');
            $table->unsignedInteger('SupplierID')->nullable()->index('SupplierID');
            $table->enum('Status', ['FINISHED', 'INCOMPLETE', 'HISTORY'])
                ->default('INCOMPLETE')->index('Status')->comment('current status of the batch scan');
            $table->integer('Quantity')->nullable()->default(0)->comment('count of the batch at the time of import');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InventoryItemBatch');
    }
};