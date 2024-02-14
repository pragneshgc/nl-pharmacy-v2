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
        Schema::create('InventoryMessage', function (Blueprint $table) {
            $table->increments('InventoryMessageID');
            $table->string('Title', 50);
            $table->string('Category', 50);
            $table->text('Description')->nullable();
            $table->text('SeenBy')->nullable();
            $table->integer('Type')->default(1)->comment('1 - FAQ');
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
        Schema::dropIfExists('InventoryMessage');
    }
};