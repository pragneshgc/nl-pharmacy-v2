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
        Schema::create('Product', function (Blueprint $table) {
            $table->integer('ProductID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->string('GUID', 50)->nullable();
            $table->string('Code', 100)->nullable()->index('code');
            $table->text('Description')->nullable();
            $table->text('Instructions')->nullable();
            $table->text('Instructions2')->nullable();
            $table->string('Quantity', 50)->nullable();
            $table->string('Unit', 50)->nullable();
            $table->integer('Dosage')->nullable();

            $table->index(['Code', 'PrescriptionID'], 'CodePrescriptionID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Product');
    }
};