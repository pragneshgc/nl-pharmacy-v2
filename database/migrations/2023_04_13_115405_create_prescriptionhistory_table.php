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
        Schema::create('PrescriptionHistory', function (Blueprint $table) {
            $table->integer('PrescriptionHistoryID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->integer('Status')->nullable()->index('StatusTo');
            $table->integer('SubStatus')->nullable();
            $table->integer('UpdatedDate')->nullable();
            $table->integer('UpdatedBy')->nullable()->index('UpdatedBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PrescriptionHistory');
    }
};