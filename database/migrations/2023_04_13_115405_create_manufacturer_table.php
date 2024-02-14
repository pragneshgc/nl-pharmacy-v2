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
        Schema::create('Manufacturer', function (Blueprint $table) {
            $table->increments('ManufacturerID');
            $table->string('Title', 50)->default('Not Set');
            $table->mediumText('Description')->nullable();
            $table->integer('Status')->nullable()->default(1)->index('Status')
                ->comment('0 - inactive, 1 - active');
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
        Schema::dropIfExists('Manufacturer');
    }
};