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
        Schema::create('InventoryHelp', function (Blueprint $table) {
            $table->increments('InventoryHelpID');
            $table->string('Title', 50);
            $table->string('Category', 50);
            $table->text('Description')->nullable();
            $table->string('RelatedPage')->nullable();
            $table->unsignedInteger('CreatedBy');
            $table->unsignedInteger('UpdatedBy')->nullable();
            $table->integer('Type')->default(1)->comment('1 - FAQ');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->useCurrent();
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
        Schema::dropIfExists('InventoryHelp');
    }
};