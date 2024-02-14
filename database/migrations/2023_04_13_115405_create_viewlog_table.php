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
        Schema::create('ViewLog', function (Blueprint $table) {
            $table->increments('ViewLogID');
            $table->unsignedInteger('UserID')->default(0)->index('UserID');
            $table->string('Page', 50)->nullable()->default('');
            $table->string('IP', 15)->nullable()->default('');
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
        Schema::dropIfExists('ViewLog');
    }
};