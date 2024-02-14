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
        Schema::create('CustomLabel', function (Blueprint $table) {
            $table->increments('CustomLabelID');
            $table->string('Title', 50)->nullable();
            $table->text('Description')->nullable();
            $table->unsignedInteger('UserID');
            $table->integer('Type')->default(1)->comment('1 - active');
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
        Schema::dropIfExists('CustomLabel');
    }
};