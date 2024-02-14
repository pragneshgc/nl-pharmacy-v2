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
        Schema::table('InventoryItem', function (Blueprint $table) {
            $table->foreign(['BatchID'], 'InventoryItem_ibfk_1')->references(['InventoryItemBatchID'])
                ->on('InventoryItemBatch')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('InventoryItem', function (Blueprint $table) {
            $table->dropForeign('InventoryItem_ibfk_1');
        });
    }
};